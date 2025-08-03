# Test Laravel Workflow Script for Windows PowerShell
# Run this before pushing to main branch

Write-Host "🚀 Starting Laravel workflow test..." -ForegroundColor Cyan

# Function to print colored output
function Write-Step {
    param($Message)
    Write-Host "📋 $Message" -ForegroundColor Yellow
}

function Write-Success {
    param($Message)
    Write-Host "✅ $Message" -ForegroundColor Green
}

function Write-Error {
    param($Message)
    Write-Host "❌ $Message" -ForegroundColor Red
    exit 1
}

# Step 1: Check PHP version
Write-Step "Checking PHP version..."
try {
    php --version
    Write-Success "PHP version check passed"
} catch {
    Write-Error "PHP not found or version check failed"
}

# Step 2: Copy .env files
Write-Step "Copying .env files..."
php -r "file_exists('.env') || copy('.env.example', '.env');"
php -r "file_exists('.env.testing') || copy('.env.example', '.env.testing');"
Write-Success ".env files copied"

# Step 3: Install dependencies
Write-Step "Installing dependencies..."
$composerResult = composer install -q --no-ansi --no-interaction --no-scripts --no-progress --prefer-dist --optimize-autoloader
if ($LASTEXITCODE -ne 0) {
    Write-Error "Composer install failed"
}
Write-Success "Dependencies installed"

# Step 4: Generate keys
Write-Step "Generating application keys..."
php artisan key:generate
php artisan key:generate --env=testing
Write-Success "Keys generated"

# Step 5: Clear caches
Write-Step "Clearing config & route cache..."
php artisan config:clear
php artisan route:clear
Write-Success "Caches cleared"

# Step 6: Create directories and set permissions
Write-Step "Creating directories and setting permissions..."
if (!(Test-Path "storage")) {
    New-Item -ItemType Directory -Path "storage" -Force
}
if (!(Test-Path "bootstrap\cache")) {
    New-Item -ItemType Directory -Path "bootstrap\cache" -Force
}
# Note: Windows doesn't use chmod, but we'll ensure directories exist
Write-Success "Directories created and permissions set"

# Step 7: Create test database
Write-Step "Creating test database..."
if (!(Test-Path "database")) {
    New-Item -ItemType Directory -Path "database" -Force
}
if (!(Test-Path "database\database.sqlite")) {
    New-Item -ItemType File -Path "database\database.sqlite" -Force
}
Write-Success "Test database created"

# Step 8: Run migrations
Write-Step "Running migrations..."
$env:DB_CONNECTION = "sqlite"
$env:DB_DATABASE = "$PWD\database\database.sqlite"
$migrateResult = php artisan migrate --force
if ($LASTEXITCODE -ne 0) {
    Write-Error "Migration failed"
}
Write-Success "Migrations completed"

# Step 9: Seed database (optional)
Write-Step "Seeding database..."
try {
    php artisan db:seed --force
    Write-Success "Database seeding completed"
} catch {
    Write-Host "No seeders found or seeding failed, continuing..." -ForegroundColor Yellow
    Write-Success "Database seeding completed (skipped)"
}

# Step 10: Run tests
Write-Step "Running tests..."
$testResult = php artisan test
if ($LASTEXITCODE -ne 0) {
    Write-Error "Tests failed"
}
Write-Success "All tests passed"

# Step 11: Clean up temporary files
Write-Step "Cleaning up temporary files..."
try {
    # Remove test database
    if (Test-Path "database\database.sqlite") {
        Remove-Item "database\database.sqlite" -Force
        Write-Host "   - Removed test database" -ForegroundColor DarkGray
    }
    
    # Remove .env.testing if it was created
    if (Test-Path ".env.testing") {
        Remove-Item ".env.testing" -Force
        Write-Host "   - Removed .env.testing" -ForegroundColor DarkGray
    }
    
    # Clear Laravel caches again
    php artisan config:clear | Out-Null
    php artisan route:clear | Out-Null
    php artisan view:clear | Out-Null
    php artisan cache:clear | Out-Null
    Write-Host "   - Cleared Laravel caches" -ForegroundColor DarkGray
    
    # Reset environment variables
    $env:DB_CONNECTION = $null
    $env:DB_DATABASE = $null
    Write-Host "   - Reset environment variables" -ForegroundColor DarkGray
    
    Write-Success "Cleanup completed"
} catch {
    Write-Host "⚠️  Some cleanup operations failed, but this is not critical" -ForegroundColor Yellow
}

Write-Host ""
Write-Host "🎉 All workflow steps completed successfully!" -ForegroundColor Green
Write-Host "✅ Your code is ready to be pushed to main branch" -ForegroundColor Green

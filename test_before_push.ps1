# PowerShell script to test locally before pushing to GitHub
# Run: .\test_before_push.ps1

Write-Host "🚀 Testing Laravel application before push..." -ForegroundColor Green

# Set testing environment
$env:APP_ENV = "testing"

Write-Host "📋 Step 1: Installing dependencies..." -ForegroundColor Blue
composer install --no-interaction --prefer-dist --optimize-autoloader

Write-Host "🔑 Step 2: Setting up environment..." -ForegroundColor Blue
if (!(Test-Path ".env.testing")) {
    Copy-Item ".env.example" ".env.testing"
}
php artisan key:generate --env=testing

Write-Host "🗄️  Step 3: Setting up database..." -ForegroundColor Blue
if (!(Test-Path "database")) {
    New-Item -ItemType Directory -Path "database"
}
if (!(Test-Path "database/database.sqlite")) {
    New-Item -ItemType File -Path "database/database.sqlite"
}

Write-Host "📦 Step 4: Running migrations..." -ForegroundColor Blue
php artisan migrate:fresh --env=testing --force

Write-Host "🌱 Step 5: Running seeders (optional)..." -ForegroundColor Blue
try {
    php artisan db:seed --env=testing --force
} catch {
    Write-Host "No seeders found or seeding failed, continuing..." -ForegroundColor Yellow
}

Write-Host "🧪 Step 6: Running tests..." -ForegroundColor Blue
php artisan test --env=testing

Write-Host "✅ All tests completed!" -ForegroundColor Green
Write-Host "📤 Ready to push to GitHub!" -ForegroundColor Green

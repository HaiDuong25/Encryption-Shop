#!/bin/bash

# Script to test locally before pushing to GitHub
# Run: bash test_before_push.sh

echo "🚀 Testing Laravel application before push..."

# Set testing environment
export APP_ENV=testing

echo "📋 Step 1: Installing dependencies..."
composer install --no-interaction --prefer-dist --optimize-autoloader

echo "🔑 Step 2: Setting up environment..."
cp .env.example .env.testing 2>/dev/null || echo ".env.testing already exists"
php artisan key:generate --env=testing

echo "🗄️  Step 3: Setting up database..."
touch database/database.sqlite 2>/dev/null || echo "SQLite file already exists"

echo "📦 Step 4: Running migrations..."
php artisan migrate:fresh --env=testing --force

echo "🌱 Step 5: Running seeders (optional)..."
php artisan db:seed --env=testing --force || echo "No seeders found or seeding failed, continuing..."

echo "🧪 Step 6: Running tests..."
php artisan test --env=testing

echo "✅ All tests completed!"
echo "📤 Ready to push to GitHub!"

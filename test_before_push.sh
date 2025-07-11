#!/bin/bash

# Script techo "Step 4: Running migrations..."
# Chỉ chạy migrate (không fresh để tránh xóa data)
php artisan migrate --env=testing --forcetest locally before pushing to GitHub
# Run: bash test_before_push.sh

echo "Testing Laravel application before push..."

# Set testing environment
export APP_ENV=testing

echo "Step 1: Installing dependencies..."
composer install --no-interaction --prefer-dist --optimize-autoloader

echo "Step 2: Setting up environment..."
cp .env.example .env.testing 2>/dev/null || echo ".env.testing already exists"
# Cập nhật database config cho testing - sử dụng SQLite riêng
sed -i 's/DB_CONNECTION=.*/DB_CONNECTION=sqlite/' .env.testing
sed -i "s|DB_DATABASE=.*|DB_DATABASE=$(pwd)/database/test_database.sqlite|" .env.testing
php artisan key:generate --env=testing

echo "Step 3: Setting up test database..."
touch database/test_database.sqlite 2>/dev/null || echo "Test SQLite file already exists"

echo "Step 4: Running migrations..."
php artisan migrate:fresh --env=testing --force

echo "Step 5: Running seeders (optional)..."
php artisan db:seed --env=testing --force || echo "No seeders found or seeding failed, continuing..."

echo "Step 6: Running tests..."
php artisan test --env=testing

echo "Cleaning up test files..."
rm -f .env.testing
rm -f database/test_database.sqlite
# Chỉ xóa các file test tạm thời, KHÔNG xóa migration/seeder
rm -f test_demo*.php
rm -f test_logic*.php
rm -f test_*.html
rm -f demo_*.php

echo "All tests completed!"
echo "Ready to push to GitHub!"

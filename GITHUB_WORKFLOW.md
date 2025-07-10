# GitHub Actions CI/CD Workflow

## Mô tả
Workflow tự động test Laravel application mỗi khi có push hoặc pull request vào branch `main`.

## Các bước được thực hiện

### 1. Setup môi trường
- PHP 8.3 với các extensions cần thiết
- Checkout code từ repository
- Cache Composer dependencies

### 2. Cài đặt dependencies
- Install Composer packages với optimization
- Copy file .env từ .env.example
- Generate application key

### 3. Setup database
- Tạo SQLite database file
- Chạy migrations để tạo bảng
- Chạy seeders (nếu có)

### 4. Chạy tests
- Execute PHPUnit/Pest tests
- Kiểm tra database connection
- Test các tính năng cơ bản

## Files cấu hình

### `.github/workflows/laravel.yml`
Workflow chính cho GitHub Actions

### `.env.testing`
Environment configuration cho testing:
- Sử dụng SQLite in-memory database
- Cache driver: array
- Session driver: array
- Mail driver: array

### `tests/TestCase.php`
Base test class với:
- RefreshDatabase trait
- Automatic migration setup

## Troubleshooting

### Lỗi "no such table: products"
**Nguyên nhân:** Migration chưa được chạy trước khi test

**Giải pháp:** Workflow đã được cập nhật để:
1. Tạo database file
2. Chạy `php artisan migrate --force`
3. Chạy seeders (optional)
4. Mới chạy tests

### Test local trước khi push
```bash
# Linux/macOS
bash test_before_push.sh

# Windows PowerShell
.\test_before_push.ps1
```

## Cải tiến đã thực hiện

1. **Database Setup:** Thêm bước migrate trước khi test
2. **Environment:** Tạo .env.testing riêng biệt
3. **Caching:** Cache Composer dependencies để tăng tốc
4. **Extensions:** Thêm PHP extensions cần thiết
5. **Error Handling:** Xử lý lỗi seeding gracefully
6. **Local Testing:** Scripts để test local

## Performance optimizations

- Composer cache để giảm thời gian install
- Optimize autoloader
- SQLite in-memory cho test nhanh hơn
- Parallel job execution (có thể mở rộng sau)

## Best practices

1. Always run tests locally before pushing
2. Keep .env.testing up to date
3. Use RefreshDatabase in feature tests
4. Mock external services in tests
5. Keep test database lightweight

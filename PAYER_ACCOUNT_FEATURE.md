# Tính năng lưu thông tin tài khoản người thanh toán

## Tổng quan
Tính năng này cho phép lưu trữ và hiển thị thông tin tài khoản của người thực hiện thanh toán online qua MoMo và ZaloPay.

## Các thông tin được lưu trữ

### 1. Trường mới trong bảng `payments`:
- `payer_account` (string, nullable) - Số tài khoản/SĐT người thanh toán
- `payer_name` (string, nullable) - Tên người thanh toán (nếu có)
- `payment_method_type` (string, nullable) - Loại ví: MoMo, ZaloPay, etc.

### 2. Thông tin được thu thập:

#### **MoMo:**
- `payer_account`: Parse từ extraData hoặc lấy 6 ký tự cuối của transId
- `payer_name`: Parse từ extraData (thường null vì bảo mật)
- `payment_method_type`: "MoMo"
- `transaction_code`: Mã giao dịch MoMo

#### **ZaloPay:**
- `payer_account`: Parse từ app_user hoặc lấy 8 ký tự cuối của apptransid
- `payer_name`: Parse từ embed_data (thường null vì bảo mật)
- `payment_method_type`: "ZaloPay"
- `transaction_code`: Mã giao dịch ZaloPay

## Hiển thị thông tin

### 1. Trang quản lý thanh toán (`/admin/payments`)
- Thêm cột "Người thanh toán" hiển thị:
  - Loại ví (badge màu xanh)
  - Số tài khoản/identifier
  - Tên người thanh toán (nếu có)
  - 8 ký tự cuối mã giao dịch

### 2. Trang chi tiết đơn hàng (`/admin/orders/{id}`)
- Thêm section "Thông tin thanh toán" bao gồm:
  - Loại ví điện tử
  - Tài khoản thanh toán
  - Tên người thanh toán
  - Mã giao dịch đầy đủ

## Cách sử dụng

### 1. Khi khách hàng thanh toán:
- Hệ thống tự động lưu thông tin từ response của MoMo/ZaloPay
- Thông tin được parse và lưu vào database

### 2. Khi admin xem:
- Vào trang quản lý thanh toán để xem tổng quan
- Vào chi tiết đơn hàng để xem thông tin đầy đủ

## Lưu ý bảo mật

### 1. Thông tin được ẩn:
- Số tài khoản/SĐT được rút gọn để bảo mật
- Tên người dùng thường không có do chính sách bảo mật của ví

### 2. Thông tin công khai:
- Mã giao dịch: Có thể hiển thị để đối soát
- Loại ví: Thông tin công khai
- Thời gian giao dịch: Thông tin công khai

## Migration cần chạy

```bash
php artisan migrate
```

Lệnh này sẽ thêm 3 trường mới vào bảng `payments`:
- `payer_account`
- `payer_name` 
- `payment_method_type`

## Testing

### 1. Test MoMo:
- Thực hiện thanh toán qua MoMo
- Kiểm tra trang quản lý thanh toán
- Xác nhận thông tin được lưu đúng

### 2. Test ZaloPay:
- Thực hiện thanh toán qua ZaloPay
- Kiểm tra trang quản lý thanh toán
- Xác nhận thông tin được lưu đúng

### 3. Test hiển thị:
- Kiểm tra cột mới trong bảng payments
- Kiểm tra section mới trong chi tiết đơn hàng
- Xác nhận responsive trên mobile

## Troubleshooting

### 1. Không có thông tin người thanh toán:
- Kiểm tra response từ MoMo/ZaloPay trong logs
- Xác nhận các helper method hoạt động đúng
- Kiểm tra migration đã chạy

### 2. Lỗi hiển thị:
- Xác nhận view đã được cập nhật
- Kiểm tra relationship trong Model
- Clear cache nếu cần: `php artisan view:clear`

### 3. Migration lỗi:
- Kiểm tra kết nối database
- Chạy `php artisan migrate:status`
- Rollback và chạy lại nếu cần

## Cải tiến tương lai

1. **Thêm thông tin chi tiết hơn** từ response của payment gateway
2. **Tích hợp thống kê** số lượng thanh toán theo từng ví
3. **Export báo cáo** thanh toán theo ví điện tử
4. **Tích hợp webhook** để cập nhật real-time

---
**Cập nhật**: `{{ date('d/m/Y H:i') }}`

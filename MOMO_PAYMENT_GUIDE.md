# Hướng dẫn test thanh toán MoMo

## Đã hoàn thành:

1. ✅ **MoMoController** - Xử lý thanh toán MoMo với test credentials
2. ✅ **CartController** - Cập nhật để detect và chuyển hướng thanh toán MoMo  
3. ✅ **Routes** - Thêm routes cho MoMo payment
4. ✅ **Order Model** - Thêm các field cần thiết
5. ✅ **Migration** - Thêm payment_status, transaction_id, total, discount, shipping_address_id
6. ✅ **Success Page** - Hiển thị kết quả đặt hàng

## Test credentials MoMo (Sandbox):
- **Partner Code**: MOMOBKUN20180529
- **Access Key**: klm05TvNBzhg7h7j  
- **Secret Key**: at67qH6mk8w5Y1nAyMoYKMWACiEi2bsa
- **Endpoint**: https://test-payment.momo.vn/v2/gateway/api/create
- **Request Type**: captureWallet

## Cách test:
**Cần phải có tài khoản MOMO UAT để thanh toán**

1. **Thêm sản phẩm vào giỏ hàng**
2. **Vào trang checkout** (`/checkout`)
3. **Chọn địa chỉ giao hàng**
4. **Chọn phương thức thanh toán "Ví Điện Tử MOMO"** 
5. **Click "Đặt hàng ngay"**
6. **Hệ thống sẽ redirect đến MoMo payment page**
7. **Trong môi trường test, chọn "Thanh toán thành công"**
8. **Sẽ được redirect về trang success với thông tin đơn hàng**

## Flow xử lý:

1. `CartController::processCheckout()` - Detect MoMo payment method
2. Lưu order data vào session với đầy đủ thông tin (subtotal, discount, coupon)
3. Redirect đến `MoMoController::createPayment()`
4. Tạo signature theo format chuẩn MoMo với requestType="captureWallet"
5. Call MoMo API tạo payment URL
6. Redirect user đến MoMo payment page
7. User thanh toán trên MoMo (test environment)
8. MoMo redirect về `MoMoController::returnPayment()`
9. Verify signature bảo mật và kiểm tra resultCode
10. Tạo order và order details trong database
11. Giảm stock sản phẩm và xóa giỏ hàng
12. Xóa session data và redirect đến trang success

## Tính năng hiện có:

- ✅ Thanh toán test MoMo với credentials MOMOBKUN20180529
- ✅ Xử lý signature bảo mật theo chuẩn MoMo v2
- ✅ Support requestType "captureWallet" cho ví điện tử
- ✅ Tạo đơn hàng tự động sau thanh toán thành công
- ✅ Xóa giỏ hàng sau khi thanh toán
- ✅ Giảm stock sản phẩm theo variant hoặc product
- ✅ Áp dụng mã giảm giá với session handling
- ✅ Xử lý coupon percentage và fixed amount
- ✅ Kiểm tra đơn hàng tối thiểu cho coupon
- ✅ Hiển thị trang success với thông tin đơn hàng chi tiết
- ✅ Logging đầy đủ cho debug và monitoring
- ✅ Error handling và rollback session khi lỗi

## Lưu ý quan trọng:
- **Đây là môi trường TEST** - Không có tiền thật
- **Credentials hiện tại**: MOMOBKUN20180529 (đã được cập nhật)
- **Request Type**: captureWallet (ví điện tử)
- **Chỉ dành cho demo và chấm điểm đồ án**
- **Có thể mở rộng thêm**: ZaloPay, VNPay, Banking...
- **Session handling**: Đảm bảo data consistency
- **Error logging**: Hỗ trợ debug khi có vấn đề

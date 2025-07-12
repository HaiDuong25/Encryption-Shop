# Hướng dẫn test thanh toán MoMo

## Đã hoàn thành:

1. ✅ **MoMoController** - Xử lý thanh toán MoMo với test credentials
2. ✅ **CartController** - Cập nhật để detect và chuyển hướng thanh toán MoMo  
3. ✅ **Routes** - Thêm routes cho MoMo payment
4. ✅ **Order Model** - Thêm các field cần thiết
5. ✅ **Migration** - Thêm payment_status, transaction_id, total, discount, shipping_address_id
6. ✅ **Success Page** - Hiển thị kết quả đặt hàng

## Test credentials MoMo (Sandbox):
- **Partner Code**: MOMO_TEST_2021
- **Access Key**: F8BBA842ECF85  
- **Secret Key**: K951B6PE1waDMi640xX08PD3vg6EkVlz
- **Endpoint**: https://test-payment.momo.vn/v2/gateway/api/create

## Cách test:

1. **Thêm sản phẩm vào giỏ hàng**
2. **Vào trang checkout** (`/checkout`)
3. **Chọn địa chỉ giao hàng**
4. **Chọn phương thức thanh toán "Ví Điện Tử MOMO"** 
5. **Click "Đặt hàng ngay"**
6. **Hệ thống sẽ redirect đến MoMo payment page**
7. **Trong môi trường test, chọn "Thanh toán thành công"**
8. **Sẽ được redirect về trang success với thông tin đơn hàng**

## Flow xử lý:

1. `CartController::processCheckout()` - Detect MoMo payment
2. Lưu order data vào session  
3. Redirect đến `MoMoController::createPayment()`
4. Call MoMo API tạo payment URL
5. Redirect user đến MoMo payment page
6. User thanh toán trên MoMo
7. MoMo redirect về `MoMoController::returnPayment()`
8. Verify signature và tạo order trong database
9. Redirect đến trang success

## Tính năng:

- ✅ Thanh toán test MoMo
- ✅ Verify signature bảo mật
- ✅ Tạo đơn hàng tự động sau thanh toán thành công
- ✅ Xóa giỏ hàng sau khi thanh toán
- ✅ Giảm stock sản phẩm
- ✅ Áp dụng mã giảm giá
- ✅ Hiển thị trang success với thông tin đơn hàng

## Lưu ý:
- Đây là môi trường TEST, không có tiền thật
- Chỉ dành cho demo và chấm điểm
- Có thể mở rộng thêm các ví điện tử khác (ZaloPay, VNPay...)

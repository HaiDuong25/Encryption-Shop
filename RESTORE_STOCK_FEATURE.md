# CHỨC NĂNG TRẢ LẠI TỒN KHO KHI HỦY/XÓA ĐƠN HÀNG

## Tổng quan
Đã implement chức năng tự động trả lại số lượng sản phẩm vào kho khi:
1. Admin hủy đơn hàng (chuyển status thành 'cancelled')
2. Admin xóa đơn hàng 
3. User hủy đơn hàng (chỉ khi đơn hàng ở trạng thái 'pending' hoặc 'confirmed')

## Các file đã thay đổi

### 1. Model Order (`app/Models/Order.php`)
- Thêm status `STATUS_CANCELLED = 'cancelled'`
- Thêm method `restoreStock()`: Trả lại số lượng sản phẩm vào kho
- Thêm method `canBeCancelled()`: Kiểm tra đơn hàng có thể hủy được không
- Thêm method `cancelOrder()`: Hủy đơn hàng và trả lại tồn kho

### 2. Observer (`app/Observers/OrderObserver.php`)
- **Mới tạo**: Observer để tự động trả lại tồn kho khi xóa đơn hàng
- Method `deleting()`: Tự động gọi `restoreStock()` trước khi xóa
- Method `updating()`: Xử lý khi admin cập nhật status (hiện tại chỉ log)

### 3. AppServiceProvider (`app/Providers/AppServiceProvider.php`)
- Đăng ký `OrderObserver` cho model `Order`

### 4. Admin OrderController (`app/Http/Controllers/Admin/OrderController.php`)
- Thêm method `cancel()`: Hủy đơn hàng với validation
- Cập nhật method `destroy()`: Thêm thông báo về việc trả lại tồn kho
- Cập nhật method `updateStatus()`: Xử lý khi admin chuyển status thành 'cancelled'

### 5. Client OrderController (`app/Http/Controllers/Client/OrderController.php`)
- Thêm method `cancel()`: Cho phép user hủy đơn hàng của mình
- Thêm method `show()`: Xem chi tiết đơn hàng

### 6. Routes (`routes/web.php`)
- Thêm route admin: `POST /admin/orders/{order}/cancel`
- Thêm route client: `GET /orders/{id}` và `POST /orders/{id}/cancel`

### 7. Views
#### Admin (`resources/views/admin/orders/index.blade.php`)
- Thêm nút "Hủy đơn hàng" (chỉ hiện với status 'pending', 'confirmed')
- Thêm confirm dialog cho cả nút hủy và xóa
- Hiển thị status 'cancelled'

#### Client (`resources/views/client/orders/index.blade.php`)
- Thêm nút "Hủy" và "Xem" cho mỗi đơn hàng
- Nút hủy chỉ hiện với status 'pending', 'confirmed'

#### Client (`resources/views/client/orders/show.blade.php`)
- **Mới tạo**: Trang xem chi tiết đơn hàng
- Hiển thị đầy đủ thông tin đơn hàng, sản phẩm, giao hàng
- Có nút hủy đơn hàng (nếu được phép)

## Logic xử lý tồn kho

### Khi hủy đơn hàng:
```php
foreach ($this->orderDetails as $detail) {
    if ($detail->variant_id) {
        // Trả lại stock cho variant
        $variant = $detail->variant;
        if ($variant) {
            $variant->increment('stock', $detail->quantity);
        }
    } else {
        // Trả lại stock cho product
        $product = $detail->product;
        if ($product) {
            $product->increment('stock', $detail->quantity);
        }
    }
}
```

### Quy trình xử lý:
1. **Admin hủy qua nút "Hủy"**: Gọi `cancel()` method → `cancelOrder()` → `restoreStock()`
2. **Admin hủy qua cập nhật status**: Gọi `updateStatus()` → kiểm tra status → `restoreStock()`
3. **Admin xóa đơn hàng**: Observer `deleting()` → `restoreStock()` → xóa đơn hàng
4. **User hủy đơn hàng**: Gọi client `cancel()` → `cancelOrder()` → `restoreStock()`

## Logic nghiệp vụ hủy đơn hàng

### Quy tắc hủy đơn hàng cho USER:
```
✅ USER ĐƯỢC PHÉP HỦY:
├── pending (Chờ xử lý) - Đơn hàng mới tạo, chưa được xử lý
└── confirmed (Đã xác nhận) - Admin đã xác nhận nhưng chưa giao cho ĐVVC

❌ USER KHÔNG ĐƯỢC HỦY:
├── shipping (Giao cho ĐVVC) - Đã giao cho đơn vị vận chuyển
├── delivering (Đang giao) - Đang trên đường giao hàng  
├── received (Đã nhận) - Khách hàng đã nhận hàng
├── completed (Hoàn thành) - Đơn hàng đã hoàn tất
└── cancelled (Đã hủy) - Đơn hàng đã bị hủy trước đó
```

### Quy tắc hủy đơn hàng cho ADMIN:
```
✅ ADMIN ĐƯỢC PHÉP HỦY TẤT CẢ (trừ đã hủy):
├── pending (Chờ xử lý) ✅
├── confirmed (Đã xác nhận) ✅
├── shipping (Giao cho ĐVVC) ✅ 
├── delivering (Đang giao) ✅
├── received (Đã nhận) ✅
├── completed (Hoàn thành) ✅
└── cancelled (Đã hủy) ❌ - Không thể hủy đơn đã hủy
```

### Lý do thiết kế:
- **User**: Chỉ hủy khi hàng còn trong tầm kiểm soát của shop
- **Admin**: Có quyền cao nhất, có thể xử lý các tình huống đặc biệt
- **Bảo vệ lợi ích**: Cân bằng giữa quyền lợi khách hàng và hiệu quả vận hành

## Validation và bảo mật
- User chỉ có thể hủy đơn hàng của chính mình
- **User chỉ được hủy đơn hàng ở trạng thái:**
  - `pending` (Chờ xử lý) 
  - `confirmed` (Đã xác nhận)
- **Admin có thể hủy đơn hàng ở tất cả trạng thái trừ:**
  - `cancelled` (Đã hủy) - Không thể hủy đơn đã hủy
- **Methods validation:**
  - `canBeCancelled()` - Cho user
  - `canBeCancelledByAdmin()` - Cho admin  
  - `cancelOrder()` - User cancel với validation nghiêm ngặt
  - `cancelOrderByAdmin()` - Admin cancel với quyền cao hơn
- Có confirm dialog trước khi hủy/xóa với thông báo phân biệt user/admin
- Thông báo lỗi chi tiết khi không thể hủy

## Test
Đã tạo file `test_restore_stock.php` để test chức năng:
```bash
php test_restore_stock.php
```

## Các tính năng đã hoàn thành
✅ Trả lại tồn kho khi hủy đơn hàng  
✅ Trả lại tồn kho khi xóa đơn hàng  
✅ UI admin với nút hủy/xóa đơn hàng  
✅ UI client với nút hủy đơn hàng  
✅ Trang chi tiết đơn hàng cho client  
✅ Validation quyền hạn và trạng thái  
✅ Observer tự động xử lý khi xóa  
✅ Thông báo thành công/lỗi  

## Lưu ý khi sử dụng
1. Chức năng này chỉ áp dụng với đơn hàng chưa được giao
2. Stock sẽ được trả lại ngay lập tức khi hủy/xóa
3. Observer sẽ tự động xử lý khi xóa đơn hàng bằng bất kỳ cách nào
4. Admin có thể hủy/xóa mọi đơn hàng, user chỉ hủy được đơn hàng của mình

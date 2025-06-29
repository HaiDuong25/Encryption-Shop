@extends('admin.layouts.main')

@section('title', 'Chỉnh sửa đơn hàng')

@section('content')
    <div class="card">
        <div class="card-header">
            <h5 class="mb-0">Chỉnh sửa đơn hàng #{{ $order->id }}</h5>
        </div>
        <div class="card-body">
            <form action="{{ route('orders.update', $order->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="mb-3">
                    <label for="user_id" class="form-label">Khách hàng</label>
                    <select class="form-select" id="user_id" name="user_id" required>
                        <option value="">-- Chọn khách hàng --</option>
                        @foreach ($users as $user)
                            <option value="{{ $user->id }}" data-name="{{ $user->name }}" data-phone="{{ $user->phone }}" data-address="{{ $user->address }}" {{ old('user_id', $order->user_id) == $user->id ? 'selected' : '' }}>
                                {{ $user->name }} (ID: {{ $user->id }})
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label">Sản phẩm trong đơn hàng</label>
                    <div id="products-wrapper">
                        @foreach ($order->orderDetails as $detail)
                            <div class="row mb-2 product-row">
                                <div class="col-7">
                                    <input type="text" class="form-control" value="{{ $detail->product->name ?? 'Sản phẩm đã xóa' }}" readonly>
                                </div>
                                <div class="col-3">
                                    <input type="number" name="quantities[]" class="form-control" min="1" value="{{ $detail->quantity }}" required>
                                </div>
                                <div class="col-2 d-flex align-items-center">
                                    <!-- Không cho xóa sản phẩm ở đây -->
                                </div>
                                <input type="hidden" name="product_ids[]" value="{{ $detail->product_id }}">
                            </div>
                        @endforeach
                    </div>
                </div>
                <div class="mb-3">
                    <label for="name" class="form-label">Tên khách hàng</label>
                    <input type="text" class="form-control" id="name" name="name" required value="{{ old('name', $order->name) }}">
                </div>
                <div class="mb-3">
                    <label for="phone" class="form-label">Số điện thoại</label>
                    <input type="text" class="form-control" id="phone" name="phone" required value="{{ old('phone', $order->phone) }}">
                </div>
                <div class="mb-3">
                    <label for="address" class="form-label">Địa chỉ</label>
                    <input type="text" class="form-control" id="address" name="address" required value="{{ old('address', $order->address) }}">
                </div>
                <div class="mb-3">
                    <label for="status" class="form-label">Trạng thái</label>
                    <select class="form-select" id="status" name="status" required>
                        <option value="0" {{ old('status', $order->status) == 0 ? 'selected' : '' }}>Đã đặt</option>
                        <option value="1" {{ old('status', $order->status) == 1 ? 'selected' : '' }}>Xác nhận</option>
                        <option value="2" {{ old('status', $order->status) == 2 ? 'selected' : '' }}>Giao cho ĐVVC</option>
                        <option value="3" {{ old('status', $order->status) == 3 ? 'selected' : '' }}>Đang giao</option>
                        <option value="4" {{ old('status', $order->status) == 4 ? 'selected' : '' }}>Đã nhận</option>
                        <option value="5" {{ old('status', $order->status) == 5 ? 'selected' : '' }}>Hoàn thành</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label for="discount_id" class="form-label">Mã giảm giá</label>
                    <select class="form-select" id="discount_id" name="discount_id">
                        <option value="">-- Không áp dụng --</option>
                        @foreach ($coupons as $coupon)
                            <option value="{{ $coupon->id }}" {{ old('discount_id', $order->discount_id) == $coupon->id ? 'selected' : '' }}>
                                {{ $coupon->code }} - {{ $coupon->discount }}%
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="mb-3">
                    <label for="payment_method_id" class="form-label">Phương thức thanh toán</label>
                    <select class="form-select" id="payment_method_id" name="payment_method_id" required>
                        <option value="">-- Chọn phương thức --</option>
                        @foreach ($paymentMethods as $method)
                            <option value="{{ $method->id }}" {{ old('payment_method_id', $order->payment_method_id) == $method->id ? 'selected' : '' }}>
                                {{ $method->payment_type }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <button type="submit" class="btn btn-primary">Cập nhật</button>
                <a href="{{ route('orders.index') }}" class="btn btn-secondary">Quay lại</a>
            </form>
        </div>
    </div>
    <script>
        document.getElementById('user_id').addEventListener('change', function() {
            var selected = this.options[this.selectedIndex];
            document.getElementById('name').value = selected.getAttribute('data-name') || '';
            document.getElementById('phone').value = selected.getAttribute('data-phone') || '';
            document.getElementById('address').value = selected.getAttribute('data-address') || '';
        });
    </script>
@endsection

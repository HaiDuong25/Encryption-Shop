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
                        <option value="{{ $user->id }}" {{ old('user_id', $order->user_id) == $user->id ? 'selected' : '' }}>
                            {{ $user->name }} (ID: {{ $user->id }})
                        </option>
                    @endforeach
                </select>
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
                <label for="total_price" class="form-label">Tổng tiền</label>
                <input type="number" class="form-control" id="total_price" name="total_price" required value="{{ old('total_price', $order->total_price) }}">
            </div>
            <div class="mb-3">
                <label for="status" class="form-label">Trạng thái</label>
                <select class="form-select" id="status" name="status" required>
                    <option value="0" {{ old('status', $order->status) == 0 ? 'selected' : '' }}>Chờ xử lý</option>
                    <option value="1" {{ old('status', $order->status) == 1 ? 'selected' : '' }}>Đang giao</option>
                    <option value="2" {{ old('status', $order->status) == 2 ? 'selected' : '' }}>Hoàn thành</option>
                    <option value="3" {{ old('status', $order->status) == 3 ? 'selected' : '' }}>Đã hủy</option>
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
                    <option value="1" {{ old('payment_method_id', $order->payment_method_id) == 1 ? 'selected' : '' }}>Chuyển khoản</option>
                    <option value="2" {{ old('payment_method_id', $order->payment_method_id) == 2 ? 'selected' : '' }}>Tiền mặt</option>
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Cập nhật</button>
            <a href="{{ route('orders.index') }}" class="btn btn-secondary">Quay lại</a>
        </form>
    </div>
</div>
@endsection
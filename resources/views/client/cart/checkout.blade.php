@extends('client.layout.main')

@section('content')
<div class="container py-5">
    <h2 class="mb-4 text-primary"><i class="fa-solid fa-cart-shopping me-2"></i>Thanh toán</h2>

    @if($carts->count() > 0)
    <div class="row">
        <!-- Địa chỉ nhận hàng -->
        <div class="col-lg-6 mb-4">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="fa-solid fa-location-dot me-2"></i>Địa chỉ nhận hàng</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('cart.processCheckout') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Họ và Tên</label>
                            <input type="text" name="name" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Số điện thoại</label>
                            <input type="text" name="phone" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Địa chỉ</label>
                            <input type="text" name="address" class="form-control" required>
                        </div>
                        <!-- Voucher -->
                        <div class="mb-3">
                            <label class="form-label fw-semibold"><i class="fa-solid fa-ticket me-1 text-warning"></i> Mã giảm giá</label>
                            <div class="input-group">
                                <input type="text" class="form-control" name="voucher" placeholder="Nhập mã voucher...">
                                <button class="btn btn-secondary" type="button" disabled>Áp dụng</button>
                            </div>
                            <small class="text-muted">Chức năng voucher đang được phát triển.</small>
                        </div>
                        <!-- Phương thức vận chuyển -->
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Phương thức vận chuyển</label>
                            <select name="payment_method_id" class="form-select" required>
                                <option value="">-- Chọn phương thức --</option>
                                @foreach($payment_methods as $method)
                                <option value="{{ $method->id }}">{{ $method->payment_type }}</option>
                                @endforeach
                            </select>
                        </div>
                        <button class="btn w-100 py-2 mt-3" style="background:#222;color:#fff;">Thanh toán ngay</button>
                    </form>
                </div>
            </div>
        </div>
        <!-- Sản phẩm thanh toán -->
        <div class="col-lg-6 mb-4">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="fa-solid fa-box-open me-2"></i>Sản phẩm thanh toán</h5>
                </div>
                <div class="card-body p-0">
                    <table class="table table-bordered align-middle text-center mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Ảnh</th>
                                <th>Tên</th>
                                <th>Danh mục</th>
                                <th>Thương hiệu</th>
                                <th>SKU</th>
                                <th>Số lượng</th>
                                <th>Giá</th>
                                <th>Tổng</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($carts as $cart)
                            <tr>
                                <td><img src="{{ asset($cart->variant && $cart->variant->image ? 'storage/' . $cart->variant->image : 'storage/' . $cart->product->image) }}" width="60"></td>
                                <td>{{ $cart->product->name }}</td>
                                <td>{{ $cart->product->category->name ?? $cart->product->category_id }}</td>
                                <td>{{ $cart->product->brand->name ?? $cart->product->brand_id }}</td>
                                <td>{{ $cart->variant->sku ?? $cart->product->sku ?? '-' }}</td>
                                <td>{{ $cart->quantity }}</td>
                                <td>{{ number_format($cart->variant->price ?? $cart->product->sale_price ?? $cart->product->price) }} đ</td>
                                <td>{{ number_format(($cart->variant->price ?? $cart->product->sale_price ?? $cart->product->price) * $cart->quantity) }} đ</td>
                            </tr>
                            @endforeach
                            <tr>
                                <td colspan="7" class="text-end fw-bold">Tổng cộng</td>
                                <td class="fw-bold text-danger">{{ number_format($total) }} đ</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    @else
    <div class="alert alert-warning text-center">Giỏ hàng trống.</div>
    @endif
</div>
@endsection

@extends('client.layout.main')
@section('content')
    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif

    <div class="container py-5">
        <h2 class="mb-4 text-primary"><i class="fa-solid fa-cart-shopping me-2"></i>Giỏ hàng của bạn</h2>

        @if($carts->count() > 0)
            <div class="row">
                <!-- Bảng giỏ hàng -->
                <div class="col-lg-8 mb-4">
                    <div class="table-responsive">
                        <table class="table table-bordered align-middle text-center shadow-sm">
                            <thead class="table-primary">
                                <tr>
                                    <th>Ảnh</th>
                                    <th>Tên sản phẩm</th>
                                    <th>Số lượng</th>
                                    <th>Giá</th>
                                    <th>Tổng</th>
                                    <th>Hành động</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($carts as $cart)
                                    <tr>
                                        <td>
                                            <img src="{{ asset($cart->variant && $cart->variant->image ? 'storage/' . $cart->variant->image : 'storage/' . $cart->product->image) }}"
                                                width="70" class="img-thumbnail">
                                        </td>
                                        <td>
                                            <strong>{{ $cart->product->name }}</strong>
                                            @if($cart->variant)
                                                <br>
                                                <small class="text-muted">Biến thể: {{ $cart->variant->sku }}</small>
                                            @endif
                                        </td>
                                        <td>
                                            <form action="{{ route('cart.update', $cart->id) }}" method="POST"
                                                class="d-inline-flex align-items-center gap-1 quantity-form">
                                                @csrf
                                                <button type="button" class="btn btn-outline-secondary btn-sm qty-minus">-</button>
                                                <input type="number" name="quantity" value="{{ $cart->quantity }}" min="1"
                                                    class="form-control form-control-sm text-center" style="width: 60px;">
                                                <button type="button" class="btn btn-outline-secondary btn-sm qty-plus">+</button>
                                                <button type="submit" class="btn btn-primary btn-sm ms-2">✔️</button>
                                            </form>
                                        </td>
                                        <td>{{ number_format($cart->variant->price ?? $cart->product->sale_price ?? $cart->product->price) }}
                                            đ</td>
                                        <td>{{ number_format(($cart->variant->price ?? $cart->product->sale_price ?? $cart->product->price) * $cart->quantity) }}
                                            đ</td>
                                        <td>
                                            <form action="{{ route('cart.delete', $cart->id) }}" method="POST"
                                                onsubmit="return confirm('Xóa sản phẩm này?')">
                                                @csrf
                                                @method('DELETE')
                                                <button class="btn btn-danger btn-sm">🗑️ Xóa</button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Tóm tắt đơn hàng -->
                <div class="col-lg-4">
                    <div class="card shadow-sm border-0">
                        <div class="card-header bg-primary text-white">
                            <h5 class="mb-0"><i class="fa-solid fa-receipt me-2"></i> Tóm tắt đơn hàng</h5>
                        </div>
                        <div class="card-body">
                            <p class="d-flex justify-content-between mb-2">
                                <span>Tổng sản phẩm:</span>
                                <span class="fw-semibold">{{ $carts->sum('quantity') }}</span>
                            </p>

                            @php
                                $grandTotal = $carts->sum(function ($cart) {
                                    return ($cart->variant->price ?? $cart->product->sale_price ?? $cart->product->price) * $cart->quantity;
                                });
                                $voucherDiscount = 50000; // Giả lập voucher giảm 50k
                                $finalTotal = max(0, $grandTotal - $voucherDiscount);
                            @endphp

                            <p class="d-flex justify-content-between mb-2">
                                <span>Tạm tính:</span>
                                <span class="fw-semibold">{{ number_format($grandTotal) }} đ</span>
                            </p>

                            <!-- Divider -->
                            <hr class="my-3">

                            <!-- Voucher -->
                            <form action="{{ route('cart.applyVoucher') }}" method="POST" class="mb-3">
                                @csrf
                                <label for="voucher" class="form-label fw-semibold"><i
                                        class="fa-solid fa-ticket me-1 text-warning"></i> Mã giảm giá</label>
                                <div class="input-group">
                                    <input type="text" class="form-control" id="voucher" name="voucher"
                                        placeholder="Nhập mã voucher..." value="{{ session('voucher_code') }}">
                                    <button class="btn btn-secondary" type="submit">Áp dụng</button>
                                </div>
                                @if(session('voucher_message'))
                                    <small class="text-success">{{ session('voucher_message') }}</small>
                                @elseif(session('voucher_error'))
                                    <small class="text-danger">{{ session('voucher_error') }}</small>
                                @else
                                    <small class="text-muted">Nhập mã voucher để áp dụng giảm giá.</small>
                                @endif
                            </form>

                            <p class="d-flex justify-content-between text-success mb-2">
                                <span>Giảm giá voucher:</span>
                                <span>-{{ number_format($voucherDiscount) }} đ</span>
                            </p>

                            <!-- Divider -->
                            <hr class="my-3">

                            <p class="d-flex justify-content-between fs-5 fw-bold">
                                <span>Tổng thanh toán:</span>
                                <span class="text-primary">{{ number_format($finalTotal) }} đ</span>
                            </p>

                            <a href="{{ route('cart.checkout') }}" class="btn w-100 py-2 mt-3"
                                style="background:#222;color:#fff;">
                                <i class="fa-solid fa-credit-card me-1"></i> Tiến hành thanh toán
                            </a>
                        </div>
                    </div>
                </div>
            </div>

        @else
            <div class="alert alert-warning text-center">Giỏ hàng trống.</div>
        @endif
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            document.querySelectorAll('.quantity-form').forEach(form => {
                const minus = form.querySelector('.qty-minus');
                const plus = form.querySelector('.qty-plus');
                const input = form.querySelector('input[name="quantity"]');

                minus.addEventListener('click', function () {
                    let val = parseInt(input.value) || 1;
                    if (val > 1) input.value = val - 1;
                });

                plus.addEventListener('click', function () {
                    let val = parseInt(input.value) || 1;
                    input.value = val + 1;
                });
            });
        });
    </script>
@endpush

@push('styles')
    <style>
        .quantity-form button.qty-minus,
        .quantity-form button.qty-plus {
            width: 32px;
            height: 32px;
            padding: 0;
            font-weight: bold;
        }

        .card-header h5 i {
            opacity: 0.9;
        }

        .input-group input#voucher::placeholder {
            font-style: italic;
            color: #999;
        }
    </style>
@endpush
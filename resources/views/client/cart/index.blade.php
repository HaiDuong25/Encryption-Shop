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
    <h2>🛒 Giỏ hàng của bạn</h2>
    <br>
    @if($carts->count() > 0)
    <table class="table table-bordered align-middle text-center">
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
                    <img src="{{ asset($cart->variant && $cart->variant->image ? 'storage/' . $cart->variant->image : 'storage/' . $cart->product->image) }}" width="70" class="img-thumbnail">
                </td>
                <td>
                    {{ $cart->product->name }}
                    @if($cart->variant)
                    <br>
                    <small class="text-muted">Biến thể: {{ $cart->variant->sku }}</small>
                    @endif
                </td>
                <td>
                    <form action="{{ route('cart.update', $cart->id) }}" method="POST" class="d-inline-flex align-items-center gap-1 quantity-form">
                        @csrf
                        <button type="button" class="btn btn-outline-secondary btn-sm qty-minus">-</button>
                        <input type="number" name="quantity" value="{{ $cart->quantity }}" min="1" class="form-control form-control-sm text-center" style="width: 60px;">
                        <button type="button" class="btn btn-outline-secondary btn-sm qty-plus">+</button>
                        <button type="submit" class="btn btn-primary btn-sm ms-2">✔️</button>
                    </form>
                </td>
                <td>{{ number_format($cart->variant->price ?? $cart->product->sale_price ?? $cart->product->price) }} đ</td>
                <td>{{ number_format(($cart->variant->price ?? $cart->product->sale_price ?? $cart->product->price) * $cart->quantity) }} đ</td>
                <td>
                    <form action="{{ route('cart.delete', $cart->id) }}" method="POST" onsubmit="return confirm('Xóa sản phẩm này?')">
                        @csrf
                        @method('DELETE')
                        <button class="btn btn-danger btn-sm">🗑️ Xóa</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="text-end">
        <a href="{{ route('cart.checkout') }}" class="btn btn-success btn-lg">💳 Tiến hành thanh toán</a>
    </div>

    @else
    <div class="alert alert-warning text-center">Giỏ hàng trống.</div>
    @endif
</div>
@endsection

@push('scripts')
<script>
document.addEventListener("DOMContentLoaded", function() {
    document.querySelectorAll('.quantity-form').forEach(form => {
        const minus = form.querySelector('.qty-minus');
        const plus = form.querySelector('.qty-plus');
        const input = form.querySelector('input[name="quantity"]');

        minus.addEventListener('click', function() {
            let val = parseInt(input.value) || 1;
            if(val > 1) input.value = val - 1;
        });

        plus.addEventListener('click', function() {
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
</style>
@endpush

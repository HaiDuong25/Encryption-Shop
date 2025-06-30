@extends('client.layout.main')

@section('content')
<div class="container py-5">
    <h2>Thanh toán</h2>

    @if($carts->count() > 0)
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Tên sản phẩm</th>
                <th>Số lượng</th>
                <th>Giá</th>
                <th>Tổng</th>
            </tr>
        </thead>
        <tbody>
            @foreach($carts as $cart)
            <tr>
                <td>{{ $cart->product->name }}</td>
                <td>{{ $cart->quantity }}</td>
                <td>{{ number_format($cart->product->sale_price ?? $cart->product->price) }} đ</td>
                <td>{{ number_format(($cart->product->sale_price ?? $cart->product->price) * $cart->quantity) }} đ</td>
            </tr>
            @endforeach
            <tr>
                <td colspan="3" class="text-end fw-bold">Tổng cộng</td>
                <td>{{ number_format($total) }} đ</td>
            </tr>
        </tbody>
    </table>

    <button class="btn btn-primary">Thanh toán ngay</button>

    @else
    <p>Giỏ hàng trống.</p>
    @endif
</div>
@endsection

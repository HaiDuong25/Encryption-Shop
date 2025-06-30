@extends('client.layout.main')

@section('content')
<div class="container py-5">
    <h2>Giỏ hàng của bạn</h2>

    @if($carts->count() > 0)
    <table class="table table-bordered">
        <thead>
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
                <td><img src="{{ asset('storage/' . $cart->product->image) }}" width="70"></td>
                <td>{{ $cart->product->name }}</td>
                <td>
                    <form action="{{ route('cart.update', $cart->id) }}" method="POST">
                        @csrf
                        <input type="number" name="quantity" value="{{ $cart->quantity }}" min="1" class="form-control w-50 d-inline">
                        <button type="submit" class="btn btn-sm btn-primary">Cập nhật</button>
                    </form>
                </td>
                <td>{{ number_format($cart->product->sale_price ?? $cart->product->price) }} đ</td>
                <td>{{ number_format(($cart->product->sale_price ?? $cart->product->price) * $cart->quantity) }} đ</td>
                <td><a href="{{ route('cart.delete', $cart->id) }}" class="btn btn-sm btn-danger">Xóa</a></td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <a href="{{ route('cart.checkout') }}" class="btn btn-success">Tiến hành thanh toán</a>

    @else
    <p>Giỏ hàng trống.</p>
    @endif
</div>
@endsection

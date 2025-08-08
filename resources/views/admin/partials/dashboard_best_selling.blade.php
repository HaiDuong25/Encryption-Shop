@foreach($bestSellingProducts as $product)
<tr>
    <td>
        <div class="best-product-box">
            <div class="product-image">
                <img src="{{ asset('storage/' . $product->image) }}" class="img-fluid" alt="{{ $product->name }}">
            </div>
            <div class="product-name">
                <h5>{{ $product->name }}</h5>
                <h6>{{ $product->created_at->format('d-m-Y') }}</h6>
            </div>
        </div>
    </td>
    <td>
        <div class="product-detail-box">
            <h6>Giá</h6>
            <h5>{{ format_vnd($product->sale_price ?? $product->price) }} đ</h5>
        </div>
    </td>
    <td>
        <div class="product-detail-box">
            <h6>Đơn hàng</h6>
            <h5>{{ $product->total_orders }}</h5>
        </div>
    </td>
    <td>
        <div class="product-detail-box">
            <h6>Doanh thu</h6>
            <h5>{{ format_vnd(($product->sale_price ?? $product->price) * $product->total_orders) }} đ</h5>
        </div>
    </td>
</tr>
@endforeach

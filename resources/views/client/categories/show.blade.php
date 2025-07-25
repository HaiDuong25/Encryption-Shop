@extends('client.layout.main')

@section('title', $category->name)

@section('content')
<section class="section-b-space shop-section">
    <div class="container">
        <div class="row">

        <div class="col-12">
                <h2 class="mb-4">Danh mục: {{ $category->name }}</h2>
                <div class="row g-sm-4 g-3">
                    @forelse($products as $product)
                        <div class="col-lg-3 col-md-4 col-sm-6 col-12 mb-4">
                            <div class="product-box-3 h-100">
                                <div class="product-header">
                                    <div class="product-image">
                                        <a href="{{ route('client.products.show', $product->id) }}">
                                            <img src="{{ asset('storage/' . $product->image) }}" class="img-fluid blur-up lazyload"
                                                alt="{{ $product->name }}">
                                        </a>
                                    </div>
                                </div>
                                <div class="product-footer">
                                    <div class="product-detail">
                                        <span class="span-name">{{ $product->category->name ?? 'Chưa phân loại' }}</span>
                                        <a href="{{ route('client.products.show', $product->id) }}">
                                            <h5 class="name">{{ $product->name }}</h5>
                                        </a>
                                        <p class="text-content mt-1 mb-2 product-content">{{ $product->description }}</p>

                                        <div class="product-rating mt-2">
                                            @php
                                                $avgRate = $product->rates->where('status', 1)->avg('score') ?? 0;
                                                $avgRate = round($avgRate * 2) / 2;
                                            @endphp
                                            <ul class="rating">
                                                @for ($i = 1; $i <= 5; $i++)
                                                    @if ($avgRate >= $i)
                                                        <li><i data-feather="star" class="fill"></i></li>
                                                    @elseif ($avgRate == ($i - 0.5))
                                                        <li><i data-feather="star-half"></i></li>
                                                    @else
                                                        <li><i data-feather="star"></i></li>
                                                    @endif
                                                @endfor
                                            </ul>
                                            <span>({{ number_format($avgRate, 1) }})</span>
                                        </div>

                                        <h6 class="unit">{{ $product->material ?? 'Đang cập nhật' }}</h6>

                                        <h5 class="price">
                                            <span class="theme-color">
                                                {{ number_format($product->sale_price && $product->sale_price < $product->price ? $product->sale_price : $product->price) }} đ
                                            </span>
                                            @if($product->sale_price && $product->sale_price < $product->price)
                                                <del>{{ number_format($product->price) }} đ</del>
                                            @endif
                                        </h5>

                                        <div class="add-to-cart-box bg-white">
                                            <a href="{{ route('client.products.show', $product->id) }}" class="btn btn-add-cart w-100">
                                                Xem chi tiết
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <p>Không có sản phẩm nào trong danh mục này.</p>
                    @endforelse
                </div>

                <!-- Phân trang -->
                <div class="mt-4">
                    {{ $products->links('pagination::bootstrap-5') }}
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@extends('client.layout.main')
@section('content')
<style>
    .list-group-item {
        padding-left: 0;
        padding-right: 0;
        border: none;
    }

    .list-group-item:hover {
        background-color: #f8f9fa;
    }

    h5 {
        font-weight: 600;
    }
</style>

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

@if($errors->any())
<div class="alert alert-danger">
    <ul class="mb-0">
        @foreach($errors->all() as $error)
        <li>{{ $error }}</li>
        @endforeach
    </ul>
</div>
@endif

<section class="section-b-space shop-section">
    <div class="container">
        <div class="row">
            <!-- Sidebar category -->
            <div class="col-lg-3">
                <div class="left-box wow fadeInUp">
                    <div class="shop-left-sidebar">
                        <div class="back-button">
                            <h3><i class="fa-solid fa-arrow-left"></i> Back</h3>
                        </div>
                        <div class="accordion custom-accordion" id="accordionExample">
                            <!-- Categories filter -->
                            <div class="accordion-item">
                                <h2 class="accordion-header" id="headingOne">
                                    <button class="accordion-button" type="button" data-bs-toggle="collapse"
                                        data-bs-target="#collapseOne">
                                        <span>Categories</span>
                                    </button>
                                </h2>
                                <div id="collapseOne" class="accordion-collapse collapse show">
                                    <div class="accordion-body">
                                        <div class="form-floating theme-form-floating-2 search-box">
                                            <input type="search" class="form-control" id="search-category" placeholder="Search ..">
                                            <label for="search-category">Search</label>
                                        </div>

                                        <form action="{{ route('client.products.index') }}" method="GET">
                                            <ul class="category-list custom-padding custom-height" id="category-list">
                                                @foreach($categories as $category)
                                                <li>
                                                    <div class="form-check ps-0 m-0 category-list-box">
                                                        <input class="checkbox_animated" type="checkbox" name="categories[]" value="{{ $category->id }}"
                                                            id="category-{{ $category->id }}"
                                                            @if(in_array($category->id, $selectedCategories ?? [])) checked @endif>
                                                        <label class="form-check-label" for="category-{{ $category->id }}">
                                                            <span class="name">
                                                                <a href="{{ route('client.products.category', $category->id) }}">
                                                                    {{ $category->name }}
                                                                </a>
                                                            </span>
                                                            <span class="number">({{ $category->products()->where('status',1)->count() }})</span>
                                                        </label>
                                                    </div>
                                                </li>
                                                @endforeach
                                            </ul>

                                            <!-- Brand filter -->
                                            <div class="mt-4">
                                                <h5>Thương hiệu</h5>
                                                <ul class="category-list custom-padding custom-height">
                                                    @foreach($brands as $brand)
                                                    <li>
                                                        <div class="form-check ps-0 m-0 category-list-box">
                                                            <input class="checkbox_animated" type="checkbox" name="brands[]" value="{{ $brand->id }}"
                                                                id="brand-{{ $brand->id }}"
                                                                @if(in_array($brand->id, $selectedBrands ?? [])) checked @endif>
                                                            <label class="form-check-label" for="brand-{{ $brand->id }}">
                                                                <span class="name">
                                                                    <a href="#">
                                                                        {{ $brand->name }}
                                                                    </a>
                                                                </span>
                                                                <span class="number">({{ $brand->products()->where('status',1)->count() }})</span>
                                                            </label>
                                                        </div>
                                                    </li>
                                                    @endforeach
                                                </ul>
                                            </div>


                                            <!-- Price filter -->
                                            <div class="mt-4">
                                                <h5 class="mb-3 border-bottom pb-2">Khoảng giá</h5>
                                                <div class="input-group mb-2">
                                                    <span class="input-group-text">Từ</span>
                                                    <input type="number" name="min_price" class="form-control" placeholder="0" value="{{ request('min_price') }}">
                                                    <span class="input-group-text">đ</span>
                                                </div>
                                                <div class="input-group">
                                                    <span class="input-group-text">Đến</span>
                                                    <input type="number" name="max_price" class="form-control" placeholder="0" value="{{ request('max_price') }}">
                                                    <span class="input-group-text">đ</span>
                                                </div>
                                            </div>
                                            <button type="submit" class="btn theme-bg-color btn-md text-white fw-bold mt-3 w-100">Lọc</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Product list -->
            <div class="col-lg-9">
                <div class="row g-sm-4 g-3">
                    @foreach($products as $product)
                    <div class="col-lg-4 col-md-6 col-6">
                        <div class="product-box-3 h-100 wow fadeInUp">
                            <div class="product-header">
                                <div class="product-image">
                                    <a href="{{ route('client.products.show', $product->id) }}">
                                        <img src="{{ asset('storage/' . $product->image) }}" class="img-fluid blur-up lazyload" alt="{{ $product->name }}">
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
                                        $avgRate = $product->rates->where('status', 1)->avg('score');
                                        $avgRate = round($avgRate * 2) / 2; // làm tròn 0.5
                                        @endphp
                                        <ul class="rating">
                                            @for ($i = 1; $i <= 5; $i++)
                                                @if ($avgRate>= $i)
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
                                        @if($product->sale_price && $product->sale_price < $product->price)
                                        <span class="theme-color">{{ number_format($product->sale_price) }} đ</span>
                                        <del>{{ number_format($product->price) }} đ</del>
                                        @else
                                        <span class="theme-color">{{ number_format($product->price) }} đ</span>
                                        @endif
                                    </h5>

                                    <div class="add-to-cart-box bg-white">
                                        <button class="btn btn-add-cart addcart-button">Add

                                        </button>

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    @endforeach
                </div>

                <div class="mt-4">
                    {{ $products->links('pagination::bootstrap-5') }}
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
@push('scripts')
<script>
    document.addEventListener("DOMContentLoaded", function() {

        // ========== Tăng giảm số lượng ==========
        document.querySelectorAll('.qty-left-minus').forEach(function(btn) {
            const newBtn = btn.cloneNode(true);
            btn.parentNode.replaceChild(newBtn, btn);
            newBtn.addEventListener('click', function(e) {
                const input = newBtn.closest('.input-group').querySelector('input.qty-input');
                let value = parseInt(input.value) || 1;
                if (value > 1) input.value = value - 1;
            });
        });

        document.querySelectorAll('.qty-right-plus').forEach(function(btn) {
            const newBtn = btn.cloneNode(true);
            btn.parentNode.replaceChild(newBtn, btn);
            newBtn.addEventListener('click', function(e) {
                const input = newBtn.closest('.input-group').querySelector('input.qty-input');
                let value = parseInt(input.value) || 1;
                input.value = value + 1;
            });
        });

        // ========== Search category ==========
        const searchInput = document.getElementById('search-category');
        const categoryList = document.getElementById('category-list');
        if (searchInput && categoryList) {
            const items = categoryList.getElementsByTagName('li');
            searchInput.addEventListener('keyup', function() {
                const filter = searchInput.value.toLowerCase();
                for (let i = 0; i < items.length; i++) {
                    const item = items[i];
                    const text = item.textContent || item.innerText;
                    item.style.display = text.toLowerCase().includes(filter) ? "" : "none";
                }
            });
        }

        // ========== Xử lý variant selection trong modal ==========
        document.querySelectorAll('[id^="variant-select-"]').forEach(function(select) {
            select.addEventListener('change', function() {
                const selectedOption = select.options[select.selectedIndex];
                const price = selectedOption.getAttribute('data-price');
                const salePrice = selectedOption.getAttribute('data-sale-price');
                const productId = select.id.replace('variant-select-', '');
                const priceElement = document.getElementById('price-' + productId);

                if (priceElement) {
                    if (salePrice && parseFloat(salePrice) < parseFloat(price)) {
                        priceElement.innerHTML = '<span class="theme-color">' +
                            parseInt(salePrice).toLocaleString('vi-VN') + ' đ</span>' +
                            '<del class="text-muted ms-2">' +
                            parseInt(price).toLocaleString('vi-VN') + ' đ</del>';
                    } else {
                        priceElement.innerHTML = parseInt(price).toLocaleString('vi-VN') + ' đ';
                    }
                }
            });
        });

    });
</script>
@endpush

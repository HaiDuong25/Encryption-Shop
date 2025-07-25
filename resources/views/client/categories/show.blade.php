@extends('client.layout.main')

@section('title', $category->name)

@section('content')
<div class="container py-4">
    <h2>Sản phẩm thuộc danh mục: {{ $category->name }}</h2>
    <div class="row">
        @forelse($products as $product)
            <div class="col-md-3 mb-4">
                <div class="card h-100">
                    <img src="{{ asset('storage/' . $product->image) }}" class="card-img-top" alt="{{ $product->name }}">
                    <div class="card-body">
                        <h5 class="card-title">{{ $product->name }}</h5>
                        <p class="card-text text-danger">{{ number_format($product->price) }} đ</p>
                        <a href="{{ route('products.show', $product->id) }}" class="btn btn-primary btn-sm">Xem chi tiết</a>
                    </div>
                </div>
            </div>
        @empty
            <p>Không có sản phẩm nào trong danh mục này.</p>
        @endforelse
    </div>
</div>
@endsection

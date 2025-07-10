@extends('client.layout.main')

@section('title', 'Sản phẩm yêu thích')

@section('content')
<div class="container py-5">
    <h3 class="fw-bold mb-4">💖 Sản phẩm yêu thích</h3>

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @forelse ($wishlists as $item)
        <div class="card mb-3 shadow-sm border-0">
<div class="card mb-3">
    <div class="row g-0 align-items-center">
        <div class="col-md-2">
            <a href="{{ route('client.products.show', $item->product->id) }}">
                <img src="{{ asset('storage/' . $item->product->image) }}" class="img-fluid rounded-start">
            </a>
        </div>
        <div class="col-md-8">
            <div class="card-body">
                <h5 class="card-title">
                    <a href="{{ route('client.products.show', $item->product->id) }}" class="text-decoration-none text-dark">
                        {{ $item->product->name }}
                    </a>
                </h5>
                <p class="card-text text-danger fw-bold">{{ number_format($item->product->price) }}₫</p>
            </div>
        </div>
        <div class="col-md-2 text-end pe-3">
            <form method="POST" action="{{ route('wishlist.remove', $item->product->id) }}">
                @csrf
                @method('DELETE')
                <button class="btn btn-sm btn-outline-danger">Xóa</button>
            </form>
        </div>
    </div>
</div>

    @empty
        <div class="alert alert-info">Chưa có sản phẩm yêu thích nào.</div>
    @endforelse
</div>
@endsection

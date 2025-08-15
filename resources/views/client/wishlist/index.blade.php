@extends('client.layout.main')

@section('title', 'Sản phẩm yêu thích')
<style>
    /* Card sản phẩm */
    .wishlist-card {
        transition: transform 0.25s ease, box-shadow 0.25s ease;
        border-radius: 12px;
        overflow: hidden;
    }
    .wishlist-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 20px rgba(0,0,0,0.1);
    }

    /* Ảnh sản phẩm */
    .wishlist-card img {
        border-top-left-radius: 12px;
        border-top-right-radius: 12px;
        transition: transform 0.3s ease;
    }
    .wishlist-card:hover img {
        transform: scale(1.05);
    }

    /* Nút trái tim */
    .heart-btn {
        width: 38px;
        height: 38px;
        display: flex;
        align-items: center;
        justify-content: center;
        background-color: white;
        transition: background-color 0.2s ease, transform 0.2s ease;
    }
    .heart-btn:hover {
        background-color: #ffe6e6;
        transform: scale(1.15);
    }

    /* Hiệu ứng nhịp tim */
    .heart-btn i {
        animation: pulse 1.2s infinite;
    }
    @keyframes pulse {
        0%, 100% {
            transform: scale(1);
        }
        50% {
            transform: scale(1.25);
        }
    }

    /* Giá sản phẩm */
    .card-text {
        font-size: 1rem;
    }
</style>

@section('content')
<div class="container py-5">
    <h3 class="fw-bold mb-4">💖 Sản phẩm yêu thích</h3>

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if ($wishlists->isEmpty())
        <div class="alert alert-info">Chưa có sản phẩm yêu thích nào.</div>
    @else
        <div class="row row-cols-2 row-cols-sm-2 row-cols-md-3 row-cols-lg-4 g-4">
            @foreach ($wishlists as $item)
                <div class="col">
                    <div class="card h-100 border-0 shadow-sm wishlist-card position-relative">
                        <a href="{{ route('client.products.show', $item->product->id) }}">
                            <img src="{{ asset('storage/' . $item->product->image) }}"
                                class="card-img-top" style="height: 280px; object-fit: cover;">
                        </a>

                        <!-- Nút "bỏ yêu thích" -->
                        <form method="POST" action="{{ route('wishlist.remove', $item->product->id) }}"
                            class="position-absolute top-0 end-0 m-2">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-light rounded-circle border heart-btn"
                                title="Bỏ yêu thích">
                                <i class="fas fa-heart text-danger"></i>
                            </button>
                        </form>

                        <div class="card-body text-center">
                            <h6 class="card-title mb-1">
                                <a href="{{ route('client.products.show', $item->product->id) }}"
                                    class="text-decoration-none text-dark">
                                    {{ $item->product->name }}
                                </a>
                            </h6>
                            <p class="card-text text-danger fw-bold">
                                {{ format_vnd($item->product->price) }}₫
                            </p>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>
@endsection

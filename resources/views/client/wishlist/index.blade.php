@extends('client.layout.main')

@section('title', 'Sản phẩm yêu thích')

@section('content')
    <div class="container py-5">
        <h3 class="fw-bold mb-4">💖 Sản phẩm yêu thích</h3>

        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        @if ($wishlists->isEmpty())
            <div class="alert alert-info">Chưa có sản phẩm yêu thích nào.</div>
        @else
            <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-lg-4 g-4">
                @foreach ($wishlists as $item)
                    <div class="col">
                        <div class="card h-100 position-relative shadow-sm border-0">
                            <a href="{{ route('client.products.show', $item->product->id) }}">
                                <img src="{{ asset('storage/' . $item->product->image) }}" class="card-img-top"
                                    style="height: 220px; object-fit: cover;">
                            </a>

                            <!-- Nút xóa góc phải -->
                            <form method="POST" action="{{ route('wishlist.remove', $item->product->id) }}"
                                class="position-absolute top-0 end-0 m-2">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-sm btn-danger rounded-circle" title="Xóa khỏi yêu thích">
                                    &times;
                                </button>
                            </form>

                            <div class="card-body">
                                <h6 class="card-title">
                                    <a href="{{ route('client.products.show', $item->product->id) }}"
                                        class="text-decoration-none text-dark">
                                        {{ $item->product->name }}
                                    </a>
                                </h6>
                                <p class="card-text text-danger fw-bold">{{ number_format($item->product->price) }}₫</p>
                                <form method="POST" action="{{ route('wishlist.remove', $item->product->id) }}">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-danger btn btn-sm btn-outline-danger">Xóa</button>
                                </form>

                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
@endsection

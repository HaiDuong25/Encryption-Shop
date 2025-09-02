@extends('client.layout.main')

@section('title', 'Tất cả danh mục')

@section('content')
    <section class="section-b-space shop-section">
        <div class="container-fluid-lg">
            <h2 class="mb-5 text-center fw-bold fs-2">Tất cả danh mục</h2>
            <div class="row g-sm-4 g-3 row-cols-xxl-4 row-cols-xl-3 row-cols-lg-2 row-cols-md-3 row-cols-2">
                @forelse($categories as $category)
                    <div class="col">
                        <div class="category-card-3 h-100 wow fadeInUp p-0"
                            style="border-radius: 1.5rem; box-shadow: 0 8px 25px rgba(0,0,0,0.07); background: #fff; overflow: hidden; transition: all 0.3s;">
                            <div class="category-header position-relative">
                                @if($category->image)
                                    <img src="{{ asset('storage/' . $category->image) }}" alt="{{ $category->name }}"
                                        class="img-fluid w-100" style="height: 240px; object-fit: cover;">
                                @else
                                    <div class="d-flex align-items-center justify-content-center bg-light" style="height: 240px;">
                                        <i class="fa-solid fa-box-open fa-3x text-secondary"></i>
                                    </div>
                                @endif
                            </div>
                            <div class="category-footer p-4 text-center">
                                <h5 class="fw-bold mb-2">
                                    <a href="{{ route('categories.show', $category->id) }}"
                                        class="text-dark text-decoration-none">
                                        {{ $category->name }}
                                    </a>
                                </h5>
                                @if($category->children->count())
                                    <div class="child-categories mt-3 d-flex flex-column align-items-center gap-2"
                                        style="width: 100%;">
                                        @foreach($category->children as $child)
                                            <a href="{{ route('categories.show', $child->id) }}"
                                                class="badge bg-light text-dark px-3 py-2 d-flex align-items-center gap-2"
                                                style="border-radius: 1rem; font-size: 0.95rem; width: 100%; max-width: 320px; white-space: nowrap;">
                                                <i class="fa-solid fa-chevron-right text-primary"></i>
                                                <span>{{ $child->name }}</span>
                                            </a>
                                        @endforeach
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-12">
                        <p class="text-center fs-5">Không có danh mục nào.</p>
                    </div>
                @endforelse
            </div>
        </div>
    </section>

@endsection
<style>
    .category-card-3 {
        transition: transform 0.3s, box-shadow 0.3s;
    }

    .category-header img {
        border-top-left-radius: 1.5rem;
        border-top-right-radius: 1.5rem;
        height: 240px !important;
        object-fit: cover;
    }

    .child-categories a.badge {
        transition: background 0.2s, color 0.2s;
        cursor: pointer;
    }

    .category-footer {
        border-bottom-left-radius: 1.5rem;
        border-bottom-right-radius: 1.5rem;
    }

    .category-card-3:hover {
        transform: translateY(-8px) scale(1.03);
        box-shadow: 0 16px 32px rgba(0, 0, 0, 0.12);
        z-index: 2;
    }

    .child-categories a.badge:hover {
        background: #0d6efd;
        color: #fff;
    }
</style>
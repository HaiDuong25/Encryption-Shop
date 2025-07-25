@extends('client.layout.main')

@section('title', 'Tất cả danh mục')

@section('content')
<div class="container py-4">
    <h2 class="mb-5 text-center fw-bold fs-2">Tất cả danh mục</h2>

    <div class="row">
        @forelse($categories as $category)
            <div class="col-12 col-md-6 col-lg-4 mb-4">
                <div class="category-card text-center border border-2 rounded-4 shadow-sm p-4 h-100 bg-white">
                    {{-- Ảnh đại diện nếu có --}}
                    @if($category->image)
                        <img src="{{ asset('storage/' . $category->image) }}"
                             alt="{{ $category->name }}"
                             class="img-fluid rounded-3 mb-3"
                             style="width: 100%; height: 300px; object-fit: cover;">
                    @endif

                    {{-- Tên danh mục --}}
                    <h5 class="fw-bold mb-3">
                        <a href="{{ route('categories.show', $category->id) }}" class="text-dark text-decoration-none">
                            {{ $category->name }}
                        </a>
                    </h5>

                    {{-- Danh mục con --}}
                    @if($category->children->count())
                        <div class="child-categories mt-3">
                            @foreach($category->children as $child)
                                <a href="{{ route('categories.show', $child->id) }}" class="child-link d-flex justify-content-between align-items-center">
                                    <div class="d-flex align-items-center">
                                        <i class="bi bi-chevron-right me-1 text-primary"></i>
                                        <span>{{ $child->name }}</span>
                                    </div>
                                    @if($child->image)
                                        <img src="{{ asset('storage/' . $child->image) }}" alt="{{ $child->name }}" class="ms-2 rounded" style="width: 40px; height: 40px; object-fit: cover;">
                                    @endif
                                </a>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        @empty
            <div class="col-12">
                <p class="text-center fs-5">Không có danh mục nào.</p>
            </div>
        @endforelse
    </div>
</div>
@endsection

<style>
.category-card {
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}
.category-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 0.85rem 1.5rem rgba(0, 0, 0, 0.1);
}
.child-categories a:hover {
    color: #0d6efd;
    text-decoration: underline;
}
.child-categories a {
    line-height: 1.8;
}
.child-categories {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 6px;
}

.child-link {
    font-size: 15px;
    color: #6c757d;
    text-decoration: none;
    transition: all 0.2s ease;
    display: flex;
    align-items: center;
}

.child-link i {
    font-size: 0.8rem;
    color: #0d6efd;
}

.child-link:hover {
    color: #0d6efd;
    transform: translateX(3px);
}
.child-link img {
    border: 1px solid #dee2e6;
    padding: 2px;
}

</style>

{{-- filepath: c:\laragon\www\DANT\Encryption-Shop\resources\views\banners\show.blade.php --}}
@extends('admin.layouts.main')

@section('content')
    <div class="container">
        <h2 style="font-size:2rem; font-weight:bold;">{{ $banner->title }}</h2>
        <div class="mb-3">
            <strong>Vị trí:</strong> {{ $banner->position }}
        </div>
        <div class="mb-3">
            <strong>Link:</strong>
            @if($banner->link)
                <a href="{{ $banner->link }}" target="_blank">{{ $banner->link }}</a>
            @else
                <span class="text-muted fst-italic">Không có link</span>
            @endif
        </div>
        <div class="mb-3">
            <strong>Trạng thái:</strong>
            @if($banner->is_active)
                <span class="badge bg-success">Hiện</span>
            @else
                <span class="badge bg-danger">Ẩn</span>
            @endif
        </div>
        <div class="mb-3">
            <strong>Ảnh banner:</strong>
            <div class="d-flex flex-wrap gap-2 mt-2">
                @if($banner->images && count($banner->images) > 0)
                    @foreach($banner->images as $img)
                        <img src="{{ asset('storage/' . $img) }}" width="120" height="120"
                            style="object-fit:contain; aspect-ratio:1/1; border-radius:6px; border:1px solid #eee; background:#fafafa;"
                            alt="Banner Image">
                    @endforeach
                @else
                    <span class="text-muted fst-italic">Không có ảnh</span>
                @endif
            </div>
        </div>
        <a href="{{ route('banners.index') }}" class="btn btn-secondary">Quay lại danh sách</a>
    </div>
@endsection
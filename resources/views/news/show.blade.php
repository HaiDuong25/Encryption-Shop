@extends('admin.layouts.main')
@section('content')
    <div class="container">
        <h2 style="font-size:2.2rem; font-weight:bold;">{{ $news->title }}</h2>
        <div class="mb-2">
            <strong>Tác giả:</strong> {{ $news->author }}
        </div>
        <div class="mb-3">
            <strong>Ngày đăng:</strong> {{ $news->created_at->format('d/m/Y') }}
        </div>
        <div class="mb-3">
            <strong>Trạng thái:</strong>
            @if($news->is_published)
                <span class="badge bg-success">Đã đăng</span>
            @else
                <span class="badge bg-danger">Nháp</span>
            @endif
        </div>
        <div class="mb-3">
            @if($news->image)
                <img src="{{ asset('storage/' . $news->image) }}"
                    style="max-width:320px; border-radius:8px; box-shadow:0 2px 6px #0001;">
            @endif
        </div>
        <div class="mb-4">
            <div class="p-4 bg-white rounded shadow-sm border" style="min-height:120px;">
                {!! $news->content !!}
            </div>
        </div>
        <a href="{{ route('news.index') }}" class="btn btn-secondary">Quay lại danh sách</a>
    </div>
@endsection
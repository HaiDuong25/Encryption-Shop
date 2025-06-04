@extends('admin.layouts.main') {{-- Kế thừa từ layout chính của bạn --}}

@section('title', 'Chi tiết Đánh giá #' . $rate->id)

@section('content')
<div class="col-12">
    {{-- Breadcrumb hoặc tiêu đề trang --}}
    <div class="page-header mb-3">
        <div class="row align-items-center">
            <div class="col-sm">
                <h1 class="">Chi tiết Đánh giá #{{ $rate->id }}</h1>

            </div>

        </div>
    </div>
<div class="col-sm-auto">
                <a href="{{ route('admin.rates.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Quay lại Danh sách
                </a>
            </div>
    <div class="card">
        <div class="card-header">
            <h5 class="card-title mb-0">Thông tin Đánh giá</h5>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <p><strong>ID Đánh giá:</strong> {{ $rate->id }}</p>
                    <p><strong>Sản phẩm ID:</strong> {{ $rate->product_id ?: 'N/A' }}</p> {{-- Hiển thị N/A nếu product_id null --}}
                    <p>
                        <strong>Người đánh giá:</strong>
                        @if ($rate->user)
                            {{ $rate->user->name }} (Email: {{ $rate->user->email }})
                        @else
                            <span class="text-muted">Không xác định</span>
                        @endif
                    </p>
                </div>
                <div class="col-md-6">
                    <p><strong>Điểm:</strong>
                        @for ($i = 1; $i <= 5; $i++)
                            <i class="fa-star {{ $i <= $rate->score ? 'fas text-warning' : 'far text-muted' }}"></i>
                        @endfor
                        ({{ $rate->score }}/5)
                    </p>
                    <p><strong>Ngày đánh giá:</strong> {{ $rate->created_at->format('d/m/Y H:i:s') }}</p>
                    <p>
                        <strong>Trạng thái:</strong>
                       <span class="badge rounded-pill {{ $rate->status_class }}">
        {{ ucfirst(str_replace('_', ' ', $rate->status_text)) }}
    </span>
                    </p>
                    <a href="{{ route('admin.rates.edit', $rate->id) }}" class="btn btn-primary">Thay đổi Trạng thái Đánh giá</a>
                </div>
            </div>
            <hr>
            <h5>Nội dung đánh giá:</h5>
            <div class="p-3 border rounded bg-light text-muted">
                {!! nl2br(e($rate->content)) !!}
            </div>
        </div>
    </div>

    <div class="card mt-4">
        <div class="card-header">
            <h5 class="card-title mb-0">Phản hồi của Admin</h5>
        </div>
        <div class="card-body">
            @if ($rate->replies->isNotEmpty())
                @foreach ($rate->replies as $reply)
                    <div class="border-bottom mb-3 pb-3">
                        <p class="mb-1">
                            <strong>
                            @if ($reply->admin)
                                {{ $reply->admin->name }}
                            @else
                                <span class="text-muted">Admin không xác định</span>
                            @endif
                            </strong>
                            <small class="text-muted ms-2">- {{ $reply->created_at->diffForHumans() }} ({{ $reply->created_at->format('d/m/Y H:i') }})</small>
                        </p>
                        <div class="p-2 border rounded bg-white">
                            {!! nl2br(e($reply->reply_content)) !!}
                        </div>
                        <div class="mt-2">
                            {{-- TODO: Nút Sửa/Xóa Phản hồi nếu cần --}}
                            {{-- <a href="#" class="btn btn-sm btn-outline-primary">Sửa</a> --}}
                            {{-- <form action="#" method="POST" class="d-inline"> @csrf @method('DELETE') <button type="submit" class="btn btn-sm btn-outline-danger">Xóa</button></form> --}}
                        </div>
                    </div>
                @endforeach
            @else
                <p class="text-muted">Chưa có phản hồi nào cho đánh giá này.</p>
            @endif

            {{-- TODO: Form để admin thêm phản hồi mới. Chúng ta sẽ làm việc này với AdminRateReplyController --}}
            <hr>
            <h5>Thêm phản hồi mới:</h5>
            <form action="{{-- route('admin.rates.replies.store', $rate->id) --}}" method="POST">
                @csrf
                <div class="mb-3">
                    <textarea name="reply_content" class="form-control" rows="4" placeholder="Nhập nội dung phản hồi của bạn..."></textarea>
                </div>
                <button type="submit" class="btn btn-primary">Gửi phản hồi</button>
            </form>
        </div>
    </div>

    <div class="mt-4">
        {{-- TODO: Các nút hành động khác cho đánh giá, ví dụ: thay đổi trạng thái --}}
        {{-- <a href="{{ route('admin.rates.edit', $rate->id) }}" class="btn btn-primary">Thay đổi Trạng thái Đánh giá</a> --}}
    </div>

</div>
@endsection

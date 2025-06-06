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
            {{-- Nút "Quay lại Danh sách" đã được bạn đặt ở vị trí khác, nên tôi giữ nguyên theo code của bạn --}}
        </div>
    </div>

    {{-- Nút "Quay lại Chi tiết" theo cấu trúc bạn cung cấp --}}
    <div class="mb-3"> {{-- Thêm class mb-3 cho nút này để tạo khoảng cách với card bên dưới --}}
        <a href="{{ route('admin.rates.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Quay lại Danh sách
        </a>
    </div>

    {{-- Card Thông tin Đánh giá --}}
    <div class="card">
        <div class="card-header">
            <h5 class="card-title mb-0">Thông tin Đánh giá</h5>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <p><strong>ID Đánh giá:</strong> {{ $rate->id }}</p>
                    <p><strong>Sản phẩm ID:</strong> {{ $rate->product_id ?: 'N/A' }}</p>
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
                    {{-- Nút thay đổi trạng thái đã được bạn đặt ở đây, giữ nguyên --}}
                    <a href="{{ route('admin.rates.edit', $rate->id) }}" class="btn btn-primary mt-2"> <i class="fas fa-edit"></i> Thay đổi Trạng thái</a>
                </div>
            </div>
            <hr>
            <h5>Nội dung đánh giá:</h5>
            <div class="p-3 border rounded bg-light text-muted"> {{-- Giữ nguyên text-muted nếu bạn muốn màu xám --}}
                {!! nl2br(e($rate->content)) !!}
            </div>
        </div>
    </div>

    {{-- Card Phản hồi của Admin -- START PHẦN CHỈNH SỬA CHÍNH --}}
    <div class="card mt-4">
        <div class="card-header">
            <h5 class="card-title mb-0">Phản hồi của Admin</h5>
        </div>
        <div class="card-body">
            {{-- Hiển thị các phản hồi đã có --}}
            @if ($rate->replies->isNotEmpty())
                @foreach ($rate->replies as $reply)
                    <div class="mb-3 pb-3 @if(!$loop->last) border-bottom @endif"> {{-- Bỏ border-bottom cho item cuối --}}
                        <div class="d-flex align-items-start">
                            {{-- Avatar admin (ví dụ) - bạn có thể thay bằng ảnh thật nếu có --}}
                            <div class="me-3">
                                <i class="fas fa-user-shield fa-2x text-primary"></i> {{-- Icon admin ví dụ --}}
                            </div>
                            <div class="flex-grow-1">
                                <p class="mb-0">
                                    <strong>
                                    @if ($reply->admin)
                                        {{ $reply->admin->name }} (ID: {{ $reply->admin->id }})
                                    @else
                                        <span class="text-muted">Admin không xác định</span>
                                    @endif
                                    </strong>
                                    <small class="text-muted ms-2">- {{ $reply->created_at->diffForHumans() }}</small>
                                </p>
                                <div class="p-2 mt-1 border rounded bg-white shadow-sm">
                                    {!! nl2br(e($reply->reply_content)) !!}
                                </div>
                                <div class="mt-1 text-end">
                                    {{-- TODO: Nút Sửa/Xóa Phản hồi nếu cần (Chức năng nâng cao) --}}
                                    {{-- <a href="#" class="btn btn-sm btn-outline-secondary py-0 px-1">Sửa</a> --}}
                                    {{-- <form action="#" method="POST" class="d-inline"> @csrf @method('DELETE') <button type="submit" class="btn btn-sm btn-outline-danger py-0 px-1">Xóa</button></form> --}}
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            @else
                <p class="text-muted">Chưa có phản hồi nào cho đánh giá này.</p>
            @endif

            {{-- Form thêm phản hồi mới --}}
            <hr class="my-4">
            <h5>Thêm phản hồi mới:</h5>
            <form action="{{ route('admin.rates.replies.store', $rate->id) }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label for="reply_content" class="form-label">Nội dung phản hồi của bạn:</label>
                    <textarea name="reply_content" id="reply_content" class="form-control @error('reply_content') is-invalid @enderror" rows="4" placeholder="Nhập nội dung phản hồi...">{{ old('reply_content') }}</textarea>
                    @error('reply_content')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>
                <button type="submit" class="btn btn-success">
                    <i class="fas fa-paper-plane"></i> Gửi phản hồi
                </button>
            </form>
        </div>
    </div>
    {{-- Card Phản hồi của Admin -- END PHẦN CHỈNH SỬA CHÍNH --}}

    {{-- Các nút hành động khác (nếu có) đã được bạn tích hợp vào Card Thông tin Đánh giá --}}
    {{-- <div class="mt-4">
         <a href="{{ route('admin.rates.edit', $rate->id) }}" class="btn btn-primary">Thay đổi Trạng thái Đánh giá</a>
    </div> --}}
</div>
@endsection

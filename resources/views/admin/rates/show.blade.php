@extends('admin.layouts.main')

@section('title', 'Chi tiết Đánh giá #' . $rate->id)

@section('content')
<div class="container-fluid mt-4"> {{-- Sử dụng container-fluid cho bố cục toàn chiều rộng --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0 text-gray-800">Chi tiết Đánh giá #{{ $rate->id }}</h1>
        </div>
        <div>
            <a href="{{ route('rates.index') }}" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left me-2"></i> Quay lại Danh sách
            </a>
        </div>
    </div>

    <div class="card shadow mb-4"> {{-- Thêm đổ bóng cho card chính --}}
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h5 class="m-0 font-weight-bold text-dark">Thông tin Đánh giá</h5>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <p class="mb-2"><strong>ID Đánh giá:</strong> <span class="text-dark">{{ $rate->id }}</span></p>
                    <p class="mb-2"><strong>Sản phẩm ID:</strong> <span class="text-dark">{{ $rate->product_id ?: 'N/A' }}</span></p>
                    <p class="mb-2">
                        <strong>Người đánh giá:</strong>
                        @if ($rate->user)
                            <span class="text-dark">{{ $rate->user->name }}</span> (Email: <a href="mailto:{{ $rate->user->email }}" class="text-decoration-none">{{ $rate->user->email }}</a>)
                        @else
                            <span class="text-muted">Không xác định</span>
                        @endif
                    </p>
                </div>
                <div class="col-md-6">
                    <p class="mb-2">
                        <strong>Điểm:</strong>
                        @for ($i = 1; $i <= 5; $i++)
                            <i class="fa-star {{ $i <= $rate->score ? 'fas text-warning' : 'far text-muted' }} me-1"></i> {{-- Thêm me-1 để tạo khoảng cách giữa các sao --}}
                        @endfor
                        <span class="text-dark">({{ $rate->score }}/5)</span>
                    </p>
                    <p class="mb-2"><strong>Ngày đánh giá:</strong> <span class="text-dark">{{ $rate->created_at->format('d/m/Y H:i:s') }}</span></p>
                    <p class="mb-2">
                        <strong>Trạng thái:</strong>
                        <span class="badge rounded-pill {{ $rate->status_class }} py-2 px-3 fs-6"> {{-- Tăng kích thước badge một chút --}}
                            {{ ucfirst(str_replace('_', ' ', $rate->status_text)) }}
                        </span>
                    </p>

                    <div class="mt-3"> {{-- Thêm margin top để tách nút ra khỏi các dòng trên --}}
                        <a href="{{ route('rates.edit', $rate->id) }}" class="btn btn-primary btn-sm"> {{-- Sử dụng btn-sm cho nút nhỏ hơn --}}
                            <i class="fas fa-edit me-2"></i> Thay đổi Trạng thái
                        </a>
                    </div>
                </div>
            </div>

            <hr class="my-4">

            <h5 class="mb-3 font-weight-bold text-dark">Nội dung đánh giá:</h5>
            <div class="p-4 border rounded bg-light text-dark custom-scrollable-content"> {{-- Đổi text-muted thành text-dark --}}
                {!! nl2br(e($rate->content)) !!}
            </div>
        </div>
    </div>

    <div class="card shadow mt-4 mb-4"> {{-- Thêm đổ bóng và margin bottom --}}
        <div class="card-header py-3">
            <h5 class="m-0 font-weight-bold text-primary">Phản hồi của Admin</h5>
        </div>
        <div class="card-body">
            @if ($rate->replies->isNotEmpty())
                @foreach ($rate->replies as $reply)
                    <div class="d-flex align-items-start mb-4 pb-3 @if(!$loop->last) border-bottom @endif"> {{-- Dùng mb-4 để tạo khoảng cách lớn hơn giữa các phản hồi --}}
                        <div class="me-3 flex-shrink-0">
                            <i class="fas fa-user-shield fa-2x text-primary"></i>
                        </div>
                        <div class="flex-grow-1">
                            <p class="mb-1"> {{-- Giảm mb để sát với nội dung hơn --}}
                                <strong>
                                    @if ($reply->admin)
                                        {{ $reply->admin->name }} (ID: {{ $reply->admin->id }})
                                    @else
                                        <span class="text-muted">Admin không xác định</span>
                                    @endif
                                </strong>
                                <small class="text-muted ms-2">{{ $reply->created_at->diffForHumans() }}</small>
                            </p>
                            <div class="p-3 mt-1 border rounded bg-white shadow-sm custom-reply-content"> {{-- Tăng padding, thêm shadow --}}
                                {!! nl2br(e($reply->reply_content)) !!}
                            </div>
                        </div>
                    </div>
                @endforeach
            @else
                <p class="text-muted">Chưa có phản hồi nào cho đánh giá này.</p>
            @endif

            <hr class="my-4">

            <h5 class="mb-3 font-weight-bold text-primary">Thêm phản hồi mới:</h5>
            <form action="{{ route('rates.replies.store', $rate->id) }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label for="reply_content" class="form-label visually-hidden">Nội dung phản hồi của bạn:</label> {{-- Ẩn nhãn nếu muốn textarea tự giải thích --}}
                    <textarea name="reply_content" id="reply_content" class="form-control @error('reply_content') is-invalid @enderror" rows="5" placeholder="Nhập nội dung phản hồi của bạn về đánh giá này...">{{ old('reply_content') }}</textarea> {{-- Tăng rows --}}
                    @error('reply_content')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>
                <button type="submit" class="btn btn-success">
                    <i class="fas fa-paper-plane me-2"></i> Gửi phản hồi
                </button>
            </form>
        </div>
    </div>
</div>
@endsection

@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Chi tiết Đánh giá #{{ $rate->id }}</h2>

    <div class="mb-3">
        <strong>Nội dung:</strong>
        <div class="border p-2">{!! nl2br(e($rate->content)) !!}</div>
    </div>


    <div class="mb-3">
        <strong>Trạng thái:</strong>
        <span class="{{ $rate->status_class ?? '' }}">
            {{ $rate->status_text ?? '' }}
        </span>

    {{-- Nút "Quay lại Chi tiết" theo cấu trúc bạn cung cấp --}}
    <div class="mb-3"> {{-- Thêm class mb-3 cho nút này để tạo khoảng cách với card bên dưới --}}
        <a href="{{ route('rates.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Quay lại Danh sách
        </a>

    </div>

    @if($rate->replies && $rate->replies->isNotEmpty())
        <div class="mb-3">
            <strong>Phản hồi:</strong>
            <ul>
                @foreach($rate->replies as $reply)
                    <li>
                        @if($reply->admin)
                            <span class="badge bg-primary">Admin</span>
                        @else
                            <span class="badge bg-secondary">Khách</span>
                        @endif
                        {!! nl2br(e($reply->content)) !!}
                    </li>
                @endforeach
            </ul>

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
                    <a href="{{ route('rates.edit', $rate->id) }}" class="btn btn-primary mt-2"> <i class="fas fa-edit"></i> Thay đổi Trạng thái</a>
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
            <form action="{{ route('rates.replies.store', $rate->id) }}" method="POST">
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
    @endif

    <a href="{{ route('rates.index') }}" class="btn btn-secondary">Quay lại</a>
    {{-- Các nút hành động khác (nếu có) đã được bạn tích hợp vào Card Thông tin Đánh giá --}}
    {{-- <div class="mt-4">
         <a href="{{ route('rates.edit', $rate->id) }}" class="btn btn-primary">Thay đổi Trạng thái Đánh giá</a>
    </div> --}}
</div>
@endsection
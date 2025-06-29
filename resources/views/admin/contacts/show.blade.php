@extends('admin.layouts.main')

@section('title', 'Chi tiết Liên hệ #' . $contact->id)

@section('content')
<div class="container-fluid mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0 text-gray-800">Chi tiết Liên hệ #{{ $contact->id }}</h1>
        </div>
        <div>
            <a href="{{ route('contacts.index') }}" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left me-2"></i> Quay lại Danh sách
            </a>
        </div>
    </div>

    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h5 class="m-0 font-weight-bold text-dark">Thông tin Liên hệ</h5>

        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <p class="mb-2"><strong>ID:</strong> <span class="text-muted">{{ $contact->id }}</span></p>
                    <p class="mb-2"><strong>Người gửi:</strong> <span class="text-muted">{{ $contact->name }}</span></p>
                    <p class="mb-2"><strong>Email:</strong> <a href="mailto:{{ $contact->email }}" class="text-decoration-none">{{ $contact->email }}</a></p>
                    <p class="mb-2"><strong>Điện thoại:</strong> <span class="text-muted">{{ $contact->phone ?: 'N/A' }}</span></p>
                </div>
                <div class="col-md-6">
                    <p class="mb-2"><strong>Ngày gửi:</strong> <span class="text-muted">{{ $contact->created_at->format('d/m/Y H:i:s') }}</span></p>
                    @if($contact->user_id && $contact->user)
                    <p class="mb-2"><strong>Tài khoản liên kết:</strong> <span class="text-muted">{{ $contact->user->name }} (ID: {{ $contact->user_id }})</span></p>
                    @endif
                </div>
            </div>

            <hr class="my-4">

            <h5 class="mb-3 font-weight-bold text-dark">Nội dung:</h5>
            <div class="p-3 border rounded bg-light text-muted">
                {!! nl2br(e($contact->content)) !!}
            </div>
        </div>
        <div class="card-footer d-flex justify-content-end">
            <form action="{{ route('contacts.destroy', $contact->id) }}" method="POST" onsubmit="return confirm('Bạn có chắc chắn muốn xóa liên hệ này? Thao tác này không thể hoàn tác!');">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger">
                    <i class="fas fa-trash me-2"></i> Xóa liên hệ này
                </button>
            </form>
        </div>
    </div>
</div>
@endsection

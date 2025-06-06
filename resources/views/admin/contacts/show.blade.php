@extends('admin.layouts.main')

@section('title', 'Chi tiết Liên hệ #' . $contact->id)

@section('content')
<div class="col-12">
    <div class="page-header mb-3">
        <div class="row align-items-center">
            <div class="col-sm">
                <h1 class="">Chi tiết Liên hệ #{{ $contact->id }}</h1>

            </div>

        </div>
    </div>
    <div class="col-sm-auto">
        <a href="{{ route('contacts.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Quay lại Danh sách
        </a>
    </div>
    <div class="card">
        <div class="card-header">
            <h5 class="card-title mb-0">Thông tin Liên hệ</h5>
        </div>
        <div class="card-body">
            <p><strong>ID:</strong> {{ $contact->id }}</p>
            <p><strong>Người gửi:</strong> {{ $contact->name }}</p>
            <p><strong>Email:</strong> <a href="mailto:{{ $contact->email }}">{{ $contact->email }}</a></p>
            <p><strong>Điện thoại:</strong> {{ $contact->phone ?: 'N/A' }}</p>
            <p><strong>Ngày gửi:</strong> {{ $contact->created_at->format('d/m/Y H:i:s') }}</p>
            @if($contact->user_id && $contact->user)
            <p><strong>Tài khoản liên kết:</strong> {{ $contact->user->name }} (ID: {{ $contact->user_id }})</p>
            @endif
            <hr>
            <h5>Nội dung:</h5>
            <div class="p-3 border rounded bg-light text-muted">
                {!! nl2br(e($contact->content)) !!}
            </div>
        </div>
        <div class="card-footer text-end">
            <form action="{{ route('contacts.destroy', $contact->id) }}" method="POST" onsubmit="return confirm('Bạn có chắc chắn muốn xóa liên hệ này?');">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger">
                    <i class="fas fa-trash"></i> Xóa liên hệ này
                </button>
            </form>
        </div>
    </div>
</div>
@endsection

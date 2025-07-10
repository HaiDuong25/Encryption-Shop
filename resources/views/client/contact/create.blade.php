@extends('client.layout.main')

@section('title', 'Liên hệ')

@section('content')
<div class="container py-5">
    <h2 class="mb-4 text-center">📞 Liên hệ với chúng tôi</h2>

    @if(session('success'))
    <div class="alert alert-success text-center">
        {{ session('success') }}
    </div>
    @endif

    @if($errors->any())
    <div class="alert alert-danger">
        <ul class="mb-0">
            @foreach($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <div class="card shadow-sm">
        <div class="card-body">
            <form action="{{ route('client.contact.store') }}" method="POST">
                @csrf

                <div class="mb-3">
                    <label for="name" class="form-label">Họ tên</label>
                    <input type="text" name="name" id="name" class="form-control" placeholder="Nhập họ tên của bạn" value="{{ old('name') }}" required>
                </div>

                <div class="mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" name="email" id="email" class="form-control" placeholder="Nhập email" value="{{ old('email') }}" required>
                </div>

                <div class="mb-3">
                    <label for="phone" class="form-label">Số điện thoại</label>
                    <input type="text" name="phone" id="phone" class="form-control" placeholder="Nhập số điện thoại" value="{{ old('phone') }}">
                </div>

                <div class="mb-3">
                    <label for="content" class="form-label">Nội dung</label>
                    <textarea name="content" id="content" class="form-control" rows="5" placeholder="Nhập nội dung liên hệ" required>{{ old('content') }}</textarea>
                </div>

                <div class="d-grid">
                    <button type="submit" class="btn btn-primary btn-lg">
                        <i class="fas fa-paper-plane me-2"></i> Gửi liên hệ
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    h2 {
        font-weight: 700;
        color: #333;
    }

    .card {
        max-width: 600px;
        margin: 0 auto;
        border-radius: 12px;
    }

    .btn-primary {
        background: linear-gradient(45deg, #007bff, #0056b3);
        border: none;
    }

    .btn-primary:hover {
        background: linear-gradient(45deg, #0056b3, #004080);
    }
</style>
@endpush

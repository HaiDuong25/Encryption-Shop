@extends('client.layout.main')

@section('title', 'Liên hệ')

@section('content')

<style>
    h2 {
        font-weight: 700;
        color: #333;
    }

    .card {
        max-width: 800px;
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

    /* Alert custom style */
    .custom-alert {
        border-radius: 10px;
        padding: 15px 20px;
        background: linear-gradient(45deg, #d4edda, #c3e6cb);
        box-shadow: 0 0 10px rgba(0,0,0,0.1);
        margin-bottom: 20px;
        animation: slideDown 0.5s ease;
    }

    .custom-alert i {
        color: #28a745;
    }

    .alert-danger.custom-alert {
        background: linear-gradient(45deg, #f8d7da, #f5c6cb);
    }

    .alert-danger.custom-alert i {
        color: #dc3545;
    }

    @keyframes slideDown {
        from { opacity: 0; transform: translateY(-10px); }
        to { opacity: 1; transform: translateY(0); }
    }
</style>

<div class="container py-5">
    <h2 class="mb-4 text-center">📞 Liên hệ với chúng tôi</h2>

    <div class="card shadow-sm">
        <div class="card-body position-relative">

            {{-- Thông báo thành công --}}
            @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show custom-alert" role="alert">
                <div class="d-flex align-items-center">
                    <i class="fas fa-check-circle fa-2x me-3"></i>
                    <div>
                        <strong>Thành công!</strong> {{ session('success') }}
                    </div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            @endif

            {{-- Thông báo lỗi --}}
            @if($errors->any())
            <div class="alert alert-danger alert-dismissible fade show custom-alert" role="alert">
                <div class="d-flex align-items-start">
                    <i class="fas fa-exclamation-circle fa-2x me-3"></i>
                    <div>
                        <strong>Lỗi:</strong>
                        <ul class="mb-0">
                            @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            @endif

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



@push('scripts')
<script>
    // Tự động ẩn thông báo sau 4s với fade out
    setTimeout(() => {
        const alerts = document.querySelectorAll('.custom-alert');
        alerts.forEach(alert => {
            alert.classList.remove('show');
            alert.classList.add('fade');
            setTimeout(() => alert.remove(), 500);
        });
    }, 4000);
</script>
@endpush

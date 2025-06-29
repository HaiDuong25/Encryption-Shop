@extends('admin.layouts.main')

@section('title', 'Chỉnh sửa Trạng thái Đánh giá #' . $rate->id)

@section('content')
<div class="container-fluid mt-4"> {{-- Sử dụng container-fluid cho bố cục toàn chiều rộng --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0 text-gray-800">Chỉnh sửa Trạng thái Đánh giá #{{ $rate->id }}</h1>
        </div>
        <div>
            <a href="{{ route('rates.show', $rate->id) }}" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left me-2"></i> Quay lại Chi tiết
            </a>
        </div>
    </div>

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h5 class="m-0 font-weight-bold text-dark">Thông tin Đánh giá & Cập nhật Trạng thái</h5>
        </div>
        <div class="card-body">
            <div class="row mb-4">
                <div class="col-md-6">
                    <p class="mb-2"><strong>ID Đánh giá:</strong> <span class="text-dark">{{ $rate->id }}</span></p>
                    <p class="mb-2"><strong>Người dùng:</strong>
                        <span class="text-dark">{{ $rate->user ? $rate->user->name : 'N/A' }}</span>
                        @if($rate->user)
                            (<a href="mailto:{{ $rate->user->email }}" class="text-decoration-none">{{ $rate->user->email }}</a>)
                        @endif
                    </p>
                    <p class="mb-2"><strong>Nội dung:</strong> <span class="text-dark">{{ Str::limit($rate->content, 150) }}</span></p>
                </div>
                <div class="col-md-6">
                    <p class="mb-2"><strong>Trạng thái hiện tại:</strong>
                        <span class="badge rounded-pill {{ $rate->status_class }} py-2 px-3 fs-6">
                            {{ ucfirst(str_replace('_', ' ', $rate->status_text)) }}
                        </span>
                    </p>
                    <p class="mb-2"><strong>Ngày đánh giá:</strong> <span class="text-dark">{{ $rate->created_at->format('d/m/Y H:i:s') }}</span></p>
                </div>
            </div>

            <hr class="my-4">

            <h5 class="mb-3 font-weight-bold text-dark">Cập nhật Trạng thái</h5>
            <form action="{{ route('rates.update', $rate->id) }}" method="POST">
                @csrf {{-- Cross-Site Request Forgery token --}}
                @method('PUT') {{-- Phương thức HTTP cho update --}}

                <div class="mb-3">
                    <label for="status" class="form-label">Chọn trạng thái mới:</label>
                    <select name="status" id="status" class="form-select form-select-lg @error('status') is-invalid @enderror"> {{-- Thêm form-select-lg cho combobox lớn hơn --}}
                        @foreach ($statuses as $value => $text)
                            <option value="{{ $value }}" {{ old('status', $rate->status) == $value ? 'selected' : '' }}>
                                {{ $text }}
                            </option>
                        @endforeach
                    </select>
                    @error('status')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <button type="submit" class="btn btn-primary mt-3">
                    <i class="fas fa-save me-2"></i> Cập nhật Trạng thái
                </button>
            </form>
        </div>
    </div>
</div>
@endsection

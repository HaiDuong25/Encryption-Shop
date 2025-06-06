@extends('admin.layouts.main')

@section('title', 'Chỉnh sửa Trạng thái Đánh giá #' . $rate->id)

@section('content')
<div class="col-12">
    <div class="page-header mb-3">
        <div class="row align-items-center">
            <div class="col-sm">
                <h1 class="">Chỉnh sửa Trạng thái #{{ $rate->id }}</h1>

            </div>

        </div>
    </div>
<div class="col-sm-auto">
                <a href="{{ route('admin.rates.show', $rate->id) }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Quay lại Chi tiết
                </a>
            </div>
    <div class="card">
        <div class="card-header">
            <h5 class="card-title mb-0">Cập nhật Trạng thái cho Đánh giá</h5>
        </div>
        <div class="card-body">
            <p><strong>ID Đánh giá:</strong> {{ $rate->id }}</p>
            <p><strong>Người dùng:</strong> {{ $rate->user ? $rate->user->name : 'N/A' }}</p>
            <p><strong>Nội dung:</strong> {{ Str::limit($rate->content, 150) }}</p>
            <p><strong>Trạng thái hiện tại:</strong>
                <span class="badge rounded-pill {{ $rate->status_class }}">
                    {{ ucfirst(str_replace('_', ' ', $rate->status_text)) }}
                </span>
            </p>

            <form action="{{ route('admin.rates.update', $rate->id) }}" method="POST">
                @csrf {{-- Cross-Site Request Forgery token --}}
                @method('PUT') {{-- Hoặc 'PATCH'. Phương thức HTTP cho update --}}

                <div class="mb-3">
                    <label for="status" class="form-label">Chọn trạng thái mới:</label>
                    <select name="status" id="status" class="form-select @error('status') is-invalid @enderror">
                        @foreach ($statuses as $value => $text)
                            <option value="{{ $value }}" {{ old('status', $rate->status) == $value ? 'selected' : '' }}>
                                {{ $text }}
                        @endforeach
                    </select>
                    @error('status')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <button type="submit" class="btn btn-primary">Cập nhật Trạng thái</button>
            </form>
        </div>
    </div>
</div>
@endsection

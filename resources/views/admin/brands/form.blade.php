@extends('admin.layouts.main')

@section('title', isset($brand) ? 'Chỉnh sửa Thương hiệu' : 'Thêm Thương hiệu')

@section('content')
<div class="col-12">
    <h3 class="mt-3 mb-3">{{ isset($brand) ? 'Chỉnh sửa' : 'Thêm mới' }} Thương hiệu</h3>
    <div class="card">
        <div class="card-body">
            <form id="brandForm" enctype="multipart/form-data">
                @csrf
                @if (isset($brand))
                    <input type="hidden" name="_method" value="PUT">
                    <input type="hidden" id="brandId" value="{{ $brand->id }}">
                @endif

                <div class="mb-3">
                    <label for="name" class="form-label">Tên thương hiệu</label>
                    <input type="text" name="name" id="name"
                        class="form-control @error('name') is-invalid @enderror"
                        value="{{ old('name', $brand->name ?? '') }}" >
                    @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="image" class="form-label">Ảnh</label>
                    @if (isset($brand) && $brand->image)
                        <div class="mb-2">
                            <img src="{{ asset('storage/' . $brand->image) }}" alt="Ảnh hiện tại" width="100">
                        </div>
                    @endif
                    <input type="file" name="image" id="image"
                        class="form-control @error('image') is-invalid @enderror"
                        accept="image/*">
                    @error('image')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="d-flex justify-content-end">
                    <a href="{{ route('brands.index') }}" class="btn btn-secondary me-2">Huỷ</a>
                    <button type="submit" class="btn btn-primary">
                        <span class="btn-text">{{ isset($brand) ? 'Cập nhật' : 'Thêm' }}</span>
                        <span class="spinner-border spinner-border-sm d-none" role="status"></span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('brandForm');
    const submitBtn = form.querySelector('button[type="submit"]');
    const btnText = submitBtn.querySelector('.btn-text');
    const spinner = submitBtn.querySelector('.spinner-border');
    
    form.addEventListener('submit', function(e) {
        e.preventDefault();
        
        // Show loading state
        submitBtn.disabled = true;
        btnText.textContent = 'Đang xử lý...';
        spinner.classList.remove('d-none');
        
        const formData = new FormData(form);
        const isEdit = document.getElementById('brandId');
        
        let url, method;
        if (isEdit) {
            url = '/admin/brands/' + isEdit.value;
            method = 'POST';
        } else {
            url = '{{ route('brands.store') }}';
            method = 'POST';
        }
        
        fetch(url, {
            method: method,
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert(data.message);
                window.location.href = '{{ route('brands.index') }}';
            } else {
                alert(data.message || 'Có lỗi xảy ra, vui lòng thử lại!');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Có lỗi xảy ra, vui lòng thử lại!');
        })
        .finally(() => {
            submitBtn.disabled = false;
            btnText.textContent = isEdit ? 'Cập nhật' : 'Thêm';
            spinner.classList.add('d-none');
        });
    });
});
</script>
@endsection

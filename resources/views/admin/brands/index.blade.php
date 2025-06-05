@extends('admin.layouts.main')

@section('title', 'Quản lý Thương hiệu')

@section('content')
<div class="col-12">
    <h3 class="mt-3 mb-3">Danh sách Thương hiệu</h3>

    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="card-title mb-0"><i class="fas fa-tags me-1"></i> Tất cả Thương hiệu</h5>
            <a href="{{ route('brands.create') }}" class="btn btn-success btn-sm">+ Thêm mới</a>
        </div>

        <div class="card-body">
            @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            @endif

            <div class="table-responsive">
                <table class="table table-bordered table-hover table-striped text-center">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Tên</th>
                            <th>Ảnh</th>
                            <th>Hành động</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($brands as $brand)
                        <tr>
                            <td>{{ $brand->id }}</td>
                            <td>{{ $brand->name }}</td>
                            <td>
                                @if ($brand->image)
                                <img src="{{ asset('storage/' . $brand->image) }}" alt="{{ $brand->name }}" width="100">
                                @else
                                —
                                @endif
                            </td>
                            <td>
                               <div class="d-flex gap-1 justify-content-center">
                                    <a href="{{ route('brands.edit', $brand) }}" class="btn btn-sm btn-primary"><i class="fas fa-edit"></i></a>
                                    <form action="{{ route('brands.destroy', $brand) }}" method="POST" onsubmit="return confirm('Xác nhận xoá?');">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-sm btn-danger"><i class="fas fa-trash"></i></button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="text-center">Không có thương hiệu.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

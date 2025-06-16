@extends('admin.layouts.main')

@section('title', 'Quản lý Thương hiệu')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-sm-12">
            <div class="card card-table">
                <div class="card-body">
                    <div class="title-header option-title d-sm-flex d-block justify-content-between align-items-center">
                        <h5>Danh sách thương hiệu</h5>
                        <div class="right-options d-flex gap-2 align-items-center">
                            <a class="btn btn-solid btn-sm" href="{{ route('brands.create') }}">Thêm mới</a>
                        </div>
                    </div>

                    @if (session('success'))
                    <div class="alert alert-success alert-dismissible fade show mt-3">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                    @endif

                    <div class="table-responsive mt-3">
                        <table class="table all-package theme-table table-product text-center align-middle" style="border-collapse: separate; border-spacing: 0 12px;">
                            <thead class="table-light">
                                <tr>
                                    <th>Tên thương hiệu</th>
                                    <th>Ngày tạo</th>
                                    <th>Ảnh</th>
                                    <th>Hành động</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($brands as $brand)
                                <tr style="border-bottom: none !important;">
                                    <td>{{ $brand->name }}</td>
                                    <td>
                                        {{ $brand->created_at ? $brand->created_at->format('d/m/Y H:i') : '—' }}
                                    </td>
                                    <td>
                                        @if ($brand->image)
                                        <img src="{{ asset('storage/' . $brand->image) }}" class="img-fluid" width="60" alt="{{ $brand->name }}">
                                        @else
                                        —
                                        @endif
                                    </td>
                                    <td>
                                        <ul class="d-flex justify-content-center gap-2 list-unstyled mb-0">
                                            <li>
                                                <a href="{{ route('brands.edit', $brand) }}">
                                                    <i class="ri-pencil-line"></i>
                                                </a>
                                            </li>
                                            <li>
                                                <form action="{{ route('brands.destroy', $brand) }}" method="POST" onsubmit="return confirm('Xác nhận xoá?');">
                                                    @csrf @method('DELETE')
                                                    <button type="submit" class="btn btn-link p-0 text-danger">
                                                        <i class="ri-delete-bin-line"></i>
                                                    </button>
                                                </form>
                                            </li>
                                        </ul>
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
    </div>
</div>
@endsection

@extends('admin.layouts.main')

@section('title', 'Phương thức thanh toán')

@section('content')
<div class="col-12">
    <h3 class="mt-3 mb-3">Danh sách Phương thức thanh toán</h3>

    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="card-title mb-0">Tất cả phương thức</h5>
            <div class="right-options d-flex gap-2 align-items-center">
                {{-- Form tìm kiếm theo loại thanh toán hoặc mô tả --}}
                <form method="GET" action="{{ route('payment-methods.index') }}" class="d-flex">
                    <input type="text" name="search" class="form-control me-2" placeholder="Tìm theo loại hoặc mô tả..." 
                           value="{{ request('search') }}" style="width: 250px;">
                    <button type="submit" class="btn btn-primary me-2">
                        <i class="ri-search-line"></i> Tìm
                    </button>
                    @if(request('search'))
                        <a href="{{ route('payment-methods.index') }}" class="btn btn-outline-secondary me-2 bg-dark">
                            <i class="ri-refresh-line"></i> Xóa bộ lọc
                        </a>
                    @endif
                </form>
                <a href="{{ route('payment-methods.create') }}" class="btn btn-success btn-sm">
                    <i class="fas fa-plus"></i> Thêm mới
                </a>
            </div>
        </div>

        <div class="card-body">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <table class="table table-bordered table-hover table-striped text-center align-middle">
                <thead class="table-light">
                    <tr>
                        <th>ID</th>
                        <th>Loại thanh toán</th>
                        <th>Mô tả</th>
                        <th>Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($methods as $method)
                        <tr>
                            <td>{{ $method->id }}</td>
                            <td>{{ $method->payment_type }}</td>
                            <td>{{ $method->description }}</td>
                            <td>
                                <div class="d-flex justify-content-center gap-1">
                                    <a href="{{ route('payment-methods.edit', $method) }}" class="btn btn-sm btn-primary" title="Sửa">
                                        <i class="fas fa-edit"></i>
                                    </a>

                                    <button class="btn btn-sm btn-danger" data-bs-toggle="modal"
                                        data-bs-target="#deleteModal{{ $method->id }}" title="Xóa">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                </div>

                                <!-- Modal Xác nhận Xóa -->
                                <div class="modal fade" id="deleteModal{{ $method->id }}" tabindex="-1">
                                    <div class="modal-dialog modal-dialog-centered">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title text-danger">Xác nhận xóa</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                            </div>
                                            <div class="modal-body">
                                                <p>Bạn có chắc chắn muốn xóa phương thức <strong>{{ $method->payment_type }}</strong> không?</p>
                                            </div>
                                            <div class="modal-footer">
                                                <form action="{{ route('payment-methods.destroy', $method) }}" method="POST">
                                                    @csrf @method('DELETE')
                                                    <button type="submit" class="btn btn-danger">Xóa</button>
                                                </form>
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- End Modal -->
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <div class="d-flex justify-content-center mt-3">
                {{ $methods->links() }}
            </div>
        </div>
    </div>
</div>
@endsection

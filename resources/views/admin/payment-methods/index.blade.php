@extends('admin.layouts.main')

@section('content')
    <h2>Danh sách phương thức thanh toán</h2>

    <a href="{{ route('payment-methods.create') }}" class="btn btn-primary mb-3 ">
        <i class="fa fa-plus"></i> Thêm mới
    </a>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <table class="table table-bordered">
        <thead >
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
    <div class="d-flex align-items-center gap-2">
        <a href="{{ route('payment-methods.edit', $method) }}"
           class="btn btn-primary btn-sm fw-bold rounded-2 shadow-sm" title="Sửa">
          <i class="fas fa-edit" style="display: inline-block;"></i> Sửa
        </a>

        <button class="btn btn-primary btn-sm fw-bold rounded-2 shadow-sm"
                data-bs-toggle="modal" data-bs-target="#deleteModal{{ $method->id }}" title="Xóa">
            <i class="fa fa-trash"></i> Xóa
        </button>
    </div>
</td>
                        <!-- Modal Xác nhận Xóa -->
                        <div class="modal fade" id="deleteModal{{ $method->id }}" tabindex="-1">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title text-danger">Xác nhận xóa</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                    </div>
                                    <div class="modal-body">
                                        <p class="fw-bold">Bạn có chắc chắn muốn xóa phương thức này?</p>
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
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    {{ $methods->links() }}
@endsection

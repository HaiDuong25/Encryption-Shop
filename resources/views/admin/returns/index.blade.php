{{-- filepath: resources/views/admin/returns/index.blade.php --}}
@extends('admin.layouts.main')

@section('title', 'Danh sách yêu cầu trả hàng')

@section('content')
    <div class="container py-4">
        <h2>Danh sách yêu cầu trả hàng</h2>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Khách</th>
                    <th>Sản phẩm</th>
                    <th>Lý do</th>
                    <th>Trạng thái</th>
                    <th>Ngày gửi</th>
                    <th>Thao tác</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($returns as $return)
                    @php
                        $statusLabels = [
                            'pending' => 'Chờ duyệt',
                            'approved' => 'Đã duyệt',

                        ];
                    @endphp

                    <tr>
                        <td>{{ $return->id }}</td>
                        <td>{{ $return->user->name ?? 'Ẩn' }}</td>
                        <td>{{ $return->orderDetail->product->name ?? 'Ẩn' }}</td>
                        <td>{{ $return->reason }}</td>
                        <td>
                            <span class="badge bg-warning">
                                {{ $statusLabels[$return->status] ?? $return->status }}
                            </span>
                        </td>
                        <td>{{ $return->created_at->format('d/m/Y H:i') }}</td>
                        <td>
                            <a href="{{ route('admin.returns.show', $return->id) }}" class="btn btn-sm btn-primary">Xem</a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        {{ $returns->links() }}
    </div>
@endsection

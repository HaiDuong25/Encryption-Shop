@extends('admin.layouts.main')

@section('title', 'Chi tiết yêu cầu trả hàng')

@section('content')
<div class="container py-4">
    <h2>Chi tiết yêu cầu trả hàng #{{ $return->id }}</h2>

    <ul>
        <li><strong>Khách:</strong> {{ $return->user->name }}</li>
        <li><strong>Sản phẩm:</strong> {{ $return->orderDetail->product->name ?? 'Ẩn' }}</li>
        <li><strong>Lý do:</strong> {{ $return->reason }}</li>
        <li><strong>Mô tả:</strong> {{ $return->description }}</li>
        <li><strong>Ảnh:</strong>
            @if ($return->image)
                <img src="{{ asset('storage/' . $return->image) }}" width="100" />
            @else
                Không có
            @endif
        </li>
        <li><strong>Trạng thái hiện tại:</strong> <span class="badge bg-warning">{{ $return->status }}</span></li>
    </ul>

    <form action="{{ route('admin.returns.updateStatus', $return->id) }}" method="POST">
        @csrf
        <div class="form-group">
            <label>Trạng thái mới</label>
            <select name="status" class="form-control" required>
                <option value="pending" {{ $return->status == 'pending' ? 'selected' : '' }}>Chờ duyệt</option>
                <option value="approved" {{ $return->status == 'approved' ? 'selected' : '' }}>Đã chấp nhận</option>
                <option value="rejected" {{ $return->status == 'rejected' ? 'selected' : '' }}>Từ chối</option>
                <option value="returned" {{ $return->status == 'returned' ? 'selected' : '' }}>Đã nhận hàng</option>
                <option value="refunded" {{ $return->status == 'refunded' ? 'selected' : '' }}>Đã hoàn tiền</option>
            </select>
        </div>
        <button type="submit" class="btn btn-success mt-2">Cập nhật trạng thái</button>
    </form>
</div>
@endsection

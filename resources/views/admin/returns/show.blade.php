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
@php
    $statusLabels = [
        'returned' => 'Chờ duyệt',
        'approved' => 'Đã chấp nhận',

    ];
@endphp

<li>
    <strong>Trạng thái hiện tại:</strong>
    <span class="badge bg-warning">
        {{ $statusLabels[$return->status] ?? $return->status }}
    </span>
</li>
    </ul>

    <form action="{{ route('admin.returns.updateStatus', $return->id) }}" method="POST">
    @csrf
    <div class="form-group">
        <label>Trạng thái mới</label>
        <select name="status" class="form-control" required>
            <option value="returned" {{ $return->status == 'returned' ? 'selected' : '' }}>Chờ duyệt</option>
            <option value="approved" {{ $return->status == 'approved' ? 'selected' : '' }}>Đã duyệt</option>
        </select>
    </div>
    <button type="submit" class="btn btn-success mt-2">Cập nhật trạng thái</button>
</form>

</div>
@endsection

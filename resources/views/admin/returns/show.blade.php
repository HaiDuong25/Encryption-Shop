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
                'pending' => 'Chờ duyệt',
                'returning' => 'Đang trả hàng',
                'approved' => 'Đã phê duyệt',
                'rejected' => 'Từ chối',
                'returned' => 'Đã trả hàng',
                'refunded' => 'Đã hoàn tiền',
            ];
        @endphp

        <li>
            <strong>Trạng thái hiện tại:</strong>
            <span class="badge bg-warning">
                {{ $statusLabels[$return->status] ?? $return->status }}
            </span>
        </li>
    </ul>

    {{-- Chỉ hiển thị form cập nhật nếu đang ở trạng thái pending --}}
    @if ($return->status === 'pending')
        <form action="{{ route('admin.returns.updateStatus', $return->id) }}" method="POST">
            @csrf
            <div class="form-group">
                <label>Trạng thái mới</label>
                <select name="status" class="form-control" required>
                    <option value="returning">Đang trả hàng</option>
                    <option value="approved">Đã phê duyệt</option>
                    <option value="rejected">Từ chối</option>
                </select>
            </div>
            <button type="submit" class="btn btn-success mt-2">Cập nhật trạng thái</button>
        </form>
    @else
        <p class="text-muted">Không thể cập nhật trạng thái khi đơn không ở trạng thái <strong>Chờ duyệt</strong>.</p>
    @endif
</div>
@endsection

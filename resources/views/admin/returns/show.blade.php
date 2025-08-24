@extends('admin.layouts.main')

@section('title', 'Chi tiết yêu cầu trả hàng')

@section('content')
<div class="container py-4">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Chi tiết yêu cầu trả hàng #{{ $return->id }}</h5>
            <a href="{{ route('admin.returns.index') }}" class="btn btn-sm btn-secondary">← Quay lại</a>
        </div>
        <div class="card-body">
            <table class="table table-sm table-bordered mb-0">
                <!-- Thông tin khách hàng -->
                <tr>
                    <th width="25%">Khách hàng</th>
                    <td>{{ $return->user->name }} ({{ $return->user->email }})</td>
                </tr>

                <!-- Thông tin người nhận -->
                <tr>
                    <th>Người nhận</th>
                    <td>{{ $return->order->receiver_name ?? 'Admin' }}</td>
                </tr>
                <tr>
                    <th>SĐT</th>
                    <td>{{ $return->order->receiver_phone ?? '0766304377' }}</td>
                </tr>
                <tr>
                    <th>Địa chỉ</th>
                    <td>{{ $return->order->receiver_address ?? '1aaz, Phường Phương Canh, Quận Nam Từ Liêm, Hà Nội' }}</td>
                </tr>
                <tr>
                    <th>Email</th>
                    <td>{{ $return->order->receiver_email ?? 'admin@gmail.com' }}</td>
                </tr>

                <!-- Thông tin sản phẩm -->
                <tr>
                    <th>Sản phẩm</th>
                    <td>
                        {{ $return->orderDetail->product->name ?? 'Ẩn' }}
                        @if($return->orderDetail->variant)
                            <br>
                            <small>Biến thể: {{ $return->orderDetail->variant->name }}</small>
                        @endif
                        <br>
                        <small>Giá: {{ number_format($return->orderDetail->price, 0, ',', '.') }} ₫</small>
                    </td>
                </tr>

                <tr>
                    <th>Lý do</th>
                    <td>{{ $return->reason }}</td>
                </tr>
                <tr>
                    <th>Mô tả</th>
                    <td>{{ $return->description }}</td>
                </tr>
                <tr>
                    <th>Ảnh minh hoạ</th>
                    <td>
                        @if ($return->image)
                            <img src="{{ asset('storage/' . $return->image) }}" width="100" class="img-thumbnail">
                        @else
                            <em>Không có</em>
                        @endif
                    </td>
                </tr>

                <tr>
                    <th>Trạng thái</th>
                    <td>
                        @php
                            $statusLabels = [
                                'pending' => 'Chờ duyệt',
                                'returning' => 'Đang trả hàng',
                                'approved' => 'Đã trả hàng',
                                'rejected' => 'Từ chối',
                                'returned' => 'Đã duyệt đơn',
                                'refunded' => 'Đã hoàn tiền',
                            ];
                            $badgeColors = [
                                'pending' => 'warning',
                                'returning' => 'info',
                                'approved' => 'success',
                                'rejected' => 'danger',
                                'returned' => 'secondary',
                                'refunded' => 'primary',
                            ];
                        @endphp
                        <span class="badge bg-{{ $badgeColors[$return->status] ?? 'secondary' }}">
                            {{ $statusLabels[$return->status] ?? $return->status }}
                        </span>
                    </td>
                </tr>
                <tr>
                    <th>Ngày yêu cầu</th>
                    <td>{{ $return->created_at->format('H:i d/m/Y') }}</td>
                </tr>
            </table>

            @if ($return->status === 'pending')
                <form action="{{ route('admin.returns.updateStatus', $return->id) }}" method="POST" class="mt-4">
                    @csrf
                    <div class="row g-2 align-items-end">
                        <div class="col-md-4">
                            <label for="status">Trạng thái mới</label>
                            <select name="status" id="status" class="form-control" required>
                                <option value="returning">Chờ duyệt</option>
                                <option value="approved">Đã trả hàng</option>
                                <option value="rejected">Từ chối</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="note">Lý do/ghi chú</label>
                            <input type="text" name="note" id="note" class="form-control" placeholder="Nhập lý do từ chối nếu có">
                        </div>
                        <div class="col-auto">
                            <button type="submit" class="btn btn-success">Cập nhật</button>
                        </div>
                    </div>
                </form>
            @else
                <div class="alert alert-secondary mt-3 mb-0">
                    <i class="fa fa-info-circle me-1"></i> Không thể cập nhật trạng thái khi đơn không ở trạng thái <strong>Chờ duyệt</strong>.
                </div>
            @endif
        </div>
    </div>
</div>
@endsection

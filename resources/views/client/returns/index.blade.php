@extends('client.layout.main')

@section('title', 'Danh sách yêu cầu trả hàng')

@section('content')
    <div class="container py-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4>Danh sách yêu cầu trả hàng</h4>
            <a href="{{ route('client.orders.index') }}" class="btn btn-outline-secondary">
                ← Quay lại đơn hàng
            </a>
        </div>

        @if($returns->count() > 0)
            <div class="card shadow-sm">
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Đơn hàng</th>
                                    <th>Sản phẩm</th>
                                    <th>Lý do</th>
                                    <th>Trạng thái</th>
                                    <th>Ngày tạo</th>
                                    <th>Thao tác</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($returns as $return)
                                    <tr>
                                        <td>
                                            <strong>#{{ $return->order_id }}</strong>
                                        </td>
                                        <td>
                                            @if($return->orderDetail && $return->orderDetail->product)
                                                @php
                                                    $product = $return->orderDetail->variant->product ?? $return->orderDetail->product;
                                                    $image = $product->image ?? null;
                                                    $imageUrl = $image 
                                                        ? (Str::startsWith($image, ['http://', 'https://']) 
                                                            ? $image 
                                                            : asset('storage/' . $image))
                                                        : 'https://via.placeholder.com/50?text=No+Image';
                                                @endphp
                                                <div class="d-flex align-items-center">
                                                    <img src="{{ $imageUrl }}" width="50" height="50" class="rounded me-2">
                                                    <div>
                                                        <div class="fw-bold">{{ $product->name }}</div>
                                                        <small class="text-muted">Số lượng: {{ $return->orderDetail->quantity }}</small>
                                                    </div>
                                                </div>
                                            @else
                                                <span class="text-muted">Sản phẩm đã xóa</span>
                                            @endif
                                        </td>
                                        <td>{{ $return->reason }}</td>
                                        <td>
                                            @switch($return->status)
                                                @case('pending')
                                                    <span class="badge bg-warning text-dark">Chờ duyệt</span>
                                                    @break
                                                @case('approved')
                                                    <span class="badge bg-success">Đã duyệt</span>
                                                    @break
                                                @case('rejected')
                                                    <span class="badge bg-danger">Từ chối</span>
                                                    @break
                                                @default
                                                    <span class="badge bg-secondary">{{ ucfirst($return->status) }}</span>
                                            @endswitch
                                        </td>
                                        <td>{{ $return->created_at->format('d/m/Y H:i') }}</td>
                                        <td>
                                            <a href="{{ route('client.returns.show', $return->id) }}" class="btn btn-sm btn-outline-primary">
                                                Chi tiết
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Pagination -->
            <div class="d-flex justify-content-center mt-4">
                {{ $returns->links() }}
            </div>
        @else
            <div class="text-center py-5">
                <i class="fas fa-box-open fa-3x text-muted mb-3"></i>
                <h5 class="text-muted">Chưa có yêu cầu trả hàng nào</h5>
                <p class="text-muted">Bạn chưa có yêu cầu trả hàng nào.</p>
                <a href="{{ route('client.orders.index') }}" class="btn btn-primary">Xem đơn hàng</a>
            </div>
        @endif
    </div>
@endsection

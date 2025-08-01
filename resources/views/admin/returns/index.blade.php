@extends('admin.layouts.main')

@section('title', 'Danh sách yêu cầu trả hàng')

@section('content')
    <div class="container py-4">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h2 class="mb-0">📦 Danh sách yêu cầu trả hàng</h2>

            <form action="{{ route('admin.returns.index') }}" method="GET" class="d-flex gap-2" style="max-width: 400px;">
                <input type="text" name="search" class="form-control" placeholder="Tìm khách hàng, sản phẩm, lý do..."
                       value="{{ request('search') }}">
                <button class="btn btn-primary me-2">
                    <i class="ri-search-line"></i> Tìm
                </button>
                @if(request('search'))
                    <a href="{{ route('admin.returns.index') }}" class="btn btn-outline-secondary me-2 bg-dark">
                        <i class="ri-refresh-line"></i> Xóa bộ lọc
                    </a>
                @endif
            </form>
        </div>

        <div class="card">
            <div class="card-body table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>Khách hàng</th>
                            <th>Sản phẩm</th>
                            <th>Lý do</th>
                            <th>Trạng thái</th>
                            <th>Ngày gửi</th>
                            <th>Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($returns as $return)
                            @php
                                $statusLabels = [
                                    'pending' => ['label' => 'Chờ duyệt đơn ', 'class' => 'bg-warning'],
                                    'returning' => ['label' => 'Đang trả hàng', 'class' => 'bg-info'],
                                    'approved' => ['label' => 'Đã trả hàng', 'class' => 'bg-success'],
                                    'rejected' => ['label' => 'Từ chối', 'class' => 'bg-danger'],
                                    'returned' => ['label' => 'Đã duyệt đơn', 'class' => 'bg-secondary'],
                                    'refunded' => ['label' => 'Đã hoàn tiền', 'class' => 'bg-success'],
                                ];
                                $status = $statusLabels[$return->status] ?? ['label' => ucfirst($return->status), 'class' => 'bg-light'];
                            @endphp
                            <tr>
                                <td>{{ $return->id }}</td>
                                <td>{{ $return->user->name ?? 'Ẩn danh' }}</td>
                                <td>{{ $return->orderDetail->product->name ?? 'Không rõ' }}</td>
                                <td>{{ $return->reason }}</td>
                                <td><span class="badge {{ $status['class'] }}">{{ $status['label'] }}</span></td>
                                <td>{{ $return->created_at->format('d/m/Y H:i') }}</td>
                                <td>
                                    <a href="{{ route('admin.returns.show', $return->id) }}"
                                       class="btn btn-sm btn-outline-primary">Chi tiết</a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center text-muted">Không có yêu cầu trả hàng nào.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Phân trang --}}
        <div class="mt-3">
            {{ $returns->withQueryString()->links() }}
        </div>
    </div>
@endsection

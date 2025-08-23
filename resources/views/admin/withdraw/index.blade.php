@extends('admin.layouts.main')

@section('content')
<div class="container py-4">
    <h3 class="mb-4 fw-bold"><i class="fas fa-wallet me-2 text-primary"></i>Danh sách yêu cầu rút tiền</h3>
    <div class="card shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-bordered align-middle mb-0 text-center">
                    <thead class="table-light">
                        <tr>
                            <th>ID</th>
                            <th>Khách hàng</th>
                            <th>Số tiền</th>
                            <th>Ngân hàng</th>
                            <th>Số tài khoản</th>
                            <th>Chủ tài khoản</th>
                            <th>Trạng thái</th>
                            <th>Lý do từ chối</th>
                            <th>Ngày yêu cầu</th>
                            <th>Hành động</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($requests as $req)
                            <tr>
                                <td>{{ $req->id }}</td>
                                <td>
                                    <div class="fw-semibold">{{ $req->user->name }}</div>
                                    <div class="text-muted small">{{ $req->user->email ?? '' }}</div>
                                </td>
                                <td class="text-end text-primary fw-bold">{{ number_format($req->amount) }} đ</td>
                                <td>{{ $req->bankAccount->bank_name ?? 'Chưa có' }}</td>
                                <td>{{ $req->bankAccount->account_number ?? 'Chưa có' }}</td>
                                <td>{{ $req->bankAccount->account_holder ?? 'Chưa có' }}</td>
                                <td>
                                    @if ($req->status == 'pending')
                                        <span class="badge bg-warning text-dark"><i class="fas fa-hourglass-half me-1"></i>Chờ duyệt</span>
                                    @elseif($req->status == 'approved')
                                        <span class="badge bg-success"><i class="fas fa-check-circle me-1"></i>Đã duyệt</span>
                                    @else
                                        <span class="badge bg-danger"><i class="fas fa-times-circle me-1"></i>Từ chối</span>
                                    @endif
                                </td>
                                <td class="text-danger small">{{ $req->note }}</td>
                                <td>{{ $req->created_at->format('d/m/Y H:i') }}</td>
                                <td>
                                    @if ($req->status == 'pending')
                                        <form action="{{ route('admin.withdraw.approve', $req->id) }}" method="POST" class="d-inline-block">
                                            @csrf
                                            <button class="btn btn-success btn-sm mb-1"><i class="fas fa-check"></i></button>
                                        </form>
                                        <form action="{{ route('admin.withdraw.reject', $req->id) }}" method="POST" class="d-inline-block">
                                            @csrf
                                            <button class="btn btn-danger btn-sm"><i class="fas fa-times"></i></button>
                                        </form>
                                    @else
                                        <span class="text-muted small">---</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="10" class="text-center text-muted">Không có yêu cầu nào.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="p-3">
                {{ $requests->links() }}
            </div>
        </div>
    </div>
</div>
@endsection

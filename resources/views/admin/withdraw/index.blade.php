@extends('admin.layouts.main')

@section('content')
    <div class="container py-4">
        <h3 class="mb-4 fw-bold">
            <i class="fas fa-wallet me-2 text-primary"></i>Danh sách yêu cầu rút tiền
        </h3>
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
                                            <span class="badge bg-warning text-dark">
                                                <i class="fas fa-hourglass-half me-1"></i>Chờ duyệt
                                            </span>
                                        @elseif($req->status == 'approved')
                                            <span class="badge bg-success">
                                                <i class="fas fa-check-circle me-1"></i>Đã duyệt
                                            </span>
                                        @else
                                            <span class="badge bg-danger">
                                                <i class="fas fa-times-circle me-1"></i>Từ chối
                                            </span>
                                        @endif
                                    </td>
                                    <td class="text-danger small">{{ $req->note }}</td>
                                    <td>{{ $req->created_at->format('d/m/Y H:i') }}</td>
                               <td class="text-center align-middle">
    @if ($req->status == 'pending')
        <div class="d-flex justify-content-center gap-2">
            {{-- Nút duyệt --}}
            <form action="{{ route('admin.withdraw.approve', $req->id) }}" method="POST" style="margin: 0;">
                @csrf
                <button class="btn btn-success btn-sm" type="submit">
                    <i class="fas fa-check"></i>
                </button>
            </form>

            {{-- Nút mở modal từ chối --}}
            <button type="button" class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#rejectModal{{ $req->id }}">
                <i class="fas fa-times"></i>
            </button>
        </div>
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

    {{-- Modal nhập lý do từ chối (đặt ngoài bảng để tránh lỗi UI) --}}
    @foreach ($requests as $req)
        @if ($req->status == 'pending')
            <div class="modal fade" id="rejectModal{{ $req->id }}" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
                    <div class="modal-content">
                        <form action="{{ route('admin.withdraw.reject', $req->id) }}" method="POST">
                            @csrf
                            <div class="modal-header">
                                <h5 class="modal-title text-danger mb-0">
                                    <i class="fas fa-times-circle me-2"></i>Từ chối yêu cầu
                                </h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Đóng"></button>
                            </div>
                            <div class="modal-body">
                                <div class="form-group">
                                    <label class="form-label fw-semibold">Lý do từ chối</label>
                                    <textarea name="note" class="form-control" rows="3" placeholder="Nhập lý do từ chối..." required></textarea>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                                <button type="submit" class="btn btn-danger">Xác nhận từ chối</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        @endif
    @endforeach
@endsection

{{-- Fix z-index để modal luôn nổi trên tất cả --}}
<style>
    /* Ensure modal and backdrop have high z-index */
    .modal {
        z-index: 1055 !important;
        /* Bootstrap 5 default is 1050, increase slightly */
    }

    .modal-backdrop {
        z-index: 1050 !important;
        /* Bootstrap 5 default is 1040, increase slightly */
    }

    /* Ensure modal is not affected by parent stacking contexts */
    .modal {
        position: fixed !important;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
    }

    /* Optional: Fix potential overflow issues in parent containers */
    body.modal-open {
        overflow: visible !important;
    }

    /* Ensure modal dialog is centered and visible */
    .modal-dialog {
        z-index: 1060 !important;
        /* Higher than modal to ensure dialog is on top */
    }

    td form,
    td button {
        display: inline-block;
        vertical-align: middle;
    }
</style>

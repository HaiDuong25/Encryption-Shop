@extends('admin.layouts.main')

@section('title', 'Quản lý mã giảm giá')

@section('content')
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <div class="container-fluid">
        <div class="card card-table">
            <div class="card-body">
                <div class="title-header option-title">
                    <h5>Danh sách mã giảm giá</h5>
                    <a href="{{ route('coupons.create') }}" class="btn btn-theme">
                        <i data-feather="plus"></i> Tạo mã mới
                    </a>
                </div>
                {{-- Form tìm kiếm --}}
                <form action="{{ route('coupons.index') }}" method="GET" class="mb-3 d-flex flex-wrap gap-2 align-items-end">
                    <div class="search-box" style="width:250px;">
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Tìm kiếm theo mã, mô tả..." class="form-control">
                    </div>
                    <button class="btn btn-primary me-2" type="submit">
                        <i class="ri-search-line"></i> Tìm
                    </button>
                    @if(request('search'))
                        <a href="{{ route('coupons.index') }}" class="btn btn-outline-secondary me-2 bg-dark">
                            <i class="ri-refresh-line"></i> Xóa bộ lọc
                        </a>
                    @endif
                </form>

                <div class="table-responsive table-product mt-3">
                    <table class="table theme-table align-middle">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Mã giảm giá</th>
                                <th>Mô tả</th>
                                <th>Giá trị</th>
                                <th>Đơn tối thiểu</th>
                                <th>Giới hạn sử dụng</th>
                                <th>Đã sử dụng</th>
                                <th>Một lần/User</th>
                                <th>Trạng thái</th>
                                <th>Hành động</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($coupons as $coupon)
                                                    <tr>
                                                        <td>{{ $coupon->id }}</td>
                                                        <td><strong>{{ $coupon->code }}</strong></td>
                                                        <td>
                                                            @if($coupon->description)
                                                                <span class="text-muted">{{ Str::limit($coupon->description, 30) }}</span>
                                                            @else
                                                                <span class="text-muted fst-italic">Không có mô tả</span>
                                                            @endif
                                                        </td>
                                                        <td>
                                                            @if($coupon->discount_type === 'percentage')
                                                                {{ $coupon->discount }}%
                                                                @if($coupon->max_discount_amount)
                                                                    <small class="text-muted d-block">
                                                                        (tối đa {{ format_vnd($coupon->max_discount_amount) }}₫)
                                                                    </small>
                                                                @endif
                                                            @else
                                                                {{ format_vnd($coupon->discount) }}₫
                                                            @endif
                                                        </td>
                                                        <td>
                                                            @if($coupon->min_order_amount)
                                                                <span class="badge bg-info">{{ format_vnd($coupon->min_order_amount) }}₫</span>
                                                            @else
                                                                <span class="text-muted">Không</span>
                                                            @endif
                                                        </td>
                                                        <td>
                                                            @if($coupon->usage_limit > 0)
                                                                <span class="badge bg-info">{{ $coupon->usage_limit }}</span>
                                                            @else
                                                                <span class="badge bg-secondary">Không giới hạn</span>
                                                            @endif
                                                        </td>
                                                        <td>
                                                            @php
                                                                $usedCount = $coupon->couponUses ? $coupon->couponUses->count() : 0;
                                                            @endphp
                                                            <span class="badge {{ $usedCount > 0 ? 'bg-warning' : 'bg-light text-dark' }}">
                                                                {{ $usedCount }}
                                                            </span>
                                                            @if($coupon->usage_limit > 0)
                                                                <small class="text-muted d-block">
                                                                    Còn: {{ max(0, $coupon->usage_limit - $usedCount) }}
                                                                </small>
                                                            @endif
                                                        </td>
                                                        <td>
                                                            @if($coupon->is_one_time_per_user)
                                                                <span class="badge bg-warning">Có</span>
                                                            @else
                                                                <span class="badge bg-secondary">Không</span>
                                                            @endif
                                                        </td>
                                                        <td>
                                                @php
                                                    $now = \Carbon\Carbon::now();
                                                    $start = $coupon->start_date ? \Carbon\Carbon::parse($coupon->start_date) : null;
                                                    $end = $coupon->end_date ? \Carbon\Carbon::parse($coupon->end_date) : null;
                                                @endphp

                                                            @if($start && $end && $now->between($start, $end))
                                                                <span class="badge bg-primary">
                                                                    Còn {{ floor($now->diffInDays($end) + 1) }} ngày
                                                                </span>
                                                            @elseif($end && $now->gt($end))
                                                                <span class="badge bg-danger">Hết hạn</span>
                                                            @elseif($start && $now->lt($start))
                                                                <span class="badge bg-success">Chưa bắt đầu</span>
                                                            @else
                                                                <span class="badge bg-warning">Không xác định</span>
                                                            @endif
                                                        </td>
                                                        <td>
                                                            <ul class="d-flex flex-wrap gap-2 mb-0" style="list-style:none; padding-left:0;">
                                                                <li>
                                                                    <a href="{{ route('coupons.edit', $coupon->id) }}" class="btn btn-link p-0"
                                                                        title="Sửa">
                                                                        <i data-feather="edit"></i>
                                                                    </a>
                                                                </li>
                                                                <li>
                                                                    <button type="button" class="btn btn-link p-0 text-danger delete-btn"
                                                                        data-id="{{ $coupon->id }}" data-name="{{ $coupon->code }}" title="Xoá">
                                                                        <i data-feather="trash-2"></i>
                                                                    </button>
                                                                </li>
                                                            </ul>
                                                        </td>
                                                    </tr>
                            @empty
                                <tr>
                                    <td colspan="10" class="text-center text-muted">
                                        {{ request('search') ? 'Không tìm thấy mã giảm giá nào phù hợp.' : 'Chưa có mã giảm giá nào.' }}
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>

                    @if(method_exists($coupons, 'links'))
                        <div class="mt-3 d-flex justify-content-end">
                            {{ $coupons->withQueryString()->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        if (typeof feather !== 'undefined') {
            feather.replace();
        }

        // AJAX Delete functionality
        document.querySelectorAll('.delete-btn').forEach(button => {
            button.addEventListener('click', function () {
                const couponId = this.dataset.id;
                const couponCode = this.dataset.name;

                if (confirm(`Bạn có chắc muốn xóa mã "${couponCode}"?`)) {
                    // Show loading state
                    const icon = this.querySelector('i');
                    const originalContent = this.innerHTML;
                    this.innerHTML = '<i data-feather="loader" class="rotating"></i>';
                    this.disabled = true;

                    // Get CSRF token
                    const csrfToken = document.querySelector('meta[name="csrf-token"]');
                    if (!csrfToken) {
                        alert('Không tìm thấy CSRF token. Vui lòng tải lại trang.');
                        this.innerHTML = originalContent;
                        this.disabled = false;
                        feather.replace();
                        return;
                    }

                    fetch(`/admin/coupons/${couponId}`, {
                        method: 'DELETE',
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'X-CSRF-TOKEN': csrfToken.content,
                            'Content-Type': 'application/json',
                            'Accept': 'application/json'
                        }
                    })
                        .then(response => {
                            if (!response.ok) {
                                throw new Error(`HTTP error! status: ${response.status}`);
                            }
                            return response.json();
                        })
                        .then(data => {
                            if (data.success) {
                                // Remove the row from table
                                const row = this.closest('tr');
                                row.style.transition = 'opacity 0.3s ease';
                                row.style.opacity = '0';

                                setTimeout(() => {
                                    row.remove();                                                // Check if table is empty
                                    const tbody = document.querySelector('tbody');
                                    if (tbody && tbody.children.length === 0) {
                                        const emptyRow = document.createElement('tr');
                                        emptyRow.innerHTML = `
                                                            <td colspan="10" class="text-center text-muted">
                                                                Chưa có mã giảm giá nào.
                                                            </td>
                                                        `;
                                        tbody.appendChild(emptyRow);
                                    }
                                }, 300);

                                // Show success message
                                showAlert('success', data.message);
                            } else {
                                throw new Error(data.message || 'Có lỗi xảy ra khi xóa mã giảm giá!');
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);

                            let errorMessage = 'Có lỗi xảy ra khi xóa mã giảm giá!';
                            if (error.message && error.message !== 'Failed to fetch') {
                                errorMessage = error.message;
                            }

                            showAlert('danger', errorMessage);

                            // Restore button state
                            this.innerHTML = originalContent;
                            this.disabled = false;
                            feather.replace();
                        });
                }
            });
        });

        // Function to show alert messages
        function showAlert(type, message) {
            // Remove existing alerts
            const existingAlerts = document.querySelectorAll('.alert');
            existingAlerts.forEach(alert => alert.remove());

            const alertDiv = document.createElement('div');
            alertDiv.className = `alert alert-${type} alert-dismissible fade show`;
            alertDiv.innerHTML = `
                    ${message}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                `;

            const container = document.querySelector('.container-fluid');
            const card = document.querySelector('.card');
            container.insertBefore(alertDiv, card);

            // Auto hide after 5 seconds
            setTimeout(() => {
                if (alertDiv.parentNode) {
                    alertDiv.remove();
                }
            }, 5000);
        }
    </script>
    <style>
        .rotating {
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            from {
                transform: rotate(0deg);
            }

            to {
                transform: rotate(360deg);
            }
        }
    </style>
@endpush
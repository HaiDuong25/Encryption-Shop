@extends('admin.layouts.main')

@section('title', 'Quản lý người dùng')

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
                <div class="title-header option-title d-sm-flex d-block justify-content-between align-items-center">
                    <h5>Danh sách người dùng</h5>
                    <div class="right-options d-flex gap-2 align-items-center">
                        {{-- Form tìm kiếm theo tên, email hoặc điện thoại --}}
                        <form method="GET" action="{{ route('users.index') }}" class="d-flex">
                            <input type="text" name="search" class="form-control me-2"
                                placeholder="Tìm theo tên, email hoặc SĐT..." value="{{ request('search') }}"
                                style="width: 280px;">
                            <button type="submit" class="btn btn-primary me-2">
                                <i class="ri-search-line"></i> Tìm
                            </button>
                            @if(request('search'))
                                <a href="{{ route('users.index') }}" class="btn btn-outline-secondary me-2 bg-dark">
                                    <i class="ri-refresh-line"></i> Xóa bộ lọc
                                </a>
                            @endif
                        </form>
                        <a href="{{ route('users.create') }}" class="btn btn-theme">
                            <i data-feather="plus"></i> Thêm mới
                        </a>
                    </div>
                </div>

                <div class="table-responsive table-product">
                    <table class="table theme-table">
                        <thead>
                            <tr>
                                <th>Ảnh</th>
                                <th>Tên</th>
                                <th>Quyền</th>
                                <th>Điện thoại</th>
                                <th>Email</th>
                                <th>Địa chỉ</th>
                                <th>Trạng thái</th>
                                <th>Hành động</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($users as $user)
                                <tr>
                                    <td class="text-center">
                                        @if ($user->avatar)
                                            <img src="{{ asset('storage/' . $user->avatar) }}" alt="{{ $user->name }}" width="100">
                                        @endif
                                    </td>
                                    <td>
                                        <span>{{ $user->name }}</span>
                                    </td>
                                    <td>{{ $user->role }}</td>
                                    <td>{{ $user->phone }}</td>
                                    <td>{{ $user->email }}</td>
                                    <td>{{ $user->address }}</td>
                                    <td>
                                        @if($user->status == 'active')
                                            <span class="badge bg-success">Hoạt động</span>
                                        @else
                                            <span class="badge bg-danger">Khóa</span>
                                        @endif
                                    </td>
                                    <td>
                                        <ul>
                                            <li><a href="{{ route('users.show', $user) }}" title="Xem chi tiết"><i
                                                        class="ri-eye-line"></i></a></li>

                                            <li>
                                                @if($user->role !== 'admin')
                                                    <button type="button" class="btn btn-link p-0 toggle-btn"
                                                        data-id="{{ $user->id }}" data-name="{{ $user->name }}"
                                                        data-current-status="{{ $user->status }}">
                                                        {{ $user->status == 'active' ? 'Khóa' : 'Mở khóa' }}
                                                    </button>
                                                @endif
                                            </li>

                                            <li><a href="{{ route('users.edit', $user) }}" title="Chỉnh sửa"><i
                                                        class="ri-pencil-line"></i></a></li>

                                            <!-- Nút xóa đã bị loại bỏ theo yêu cầu -->
                                        </ul>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                    {{ $users->links() }}
                </div>
            </div>
        </div>
    </div>

    <!-- Modal xác nhận -->
    <div class="modal fade" id="confirmModal" tabindex="-1" aria-labelledby="confirmModalLabel" aria-hidden="true"
        style="z-index: 9999;">
        <div class="modal-dialog modal-dialog-centered"
            style="position: fixed; top: 50%; left: 50%; transform: translate(-50%, -50%); z-index: 10000;">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="confirmModalLabel">
                        <i class="ri-question-line text-warning me-2"></i>
                        Xác nhận hành động
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body text-center">
                    <div id="confirmIcon" class="mb-3">
                        <i class="ri-question-line" style="font-size: 48px; color: #ffc107;"></i>
                    </div>
                    <p id="confirmMessage" class="mb-0"></p>
                </div>
                <div class="modal-footer justify-content-center">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="ri-close-line me-1"></i>Hủy
                    </button>
                    <button type="button" class="btn btn-danger" id="confirmButton">
                        <i class="ri-check-line me-1"></i>Xác nhận
                    </button>
                </div>
            </div>
        </div>
    </div>



    <script>
        // Function để hiển thị alert
        function showAlert(message, type = 'success') {
            const alertDiv = document.createElement('div');
            alertDiv.className = `alert alert-${type} alert-dismissible fade show`;
            alertDiv.innerHTML = `
            ${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
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

        // Function để hiển thị modal xác nhận
        function showConfirmModal(message, onConfirm, type = 'warning') {
            const modal = new bootstrap.Modal(document.getElementById('confirmModal'));
            const confirmMessage = document.getElementById('confirmMessage');
            const confirmButton = document.getElementById('confirmButton');
            const confirmIcon = document.getElementById('confirmIcon');

            // Cập nhật nội dung modal
            confirmMessage.textContent = message;

            // Cập nhật icon và màu sắc dựa trên type
            if (type === 'danger') {
                confirmIcon.innerHTML = '<i class="ri-delete-bin-line" style="font-size: 48px; color: #dc3545;"></i>';
                confirmButton.className = 'btn btn-danger';
                confirmButton.innerHTML = '<i class="ri-delete-bin-line me-1"></i>Xóa';
            } else if (type === 'warning') {
                confirmIcon.innerHTML = '<i class="ri-alert-line" style="font-size: 48px; color: #ffc107;"></i>';
                confirmButton.className = 'btn btn-warning';
                confirmButton.innerHTML = '<i class="ri-check-line me-1"></i>Xác nhận';
            } else {
                confirmIcon.innerHTML = '<i class="ri-question-line" style="font-size: 48px; color: #0d6efd;"></i>';
                confirmButton.className = 'btn btn-primary';
                confirmButton.innerHTML = '<i class="ri-check-line me-1"></i>Xác nhận';
            }

            // Xóa event listener cũ và thêm mới
            const newConfirmButton = confirmButton.cloneNode(true);
            confirmButton.parentNode.replaceChild(newConfirmButton, confirmButton);

            // Thêm event listener cho nút xác nhận
            newConfirmButton.addEventListener('click', function () {
                modal.hide();
                onConfirm();
            });

            // Hiển thị modal
            modal.show();
        }

        document.addEventListener('DOMContentLoaded', function () {
            // AJAX Toggle Status functionality
            document.querySelectorAll('.toggle-btn').forEach(button => {
                button.addEventListener('click', function () {
                    const userId = this.dataset.id;
                    const userName = this.dataset.name;
                    const currentStatus = this.dataset.currentStatus;
                    const action = currentStatus === 'active' ? 'khóa' : 'mở khóa';

                    // Sử dụng modal xác nhận thay vì confirm()
                    showConfirmModal(
                        `Bạn chắc chắn muốn ${action} người dùng "${userName}"?`,
                        () => {
                            // Show loading state
                            const originalText = this.textContent;
                            this.textContent = 'Đang xử lý...';
                            this.disabled = true;

                            // Get CSRF token
                            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
                            if (!csrfToken) {
                                showAlert('Lỗi CSRF token không tìm thấy!', 'danger');
                                this.textContent = originalText;
                                this.disabled = false;
                                return;
                            }

                            fetch(`/admin/users/${userId}/toggle`, {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'X-Requested-With': 'XMLHttpRequest',
                                    'X-CSRF-TOKEN': csrfToken
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
                                        // Update button text and status
                                        const newStatus = data.status;
                                        this.dataset.currentStatus = newStatus;
                                        this.textContent = newStatus === 'active' ? 'Khóa' : 'Mở khóa';

                                        // Update status badge
                                        const row = this.closest('tr');
                                        const statusBadge = row.querySelector('.badge');
                                        if (statusBadge) {
                                            if (newStatus === 'active') {
                                                statusBadge.className = 'badge bg-success';
                                                statusBadge.textContent = 'Hoạt động';
                                            } else {
                                                statusBadge.className = 'badge bg-danger';
                                                statusBadge.textContent = 'Khóa';
                                            }
                                        }

                                        // Show success message
                                        showAlert(data.message, 'success');
                                    } else {
                                        showAlert(data.message || 'Có lỗi xảy ra!', 'danger');
                                        this.textContent = originalText;
                                    }
                                })
                                .catch(error => {
                                    console.error('Error:', error);
                                    showAlert('Có lỗi kết nối xảy ra!', 'danger');
                                    this.textContent = originalText;
                                })
                                .finally(() => {
                                    this.disabled = false;
                                });
                        },
                        'warning'
                    );
                });
            });

            // AJAX Delete functionality
            document.querySelectorAll('.delete-btn').forEach(button => {
                button.addEventListener('click', function () {
                    const userId = this.dataset.id;
                    const userName = this.dataset.name;

                    // Sử dụng modal xác nhận thay vì confirm()
                    showConfirmModal(
                        `Bạn có chắc muốn xóa người dùng "${userName}"? Hành động này không thể hoàn tác!`,
                        () => {
                            // Show loading state
                            this.innerHTML = '<i class="ri-loader-4-line"></i>';
                            this.disabled = true;

                            // Get CSRF token
                            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
                            if (!csrfToken) {
                                showAlert('Lỗi CSRF token không tìm thấy!', 'danger');
                                this.innerHTML = '<i class="ri-delete-bin-line"></i>';
                                this.disabled = false;
                                return;
                            }

                            fetch(`/admin/users/${userId}`, {
                                method: 'DELETE',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'X-Requested-With': 'XMLHttpRequest',
                                    'X-CSRF-TOKEN': csrfToken
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
                                        // Remove the row from table with animation
                                        const row = this.closest('tr');
                                        row.style.transition = 'all 0.3s ease';
                                        row.style.opacity = '0';
                                        row.style.transform = 'translateX(-100%)';

                                        setTimeout(() => {
                                            row.remove();
                                        }, 300);

                                        // Show success message
                                        showAlert(data.message, 'success');
                                    } else {
                                        showAlert(data.message || 'Có lỗi xảy ra khi xóa người dùng!', 'danger');
                                        // Restore button state
                                        this.innerHTML = '<i class="ri-delete-bin-line"></i>';
                                        this.disabled = false;
                                    }
                                })
                                .catch(error => {
                                    console.error('Error:', error);
                                    showAlert('Có lỗi kết nối xảy ra khi xóa người dùng!', 'danger');
                                    // Restore button state
                                    this.innerHTML = '<i class="ri-delete-bin-line"></i>';
                                    this.disabled = false;
                                });
                        },
                        'danger'
                    );
                });
            });
        });
    </script>
@endsection
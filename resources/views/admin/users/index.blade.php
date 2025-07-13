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
            <div class="title-header option-title">
                <h5>Danh sách người dùng</h5>
                <a href="{{ route('users.create') }}" class="btn btn-theme">
                    <i data-feather="plus"></i> Thêm mới
                </a>
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
                                    <li>
                                        @if($user->role !== 'admin')
                                        <button type="button" class="btn btn-link p-0 toggle-btn" 
                                            data-id="{{ $user->id }}" 
                                            data-name="{{ $user->name }}"
                                            data-current-status="{{ $user->status }}">
                                            {{ $user->status == 'active' ? 'Khóa' : 'Mở khóa' }}
                                        </button>
                                        @endif
                                    </li>

                                    <li><a href="{{ route('users.edit', $user) }}"><i class="ri-pencil-line"></i></a></li>

                                    <li>
                                        @if($user->role !== 'admin')
                                        <button type="button" class="btn btn-link p-0 text-danger delete-btn" 
                                            data-id="{{ $user->id }}" 
                                            data-name="{{ $user->name }}">
                                            <i class="ri-delete-bin-line"></i>
                                        </button>
                                        @else
                                        <span class="text-muted" style="font-size: 14px;">
                                            <i class="ri-delete-bin-line"></i>
                                        </span>
                                        @endif
                                    </li>
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

<script>
document.addEventListener('DOMContentLoaded', function() {
    // AJAX Toggle Status functionality
    document.querySelectorAll('.toggle-btn').forEach(button => {
        button.addEventListener('click', function() {
            const userId = this.dataset.id;
            const userName = this.dataset.name;
            const currentStatus = this.dataset.currentStatus;
            const action = currentStatus === 'active' ? 'khóa' : 'mở khóa';
            
            if (confirm(`Bạn chắc chắn muốn ${action} người dùng "${userName}"?`)) {
                // Show loading state
                const originalText = this.textContent;
                this.textContent = 'Đang xử lý...';
                this.disabled = true;
                
                fetch(`/admin/users/${userId}/toggle`, {
                    method: 'POST',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    }
                })
                .then(response => response.json())
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
                        const alertDiv = document.createElement('div');
                        alertDiv.className = 'alert alert-success alert-dismissible fade show';
                        alertDiv.innerHTML = `
                            ${data.message}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        `;
                        document.querySelector('.container-fluid').insertBefore(alertDiv, document.querySelector('.card'));
                        
                        // Auto hide after 3 seconds
                        setTimeout(() => {
                            if (alertDiv.parentNode) {
                                alertDiv.remove();
                            }
                        }, 3000);
                    } else {
                        alert(data.message || 'Có lỗi xảy ra!');
                        this.textContent = originalText;
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Có lỗi xảy ra!');
                    this.textContent = originalText;
                })
                .finally(() => {
                    this.disabled = false;
                });
            }
        });
    });
    
    // AJAX Delete functionality
    document.querySelectorAll('.delete-btn').forEach(button => {
        button.addEventListener('click', function() {
            const userId = this.dataset.id;
            const userName = this.dataset.name;
            
            if (confirm(`Bạn có chắc muốn xóa người dùng "${userName}"?`)) {
                // Show loading state
                this.innerHTML = '<i class="ri-loader-4-line"></i>';
                this.disabled = true;
                
                fetch(`/admin/users/${userId}`, {
                    method: 'DELETE',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Remove the row from table
                        const row = this.closest('tr');
                        row.remove();
                        
                        // Show success message
                        const alertDiv = document.createElement('div');
                        alertDiv.className = 'alert alert-success alert-dismissible fade show';
                        alertDiv.innerHTML = `
                            ${data.message}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        `;
                        document.querySelector('.container-fluid').insertBefore(alertDiv, document.querySelector('.card'));
                        
                        // Auto hide after 3 seconds
                        setTimeout(() => {
                            if (alertDiv.parentNode) {
                                alertDiv.remove();
                            }
                        }, 3000);
                    } else {
                        alert(data.message || 'Có lỗi xảy ra khi xóa người dùng!');
                        // Restore button state
                        this.innerHTML = '<i class="ri-delete-bin-line"></i>';
                        this.disabled = false;
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Có lỗi xảy ra khi xóa người dùng!');
                    // Restore button state
                    this.innerHTML = '<i class="ri-delete-bin-line"></i>';
                    this.disabled = false;
                });
            }
        });
    });
});
</script>
@endsection
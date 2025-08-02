<div class="dashboard-left-sidebar">
    <!-- Profile Card -->
    <div class="profile-card">
        <div class="profile-header">
            <div class="profile-bg" 
                 style="background-image: url('{{ auth()->user()->cover_image ? asset('storage/' . auth()->user()->cover_image) : '' }}')">
                <div class="cover-overlay">
                    <button class="cover-upload-btn" onclick="document.getElementById('cover-upload').click()">
                        <i class="fas fa-camera"></i>
                        <span>Đổi ảnh bìa</span>
                    </button>
                    <input type="file" id="cover-upload" accept="image/*" onchange="uploadCoverImage(this)" style="display: none;">
                </div>
            </div>
            <div class="profile-avatar-container">
                <div class="profile-avatar">
                    <img id="avatar-preview" 
                         src="{{ auth()->user()->avatar ? asset('storage/' . auth()->user()->avatar) : asset('assets-front/images/inner-page/users/1.png') }}" 
                         alt="Ảnh đại diện">
                    <div class="avatar-overlay">
                        <i class="fas fa-camera"></i>
                        <input type="file" id="avatar-upload" accept="image/*" onchange="uploadAvatar(this)" title="nhấn để thay đổi ảnh đại diện">
                    </div>
                </div>
            </div>
        </div>
        
        <div class="profile-info">
            <h4 class="profile-name">{{ auth()->user()->name }}</h4>
            <p class="profile-email">{{ auth()->user()->email }}</p>
            @if(auth()->user()->phone)
                <p class="profile-phone">
                    <i class="fas fa-phone me-1"></i>{{ auth()->user()->phone }}
                </p>
            @endif
        </div>
    </div>

    <!-- Navigation Menu -->
    <div class="sidebar-menu">
        <div class="menu-section">
            <h6 class="menu-title">Tài khoản</h6>
            <ul class="menu-list">
                <li class="menu-item">
                    <a href="{{ route('account.index') }}" class="menu-link {{ request()->routeIs('account.index') ? 'active' : '' }}">
                        <div class="menu-icon">
                            <i class="fas fa-user"></i>
                        </div>
                        <span class="menu-text">Hồ sơ cá nhân</span>
                    </a>
                </li>
                <li class="menu-item">
                    <a href="{{ route('account.editProfile') }}" class="menu-link {{ request()->routeIs('account.editProfile') ? 'active' : '' }}">
                        <div class="menu-icon">
                            <i class="fas fa-edit"></i>
                        </div>
                        <span class="menu-text">Chỉnh sửa hồ sơ</span>
                    </a>
                </li>
                <li class="menu-item">
                    <a href="{{ route('account.changePassword') }}" class="menu-link {{ request()->routeIs('account.changePassword') ? 'active' : '' }}">
                        <div class="menu-icon">
                            <i class="fas fa-key"></i>
                        </div>
                        <span class="menu-text">Đổi mật khẩu</span>
                    </a>
                </li>
            </ul>
        </div>

        <div class="menu-section">
            <h6 class="menu-title">Mua sắm</h6>
            <ul class="menu-list">
                <li class="menu-item">
                    <a href="{{ route('client.addresses.index') }}" class="menu-link {{ request()->routeIs('client.addresses.*') ? 'active' : '' }}">
                        <div class="menu-icon">
                            <i class="fas fa-map-marker-alt"></i>
                        </div>
                        <span class="menu-text">Sổ địa chỉ</span>
                    </a>
                </li>
                <li class="menu-item">
                    <a href="{{ route('client.orders.index') }}" class="menu-link {{ request()->routeIs('orders.*') ? 'active' : '' }}">
                        <div class="menu-icon">
                            <i class="fas fa-shopping-bag"></i>
                        </div>
                        <span class="menu-text">Đơn hàng của tôi</span>
                    </a>
                </li>
                <li class="menu-item">
                    <a href="{{ route('wishlist.index') }}" class="menu-link {{ request()->routeIs('wishlist.*') ? 'active' : '' }}">
                        <div class="menu-icon">
                            <i class="fas fa-heart"></i>
                        </div>
                        <span class="menu-text">Sản phẩm yêu thích</span>
                    </a>
                </li>
            </ul>
        </div>

        <div class="menu-section">
            <div class="logout-section">
                <form action="{{ route('logout') }}" method="POST" class="d-inline">
                    @csrf
                    <button type="submit" class="logout-btn" onclick="return confirm('Bạn có chắc muốn đăng xuất?')">
                        <div class="menu-icon">
                            <i class="fas fa-sign-out-alt"></i>
                        </div>
                        <span class="menu-text">Đăng xuất</span>
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
function uploadAvatar(input) {
    console.log('uploadAvatar called'); // Debug
    
    if (input.files && input.files[0]) {
        const file = input.files[0];
        console.log('File selected:', file.name, file.size); // Debug
        
        // Kiểm tra kích thước file (max 2MB)
        if (file.size > 2 * 1024 * 1024) {
            alert('Ảnh không được vượt quá 2MB');
            return;
        }
        
        // Kiểm tra định dạng file
        const allowedTypes = ['image/jpeg', 'image/png', 'image/jpg', 'image/gif'];
        if (!allowedTypes.includes(file.type)) {
            alert('Chỉ chấp nhận ảnh định dạng JPG, PNG, GIF');
            return;
        }
        
        // Preview ảnh ngay lập tức
        const reader = new FileReader();
        reader.onload = function(e) {
            // Sử dụng function global để đồng bộ preview
            if (window.syncAvatarGlobally) {
                window.syncAvatarGlobally(e.target.result);
            } else {
                // Fallback
                document.getElementById('avatar-preview').src = e.target.result;
            }
        }
        reader.readAsDataURL(file);
        
        // Upload ảnh
        const formData = new FormData();
        formData.append('avatar', file);
        
        const csrfToken = document.querySelector('meta[name="csrf-token"]');
        if (csrfToken) {
            formData.append('_token', csrfToken.getAttribute('content'));
        } else {
            console.error('CSRF token not found');
            alert('Lỗi bảo mật. Vui lòng tải lại trang.');
            return;
        }
        
        console.log('Sending upload request...'); // Debug
        
        fetch('{{ route('account.uploadAvatar') }}', {
            method: 'POST',
            body: formData
        })
        .then(response => {
            console.log('Response status:', response.status); // Debug
            return response.json();
        })
        .then(data => {
            console.log('Response data:', data); // Debug
            if (data.success) {
                // Sử dụng function global để đồng bộ avatar
                if (window.syncAvatarGlobally) {
                    window.syncAvatarGlobally(data.avatar_url);
                } else {
                    // Fallback nếu function global chưa load
                    document.getElementById('avatar-preview').src = data.avatar_url;
                }
                
                // Dispatch custom event để các component khác có thể lắng nghe
                const avatarUpdateEvent = new CustomEvent('avatarUpdated', {
                    detail: { avatarUrl: data.avatar_url }
                });
                document.dispatchEvent(avatarUpdateEvent);
                
                // Hiển thị thông báo thành công
                showToast(data.message, 'success');
            } else {
                alert(data.message || 'Có lỗi xảy ra khi tải ảnh lên');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Có lỗi xảy ra khi tải ảnh lên');
        });
    }
}

function uploadCoverImage(input) {
    if (input.files && input.files[0]) {
        const file = input.files[0];
        
        // Kiểm tra kích thước file (max 5MB)
        if (file.size > 5 * 1024 * 1024) {
            alert('Ảnh bìa không được vượt quá 5MB');
            return;
        }
        
        // Kiểm tra định dạng file
        const allowedTypes = ['image/jpeg', 'image/png', 'image/jpg', 'image/gif'];
        if (!allowedTypes.includes(file.type)) {
            alert('Chỉ chấp nhận ảnh định dạng JPG, PNG, GIF');
            return;
        }
        
        // Preview ảnh ngay lập tức
        const reader = new FileReader();
        reader.onload = function(e) {
            const profileBg = document.querySelector('.profile-bg');
            profileBg.style.backgroundImage = `url('${e.target.result}')`;
        }
        reader.readAsDataURL(file);
        
        // Upload ảnh
        const formData = new FormData();
        formData.append('cover_image', file);
        formData.append('_token', document.querySelector('meta[name="csrf-token"]').getAttribute('content'));
        
        fetch('{{ route('account.uploadCoverImage') }}', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Cập nhật ảnh bìa thành công
                const profileBg = document.querySelector('.profile-bg');
                profileBg.style.backgroundImage = `url('${data.cover_image_url}')`;
                
                // Hiển thị thông báo thành công
                showToast(data.message, 'success');
            } else {
                alert(data.message || 'Có lỗi xảy ra khi tải ảnh bìa lên');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Có lỗi xảy ra khi tải ảnh bìa lên');
        });
    }
}

function showToast(message, type = 'success') {
    // Xóa toast cũ nếu có
    const existingToast = document.querySelector('.toast-notification');
    if (existingToast) {
        existingToast.remove();
    }
    
    // Tạo toast mới
    const toast = document.createElement('div');
    toast.className = `toast-notification ${type}`;
    toast.textContent = message;
    document.body.appendChild(toast);
    
    setTimeout(() => {
        toast.remove();
    }, 3000);
}
</script>

<style>
/* === DASHBOARD SIDEBAR STYLES === */
.dashboard-left-sidebar {
    background: #fff;
    border-radius: 15px;
    box-shadow: 0 2px 20px rgba(0, 0, 0, 0.08);
    position: sticky;
    top: 20px;
    height: calc(100vh - 40px);
    overflow-y: auto;
    align-self: flex-start;
}

/* === PROFILE CARD === */
.profile-card {
    position: relative;
    background: #fff;
}

.profile-header {
    position: relative;
    height: 200px;
    overflow: visible;
}

.profile-bg {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    background-size: cover;
    background-position: center;
    background-repeat: no-repeat;
    transition: all 0.3s ease;
}

.cover-overlay {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(0, 0, 0, 0.3);
    display: flex;
    align-items: center;
    justify-content: center;
    opacity: 0;
    transition: all 0.3s ease;
}

.profile-header:hover .cover-overlay {
    opacity: 1;
}

.cover-upload-btn {
    background: rgba(255, 255, 255, 0.9);
    border: none;
    border-radius: 8px;
    padding: 10px 15px;
    color: #333;
    font-size: 14px;
    font-weight: 500;
    cursor: pointer;
    transition: all 0.3s ease;
    display: flex;
    align-items: center;
    gap: 8px;
}

.cover-upload-btn:hover {
    background: white;
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
}

.profile-avatar-container {
    position: absolute;
    bottom: -50px;
    left: 50%;
    transform: translateX(-50%);
    z-index: 10;
}

.profile-avatar {
    position: relative;
    width: 100px;
    height: 100px;
    border-radius: 50%;
    border: 4px solid #fff;
    overflow: hidden;
    background: #f8f9fa;
    cursor: pointer;
    transition: all 0.3s ease;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
}

.profile-avatar:hover {
    transform: scale(1.05);
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
}

.profile-avatar img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: all 0.3s ease;
}

.avatar-overlay {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(0, 0, 0, 0.5);
    display: flex;
    align-items: center;
    justify-content: center;
    opacity: 0;
    transition: all 0.3s ease;
    color: white;
    font-size: 18px;
}

.profile-avatar:hover .avatar-overlay {
    opacity: 1;
}

.avatar-overlay input[type="file"] {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    opacity: 0;
    cursor: pointer;
}

.profile-info {
    text-align: center;
    padding: 65px 20px 25px;
    border-bottom: 1px solid #f0f0f0;
}

.profile-name {
    font-size: 18px;
    font-weight: 600;
    color: #2c3e50;
    margin-bottom: 5px;
}

.profile-email {
    font-size: 14px;
    color: #7f8c8d;
    margin-bottom: 8px;
}

.profile-phone {
    font-size: 13px;
    color: #95a5a6;
    margin: 0 0 8px 0;
}

.profile-birthday {
    font-size: 13px;
    color: #95a5a6;
    margin: 0 0 8px 0;
}

.profile-gender {
    font-size: 13px;
    color: #95a5a6;
    margin: 0;
}

/* === SIDEBAR MENU === */
.sidebar-menu {
    padding: 0;
}

.menu-section {
    border-bottom: 1px solid #f0f0f0;
}

.menu-section:last-child {
    border-bottom: none;
}

.menu-title {
    font-size: 12px;
    font-weight: 600;
    text-transform: uppercase;
    color: #95a5a6;
    letter-spacing: 0.5px;
    padding: 20px 25px 10px;
    margin: 0;
}

.menu-list {
    list-style: none;
    padding: 0;
    margin: 0 0 15px 0;
}

.menu-item {
    margin: 0;
}

.menu-link {
    display: flex;
    align-items: center;
    padding: 12px 25px;
    color: #5a6c7d;
    text-decoration: none;
    transition: all 0.3s ease;
    border-left: 3px solid transparent;
    position: relative;
}

.menu-link:hover {
    background: #f8f9fa;
    color: #667eea;
    text-decoration: none;
    border-left-color: #667eea;
}

.menu-link.active {
    background: linear-gradient(135deg, rgba(102, 126, 234, 0.1) 0%, rgba(118, 75, 162, 0.1) 100%);
    color: #667eea;
    border-left-color: #667eea;
    font-weight: 500;
}

.menu-link.active::before {
    content: '';
    position: absolute;
    right: 0;
    top: 0;
    bottom: 0;
    width: 3px;
    background: #667eea;
}

.menu-icon {
    width: 40px;
    height: 40px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 8px;
    background: #f8f9fa;
    margin-right: 15px;
    transition: all 0.3s ease;
    flex-shrink: 0;
}

.menu-link:hover .menu-icon {
    background: #667eea;
    color: white;
    transform: translateY(-1px);
}

.menu-link.active .menu-icon {
    background: #667eea;
    color: white;
}

.menu-icon i {
    font-size: 16px;
}

.menu-text {
    font-size: 14px;
    font-weight: 500;
}

/* === LOGOUT SECTION === */
.logout-section {
    padding: 15px 0;
}

.logout-btn {
    display: flex;
    align-items: center;
    width: 100%;
    padding: 12px 25px;
    background: none;
    border: none;
    color: #e74c3c;
    text-decoration: none;
    transition: all 0.3s ease;
    border-left: 3px solid transparent;
    cursor: pointer;
}

.logout-btn:hover {
    background: rgba(231, 76, 60, 0.1);
    border-left-color: #e74c3c;
}

.logout-btn .menu-icon {
    background: rgba(231, 76, 60, 0.1);
    color: #e74c3c;
}

.logout-btn:hover .menu-icon {
    background: #e74c3c;
    color: white;
}

/* === TOAST NOTIFICATION === */
.toast-notification {
    position: fixed;
    top: 20px;
    right: 20px;
    background: #28a745;
    color: white;
    padding: 15px 20px;
    border-radius: 10px;
    z-index: 9999;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.15);
    display: flex;
    align-items: center;
    animation: slideInRight 0.3s ease;
}

.toast-notification.success {
    background: linear-gradient(135deg, #28a745, #20c997);
}

.toast-notification::before {
    content: '✓';
    margin-right: 10px;
    font-weight: bold;
    font-size: 16px;
}

@keyframes slideInRight {
    from {
        transform: translateX(100%);
        opacity: 0;
    }
    to {
        transform: translateX(0);
        opacity: 1;
    }
}

/* === RESPONSIVE === */
@media (max-width: 991.98px) {
    .dashboard-left-sidebar {
        margin-bottom: 30px;
        position: static !important;
        top: auto !important;
        height: auto !important;
        align-self: auto !important;
    }
    
    .profile-info {
        padding: 60px 15px 20px;
    }
    
    .menu-title {
        padding: 15px 20px 8px;
    }
    
    .menu-link {
        padding: 10px 20px;
    }
}

@media (max-width: 575.98px) {
    .menu-text {
        font-size: 13px;
    }
    
    .menu-icon {
        width: 35px;
        height: 35px;
        margin-right: 12px;
    }
    
    .menu-icon i {
        font-size: 14px;
    }
}
</style>

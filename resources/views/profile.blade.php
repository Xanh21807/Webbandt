@extends('layouts.app')

@section('title', 'Tài khoản - XanhStore')

@section('content')
<div class="profile-page">
    <div class="container">
        <div class="profile-layout">
            <!-- Sidebar -->
            <aside class="profile-sidebar">
                <div class="profile-avatar">
                    <div class="avatar-image" id="avatarImage">
                        <i class="fas fa-user"></i>
                    </div>
                    <h3 id="userName">Người dùng</h3>
                    <p id="userEmail">user@example.com</p>
                </div>
                
                <nav class="profile-nav">
                    <a href="/profile" class="{{ request()->is('profile') ? 'active' : '' }}">
                        <i class="fas fa-user"></i>
                        Thông tin tài khoản
                    </a>
                    <a href="/orders" class="{{ request()->is('orders*') ? 'active' : '' }}">
                        <i class="fas fa-shopping-bag"></i>
                        Đơn hàng của tôi
                    </a>
                    <a href="/favorites" class="{{ request()->is('favorites') ? 'active' : '' }}">
                        <i class="fas fa-heart"></i>
                        Sản phẩm yêu thích
                    </a>
                    <a href="/change-password" class="{{ request()->is('change-password') ? 'active' : '' }}">
                        <i class="fas fa-lock"></i>
                        Đổi mật khẩu
                    </a>
                    <a href="#" onclick="logout()" class="logout-link">
                        <i class="fas fa-sign-out-alt"></i>
                        Đăng xuất
                    </a>
                </nav>
            </aside>

            <!-- Main Content -->
            <main class="profile-main">
                <div class="profile-section">
                    <div class="section-header">
                        <h2>Thông tin tài khoản</h2>
                        <button class="btn btn-outline btn-sm" id="editBtn" onclick="toggleEdit()">
                            <i class="fas fa-edit"></i> Chỉnh sửa
                        </button>
                    </div>
                    
                    <form id="profileForm" class="profile-form">
                        <div class="form-row">
                            <div class="form-group">
                                <label for="name">Họ và tên</label>
                                <input type="text" id="name" name="name" disabled>
                            </div>
                            <div class="form-group">
                                <label for="phone">Số điện thoại</label>
                                <input type="tel" id="phone" name="phone" disabled>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label for="email">Email</label>
                            <input type="email" id="email" name="email" disabled>
                        </div>
                        
                        <div class="form-row">
                            <div class="form-group">
                                <label for="birthday">Ngày sinh</label>
                                <input type="date" id="birthday" name="birthday" disabled>
                            </div>
                            <div class="form-group">
                                <label>Giới tính</label>
                                <div class="gender-options">
                                    <label class="radio-wrapper">
                                        <input type="radio" name="gender" value="male" disabled>
                                        <span class="checkmark"></span>
                                        Nam
                                    </label>
                                    <label class="radio-wrapper">
                                        <input type="radio" name="gender" value="female" disabled>
                                        <span class="checkmark"></span>
                                        Nữ
                                    </label>
                                    <label class="radio-wrapper">
                                        <input type="radio" name="gender" value="other" disabled>
                                        <span class="checkmark"></span>
                                        Khác
                                    </label>
                                </div>
                            </div>
                        </div>
                        
                        <div class="form-actions" id="formActions" style="display: none;">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Lưu thay đổi
                            </button>
                            <button type="button" class="btn btn-outline" onclick="cancelEdit()">
                                Hủy
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Recent Orders -->
                <div class="profile-section">
                    <div class="section-header">
                        <h2>Đơn hàng gần đây</h2>
                        <a href="/orders" class="btn btn-outline btn-sm">Xem tất cả</a>
                    </div>
                    
                    <div class="recent-orders" id="recentOrders">
                        <div class="loading">
                            <i class="fas fa-spinner fa-spin"></i>
                            <p>Đang tải...</p>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
.profile-page {
    padding: 24px 0 60px;
    background: #f9fafb;
    min-height: 100vh;
}

.profile-layout {
    display: grid;
    grid-template-columns: 280px 1fr;
    gap: 24px;
}

/* Sidebar */
.profile-sidebar {
    background: white;
    border-radius: 16px;
    padding: 24px;
    box-shadow: var(--shadow-sm);
    height: fit-content;
    position: sticky;
    top: 100px;
}

.profile-avatar {
    text-align: center;
    padding-bottom: 20px;
    border-bottom: 1px solid #e5e7eb;
    margin-bottom: 20px;
}

.avatar-image {
    width: 100px;
    height: 100px;
    margin: 0 auto 16px;
    background: linear-gradient(135deg, var(--primary) 0%, #ff6b6b 100%);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 40px;
}

.avatar-image img {
    width: 100%;
    height: 100%;
    border-radius: 50%;
    object-fit: cover;
}

.profile-avatar h3 {
    font-size: 18px;
    font-weight: 600;
    color: var(--gray-900);
    margin-bottom: 4px;
}

.profile-avatar p {
    font-size: 14px;
    color: var(--gray-500);
}

.profile-nav {
    display: flex;
    flex-direction: column;
    gap: 4px;
}

.profile-nav a {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 12px 16px;
    border-radius: 8px;
    color: var(--gray-700);
    font-size: 14px;
    font-weight: 500;
    transition: all 0.2s;
}

.profile-nav a:hover {
    background: #f3f4f6;
    color: var(--primary);
}

.profile-nav a.active {
    background: #fef2f2;
    color: var(--primary);
}

.profile-nav a i {
    width: 20px;
    text-align: center;
}

.profile-nav .logout-link {
    color: #ef4444;
    margin-top: 8px;
    border-top: 1px solid #e5e7eb;
    padding-top: 12px;
}

.profile-nav .logout-link:hover {
    background: #fef2f2;
}

/* Main Content */
.profile-section {
    background: white;
    border-radius: 16px;
    padding: 24px;
    box-shadow: var(--shadow-sm);
    margin-bottom: 24px;
}

.section-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 24px;
}

.section-header h2 {
    font-size: 18px;
    font-weight: 700;
    color: var(--gray-900);
}

/* Profile Form */
.profile-form .form-row {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 16px;
}

.profile-form .form-group {
    margin-bottom: 16px;
}

.profile-form label {
    display: block;
    font-size: 14px;
    font-weight: 500;
    color: var(--gray-700);
    margin-bottom: 6px;
}

.profile-form input {
    width: 100%;
    padding: 12px 14px;
    border: 1px solid #e5e7eb;
    border-radius: 8px;
    font-size: 14px;
    background: #f9fafb;
}

.profile-form input:not(:disabled) {
    background: white;
    border-color: var(--primary);
}

.gender-options {
    display: flex;
    gap: 24px;
    padding-top: 8px;
}

.radio-wrapper {
    display: flex;
    align-items: center;
    gap: 8px;
    cursor: pointer;
    font-size: 14px;
}

.form-actions {
    display: flex;
    gap: 12px;
    margin-top: 24px;
    padding-top: 24px;
    border-top: 1px solid #e5e7eb;
}

/* Recent Orders */
.recent-orders .loading {
    text-align: center;
    padding: 40px;
    color: var(--gray-500);
}

.order-card {
    border: 1px solid #e5e7eb;
    border-radius: 12px;
    padding: 16px;
    margin-bottom: 12px;
}

.order-card:last-child {
    margin-bottom: 0;
}

.order-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 12px;
    padding-bottom: 12px;
    border-bottom: 1px solid #e5e7eb;
}

.order-id {
    font-weight: 600;
    color: var(--gray-900);
}

.order-date {
    font-size: 13px;
    color: var(--gray-500);
}

.order-items {
    display: flex;
    gap: 8px;
    margin-bottom: 12px;
}

.order-item-thumb {
    width: 50px;
    height: 50px;
    border-radius: 8px;
    overflow: hidden;
    background: #f9fafb;
}

.order-item-thumb img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.order-item-more {
    width: 50px;
    height: 50px;
    border-radius: 8px;
    background: #f3f4f6;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 13px;
    color: var(--gray-500);
}

.order-footer {
    display: flex;
    align-items: center;
    justify-content: space-between;
}

.order-total {
    font-weight: 600;
    color: var(--primary);
}

.order-status {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 4px 12px;
    border-radius: 20px;
    font-size: 13px;
    font-weight: 500;
}

.order-status.pending {
    background: #fef3c7;
    color: #d97706;
}

.order-status.processing {
    background: #dbeafe;
    color: #2563eb;
}

.order-status.shipping {
    background: #e0e7ff;
    color: #4f46e5;
}

.order-status.completed {
    background: #d1fae5;
    color: #059669;
}

.order-status.cancelled {
    background: #fee2e2;
    color: #dc2626;
}

.empty-orders {
    text-align: center;
    padding: 40px;
}

.empty-orders i {
    font-size: 48px;
    color: #e5e7eb;
    margin-bottom: 16px;
}

.empty-orders p {
    color: var(--gray-500);
    margin-bottom: 16px;
}

/* Responsive */
@media (max-width: 1024px) {
    .profile-layout {
        grid-template-columns: 1fr;
    }
    
    .profile-sidebar {
        position: static;
    }
}

@media (max-width: 768px) {
    .profile-form .form-row {
        grid-template-columns: 1fr;
    }
}
</style>
@endpush

@push('scripts')
<script>
let isEditing = false;
let originalData = {};

// Format price
function formatPrice(price) {
    return new Intl.NumberFormat('vi-VN').format(price);
}

// Get status text
function getStatusText(status) {
    const statusMap = {
        'pending': 'Chờ xác nhận',
        'confirmed': 'Đã xác nhận',
        'processing': 'Đang xử lý',
        'shipping': 'Đang giao',
        'paid': 'Đã thanh toán',
        'completed': 'Hoàn thành',
        'cancelled': 'Đã hủy'
    };
    return statusMap[status] || status;
}

// Check auth
document.addEventListener('DOMContentLoaded', () => {
    const token = localStorage.getItem('auth_token');
    if (!token) {
        window.location.href = '/login';
        return;
    }
    
    loadUserProfile();
    loadRecentOrders();
});

// Load user profile
async function loadUserProfile() {
    const token = localStorage.getItem('auth_token');
    
    try {
        const response = await fetch('/api/profile', {
            headers: {
                'Authorization': `Bearer ${token}`,
                'Accept': 'application/json'
            }
        });
        
        if (response.ok) {
            const data = await response.json();
            const user = data.data?.user || data.data || data;
            
            // Update sidebar
            document.getElementById('userName').textContent = user.name || 'Người dùng';
            document.getElementById('userEmail').textContent = user.email;
            
            if (user.avatar) {
                document.getElementById('avatarImage').innerHTML = `<img src="${user.avatar}" alt="Avatar">`;
            } else {
                document.getElementById('avatarImage').innerHTML = user.name ? user.name.charAt(0).toUpperCase() : '<i class="fas fa-user"></i>';
            }
            
            // Update form
            document.getElementById('name').value = user.name || '';
            document.getElementById('phone').value = user.phone || '';
            document.getElementById('email').value = user.email || '';
            document.getElementById('birthday').value = user.birthday || '';
            
            if (user.gender) {
                document.querySelector(`input[name="gender"][value="${user.gender}"]`).checked = true;
            }
            
            // Store original data
            originalData = { ...user };
        } else {
            localStorage.removeItem('auth_token');
            window.location.href = '/login';
        }
    } catch (error) {
        console.error('Error loading profile:', error);
    }
}

// Load recent orders
async function loadRecentOrders() {
    const token = localStorage.getItem('auth_token');
    const container = document.getElementById('recentOrders');
    
    try {
        const response = await fetch('/api/orders?limit=3', {
            headers: {
                'Authorization': `Bearer ${token}`,
                'Accept': 'application/json'
            }
        });
        
        if (response.ok) {
            const data = await response.json();
            const orders = data.data?.orders || data.data || [];
            
            if (orders.length === 0) {
                container.innerHTML = `
                    <div class="empty-orders">
                        <i class="fas fa-shopping-bag"></i>
                        <p>Bạn chưa có đơn hàng nào</p>
                        <a href="/products" class="btn btn-primary">Mua sắm ngay</a>
                    </div>
                `;
            } else {
                container.innerHTML = orders.map(order => `
                    <div class="order-card">
                        <div class="order-header">
                            <span class="order-id">Đơn hàng #${order.order_number || order.id}</span>
                            <span class="order-date">${new Date(order.created_at).toLocaleDateString('vi-VN')}</span>
                        </div>
                        <div class="order-items">
                            ${(order.items || []).slice(0, 3).map(item => `
                                <div class="order-item-thumb">
                                    <img src="${item.product?.image || 'https://placehold.co/50x50/f5f5f5/333?text=No+Image'}" alt="${item.product?.name}">
                                </div>
                            `).join('')}
                            ${(order.items || []).length > 3 ? `
                                <div class="order-item-more">+${order.items.length - 3}</div>
                            ` : ''}
                        </div>
                        <div class="order-footer">
                            <span class="order-total">${formatPrice(order.total_amount || order.total)}₫</span>
                            <span class="order-status ${order.status}">${getStatusText(order.status)}</span>
                        </div>
                    </div>
                `).join('');
            }
        }
    } catch (error) {
        console.error('Error loading orders:', error);
        container.innerHTML = '<p style="color: var(--gray-500); text-align: center;">Không thể tải đơn hàng</p>';
    }
}

// Toggle edit mode
function toggleEdit() {
    isEditing = !isEditing;
    const btn = document.getElementById('editBtn');
    const actions = document.getElementById('formActions');
    const inputs = document.querySelectorAll('#profileForm input:not([type="radio"])');
    const radios = document.querySelectorAll('#profileForm input[type="radio"]');
    
    if (isEditing) {
        btn.innerHTML = '<i class="fas fa-times"></i> Hủy';
        btn.classList.add('btn-danger');
        actions.style.display = 'flex';
        inputs.forEach(input => input.disabled = false);
        radios.forEach(radio => radio.disabled = false);
    } else {
        btn.innerHTML = '<i class="fas fa-edit"></i> Chỉnh sửa';
        btn.classList.remove('btn-danger');
        actions.style.display = 'none';
        inputs.forEach(input => input.disabled = true);
        radios.forEach(radio => radio.disabled = true);
        
        // Restore original data
        document.getElementById('name').value = originalData.name || '';
        document.getElementById('phone').value = originalData.phone || '';
        document.getElementById('email').value = originalData.email || '';
        document.getElementById('birthday').value = originalData.birthday || '';
    }
}

function cancelEdit() {
    toggleEdit();
}

// Submit form
document.getElementById('profileForm').addEventListener('submit', async function(e) {
    e.preventDefault();
    
    const token = localStorage.getItem('auth_token');
    const btn = this.querySelector('button[type="submit"]');
    btn.disabled = true;
    btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Đang lưu...';
    
    const formData = {
        name: document.getElementById('name').value,
        phone: document.getElementById('phone').value,
        email: document.getElementById('email').value,
        birthday: document.getElementById('birthday').value,
        gender: document.querySelector('input[name="gender"]:checked')?.value
    };
    
    try {
        const response = await fetch('/api/profile', {
            method: 'PUT',
            headers: {
                'Content-Type': 'application/json',
                'Authorization': `Bearer ${token}`,
                'Accept': 'application/json'
            },
            body: JSON.stringify(formData)
        });
        
        if (response.ok) {
            const data = await response.json();
            originalData = data.data?.user || data.data || data;
            localStorage.setItem('user', JSON.stringify(originalData));
            
            alert('Cập nhật thông tin thành công!');
            toggleEdit();
            loadUserProfile();
        } else {
            const data = await response.json();
            alert(data.message || 'Cập nhật không thành công');
        }
    } catch (error) {
        console.error('Error updating profile:', error);
        alert('Đã có lỗi xảy ra');
    } finally {
        btn.disabled = false;
        btn.innerHTML = '<i class="fas fa-save"></i> Lưu thay đổi';
    }
});

// Logout
function logout() {
    localStorage.removeItem('auth_token');
    localStorage.removeItem('user');
    window.location.href = '/login';
}
</script>
@endpush

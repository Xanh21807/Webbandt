@extends('layouts.admin')

@section('page-title', 'Quản lý khách hàng')

@section('content')
<!-- Header -->
<div class="page-actions">
    <div class="search-box">
        <i class="fas fa-search"></i>
        <input type="text" id="searchInput" placeholder="Tìm tên, email, số điện thoại..." onkeyup="searchUsers()">
    </div>
    <div class="action-buttons">
        <button class="btn btn-primary" onclick="openModal('add')">
            <i class="fas fa-plus"></i> Thêm khách hàng
        </button>
    </div>
</div>

<!-- Stats -->
<div class="user-stats">
    <div class="stat-item">
        <span class="stat-value" id="totalUsers">0</span>
        <span class="stat-label">Tổng khách hàng</span>
    </div>
    <div class="stat-item">
        <span class="stat-value" id="newUsers">0</span>
        <span class="stat-label">Mới tháng này</span>
    </div>
    <div class="stat-item">
        <span class="stat-value" id="activeUsers">0</span>
        <span class="stat-label">Đang hoạt động</span>
    </div>
</div>

<!-- Filters -->
<div class="filters-bar">
    <select id="roleFilter" onchange="loadUsers()">
        <option value="">Tất cả vai trò</option>
        <option value="user">Khách hàng</option>
        <option value="admin">Admin</option>
    </select>
    <select id="statusFilter" onchange="loadUsers()">
        <option value="">Tất cả trạng thái</option>
        <option value="active">Đang hoạt động</option>
        <option value="inactive">Đã khóa</option>
    </select>
</div>

<!-- Users Table -->
<div class="table-card">
    <table class="admin-table">
        <thead>
            <tr>
                <th><input type="checkbox" id="selectAll" onchange="toggleSelectAll()"></th>
                <th>Khách hàng</th>
                <th>Email</th>
                <th>Điện thoại</th>
                <th>Đơn hàng</th>
                <th>Tổng chi tiêu</th>
                <th>Ngày tham gia</th>
                <th>Trạng thái</th>
                <th>Thao tác</th>
            </tr>
        </thead>
        <tbody id="usersTable">
            <tr>
                <td colspan="9" class="loading-cell">
                    <i class="fas fa-spinner fa-spin"></i> Đang tải...
                </td>
            </tr>
        </tbody>
    </table>
</div>

<!-- Pagination -->
<div class="pagination" id="pagination"></div>

<!-- User Modal -->
<div class="modal" id="userModal">
    <div class="modal-overlay" onclick="closeModal()"></div>
    <div class="modal-content">
        <div class="modal-header">
            <h3 id="modalTitle">Thêm khách hàng</h3>
            <button class="modal-close" onclick="closeModal()">&times;</button>
        </div>
        <div class="modal-body">
            <form id="userForm">
                <input type="hidden" id="userId">
                
                <div class="form-group">
                    <label for="userName">Họ tên *</label>
                    <input type="text" id="userName" required>
                </div>
                
                <div class="form-group">
                    <label for="userEmail">Email *</label>
                    <input type="email" id="userEmail" required>
                </div>
                
                <div class="form-group">
                    <label for="userPhone">Số điện thoại</label>
                    <input type="tel" id="userPhone">
                </div>
                
                <div class="form-group">
                    <label for="userPassword">Mật khẩu <span id="passwordNote">(để trống nếu không đổi)</span></label>
                    <input type="password" id="userPassword">
                </div>
                
                <div class="form-group">
                    <label for="userRole">Vai trò *</label>
                    <select id="userRole" required>
                        <option value="user">Khách hàng</option>
                        <option value="admin">Admin</option>
                    </select>
                </div>
                
                <div class="form-group">
                    <label for="userAddress">Địa chỉ</label>
                    <textarea id="userAddress" rows="2"></textarea>
                </div>
                
                <div class="form-group">
                    <label for="userStatus">Trạng thái <span class="required">*</span></label>
                    <select id="userStatus" required>
                        <option value="active">Đang hoạt động</option>
                        <option value="blocked">Đã khóa</option>
                    </select>
                </div>
            </form>
        </div>
        <div class="modal-footer">
            <button class="btn btn-outline" onclick="closeModal()">Hủy</button>
            <button class="btn btn-primary" onclick="saveUser()">
                <i class="fas fa-save"></i> Lưu
            </button>
        </div>
    </div>
</div>

<!-- User Detail Modal -->
<div class="modal" id="userDetailModal">
    <div class="modal-overlay" onclick="closeDetailModal()"></div>
    <div class="modal-content modal-lg">
        <div class="modal-header">
            <h3>Chi tiết khách hàng</h3>
            <button class="modal-close" onclick="closeDetailModal()">&times;</button>
        </div>
        <div class="modal-body" id="userDetailContent">
            <!-- Content loaded dynamically -->
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
.page-actions {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 20px;
}

.search-box {
    position: relative;
    width: 320px;
}

.search-box i {
    position: absolute;
    left: 14px;
    top: 50%;
    transform: translateY(-50%);
    color: var(--gray-500);
}

.search-box input {
    width: 100%;
    padding: 10px 14px 10px 40px;
    border: 1px solid #e5e7eb;
    border-radius: 8px;
}

.user-stats {
    display: flex;
    gap: 20px;
    margin-bottom: 20px;
}

.stat-item {
    background: white;
    padding: 16px 24px;
    border-radius: 10px;
    box-shadow: var(--shadow-sm);
}

.stat-value {
    display: block;
    font-size: 24px;
    font-weight: 700;
    color: var(--primary);
}

.stat-label {
    font-size: 14px;
    color: var(--gray-500);
}

.filters-bar {
    display: flex;
    gap: 12px;
    margin-bottom: 20px;
}

.filters-bar select {
    padding: 10px 14px;
    border: 1px solid #e5e7eb;
    border-radius: 8px;
    font-size: 14px;
    min-width: 150px;
}

.table-card {
    background: white;
    border-radius: 12px;
    box-shadow: var(--shadow-sm);
    overflow: hidden;
}

.admin-table {
    width: 100%;
    border-collapse: collapse;
}

.admin-table th,
.admin-table td {
    padding: 14px 16px;
    text-align: left;
    border-bottom: 1px solid #e5e7eb;
}

.admin-table th {
    background: #f9fafb;
    font-size: 13px;
    font-weight: 600;
    color: var(--gray-600);
    text-transform: uppercase;
}

.loading-cell {
    text-align: center;
    padding: 60px !important;
    color: var(--gray-500);
}

.user-cell {
    display: flex;
    align-items: center;
    gap: 12px;
}

.user-avatar {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    background: linear-gradient(135deg, var(--primary), #ff6b6b);
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-weight: 600;
    font-size: 16px;
}

.user-name {
    font-weight: 500;
}

.user-role {
    font-size: 12px;
    color: var(--gray-500);
}

.status-badge {
    display: inline-block;
    padding: 4px 10px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: 500;
}

.status-badge.active { background: #d1fae5; color: #059669; }
.status-badge.inactive { background: #fee2e2; color: #dc2626; }

.action-btns {
    display: flex;
    gap: 8px;
}

.action-btns button {
    width: 32px;
    height: 32px;
    border: none;
    border-radius: 6px;
    cursor: pointer;
}

.action-btns .btn-view { background: #dbeafe; color: #2563eb; }
.action-btns .btn-edit { background: #fef3c7; color: #d97706; }
.action-btns .btn-delete { background: #fee2e2; color: #dc2626; }

/* Modal */
.modal {
    display: none !important;
    position: fixed !important;
    top: 0 !important;
    left: 0 !important;
    right: 0 !important;
    bottom: 0 !important;
    z-index: 9999 !important;
    align-items: center !important;
    justify-content: center !important;
    background-color: transparent !important;
    border-radius: 0 !important;
    max-width: none !important;
    max-height: none !important;
    overflow: visible !important;
    transform: none !important;
}

.modal.active {
    display: flex !important;
}

.modal-overlay {
    position: fixed !important;
    top: 0 !important;
    left: 0 !important;
    right: 0 !important;
    bottom: 0 !important;
    background: rgba(0, 0, 0, 0.5) !important;
    z-index: 9999 !important;
}

.modal-content {
    position: relative !important;
    background: white !important;
    border-radius: 16px !important;
    max-width: 500px !important;
    width: 90% !important;
    max-height: 90vh !important;
    overflow: hidden !important;
    display: flex !important;
    flex-direction: column !important;
    z-index: 10000 !important;
    transform: none !important;    box-shadow: 0 10px 40px rgba(215, 0, 24, 0.15) !important;    /* Ẩn thanh trượt */
    scrollbar-width: none;
    -ms-overflow-style: none;
}

.modal-content::-webkit-scrollbar {
    display: none;
}

.modal-lg {
    max-width: 700px;
}

.modal-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 20px 24px;
    border-bottom: 1px solid #e5e7eb;
}

.modal-header h3 {
    font-size: 18px;
    font-weight: 700;
}

.modal-close {
    width: 32px;
    height: 32px;
    border: none;
    background: #f3f4f6;
    border-radius: 50%;
    font-size: 20px;
    cursor: pointer;
}

.modal-body {
    padding: 24px;
    overflow-y: auto;
    flex: 1;
    /* Ẩn thanh trượt */
    scrollbar-width: none; /* Firefox */
    -ms-overflow-style: none; /* IE and Edge */
}

.modal-body::-webkit-scrollbar {
    display: none; /* Chrome, Safari, Opera */
}

.modal-footer {
    display: flex;
    justify-content: flex-end;
    gap: 12px;
    padding: 20px 24px;
    border-top: 1px solid #e5e7eb;
}

.form-group {
    margin-bottom: 16px;
}

.form-group label {
    display: block;
    font-size: 14px;
    font-weight: 500;
    color: var(--gray-700);
    margin-bottom: 6px;
}

.form-group input,
.form-group select,
.form-group textarea {
    width: 100%;
    padding: 10px 14px;
    border: 1px solid #e5e7eb;
    border-radius: 8px;
    font-size: 14px;
}

#passwordNote {
    font-weight: 400;
    color: var(--gray-500);
    font-size: 12px;
}

/* User Detail */
.user-detail-header {
    display: flex;
    align-items: center;
    gap: 20px;
    margin-bottom: 24px;
}

.detail-avatar {
    width: 80px;
    height: 80px;
    border-radius: 50%;
    background: linear-gradient(135deg, var(--primary), #ff6b6b);
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 32px;
    font-weight: 600;
}

.detail-info h2 {
    font-size: 24px;
    margin-bottom: 4px;
}

.detail-info p {
    color: var(--gray-500);
}

.detail-stats {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 16px;
    margin-bottom: 24px;
}

.detail-stat {
    background: #f9fafb;
    padding: 16px;
    border-radius: 10px;
    text-align: center;
}

.detail-stat .value {
    font-size: 24px;
    font-weight: 700;
    color: var(--primary);
}

.detail-stat .label {
    font-size: 13px;
    color: var(--gray-500);
}

.detail-section {
    background: #f9fafb;
    padding: 16px;
    border-radius: 10px;
    margin-bottom: 16px;
}

.detail-section h4 {
    font-size: 14px;
    font-weight: 600;
    margin-bottom: 12px;
}

.detail-row {
    display: flex;
    justify-content: space-between;
    padding: 8px 0;
    font-size: 14px;
    border-bottom: 1px solid #e5e7eb;
}

.detail-row:last-child {
    border-bottom: none;
}

.detail-row .label {
    color: var(--gray-500);
}

.pagination {
    display: flex;
    justify-content: center;
    gap: 8px;
    margin-top: 24px;
}

.pagination button {
    min-width: 40px;
    height: 40px;
    border: 1px solid #e5e7eb;
    border-radius: 8px;
    background: white;
    cursor: pointer;
}

.pagination button.active {
    background: var(--primary);
    border-color: var(--primary);
    color: white;
}
</style>
@endpush

@push('scripts')
<script>
let users = [];
let currentPage = 1;

document.addEventListener('DOMContentLoaded', () => {
    loadUserStats();
    loadUsers();
});

// Load user stats
async function loadUserStats() {
    const token = localStorage.getItem('auth_token');
    
    try {
        const response = await fetch('/api/admin/users/stats', {
            headers: {
                'Authorization': `Bearer ${token}`,
                'Accept': 'application/json'
            }
        });
        
        if (response.ok) {
            const stats = await response.json();
            document.getElementById('totalUsers').textContent = stats.total || 0;
            document.getElementById('newUsers').textContent = stats.new_this_month || 0;
            document.getElementById('activeUsers').textContent = stats.active || 0;
        }
    } catch (error) {
        console.error('Error loading stats:', error);
    }
}

// Load users
async function loadUsers(page = 1) {
    currentPage = page;
    const token = localStorage.getItem('auth_token');
    const tbody = document.getElementById('usersTable');
    
    const params = new URLSearchParams();
    params.append('page', page);
    
    const role = document.getElementById('roleFilter').value;
    const status = document.getElementById('statusFilter').value;
    const search = document.getElementById('searchInput').value;
    
    if (role) params.append('role', role);
    if (status) params.append('status', status);
    if (search) params.append('search', search);
    
    tbody.innerHTML = '<tr><td colspan="9" class="loading-cell"><i class="fas fa-spinner fa-spin"></i> Đang tải...</td></tr>';
    
    try {
        const response = await fetch(`/api/admin/users?${params.toString()}`, {
            headers: {
                'Authorization': `Bearer ${token}`,
                'Accept': 'application/json'
            }
        });
        
        if (response.ok) {
            const result = await response.json();
            const data = result.data || {};
            users = data.data || [];
            
            if (users.length === 0) {
                tbody.innerHTML = '<tr><td colspan="9" class="loading-cell">Không có khách hàng nào</td></tr>';
            } else {
                tbody.innerHTML = users.map(user => `
                    <tr>
                        <td><input type="checkbox" class="user-checkbox" value="${user.id}"></td>
                        <td>
                            <div class="user-cell">
                                <div class="user-avatar">${getInitials(user.name)}</div>
                                <div>
                                    <div class="user-name">${user.name}</div>
                                    <div class="user-role">${user.role === 'admin' ? 'Admin' : 'Khách hàng'}</div>
                                </div>
                            </div>
                        </td>
                        <td>${user.email}</td>
                        <td>${user.phone || '-'}</td>
                        <td>${user.orders_count || 0}</td>
                        <td>${formatPrice(user.total_spent || 0)}₫</td>
                        <td>${formatDate(user.created_at)}</td>
                        <td>
                            <span class="status-badge ${user.status === 'active' ? 'active' : 'inactive'}">${user.status === 'active' ? 'Hoạt động' : 'Đã khóa'}</span>
                        </td>
                        <td>
                            <div class="action-btns">
                                <button class="btn-view" onclick="viewUser(${user.id})" title="Xem chi tiết">
                                    <i class="fas fa-eye"></i>
                                </button>
                                <button class="btn-edit" onclick="editUser(${user.id})" title="Sửa">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button class="btn-delete" onclick="deleteUser(${user.id})" title="Xóa">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                `).join('');
            }
            
            if (data.last_page) {
                renderPagination(data.current_page, data.last_page);
            }
        }
    } catch (error) {
        console.error('Error loading users:', error);
        tbody.innerHTML = '<tr><td colspan="9" class="loading-cell">Không thể tải dữ liệu</td></tr>';
    }
}

function getInitials(name) {
    if (!name) return '??';
    return name.split(' ').map(n => n[0]).slice(0, 2).join('').toUpperCase();
}

function formatDate(dateStr) {
    const date = new Date(dateStr);
    return date.toLocaleDateString('vi-VN', { day: '2-digit', month: '2-digit', year: 'numeric' });
}

function searchUsers() {
    clearTimeout(window.searchTimeout);
    window.searchTimeout = setTimeout(() => loadUsers(), 300);
}

function openModal(mode = 'add') {
    document.getElementById('userModal').classList.add('active');
    document.getElementById('modalTitle').textContent = mode === 'add' ? 'Thêm khách hàng' : 'Sửa khách hàng';
    document.getElementById('passwordNote').style.display = mode === 'add' ? 'none' : 'inline';
    
    if (mode === 'add') {
        document.getElementById('userForm').reset();
        document.getElementById('userId').value = '';
        document.getElementById('userActive').checked = true;
    }
}

function closeModal() {
    document.getElementById('userModal').classList.remove('active');
}

function closeDetailModal() {
    document.getElementById('userDetailModal').classList.remove('active');
}

async function viewUser(id) {
    const token = localStorage.getItem('auth_token');
    
    try {
        const response = await fetch(`/api/admin/users/${id}`, {
            headers: {
                'Authorization': `Bearer ${token}`,
                'Accept': 'application/json'
            }
        });
        
        if (response.ok) {
            const result = await response.json();
            const user = result.data?.user || result.data || result;
            
            document.getElementById('userDetailContent').innerHTML = `
                <div class="user-detail-header">
                    <div class="detail-avatar">${getInitials(user.name)}</div>
                    <div class="detail-info">
                        <h2>${user.name}</h2>
                        <p>${user.email}</p>
                    </div>
                </div>
                
                <div class="detail-stats">
                    <div class="detail-stat">
                        <div class="value">${user.orders_count || 0}</div>
                        <div class="label">Đơn hàng</div>
                    </div>
                    <div class="detail-stat">
                        <div class="value">${formatPrice(user.total_spent || 0)}₫</div>
                        <div class="label">Tổng chi tiêu</div>
                    </div>
                    <div class="detail-stat">
                        <div class="value">${user.reviews_count || 0}</div>
                        <div class="label">Đánh giá</div>
                    </div>
                </div>
                
                <div class="detail-section">
                    <h4><i class="fas fa-user"></i> Thông tin cá nhân</h4>
                    <div class="detail-row">
                        <span class="label">Họ tên:</span>
                        <span>${user.name}</span>
                    </div>
                    <div class="detail-row">
                        <span class="label">Email:</span>
                        <span>${user.email}</span>
                    </div>
                    <div class="detail-row">
                        <span class="label">Điện thoại:</span>
                        <span>${user.phone || '-'}</span>
                    </div>
                    <div class="detail-row">
                        <span class="label">Địa chỉ:</span>
                        <span>${user.address || '-'}</span>
                    </div>
                    <div class="detail-row">
                        <span class="label">Vai trò:</span>
                        <span>${user.role === 'admin' ? 'Admin' : 'Khách hàng'}</span>
                    </div>
                    <div class="detail-row">
                        <span class="label">Ngày tham gia:</span>
                        <span>${formatDate(user.created_at)}</span>
                    </div>
                    <div class="detail-row">
                        <span class="label">Trạng thái:</span>
                        <span class="status-badge ${user.status === 'active' ? 'active' : 'inactive'}">${user.status === 'active' ? 'Hoạt động' : 'Đã khóa'}</span>
                    </div>
                </div>
                
                ${user.recent_orders && user.recent_orders.length > 0 ? `
                    <div class="detail-section">
                        <h4><i class="fas fa-shopping-bag"></i> Đơn hàng gần đây</h4>
                        ${user.recent_orders.map(order => `
                            <div class="detail-row">
                                <span>#${order.order_number || order.id}</span>
                                <span>${formatPrice(order.total)}₫</span>
                            </div>
                        `).join('')}
                    </div>
                ` : ''}
            `;
            
            document.getElementById('userDetailModal').classList.add('active');
        }
    } catch (error) {
        console.error('Error loading user:', error);
    }
}

async function editUser(id) {
    const user = users.find(u => u.id === id);
    if (!user) return;
    
    document.getElementById('userId').value = user.id;
    document.getElementById('userName').value = user.name;
    document.getElementById('userEmail').value = user.email;
    document.getElementById('userPhone').value = user.phone || '';
    document.getElementById('userPassword').value = '';
    document.getElementById('userRole').value = user.role || 'user';
    document.getElementById('userAddress').value = user.address || '';
    document.getElementById('userStatus').value = user.status || 'active';
    
    openModal('edit');
}

async function saveUser() {
    const token = localStorage.getItem('auth_token');
    const id = document.getElementById('userId').value;
    
    const formData = {
        name: document.getElementById('userName').value,
        email: document.getElementById('userEmail').value,
        phone: document.getElementById('userPhone').value,
        role: document.getElementById('userRole').value,
        address: document.getElementById('userAddress').value,
        status: document.getElementById('userStatus').value
    };
    
    const password = document.getElementById('userPassword').value;
    
    // Password required for new user
    if (!id && !password) {
        alert('Vui lòng nhập mật khẩu cho khách hàng mới');
        return;
    }
    
    if (password) {
        formData.password = password;
    }
    
    try {
        const url = id ? `/api/admin/users/${id}` : '/api/admin/users';
        const method = id ? 'PUT' : 'POST';
        
        const response = await fetch(url, {
            method,
            headers: {
                'Content-Type': 'application/json',
                'Authorization': `Bearer ${token}`,
                'Accept': 'application/json'
            },
            body: JSON.stringify(formData)
        });
        
        if (response.ok) {
            alert(id ? 'Cập nhật thành công!' : 'Thêm khách hàng thành công!');
            closeModal();
            loadUsers(currentPage);
            loadUserStats();
        } else {
            const data = await response.json();
            alert(data.message || 'Có lỗi xảy ra');
        }
    } catch (error) {
        console.error('Error saving user:', error);
        alert('Không thể lưu');
    }
}

async function deleteUser(id) {
    if (!confirm('Bạn có chắc muốn xóa khách hàng này?')) return;
    
    const token = localStorage.getItem('auth_token');
    
    try {
        const response = await fetch(`/api/admin/users/${id}`, {
            method: 'DELETE',
            headers: {
                'Authorization': `Bearer ${token}`,
                'Accept': 'application/json'
            }
        });
        
        if (response.ok) {
            alert('Đã xóa khách hàng');
            loadUsers(currentPage);
            loadUserStats();
        } else {
            alert('Không thể xóa khách hàng');
        }
    } catch (error) {
        console.error('Error deleting user:', error);
    }
}

function toggleSelectAll() {
    const checked = document.getElementById('selectAll').checked;
    document.querySelectorAll('.user-checkbox').forEach(cb => cb.checked = checked);
}

function renderPagination(current, total) {
    const container = document.getElementById('pagination');
    let html = '';
    
    html += `<button ${current === 1 ? 'disabled' : ''} onclick="loadUsers(${current - 1})"><i class="fas fa-chevron-left"></i></button>`;
    
    for (let i = 1; i <= total; i++) {
        if (i === 1 || i === total || (i >= current - 2 && i <= current + 2)) {
            html += `<button class="${i === current ? 'active' : ''}" onclick="loadUsers(${i})">${i}</button>`;
        } else if (i === current - 3 || i === current + 3) {
            html += `<span style="padding: 0 8px;">...</span>`;
        }
    }
    
    html += `<button ${current === total ? 'disabled' : ''} onclick="loadUsers(${current + 1})"><i class="fas fa-chevron-right"></i></button>`;
    
    container.innerHTML = html;
}

function exportUsers() {
    alert('Tính năng xuất Excel đang phát triển');
}
</script>
@endpush

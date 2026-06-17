@extends('layouts.admin')

@section('page-title', 'Quản lý đơn hàng')

@section('content')
<!-- Header -->
<div class="page-actions">
    <div class="search-box">
        <i class="fas fa-search"></i>
        <input type="text" id="searchInput" placeholder="Tìm mã đơn, tên khách hàng..." onkeyup="searchOrders()">
    </div>
</div>

<!-- Order Tabs -->
<div class="order-tabs">
    <button class="tab-btn active" data-status="" onclick="filterByStatus(this)">
        Tất cả <span class="count" id="countAll">0</span>
    </button>
    <button class="tab-btn" data-status="pending" onclick="filterByStatus(this)">
        Chờ xác nhận <span class="count" id="countPending">0</span>
    </button>
    <button class="tab-btn" data-status="paid" onclick="filterByStatus(this)">
        Đã xác nhận <span class="count" id="countPaid">0</span>
    </button>
    <button class="tab-btn" data-status="shipping" onclick="filterByStatus(this)">
        Đang giao <span class="count" id="countShipping">0</span>
    </button>
    <button class="tab-btn" data-status="completed" onclick="filterByStatus(this)">
        Hoàn thành <span class="count" id="countCompleted">0</span>
    </button>
    <button class="tab-btn" data-status="cancelled" onclick="filterByStatus(this)">
        Đã hủy <span class="count" id="countCancelled">0</span>
    </button>
</div>

<!-- Filters -->
<div class="filters-bar">
    <input type="date" id="dateFrom" onchange="loadOrders()">
    <span>đến</span>
    <input type="date" id="dateTo" onchange="loadOrders()">
    <select id="paymentFilter" onchange="loadOrders()">
        <option value="">Phương thức thanh toán</option>
        <option value="cod">COD</option>
        <option value="bank_transfer">Chuyển khoản</option>
        <option value="momo">MoMo</option>
        <option value="vnpay">VNPay</option>
    </select>
</div>

<!-- Orders Table -->
<div class="table-card">
    <table class="admin-table">
        <thead>
            <tr>
                <th><input type="checkbox" id="selectAll" onchange="toggleSelectAll()"></th>
                <th>Mã đơn</th>
                <th>Khách hàng</th>
                <th>Sản phẩm</th>
                <th>Tổng tiền</th>
                <th>Thanh toán</th>
                <th>Trạng thái</th>
                <th>Ngày đặt</th>
                <th>Thao tác</th>
            </tr>
        </thead>
        <tbody id="ordersTable">
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

<!-- Order Detail Modal -->
<div class="modal" id="orderModal">
    <div class="modal-overlay" onclick="closeModal()"></div>
    <div class="modal-content modal-xl">
        <div class="modal-header">
            <h3>Chi tiết đơn hàng #<span id="modalOrderNumber"></span></h3>
            <button class="modal-close" onclick="closeModal()">&times;</button>
        </div>
        <div class="modal-body" id="orderDetailContent">
            <!-- Content loaded dynamically -->
        </div>
        <div class="modal-footer">
            <button class="btn btn-outline" onclick="printOrder()">
                <i class="fas fa-print"></i> In đơn hàng
            </button>
            <button class="btn btn-outline" onclick="closeModal()">Đóng</button>
        </div>
    </div>
</div>

<!-- Status Update Modal -->
<div class="modal" id="statusModal">
    <div class="modal-overlay" onclick="closeStatusModal()"></div>
    <div class="modal-content">
        <div class="modal-header">
            <h3>Cập nhật trạng thái</h3>
            <button class="modal-close" onclick="closeStatusModal()">&times;</button>
        </div>
        <div class="modal-body">
            <input type="hidden" id="statusOrderId">
            <div class="form-group">
                <label>Trạng thái mới</label>
                <select id="newStatus">
                    <option value="pending">Chờ xác nhận</option>
                    <option value="paid">Đã xác nhận</option>
                    <option value="shipping">Đang giao hàng</option>
                    <option value="completed">Hoàn thành</option>
                    <option value="cancelled">Hủy đơn</option>
                </select>
            </div>
            <div class="form-group">
                <label>Ghi chú</label>
                <textarea id="statusNote" rows="3" placeholder="Ghi chú cập nhật trạng thái..."></textarea>
            </div>
        </div>
        <div class="modal-footer">
            <button class="btn btn-outline" onclick="closeStatusModal()">Hủy</button>
            <button class="btn btn-primary" onclick="updateOrderStatus()">Cập nhật</button>
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

.order-tabs {
    display: flex;
    gap: 4px;
    background: white;
    padding: 6px;
    border-radius: 10px;
    margin-bottom: 20px;
    overflow-x: auto;
}

.tab-btn {
    padding: 10px 16px;
    border: none;
    background: none;
    border-radius: 8px;
    font-size: 14px;
    font-weight: 500;
    cursor: pointer;
    white-space: nowrap;
    transition: all 0.2s;
}

.tab-btn:hover {
    background: #f3f4f6;
}

.tab-btn.active {
    background: var(--primary);
    color: white;
}

.tab-btn .count {
    display: inline-block;
    padding: 2px 8px;
    border-radius: 20px;
    background: #f3f4f6;
    font-size: 12px;
    margin-left: 6px;
}

.tab-btn.active .count {
    background: rgba(255, 255, 255, 0.2);
}

.filters-bar {
    display: flex;
    align-items: center;
    gap: 12px;
    margin-bottom: 20px;
}

.filters-bar input,
.filters-bar select {
    padding: 10px 14px;
    border: 1px solid #e5e7eb;
    border-radius: 8px;
    font-size: 14px;
}

.filters-bar span {
    color: var(--gray-500);
}

.table-card {
    background: white;
    border-radius: 12px;
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
    border-bottom: 1px solid #f3f4f6;
    border-left: none;
    border-right: none;
    border-top: none;
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

.order-number {
    font-weight: 600;
    color: var(--primary);
    cursor: pointer;
}

.customer-cell {
    display: flex;
    flex-direction: column;
}

.customer-cell .name {
    font-weight: 500;
}

.customer-cell .phone {
    font-size: 13px;
    color: var(--gray-500);
}

.products-preview {
    display: flex;
    align-items: center;
    gap: 8px;
}

.products-preview img {
    width: 36px;
    height: 36px;
    border-radius: 6px;
    object-fit: cover;
}

.products-preview .more {
    width: 36px;
    height: 36px;
    border-radius: 6px;
    background: #f3f4f6;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 12px;
    font-weight: 500;
}

.payment-method {
    display: flex;
    align-items: center;
    gap: 8px;
}

.payment-method i {
    font-size: 18px;
    color: var(--gray-600);
}

.status-badge {
    display: inline-block;
    padding: 6px 12px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: 500;
    cursor: pointer;
}

.status-badge.pending { background: #fef3c7; color: #d97706; }
.status-badge.paid { background: #dbeafe; color: #2563eb; }
.status-badge.shipping { background: #d1fae5; color: #059669; }
.status-badge.completed { background: #10b981; color: white; }
.status-badge.cancelled { background: #fee2e2; color: #dc2626; }

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
.action-btns .btn-delete { background: #fee2e2; color: #dc2626; }
.action-btns .btn-confirm { background: #d1fae5; color: #059669; }
.action-btns .btn-cancel { background: #fee2e2; color: #dc2626; }

/* Order Detail Modal */
.modal-xl {
    max-width: 900px;
}

.order-detail-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 24px;
}

.detail-section {
    background: white;
    padding: 16px;
    border-radius: 10px;
}

.detail-section h4 {
    font-size: 14px;
    font-weight: 600;
    margin-bottom: 12px;
    color: var(--gray-700);
}

.detail-row {
    display: flex;
    justify-content: space-between;
    padding: 8px 0;
    font-size: 14px;
}

.detail-row .label {
    color: var(--gray-500);
}

.order-items-table {
    width: 100%;
    margin-top: 16px;
    border-collapse: collapse;
}

.order-items-table th,
.order-items-table td {
    padding: 12px;
    text-align: left;
    border-bottom: 1px solid #e5e7eb;
}

.order-items-table th {
    background: #f9fafb;
    font-size: 13px;
    font-weight: 600;
}

.order-items-table tfoot td {
    border-bottom: none;
    padding: 8px 12px;
    font-size: 14px;
}

.order-items-table tfoot tr:last-child td {
    padding-top: 12px;
    border-top: 2px solid #e5e7eb;
}

.item-cell {
    display: flex;
    align-items: center;
    gap: 12px;
}

.item-cell img {
    width: 50px;
    height: 50px;
    border-radius: 8px;
    object-fit: cover;
}

.order-timeline {
    margin-top: 24px;
    padding: 16px;
    background: #f9fafb;
    border-radius: 10px;
}

.timeline-item {
    display: flex;
    gap: 16px;
    padding: 12px 0;
}

.timeline-dot {
    width: 12px;
    height: 12px;
    border-radius: 50%;
    background: #10b981;
    margin-top: 4px;
}

.timeline-content .time {
    font-size: 12px;
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

/* Modal Styles */
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
    width: 90% !important;
    max-width: 500px !important;
    max-height: 90vh !important;
    overflow: hidden !important;
    display: flex !important;
    flex-direction: column !important;
    z-index: 10000 !important;
    transform: none !important;
    box-shadow: 0 10px 40px rgba(215, 0, 24, 0.15) !important;
}

.modal-content.modal-xl {
    max-width: 900px;
}

@keyframes modalSlideIn {
    from {
        opacity: 0;
        transform: translate(-50%, calc(-50% - 20px));
    }
    to {
        opacity: 1;
        transform: translate(-50%, -50%);
    }
}

.modal-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 20px 24px;
    border-bottom: 1px solid #e5e7eb;
}

.modal-header h3 {
    font-size: 18px;
    font-weight: 600;
    margin: 0;
}

.modal-close {
    background: none;
    border: none;
    font-size: 28px;
    color: #9ca3af;
    cursor: pointer;
    line-height: 1;
}

.modal-close:hover {
    color: #6b7280;
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
    padding: 16px 24px;
    border-top: 1px solid #e5e7eb;
}

.form-group {
    margin-bottom: 16px;
}

.form-group label {
    display: block;
    font-size: 14px;
    font-weight: 500;
    margin-bottom: 8px;
    color: #374151;
}

.form-group select,
.form-group input,
.form-group textarea {
    width: 100%;
    padding: 10px 14px;
    border: 1px solid #e5e7eb;
    border-radius: 8px;
    font-size: 14px;
}

.form-group select:focus,
.form-group input:focus,
.form-group textarea:focus {
    outline: none;
    border-color: var(--primary);
    box-shadow: 0 0 0 3px rgba(239, 68, 68, 0.1);
}

.btn {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 10px 20px;
    border-radius: 8px;
    font-size: 14px;
    font-weight: 500;
    cursor: pointer;
    border: none;
    transition: all 0.2s;
}

.btn-primary {
    background: var(--primary);
    color: white;
}

.btn-primary:hover {
    background: #dc2626;
}

.btn-outline {
    background: white;
    border: 1px solid #e5e7eb;
    color: #374151;
}

.btn-outline:hover {
    background: #f9fafb;
}
</style>
@endpush

@push('scripts')
<script>
let orders = [];
let currentPage = 1;
let currentStatus = '';

document.addEventListener('DOMContentLoaded', () => {
    loadOrderCounts();
    loadOrders();
});

// Load order counts by status
async function loadOrderCounts() {
    const token = localStorage.getItem('auth_token');
    
    try {
        const response = await fetch('/api/admin/orders/counts', {
            headers: {
                'Authorization': `Bearer ${token}`,
                'Accept': 'application/json'
            }
        });
        
        if (response.ok) {
            const counts = await response.json();
            document.getElementById('countAll').textContent = counts.all || 0;
            document.getElementById('countPending').textContent = counts.pending || 0;
            document.getElementById('countPaid').textContent = counts.paid || 0;
            document.getElementById('countShipping').textContent = counts.shipping || 0;
            document.getElementById('countCompleted').textContent = counts.completed || 0;
            document.getElementById('countCancelled').textContent = counts.cancelled || 0;
        }
    } catch (error) {
        console.error('Error loading counts:', error);
    }
}

function filterByStatus(btn) {
    document.querySelectorAll('.tab-btn').forEach(b => b.classList.remove('active'));
    btn.classList.add('active');
    currentStatus = btn.dataset.status;
    loadOrders();
}

// Load orders
async function loadOrders(page = 1) {
    currentPage = page;
    const token = localStorage.getItem('auth_token');
    const tbody = document.getElementById('ordersTable');
    
    const params = new URLSearchParams();
    params.append('page', page);
    
    if (currentStatus) params.append('status', currentStatus);
    
    const dateFrom = document.getElementById('dateFrom').value;
    const dateTo = document.getElementById('dateTo').value;
    const payment = document.getElementById('paymentFilter').value;
    const search = document.getElementById('searchInput').value;
    
    if (dateFrom) params.append('date_from', dateFrom);
    if (dateTo) params.append('date_to', dateTo);
    if (payment) params.append('payment_method', payment);
    if (search) params.append('search', search);
    
    tbody.innerHTML = '<tr><td colspan="9" class="loading-cell"><i class="fas fa-spinner fa-spin"></i> Đang tải...</td></tr>';
    
    try {
        const response = await fetch(`/api/admin/orders?${params.toString()}`, {
            headers: {
                'Authorization': `Bearer ${token}`,
                'Accept': 'application/json'
            }
        });
        
        if (response.ok) {
            const result = await response.json();
            const data = result.data || {};
            orders = data.data || [];
            
            if (orders.length === 0) {
                tbody.innerHTML = '<tr><td colspan="9" class="loading-cell">Không có đơn hàng nào</td></tr>';
            } else {
                tbody.innerHTML = orders.map(order => `
                    <tr>
                        <td><input type="checkbox" class="order-checkbox" value="${order.id}"></td>
                        <td>
                            <span class="order-number" onclick="viewOrder(${order.id})">#${order.order_number || order.id}</span>
                        </td>
                        <td>
                            <div class="customer-cell">
                                <span class="name">${order.receiver_name || order.user?.name || 'Khách vãng lai'}</span>
                                <span class="phone">${order.receiver_phone || order.user?.phone || ''}</span>
                            </div>
                        </td>
                        <td>
                            <div class="products-preview">
                                ${renderProductsPreview(order.items)}
                            </div>
                        </td>
                        <td><strong>${formatPrice(order.total_amount)}₫</strong></td>
                        <td>
                            <div class="payment-method">
                                <i class="${getPaymentIcon(order.payment?.payment_method)}"></i>
                                <span>${getPaymentText(order.payment?.payment_method)}</span>
                            </div>
                            <div class="payment-status ${order.payment?.status || 'pending'}">
                                ${getPaymentStatusText(order.payment?.status)}
                            </div>
                        </td>
                        <td>
                            <span class="status-badge ${order.status}" onclick="openStatusModal(${order.id}, '${order.status}')">${getStatusText(order.status)}</span>
                        </td>
                        <td>${formatDate(order.created_at)}</td>
                        <td>
                            <div class="action-btns">
                                <button class="btn-view" onclick="viewOrder(${order.id})" title="Xem chi tiết">
                                    <i class="fas fa-eye"></i>
                                </button>
                                ${order.status === 'pending' ? `
                                    <button class="btn-confirm" onclick="confirmOrder(${order.id})" title="Xác nhận">
                                        <i class="fas fa-check"></i>
                                    </button>
                                    <button class="btn-cancel" onclick="cancelOrder(${order.id})" title="Hủy">
                                        <i class="fas fa-times"></i>
                                    </button>
                                ` : ''}
                                ${(order.payment?.status !== 'completed' && ['banking', 'wallet'].includes(order.payment?.payment_method)) ? `
                                    <button class="btn-confirm" onclick="confirmPayment(${order.id})" title="Xác nhận đã thanh toán">
                                        <i class="fas fa-qrcode"></i>
                                    </button>
                                ` : ''}
                                ${order.status === 'paid' ? `
                                    <button class="btn-confirm" onclick="shipOrder(${order.id})" title="Giao hàng">
                                        <i class="fas fa-truck"></i>
                                    </button>
                                ` : ''}
                                ${order.status === 'shipping' ? `
                                    <button class="btn-confirm" onclick="completeOrder(${order.id})" title="Hoàn thành">
                                        <i class="fas fa-check-double"></i>
                                    </button>
                                ` : ''}
                            </div>
                        </td>
                    </tr>
                `).join('');
            }
            
            // Update pagination
            if (data.last_page) {
                renderPagination(data.current_page, data.last_page);
            }
        }
    } catch (error) {
        console.error('Error loading orders:', error);
        tbody.innerHTML = '<tr><td colspan="9" class="loading-cell">Không thể tải dữ liệu</td></tr>';
    }
}

function renderProductsPreview(items) {
    if (!items || items.length === 0) return '-';
    
    const productNames = items.map(item => item.product?.name || 'Sản phẩm').join(', ');
    
    if (items.length > 2) {
        return items.slice(0, 2).map(item => item.product?.name || 'Sản phẩm').join(', ') + 
               ` <span class="more">+${items.length - 2}</span>`;
    }
    
    return productNames;
}

function getPaymentIcon(method) {
    const icons = {
        'cod': 'fas fa-money-bill-wave',
        'bank_transfer': 'fas fa-university',
        'momo': 'fas fa-wallet',
        'vnpay': 'fas fa-credit-card'
    };
    return icons[method] || 'fas fa-money-bill';
}

function getPaymentText(method) {
    const texts = {
        'cod': 'COD',
        'bank_transfer': 'Chuyển khoản',
        'momo': 'MoMo',
        'vnpay': 'VNPay'
    };
    return texts[method] || method;
}

function getPaymentStatusText(status) {
    const texts = {
        'pending': 'Chờ thanh toán',
        'completed': 'Đã thanh toán',
        'failed': 'Thanh toán lỗi'
    };
    return texts[status] || 'Chờ thanh toán';
}

function getStatusText(status) {
    const statusMap = {
        'pending': 'Chờ xác nhận',
        'paid': 'Đã xác nhận',
        'shipping': 'Đang giao',
        'completed': 'Hoàn thành',
        'cancelled': 'Đã hủy'
    };
    return statusMap[status] || status;
}

function formatDate(dateStr) {
    const date = new Date(dateStr);
    return date.toLocaleDateString('vi-VN', { day: '2-digit', month: '2-digit', year: 'numeric' });
}

function searchOrders() {
    clearTimeout(window.searchTimeout);
    window.searchTimeout = setTimeout(() => loadOrders(), 300);
}

async function viewOrder(id) {
    const token = localStorage.getItem('auth_token');
    
    try {
        const response = await fetch('/api/admin/orders/' + id, {
            headers: {
                'Authorization': 'Bearer ' + token,
                'Accept': 'application/json'
            }
        });
        
        if (response.ok) {
            const result = await response.json();
            const order = result.data?.order || result.data || result;
            console.log('Order details:', order); // Debug
            document.getElementById('modalOrderNumber').textContent = order.order_number || order.id;
            
            // Tính subtotal từ items nếu không có
            let subtotal = order.subtotal || 0;
            if (subtotal === 0 && order.items && order.items.length > 0) {
                subtotal = order.items.reduce((sum, item) => sum + (item.price * item.quantity), 0);
            }
            
            const discount = order.discount || 0;
            const shippingFee = order.shipping_fee || 0;
            const total = order.total || (subtotal - discount + shippingFee);
            
            let html = '<div class="order-detail-grid">' +
                '<div class="detail-section">' +
                    '<h4><i class="fas fa-user"></i> Thông tin khách hàng</h4>' +
                    '<div class="detail-row">' +
                        '<span class="label">Họ tên:</span>' +
                        '<span>' + (order.shipping_name || order.user?.name || '-') + '</span>' +
                    '</div>' +
                    '<div class="detail-row">' +
                        '<span class="label">Điện thoại:</span>' +
                        '<span>' + (order.shipping_phone || '-') + '</span>' +
                    '</div>' +
                    '<div class="detail-row">' +
                        '<span class="label">Email:</span>' +
                        '<span>' + (order.user?.email || '-') + '</span>' +
                    '</div>' +
                    '<div class="detail-row">' +
                        '<span class="label">Địa chỉ:</span>' +
                        '<span>' + (order.shipping_address || '-') + '</span>' +
                    '</div>' +
                '</div>' +
                '<div class="detail-section">' +
                    '<h4><i class="fas fa-file-invoice"></i> Thông tin đơn hàng</h4>' +
                    '<div class="detail-row">' +
                        '<span class="label">Mã đơn:</span>' +
                        '<span>#' + (order.order_number || order.id) + '</span>' +
                    '</div>' +
                    '<div class="detail-row">' +
                        '<span class="label">Ngày đặt:</span>' +
                        '<span>' + formatDate(order.created_at) + '</span>' +
                    '</div>' +
                    '<div class="detail-row">' +
                        '<span class="label">Thanh toán:</span>' +
                        '<span>' + getPaymentText(order.payment_method) + '</span>' +
                    '</div>' +
                    '<div class="detail-row">' +
                        '<span class="label">Trạng thái:</span>' +
                        '<span class="status-badge ' + order.status + '">' + getStatusText(order.status) + '</span>' +
                    '</div>' +
                '</div>' +
            '</div>' +
            '<table class="order-items-table">' +
                '<thead>' +
                    '<tr>' +
                        '<th>Sản phẩm</th>' +
                        '<th>Đơn giá</th>' +
                        '<th>Số lượng</th>' +
                        '<th>Thành tiền</th>' +
                    '</tr>' +
                '</thead>' +
                '<tbody>';
            
            (order.items || []).forEach(item => {
                html += '<tr>' +
                    '<td>' +
                        '<div class="item-cell">' +
                            '<img src="' + (item.product?.image || 'https://placehold.co/50x50') + '" alt="">' +
                            '<div>' +
                                '<div>' + (item.product?.name || item.product_name) + '</div>' +
                                (item.variant ? '<small style="color: #6b7280">' + item.variant + '</small>' : '') +
                            '</div>' +
                        '</div>' +
                    '</td>' +
                    '<td>' + formatPrice(item.price) + '₫</td>' +
                    '<td>' + item.quantity + '</td>' +
                    '<td><strong>' + formatPrice(item.price * item.quantity) + '₫</strong></td>' +
                '</tr>';
            });
            
            html += '</tbody>' +
                '<tfoot>' +
                    '<tr>' +
                        '<td colspan="3" style="text-align: right">Tạm tính:</td>' +
                        '<td>' + formatPrice(subtotal) + '₫</td>' +
                    '</tr>';
            
            if (discount > 0) {
                html += '<tr>' +
                    '<td colspan="3" style="text-align: right">Giảm giá:</td>' +
                    '<td>-' + formatPrice(discount) + '₫</td>' +
                '</tr>';
            }
            
            html += '<tr>' +
                    '<td colspan="3" style="text-align: right">Phí vận chuyển:</td>' +
                    '<td>' + (shippingFee > 0 ? formatPrice(shippingFee) + '₫' : 'Miễn phí') + '</td>' +
                '</tr>' +
                '<tr>' +
                    '<td colspan="3" style="text-align: right"><strong>Tổng cộng:</strong></td>' +
                    '<td><strong style="color: var(--primary); font-size: 18px">' + formatPrice(total) + '₫</strong></td>' +
                '</tr>' +
            '</tfoot>' +
        '</table>';
            
            if (order.note) {
                html += '<div class="detail-section" style="margin-top: 16px">' +
                    '<h4><i class="fas fa-sticky-note"></i> Ghi chú</h4>' +
                    '<p>' + order.note + '</p>' +
                '</div>';
            }
            
            document.getElementById('orderDetailContent').innerHTML = html;
            document.getElementById('orderModal').classList.add('active');
        }
    } catch (error) {
        console.error('Error loading order:', error);
    }
}

function closeModal() {
    document.getElementById('orderModal').classList.remove('active');
}

function openStatusModal(id, currentStatus) {
    document.getElementById('statusOrderId').value = id;
    document.getElementById('newStatus').value = currentStatus;
    document.getElementById('statusNote').value = '';
    document.getElementById('statusModal').classList.add('active');
}

function closeStatusModal() {
    document.getElementById('statusModal').classList.remove('active');
}

async function updateOrderStatus() {
    const token = localStorage.getItem('auth_token');
    const id = document.getElementById('statusOrderId').value;
    const status = document.getElementById('newStatus').value;
    const note = document.getElementById('statusNote').value;
    
    try {
        const response = await fetch(`/api/admin/orders/${id}`, {
            method: 'PUT',
            headers: {
                'Content-Type': 'application/json',
                'Authorization': `Bearer ${token}`,
                'Accept': 'application/json'
            },
            body: JSON.stringify({ status, note })
        });
        
        if (response.ok) {
            alert('Cập nhật trạng thái thành công!');
            closeStatusModal();
            loadOrders(currentPage);
            loadOrderCounts();
        } else {
            alert('Không thể cập nhật trạng thái');
        }
    } catch (error) {
        console.error('Error updating status:', error);
    }
}

async function confirmPayment(id) {
    if (!confirm('Xác nhận đơn hàng này đã được thanh toán?')) return;

    const token = localStorage.getItem('auth_token');

    try {
        const response = await fetch(`/api/admin/orders/${id}/confirm-payment`, {
            method: 'PUT',
            headers: {
                'Content-Type': 'application/json',
                'Authorization': `Bearer ${token}`,
                'Accept': 'application/json'
            }
        });

        if (response.ok) {
            alert('Đã xác nhận thanh toán thành công!');
            loadOrders(currentPage);
            loadOrderCounts();
        } else {
            const data = await response.json().catch(() => ({}));
            alert(data.message || 'Không thể xác nhận thanh toán');
        }
    } catch (error) {
        console.error('Error confirming payment:', error);
        alert('Không thể xác nhận thanh toán');
    }
}

// Quick action functions
async function quickUpdateStatus(id, status, confirmMessage) {
    if (!confirm(confirmMessage)) return;
    
    const token = localStorage.getItem('auth_token');
    
    try {
        const response = await fetch(`/api/admin/orders/${id}`, {
            method: 'PUT',
            headers: {
                'Content-Type': 'application/json',
                'Authorization': `Bearer ${token}`,
                'Accept': 'application/json'
            },
            body: JSON.stringify({ status })
        });
        
        if (response.ok) {
            loadOrders(currentPage);
            loadOrderCounts();
        } else {
            alert('Không thể cập nhật trạng thái');
        }
    } catch (error) {
        console.error('Error:', error);
    }
}

function confirmOrder(id) {
    quickUpdateStatus(id, 'paid', 'Xác nhận đơn hàng này?');
}

function cancelOrder(id) {
    quickUpdateStatus(id, 'cancelled', 'Hủy đơn hàng này?');
}

function shipOrder(id) {
    quickUpdateStatus(id, 'shipping', 'Chuyển sang trạng thái đang giao hàng?');
}

function completeOrder(id) {
    quickUpdateStatus(id, 'completed', 'Xác nhận đơn hàng đã hoàn thành?');
}

async function deleteOrder(id) {
    if (!confirm('Bạn có chắc muốn xóa đơn hàng này?')) return;
    
    const token = localStorage.getItem('auth_token');
    
    try {
        const response = await fetch(`/api/admin/orders/${id}`, {
            method: 'DELETE',
            headers: {
                'Authorization': `Bearer ${token}`,
                'Accept': 'application/json'
            }
        });
        
        if (response.ok) {
            alert('Đã xóa đơn hàng');
            loadOrders(currentPage);
            loadOrderCounts();
        } else {
            alert('Không thể xóa đơn hàng');
        }
    } catch (error) {
        console.error('Error deleting order:', error);
    }
}

function toggleSelectAll() {
    const checked = document.getElementById('selectAll').checked;
    document.querySelectorAll('.order-checkbox').forEach(cb => cb.checked = checked);
}

function renderPagination(current, total) {
    const container = document.getElementById('pagination');
    let html = '';
    
    html += `<button ${current === 1 ? 'disabled' : ''} onclick="loadOrders(${current - 1})"><i class="fas fa-chevron-left"></i></button>`;
    
    for (let i = 1; i <= total; i++) {
        if (i === 1 || i === total || (i >= current - 2 && i <= current + 2)) {
            html += `<button class="${i === current ? 'active' : ''}" onclick="loadOrders(${i})">${i}</button>`;
        } else if (i === current - 3 || i === current + 3) {
            html += `<span style="padding: 0 8px;">...</span>`;
        }
    }
    
    html += `<button ${current === total ? 'disabled' : ''} onclick="loadOrders(${current + 1})"><i class="fas fa-chevron-right"></i></button>`;
    
    container.innerHTML = html;
}

function printOrder() {
    window.print();
}

function exportOrders() {
    alert('Tính năng xuất Excel đang phát triển');
}
</script>
@endpush

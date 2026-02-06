@extends('layouts.app')

@section('title', 'Đơn hàng của tôi - XanhStore')

@section('content')
<div class="orders-page">
    <div class="container">
        <div class="orders-layout">
            <!-- Left Sidebar -->
            <div class="orders-sidebar">
                @include('partials.profile-sidebar')
            </div>

            <!-- Orders List (Middle) -->
            <main class="orders-main">
                <div class="profile-section">
                    <div class="section-header">
                        <h2><i class="fas fa-shopping-bag"></i> Đơn hàng của tôi</h2>
                    </div>
                    
                    <!-- Order Tabs -->
                    <div class="order-tabs">
                        <button class="tab-btn active" onclick="filterOrders('all')">
                            Tất cả
                        </button>
                        <button class="tab-btn" onclick="filterOrders('pending')">
                            Chờ xác nhận
                        </button>
                        <button class="tab-btn" onclick="filterOrders('processing')">
                            Đang xử lý
                        </button>
                        <button class="tab-btn" onclick="filterOrders('shipping')">
                            Đang giao
                        </button>
                        <button class="tab-btn" onclick="filterOrders('completed')">
                            Hoàn thành
                        </button>
                        <button class="tab-btn" onclick="filterOrders('cancelled')">
                            Đã hủy
                        </button>
                    </div>
                    
                    <div id="ordersContainer">
                        <div class="loading">
                            <i class="fas fa-spinner fa-spin"></i>
                            <p>Đang tải...</p>
                        </div>
                    </div>
                </div>
            </main>

            <!-- Order Detail Panel (Right) -->
            <aside class="order-detail-panel" id="orderDetailPanel">
                <div class="panel-placeholder">
                    <i class="fas fa-box-open"></i>
                    <p>Chọn một đơn hàng để xem chi tiết</p>
                </div>
            </aside>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
.orders-page {
    padding: 24px 0 60px;
    background: #f9fafb;
    min-height: 100vh;
}

/* Layout 3 cột */
.orders-layout {
    display: grid;
    grid-template-columns: 250px 1fr 400px;
    gap: 24px;
    align-items: start;
}

.orders-sidebar {
    position: sticky;
    top: 24px;
}

.orders-main {
    min-width: 0;
}

/* Order Detail Panel bên phải */
.order-detail-panel {
    background: white;
    border-radius: 16px;
    box-shadow: var(--shadow-sm);
    position: sticky;
    top: 24px;
    max-height: calc(100vh - 48px);
    overflow-y: auto;
}

.panel-placeholder {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    padding: 60px 20px;
    color: var(--gray-400);
    text-align: center;
}

.panel-placeholder i {
    font-size: 48px;
    margin-bottom: 16px;
}

.panel-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 20px;
    border-bottom: 1px solid #e5e7eb;
    position: sticky;
    top: 0;
    background: white;
    z-index: 10;
}

.panel-header h3 {
    font-size: 16px;
    font-weight: 700;
    margin: 0;
}

.panel-close {
    width: 32px;
    height: 32px;
    border: none;
    background: #f3f4f6;
    border-radius: 50%;
    font-size: 18px;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
}

.panel-body {
    padding: 20px;
}

@media (max-width: 1200px) {
    .orders-layout {
        grid-template-columns: 1fr 350px;
    }
    .orders-sidebar {
        display: none;
    }
}

@media (max-width: 900px) {
    .orders-layout {
        grid-template-columns: 1fr;
    }
    .order-detail-panel {
        display: none;
    }
    .order-detail-panel.active {
        display: block;
        position: fixed;
        top: 0;
        right: 0;
        bottom: 0;
        width: 100%;
        max-width: 400px;
        max-height: 100vh;
        border-radius: 16px 0 0 16px;
        z-index: 1000;
    }
}

.order-tabs {
    display: flex;
    gap: 8px;
    margin-bottom: 24px;
    overflow-x: auto;
    padding-bottom: 8px;
}

.order-tabs .tab-btn {
    padding: 8px 16px;
    border: 1px solid #e5e7eb;
    border-radius: 20px;
    background: white;
    color: var(--gray-600);
    font-size: 14px;
    font-weight: 500;
    white-space: nowrap;
    cursor: pointer;
    transition: all 0.2s;
}

.order-tabs .tab-btn:hover {
    border-color: var(--primary);
    color: var(--primary);
}

.order-tabs .tab-btn.active {
    background: var(--primary);
    border-color: var(--primary);
    color: white;
}

.orders-list {
    display: flex;
    flex-direction: column;
    gap: 16px;
}

.order-card {
    background: white;
    border-radius: 12px;
    overflow: hidden;
    box-shadow: var(--shadow-sm);
}

.order-card-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 16px 20px;
    background: #f9fafb;
    border-bottom: 1px solid #e5e7eb;
}

.order-info {
    display: flex;
    align-items: center;
    gap: 16px;
}

.order-number {
    font-weight: 600;
    color: var(--gray-900);
}

.order-date {
    font-size: 13px;
    color: var(--gray-500);
}

.order-status {
    padding: 6px 16px;
    border-radius: 20px;
    font-size: 13px;
    font-weight: 500;
}

.order-status.pending { background: #fef3c7; color: #d97706; }
.order-status.confirmed { background: #dbeafe; color: #2563eb; }
.order-status.processing { background: #e0e7ff; color: #4f46e5; }
.order-status.shipping { background: #fae8ff; color: #c026d3; }
.order-status.completed { background: #d1fae5; color: #059669; }
.order-status.cancelled { background: #fee2e2; color: #dc2626; }

.order-card-body {
    padding: 16px 20px;
}

.order-products {
    display: flex;
    flex-direction: column;
    gap: 12px;
}

.order-product {
    display: flex;
    align-items: center;
    gap: 16px;
}

.order-product-image {
    width: 70px;
    height: 70px;
    border-radius: 8px;
    overflow: hidden;
    background: #f9fafb;
    flex-shrink: 0;
}

.order-product-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.order-product-info {
    flex: 1;
}

.order-product-name {
    font-weight: 500;
    color: var(--gray-900);
    margin-bottom: 4px;
}

.order-product-variant {
    font-size: 13px;
    color: var(--gray-500);
}

.order-product-price {
    text-align: right;
}

.order-product-price .price {
    font-weight: 600;
    color: var(--primary);
}

.order-product-price .quantity {
    font-size: 13px;
    color: var(--gray-500);
}

.order-card-footer {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 16px 20px;
    border-top: 1px solid #e5e7eb;
}

.order-total {
    font-size: 13px;
    color: var(--gray-600);
}

.order-total strong {
    font-size: 18px;
    color: var(--primary);
    margin-left: 8px;
}

.order-actions {
    display: flex;
    gap: 8px;
}

/* CSS cho order-detail-section đã được xử lý trong panel */

.order-detail-section {
    margin-bottom: 24px;
}

.order-detail-section h4 {
    font-size: 14px;
    font-weight: 600;
    color: var(--gray-700);
    margin-bottom: 12px;
    text-transform: uppercase;
}

.order-timeline {
    display: flex;
    flex-direction: column;
    gap: 16px;
}

.timeline-item {
    display: flex;
    gap: 16px;
}

.timeline-icon {
    width: 32px;
    height: 32px;
    border-radius: 50%;
    background: #e5e7eb;
    display: flex;
    align-items: center;
    justify-content: center;
    color: var(--gray-500);
    flex-shrink: 0;
}

.timeline-item.completed .timeline-icon {
    background: #d1fae5;
    color: #059669;
}

.timeline-content {
    flex: 1;
}

.timeline-title {
    font-weight: 500;
    color: var(--gray-900);
}

.timeline-time {
    font-size: 13px;
    color: var(--gray-500);
}

.empty-orders {
    text-align: center;
    padding: 60px 20px;
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

@media (max-width: 768px) {
    .order-card-header {
        flex-direction: column;
        align-items: flex-start;
        gap: 12px;
    }
    
    .order-card-footer {
        flex-direction: column;
        gap: 16px;
    }
    
    .order-actions {
        width: 100%;
    }
    
    .order-actions .btn {
        flex: 1;
    }
}
</style>
@endpush

@push('scripts')
<script>
let allOrders = [];
let currentFilter = 'all';

// Format price
function formatPrice(price) {
    return new Intl.NumberFormat('vi-VN').format(price);
}

// Check auth
document.addEventListener('DOMContentLoaded', () => {
    const token = localStorage.getItem('auth_token');
    if (!token) {
        window.location.href = '/login';
        return;
    }
    
    loadOrders();
});

// Load orders
async function loadOrders() {
    const token = localStorage.getItem('auth_token');
    const container = document.getElementById('ordersContainer');
    
    try {
        const response = await fetch('/api/orders', {
            headers: {
                'Authorization': `Bearer ${token}`,
                'Accept': 'application/json'
            }
        });
        
        if (response.ok) {
            const data = await response.json();
            allOrders = data.data?.orders || data.data || [];
            renderOrders();
        }
    } catch (error) {
        console.error('Error loading orders:', error);
        container.innerHTML = '<p style="color: var(--gray-500); text-align: center;">Không thể tải đơn hàng</p>';
    }
}

// Filter orders
function filterOrders(status) {
    currentFilter = status;
    
    document.querySelectorAll('.order-tabs .tab-btn').forEach(btn => {
        btn.classList.toggle('active', btn.textContent.toLowerCase().includes(
            status === 'all' ? 'tất cả' : getStatusText(status).toLowerCase()
        ));
    });
    
    renderOrders();
}

// Render orders
function renderOrders() {
    const container = document.getElementById('ordersContainer');
    let orders = currentFilter === 'all' ? allOrders : allOrders.filter(o => o.status === currentFilter);
    
    if (orders.length === 0) {
        container.innerHTML = `
            <div class="empty-orders">
                <i class="fas fa-shopping-bag"></i>
                <p>${currentFilter === 'all' ? 'Bạn chưa có đơn hàng nào' : 'Không có đơn hàng nào trong trạng thái này'}</p>
                <a href="/products" class="btn btn-primary">Mua sắm ngay</a>
            </div>
        `;
        return;
    }
    
    container.innerHTML = `
        <div class="orders-list">
            ${orders.map(order => `
                <div class="order-card">
                    <div class="order-card-header">
                        <div class="order-info">
                            <span class="order-number">Đơn hàng #${order.order_number || order.id}</span>
                            <span class="order-date">${new Date(order.created_at).toLocaleDateString('vi-VN', { year: 'numeric', month: 'long', day: 'numeric' })}</span>
                        </div>
                        <span class="order-status ${order.status}">${getStatusText(order.status)}</span>
                    </div>
                    <div class="order-card-body">
                        <div class="order-products">
                            ${(order.items || []).slice(0, 2).map(item => `
                                <div class="order-product">
                                    <div class="order-product-image">
                                        <img src="${item.product?.image || 'https://placehold.co/70x70/f5f5f5/333?text=No+Image'}" alt="${item.product?.name}">
                                    </div>
                                    <div class="order-product-info">
                                        <div class="order-product-name">${item.product?.name || 'Sản phẩm'}</div>
                                        ${item.variant ? `<div class="order-product-variant">${item.variant}</div>` : ''}
                                    </div>
                                    <div class="order-product-price">
                                        <div class="price">${formatPrice(item.price)}₫</div>
                                        <div class="quantity">x${item.quantity}</div>
                                    </div>
                                </div>
                            `).join('')}
                            ${(order.items || []).length > 2 ? `
                                <p style="color: var(--gray-500); font-size: 13px;">+ ${order.items.length - 2} sản phẩm khác</p>
                            ` : ''}
                        </div>
                    </div>
                    <div class="order-card-footer">
                        <div class="order-total">
                            Tổng tiền: <strong>${formatPrice(order.total_amount || order.total)}₫</strong>
                        </div>
                        <div class="order-actions">
                            <button class="btn btn-outline btn-sm" onclick="viewOrderDetail(${order.id})">
                                <i class="fas fa-eye"></i> Chi tiết
                            </button>
                            ${order.status === 'pending' ? `
                                <button class="btn btn-danger btn-sm" onclick="cancelOrder(${order.id})">
                                    <i class="fas fa-times"></i> Hủy đơn
                                </button>
                            ` : ''}
                            ${order.status === 'completed' ? `
                                <button class="btn btn-primary btn-sm" onclick="reorder(${order.id})">
                                    <i class="fas fa-redo"></i> Mua lại
                                </button>
                            ` : ''}
                        </div>
                    </div>
                </div>
            `).join('')}
        </div>
    `;
}

// Get status text
function getStatusText(status) {
    const statusMap = {
        'pending': 'Chờ xác nhận',
        'confirmed': 'Đã xác nhận',
        'processing': 'Đang xử lý',
        'shipping': 'Đang giao',
        'completed': 'Hoàn thành',
        'cancelled': 'Đã hủy'
    };
    return statusMap[status] || status;
}

// View order detail - hiển thị bên phải
async function viewOrderDetail(orderId) {
    const token = localStorage.getItem('auth_token');
    const panel = document.getElementById('orderDetailPanel');
    
    // Hiển thị loading
    panel.innerHTML = `
        <div class="panel-header">
            <h3>Chi tiết đơn hàng</h3>
            <button class="panel-close" onclick="closeDetailPanel()"><i class="fas fa-times"></i></button>
        </div>
        <div class="panel-body">
            <div class="loading" style="text-align: center; padding: 40px;"><i class="fas fa-spinner fa-spin"></i> Đang tải...</div>
        </div>
    `;
    panel.classList.add('active');
    
    try {
        const response = await fetch(`/api/orders/${orderId}`, {
            headers: {
                'Authorization': `Bearer ${token}`,
                'Accept': 'application/json'
            }
        });
        
        if (response.ok) {
            const data = await response.json();
            const order = data.data?.order || data.data || data;
            
            console.log('Order detail:', order);
            
            // Calculate subtotal from items
            const subtotal = (order.items || []).reduce((sum, item) => sum + (item.price * item.quantity), 0);
            const total = order.total_amount || subtotal;
            const shippingFee = order.shipping_fee || 0;
            
            panel.innerHTML = `
                <div class="panel-header">
                    <h3>Đơn hàng #${order.id}</h3>
                    <button class="panel-close" onclick="closeDetailPanel()"><i class="fas fa-times"></i></button>
                </div>
                <div class="panel-body">
                    <div class="order-detail-section">
                        <h4>TRẠNG THÁI ĐƠN HÀNG</h4>
                        <div class="order-timeline">
                            <div class="timeline-item ${['pending', 'paid', 'confirmed', 'processing', 'shipping', 'completed'].includes(order.status) ? 'completed' : ''}">
                                <div class="timeline-icon"><i class="fas fa-clock"></i></div>
                                <div class="timeline-content">
                                    <div class="timeline-title">Đặt hàng thành công</div>
                                    <div class="timeline-time">${order.created_at ? new Date(order.created_at).toLocaleString('vi-VN') : ''}</div>
                                </div>
                            </div>
                            <div class="timeline-item ${['paid', 'confirmed', 'processing', 'shipping', 'completed'].includes(order.status) ? 'completed' : ''}">
                                <div class="timeline-icon"><i class="fas fa-check"></i></div>
                                <div class="timeline-content">
                                    <div class="timeline-title">Đã xác nhận</div>
                                </div>
                            </div>
                            <div class="timeline-item ${['processing', 'shipping', 'completed'].includes(order.status) ? 'completed' : ''}">
                                <div class="timeline-icon"><i class="fas fa-box"></i></div>
                                <div class="timeline-content">
                                    <div class="timeline-title">Đang xử lý</div>
                                </div>
                            </div>
                            <div class="timeline-item ${['shipping', 'completed'].includes(order.status) ? 'completed' : ''}">
                                <div class="timeline-icon"><i class="fas fa-shipping-fast"></i></div>
                                <div class="timeline-content">
                                    <div class="timeline-title">Đang giao hàng</div>
                                </div>
                            </div>
                            <div class="timeline-item ${order.status === 'completed' ? 'completed' : ''}">
                                <div class="timeline-icon"><i class="fas fa-check-circle"></i></div>
                                <div class="timeline-content">
                                    <div class="timeline-title">Hoàn thành</div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="order-detail-section">
                        <h4>THÔNG TIN GIAO HÀNG</h4>
                        <p><strong>${order.receiver_name || ''}</strong></p>
                        <p>${order.receiver_phone || ''}</p>
                        <p>${order.receiver_address || ''}</p>
                    </div>
                    
                    <div class="order-detail-section">
                        <h4>SẢN PHẨM</h4>
                        <div class="order-products">
                            ${(order.items || []).map(item => `
                                <div class="order-product">
                                    <div class="order-product-image">
                                        <img src="${item.product?.images?.[0]?.image_url || item.product?.image || 'https://placehold.co/70x70/f5f5f5/333?text=No+Image'}" alt="${item.product?.name}">
                                    </div>
                                    <div class="order-product-info">
                                        <div class="order-product-name">${item.product?.name || 'Sản phẩm'}</div>
                                        ${item.variant ? `<div class="order-product-variant">${item.variant}</div>` : ''}
                                    </div>
                                    <div class="order-product-price">
                                        <div class="price">${formatPrice(item.price)}₫</div>
                                        <div class="quantity">x${item.quantity}</div>
                                    </div>
                                </div>
                            `).join('')}
                        </div>
                    </div>
                    
                    <div class="order-detail-section">
                        <h4>THANH TOÁN</h4>
                        <div style="display: flex; justify-content: space-between; margin-bottom: 8px;">
                            <span>Tạm tính</span>
                            <span>${formatPrice(subtotal)}₫</span>
                        </div>
                        <div style="display: flex; justify-content: space-between; margin-bottom: 8px;">
                            <span>Phí vận chuyển</span>
                            <span>${formatPrice(shippingFee)}₫</span>
                        </div>
                        <div style="display: flex; justify-content: space-between; font-weight: 700; font-size: 18px; padding-top: 12px; border-top: 1px solid #e5e7eb; margin-top: 12px;">
                            <span>Tổng cộng</span>
                            <span style="color: var(--primary);">${formatPrice(total)}₫</span>
                        </div>
                    </div>
                </div>
            `;
        }
    } catch (error) {
        console.error('Error loading order detail:', error);
        panel.innerHTML = `
            <div class="panel-header">
                <h3>Chi tiết đơn hàng</h3>
                <button class="panel-close" onclick="closeDetailPanel()"><i class="fas fa-times"></i></button>
            </div>
            <div class="panel-body">
                <p style="color: var(--gray-500); text-align: center;">Không thể tải chi tiết đơn hàng</p>
            </div>
        `;
    }
}

// Đóng panel chi tiết
function closeDetailPanel() {
    const panel = document.getElementById('orderDetailPanel');
    panel.classList.remove('active');
    panel.innerHTML = `
        <div class="panel-placeholder">
            <i class="fas fa-receipt"></i>
            <p>Chọn một đơn hàng để xem chi tiết</p>
        </div>
    `;
}

// Close modal (deprecated - keep for compatibility)
function closeModal() {
    closeDetailPanel();
}

// Cancel order
async function cancelOrder(orderId) {
    if (!confirm('Bạn có chắc muốn hủy đơn hàng này?')) return;
    
    const token = localStorage.getItem('auth_token');
    console.log('Cancelling order:', orderId);
    
    try {
        const response = await fetch(`/api/orders/${orderId}/cancel`, {
            method: 'PUT',
            headers: {
                'Authorization': `Bearer ${token}`,
                'Accept': 'application/json',
                'Content-Type': 'application/json'
            }
        });
        
        console.log('Cancel response status:', response.status);
        const data = await response.json();
        console.log('Cancel response data:', data);
        
        if (response.ok && data.success) {
            alert('Đã hủy đơn hàng thành công');
            loadOrders();
        } else {
            alert(data.message || 'Không thể hủy đơn hàng');
        }
    } catch (error) {
        console.error('Error cancelling order:', error);
        alert('Đã có lỗi xảy ra');
    }
}

// Reorder
async function reorder(orderId) {
    const order = allOrders.find(o => o.id === orderId);
    if (!order || !order.items) return;
    
    order.items.forEach(item => {
        addToCart(item.product_id, item.quantity);
    });
    
    alert('Đã thêm các sản phẩm vào giỏ hàng');
    window.location.href = '/cart';
}
</script>
@endpush

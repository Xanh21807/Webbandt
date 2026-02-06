@extends('layouts.app')

@section('title', 'Đặt hàng thành công - XanhStore')

@section('content')
<div class="success-page">
    <div class="container">
        <div class="success-card">
            <div class="success-icon">
                <i class="fas fa-check-circle"></i>
            </div>
            
            <h1>Đặt hàng thành công!</h1>
            <p class="success-message">Cảm ơn bạn đã đặt hàng tại XanhStore</p>
            
            <div class="order-info">
                <div class="order-info-row">
                    <span>Mã đơn hàng:</span>
                    <strong id="orderCode">#{{ $orderId }}</strong>
                </div>
                <div class="order-info-row">
                    <span>Trạng thái:</span>
                    <span class="status-badge status-pending">Chờ xác nhận</span>
                </div>
            </div>
            
            <div class="order-details" id="orderDetails">
                <!-- Load via JS -->
            </div>
            
            <div class="success-note">
                <i class="fas fa-info-circle"></i>
                <p>Chúng tôi sẽ liên hệ với bạn trong thời gian sớm nhất để xác nhận đơn hàng. Bạn có thể theo dõi trạng thái đơn hàng trong mục "Đơn hàng của tôi".</p>
            </div>
            
            <div class="success-actions">
                <a href="/orders" class="btn btn-primary">
                    <i class="fas fa-box"></i>
                    Xem đơn hàng của tôi
                </a>
                <a href="/products" class="btn btn-outline">
                    <i class="fas fa-shopping-bag"></i>
                    Tiếp tục mua sắm
                </a>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
.success-page {
    padding: 60px 0;
    background: #f9fafb;
    min-height: 100vh;
    display: flex;
    align-items: center;
}

.success-card {
    max-width: 600px;
    margin: 0 auto;
    background: white;
    border-radius: 20px;
    padding: 48px;
    text-align: center;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
}

.success-icon {
    width: 100px;
    height: 100px;
    background: linear-gradient(135deg, #10b981, #059669);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 24px;
    animation: scaleIn 0.5s ease-out;
}

@keyframes scaleIn {
    0% { transform: scale(0); }
    50% { transform: scale(1.2); }
    100% { transform: scale(1); }
}

.success-icon i {
    font-size: 48px;
    color: white;
}

.success-card h1 {
    font-size: 28px;
    font-weight: 700;
    color: #111827;
    margin-bottom: 8px;
}

.success-message {
    color: #6b7280;
    font-size: 16px;
    margin-bottom: 32px;
}

.order-info {
    background: #f3f4f6;
    border-radius: 12px;
    padding: 20px;
    margin-bottom: 24px;
}

.order-info-row {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 8px 0;
}

.order-info-row:not(:last-child) {
    border-bottom: 1px solid #e5e7eb;
}

.order-info-row span {
    color: #6b7280;
}

.order-info-row strong {
    color: var(--primary);
    font-size: 18px;
}

.status-badge {
    padding: 4px 12px;
    border-radius: 20px;
    font-size: 13px;
    font-weight: 500;
}

.status-pending {
    background: #fef3c7;
    color: #d97706;
}

.status-paid {
    background: #d1fae5;
    color: #059669;
}

.order-details {
    text-align: left;
    margin-bottom: 24px;
}

.order-item {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 12px 0;
    border-bottom: 1px solid #e5e7eb;
}

.order-item-image {
    width: 60px;
    height: 60px;
    border-radius: 8px;
    overflow: hidden;
    background: #f3f4f6;
}

.order-item-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.order-item-info {
    flex: 1;
}

.order-item-name {
    font-weight: 500;
    color: #111827;
    margin-bottom: 4px;
}

.order-item-qty {
    font-size: 13px;
    color: #6b7280;
}

.order-item-price {
    font-weight: 600;
    color: var(--primary);
}

.order-total {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 16px 0;
    font-size: 18px;
    font-weight: 700;
}

.order-total span:last-child {
    color: var(--primary);
}

.success-note {
    display: flex;
    align-items: flex-start;
    gap: 12px;
    background: #eff6ff;
    border-radius: 12px;
    padding: 16px;
    margin-bottom: 32px;
    text-align: left;
}

.success-note i {
    color: #3b82f6;
    font-size: 20px;
    margin-top: 2px;
}

.success-note p {
    color: #1e40af;
    font-size: 14px;
    margin: 0;
    line-height: 1.6;
}

.success-actions {
    display: flex;
    gap: 16px;
    justify-content: center;
}

.success-actions .btn {
    min-width: 180px;
}

@media (max-width: 640px) {
    .success-card {
        padding: 32px 20px;
    }
    
    .success-actions {
        flex-direction: column;
    }
    
    .success-actions .btn {
        width: 100%;
    }
}
</style>
@endpush

@push('scripts')
<script>
const orderId = {{ $orderId }};

document.addEventListener('DOMContentLoaded', loadOrderDetails);

async function loadOrderDetails() {
    const token = localStorage.getItem('auth_token');
    
    if (!token) return;
    
    try {
        const response = await fetch(`/api/orders/${orderId}`, {
            headers: {
                'Authorization': `Bearer ${token}`,
                'Accept': 'application/json'
            }
        });
        
        if (response.ok) {
            const data = await response.json();
            const order = data.data?.order || data.data;
            
            if (order) {
                renderOrderDetails(order);
            }
        }
    } catch (error) {
        console.error('Error loading order:', error);
    }
}

function renderOrderDetails(order) {
    const container = document.getElementById('orderDetails');
    
    let itemsHtml = order.items?.map(item => `
        <div class="order-item">
            <div class="order-item-image">
                <img src="${item.product?.images?.[0]?.image_url || 'https://placehold.co/60x60/f5f5f5/333?text=No+Image'}" alt="${item.product?.name}">
            </div>
            <div class="order-item-info">
                <div class="order-item-name">${item.product?.name || 'Sản phẩm'}</div>
                <div class="order-item-qty">x${item.quantity}</div>
            </div>
            <div class="order-item-price">${formatPrice(item.price * item.quantity)}₫</div>
        </div>
    `).join('') || '';
    
    container.innerHTML = `
        ${itemsHtml}
        <div class="order-total">
            <span>Tổng cộng:</span>
            <span>${formatPrice(order.total_amount)}₫</span>
        </div>
    `;
    
    // Update status badge
    const statusMap = {
        'pending': { text: 'Chờ xác nhận', class: 'status-pending' },
        'paid': { text: 'Đã thanh toán', class: 'status-paid' },
        'shipping': { text: 'Đang giao hàng', class: 'status-shipping' },
        'completed': { text: 'Hoàn thành', class: 'status-completed' },
        'cancelled': { text: 'Đã hủy', class: 'status-cancelled' }
    };
    
    const status = statusMap[order.status] || statusMap['pending'];
    const statusBadge = document.querySelector('.status-badge');
    if (statusBadge) {
        statusBadge.textContent = status.text;
        statusBadge.className = `status-badge ${status.class}`;
    }
}

function formatPrice(price) {
    return new Intl.NumberFormat('vi-VN').format(price);
}
</script>
@endpush

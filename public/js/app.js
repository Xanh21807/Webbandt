// Bootstrap is loaded separately in resources/js/app.js

/**
 * XanhStore - Main JavaScript
 * Common functions and utilities for frontend
 */

// API Base URL
const API_URL = '/api';

// Format price to VND format
window.formatPrice = function(price) {
    return new Intl.NumberFormat('vi-VN').format(price);
}

// Generate star rating HTML
window.generateStars = function(rating, showText = false) {
    const fullStars = Math.floor(rating);
    const hasHalf = rating % 1 >= 0.5;
    const emptyStars = 5 - fullStars - (hasHalf ? 1 : 0);
    
    let html = '<div class="stars">';
    
    for (let i = 0; i < fullStars; i++) {
        html += '<i class="fas fa-star"></i>';
    }
    
    if (hasHalf) {
        html += '<i class="fas fa-star-half-alt"></i>';
    }
    
    for (let i = 0; i < emptyStars; i++) {
        html += '<i class="far fa-star"></i>';
    }
    
    if (showText) {
        html += `<span class="rating-text">${rating.toFixed(1)}</span>`;
    }
    
    html += '</div>';
    return html;
}

// Get token from localStorage
window.getToken = function() {
    return localStorage.getItem('auth_token') || localStorage.getItem('token');
}

// Check if user is logged in
window.isLoggedIn = function() {
    return !!window.getToken();
}

// Get current user from localStorage
window.getCurrentUser = function() {
    const user = localStorage.getItem('user');
    return user ? JSON.parse(user) : null;
}

// API request helper
window.apiRequest = async function(endpoint, options = {}) {
    const token = window.getToken();
    
    const headers = {
        'Accept': 'application/json',
        'Content-Type': 'application/json',
        ...options.headers
    };
    
    if (token) {
        headers['Authorization'] = `Bearer ${token}`;
    }
    
    const response = await fetch(`${API_URL}${endpoint}`, {
        ...options,
        headers
    });
    
    // Handle 401 Unauthorized
    if (response.status === 401) {
        localStorage.removeItem('auth_token');
        localStorage.removeItem('token');
        localStorage.removeItem('user');
        window.location.href = '/login';
        return;
    }
    
    return response;
}

// Add to cart
window.addToCart = async function(productId, quantity = 1, variant = null) {
    if (!window.isLoggedIn()) {
        // Store in localStorage for guests
        let cart = JSON.parse(localStorage.getItem('guest_cart') || '[]');
        
        const existingIndex = cart.findIndex(item => 
            item.product_id === productId && item.variant === variant
        );
        
        if (existingIndex > -1) {
            cart[existingIndex].quantity += quantity;
        } else {
            cart.push({ product_id: productId, quantity, variant });
        }
        
        localStorage.setItem('guest_cart', JSON.stringify(cart));
        window.updateCartCount();
        window.showToast('Đã thêm vào giỏ hàng!', 'success');
        return true;
    }
    
    try {
        const response = await window.apiRequest('/cart/add', {
            method: 'POST',
            body: JSON.stringify({
                product_id: productId,
                quantity,
                variant
            })
        });
        
        if (response.ok) {
            window.updateCartCount();
            window.showToast('Đã thêm vào giỏ hàng!', 'success');
            return true;
        } else {
            const data = await response.json();
            window.showToast(data.message || 'Không thể thêm vào giỏ hàng', 'error');
            return false;
        }
    } catch (error) {
        console.error('Error adding to cart:', error);
        window.showToast('Có lỗi xảy ra', 'error');
        return false;
    }
}

// Update cart count in header
window.updateCartCount = async function() {
    if (typeof window.updateCartBadge === 'function') {
        window.updateCartBadge();
        return;
    }
    const countElement = document.querySelector('.cart-count') || document.getElementById('cartCount');
    if (!countElement) return;
    
    if (!window.isLoggedIn()) {
        const cart = JSON.parse(localStorage.getItem('guest_cart') || '[]');
        const count = cart.reduce((sum, item) => sum + item.quantity, 0);
        countElement.textContent = count;
        countElement.style.display = count > 0 ? 'flex' : 'none';
        return;
    }
    
    try {
        const response = await window.apiRequest('/cart');
        if (response.ok) {
            const data = await response.json();
            const items = data.data || [];
            const count = Array.isArray(items) ? items.reduce((sum, item) => sum + (item.quantity || 1), 0) : 0;
            countElement.textContent = count;
            countElement.style.display = count > 0 ? 'flex' : 'none';
        }
    } catch (error) {
        console.error('Error updating cart count:', error);
    }
}

// Toggle favorite
window.toggleFavorite = async function(productId, button = null) {
    if (!window.isLoggedIn()) {
        window.showToast('Vui lòng đăng nhập để thêm yêu thích', 'warning');
        return false;
    }
    
    try {
        const response = await window.apiRequest(`/favorites/${productId}`, {
            method: 'POST'
        });
        
        if (response.ok) {
            const data = await response.json();
            
            if (button) {
                const icon = button.querySelector('i');
                if (data.is_favorite) {
                    icon.classList.remove('far');
                    icon.classList.add('fas');
                    button.classList.add('active');
                } else {
                    icon.classList.remove('fas');
                    icon.classList.add('far');
                    button.classList.remove('active');
                }
            }
            
            window.showToast(data.is_favorite ? 'Đã thêm vào yêu thích!' : 'Đã xóa khỏi yêu thích', 'success');
            return data.is_favorite;
        }
    } catch (error) {
        console.error('Error toggling favorite:', error);
        window.showToast('Có lỗi xảy ra', 'error');
    }
    
    return false;
}

// Show toast notification
window.showToast = function(message, type = 'info') {
    // Remove existing toast
    const existingToast = document.querySelector('.toast');
    if (existingToast) {
        existingToast.remove();
    }
    
    const toast = document.createElement('div');
    toast.className = `toast toast-${type}`;
    
    const icons = {
        success: 'fas fa-check-circle',
        error: 'fas fa-times-circle',
        warning: 'fas fa-exclamation-circle',
        info: 'fas fa-info-circle'
    };
    
    toast.innerHTML = `
        <i class="${icons[type]}"></i>
        <span>${message}</span>
    `;
    
    document.body.appendChild(toast);
    
    // Trigger animation
    setTimeout(() => toast.classList.add('show'), 10);
    
    // Auto remove
    setTimeout(() => {
        toast.classList.remove('show');
        setTimeout(() => toast.remove(), 300);
    }, 3000);
}

// Custom Confirm Dialog for client pages
window.showConfirm = function(message, onConfirm, options = {}) {
    const title = options.title || 'Xác nhận';
    const confirmText = options.confirmText || 'Xác nhận';
    const cancelText = options.cancelText || 'Hủy';
    const type = options.type || 'danger'; // danger | warning | info

    let iconHtml = '🗑️';
    let iconClass = '';
    let btnClass = '';
    if (type === 'warning') {
        iconHtml = '⚠️';
        iconClass = 'icon-warning';
        btnClass = 'btn-warning';
    } else if (type === 'info') {
        iconHtml = 'ℹ️';
        iconClass = 'icon-info';
        btnClass = 'btn-info';
    }

    // Styles for confirm dialog (injected dynamically if not already present)
    if (!document.getElementById('confirm-dialog-styles')) {
        const style = document.createElement('style');
        style.id = 'confirm-dialog-styles';
        style.textContent = `
            .confirm-overlay {
                position: fixed;
                inset: 0;
                background: rgba(0, 0, 0, 0.55);
                z-index: 9999999;
                display: flex;
                align-items: center;
                justify-content: center;
                opacity: 0;
                transition: opacity 0.2s ease;
            }
            .confirm-overlay.active {
                opacity: 1;
            }
            .confirm-dialog {
                background: white;
                border-radius: 20px;
                padding: 32px 32px 28px;
                max-width: 420px;
                width: 90%;
                box-shadow: 0 25px 60px rgba(0,0,0,0.18);
                text-align: center;
                transform: translateY(-20px) scale(0.95);
                transition: all 0.25s cubic-bezier(0.175, 0.885, 0.32, 1.275);
                font-family: inherit;
            }
            .confirm-overlay.active .confirm-dialog {
                transform: translateY(0) scale(1);
            }
            .confirm-dialog-icon {
                width: 72px;
                height: 72px;
                border-radius: 50%;
                background: linear-gradient(135deg, #fee2e2, #fecaca);
                display: flex;
                align-items: center;
                justify-content: center;
                margin: 0 auto 20px;
                font-size: 32px;
            }
            .confirm-dialog-icon.icon-warning {
                background: linear-gradient(135deg, #fef3c7, #fde68a);
            }
            .confirm-dialog-icon.icon-info {
                background: linear-gradient(135deg, #dbeafe, #bfdbfe);
            }
            .confirm-dialog h3 {
                font-size: 20px;
                font-weight: 700;
                color: #111827;
                margin-bottom: 8px;
                margin-top: 0;
            }
            .confirm-dialog p {
                font-size: 14px;
                color: #6b7280;
                line-height: 1.6;
                margin-bottom: 28px;
                margin-top: 0;
            }
            .confirm-dialog-actions {
                display: flex;
                gap: 12px;
                justify-content: center;
            }
            .confirm-btn-cancel {
                flex: 1;
                padding: 11px 20px;
                border: 1.5px solid #e5e7eb;
                border-radius: 10px;
                background: white;
                color: #374151;
                font-size: 14px;
                font-weight: 500;
                cursor: pointer;
                transition: all 0.2s;
            }
            .confirm-btn-cancel:hover {
                background: #f9fafb;
                border-color: #d1d5db;
            }
            .confirm-btn-ok {
                flex: 1;
                padding: 11px 20px;
                border: none;
                border-radius: 10px;
                background: linear-gradient(135deg, #d70018, #af0010);
                color: white;
                font-size: 14px;
                font-weight: 600;
                cursor: pointer;
                transition: all 0.2s;
                box-shadow: 0 4px 12px rgba(215, 0, 24, 0.3);
            }
            .confirm-btn-ok:hover {
                background: linear-gradient(135deg, #af0010, #80000a);
                transform: translateY(-1px);
                box-shadow: 0 6px 16px rgba(215, 0, 24, 0.4);
            }
            .confirm-btn-ok.btn-warning {
                background: linear-gradient(135deg, #f59e0b, #d97706);
                box-shadow: 0 4px 12px rgba(245, 158, 11, 0.3);
            }
            .confirm-btn-ok.btn-warning:hover {
                background: linear-gradient(135deg, #d97706, #b45309);
                box-shadow: 0 6px 16px rgba(245, 158, 11, 0.4);
            }
            .confirm-btn-ok.btn-info {
                background: linear-gradient(135deg, #3b82f6, #2563eb);
                box-shadow: 0 4px 12px rgba(59, 130, 246, 0.3);
            }
            .confirm-btn-ok.btn-info:hover {
                background: linear-gradient(135deg, #2563eb, #1d4ed8);
                box-shadow: 0 6px 16px rgba(59, 130, 246, 0.4);
            }
        `;
        document.head.appendChild(style);
    }

    const overlay = document.createElement('div');
    overlay.className = 'confirm-overlay';
    overlay.innerHTML = `
        <div class="confirm-dialog">
            <div class="confirm-dialog-icon ${iconClass}">${iconHtml}</div>
            <h3>${title}</h3>
            <p>${message}</p>
            <div class="confirm-dialog-actions">
                <button class="confirm-btn-cancel" id="confirmCancelBtn">${cancelText}</button>
                <button class="confirm-btn-ok ${btnClass}" id="confirmOkBtn">${confirmText}</button>
            </div>
        </div>
    `;

    document.body.appendChild(overlay);
    requestAnimationFrame(() => overlay.classList.add('active'));

    const close = () => {
        overlay.classList.remove('active');
        setTimeout(() => overlay.remove(), 250);
    };

    overlay.querySelector('#confirmOkBtn').addEventListener('click', () => {
        close();
        if (typeof onConfirm === 'function') onConfirm();
    });
    overlay.querySelector('#confirmCancelBtn').addEventListener('click', close);
    overlay.addEventListener('click', (e) => {
        if (e.target === overlay) close();
    });
}

// Logout
window.logout = async function() {
    const token = window.getToken();
    
    if (token) {
        try {
            await window.apiRequest('/logout', { method: 'POST' });
        } catch (error) {
            console.error('Logout error:', error);
        }
    }
    
    localStorage.removeItem('auth_token');
    localStorage.removeItem('token');
    localStorage.removeItem('user');
    window.location.href = '/';
}

// Format date
window.formatDate = function(dateStr) {
    const date = new Date(dateStr);
    return date.toLocaleDateString('vi-VN', {
        day: '2-digit',
        month: '2-digit',
        year: 'numeric'
    });
}

// Format datetime
window.formatDateTime = function(dateStr) {
    const date = new Date(dateStr);
    return date.toLocaleDateString('vi-VN', {
        day: '2-digit',
        month: '2-digit',
        year: 'numeric',
        hour: '2-digit',
        minute: '2-digit'
    });
}

// Debounce function
window.debounce = function(func, wait) {
    let timeout;
    return function executedFunction(...args) {
        const later = () => {
            clearTimeout(timeout);
            func(...args);
        };
        clearTimeout(timeout);
        timeout = setTimeout(later, wait);
    };
}

// Get order status text
window.getOrderStatusText = function(status) {
    const statusMap = {
        'pending': 'Chờ xác nhận',
        'confirmed': 'Đã xác nhận',
        'processing': 'Đang xử lý',
        'shipping': 'Đang giao hàng',
        'completed': 'Hoàn thành',
        'cancelled': 'Đã hủy'
    };
    return statusMap[status] || status;
}

// Get payment method text
window.getPaymentMethodText = function(method) {
    const methodMap = {
        'cod': 'Thanh toán khi nhận hàng',
        'bank_transfer': 'Chuyển khoản ngân hàng',
        'momo': 'Ví MoMo',
        'vnpay': 'VNPay'
    };
    return methodMap[method] || method;
}

// Initialize on DOM ready
document.addEventListener('DOMContentLoaded', () => {
    // Update cart count
    window.updateCartCount();
    
    // Add toast styles if not exists
    if (!document.querySelector('#toast-styles')) {
        const style = document.createElement('style');
        style.id = 'toast-styles';
        style.textContent = `
            .toast {
                position: fixed;
                bottom: 20px;
                left: 50%;
                transform: translateX(-50%) translateY(100px);
                background: #333;
                color: white;
                padding: 12px 24px;
                border-radius: 8px;
                display: flex;
                align-items: center;
                gap: 10px;
                z-index: 10000;
                opacity: 0;
                transition: all 0.3s;
                box-shadow: 0 4px 12px rgba(0,0,0,0.3);
            }
            .toast.show {
                transform: translateX(-50%) translateY(0);
                opacity: 1;
            }
            .toast-success { background: #10b981; }
            .toast-error { background: #ef4444; }
            .toast-warning { background: #f59e0b; }
            .toast-info { background: #3b82f6; }
        `;
        document.head.appendChild(style);
    }
});

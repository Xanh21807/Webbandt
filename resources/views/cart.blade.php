@extends('layouts.app')

@section('title', 'Giỏ hàng - XanhStore')

@section('content')
<div class="cart-page">
    <div class="container">
        <div class="page-header">
            <h1><i class="fas fa-shopping-cart"></i> Giỏ hàng</h1>
        </div>

        <div class="cart-layout">
            <!-- Cart Items -->
            <div class="cart-items" id="cartItems">
                <!-- Loading -->
                <div class="cart-loading">
                    <i class="fas fa-spinner fa-spin"></i>
                    <p>Đang tải giỏ hàng...</p>
                </div>
            </div>

            <!-- Cart Summary -->
            <div class="cart-summary" id="cartSummary">
                <div class="summary-card">
                    <h3>Tóm tắt đơn hàng</h3>
                    
                    <div class="summary-row">
                        <span>Tạm tính (<span id="itemCount">0</span> sản phẩm)</span>
                        <span id="subtotal">0₫</span>
                    </div>
                    <div class="summary-row">
                        <span>Giảm giá</span>
                        <span id="discount" class="text-primary">-0₫</span>
                    </div>
                    <div class="summary-row">
                        <span>Phí vận chuyển</span>
                        <span id="shipping">Miễn phí</span>
                    </div>
                    
                    <div class="coupon-section">
                        <div class="coupon-input">
                            <input type="text" id="couponCode" placeholder="Nhập mã giảm giá">
                            <button class="btn btn-outline" onclick="applyCoupon()">Áp dụng</button>
                        </div>
                        <div id="couponMessage" class="coupon-message"></div>
                    </div>
                    
                    <div class="summary-divider"></div>
                    
                    <div class="summary-row summary-total">
                        <span>Tổng cộng</span>
                        <span id="total">0₫</span>
                    </div>
                    
                    <button class="btn btn-primary btn-full" id="checkoutBtn" onclick="proceedToCheckout()">
                        <i class="fas fa-credit-card"></i>
                        Tiến hành thanh toán
                    </button>
                    
                    <a href="/products" class="btn btn-outline btn-full">
                        <i class="fas fa-arrow-left"></i>
                        Tiếp tục mua sắm
                    </a>
                </div>
            </div>
        </div>

        <!-- Empty Cart State -->
        <div class="empty-cart" id="emptyCart" style="display: none;">
            <div class="empty-cart-icon">
                <i class="fas fa-shopping-cart"></i>
            </div>
            <h2>Giỏ hàng trống</h2>
            <p>Bạn chưa có sản phẩm nào trong giỏ hàng</p>
            <a href="/products" class="btn btn-primary">
                <i class="fas fa-shopping-bag"></i>
                Khám phá sản phẩm
            </a>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
.cart-page {
    padding: 24px 0 60px;
    background: #f9fafb;
    min-height: 100vh;
}

.page-header {
    margin-bottom: 24px;
}

.page-header h1 {
    font-size: 28px;
    font-weight: 700;
    color: var(--gray-900);
    display: flex;
    align-items: center;
    gap: 12px;
}

.cart-layout {
    display: grid;
    grid-template-columns: 1fr 400px;
    gap: 24px;
    align-items: start;
}

/* Cart Items */
.cart-items {
    background: white;
    border-radius: 16px;
    box-shadow: var(--shadow-sm);
    overflow: hidden;
}

.cart-loading {
    text-align: center;
    padding: 60px;
    color: var(--gray-500);
}

.cart-loading i {
    font-size: 32px;
    margin-bottom: 16px;
}

.cart-item {
    display: grid;
    grid-template-columns: auto 100px 1fr;
    gap: 20px;
    padding: 20px;
    border-bottom: 1px solid #e5e7eb;
    align-items: start;
    position: relative;
}

.cart-item:last-child {
    border-bottom: none;
}

.cart-item-checkbox {
    width: 20px;
    height: 20px;
    cursor: pointer;
    margin-top: 8px;
}

.cart-item-image {
    width: 100px;
    height: 100px;
    border-radius: 8px;
    overflow: hidden;
    background: #f9fafb;
}

.cart-item-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.cart-item-info {
    flex: 1;
    display: flex;
    flex-direction: column;
}

.cart-item-details {
    margin-bottom: 16px;
}

.cart-item-name {
    font-weight: 600;
    color: var(--gray-900);
    margin-bottom: 4px;
    display: block;
}

.cart-item-name:hover {
    color: var(--primary);
}

.cart-item-variant {
    font-size: 13px;
    color: var(--gray-500);
    margin-bottom: 8px;
}

.cart-item-price {
    display: flex;
    align-items: center;
    gap: 8px;
}

.cart-item-price .price-old {
    font-size: 13px;
    color: var(--gray-500);
    text-decoration: line-through;
}

.cart-item-price .price-current {
    font-weight: 600;
    color: var(--primary);
}

.cart-item-bottom {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 16px;
}

.cart-item-left {
    display: flex;
    align-items: center;
    gap: 16px;
}

.cart-item-quantity {
    display: flex;
    align-items: center;
    border: 1px solid #e5e7eb;
    border-radius: 8px;
    overflow: hidden;
}

.cart-item-quantity button {
    width: 36px;
    height: 36px;
    border: none;
    background: #f3f4f6;
    color: var(--gray-700);
    cursor: pointer;
}

.cart-item-quantity button:hover {
    background: #e5e7eb;
}

.cart-item-quantity input {
    width: 50px;
    height: 36px;
    border: none;
    text-align: center;
    font-weight: 600;
}

.cart-item-total {
    font-size: 18px;
    font-weight: 700;
    color: var(--primary);
    min-width: auto;
}

.cart-item-actions {
    display: flex;
    gap: 12px;
    align-items: center;
}

.cart-item-actions button {
    padding: 8px;
    border: none;
    background: none;
    color: var(--gray-500);
    cursor: pointer;
    transition: color 0.2s;
}

.cart-item-actions button:hover {
    color: var(--primary);
}

.cart-item-actions .btn-remove:hover {
    color: #ef4444;
}

/* Cart Header */
.cart-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 16px 20px;
    background: #f9fafb;
    border-bottom: 1px solid #e5e7eb;
}

.cart-header label {
    display: flex;
    align-items: center;
    gap: 8px;
    cursor: pointer;
}

.cart-header .btn-clear {
    color: #ef4444;
    border-color: #ef4444;
}

/* Cart Summary */
.summary-card {
    background: white;
    border-radius: 16px;
    padding: 24px;
    box-shadow: var(--shadow-sm);
    position: sticky;
    top: 100px;
}

.summary-card h3 {
    font-size: 18px;
    font-weight: 700;
    color: var(--gray-900);
    margin-bottom: 20px;
}

.summary-row {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 12px;
    font-size: 14px;
}

.summary-row span:first-child {
    color: var(--gray-600);
}

.summary-row span:last-child {
    font-weight: 500;
    color: var(--gray-900);
}

.summary-divider {
    height: 1px;
    background: #e5e7eb;
    margin: 20px 0;
}

.summary-total {
    font-size: 16px !important;
}

.summary-total span:first-child {
    font-weight: 600;
    color: var(--gray-900) !important;
}

.summary-total span:last-child {
    font-size: 24px;
    font-weight: 700;
    color: var(--primary) !important;
}

.coupon-section {
    margin-top: 20px;
    padding-top: 20px;
    border-top: 1px solid #e5e7eb;
}

.coupon-input {
    display: flex;
    gap: 8px;
}

.coupon-input input {
    flex: 1;
    padding: 10px 14px;
    border: 1px solid #e5e7eb;
    border-radius: 8px;
    font-size: 14px;
}

.coupon-message {
    font-size: 13px;
    margin-top: 8px;
}

.coupon-message.success {
    color: #10b981;
}

.coupon-message.error {
    color: #ef4444;
}

.summary-card .btn {
    margin-top: 12px;
}

/* Empty Cart */
.empty-cart {
    text-align: center;
    padding: 80px 20px;
    background: white;
    border-radius: 16px;
    box-shadow: var(--shadow-sm);
}

.empty-cart-icon {
    width: 120px;
    height: 120px;
    margin: 0 auto 24px;
    background: #f3f4f6;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
}

.empty-cart-icon i {
    font-size: 48px;
    color: #d1d5db;
}

.empty-cart h2 {
    font-size: 24px;
    color: var(--gray-900);
    margin-bottom: 8px;
}

.empty-cart p {
    color: var(--gray-500);
    margin-bottom: 24px;
}

/* Responsive */
@media (max-width: 1024px) {
    .cart-layout {
        grid-template-columns: 1fr;
    }
    
    .summary-card {
        position: static;
    }
}

@media (max-width: 768px) {
    .cart-item {
        grid-template-columns: 1fr;
        gap: 12px;
    }
    
    .cart-item-image {
        width: 80px;
        height: 80px;
    }
}
</style>
@endpush

@push('scripts')
<script>
let cartItems = [];
let appliedCoupon = null;

// Format price helper
function formatPrice(price) {
    return new Intl.NumberFormat('vi-VN').format(price);
}

// Load cart
async function loadCart() {
    const token = localStorage.getItem('auth_token');
    const container = document.getElementById('cartItems');
    
    console.log('Loading cart, token:', token ? 'exists' : 'none');
    
    if (!token) {
        // Load from localStorage for guests
        cartItems = JSON.parse(localStorage.getItem('guest_cart') || '[]');
        console.log('Guest cart items:', cartItems);
        // Nếu có product_id thì load thông tin sản phẩm
        if (cartItems.length > 0 && !cartItems[0].name) {
            await loadGuestCartProducts();
        }
        renderCart();
        return;
    }
    
    try {
        console.log('Fetching cart from API...');
        const response = await fetch('/api/cart', {
            headers: {
                'Authorization': `Bearer ${token}`,
                'Accept': 'application/json'
            }
        });
        
        console.log('Cart API response status:', response.status);
        
        if (response.ok) {
            const result = await response.json();
            console.log('Cart API result:', result);
            // data là mảng items đã format
            cartItems = Array.isArray(result.data) ? result.data : [];
            console.log('Cart items loaded:', cartItems);
        } else if (response.status === 401) {
            console.log('Token expired, loading guest cart');
            // Token hết hạn, xóa và load guest cart
            localStorage.removeItem('auth_token');
            localStorage.removeItem('user');
            cartItems = JSON.parse(localStorage.getItem('guest_cart') || '[]');
            if (cartItems.length > 0 && !cartItems[0].name) {
                await loadGuestCartProducts();
            }
        } else {
            console.log('Error response:', await response.text());
            cartItems = [];
        }
    } catch (error) {
        console.error('Error loading cart:', error);
        cartItems = [];
    }
    
    renderCart();
}

// Load product info for guest cart
async function loadGuestCartProducts() {
    const promises = cartItems.map(async (item) => {
        try {
            const res = await fetch(`/api/products/${item.product_id}`);
            const data = await res.json();
            if (data.success && data.data.product) {
                const product = data.data.product;
                item.name = product.name;
                item.price = product.price;
                item.image = product.images?.[0]?.image_url;
            }
        } catch (e) {
            console.error('Error loading product:', e);
        }
    });
    await Promise.all(promises);
}

// Render cart
function renderCart() {
    console.log('renderCart called with items:', cartItems);
    const container = document.getElementById('cartItems');
    const emptyCart = document.getElementById('emptyCart');
    const cartSummary = document.getElementById('cartSummary');
    const cartLayout = document.querySelector('.cart-layout');
    
    if (!cartItems || cartItems.length === 0) {
        console.log('No cart items, showing empty state');
        if (cartLayout) cartLayout.style.display = 'none';
        if (emptyCart) emptyCart.style.display = 'block';
        return;
    }
    
    console.log('Rendering', cartItems.length, 'cart items');
    if (cartLayout) cartLayout.style.display = 'grid';
    if (emptyCart) emptyCart.style.display = 'none';
    
    container.innerHTML = `
        <div class="cart-header">
            <label>
                <input type="checkbox" id="selectAll" onchange="toggleSelectAll()" checked>
                Chọn tất cả (${cartItems.length} sản phẩm)
            </label>
            <button class="btn btn-outline btn-clear btn-sm" onclick="clearCart()">
                <i class="fas fa-trash"></i> Xóa tất cả
            </button>
        </div>
        ${cartItems.map((item, index) => `
            <div class="cart-item" data-index="${index}">
                <input type="checkbox" class="cart-item-checkbox" checked onchange="updateSelection()">
                <div class="cart-item-image">
                    <img src="${item.image || item.product?.image || 'https://placehold.co/100x100/f5f5f5/333?text=No+Image'}" alt="${item.name || item.product?.name}">
                </div>
                <div class="cart-item-info">
                    <div class="cart-item-details">
                        <a href="/products/${item.product_id || item.id}" class="cart-item-name">${item.name || item.product?.name}</a>
                        ${item.variant ? `<div class="cart-item-variant">${item.variant}</div>` : ''}
                        <div class="cart-item-price">
                            ${item.original_price && item.original_price > item.price ? 
                                `<span class="price-old">${formatPrice(item.original_price)}₫</span>` : ''}
                            <span class="price-current">${formatPrice(item.price)}₫</span>
                        </div>
                    </div>
                    <div class="cart-item-bottom">
                        <div class="cart-item-left">
                            <div class="cart-item-quantity">
                                <button onclick="updateQuantity(${index}, -1)">−</button>
                                <input type="number" value="${item.quantity}" min="1" onchange="setQuantity(${index}, this.value)">
                                <button onclick="updateQuantity(${index}, 1)">+</button>
                            </div>
                            <div class="cart-item-total">${formatPrice(item.price * item.quantity)}₫</div>
                        </div>
                        <div class="cart-item-actions">
                            <button onclick="toggleFavorite(${item.product_id || item.id})" title="Thêm vào yêu thích">
                                <i class="far fa-heart"></i>
                            </button>
                            <button class="btn-remove" onclick="removeItem(${index})" title="Xóa">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        `).join('')}
    `;
    
    updateSummary();
}

// Update quantity
async function updateQuantity(index, delta) {
    const newQty = Math.max(1, cartItems[index].quantity + delta);
    cartItems[index].quantity = newQty;
    
    const token = localStorage.getItem('auth_token');
    if (token && cartItems[index].id) {
        try {
            await fetch(`/api/cart/items/${cartItems[index].id}`, {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json',
                    'Authorization': `Bearer ${token}`,
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ quantity: newQty })
            });
        } catch (error) {
            console.error('Error updating quantity:', error);
        }
    } else {
        localStorage.setItem('guest_cart', JSON.stringify(cartItems));
    }
    
    renderCart();
}

// Set quantity directly
function setQuantity(index, value) {
    const qty = Math.max(1, parseInt(value) || 1);
    cartItems[index].quantity = qty;
    
    const token = localStorage.getItem('auth_token');
    if (!token) {
        localStorage.setItem('guest_cart', JSON.stringify(cartItems));
    }
    
    renderCart();
}

// Remove item
async function removeItem(index) {
    if (!confirm('Bạn có chắc muốn xóa sản phẩm này?')) return;
    
    const token = localStorage.getItem('auth_token');
    if (token && cartItems[index].id) {
        try {
            await fetch(`/api/cart/items/${cartItems[index].id}`, {
                method: 'DELETE',
                headers: {
                    'Authorization': `Bearer ${token}`,
                    'Accept': 'application/json'
                }
            });
        } catch (error) {
            console.error('Error removing item:', error);
        }
    }
    
    cartItems.splice(index, 1);
    
    if (!token) {
        localStorage.setItem('guest_cart', JSON.stringify(cartItems));
    }
    
    renderCart();
    updateCartBadge();
}

// Clear cart
async function clearCart() {
    if (!confirm('Bạn có chắc muốn xóa tất cả sản phẩm?')) return;
    
    const token = localStorage.getItem('auth_token');
    if (token) {
        try {
            await fetch('/api/cart', {
                method: 'DELETE',
                headers: {
                    'Authorization': `Bearer ${token}`,
                    'Accept': 'application/json'
                }
            });
        } catch (error) {
            console.error('Error clearing cart:', error);
        }
    }
    
    cartItems = [];
    localStorage.setItem('guest_cart', '[]');
    renderCart();
    updateCartBadge();
}

// Toggle select all
function toggleSelectAll() {
    const selectAll = document.getElementById('selectAll').checked;
    document.querySelectorAll('.cart-item-checkbox').forEach(cb => {
        cb.checked = selectAll;
    });
    updateSummary();
}

// Update selection
function updateSelection() {
    const checkboxes = document.querySelectorAll('.cart-item-checkbox');
    const allChecked = Array.from(checkboxes).every(cb => cb.checked);
    document.getElementById('selectAll').checked = allChecked;
    updateSummary();
}

// Update summary
function updateSummary() {
    const checkboxes = document.querySelectorAll('.cart-item-checkbox');
    let subtotal = 0;
    let itemCount = 0;
    
    checkboxes.forEach((cb, index) => {
        if (cb.checked) {
            subtotal += cartItems[index].price * cartItems[index].quantity;
            itemCount += cartItems[index].quantity;
        }
    });
    
    let discount = 0;
    if (appliedCoupon) {
        if (appliedCoupon.type === 'percent') {
            discount = subtotal * appliedCoupon.value / 100;
        } else {
            discount = appliedCoupon.value;
        }
    }
    
    const total = Math.max(0, subtotal - discount);
    
    document.getElementById('itemCount').textContent = itemCount;
    document.getElementById('subtotal').textContent = formatPrice(subtotal) + '₫';
    document.getElementById('discount').textContent = '-' + formatPrice(discount) + '₫';
    document.getElementById('total').textContent = formatPrice(total) + '₫';
    
    // Disable checkout if no items selected
    document.getElementById('checkoutBtn').disabled = itemCount === 0;
}

// Apply coupon
async function applyCoupon() {
    const code = document.getElementById('couponCode').value.trim();
    const messageEl = document.getElementById('couponMessage');
    
    if (!code) {
        messageEl.textContent = 'Vui lòng nhập mã giảm giá';
        messageEl.className = 'coupon-message error';
        return;
    }
    
    try {
        const response = await fetch('/api/coupons/validate', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            },
            body: JSON.stringify({ code })
        });
        
        const data = await response.json();
        
        if (response.ok && data.valid) {
            appliedCoupon = data.coupon;
            messageEl.textContent = `Áp dụng thành công: Giảm ${data.coupon.type === 'percent' ? data.coupon.value + '%' : formatPrice(data.coupon.value) + '₫'}`;
            messageEl.className = 'coupon-message success';
            updateSummary();
        } else {
            messageEl.textContent = data.message || 'Mã giảm giá không hợp lệ';
            messageEl.className = 'coupon-message error';
        }
    } catch (error) {
        messageEl.textContent = 'Không thể kiểm tra mã giảm giá';
        messageEl.className = 'coupon-message error';
    }
}

// Proceed to checkout
function proceedToCheckout() {
    const checkboxes = document.querySelectorAll('.cart-item-checkbox');
    const selectedItems = [];
    
    checkboxes.forEach((cb, index) => {
        if (cb.checked) {
            selectedItems.push(cartItems[index]);
        }
    });
    
    if (selectedItems.length === 0) {
        alert('Vui lòng chọn ít nhất một sản phẩm');
        return;
    }
    
    // Store selected items for checkout
    sessionStorage.setItem('checkoutItems', JSON.stringify(selectedItems));
    if (appliedCoupon) {
        sessionStorage.setItem('appliedCoupon', JSON.stringify(appliedCoupon));
    }
    
    window.location.href = '/checkout';
}

// Update cart badge in header
function updateCartBadge() {
    if (typeof window.updateHeaderBadges === 'function') {
        window.updateHeaderBadges();
        return;
    }
    const badge = document.getElementById('cartCount');
    if (badge) {
        const count = cartItems.reduce((sum, item) => sum + item.quantity, 0);
        badge.textContent = count;
        badge.style.display = count > 0 ? 'flex' : 'none';
    }
}

// Initialize
document.addEventListener('DOMContentLoaded', loadCart);
</script>
@endpush

@extends('layouts.app')

@section('title', 'Thanh toán - XanhStore')

@section('content')
<div class="checkout-page">
    <div class="container">
        <div class="page-header">
            <h1><i class="fas fa-credit-card"></i> Thanh toán</h1>
        </div>

        <div class="checkout-layout">
            <!-- Checkout Form -->
            <div class="checkout-form">
                <!-- Shipping Info -->
                <div class="checkout-section">
                    <h2><span class="step-number">1</span> Thông tin giao hàng</h2>
                    
                    <form id="shippingForm">
                        <div class="form-row">
                            <div class="form-group">
                                <label for="name">Họ và tên *</label>
                                <input type="text" id="name" name="name" required>
                            </div>
                            <div class="form-group">
                                <label for="phone">Số điện thoại *</label>
                                <input type="tel" id="phone" name="phone" pattern="[0-9]{10,11}" required>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label for="email">Email</label>
                            <input type="email" id="email" name="email">
                        </div>
                        
                        <div class="form-row">
                            <div class="form-group">
                                <label for="province">Tỉnh/Thành phố *</label>
                                <select id="province" name="province" required onchange="loadDistricts()">
                                    <option value="">Chọn Tỉnh/Thành phố</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="district">Quận/Huyện *</label>
                                <select id="district" name="district" required onchange="loadWards()">
                                    <option value="">Chọn Quận/Huyện</option>
                                </select>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label for="ward">Phường/Xã *</label>
                            <select id="ward" name="ward" required>
                                <option value="">Chọn Phường/Xã</option>
                            </select>
                        </div>
                        
                        <div class="form-group">
                            <label for="address">Địa chỉ chi tiết *</label>
                            <input type="text" id="address" name="address" placeholder="Số nhà, tên đường..." required>
                        </div>
                        
                        <div class="form-group">
                            <label for="note">Ghi chú đơn hàng</label>
                            <textarea id="note" name="note" rows="3" placeholder="Ghi chú về đơn hàng, ví dụ: thời gian hay chỉ dẫn địa điểm giao hàng chi tiết hơn."></textarea>
                        </div>
                    </form>
                </div>

                <!-- Payment Method -->
                <div class="checkout-section">
                    <h2><span class="step-number">2</span> Phương thức thanh toán</h2>
                    
                    <div class="payment-methods">
                        <label class="payment-option active">
                            <input type="radio" name="payment" value="cod" checked>
                            <div class="payment-icon">
                                <i class="fas fa-money-bill-wave"></i>
                            </div>
                            <div class="payment-info">
                                <span class="payment-name">Thanh toán khi nhận hàng (COD)</span>
                                <span class="payment-desc">Thanh toán bằng tiền mặt khi nhận hàng</span>
                            </div>
                            <i class="fas fa-check-circle payment-check"></i>
                        </label>
                        
                        <label class="payment-option">
                            <input type="radio" name="payment" value="bank_transfer">
                            <div class="payment-icon">
                                <i class="fas fa-university"></i>
                            </div>
                            <div class="payment-info">
                                <span class="payment-name">Chuyển khoản ngân hàng</span>
                                <span class="payment-desc">Chuyển khoản trực tiếp vào tài khoản ngân hàng</span>
                            </div>
                            <i class="fas fa-check-circle payment-check"></i>
                        </label>
                        
                        <label class="payment-option">
                            <input type="radio" name="payment" value="momo">
                            <div class="payment-icon" style="background: #a50064;">
                                <i class="fas fa-wallet"></i>
                            </div>
                            <div class="payment-info">
                                <span class="payment-name">Ví MoMo</span>
                                <span class="payment-desc">Thanh toán qua ví điện tử MoMo</span>
                            </div>
                            <i class="fas fa-check-circle payment-check"></i>
                        </label>
                        
                        <label class="payment-option">
                            <input type="radio" name="payment" value="vnpay">
                            <div class="payment-icon" style="background: #0066b3;">
                                <i class="fas fa-credit-card"></i>
                            </div>
                            <div class="payment-info">
                                <span class="payment-name">VNPay</span>
                                <span class="payment-desc">Thanh toán qua cổng VNPay (ATM/Visa/Master)</span>
                            </div>
                            <i class="fas fa-check-circle payment-check"></i>
                        </label>
                    </div>
                    
                    <!-- Bank Transfer Info -->
                    <div class="bank-info" id="bankInfo" style="display: none;">
                        <h4>Thông tin chuyển khoản</h4>
                        <div class="bank-details">
                            <p><strong>Ngân hàng:</strong> Vietcombank</p>
                            <p><strong>Số tài khoản:</strong> 1234567890</p>
                            <p><strong>Chủ tài khoản:</strong> CONG TY XANHSTORE</p>
                            <p><strong>Nội dung CK:</strong> <span id="transferContent">DH + Số điện thoại</span></p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Order Summary -->
            <div class="checkout-summary">
                <div class="summary-card">
                    <h3>Đơn hàng của bạn</h3>
                    
                    <div class="order-items" id="orderItems">
                        <!-- Items loaded via JS -->
                    </div>
                    
                    <div class="summary-divider"></div>
                    
                    <div class="summary-row">
                        <span>Tạm tính</span>
                        <span id="subtotal">0₫</span>
                    </div>
                    <div class="summary-row">
                        <span>Phí vận chuyển</span>
                        <span id="shipping">0₫</span>
                    </div>
                    <div class="summary-row" id="discountRow" style="display: none;">
                        <span>Giảm giá</span>
                        <span id="discount" class="text-primary">-0₫</span>
                    </div>
                    
                    <div class="summary-divider"></div>
                    
                    <div class="summary-row summary-total">
                        <span>Tổng cộng</span>
                        <span id="total">0₫</span>
                    </div>
                    
                    <div class="checkout-terms">
                        <label class="checkbox-wrapper">
                            <input type="checkbox" id="agreeTerms" required>
                            <span class="checkmark"></span>
                            Tôi đã đọc và đồng ý với <a href="#">Điều khoản dịch vụ</a> và <a href="#">Chính sách bảo mật</a>
                        </label>
                    </div>
                    
                    <button class="btn btn-primary btn-full btn-lg" id="placeOrderBtn" onclick="placeOrder()">
                        <i class="fas fa-lock"></i>
                        Đặt hàng
                    </button>
                    
                    <div class="checkout-secure">
                        <i class="fas fa-shield-alt"></i>
                        <span>Giao dịch được bảo mật SSL</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
.checkout-page {
    padding: 24px 0 60px;
    background: #f9fafb;
    min-height: 100vh;
}

.page-header h1 {
    font-size: 28px;
    font-weight: 700;
    color: var(--gray-900);
    display: flex;
    align-items: center;
    gap: 12px;
    margin-bottom: 24px;
}

.checkout-layout {
    display: grid;
    grid-template-columns: 1fr 420px;
    gap: 24px;
    align-items: start;
}

/* Checkout Form */
.checkout-section {
    background: white;
    border-radius: 16px;
    padding: 24px;
    box-shadow: var(--shadow-sm);
    margin-bottom: 24px;
}

.checkout-section h2 {
    font-size: 18px;
    font-weight: 700;
    color: var(--gray-900);
    margin-bottom: 20px;
    display: flex;
    align-items: center;
    gap: 12px;
}

.step-number {
    width: 28px;
    height: 28px;
    background: var(--primary);
    color: white;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 14px;
    font-weight: 700;
}

.form-row {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 16px;
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
    padding: 12px 14px;
    border: 1px solid #e5e7eb;
    border-radius: 8px;
    font-size: 14px;
    transition: border-color 0.2s;
}

.form-group input:focus,
.form-group select:focus,
.form-group textarea:focus {
    border-color: var(--primary);
    outline: none;
}

/* Payment Methods */
.payment-methods {
    display: flex;
    flex-direction: column;
    gap: 12px;
}

.payment-option {
    display: flex;
    align-items: center;
    gap: 16px;
    padding: 16px;
    border: 2px solid #e5e7eb;
    border-radius: 12px;
    cursor: pointer;
    transition: all 0.2s;
}

.payment-option:hover {
    border-color: #d1d5db;
}

.payment-option.active {
    border-color: var(--primary);
    background: #fef2f2;
}

.payment-option input {
    display: none;
}

.payment-icon {
    width: 48px;
    height: 48px;
    background: var(--primary);
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 20px;
}

.payment-info {
    flex: 1;
}

.payment-name {
    font-weight: 600;
    color: var(--gray-900);
    display: block;
    margin-bottom: 2px;
}

.payment-desc {
    font-size: 13px;
    color: var(--gray-500);
}

.payment-check {
    font-size: 20px;
    color: var(--primary);
    display: none;
}

.payment-option.active .payment-check {
    display: block;
}

.bank-info {
    margin-top: 16px;
    padding: 16px;
    background: #f9fafb;
    border-radius: 8px;
}

.bank-info h4 {
    font-size: 14px;
    margin-bottom: 12px;
}

.bank-details p {
    font-size: 14px;
    color: var(--gray-700);
    margin-bottom: 8px;
}

/* Order Summary */
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
    margin-bottom: 20px;
}

.order-items {
    max-height: 300px;
    overflow-y: auto;
}

.order-item {
    display: flex;
    gap: 12px;
    padding: 12px 0;
    border-bottom: 1px solid #e5e7eb;
}

.order-item:last-child {
    border-bottom: none;
}

.order-item-image {
    width: 60px;
    height: 60px;
    border-radius: 8px;
    overflow: hidden;
    background: #f9fafb;
    flex-shrink: 0;
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
    font-size: 14px;
    font-weight: 500;
    color: var(--gray-900);
    margin-bottom: 4px;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

.order-item-qty {
    font-size: 13px;
    color: var(--gray-500);
}

.order-item-price {
    font-weight: 600;
    color: var(--primary);
    white-space: nowrap;
}

.summary-divider {
    height: 1px;
    background: #e5e7eb;
    margin: 16px 0;
}

.summary-row {
    display: flex;
    justify-content: space-between;
    margin-bottom: 12px;
    font-size: 14px;
}

.summary-total {
    font-size: 18px !important;
    font-weight: 700;
}

.summary-total span:last-child {
    color: var(--primary);
    font-size: 24px;
}

.checkout-terms {
    margin: 20px 0;
    font-size: 13px;
}

.checkout-terms a {
    color: var(--primary);
}

.btn-lg {
    height: 52px;
    font-size: 16px;
}

.checkout-secure {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    margin-top: 16px;
    font-size: 13px;
    color: var(--gray-500);
}

.checkout-secure i {
    color: #10b981;
}

/* Responsive */
@media (max-width: 1024px) {
    .checkout-layout {
        grid-template-columns: 1fr;
    }
    
    .summary-card {
        position: static;
    }
}

@media (max-width: 768px) {
    .form-row {
        grid-template-columns: 1fr;
    }
}
</style>
@endpush

@push('scripts')
<script>
let checkoutItems = [];
let appliedCoupon = null;
let shippingFee = 0;

// Format price helper
function formatPrice(price) {
    return new Intl.NumberFormat('vi-VN').format(price);
}

// Initialize
document.addEventListener('DOMContentLoaded', () => {
    loadCheckoutItems();
    loadProvinces();
    setupPaymentMethods();
    loadUserInfo();
});

// Load checkout items from session or cart API
async function loadCheckoutItems() {
    const items = sessionStorage.getItem('checkoutItems');
    const coupon = sessionStorage.getItem('appliedCoupon');
    
    if (items) {
        checkoutItems = JSON.parse(items);
        if (coupon) {
            appliedCoupon = JSON.parse(coupon);
        }
        renderOrderItems();
        updateSummary();
        return;
    }
    
    // Nếu không có trong session, load từ cart API
    const token = localStorage.getItem('auth_token');
    
    if (!token) {
        // Guest cart
        const guestCart = JSON.parse(localStorage.getItem('guest_cart') || '[]');
        if (guestCart.length === 0) {
            window.location.href = '/cart';
            return;
        }
        
        // Load product info for guest cart
        checkoutItems = await Promise.all(guestCart.map(async (item) => {
            try {
                const res = await fetch(`/api/products/${item.product_id}`);
                const data = await res.json();
                if (data.success && data.data.product) {
                    const product = data.data.product;
                    return {
                        product_id: item.product_id,
                        quantity: item.quantity,
                        name: product.name,
                        price: product.price,
                        image: product.images?.[0]?.image_url
                    };
                }
                return item;
            } catch (e) {
                return item;
            }
        }));
    } else {
        // Logged in user - load from cart API
        try {
            const response = await fetch('/api/cart', {
                headers: {
                    'Authorization': `Bearer ${token}`,
                    'Accept': 'application/json'
                }
            });
            
            if (response.ok) {
                const result = await response.json();
                checkoutItems = Array.isArray(result.data) ? result.data : [];
            }
        } catch (error) {
            console.error('Error loading cart:', error);
        }
    }
    
    if (checkoutItems.length === 0) {
        window.location.href = '/cart';
        return;
    }
    
    renderOrderItems();
    updateSummary();
}

// Render order items
function renderOrderItems() {
    const container = document.getElementById('orderItems');
    
    container.innerHTML = checkoutItems.map(item => `
        <div class="order-item">
            <div class="order-item-image">
                <img src="${item.image || item.product?.image || 'https://placehold.co/60x60/f5f5f5/333?text=No+Image'}" alt="${item.name}">
            </div>
            <div class="order-item-info">
                <div class="order-item-name">${item.name || item.product?.name}</div>
                <div class="order-item-qty">x${item.quantity}</div>
            </div>
            <div class="order-item-price">${formatPrice(item.price * item.quantity)}₫</div>
        </div>
    `).join('');
}

// Update summary
function updateSummary() {
    const subtotal = checkoutItems.reduce((sum, item) => sum + item.price * item.quantity, 0);
    
    let discount = 0;
    if (appliedCoupon) {
        if (appliedCoupon.type === 'percent') {
            discount = subtotal * appliedCoupon.value / 100;
        } else {
            discount = appliedCoupon.value;
        }
        document.getElementById('discountRow').style.display = 'flex';
        document.getElementById('discount').textContent = '-' + formatPrice(discount) + '₫';
    }
    
    // Calculate shipping based on province (simplified)
    shippingFee = subtotal >= 500000 ? 0 : 30000;
    
    const total = subtotal - discount + shippingFee;
    
    document.getElementById('subtotal').textContent = formatPrice(subtotal) + '₫';
    document.getElementById('shipping').textContent = shippingFee === 0 ? 'Miễn phí' : formatPrice(shippingFee) + '₫';
    document.getElementById('total').textContent = formatPrice(total) + '₫';
}

// API URL cho dữ liệu địa chính Việt Nam
const PROVINCES_API = 'https://provinces.open-api.vn/api';

// Load provinces từ API
async function loadProvinces() {
    const select = document.getElementById('province');
    
    try {
        const response = await fetch(`${PROVINCES_API}/p/`);
        const provinces = await response.json();
        
        provinces.forEach(p => {
            select.innerHTML += `<option value="${p.code}">${p.name}</option>`;
        });
    } catch (error) {
        console.error('Error loading provinces:', error);
        // Fallback to static data
        const fallbackProvinces = [
            { code: '1', name: 'Hà Nội' },
            { code: '79', name: 'Hồ Chí Minh' },
            { code: '48', name: 'Đà Nẵng' },
            { code: '92', name: 'Cần Thơ' },
            { code: '31', name: 'Hải Phòng' },
        ];
        fallbackProvinces.forEach(p => {
            select.innerHTML += `<option value="${p.code}">${p.name}</option>`;
        });
    }
}

// Load districts từ API theo tỉnh
async function loadDistricts() {
    const provinceCode = document.getElementById('province').value;
    const select = document.getElementById('district');
    
    select.innerHTML = '<option value="">Đang tải...</option>';
    document.getElementById('ward').innerHTML = '<option value="">Chọn Phường/Xã</option>';
    
    if (!provinceCode) {
        select.innerHTML = '<option value="">Chọn Quận/Huyện</option>';
        return;
    }
    
    try {
        const response = await fetch(`${PROVINCES_API}/p/${provinceCode}?depth=2`);
        const data = await response.json();
        const districts = data.districts || [];
        
        select.innerHTML = '<option value="">Chọn Quận/Huyện</option>';
        districts.forEach(d => {
            select.innerHTML += `<option value="${d.code}">${d.name}</option>`;
        });
    } catch (error) {
        console.error('Error loading districts:', error);
        select.innerHTML = '<option value="">Không thể tải dữ liệu</option>';
    }
}

// Load wards từ API theo quận/huyện
async function loadWards() {
    const districtCode = document.getElementById('district').value;
    const select = document.getElementById('ward');
    
    select.innerHTML = '<option value="">Đang tải...</option>';
    
    if (!districtCode) {
        select.innerHTML = '<option value="">Chọn Phường/Xã</option>';
        return;
    }
    
    try {
        const response = await fetch(`${PROVINCES_API}/d/${districtCode}?depth=2`);
        const data = await response.json();
        const wards = data.wards || [];
        
        select.innerHTML = '<option value="">Chọn Phường/Xã</option>';
        wards.forEach(w => {
            select.innerHTML += `<option value="${w.code}">${w.name}</option>`;
        });
    } catch (error) {
        console.error('Error loading wards:', error);
        select.innerHTML = '<option value="">Không thể tải dữ liệu</option>';
    }
}

// Load user info if logged in
async function loadUserInfo() {
    const token = localStorage.getItem('auth_token');
    
    // Thử load từ API profile nếu có token
    if (token) {
        try {
            const response = await fetch('/api/profile', {
                headers: {
                    'Authorization': `Bearer ${token}`,
                    'Accept': 'application/json'
                }
            });
            
            if (response.ok) {
                const data = await response.json();
                const user = data.data || data.user || data;
                
                if (user.name) document.getElementById('name').value = user.name;
                if (user.email) document.getElementById('email').value = user.email;
                if (user.phone) document.getElementById('phone').value = user.phone;
                if (user.address) document.getElementById('address').value = user.address;
                return;
            }
        } catch (error) {
            console.error('Error loading user profile:', error);
        }
    }
    
    // Fallback to localStorage
    const user = JSON.parse(localStorage.getItem('user') || '{}');
    if (user.name) document.getElementById('name').value = user.name;
    if (user.email) document.getElementById('email').value = user.email;
    if (user.phone) document.getElementById('phone').value = user.phone;
}

// Setup payment methods
function setupPaymentMethods() {
    document.querySelectorAll('.payment-option').forEach(option => {
        option.addEventListener('click', function() {
            document.querySelectorAll('.payment-option').forEach(o => o.classList.remove('active'));
            this.classList.add('active');
            this.querySelector('input').checked = true;
            
            // Show bank info if bank transfer selected
            const bankInfo = document.getElementById('bankInfo');
            if (this.querySelector('input').value === 'bank_transfer') {
                bankInfo.style.display = 'block';
            } else {
                bankInfo.style.display = 'none';
            }
        });
    });
}

// Place order
async function placeOrder() {
    // Validate terms
    if (!document.getElementById('agreeTerms').checked) {
        alert('Vui lòng đồng ý với điều khoản dịch vụ');
        return;
    }
    
    // Validate form
    const form = document.getElementById('shippingForm');
    if (!form.checkValidity()) {
        form.reportValidity();
        return;
    }
    
    const btn = document.getElementById('placeOrderBtn');
    btn.disabled = true;
    btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Đang xử lý...';
    
    // Collect shipping address
    const province = document.getElementById('province');
    const district = document.getElementById('district');
    const ward = document.getElementById('ward');
    
    const shippingAddress = {
        name: document.getElementById('name').value,
        phone: document.getElementById('phone').value,
        email: document.getElementById('email').value,
        province: province.options[province.selectedIndex]?.text || '',
        district: district.options[district.selectedIndex]?.text || '',
        ward: ward.options[ward.selectedIndex]?.text || '',
        address: document.getElementById('address').value,
        full_address: `${document.getElementById('address').value}, ${ward.options[ward.selectedIndex]?.text || ''}, ${district.options[district.selectedIndex]?.text || ''}, ${province.options[province.selectedIndex]?.text || ''}`
    };
    
    const orderData = {
        items: checkoutItems.map(item => ({
            product_id: item.product_id || item.id,
            quantity: item.quantity,
            price: item.price,
            variant: item.variant
        })),
        shipping_address: shippingAddress,
        payment_method: document.querySelector('input[name="payment"]:checked').value,
        note: document.getElementById('note').value,
        coupon_code: appliedCoupon?.code
    };
    
    console.log('Order data:', orderData);
    console.log('Checkout items:', checkoutItems);
    
    try {
        const token = localStorage.getItem('auth_token');
        const headers = {
            'Content-Type': 'application/json',
            'Accept': 'application/json'
        };
        
        if (token) {
            headers['Authorization'] = `Bearer ${token}`;
        }
        
        const response = await fetch('/api/orders', {
            method: 'POST',
            headers,
            body: JSON.stringify(orderData)
        });
        
        const data = await response.json();
        
        if (response.ok) {
            // Clear cart and checkout data
            sessionStorage.removeItem('checkoutItems');
            sessionStorage.removeItem('appliedCoupon');
            localStorage.setItem('cart', '[]');
            
            // Redirect to success page
            window.location.href = `/orders/${data.data?.id || data.id}/success`;
        } else {
            alert(data.message || 'Đặt hàng không thành công. Vui lòng thử lại.');
            btn.disabled = false;
            btn.innerHTML = '<i class="fas fa-lock"></i> Đặt hàng';
        }
    } catch (error) {
        console.error('Error placing order:', error);
        alert('Đã có lỗi xảy ra. Vui lòng thử lại.');
        btn.disabled = false;
        btn.innerHTML = '<i class="fas fa-lock"></i> Đặt hàng';
    }
}
</script>
@endpush

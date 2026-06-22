@extends('layouts.app')

@section('title', 'Sản phẩm - XanhStore')

@section('content')
<div class="products-page">
    <div class="container">
        <div class="page-header">
            <nav class="breadcrumb">
                <a href="/">Trang chủ</a>
                <i class="fas fa-chevron-right"></i>
                <span>Sản phẩm</span>
            </nav>
            <h1>Điện thoại di động</h1>
        </div>

        <div class="products-layout">
            <!-- Sidebar Filters -->
            <aside class="products-sidebar">
                <div class="filter-section">
                    <h3>
                        <i class="fas fa-filter"></i>
                        Bộ lọc
                    </h3>
                    
                    <!-- Categories -->
                    <div class="filter-group">
                        <h4>Danh mục</h4>
                        <div class="filter-options" id="categoryFilters">
                            <!-- Loaded via JS - uses category_id param -->
                        </div>
                    </div>
                    
                    <!-- Brands -->
                    <div class="filter-group">
                        <h4>Thương hiệu</h4>
                        <div class="filter-options" id="brandFilters">
                            <label class="checkbox-wrapper">
                                <input type="checkbox" name="brand" value="apple">
                                <span class="checkmark"></span>
                                Apple
                            </label>
                            <label class="checkbox-wrapper">
                                <input type="checkbox" name="brand" value="samsung">
                                <span class="checkmark"></span>
                                Samsung
                            </label>
                            <label class="checkbox-wrapper">
                                <input type="checkbox" name="brand" value="xiaomi">
                                <span class="checkmark"></span>
                                Xiaomi
                            </label>
                            <label class="checkbox-wrapper">
                                <input type="checkbox" name="brand" value="oppo">
                                <span class="checkmark"></span>
                                OPPO
                            </label>
                            <label class="checkbox-wrapper">
                                <input type="checkbox" name="brand" value="vivo">
                                <span class="checkmark"></span>
                                Vivo
                            </label>
                        </div>
                    </div>
                    
                    <!-- Price Range -->
                    <div class="filter-group">
                        <h4>Mức giá</h4>
                        <div class="filter-options" id="priceRangeFilters">
                            <div class="loading-text">Đang tải...</div>
                        </div>
                    </div>
                    
                    <!-- RAM -->
                    <div class="filter-group">
                        <h4>RAM</h4>
                        <div class="filter-options" id="ramFilters">
                            <div class="loading-text">Đang tải...</div>
                        </div>
                    </div>
                    
                    <!-- Storage -->
                    <div class="filter-group">
                        <h4>Bộ nhớ trong</h4>
                        <div class="filter-options" id="storageFilters">
                            <div class="loading-text">Đang tải...</div>
                        </div>
                    </div>
                    
                    <button class="btn btn-primary btn-full" onclick="applyFilters()">
                        <i class="fas fa-check"></i> Áp dụng
                    </button>
                    <button class="btn btn-outline btn-full" onclick="clearFilters()">
                        <i class="fas fa-times"></i> Xóa bộ lọc
                    </button>
                </div>
            </aside>

            <!-- Products Grid -->
            <main class="products-main">
                <!-- Toolbar -->
                <div class="products-toolbar">
                    <div class="products-count">
                        Tìm thấy <span id="totalProducts">0</span> sản phẩm
                    </div>
                    <div class="products-sort">
                        <label>Sắp xếp:</label>
                        <select id="sortSelect" onchange="loadProducts(1)">
                            <option value="newest">Mới nhất</option>
                            <option value="price_asc">Giá thấp đến cao</option>
                            <option value="price_desc">Giá cao đến thấp</option>
                            <option value="name_asc">A → Z</option>
                            <option value="bestselling">Bán chạy nhất</option>
                        </select>
                    </div>
                    <div class="products-view">
                        <button class="btn btn-icon active" onclick="setView('grid')">
                            <i class="fas fa-th"></i>
                        </button>
                        <button class="btn btn-icon" onclick="setView('list')">
                            <i class="fas fa-list"></i>
                        </button>
                    </div>
                </div>

                <!-- Products Grid -->
                <div class="products-grid" id="productsContainer">
                    <!-- Products loaded via JS -->
                    <div class="product-card skeleton"></div>
                    <div class="product-card skeleton"></div>
                    <div class="product-card skeleton"></div>
                    <div class="product-card skeleton"></div>
                    <div class="product-card skeleton"></div>
                    <div class="product-card skeleton"></div>
                    <div class="product-card skeleton"></div>
                    <div class="product-card skeleton"></div>
                </div>

                <!-- Pagination -->
                <div class="pagination" id="pagination">
                    <!-- Pagination loaded via JS -->
                </div>
            </main>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
.products-page {
    padding: 24px 0 60px;
    background: #f9fafb;
    min-height: 100vh;
}

.page-header {
    margin-bottom: 24px;
}

.breadcrumb {
    display: flex;
    align-items: center;
    gap: 8px;
    font-size: 14px;
    color: var(--gray-600);
    margin-bottom: 12px;
}

.breadcrumb a {
    color: var(--gray-600);
}

.breadcrumb a:hover {
    color: var(--primary);
}

.breadcrumb i {
    font-size: 10px;
}

.page-header h1 {
    font-size: 28px;
    font-weight: 700;
    color: var(--gray-900);
}

.products-layout {
    display: grid;
    grid-template-columns: 280px 1fr;
    gap: 24px;
}

/* Sidebar */
.products-sidebar {
    position: sticky;
    top: 100px;
    height: fit-content;
}

.filter-section {
    background: white;
    border-radius: 12px;
    padding: 20px;
    box-shadow: var(--shadow-sm);
}

.filter-section h3 {
    font-size: 16px;
    font-weight: 600;
    color: var(--gray-900);
    display: flex;
    align-items: center;
    gap: 8px;
    margin-bottom: 20px;
    padding-bottom: 12px;
    border-bottom: 1px solid #e5e7eb;
}

.filter-group {
    margin-bottom: 20px;
    padding-bottom: 16px;
    border-bottom: 1px solid #e5e7eb;
}

.filter-group:last-of-type {
    border-bottom: none;
}

.filter-group h4 {
    font-size: 14px;
    font-weight: 600;
    color: var(--gray-800);
    margin-bottom: 12px;
}

.filter-options {
    display: flex;
    flex-direction: column;
    gap: 8px;
}

/* Checkbox Wrapper */
.checkbox-wrapper {
    display: flex;
    align-items: center;
    gap: 10px;
    cursor: pointer;
    font-size: 14px;
    color: var(--gray-700);
    padding: 6px 0;
    transition: color 0.2s;
}

.checkbox-wrapper:hover {
    color: var(--primary);
}

.checkbox-wrapper input[type="checkbox"],
.checkbox-wrapper input[type="radio"] {
    width: 18px;
    height: 18px;
    accent-color: var(--primary);
    cursor: pointer;
}

.checkbox-wrapper .checkmark {
    display: none;
}

.filter-section .btn {
    margin-top: 8px;
}

/* Products Main */
.products-toolbar {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 16px;
    background: white;
    padding: 16px 20px;
    border-radius: 12px;
    margin-bottom: 20px;
    box-shadow: var(--shadow-sm);
}

.products-count {
    font-size: 14px;
    color: var(--gray-600);
}

.products-count span {
    font-weight: 600;
    color: var(--primary);
}

.products-sort {
    display: flex;
    align-items: center;
    gap: 8px;
}

.products-sort label {
    font-size: 14px;
    color: var(--gray-600);
}

.products-sort select {
    padding: 8px 12px;
    border: 1px solid #e5e7eb;
    border-radius: 8px;
    font-size: 14px;
    color: var(--gray-900);
    background: white;
    cursor: pointer;
}

.products-view {
    display: flex;
    gap: 4px;
}

.products-view .btn-icon {
    background: #f3f4f6;
    color: var(--gray-600);
    border: none;
}

.products-view .btn-icon.active {
    background: var(--primary);
    color: white;
}

/* Products Grid */
.products-grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 20px;
}

.products-list .products-grid {
    grid-template-columns: 1fr;
}

.products-list .product-card {
    display: grid;
    grid-template-columns: 200px 1fr auto;
    gap: 24px;
    align-items: center;
}

.products-list .product-image {
    height: 180px;
}

.products-list .product-actions {
    flex-direction: column;
    padding: 0 20px 0 0;
}

/* Pagination */
.pagination {
    display: flex;
    justify-content: center;
    align-items: center;
    gap: 8px;
    margin-top: 32px;
}

.pagination button {
    min-width: 40px;
    height: 40px;
    border: 1px solid #e5e7eb;
    border-radius: 8px;
    background: white;
    color: var(--gray-700);
    font-weight: 500;
    cursor: pointer;
    transition: all 0.2s;
}

.pagination button:hover {
    border-color: var(--primary);
    color: var(--primary);
}

.pagination button.active {
    background: var(--primary);
    border-color: var(--primary);
    color: white;
}

.pagination button:disabled {
    opacity: 0.5;
    cursor: not-allowed;
}

/* Skeleton */
.skeleton {
    background: linear-gradient(90deg, #f0f0f0 25%, #e0e0e0 50%, #f0f0f0 75%);
    background-size: 200% 100%;
    animation: skeleton-loading 1.5s infinite;
    min-height: 350px;
    border-radius: 12px;
}

@keyframes skeleton-loading {
    0% { background-position: 200% 0; }
    100% { background-position: -200% 0; }
}

/* Responsive */
@media (max-width: 1024px) {
    .products-grid {
        grid-template-columns: repeat(3, 1fr);
    }
}

@media (max-width: 768px) {
    .products-layout {
        grid-template-columns: 1fr;
    }
    
    .products-sidebar {
        position: static;
    }
    
    .products-grid {
        grid-template-columns: repeat(2, 1fr);
    }
    
    .products-toolbar {
        flex-wrap: wrap;
    }
}
</style>
@endpush

@push('scripts')
<script>
let currentPage = 1;
let currentFilters = {};

// Helper functions
function formatPrice(price) {
    return new Intl.NumberFormat('vi-VN').format(price);
}

function generateStars(rating) {
    let stars = '';
    for (let i = 1; i <= 5; i++) {
        if (i <= rating) {
            stars += '<i class="fas fa-star"></i>';
        } else if (i - 0.5 <= rating) {
            stars += '<i class="fas fa-star-half-alt"></i>';
        } else {
            stars += '<i class="far fa-star"></i>';
        }
    }
    return stars;
}

// Load products
async function loadProducts(page = 1) {
    currentPage = page;
    const container = document.getElementById('productsContainer');
    
    // Show skeleton
    container.innerHTML = Array(8).fill('<div class="product-card skeleton"></div>').join('');
    
    // Build query string
    const params = new URLSearchParams();
    params.append('page', page);
    params.append('sort_by', document.getElementById('sortSelect').value);
    
    // Add URL params
    const urlParams = new URLSearchParams(window.location.search);
    for (const [key, value] of urlParams) {
        if (!params.has(key)) params.append(key, value);
    }
    // If user applied explicit UI filters, drop free-text search params
    if (Object.keys(currentFilters).length > 0) {
        params.delete('search');
        params.delete('keyword');
    }
    
    // Add filters
    Object.keys(currentFilters).forEach(key => {
        const value = currentFilters[key];
        if (value) {
            if (Array.isArray(value)) {
                // Gửi dạng array: ram[]=8GB&ram[]=12GB
                value.forEach(v => params.append(`${key}[]`, v));
            } else {
                params.append(key, value);
            }
        }
    });
    
    try {
        const response = await fetch(`/api/products?${params.toString()}`);
        const result = await response.json();
        
        // API returns { success: true, data: { data: [...], total: X } }
        const data = result.data || result;
        const products = data.data || [];
        
        document.getElementById('totalProducts').textContent = data.total || products.length;
        
        if (products.length === 0) {
            container.innerHTML = `
                <div class="empty-state" style="grid-column: 1/-1; text-align: center; padding: 60px;">
                    <i class="fas fa-search" style="font-size: 48px; color: #e5e7eb; margin-bottom: 16px;"></i>
                    <h3 style="color: var(--gray-700); margin-bottom: 8px;">Không tìm thấy sản phẩm</h3>
                    <p style="color: var(--gray-500);">Thử thay đổi bộ lọc hoặc từ khóa tìm kiếm</p>
                </div>
            `;
        } else {
            container.innerHTML = products.map(product => createProductCard(product)).join('');
        }
        
        // Update pagination
        if (data.last_page) {
            renderPagination(data.current_page, data.last_page);
        }
    } catch (error) {
        console.error('Error loading products:', error);
        container.innerHTML = `
            <div class="empty-state" style="grid-column: 1/-1; text-align: center; padding: 60px;">
                <i class="fas fa-exclamation-circle" style="font-size: 48px; color: #ef4444; margin-bottom: 16px;"></i>
                <h3 style="color: var(--gray-700);">Đã có lỗi xảy ra</h3>
            </div>
        `;
    }
}

// Create product card HTML
function createProductCard(product) {
    const hasDiscount = product.sale_price && product.sale_price < product.price;
    const imageUrl = getProductImage(product);
    
    return `
        <div class="product-card">
            <div class="product-badges">
                <span class="badge badge-installment"><i class="fas fa-bolt"></i> Trả góp 0%</span>
                <span class="badge badge-gift"><i class="fas fa-gift"></i> Quà 2Tr</span>
            </div>
            <a href="/products/${product.id}" class="product-image">
                    <img src="${appendImageVersion(imageUrl, product.id)}" alt="${product.name}">
            </a>
            <div class="product-info">
                <span class="product-category">${product.category?.name || 'Điện thoại'}</span>
                <h3 class="product-name">
                    <a href="/products/${product.id}">${product.name}</a>
                </h3>
                <div class="product-rating">
                    ${generateStars(product.average_rating || 4.5)}
                    <span class="rating-count">(${product.reviews_count || Math.floor(Math.random() * 1000) + 200})</span>
                </div>
                <div class="product-specs">
                    ${getProductSpecTags(product)}
                </div>
                <div class="product-price">
                    ${product.sale_price && Number(product.sale_price) < Number(product.price) ? `
                        <span class="price-old">${formatPrice(product.price)}đ</span>
                        <span class="price-current">${formatPrice(product.sale_price)}đ</span>
                    ` : `
                        <span class="price-current">${formatPrice(product.price)}đ</span>
                    `}
                </div>
            </div>
            <div class="product-actions">
                <button class="btn btn-primary" onclick="handleAddToCart(${product.id}); event.stopPropagation();">
                    <i class="fas fa-shopping-cart"></i> Mua ngay
                </button>
                <button class="btn-favorite" onclick="handleToggleFavorite(${product.id}, this); event.stopPropagation();">
                    <i class="far fa-heart"></i>
                </button>
            </div>
        </div>
    `;
}

// Return spec tag HTML based on product category
function getProductSpecTags(product) {
    const catId = product.category_id;
    const catName = (product.category?.name || '').toLowerCase();
    const name = (product.name || '').toLowerCase();
    const phoneKeywords = ['iphone', 'samsung', 'xiaomi', 'oppo', 'vivo'];
    const isPhone = catId <= 5 || phoneKeywords.some(k => catName.includes(k));

    if (isPhone) {
        const tags = [];
        if (product.ram) tags.push(`<span class="spec-tag"><i class="fas fa-memory" style="font-size:10px"></i> ${product.ram}</span>`);
        if (product.storage) tags.push(`<span class="spec-tag"><i class="fas fa-hdd" style="font-size:10px"></i> ${product.storage}</span>`);
        if (product.battery) tags.push(`<span class="spec-tag"><i class="fas fa-battery-three-quarters" style="font-size:10px"></i> ${product.battery}</span>`);
        return tags.length ? tags.join('') : '<span class="spec-tag">Điện thoại</span>';
    }

    if (catName.includes('tai nghe')) {
        const type = name.includes('max') ? 'Over-ear' : 'TWS';
        const anc = name.includes('airpods') || name.includes('bose') || name.includes('soundpeats') ? '<span class="spec-tag">ANC</span>' : '';
        return `<span class="spec-tag">${type}</span><span class="spec-tag">Bluetooth 5.3</span>${anc}`;
    }

    if (catName.includes('ốp lưng') || catName.includes('op lung')) {
        const isMagSafe = name.includes('magsafe') || (product.brand || '').toLowerCase() === 'apple';
        return `<span class="spec-tag">Chính hãng</span>${isMagSafe ? '<span class="spec-tag">MagSafe</span>' : '<span class="spec-tag">Chống sốc</span>'}`;
    }

    if (catName.includes('cáp') || catName.includes('cap') || catName.includes('sạc')) {
        const power = name.includes('250w') ? '250W' : name.includes('100w') ? '100W' : name.includes('65w') ? '65W' : name.includes('15w') ? '15W' : 'Sạc nhanh';
        return `<span class="spec-tag">${power}</span><span class="spec-tag">GaN</span>`;
    }

    if (catName.includes('pin') || catName.includes('sạc dự phòng')) {
        const capacity = product.battery || '';
        return `${capacity ? `<span class="spec-tag">${capacity}</span>` : ''}<span class="spec-tag">Sạc nhanh</span>`;
    }

    if (catName.includes('miếng dán') || catName.includes('kính')) {
        const isPrivacy = name.includes('privacy') || name.includes('chống nhìn');
        return `<span class="spec-tag">9H</span>${isPrivacy ? '<span class="spec-tag">Chống nhìn trộm</span>' : '<span class="spec-tag">Chống trầy</span>'}`;
    }

    if (catName.includes('giá đỡ')) {
        return `<span class="spec-tag">Đa năng</span><span class="spec-tag">Chính hãng</span>`;
    }

    // Fallback
    return `<span class="spec-tag">${product.category?.name || 'Phụ kiện'}</span>`;
}

function getProductImage(product) {
    const images = product.images || [];

    if (images.length > 0) {
        const firstImage = images[0];
        return typeof firstImage === 'object' ? firstImage.image_url : firstImage;
    }

    const name = (product.name || '').toLowerCase();

    if (name.includes('iphone')) {
        return 'https://images.unsplash.com/photo-1592750475338-74b7b21085ab?w=300&h=300&fit=crop';
    }

    if (name.includes('samsung')) {
        return 'https://images.unsplash.com/photo-1610945415295-d9bbf067e59c?w=300&h=300&fit=crop';
    }

    if (name.includes('xiaomi')) {
        return 'https://images.unsplash.com/photo-1598327106026-d9521da673d1?w=300&h=300&fit=crop';
    }

    if (name.includes('oppo')) {
        return 'https://images.unsplash.com/photo-1598327105666-5b89351aff97?w=300&h=300&fit=crop';
    }

    if (name.includes('vivo')) {
        return 'https://images.unsplash.com/photo-1605236453806-6ff36851218e?w=300&h=300&fit=crop';
    }

    return 'https://placehold.co/300x300/f3f4f6/111827?text=XanhStore';
}

function appendImageVersion(url, version) {
    if (!url) return url;
    return url.includes('?') ? `${url}&v=${version}` : `${url}?v=${version}`;
}

// Render pagination
function renderPagination(current, total) {
    const container = document.getElementById('pagination');
    let html = '';
    
    // Previous button
    html += `<button ${current === 1 ? 'disabled' : ''} onclick="loadProducts(${current - 1})">
        <i class="fas fa-chevron-left"></i>
    </button>`;
    
    // Page numbers
    for (let i = 1; i <= total; i++) {
        if (i === 1 || i === total || (i >= current - 2 && i <= current + 2)) {
            html += `<button class="${i === current ? 'active' : ''}" onclick="loadProducts(${i})">${i}</button>`;
        } else if (i === current - 3 || i === current + 3) {
            html += `<span style="padding: 0 8px;">...</span>`;
        }
    }
    
    // Next button
    html += `<button ${current === total ? 'disabled' : ''} onclick="loadProducts(${current + 1})">
        <i class="fas fa-chevron-right"></i>
    </button>`;
    
    container.innerHTML = html;
}

// Apply filters
function applyFilters() {
    currentFilters = {};
    
    // Collect filter values
    document.querySelectorAll('.filter-options input:checked').forEach(input => {
        const name = input.name;
        const value = input.value;
        
        if (input.type === 'checkbox') {
            // Cho RAM và Storage - gửi dạng mảng
            if (!currentFilters[name]) currentFilters[name] = [];
            currentFilters[name].push(value);
        } else if (input.type === 'radio') {
            // Cho price_range - gửi giá trị đơn
            currentFilters[name] = value;
        }
    });
    
    loadProducts(1);
}

// Clear filters
function clearFilters() {
    document.querySelectorAll('.filter-options input').forEach(input => {
        input.checked = false;
    });
    currentFilters = {};
    loadProducts(1);
}

// Set view mode
function setView(mode) {
    const container = document.querySelector('.products-main');
    const buttons = document.querySelectorAll('.products-view .btn-icon');
    
    buttons.forEach(btn => btn.classList.remove('active'));
    event.target.closest('.btn-icon').classList.add('active');
    
    if (mode === 'list') {
        container.classList.add('products-list');
    } else {
        container.classList.remove('products-list');
    }
}

// Load categories for filter
async function loadCategoryFilters() {
    try {
        const response = await fetch('/api/categories');
        const result = await response.json();
        const categories = result.data?.data || result.data || [];

        // Dedupe categories by name (case-insensitive) to avoid duplicate entries
        const seen = new Set();
        const unique = [];
        for (const cat of categories) {
            const key = (cat.name || '').trim().toLowerCase();
            if (!seen.has(key)) {
                seen.add(key);
                unique.push(cat);
            }
        }

        const container = document.getElementById('categoryFilters');
        container.innerHTML = unique.map(cat => `
            <label class="checkbox-wrapper">
                <input type="checkbox" name="category_id" value="${cat.id}">
                <span class="checkmark"></span>
                ${cat.name}
            </label>
        `).join('');
    } catch (error) {
        console.error('Error loading categories:', error);
    }
}

// Handle add to cart
function handleAddToCart(productId) {
    const token = localStorage.getItem('auth_token');
    
    if (!token) {
        // Lưu sản phẩm vào giỏ hàng tạm (localStorage)
        let cart = JSON.parse(localStorage.getItem('guest_cart') || '[]');
        const existingItem = cart.find(item => item.product_id == productId);
        
        if (existingItem) {
            existingItem.quantity++;
        } else {
            cart.push({ product_id: productId, quantity: 1 });
        }
        
        localStorage.setItem('guest_cart', JSON.stringify(cart));
        showNotification('Đã thêm vào giỏ hàng!', 'success');
        updateCartBadge();
        return;
    }
    
    // Người dùng đã đăng nhập - gọi API
    fetch('/api/cart/add', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'Authorization': `Bearer ${token}`,
            'Accept': 'application/json'
        },
        body: JSON.stringify({
            product_id: productId,
            quantity: 1
        })
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            showNotification('Đã thêm vào giỏ hàng!', 'success');
            updateCartBadge();
        } else {
            showNotification(data.message || 'Có lỗi xảy ra', 'error');
        }
    })
    .catch(err => {
        console.error('Cart error:', err);
        showNotification('Có lỗi xảy ra', 'error');
    });
}

// Handle toggle favorite
function handleToggleFavorite(productId, button) {
    const token = localStorage.getItem('auth_token');
    
    if (!token) {
        showNotification('Vui lòng đăng nhập để thêm yêu thích!', 'warning');
        return;
    }
    
    const icon = button.querySelector('i');
    const isFavorited = icon.classList.contains('fas');
    
    fetch(`/api/favorites/${productId}`, {
        method: isFavorited ? 'DELETE' : 'POST',
        headers: {
            'Content-Type': 'application/json',
            'Authorization': `Bearer ${token}`,
            'Accept': 'application/json'
        }
    })
    .then(res => res.json())
    .then(data => {
        if (data.success || res.ok) {
            icon.classList.toggle('far');
            icon.classList.toggle('fas');
            icon.style.color = isFavorited ? '' : '#e74c3c';
            showNotification(isFavorited ? 'Đã xóa khỏi yêu thích' : 'Đã thêm vào yêu thích!', 'success');
        }
    })
    .catch(err => {
        console.error('Favorite error:', err);
    });
}

// Show notification
function showNotification(message, type = 'info') {
    // Remove existing notification
    const existing = document.querySelector('.notification-toast');
    if (existing) existing.remove();
    
    const toast = document.createElement('div');
    toast.className = `notification-toast notification-${type}`;
    toast.innerHTML = `
        <i class="fas fa-${type === 'success' ? 'check-circle' : type === 'error' ? 'exclamation-circle' : type === 'warning' ? 'exclamation-triangle' : 'info-circle'}"></i>
        <span>${message}</span>
    `;
    toast.style.cssText = `
        position: fixed;
        top: 100px;
        right: 20px;
        background: ${type === 'success' ? '#27ae60' : type === 'error' ? '#e74c3c' : type === 'warning' ? '#f39c12' : '#3498db'};
        color: white;
        padding: 12px 24px;
        border-radius: 8px;
        display: flex;
        align-items: center;
        gap: 10px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.2);
        z-index: 10000;
        animation: slideIn 0.3s ease;
    `;
    
    document.body.appendChild(toast);
    
    setTimeout(() => {
        toast.style.animation = 'slideOut 0.3s ease';
        setTimeout(() => toast.remove(), 300);
    }, 3000);
}

// Update cart badge - gọi hàm global từ layout
function updateCartBadge() {
    if (typeof window.updateHeaderBadges === 'function') {
        window.updateHeaderBadges();
        return;
    }
    
    const token = localStorage.getItem('auth_token');
    const badge = document.getElementById('cartCount');
    
    if (!badge) return;
    
    if (!token) {
        const cart = JSON.parse(localStorage.getItem('guest_cart') || '[]');
        const count = cart.reduce((sum, item) => sum + item.quantity, 0);
        badge.textContent = count;
        badge.style.display = count > 0 ? 'flex' : 'none';
    } else {
        fetch('/api/cart', {
            headers: {
                'Authorization': `Bearer ${token}`,
                'Accept': 'application/json'
            }
        })
        .then(res => res.json())
        .then(data => {
            const items = Array.isArray(data.data) ? data.data : [];
            const count = items.reduce((sum, item) => sum + (item.quantity || 1), 0);
            badge.textContent = count;
            badge.style.display = count > 0 ? 'flex' : 'none';
        });
    }
}

// Load filter options từ API
async function loadFilterOptions() {
    try {
        const response = await fetch('/api/products/filter-options');
        const result = await response.json();
        
        if (result.success) {
            const data = result.data;
            
            // Render Price Range filters
            const priceContainer = document.getElementById('priceRangeFilters');
            priceContainer.innerHTML = data.price_ranges.map(item => `
                <label class="checkbox-wrapper">
                    <input type="radio" name="price_range" value="${item.value}">
                    <span class="checkmark"></span>
                    ${item.label}
                </label>
            `).join('');
            
            // Render RAM filters
            const ramContainer = document.getElementById('ramFilters');
            if (data.rams.length > 0) {
                ramContainer.innerHTML = data.rams.map(ram => `
                    <label class="checkbox-wrapper">
                        <input type="checkbox" name="ram" value="${ram}">
                        <span class="checkmark"></span>
                        ${ram}
                    </label>
                `).join('');
            } else {
                ramContainer.innerHTML = '<span class="text-muted">Không có dữ liệu</span>';
            }
            
            // Render Storage filters
            const storageContainer = document.getElementById('storageFilters');
            if (data.storages.length > 0) {
                storageContainer.innerHTML = data.storages.map(storage => `
                    <label class="checkbox-wrapper">
                        <input type="checkbox" name="storage" value="${storage}">
                        <span class="checkmark"></span>
                        ${storage}
                    </label>
                `).join('');
            } else {
                storageContainer.innerHTML = '<span class="text-muted">Không có dữ liệu</span>';
            }
        }
    } catch (error) {
        console.error('Error loading filter options:', error);
        document.getElementById('priceRangeFilters').innerHTML = '<span class="text-muted">Lỗi tải dữ liệu</span>';
        document.getElementById('ramFilters').innerHTML = '<span class="text-muted">Lỗi tải dữ liệu</span>';
        document.getElementById('storageFilters').innerHTML = '<span class="text-muted">Lỗi tải dữ liệu</span>';
    }
}

// Initialize
document.addEventListener('DOMContentLoaded', () => {
    loadFilterOptions();
    loadProducts();
    loadCategoryFilters();
    updateCartBadge();
});
</script>
@endpush

@extends('layouts.app')

@section('title', 'Chi tiết sản phẩm - XanhStore')

@section('content')
<div class="product-detail-page">
    <div class="container">
        <!-- Breadcrumb -->
        <nav class="breadcrumb">
            <a href="/">Trang chủ</a>
            <i class="fas fa-chevron-right"></i>
            <a href="/products">Sản phẩm</a>
            <i class="fas fa-chevron-right"></i>
            <span id="productBreadcrumb">Đang tải...</span>
        </nav>

        <!-- Product Main -->
        <div class="product-main" id="productMain">
            <!-- Loading state -->
            <div class="product-gallery skeleton" style="min-height: 500px;"></div>
            <div class="product-info-section skeleton" style="min-height: 500px;"></div>
        </div>

        <!-- Product Tabs -->
        <div class="product-tabs">
            <div class="tabs-header">
                <button class="tab-btn active" onclick="switchTab('description')">
                    <i class="fas fa-file-alt"></i> Mô tả sản phẩm
                </button>
                <button class="tab-btn" onclick="switchTab('specs')">
                    <i class="fas fa-cogs"></i> Thông số kỹ thuật
                </button>
                <button class="tab-btn" onclick="switchTab('reviews')">
                    <i class="fas fa-star"></i> Đánh giá (<span id="reviewCount">0</span>)
                </button>
            </div>
            
            <div class="tabs-content">
                <!-- Description Tab -->
                <div class="tab-pane active" id="tab-description">
                    <div id="productDescription">
                        <p>Đang tải mô tả sản phẩm...</p>
                    </div>
                </div>
                
                <!-- Specs Tab -->
                <div class="tab-pane" id="tab-specs">
                    <table class="specs-table" id="specsTable">
                        <tr><td colspan="2">Đang tải thông số...</td></tr>
                    </table>
                </div>
                
                <!-- Reviews Tab -->
                <div class="tab-pane" id="tab-reviews">
                    <div class="reviews-summary" id="reviewsSummary">
                        <!-- Reviews summary loaded via JS -->
                    </div>
                    <div class="reviews-list" id="reviewsList">
                        <!-- Reviews loaded via JS -->
                    </div>
                    
                    <!-- Write Review Form -->
                    <div class="write-review" id="writeReviewSection" style="display: none;">
                        <h3>Viết đánh giá của bạn</h3>
                        <div id="reviewAccessNotice" class="review-access-notice" style="display: none;"></div>
                        <form id="reviewForm">
                            <div class="form-group">
                                <label>Đánh giá</label>
                                <div class="rating-input" id="ratingInput">
                                    <i class="far fa-star" data-rating="1"></i>
                                    <i class="far fa-star" data-rating="2"></i>
                                    <i class="far fa-star" data-rating="3"></i>
                                    <i class="far fa-star" data-rating="4"></i>
                                    <i class="far fa-star" data-rating="5"></i>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="reviewComment">Nhận xét</label>
                                <textarea id="reviewComment" rows="4" placeholder="Chia sẻ trải nghiệm của bạn về sản phẩm này..."></textarea>
                            </div>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-paper-plane"></i> Gửi đánh giá
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Related Products -->
        <section class="related-products">
            <h2><i class="fas fa-th-large"></i> Sản phẩm liên quan</h2>
            <div class="products-grid" id="relatedProducts">
                <div class="product-card skeleton"></div>
                <div class="product-card skeleton"></div>
                <div class="product-card skeleton"></div>
                <div class="product-card skeleton"></div>
            </div>
            <div id="comboActions" style="margin-top:16px; display:flex; gap:12px; align-items:center;">
                <button id="addComboBtn" class="btn btn-primary" onclick="addComboToCart()">Thêm mua kèm vào giỏ (0)</button>
                <button id="clearComboBtn" class="btn" onclick="clearComboSelection()" style="background:#f3f4f6;">Bỏ chọn</button>
            </div>
        </section>
    </div>
</div>
@endsection

@push('styles')
<style>
.product-detail-page {
    padding: 24px 0 60px;
    background: #f9fafb;
}

.breadcrumb {
    display: flex;
    align-items: center;
    gap: 8px;
    font-size: 14px;
    color: var(--gray-600);
    margin-bottom: 24px;
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

/* Product Main */
.product-main {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 40px;
    background: white;
    border-radius: 16px;
    padding: 32px;
    box-shadow: var(--shadow-sm);
    margin-bottom: 32px;
}

/* Product Gallery */
.product-gallery {
    display: flex;
    flex-direction: column;
    gap: 16px;
}

.main-image {
    width: 100%;
    aspect-ratio: 1;
    border-radius: 12px;
    overflow: hidden;
    background: #f9fafb;
    display: flex;
    align-items: center;
    justify-content: center;
}

.main-image img {
    max-width: 100%;
    max-height: 100%;
    object-fit: contain;
}

.thumbnail-list {
    display: flex;
    gap: 12px;
    overflow-x: auto;
    padding: 4px;
}

.thumbnail {
    width: 80px;
    height: 80px;
    border-radius: 8px;
    border: 2px solid transparent;
    overflow: hidden;
    cursor: pointer;
    flex-shrink: 0;
    background: #f9fafb;
}

.thumbnail.active,
.thumbnail:hover {
    border-color: var(--primary);
}

.thumbnail img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

/* Product Info */
.product-info-section h1 {
    font-size: 28px;
    font-weight: 700;
    color: var(--gray-900);
    margin-bottom: 16px;
    line-height: 1.3;
}

.product-meta {
    display: flex;
    align-items: center;
    gap: 24px;
    margin-bottom: 20px;
    font-size: 14px;
    color: var(--gray-600);
}

.product-rating {
    display: flex;
    align-items: center;
    gap: 8px;
}

.product-rating i {
    color: #fbbf24;
}

.product-sku {
    color: var(--gray-500);
}

.product-price-section {
    background: #fef2f2;
    border-radius: 12px;
    padding: 20px;
    margin-bottom: 24px;
}

.price-current {
    font-size: 32px;
    font-weight: 700;
    color: var(--primary);
}

.price-old {
    font-size: 18px;
    color: var(--gray-500);
    text-decoration: line-through;
    margin-left: 12px;
}

.price-discount {
    display: inline-block;
    background: var(--primary);
    color: white;
    padding: 4px 12px;
    border-radius: 20px;
    font-size: 14px;
    font-weight: 600;
    margin-left: 12px;
}

/* Variants */
.product-variants {
    margin-bottom: 24px;
}

.variant-group {
    margin-bottom: 16px;
}

.variant-label {
    font-size: 14px;
    font-weight: 600;
    color: var(--gray-700);
    margin-bottom: 8px;
}

.variant-options {
    display: flex;
    gap: 8px;
    flex-wrap: wrap;
}

.variant-btn {
    padding: 10px 20px;
    border: 2px solid #e5e7eb;
    border-radius: 8px;
    background: white;
    font-size: 14px;
    font-weight: 500;
    color: var(--gray-700);
    cursor: pointer;
    transition: all 0.2s;
}

.variant-btn:hover {
    border-color: var(--primary);
}

.variant-btn.active {
    border-color: var(--primary);
    background: #fef2f2;
    color: var(--primary);
}

.color-btn {
    width: 40px;
    height: 40px;
    padding: 0;
    border-radius: 50%;
}

/* Quantity */
.quantity-section {
    display: flex;
    align-items: center;
    gap: 16px;
    margin-bottom: 24px;
}

.quantity-label {
    font-size: 14px;
    font-weight: 600;
    color: var(--gray-700);
}

.quantity-input {
    display: flex;
    align-items: center;
    border: 1px solid #e5e7eb;
    border-radius: 8px;
    overflow: hidden;
}

.quantity-input button {
    width: 44px;
    height: 44px;
    border: none;
    background: #f3f4f6;
    color: var(--gray-700);
    font-size: 18px;
    cursor: pointer;
}

.quantity-input button:hover {
    background: #e5e7eb;
}

.quantity-input input {
    width: 60px;
    height: 44px;
    border: none;
    text-align: center;
    font-size: 16px;
    font-weight: 600;
}

.stock-status {
    font-size: 14px;
    color: #10b981;
}

.stock-status.out-of-stock {
    color: #ef4444;
}

/* Actions */
.product-actions-section {
    display: flex;
    gap: 12px;
    margin-bottom: 24px;
}

.product-actions-section .btn {
    flex: 1;
    height: 52px;
    font-size: 16px;
}

.btn-buy-now {
    background: var(--gray-900);
}

.btn-buy-now:hover {
    background: var(--gray-800);
}

/* Features */
.product-features {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 12px;
    padding: 20px;
    background: #f9fafb;
    border-radius: 12px;
}

.feature-item {
    display: flex;
    align-items: center;
    gap: 10px;
    font-size: 14px;
    color: var(--gray-700);
}

.feature-item i {
    color: var(--primary);
    width: 20px;
}

/* Tabs */
.product-tabs {
    background: white;
    border-radius: 16px;
    padding: 24px;
    box-shadow: var(--shadow-sm);
    margin-bottom: 32px;
}

.tabs-header {
    display: flex;
    gap: 8px;
    border-bottom: 2px solid #e5e7eb;
    margin-bottom: 24px;
}

.tab-btn {
    padding: 12px 24px;
    border: none;
    background: none;
    font-size: 15px;
    font-weight: 600;
    color: var(--gray-600);
    cursor: pointer;
    border-bottom: 2px solid transparent;
    margin-bottom: -2px;
    transition: all 0.2s;
}

.tab-btn:hover {
    color: var(--primary);
}

.tab-btn.active {
    color: var(--primary);
    border-bottom-color: var(--primary);
}

.tab-pane {
    display: none;
}

.tab-pane.active {
    display: block;
}

/* Specs Table */
.specs-table {
    width: 100%;
    border-collapse: collapse;
}

.specs-table tr:nth-child(odd) {
    background: #f9fafb;
}

.specs-table td {
    padding: 14px 16px;
    font-size: 14px;
}

.specs-table td:first-child {
    font-weight: 600;
    color: var(--gray-700);
    width: 200px;
}

.specs-table td:last-child {
    color: var(--gray-900);
}

/* Reviews */
.reviews-summary {
    display: flex;
    align-items: center;
    gap: 32px;
    padding: 24px;
    background: #f9fafb;
    border-radius: 12px;
    margin-bottom: 24px;
}

.rating-big {
    text-align: center;
}

.rating-big .number {
    font-size: 48px;
    font-weight: 700;
    color: var(--gray-900);
}

.rating-big .stars {
    color: #fbbf24;
    font-size: 18px;
}

.rating-big .count {
    font-size: 14px;
    color: var(--gray-500);
}

.rating-bars {
    flex: 1;
}

.rating-bar {
    display: flex;
    align-items: center;
    gap: 12px;
    margin-bottom: 8px;
    font-size: 14px;
}

.rating-bar span {
    width: 20px;
}

.rating-bar .bar {
    flex: 1;
    height: 8px;
    background: #e5e7eb;
    border-radius: 4px;
    overflow: hidden;
}

.rating-bar .bar-fill {
    height: 100%;
    background: #fbbf24;
}

.rating-bar .count {
    width: 40px;
    color: var(--gray-500);
}

.reviews-list {
    margin-bottom: 24px;
}

.review-item {
    padding: 20px 0;
    border-bottom: 1px solid #e5e7eb;
}

.review-item:last-child {
    border-bottom: none;
}

.review-header {
    display: flex;
    align-items: center;
    gap: 12px;
    margin-bottom: 12px;
}

.review-avatar {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    background: var(--primary);
    color: white;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 600;
}

.review-meta {
    flex: 1;
}

.review-author {
    font-weight: 600;
    color: var(--gray-900);
}

.review-date {
    font-size: 13px;
    color: var(--gray-500);
}

.review-rating {
    color: #fbbf24;
}

.review-content {
    color: var(--gray-700);
    line-height: 1.6;
}

/* Write Review */
.write-review {
    padding-top: 24px;
    border-top: 1px solid #e5e7eb;
}


.review-access-notice {
    margin-bottom: 16px;
    padding: 12px 14px;
    border-radius: 12px;
    font-size: 14px;
    line-height: 1.5;
}

.review-access-notice.success {
    background: #ecfdf5;
    color: #065f46;
    border: 1px solid #a7f3d0;
}

.review-access-notice.warning {
    background: #fff7ed;
    color: #9a3412;
    border: 1px solid #fed7aa;
}
.write-review h3 {
    font-size: 18px;
    margin-bottom: 20px;
}

.rating-input {
    display: flex;
    gap: 8px;
    font-size: 24px;
    color: #e5e7eb;
    cursor: pointer;
}

.rating-input i.active,
.rating-input i:hover {
    color: #fbbf24;
}

/* Related Products */
.related-products {
    margin-top: 40px;
}

.related-products h2 {
    font-size: 24px;
    font-weight: 700;
    margin-bottom: 24px;
    display: flex;
    align-items: center;
    gap: 12px;
}

.related-products .products-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(220px, 1fr));
    gap: 20px;
}

/* Product Card */
.product-card {
    background: white;
    border-radius: 12px;
    overflow: hidden;
    box-shadow: 0 2px 8px rgba(0,0,0,0.08);
    transition: all 0.3s ease;
    position: relative;
    display: flex;
    flex-direction: column;
}

.product-card:hover {
    box-shadow: 0 8px 25px rgba(0,0,0,0.15);
    transform: translateY(-4px);
}

.product-badges {
    position: absolute;
    top: 8px;
    left: 8px;
    display: flex;
    flex-direction: column;
    gap: 4px;
    z-index: 10;
}

.product-badges .badge {
    padding: 4px 8px;
    border-radius: 4px;
    font-size: 11px;
    font-weight: 600;
    display: flex;
    align-items: center;
    gap: 4px;
}

.badge-installment {
    background: #fbbf24;
    color: #000;
}

.badge-gift {
    background: #fbbf24;
    color: #000;
}

.product-card .product-image {
    display: block;
    width: 100%;
    aspect-ratio: 1;
    overflow: hidden;
    background: #f8f9fa;
}

.product-card .product-image img {
    width: 100%;
    height: 100%;
    object-fit: contain;
    transition: transform 0.3s;
    padding: 16px;
}

.product-card:hover .product-image img {
    transform: scale(1.05);
}

.product-card .product-info {
    padding: 16px;
    flex: 1;
    display: flex;
    flex-direction: column;
    gap: 8px;
}

.product-card .product-name {
    font-size: 14px;
    font-weight: 600;
    line-height: 1.4;
    margin: 0;
    display: -webkit-box;
    line-clamp: 2;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

.product-card .product-name a {
    color: #1f2937;
    text-decoration: none;
}

.product-card .product-name a:hover {
    color: #e53935;
}

.product-card .product-rating {
    display: flex;
    align-items: center;
    gap: 4px;
    font-size: 12px;
}

.product-card .product-rating i {
    color: #fbbf24;
    font-size: 12px;
}

.product-card .product-rating .rating-count {
    color: #6b7280;
}

.product-specs {
    display: flex;
    gap: 8px;
    flex-wrap: wrap;
}

.spec-tag {
    background: #f3f4f6;
    padding: 4px 10px;
    border-radius: 4px;
    font-size: 12px;
    color: #4b5563;
    font-weight: 500;
}

.product-card .product-price {
    margin-top: auto;
    display: flex;
    flex-direction: column;
    gap: 2px;
}

.product-card .price-current {
    font-size: 18px;
    font-weight: 700;
    color: #e53935;
}

.product-card .price-old {
    font-size: 13px;
    color: #9ca3af;
    text-decoration: line-through;
}

.product-card .product-actions {
    padding: 0 16px 16px;
    display: flex;
    gap: 8px;
}

.product-card.selected-combo {
    box-shadow: 0 12px 30px rgba(0,0,0,0.18);
    transform: translateY(-6px);
    border: 2px solid rgba(99,102,241,0.12);
}

.combo-select {
    position: absolute;
    top: 8px;
    right: 8px;
    z-index: 20;
}

.combo-select input[type="checkbox"] {
    width: 20px;
    height: 20px;
}

.product-card .product-actions .btn {
    flex: 1;
    padding: 10px 16px;
    font-size: 13px;
    border-radius: 8px;
}

.product-card .btn-favorite {
    width: 42px;
    height: 42px;
    border: 1px solid #e5e7eb;
    background: white;
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    transition: all 0.2s;
    flex-shrink: 0;
}

.product-card .btn-favorite:hover {
    border-color: #e53935;
    color: #e53935;
}

.product-card .btn-favorite i {
    font-size: 18px;
}

/* Skeleton */
.skeleton {
    background: linear-gradient(90deg, #f0f0f0 25%, #e0e0e0 50%, #f0f0f0 75%);
    background-size: 200% 100%;
    animation: skeleton-loading 1.5s infinite;
    border-radius: 12px;
}

/* Responsive */
@media (max-width: 1024px) {
    .product-main {
        grid-template-columns: 1fr;
    }
    
    .product-features {
        grid-template-columns: 1fr;
    }
}

@media (max-width: 768px) {
    .reviews-summary {
        flex-direction: column;
        text-align: center;
    }
    
    .tabs-header {
        flex-wrap: wrap;
    }
    
    .tab-btn {
        flex: 1;
        min-width: fit-content;
    }
}
</style>
@endpush

@push('scripts')
<script>
const productId = window.location.pathname.split('/').pop();
let currentProduct = null;
let selectedVariants = {};
let quantity = 1;
let selectedRating = 0;

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

// Load product details
async function loadProduct() {
    try {
        const response = await fetch(`/api/products/${productId}`);
        const result = await response.json();
        
        // API returns { success: true, data: { product: {...} } }
        currentProduct = result.data?.product || result.data || result;
        
        renderProduct();
        loadReviews();
        loadRelatedProducts();
        loadCombos();
    } catch (error) {
        console.error('Error loading product:', error);
    }
}

// Render product
function renderProduct() {
    const product = currentProduct;
    document.getElementById('productBreadcrumb').textContent = product.name;
    document.title = `${product.name} - XanhStore`;
    
    // Handle images - can be array of objects with image_url or array of strings
    let images = [];
    if (product.images && product.images.length > 0) {
        images = product.images.map((img, index) => {
            const imageUrl = typeof img === 'object' ? img.image_url : img;
            return appendImageVersion(imageUrl, `${product.id}-${index}`);
        });
    } else if (product.image) {
        images = [appendImageVersion(product.image, product.id)];
    } else {
        images = [appendImageVersion(getProductFallbackImage(product.name), product.id)];
    }
    
    const hasDiscount = product.sale_price && product.sale_price < product.price;
    const discountPercent = hasDiscount ? Math.round((1 - product.sale_price / product.price) * 100) : 0;
    
    document.getElementById('productMain').innerHTML = `
        <!-- Gallery -->
        <div class="product-gallery">
            <div class="main-image">
                <img id="mainImage" src="${images[0]}" alt="${product.name}">
            </div>
            ${images.length > 1 ? `
                <div class="thumbnail-list">
                    ${images.map((img, index) => `
                        <div class="thumbnail ${index === 0 ? 'active' : ''}" onclick="changeImage('${img}', this)">
                            <img src="${img}" alt="${product.name}">
                        </div>
                    `).join('')}
                </div>
            ` : ''}
        </div>
        
        <!-- Info -->
        <div class="product-info-section">
            <h1>${product.name}</h1>
            
            <div class="product-meta">
                <div class="product-rating">
                    ${generateStars(product.average_rating || 0)}
                    <span>(${product.reviews_count || 0} đánh giá)</span>
                </div>
                <div class="product-sku">SKU: ${product.sku || 'N/A'}</div>
            </div>
            
            <div class="product-price-section">
                <span class="price-current">${formatPrice(product.sale_price || product.price)}₫</span>
                ${hasDiscount ? `
                    <span class="price-old">${formatPrice(product.price)}₫</span>
                    <span class="price-discount">-${discountPercent}%</span>
                ` : ''}
            </div>
            
            ${renderVariants(product)}
            
            <div class="quantity-section">
                <span class="quantity-label">Số lượng:</span>
                <div class="quantity-input">
                    <button onclick="changeQuantity(-1)">−</button>
                    <input type="number" id="quantityInput" value="1" min="1" max="${product.stock || 99}" onchange="updateQuantity(this.value)">
                    <button onclick="changeQuantity(1)">+</button>
                </div>
                <span class="stock-status ${product.stock > 0 ? '' : 'out-of-stock'}">
                    ${product.stock > 0 ? `Còn ${product.stock} sản phẩm` : 'Hết hàng'}
                </span>
            </div>
            
            <div class="product-actions-section">
                <button class="btn btn-primary" onclick="addToCart(${product.id})" ${product.stock <= 0 ? 'disabled' : ''}>
                    <i class="fas fa-cart-plus"></i> Thêm vào giỏ
                </button>
                <button class="btn btn-primary btn-buy-now" onclick="buyNow(${product.id})" ${product.stock <= 0 ? 'disabled' : ''}>
                    <i class="fas fa-bolt"></i> Mua ngay
                </button>
                <button class="btn btn-icon btn-secondary" onclick="toggleFavorite(${product.id})">
                    <i class="far fa-heart"></i>
                </button>
            </div>
            
            <div class="product-features">
                <div class="feature-item">
                    <i class="fas fa-shield-alt"></i>
                    <span>Bảo hành chính hãng 12 tháng</span>
                </div>
                <div class="feature-item">
                    <i class="fas fa-sync-alt"></i>
                    <span>Đổi trả trong 30 ngày</span>
                </div>
                <div class="feature-item">
                    <i class="fas fa-shipping-fast"></i>
                    <span>Miễn phí giao hàng toàn quốc</span>
                </div>
                <div class="feature-item">
                    <i class="fas fa-headset"></i>
                    <span>Hỗ trợ kỹ thuật 24/7</span>
                </div>
            </div>
        </div>
    `;
    
    // Render description
    document.getElementById('productDescription').innerHTML = product.description || '<p>Chưa có mô tả cho sản phẩm này.</p>';
    
    // Render specs
    renderSpecs(product);
}

// Render variants
function renderVariants(product) {
    if (!product.variants || product.variants.length === 0) return '';
    
    let html = '<div class="product-variants">';
    
    // Group variants by type
    const variantTypes = {};
    product.variants.forEach(v => {
        if (!variantTypes[v.type]) variantTypes[v.type] = [];
        variantTypes[v.type].push(v);
    });
    
    Object.keys(variantTypes).forEach(type => {
        html += `
            <div class="variant-group">
                <div class="variant-label">${type}:</div>
                <div class="variant-options">
                    ${variantTypes[type].map((v, i) => `
                        <button class="variant-btn ${type === 'Màu sắc' ? 'color-btn' : ''} ${i === 0 ? 'active' : ''}" 
                                ${type === 'Màu sắc' ? `style="background-color: ${v.value}"` : ''}
                                onclick="selectVariant('${type}', '${v.value}', this)">
                            ${type !== 'Màu sắc' ? v.value : ''}
                        </button>
                    `).join('')}
                </div>
            </div>
        `;
    });
    
    html += '</div>';
    return html;
}

// Add to cart function
function addToCart(productId) {
    const token = localStorage.getItem('auth_token') || localStorage.getItem('auth_token');
    const qty = quantity || 1;
    
    if (!token) {
        // Lưu vào localStorage cho guest, kèm tên/giá/ảnh nếu có sẵn
        let cart = JSON.parse(localStorage.getItem('guest_cart') || '[]');
        const existingItem = cart.find(item => item.product_id == productId);
        
        if (existingItem) {
            existingItem.quantity = (Number(existingItem.quantity) || 0) + qty;
        } else {
            cart.push({ product_id: productId, quantity: qty, name: currentProduct?.name ?? null, price: currentProduct?.price ?? null, image: currentProduct?.images?.[0]?.image_url ?? null });
        }
        
        // normalize and coerce types
        cart = cart.map(it => ({
            product_id: it.product_id,
            quantity: Number(it.quantity) || 1,
            name: it.name ?? null,
            price: Number(it.price) || 0,
            image: it.image ?? null
        }));

        localStorage.setItem('guest_cart', JSON.stringify(cart));
        showNotification('Đã thêm vào giỏ hàng!', 'success');
        updateCartBadge();
        return;
    }
    
    // Người dùng đã đăng nhập
    fetch('/api/cart/add', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'Authorization': `Bearer ${token}`,
            'Accept': 'application/json'
        },
        body: JSON.stringify({
            product_id: productId,
            quantity: qty
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

// Toggle favorite function
function toggleFavorite(productId) {
    const token = localStorage.getItem('auth_token') || localStorage.getItem('auth_token');
    
    if (!token) {
        showNotification('Vui lòng đăng nhập để thêm yêu thích!', 'warning');
        return;
    }
    
    const btn = event.target.closest('button');
    const icon = btn.querySelector('i');
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

// Render specs table
function renderSpecs(product) {
    const specs = product.specifications || {};
    const table = document.getElementById('specsTable');
    
    const defaultSpecs = {
        'Màn hình': specs.screen || 'N/A',
        'Chip xử lý': specs.processor || 'N/A',
        'RAM': specs.ram || 'N/A',
        'Bộ nhớ trong': specs.storage || 'N/A',
        'Camera sau': specs.rear_camera || 'N/A',
        'Camera trước': specs.front_camera || 'N/A',
        'Pin': specs.battery || 'N/A',
        'Hệ điều hành': specs.os || 'N/A'
    };
    
    table.innerHTML = Object.keys(defaultSpecs).map(key => `
        <tr>
            <td>${key}</td>
            <td>${defaultSpecs[key]}</td>
        </tr>
    `).join('');
}

// Load reviews
async function loadReviews() {
    try {
        const response = await fetch(`/api/products/${productId}/reviews`);
        const data = await response.json();
        const reviews = data.data || [];
        const summary = data.summary || {};
        
        document.getElementById('reviewCount').textContent = reviews.length;
        
        // Calculate rating distribution
        const ratingDist = [0, 0, 0, 0, 0];
        let totalRating = 0;
        reviews.forEach(r => {
            ratingDist[r.rating - 1]++;
            totalRating += r.rating;
        });
        const avgRating = summary.average ?? (reviews.length ? (totalRating / reviews.length).toFixed(1) : 0);
        
        // Render summary
        document.getElementById('reviewsSummary').innerHTML = `
            <div class="rating-big">
                <div class="number">${avgRating}</div>
                <div class="stars">${generateStars(avgRating)}</div>
                <div class="count">${reviews.length} đánh giá</div>
            </div>
            <div class="rating-bars">
                ${[5, 4, 3, 2, 1].map(i => `
                    <div class="rating-bar">
                        <span>${i}<i class="fas fa-star" style="color: #fbbf24; margin-left: 2px;"></i></span>
                        <div class="bar">
                            <div class="bar-fill" style="width: ${reviews.length ? (ratingDist[i-1] / reviews.length * 100) : 0}%"></div>
                        </div>
                        <span class="count">${ratingDist[i-1]}</span>
                    </div>
                `).join('')}
            </div>
        `;
        
        // Render reviews list
        if (reviews.length === 0) {
            document.getElementById('reviewsList').innerHTML = `
                <div style="text-align: center; padding: 40px; color: var(--gray-500);">
                    <i class="fas fa-comment-slash" style="font-size: 48px; margin-bottom: 16px; color: #e5e7eb;"></i>
                    <p>Chưa có đánh giá nào cho sản phẩm này.</p>
                </div>
            `;
        } else {
            document.getElementById('reviewsList').innerHTML = reviews.map(review => `
                <div class="review-item">
                    <div class="review-header">
                        <div class="review-avatar">${review.user?.name?.charAt(0) || 'U'}</div>
                        <div class="review-meta">
                            <div class="review-author">${review.user?.name || 'Người dùng'}</div>
                            <div class="review-date">${new Date(review.created_at).toLocaleDateString('vi-VN')}</div>
                        </div>
                        <div class="review-rating">${generateStars(review.rating)}</div>
                    </div>
                    <div class="review-content">${review.comment || ''}</div>
                </div>
            `).join('');
        }
        
        await loadReviewEligibility();
    } catch (error) {
        console.error('Error loading reviews:', error);
    }
}

// Check whether the current user can review this product
async function loadReviewEligibility() {
    const section = document.getElementById('writeReviewSection');
    const notice = document.getElementById('reviewAccessNotice');
    const form = document.getElementById('reviewForm');

    if (!section || !notice || !form) {
        return;
    }

    const token = localStorage.getItem('auth_token');

    if (!token) {
        section.style.display = 'block';
        notice.style.display = 'block';
        notice.className = 'review-access-notice warning';
        notice.textContent = 'Bạn cần đăng nhập và đã mua sản phẩm này để gửi đánh giá.';
        form.style.display = 'none';
        return;
    }

    try {
        const response = await fetch(`/api/products/${productId}/review-eligibility`, {
            headers: {
                'Accept': 'application/json',
                'Authorization': `Bearer ${token}`
            }
        });

        const data = await response.json();
        const canReview = !!data.data?.can_review;
        const hasReviewed = !!data.data?.has_reviewed;

        section.style.display = 'block';
        notice.style.display = 'block';

        if (canReview) {
            notice.className = 'review-access-notice success';
            notice.textContent = hasReviewed
                ? 'Bạn đã gửi đánh giá cho sản phẩm này. Bạn có thể cập nhật lại đánh giá bên dưới.'
                : 'Bạn đã mua sản phẩm này. Hãy chia sẻ trải nghiệm của bạn.';
            form.style.display = 'block';
        } else {
            notice.className = 'review-access-notice warning';
            notice.textContent = 'Bạn chỉ có thể đánh giá sản phẩm đã mua.';
            form.style.display = 'none';
        }
    } catch (error) {
        console.error('Error loading review eligibility:', error);
        section.style.display = 'block';
        notice.style.display = 'block';
        notice.className = 'review-access-notice warning';
        notice.textContent = 'Không thể kiểm tra quyền đánh giá ngay lúc này.';
        form.style.display = 'none';
    }
}

// Load related products
async function loadRelatedProducts() {
    try {
        const categoryId = currentProduct?.category_id;
            const response = await fetch(`/api/products/${productId}/accessories`);
            const result = await response.json();
            const products = result.data || result.data?.data || [];
        
        const container = document.getElementById('relatedProducts');
        if (products.length > 0) {
            container.innerHTML = products.slice(0, 4).map(product => createProductCard(product)).join('');
        } else {
            container.innerHTML = '<p style="color: var(--gray-500);">Không có sản phẩm liên quan.</p>';
        }
    } catch (error) {
        console.error('Error loading related products:', error);
    }
}

// Load combos for this product
async function loadCombos() {
    try {
        const res = await fetch(`/api/products/${productId}/combos`);
        const data = await res.json();
        const combos = data.data || [];
        if (!combos.length) return;

        // create combo section above related products
        const container = document.getElementById('relatedProducts');
        const comboHtml = combos.map(combo => {
            // calculate original sum and discounted price
            let sum = 0;
            const items = combo.products.map(p => {
                sum += (p.price || 0) * (p.pivot?.quantity || 1);
                return p;
            });
            const discount = combo.discount_percent || 0;
            const discounted = Math.round(sum * (1 - discount / 100));

            return `
                <div class="product-card" style="position:relative; border:2px solid #eef2ff;">
                    <div style="padding:16px;">
                        <h3 style="margin:0 0 8px 0">${combo.name} <small style="color:#6b7280; font-weight:500">- ${discount}%</small></h3>
                        <div style="display:flex; gap:12px; align-items:center; margin-bottom:8px;">
                            ${items.slice(0,3).map(p=>`<img src="${getProductFallbackImage(p.name,p.images)}" style="width:64px;height:64px;object-fit:cover;border-radius:8px">`).join('')}
                        </div>
                        <div style="margin-bottom:8px;">Giá combo: <strong>${formatPrice(discounted)}₫</strong> <span style="text-decoration:line-through; color:#9ca3af; margin-left:8px">${formatPrice(sum)}₫</span></div>
                        <button class="btn btn-primary" onclick="addComboById(${combo.id})">Thêm combo vào giỏ</button>
                    </div>
                </div>
            `;
        }).join('');

        // insert at top of relatedProducts
        container.insertAdjacentHTML('afterbegin', comboHtml);
    } catch (err) {
        console.error('Error loading combos:', err);
    }
}

async function addComboById(comboId) {
    const token = localStorage.getItem('auth_token');
    if (!token) {
        showNotification('Vui lòng đăng nhập để thêm combo vào giỏ', 'warning');
        return;
    }

    try {
        const res = await fetch('/api/cart/add', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'Authorization': `Bearer ${token}`
            },
            body: JSON.stringify({ combo_id: comboId, quantity: 1 })
        });

        const data = await res.json();
        if (data.success) {
            showNotification('Đã thêm combo vào giỏ hàng!', 'success');
            updateCartBadge();
        } else {
            showNotification(data.message || 'Không thể thêm combo', 'error');
        }
    } catch (err) {
        console.error('Add combo err:', err);
        showNotification('Lỗi khi thêm combo', 'error');
    }
}

// Create product card for related products
function createProductCard(product) {
    const imageUrl = getProductFallbackImage(product.name, product.images);
    const discountPrice = Math.round(product.price * 0.85);
    
    return `
        <div class="product-card" style="position:relative;">
            <label class="combo-select"><input type="checkbox" data-pid="${product.id}" onchange="toggleAccessorySelection(${product.id}, this)"></label>
            <div class="product-badges">
                <span class="badge badge-installment"><i class="fas fa-bolt"></i> Trả góp 0%</span>
                <span class="badge badge-gift"><i class="fas fa-gift"></i> Quà 2Tr</span>
            </div>
            <a href="/products/${product.id}" class="product-image">
                <img src="${imageUrl}" alt="${product.name}">
            </a>
            <div class="product-info">
                <h3 class="product-name">
                    <a href="/products/${product.id}">${product.name}</a>
                </h3>
                <div class="product-rating">
                    ${generateStars(product.average_rating || 4.5)}
                    <span class="rating-count">(${product.reviews_count || Math.floor(Math.random() * 1000) + 500})</span>
                </div>
                <div class="product-specs">
                    <span class="spec-tag">${product.ram || '8GB'}</span>
                    <span class="spec-tag">${product.storage || '256GB'}</span>
                </div>
                <div class="product-price">
                    <span class="price-current">${formatPrice(discountPrice)}đ</span>
                    <span class="price-old">${formatPrice(product.price)}đ</span>
                </div>
            </div>
            <div class="product-actions">
                <button class="btn btn-primary" onclick="addToCart(${product.id}); event.stopPropagation();">
                    <i class="fas fa-shopping-cart"></i> Mua ngay
                </button>
                <button class="btn-favorite" onclick="toggleFavorite(${product.id}); event.stopPropagation();">
                    <i class="far fa-heart"></i>
                </button>
            </div>
        </div>
    `;
}

// Combo selection state
const selectedAccessories = new Set();

function toggleAccessorySelection(productId, checkbox) {
    if (checkbox.checked) selectedAccessories.add(productId); else selectedAccessories.delete(productId);
    updateComboUI();
}

function updateComboUI() {
    const btn = document.getElementById('addComboBtn');
    if (btn) btn.textContent = `Thêm mua kèm vào giỏ (${selectedAccessories.size})`;
    document.querySelectorAll('.product-card').forEach(card => {
        const cb = card.querySelector('input[type=checkbox]');
        if (!cb) return;
        const pid = parseInt(cb.dataset.pid);
        if (selectedAccessories.has(pid)) {
            card.classList.add('selected-combo');
            cb.checked = true;
        } else {
            card.classList.remove('selected-combo');
            cb.checked = false;
        }
    });
}

function clearComboSelection() {
    selectedAccessories.clear();
    updateComboUI();
}

async function addComboToCart() {
    if (selectedAccessories.size === 0) {
        showNotification('Chưa chọn sản phẩm mua kèm', 'warning');
        return;
    }

    const ids = Array.from(selectedAccessories);
    const token = localStorage.getItem('auth_token');

    if (!token) {
        // guest cart in localStorage - fetch metadata for each accessory to avoid NaN
        let cart = JSON.parse(localStorage.getItem('guest_cart') || '[]');
        try {
            await Promise.all(ids.map(async (id) => {
                const existing = cart.find(i => i.product_id == id);
                if (existing) { existing.quantity = Number(existing.quantity) + 1; return; }
                try {
                    const res = await fetch(`/api/products/${id}`);
                    if (!res.ok) {
                        cart.push({ product_id: id, quantity: 1, name: null, price: 0, image: null });
                        return;
                    }
                    const data = await res.json();
                    const p = (data.data && data.data.product) ? data.data.product : (data.data && data.data.id ? data.data : (data.product || data));
                    cart.push({
                        product_id: id,
                        quantity: 1,
                        name: p?.name ?? null,
                        price: Number(p?.price) || 0,
                        image: (p?.images && p.images[0] && (p.images[0].image_url || p.images[0].path)) || null
                    });
                } catch (e) {
                    cart.push({ product_id: id, quantity: 1, name: null, price: 0, image: null });
                }
            }));

            // normalize cart entries
            cart = cart.map(it => ({
                product_id: it.product_id,
                quantity: Number(it.quantity) || 1,
                name: it.name ?? null,
                price: Number(it.price) || 0,
                image: it.image ?? null
            }));

            localStorage.setItem('guest_cart', JSON.stringify(cart));
            showNotification('Đã thêm mua kèm vào giỏ hàng!', 'success');
            clearComboSelection();
            updateCartBadge();
            return;
        } catch (e) {
            console.error('Error while adding accessories to guest cart:', e);
        }
    }

    try {
        await Promise.all(ids.map(id => fetch('/api/cart/add', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'Authorization': `Bearer ${token}`
            },
            body: JSON.stringify({ product_id: id, quantity: 1 })
        })));

        showNotification('Đã thêm mua kèm vào giỏ hàng!', 'success');
        clearComboSelection();
        updateCartBadge();
    } catch (err) {
        console.error('Combo add error:', err);
        showNotification('Lỗi khi thêm mua kèm', 'error');
    }
}

function getProductFallbackImage(name, images = []) {
    if (images.length > 0) {
        const firstImage = images[0];
        return typeof firstImage === 'object' ? firstImage.image_url : firstImage;
    }

    const normalizedName = (name || '').toLowerCase();

    if (normalizedName.includes('iphone')) {
        return 'https://images.unsplash.com/photo-1592750475338-74b7b21085ab?w=500&h=500&fit=crop';
    }

    if (normalizedName.includes('samsung')) {
        return 'https://images.unsplash.com/photo-1610945415295-d9bbf067e59c?w=500&h=500&fit=crop';
    }

    if (normalizedName.includes('xiaomi')) {
        return 'https://images.unsplash.com/photo-1598327106026-d9521da673d1?w=500&h=500&fit=crop';
    }

    if (normalizedName.includes('oppo')) {
        return 'https://images.unsplash.com/photo-1598327105666-5b89351aff97?w=500&h=500&fit=crop';
    }

    if (normalizedName.includes('vivo')) {
        return 'https://images.unsplash.com/photo-1605236453806-6ff36851218e?w=500&h=500&fit=crop';
    }

    return 'https://placehold.co/500x500/f3f4f6/111827?text=XanhStore';
}

function appendImageVersion(url, version) {
    if (!url) return url;
    return url.includes('?') ? `${url}&v=${version}` : `${url}?v=${version}`;
}

// Helper functions
function changeImage(src, el) {
    document.getElementById('mainImage').src = src;
    document.querySelectorAll('.thumbnail').forEach(t => t.classList.remove('active'));
    el.classList.add('active');
}

function selectVariant(type, value, el) {
    selectedVariants[type] = value;
    el.parentElement.querySelectorAll('.variant-btn').forEach(b => b.classList.remove('active'));
    el.classList.add('active');
}

function changeQuantity(delta) {
    const input = document.getElementById('quantityInput');
    const newVal = Math.max(1, Math.min(parseInt(input.max), parseInt(input.value) + delta));
    input.value = newVal;
    quantity = newVal;
}

function updateQuantity(val) {
    const input = document.getElementById('quantityInput');
    quantity = Math.max(1, Math.min(parseInt(input.max), parseInt(val)));
    input.value = quantity;
}

function switchTab(tabId) {
    document.querySelectorAll('.tab-btn').forEach(b => b.classList.remove('active'));
    document.querySelectorAll('.tab-pane').forEach(p => p.classList.remove('active'));
    document.querySelector(`[onclick="switchTab('${tabId}')"]`).classList.add('active');
    document.getElementById('tab-' + tabId).classList.add('active');
}

function buyNow(id) {
    addToCart(id);
    window.location.href = '/checkout';
}

// Rating input
document.addEventListener('DOMContentLoaded', () => {
    loadProduct();
    
    // Rating input handler
    document.getElementById('ratingInput')?.addEventListener('click', function(e) {
        if (e.target.tagName === 'I') {
            selectedRating = parseInt(e.target.dataset.rating);
            this.querySelectorAll('i').forEach((star, i) => {
                star.classList.toggle('fas', i < selectedRating);
                star.classList.toggle('far', i >= selectedRating);
                star.classList.toggle('active', i < selectedRating);
            });
        }
    });
    
    // Review form
    document.getElementById('reviewForm')?.addEventListener('submit', async function(e) {
        e.preventDefault();
        
        const token = localStorage.getItem('auth_token');
        if (!token) {
            alert('Vui lòng đăng nhập để đánh giá sản phẩm');
            return;
        }

        if (!selectedRating) {
            alert('Vui lòng chọn số sao đánh giá');
            return;
        }

        const comment = document.getElementById('reviewComment').value.trim();
        if (!comment) {
            alert('Vui lòng nhập nội dung đánh giá');
            return;
        }
        
        try {
            const response = await fetch(`/api/products/${productId}/reviews`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'Authorization': `Bearer ${token}`
                },
                body: JSON.stringify({
                    rating: selectedRating,
                    comment
                })
            });
            
            if (response.ok) {
                const data = await response.json();
                alert(data.message || 'Cảm ơn bạn đã đánh giá!');
                loadReviews();
                this.reset();
                selectedRating = 0;
                document.querySelectorAll('#ratingInput i').forEach(star => {
                    star.classList.remove('fas', 'active');
                    star.classList.add('far');
                });
            } else {
                const data = await response.json();
                alert(data.message || 'Không thể gửi đánh giá');
            }
        } catch (error) {
            alert('Đã có lỗi xảy ra');
        }
    });
});
</script>
@endpush

@extends('layouts.app')

@section('title', 'XanhStore - Hệ thống bán lẻ điện thoại hàng đầu Việt Nam')

@section('content')
<!-- Hero Banner -->
<section class="hero-banner">
    <div class="container">
        <div class="banner-slider" id="bannerSlider">
            <div class="banner-slide active banner-slide-full">
                <a href="/products/2">
                    <img src="/images/banner_s24.png" alt="Samsung Galaxy S24 Ultra">
                </a>
            </div>
            <div class="banner-slide banner-slide-full">
                <a href="/products/1">
                    <img src="/images/banner-iphone15-aloviet.png" alt="iPhone 15">
                </a>
            </div>
        </div>
        <div class="banner-dots">
            <button class="dot active" onclick="goToSlide(0)"></button>
            <button class="dot" onclick="goToSlide(1)"></button>
        </div>
    </div>
</section>

<!-- Categories -->
<section class="categories-section">
    <div class="container">
        <div class="section-header">
            <h2><i class="fas fa-th-large"></i> Danh mục sản phẩm</h2>
        </div>
        <div class="categories-grid" id="categoriesGrid">
            <!-- Categories will be loaded via JavaScript -->
            <div class="category-card skeleton"></div>
            <div class="category-card skeleton"></div>
            <div class="category-card skeleton"></div>
            <div class="category-card skeleton"></div>
            <div class="category-card skeleton"></div>
        </div>
    </div>
</section>

<!-- Flash Sale -->
<section class="flash-sale-section">
    <div class="container">
        <div class="section-header">
            <h2>
                <i class="fas fa-bolt" style="color: var(--primary);"></i> 
                Flash Sale
            </h2>
            <div class="countdown" id="flashSaleCountdown">
                <div class="countdown-item">
                    <span class="countdown-value" id="hours">00</span>
                    <span class="countdown-label">Giờ</span>
                </div>
                <div class="countdown-separator">:</div>
                <div class="countdown-item">
                    <span class="countdown-value" id="minutes">00</span>
                    <span class="countdown-label">Phút</span>
                </div>
                <div class="countdown-separator">:</div>
                <div class="countdown-item">
                    <span class="countdown-value" id="seconds">00</span>
                    <span class="countdown-label">Giây</span>
                </div>
            </div>
            <a href="/products?sale=1" class="btn btn-outline">Xem tất cả</a>
        </div>
        <div class="products-grid" id="flashSaleProducts">
            <!-- Products will be loaded via JavaScript -->
            <div class="product-card skeleton"></div>
            <div class="product-card skeleton"></div>
            <div class="product-card skeleton"></div>
            <div class="product-card skeleton"></div>
        </div>
    </div>
</section>

<!-- Featured Products -->
<section class="featured-section">
    <div class="container">
        <div class="section-header">
            <h2><i class="fas fa-star" style="color: #fbbf24;"></i> Sản phẩm nổi bật</h2>
            <a href="/products?featured=1" class="btn btn-outline">Xem tất cả</a>
        </div>
        <div class="products-grid" id="featuredProducts">
            <!-- Products will be loaded via JavaScript -->
            <div class="product-card skeleton"></div>
            <div class="product-card skeleton"></div>
            <div class="product-card skeleton"></div>
            <div class="product-card skeleton"></div>
            <div class="product-card skeleton"></div>
            <div class="product-card skeleton"></div>
            <div class="product-card skeleton"></div>
            <div class="product-card skeleton"></div>
        </div>
    </div>
</section>

<!-- Brand Showcase -->
<section class="brands-section">
    <div class="container">
        <div class="section-header">
            <h2><i class="fas fa-building"></i> Thương hiệu nổi tiếng</h2>
        </div>

        <div class="brands-grid" id="brandsGrid">
            <a href="{{ url('/products?brand=apple') }}" class="brand-card">
                <img src="{{ asset('images/iphone.jpg') }}" alt="Apple">
            </a>

            <a href="{{ url('/products?brand=samsung') }}" class="brand-card">
                <img src="{{ asset('images/samsung.jpg') }}" alt="Samsung">
            </a>

            <a href="{{ url('/products?brand=xiaomi') }}" class="brand-card">
                <img src="{{ asset('images/xiaomi.jpg') }}" alt="Xiaomi">
            </a>

            <a href="{{ url('/products?brand=oppo') }}" class="brand-card">
                <img src="{{ asset('images/oppo.jpg') }}" alt="OPPO">
            </a>

            <a href="{{ url('/products?brand=vivo') }}" class="brand-card">
                <img src="{{ asset('images/vivo.jpg') }}" alt="Vivo">
            </a>

            <a href="{{ url('/products?brand=realme') }}" class="brand-card">
                <img src="{{ asset('images/realme.jpg') }}" alt="Realme">
            </a>
        </div>
    </div>
</section>

<!-- New Arrivals -->
<section class="new-arrivals-section">
    <div class="container">
        <div class="section-header">
            <h2><i class="fas fa-box-open" style="color: #10b981;"></i> Sản phẩm mới nhất</h2>
            <a href="/products?sort=newest" class="btn btn-outline">Xem tất cả</a>
        </div>
        <div class="products-grid" id="newProducts">
            <!-- Products will be loaded via JavaScript -->
            <div class="product-card skeleton"></div>
            <div class="product-card skeleton"></div>
            <div class="product-card skeleton"></div>
            <div class="product-card skeleton"></div>
        </div>
    </div>
</section>

<!-- Features -->
<section class="features-section">
    <div class="container">
        <div class="features-grid">
            <div class="feature-card">
                <div class="feature-icon">
                    <i class="fas fa-shipping-fast"></i>
                </div>
                <div class="feature-content">
                    <h3>Miễn phí vận chuyển</h3>
                    <p>Cho đơn hàng từ 500.000₫</p>
                </div>
            </div>
            <div class="feature-card">
                <div class="feature-icon">
                    <i class="fas fa-shield-alt"></i>
                </div>
                <div class="feature-content">
                    <h3>Bảo hành chính hãng</h3>
                    <p>Bảo hành 12-24 tháng</p>
                </div>
            </div>
            <div class="feature-card">
                <div class="feature-icon">
                    <i class="fas fa-sync-alt"></i>
                </div>
                <div class="feature-content">
                    <h3>Đổi trả dễ dàng</h3>
                    <p>Đổi trả trong 30 ngày</p>
                </div>
            </div>
            <div class="feature-card">
                <div class="feature-icon">
                    <i class="fas fa-headset"></i>
                </div>
                <div class="feature-content">
                    <h3>Hỗ trợ 24/7</h3>
                    <p>Hotline: 1900.9999</p>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@push('styles')
<style>
/* Hero Banner */
.hero-banner {
    background: linear-gradient(135deg, #fee2e2 0%, #fecaca 100%);
    padding: 40px 0;
    margin-bottom: 40px;
}

.banner-slider {
    position: relative;
    overflow: hidden;
    border-radius: 16px;
}

.banner-slide {
    display: none;
    align-items: center;
    justify-content: space-between;
    padding: 40px 60px;
    background: white;
    border-radius: 16px;
    min-height: 400px;
}

.banner-slide.active {
    display: flex;
}

.banner-slide-full {
    padding: 0 !important;
    min-height: auto !important;
    background: transparent !important;
    width: 100%;
}

.banner-slide-full a {
    display: block;
    width: 100%;
}

.banner-slide-full img {
    width: 100%;
    height: auto;
    display: block;
    border-radius: 16px;
}

.banner-content {
    max-width: 50%;
}

.banner-tag {
    display: inline-block;
    background: var(--primary);
    color: white;
    padding: 6px 16px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: 600;
    margin-bottom: 16px;
}

.banner-content h1 {
    font-size: 42px;
    font-weight: 700;
    color: var(--gray-900);
    margin-bottom: 12px;
}

.banner-content p {
    font-size: 18px;
    color: var(--gray-600);
    margin-bottom: 24px;
}

.banner-price {
    margin-bottom: 24px;
}

.banner-price .price-old {
    font-size: 18px;
    color: var(--gray-500);
    text-decoration: line-through;
    margin-right: 12px;
}

.banner-price .price-new {
    font-size: 32px;
    font-weight: 700;
    color: var(--primary);
}

.banner-image img {
    max-width: 350px;
    height: auto;
}

.banner-dots {
    display: flex;
    justify-content: center;
    gap: 8px;
    margin-top: 20px;
}

.banner-dots .dot {
    width: 12px;
    height: 12px;
    border-radius: 50%;
    background: #e5e7eb;
    border: none;
    cursor: pointer;
    transition: all 0.3s;
}

.banner-dots .dot.active {
    background: var(--primary);
    width: 32px;
    border-radius: 6px;
}

/* Categories Section */
.categories-section {
    padding: 40px 0;
}

.categories-grid {
    display: grid;
    grid-template-columns: repeat(5, 1fr);
    gap: 20px;
}

.category-card {
    background: white;
    border-radius: 12px;
    padding: 24px;
    text-align: center;
    box-shadow: var(--shadow-sm);
    transition: all 0.3s;
    cursor: pointer;
}

.category-card:hover {
    transform: translateY(-4px);
    box-shadow: var(--shadow-md);
}

.category-card .category-icon {
    width: 64px;
    height: 64px;
    margin: 0 auto 16px;
    background: #fee2e2;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 28px;
    color: var(--primary);
}

.category-card .category-name {
    font-weight: 600;
    color: var(--gray-900);
    margin-bottom: 4px;
}

.category-card .category-count {
    font-size: 13px;
    color: var(--gray-500);
}

/* Flash Sale Section */
.flash-sale-section {
    background: linear-gradient(135deg, #1a2332 0%, #2d3748 100%);
    padding: 40px 0;
    margin: 40px 0;
}

.flash-sale-section .section-header h2 {
    color: white;
}

.flash-sale-section .btn-outline {
    background-color: white;
    border-color: white;
    color: var(--primary);
    font-weight: 600;
}

.flash-sale-section .btn-outline:hover {
    background-color: var(--primary);
    border-color: var(--primary);
    color: white;
}

.countdown {
    display: flex;
    align-items: center;
    gap: 8px;
}

.countdown-item {
    background: white;
    padding: 8px 12px;
    border-radius: 8px;
    text-align: center;
    min-width: 50px;
}

.countdown-value {
    font-size: 20px;
    font-weight: 700;
    color: var(--primary);
    display: block;
}

.countdown-label {
    font-size: 11px;
    color: var(--gray-600);
}

.countdown-separator {
    color: white;
    font-size: 24px;
    font-weight: 700;
}

/* Products Grid */
.products-grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 20px;
}

/* Section Header */
.section-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 24px;
    flex-wrap: wrap;
    gap: 16px;
}

.section-header h2 {
    font-size: 24px;
    font-weight: 700;
    color: var(--gray-900);
    display: flex;
    align-items: center;
    gap: 12px;
}

/* Featured Section */
.featured-section,
.new-arrivals-section {
    padding: 40px 0;
}

/* Brands Section */
.brands-section {
    padding: 40px 0;
    background: #f9fafb;
}

.brands-grid {
    display: grid;
    grid-template-columns: repeat(6, 1fr);
    gap: 20px;
}

.brand-card {
    background: white;
    border-radius: 12px;
    padding: 24px;
    display: flex;
    align-items: center;
    justify-content: center;
    box-shadow: var(--shadow-sm);
    transition: all 0.3s;
}

.brand-card:hover {
    transform: translateY(-2px);
    box-shadow: var(--shadow-md);
}

.brand-card img {
    max-width: 100%;
    height: 40px;
    object-fit: contain;
}

/* Features Section */
.features-section {
    padding: 60px 0;
    background: white;
}

.features-grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 24px;
}

.feature-card {
    display: flex;
    align-items: center;
    gap: 16px;
    padding: 24px;
    background: #f9fafb;
    border-radius: 12px;
}

.feature-icon {
    width: 56px;
    height: 56px;
    background: var(--primary);
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 24px;
    flex-shrink: 0;
}

.feature-content h3 {
    font-weight: 600;
    color: var(--gray-900);
    margin-bottom: 4px;
}

.feature-content p {
    font-size: 14px;
    color: var(--gray-600);
}

/* Skeleton Loading */
.skeleton {
    background: linear-gradient(90deg, #f0f0f0 25%, #e0e0e0 50%, #f0f0f0 75%);
    background-size: 200% 100%;
    animation: skeleton-loading 1.5s infinite;
    min-height: 300px;
    border-radius: 12px;
}

@keyframes skeleton-loading {
    0% { background-position: 200% 0; }
    100% { background-position: -200% 0; }
}

/* Responsive */
@media (max-width: 1024px) {
    .categories-grid {
        grid-template-columns: repeat(3, 1fr);
    }
    .products-grid {
        grid-template-columns: repeat(3, 1fr);
    }
    .brands-grid {
        grid-template-columns: repeat(4, 1fr);
    }
    .features-grid {
        grid-template-columns: repeat(2, 1fr);
    }
}

@media (max-width: 768px) {
    .banner-slide {
        flex-direction: column;
        text-align: center;
        padding: 30px;
    }
    .banner-content {
        max-width: 100%;
    }
    .banner-content h1 {
        font-size: 28px;
    }
    .banner-image img {
        max-width: 250px;
        margin-top: 24px;
    }
    .categories-grid,
    .products-grid {
        grid-template-columns: repeat(2, 1fr);
    }
    .brands-grid {
        grid-template-columns: repeat(3, 1fr);
    }
    .features-grid {
        grid-template-columns: 1fr;
    }
}
</style>
@endpush

@push('scripts')
<script>
// Banner Slider
let currentSlide = 0;
const slides = document.querySelectorAll('.banner-slide');
const dots = document.querySelectorAll('.banner-dots .dot');

function goToSlide(index) {
    slides[currentSlide].classList.remove('active');
    dots[currentSlide].classList.remove('active');
    currentSlide = index;
    slides[currentSlide].classList.add('active');
    dots[currentSlide].classList.add('active');
}

// Auto slide
setInterval(() => {
    goToSlide((currentSlide + 1) % slides.length);
}, 5000);

// Countdown Timer
function updateCountdown() {
    const now = new Date();
    const endOfDay = new Date();
    endOfDay.setHours(23, 59, 59, 999);
    
    const diff = endOfDay - now;
    const hours = Math.floor(diff / (1000 * 60 * 60));
    const minutes = Math.floor((diff % (1000 * 60 * 60)) / (1000 * 60));
    const seconds = Math.floor((diff % (1000 * 60)) / 1000);
    
    document.getElementById('hours').textContent = String(hours).padStart(2, '0');
    document.getElementById('minutes').textContent = String(minutes).padStart(2, '0');
    document.getElementById('seconds').textContent = String(seconds).padStart(2, '0');
}

setInterval(updateCountdown, 1000);
updateCountdown();

// Load Categories
async function loadCategories() {
    try {
        const response = await fetch('/api/categories');
        const result = await response.json();
        
        // API returns { success: true, data: { data: [...categories] } }
        const categories = result.data?.data || result.data || [];
        
        const container = document.getElementById('categoriesGrid');
        if (!categories.length) {
            container.innerHTML = '<p>Chưa có danh mục</p>';
            return;
        }
        
        const getCategoryIconClass = (name) => {
            const nameLower = name.toLowerCase();
            if (nameLower.includes('iphone') || nameLower.includes('apple')) {
                return 'fab fa-apple';
            }
            if (nameLower.includes('samsung') || nameLower.includes('xiaomi') || nameLower.includes('oppo') || nameLower.includes('vivo') || nameLower.includes('realme') || nameLower.includes('điện thoại') || nameLower.includes('phone')) {
                return 'fas fa-mobile-alt';
            }
            if (nameLower.includes('phụ kiện') || nameLower.includes('tai nghe') || nameLower.includes('loa')) {
                return 'fas fa-headphones';
            }
            if (nameLower.includes('sạc') || nameLower.includes('cáp') || nameLower.includes('pin')) {
                return 'fas fa-charging-station';
            }
            if (nameLower.includes('ốp') || nameLower.includes('kính') || nameLower.includes('dán')) {
                return 'fas fa-shield-alt';
            }
            return 'fas fa-folder';
        };
        
        container.innerHTML = categories.slice(0, 5).map((category) => `
            <a href="/products?category=${category.id}" class="category-card">
                <div class="category-icon">
                    <i class="${getCategoryIconClass(category.name)}"></i>
                </div>
                <div class="category-name">${category.name}</div>
                <div class="category-count">${category.products_count || 0} sản phẩm</div>
            </a>
        `).join('');
    } catch (error) {
        console.error('Error loading categories:', error);
        document.getElementById('categoriesGrid').innerHTML = '<p style="color:#999;">Không thể tải danh mục</p>';
    }
}

// Load Products
async function loadProducts(endpoint, containerId, limit = 4) {
    try {
        const response = await fetch(`/api/products${endpoint}`);
        const result = await response.json();
        
        // API returns { success: true, data: { data: [...products] } }
        const products = result.data?.data || result.data || [];
        
        const container = document.getElementById(containerId);
        if (!products.length) {
            container.innerHTML = '<p style="text-align:center;color:#666;padding:40px;">Chưa có sản phẩm</p>';
            return;
        }
        
        container.innerHTML = products.slice(0, limit).map(product => `
            <div class="product-card">
                <div class="product-badges">
                    <span class="badge badge-installment"><i class="fas fa-bolt"></i> Trả góp 0%</span>
                    <span class="badge badge-gift"><i class="fas fa-gift"></i> Quà 2Tr</span>
                </div>
                <a href="/products/${product.id}" class="product-image">
                    <img src="${appendImageVersion(getProductImage(product), product.id)}" alt="${product.name}">
                </a>
                <div class="product-info">
                    <span class="product-category">${product.category?.name || 'Điện thoại'}</span>
                    <h3 class="product-name">
                        <a href="/products/${product.id}">${product.name}</a>
                    </h3>
                    <div class="product-rating">
                        ${generateStars(product.average_rating || 4.5)}
                        <span class="rating-count">(${product.reviews_count || Math.floor(Math.random() * 2000) + 500})</span>
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
                    <button class="btn btn-primary" onclick="addToCart(${product.id})">
                        <i class="fas fa-shopping-cart"></i> Mua ngay
                    </button>
                    <button class="btn-favorite" onclick="toggleFavorite(${product.id}, this)">
                        <i class="far fa-heart"></i>
                    </button>
                </div>
            </div>
        `).join('');
    } catch (error) {
        console.error('Error loading products:', error);
    }
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

// Helper Functions
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

// Add to cart function
function addToCart(productId) {
    const token = localStorage.getItem('auth_token') || localStorage.getItem('auth_token');
    
    if (!token) {
        // Lưu vào localStorage cho guest
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

// Toggle favorite function
function toggleFavorite(productId, button) {
    const token = localStorage.getItem('auth_token') || localStorage.getItem('auth_token');
    
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
        if (data.success) {
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

// Initialize
document.addEventListener('DOMContentLoaded', () => {
    loadCategories();
    // Load all products for now (API may not support featured/sale filters)
    loadProducts('', 'featuredProducts', 8);
    loadProducts('', 'flashSaleProducts', 4);
    loadProducts('', 'newProducts', 4);
    updateCartBadge();
});
</script>
@endpush

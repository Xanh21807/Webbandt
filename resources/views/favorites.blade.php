@extends('layouts.app')

@section('title', 'Sản phẩm yêu thích - XanhStore')

@section('content')
<div class="favorites-page">
    <div class="container">
        <div class="profile-layout">
            <!-- Sidebar -->
            @include('partials.profile-sidebar')

            <!-- Main Content -->
            <main class="profile-main">
                <div class="profile-section">
                    <div class="section-header">
                        <h2><i class="fas fa-heart" style="color: var(--primary);"></i> Sản phẩm yêu thích</h2>
                        <span id="favoriteCount" class="count-badge">0 sản phẩm</span>
                    </div>
                    
                    <div id="favoritesContainer">
                        <div class="loading">
                            <i class="fas fa-spinner fa-spin"></i>
                            <p>Đang tải...</p>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
.favorites-page {
    padding: 24px 0 60px;
    background: #f9fafb;
    min-height: 100vh;
}

.count-badge {
    font-size: 14px;
    color: var(--gray-500);
}

.favorites-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
    gap: 20px;
    align-items: start;
}

.favorite-item {
    background: white;
    border-radius: 12px;
    overflow: hidden;
    box-shadow: var(--shadow-sm);
    transition: all 0.3s;
    display: flex;
    flex-direction: column;
    height: 100%;
}

.favorite-item:hover {
    transform: translateY(-4px);
    box-shadow: var(--shadow-md);
}

.favorite-image {
    position: relative;
    height: 200px;
    background: #f9fafb;
}

.favorite-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.favorite-remove {
    position: absolute;
    top: 12px;
    right: 12px;
    width: 36px;
    height: 36px;
    background: white;
    border: none;
    border-radius: 50%;
    color: var(--primary);
    cursor: pointer;
    box-shadow: var(--shadow-sm);
    transition: all 0.2s;
}

.favorite-remove:hover {
    background: var(--primary);
    color: white;
}

.favorite-info {
    padding: 16px;
    display: flex;
    flex-direction: column;
    flex: 1;
}


.favorite-name {
    font-weight: 600;
    color: var(--gray-900);
    margin-bottom: 8px;
    display: block;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

.favorite-name:hover {
    color: var(--primary);
}

.favorite-price {
    display: flex;
    align-items: center;
    gap: 8px;
    margin-bottom: 12px;
}

.favorite-price .price-old {
    font-size: 13px;
    color: var(--gray-500);
    text-decoration: line-through;
}

.favorite-price .price-current {
    font-weight: 700;
    color: var(--primary);
    font-size: 18px;
}

.favorite-actions {
    display: flex;
    gap: 8px;
    margin-top: auto;
}

.favorite-actions .btn {
    flex: 1;
}

/* Empty State */
.empty-favorites {
    text-align: center;
    padding: 60px 20px;
}

.empty-favorites-icon {
    width: 120px;
    height: 120px;
    margin: 0 auto 24px;
    background: #fef2f2;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
}

.empty-favorites-icon i {
    font-size: 48px;
    color: #fca5a5;
}

.empty-favorites h3 {
    font-size: 20px;
    color: var(--gray-900);
    margin-bottom: 8px;
}

.empty-favorites p {
    color: var(--gray-500);
    margin-bottom: 24px;
}

/* Responsive */
@media (max-width: 1024px) {
    .favorites-grid {
        grid-template-columns: repeat(2, 1fr);
    }
}

@media (max-width: 768px) {
    .favorites-grid {
        grid-template-columns: 1fr;
    }
}
</style>
@endpush

@push('scripts')
<script>
let favorites = [];

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
    
    loadFavorites();
});

// Load favorites
async function loadFavorites() {
    const token = localStorage.getItem('auth_token');
    const container = document.getElementById('favoritesContainer');
    
    try {
        const response = await fetch('/api/favorites', {
            headers: {
                'Authorization': `Bearer ${token}`,
                'Accept': 'application/json'
            }
        });
        
        if (response.ok) {
            const data = await response.json();
            favorites = data.data?.favorites || data.data || [];
            
            document.getElementById('favoriteCount').textContent = `${favorites.length} sản phẩm`;
            
            if (favorites.length === 0) {
                container.innerHTML = `
                    <div class="empty-favorites">
                        <div class="empty-favorites-icon">
                            <i class="far fa-heart"></i>
                        </div>
                        <h3>Danh sách yêu thích trống</h3>
                        <p>Bạn chưa có sản phẩm yêu thích nào. Hãy khám phá và thêm sản phẩm bạn thích!</p>
                        <a href="/products" class="btn btn-primary">
                            <i class="fas fa-shopping-bag"></i> Khám phá sản phẩm
                        </a>
                    </div>
                `;
            } else {
                container.innerHTML = `
                    <div class="favorites-grid">
                        ${favorites.map(item => {
                            const product = item.product || item;
                            return `
                                <div class="favorite-item" data-id="${product.id}">
                                    <div class="favorite-image">
                                        <img src="${product.image || 'https://placehold.co/200x200/f5f5f5/333?text=No+Image'}" alt="${product.name}">
                                        <button class="favorite-remove" onclick="removeFavorite(${product.id})" title="Xóa khỏi yêu thích">
                                            <i class="fas fa-heart"></i>
                                        </button>
                                    </div>
                                    <div class="favorite-info">
                                        <a href="/products/${product.id}" class="favorite-name">${product.name}</a>
                                        <div class="favorite-price">
                                            ${product.sale_price && product.sale_price < product.price ? `
                                                <span class="price-old">${formatPrice(product.price)}₫</span>
                                                <span class="price-current">${formatPrice(product.sale_price)}₫</span>
                                            ` : `
                                                <span class="price-current">${formatPrice(product.price)}₫</span>
                                            `}
                                        </div>
                                        <div class="favorite-actions">
                                            <button class="btn btn-primary btn-sm" onclick="addToCart(${product.id})">
                                                <i class="fas fa-cart-plus"></i> Thêm vào giỏ
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            `;
                        }).join('')}
                    </div>
                `;
            }
        }
    } catch (error) {
        console.error('Error loading favorites:', error);
        container.innerHTML = '<p style="color: var(--gray-500); text-align: center;">Không thể tải danh sách yêu thích</p>';
    }
}

// Remove favorite
async function removeFavorite(productId) {
    const token = localStorage.getItem('auth_token');
    
    try {
        const response = await fetch(`/api/favorites/${productId}`, {
            method: 'DELETE',
            headers: {
                'Authorization': `Bearer ${token}`,
                'Accept': 'application/json'
            }
        });
        
        if (response.ok) {
            // Remove from DOM with animation
            const item = document.querySelector(`.favorite-item[data-id="${productId}"]`);
            if (item) {
                item.style.opacity = '0';
                item.style.transform = 'scale(0.8)';
                setTimeout(() => {
                    item.remove();
                    favorites = favorites.filter(f => (f.product?.id || f.id) !== productId);
                    document.getElementById('favoriteCount').textContent = `${favorites.length} sản phẩm`;
                    
                    if (favorites.length === 0) {
                        loadFavorites();
                    }
                }, 300);
            }
        }
    } catch (error) {
        console.error('Error removing favorite:', error);
        alert('Không thể xóa sản phẩm khỏi yêu thích');
    }
}
</script>
@endpush

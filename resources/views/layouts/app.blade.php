<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'XanhStore - Điện thoại chính hãng')</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Arimo:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    
    <!-- Styles -->
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    
    <style>
    .header-search {
        position: relative;
        flex: 1;
        max-width: 600px;
    }
    
    .search-dropdown {
        position: absolute;
        top: 100%;
        left: 0;
        right: 0;
        background: white;
        border-radius: 0 0 12px 12px;
        box-shadow: 0 10px 40px rgba(0, 0, 0, 0.15);
        max-height: 400px;
        overflow-y: auto;
        z-index: 1000;
        margin-top: 4px;
    }
    
    .search-loading {
        padding: 20px;
        text-align: center;
        color: var(--gray-500);
    }
    
    .search-empty {
        padding: 20px;
        text-align: center;
        color: var(--gray-500);
    }
    
    .search-results {
        padding: 8px 0;
    }
    
    .search-item {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 12px 16px;
        cursor: pointer;
        transition: background 0.2s;
        text-decoration: none;
        color: inherit;
    }
    
    .search-item:hover {
        background: #f9fafb;
    }
    
    .search-item-image {
        width: 50px;
        height: 50px;
        border-radius: 8px;
        overflow: hidden;
        background: #f9fafb;
        flex-shrink: 0;
    }
    
    .search-item-image img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
    
    .search-item-info {
        flex: 1;
        min-width: 0;
    }
    
    .search-item-name {
        font-weight: 600;
        color: var(--gray-900);
        margin-bottom: 4px;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }
    
    .search-item-price {
        color: var(--primary);
        font-weight: 700;
    }
    </style>
    
    @stack('styles')
</head>
<body>
    <!-- Header -->
    <header class="header">
        <!-- Top Bar -->
        <div class="header-top">
            <div class="container">
                <div class="header-top-content">
                    <div class="header-top-info">
                        <span>
                            <i class="fas fa-phone-alt"></i>
                            Gọi mua hàng: 1800.2097
                        </span>
                        <span>
                            <i class="fas fa-map-marker-alt"></i>
                            Hệ thống 120+ cửa hàng
                        </span>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Main Header -->
        <div class="header-main">
            <div class="container flex items-center justify-between">
                <!-- Logo -->
                <a href="{{ url('/') }}" class="header-logo">
                    <div class="header-logo-icon">
                        <i class="fas fa-mobile-alt"></i>
                    </div>
                    <div class="header-logo-text">
                        <h1>XanhStore</h1>
                        <span>Điện thoại chính hãng</span>
                    </div>
                </a>
                
                <!-- Search -->
                <div class="header-search">
                    <div class="header-search-input">
                        <i class="fas fa-search"></i>
                        <input type="text" id="searchInput" placeholder="Bạn cần tìm gì..." autocomplete="off">
                    </div>
                    <div class="search-dropdown" id="searchDropdown" style="display: none;">
                        <div class="search-loading" id="searchLoading" style="display: none;">
                            <i class="fas fa-spinner fa-spin"></i> Đang tìm kiếm...
                        </div>
                        <div class="search-results" id="searchResults"></div>
                        <div class="search-empty" id="searchEmpty" style="display: none;">
                            Không tìm thấy sản phẩm nào
                        </div>
                    </div>
                </div>
                
                <!-- Actions -->
                <div class="header-actions">
                    <!-- Wishlist -->
                    <a href="{{ url('/favorites') }}" class="header-btn" title="Yêu thích">
                        <i class="far fa-heart"></i>
                        <span class="header-wishlist-badge" id="wishlistCount">0</span>
                    </a>
                    
                    <!-- Cart -->
                    <a href="{{ url('/cart') }}" class="header-btn">
                        <i class="fas fa-shopping-cart"></i>
                        <span>Giỏ hàng</span>
                        <span class="header-cart-badge" id="cartCount">0</span>
                    </a>
                    
                    <!-- User Menu -->
                    <div class="dropdown" id="userDropdown">
                        <!-- Logged in state (hidden by default, shown via JS) -->
                        <div id="userLoggedIn" style="display: none;">
                            <button class="header-btn header-btn-outline" onclick="toggleDropdown()">
                                <i class="fas fa-user"></i>
                                <span id="userName">User</span>
                                <i class="fas fa-chevron-down"></i>
                            </button>
                            <div class="dropdown-menu">
                                <a href="{{ url('/profile') }}">
                                    <i class="fas fa-user"></i>
                                    Thông tin cá nhân
                                </a>
                                <a href="{{ url('/orders') }}">
                                    <i class="fas fa-box"></i>
                                    Đơn hàng của tôi
                                </a>
                                <a href="{{ url('/favorites') }}">
                                    <i class="fas fa-heart"></i>
                                    Sản phẩm yêu thích
                                </a>
                                <div class="dropdown-divider"></div>
                                <a href="#" onclick="logout(); return false;">
                                    <i class="fas fa-sign-out-alt"></i>
                                    Đăng xuất
                                </a>
                            </div>
                        </div>
                        
                        <!-- Not logged in state -->
                        <a href="{{ url('/login') }}" class="header-btn header-btn-outline" id="userNotLoggedIn">
                            <i class="fas fa-user"></i>
                            <span>Đăng nhập</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Navigation -->
        <nav class="header-nav">
            <div class="container">
                <div class="header-nav-content">
                    <div class="dropdown">
                        <button class="header-nav-btn">
                            <i class="fas fa-bars"></i>
                            📱 Danh mục
                            <i class="fas fa-chevron-down"></i>
                        </button>
                        <div class="dropdown-menu" style="top: 100%; left: 0;">
                            <a href="{{ url('/products?category=iphone') }}">
                                <i class="fab fa-apple"></i>
                                iPhone
                            </a>
                            <a href="{{ url('/products?category=samsung') }}">
                                <i class="fas fa-mobile-alt"></i>
                                Samsung
                            </a>
                            <a href="{{ url('/products?category=xiaomi') }}">
                                <i class="fas fa-mobile-alt"></i>
                                Xiaomi
                            </a>
                            <a href="{{ url('/products?category=oppo') }}">
                                <i class="fas fa-mobile-alt"></i>
                                OPPO
                            </a>
                            <a href="{{ url('/products?category=vivo') }}">
                                <i class="fas fa-mobile-alt"></i>
                                Vivo
                            </a>
                        </div>
                    </div>
                    <a href="{{ url('/products?sale=1') }}" class="header-nav-link">
                        🔥 Khuyến mãi hot
                    </a>
                    <a href="{{ url('/products?new=1') }}" class="header-nav-link">
                        ✨ Sản phẩm mới
                    </a>
                </div>
            </div>
        </nav>
    </header>

    <!-- Main Content -->
    <main>
        @if(session('success'))
            <div class="container mt-4">
                <div class="alert alert-success">
                    <i class="fas fa-check-circle"></i>
                    {{ session('success') }}
                </div>
            </div>
        @endif

        @if(session('error'))
            <div class="container mt-4">
                <div class="alert alert-error">
                    <i class="fas fa-exclamation-circle"></i>
                    {{ session('error') }}
                </div>
            </div>
        @endif

        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <div class="footer-content">
                <!-- Brand -->
                <div class="footer-brand">
                    <div class="footer-brand-logo">
                        <div class="footer-brand-logo-icon">
                            <i class="fas fa-mobile-alt"></i>
                        </div>
                        <span>XanhStore</span>
                    </div>
                    <p>Chuyên cung cấp điện thoại chính hãng, giá tốt nhất thị trường. Bảo hành toàn quốc, đổi trả trong 30 ngày.</p>
                    <div class="footer-social">
                        <a href="#"><i class="fab fa-facebook-f"></i></a>
                        <a href="#"><i class="fab fa-youtube"></i></a>
                        <a href="#"><i class="fab fa-tiktok"></i></a>
                    </div>
                </div>
                
                <!-- Support -->
                <div class="footer-column">
                    <h4>Hỗ trợ khách hàng</h4>
                    <ul>
                        <li><a href="#">Hướng dẫn mua hàng</a></li>
                        <li><a href="#">Chính sách bảo hành</a></li>
                        <li><a href="#">Chính sách đổi trả</a></li>
                        <li><a href="#">Phương thức thanh toán</a></li>
                        <li><a href="#">Câu hỏi thường gặp</a></li>
                    </ul>
                </div>
                
                <!-- Categories -->
                <div class="footer-column">
                    <h4>Danh mục sản phẩm</h4>
                    <ul>
                        <li><a href="{{ url('/products?category=iphone') }}">iPhone</a></li>
                        <li><a href="{{ url('/products?category=samsung') }}">Samsung</a></li>
                        <li><a href="{{ url('/products?category=xiaomi') }}">Xiaomi</a></li>
                        <li><a href="{{ url('/products?category=oppo') }}">OPPO</a></li>
                        <li><a href="{{ url('/products?category=accessories') }}">Phụ kiện</a></li>
                    </ul>
                </div>
                
                <!-- Contact -->
                <div class="footer-column">
                    <h4>Liên hệ</h4>
                    <ul class="footer-contact">
                        <li>
                            <i class="fas fa-map-marker-alt"></i>
                            <span>123 Đường ABC, Quận 1, TP. Hồ Chí Minh</span>
                        </li>
                        <li>
                            <i class="fas fa-phone-alt"></i>
                            <a href="tel:19001234">1900 1234</a>
                        </li>
                        <li>
                            <i class="fas fa-envelope"></i>
                            <a href="mailto:support@phonestore.vn">support@phonestore.vn</a>
                        </li>
                    </ul>
                </div>
            </div>
            
            <div class="footer-bottom">
                <p>© 2024 PhoneStore. Tất cả quyền được bảo lưu.</p>
                <div class="footer-bottom-links">
                    <a href="#">Điều khoản sử dụng</a>
                    <a href="#">Chính sách bảo mật</a>
                    <a href="#">Sitemap</a>
                </div>
            </div>
        </div>
    </footer>

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}"></script>
    <script>
        // Check auth state and update UI
        function checkAuthState() {
            const token = localStorage.getItem('auth_token');
            const userStr = localStorage.getItem('user');
            
            const loggedInDiv = document.getElementById('userLoggedIn');
            const notLoggedInDiv = document.getElementById('userNotLoggedIn');
            const userNameSpan = document.getElementById('userName');
            
            if (token && userStr) {
                try {
                    const user = JSON.parse(userStr);
                    if (loggedInDiv) loggedInDiv.style.display = 'block';
                    if (notLoggedInDiv) notLoggedInDiv.style.display = 'none';
                    if (userNameSpan) userNameSpan.textContent = user.name || 'User';
                } catch (e) {
                    if (loggedInDiv) loggedInDiv.style.display = 'none';
                    if (notLoggedInDiv) notLoggedInDiv.style.display = 'block';
                }
            } else {
                if (loggedInDiv) loggedInDiv.style.display = 'none';
                if (notLoggedInDiv) notLoggedInDiv.style.display = 'block';
            }
        }
        
        // Logout function
        function logout() {
            const token = localStorage.getItem('auth_token');
            
            if (token) {
                fetch('/api/logout', {
                    method: 'POST',
                    headers: {
                        'Authorization': `Bearer ${token}`,
                        'Accept': 'application/json'
                    }
                }).finally(() => {
                    // Xóa tất cả localStorage để tránh conflict giữa các user
                    localStorage.clear();
                    window.location.href = '/';
                });
            } else {
                // Xóa tất cả localStorage
                localStorage.clear();
                window.location.href = '/';
            }
        }
        
        // Toggle dropdown
        function toggleDropdown() {
            const dropdown = document.querySelector('#userDropdown .dropdown-menu');
            if (dropdown) {
                dropdown.classList.toggle('show');
            }
        }
        
        // Close dropdown when clicking outside
        document.addEventListener('click', function(e) {
            if (!e.target.closest('#userDropdown')) {
                const dropdown = document.querySelector('#userDropdown .dropdown-menu');
                if (dropdown) dropdown.classList.remove('show');
            }
        });
        
        // Run on page load
        document.addEventListener('DOMContentLoaded', function() {
            checkAuthState();
            updateHeaderBadges();
        });
        
        // Update cart and wishlist badges in header
        function updateHeaderBadges() {
            const token = localStorage.getItem('auth_token');
            console.log('updateHeaderBadges called, token:', token ? 'exists' : 'none');
            
            // Update cart badge
            const cartBadge = document.getElementById('cartCount');
            console.log('cartBadge element:', cartBadge);
            if (cartBadge) {
                if (token) {
                    fetch('/api/cart', {
                        headers: {
                            'Authorization': `Bearer ${token}`,
                            'Accept': 'application/json'
                        }
                    })
                    .then(res => res.json())
                    .then(data => {
                        console.log('Cart badge API response:', data);
                        const items = data.data || [];
                        const count = Array.isArray(items) ? items.reduce((sum, item) => sum + (item.quantity || 1), 0) : 0;
                        console.log('Cart badge count:', count);
                        cartBadge.textContent = count;
                        cartBadge.style.display = count > 0 ? 'flex' : 'none';
                    })
                    .catch((err) => {
                        console.error('Cart badge error:', err);
                        cartBadge.style.display = 'none';
                    });
                } else {
                    const guestCart = JSON.parse(localStorage.getItem('guest_cart') || '[]');
                    const count = guestCart.reduce((sum, item) => sum + (item.quantity || 1), 0);
                    cartBadge.textContent = count;
                    cartBadge.style.display = count > 0 ? 'flex' : 'none';
                }
            }
            
            // Update wishlist badge
            const wishlistBadge = document.getElementById('wishlistCount');
            if (wishlistBadge) {
                if (token) {
                    fetch('/api/favorites', {
                        headers: {
                            'Authorization': `Bearer ${token}`,
                            'Accept': 'application/json'
                        }
                    })
                    .then(res => res.json())
                    .then(data => {
                        // API trả về data.favorites hoặc data array
                        let favorites = [];
                        if (data.data && data.data.favorites) {
                            favorites = data.data.favorites;
                        } else if (Array.isArray(data.data)) {
                            favorites = data.data;
                        }
                        const count = favorites.length;
                        wishlistBadge.textContent = count;
                        wishlistBadge.style.display = count > 0 ? 'flex' : 'none';
                    })
                    .catch(() => {
                        wishlistBadge.style.display = 'none';
                    });
                } else {
                    const guestFavorites = JSON.parse(localStorage.getItem('guest_favorites') || '[]');
                    const count = guestFavorites.length;
                    wishlistBadge.textContent = count;
                    wishlistBadge.style.display = count > 0 ? 'flex' : 'none';
                }
            }
        }
        
        // Global function to update cart badge (called from other pages)
        window.updateCartBadge = function() {
            updateHeaderBadges();
        };
        
        // Live search functionality
        let searchTimeout;
        const searchInput = document.getElementById('searchInput');
        const searchDropdown = document.getElementById('searchDropdown');
        const searchLoading = document.getElementById('searchLoading');
        const searchResults = document.getElementById('searchResults');
        const searchEmpty = document.getElementById('searchEmpty');
        
        if (searchInput) {
            // Handle input
            searchInput.addEventListener('input', function() {
                const query = this.value.trim();
                
                clearTimeout(searchTimeout);
                
                if (query.length < 2) {
                    searchDropdown.style.display = 'none';
                    return;
                }
                
                // Show loading
                searchDropdown.style.display = 'block';
                searchLoading.style.display = 'block';
                searchResults.innerHTML = '';
                searchEmpty.style.display = 'none';
                
                // Delay search
                searchTimeout = setTimeout(() => {
                    performSearch(query);
                }, 300);
            });
            
            // Handle Enter key
            searchInput.addEventListener('keypress', function(e) {
                if (e.key === 'Enter') {
                    const query = this.value.trim();
                    if (query) {
                        searchDropdown.style.display = 'none';
                        window.location.href = `/products?search=${encodeURIComponent(query)}`;
                    }
                }
            });
            
            // Handle focus
            searchInput.addEventListener('focus', function() {
                if (this.value.trim().length >= 2 && searchResults.innerHTML) {
                    searchDropdown.style.display = 'block';
                }
            });
        }
        
        // Perform search API call
        async function performSearch(query) {
            try {
                const response = await fetch(`/api/products?search=${encodeURIComponent(query)}&limit=6`);
                const data = await response.json();
                
                searchLoading.style.display = 'none';
                
                if (data.data && data.data.data && data.data.data.length > 0) {
                    displaySearchResults(data.data.data);
                } else {
                    searchResults.innerHTML = '';
                    searchEmpty.style.display = 'block';
                }
            } catch (error) {
                console.error('Search error:', error);
                searchLoading.style.display = 'none';
                searchResults.innerHTML = '';
                searchEmpty.style.display = 'block';
            }
        }
        
        // Display search results
        function displaySearchResults(products) {
            searchResults.innerHTML = products.map(product => `
                <a href="/products/${product.id}" class="search-item">
                    <div class="search-item-image">
                        <img src="${product.image || 'https://placehold.co/50x50/f5f5f5/333?text=No+Image'}" alt="${product.name}">
                    </div>
                    <div class="search-item-info">
                        <div class="search-item-name">${product.name}</div>
                        <div class="search-item-price">${formatPrice(product.price)}₫</div>
                    </div>
                </a>
            `).join('');
            searchEmpty.style.display = 'none';
        }
        
        // Format price helper
        function formatPrice(price) {
            return new Intl.NumberFormat('vi-VN').format(price);
        }
        
        // Close search dropdown when clicking outside
        document.addEventListener('click', function(e) {
            if (!e.target.closest('.header-search')) {
                if (searchDropdown) {
                    searchDropdown.style.display = 'none';
                }
            }
        });
    </script>
    @stack('scripts')
</body>
</html>

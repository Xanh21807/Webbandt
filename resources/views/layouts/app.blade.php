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

    .chatbot-widget {
        position: fixed;
        right: 24px;
        bottom: 24px;
        z-index: 9999;
        font-family: var(--font-family);
    }

    .chatbot-toggle {
        display: inline-flex;
        align-items: center;
        gap: 10px;
        border: none;
        border-radius: 999px;
        background: linear-gradient(135deg, var(--primary), #ff4d5f);
        color: white;
        padding: 14px 18px;
        box-shadow: 0 18px 40px rgba(215, 0, 24, 0.28);
        cursor: pointer;
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }

    .chatbot-toggle:hover {
        transform: translateY(-2px);
        box-shadow: 0 20px 48px rgba(215, 0, 24, 0.34);
    }

    .chatbot-toggle-icon {
        width: 34px;
        height: 34px;
        border-radius: 50%;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        background: rgba(255, 255, 255, 0.16);
        flex-shrink: 0;
    }

    .chatbot-toggle-label {
        font-weight: 700;
        letter-spacing: 0.01em;
    }

    .chatbot-toggle-pulse {
        width: 10px;
        height: 10px;
        border-radius: 50%;
        background: #34d399;
        box-shadow: 0 0 0 0 rgba(52, 211, 153, 0.45);
        animation: chatbotPulse 2s infinite;
    }

    .chatbot-panel {
        position: absolute;
        right: 0;
        bottom: 72px;
        width: 360px;
        max-width: calc(100vw - 32px);
        height: 520px;
        background: rgba(255, 255, 255, 0.96);
        border: 1px solid rgba(215, 0, 24, 0.1);
        border-radius: 24px;
        box-shadow: 0 24px 60px rgba(15, 23, 42, 0.22);
        overflow: hidden;
        display: flex;
        flex-direction: column;
        opacity: 0;
        transform: translateY(16px) scale(0.98);
        pointer-events: none;
        transition: opacity 0.2s ease, transform 0.2s ease;
        backdrop-filter: blur(18px);
    }

    .chatbot-panel.is-open {
        opacity: 1;
        transform: translateY(0) scale(1);
        pointer-events: auto;
    }

    .chatbot-header {
        padding: 18px 18px 16px;
        background: linear-gradient(135deg, #101828, #1f2937 65%, #374151);
        color: white;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 12px;
    }

    .chatbot-title {
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .chatbot-avatar {
        width: 42px;
        height: 42px;
        border-radius: 14px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        background: linear-gradient(135deg, rgba(255, 255, 255, 0.18), rgba(255, 255, 255, 0.05));
    }

    .chatbot-title h3 {
        margin: 0;
        font-size: 16px;
        font-weight: 700;
    }

    .chatbot-title p {
        margin: 3px 0 0;
        font-size: 12px;
        color: rgba(255, 255, 255, 0.72);
    }

    .chatbot-close {
        width: 34px;
        height: 34px;
        border-radius: 50%;
        border: none;
        color: white;
        background: rgba(255, 255, 255, 0.12);
        cursor: pointer;
    }

    .chatbot-body {
        flex: 1;
        display: flex;
        flex-direction: column;
        min-height: 0;
        background: linear-gradient(180deg, #fff 0%, #fff7f8 100%);
    }

    .chatbot-messages {
        flex: 1;
        overflow-y: auto;
        padding: 18px;
        display: flex;
        flex-direction: column;
        gap: 12px;
    }

    .chatbot-message {
        display: flex;
        flex-direction: column;
        gap: 8px;
        max-width: 85%;
    }

    .chatbot-message.bot {
        align-self: flex-start;
    }

    .chatbot-message.user {
        align-self: flex-end;
    }

    .chatbot-bubble {
        padding: 12px 14px;
        border-radius: 18px;
        font-size: 14px;
        line-height: 1.5;
        white-space: pre-wrap;
        word-break: break-word;
    }

    .chatbot-message.bot .chatbot-bubble {
        background: white;
        color: var(--gray-800);
        border: 1px solid var(--gray-200);
        border-top-left-radius: 8px;
        box-shadow: 0 8px 24px rgba(15, 23, 42, 0.06);
    }

    .chatbot-message.user .chatbot-bubble {
        background: linear-gradient(135deg, var(--primary), #ff4d5f);
        color: white;
        border-bottom-right-radius: 8px;
        box-shadow: 0 12px 24px rgba(215, 0, 24, 0.2);
    }

    .chatbot-suggestions {
        display: flex;
        flex-wrap: wrap;
        gap: 8px;
    }

    .chatbot-chip {
        border: 1px solid rgba(215, 0, 24, 0.14);
        background: rgba(215, 0, 24, 0.06);
        color: var(--primary);
        border-radius: 999px;
        padding: 8px 12px;
        font-size: 12px;
        font-weight: 600;
        cursor: pointer;
        transition: background 0.2s ease, transform 0.2s ease;
    }

    .chatbot-chip:hover {
        background: rgba(215, 0, 24, 0.12);
        transform: translateY(-1px);
    }

    .chatbot-typing {
        display: none;
        align-items: center;
        gap: 6px;
        color: var(--gray-500);
        font-size: 12px;
        padding: 0 18px 10px;
    }

    .chatbot-typing span {
        width: 7px;
        height: 7px;
        border-radius: 50%;
        background: var(--primary);
        animation: chatbotTyping 1.1s infinite ease-in-out;
    }

    .chatbot-typing span:nth-child(2) {
        animation-delay: 0.15s;
    }

    .chatbot-typing span:nth-child(3) {
        animation-delay: 0.3s;
    }

    .chatbot-footer {
        padding: 14px;
        border-top: 1px solid var(--gray-200);
        background: white;
    }

    .chatbot-form {
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .chatbot-input {
        flex: 1;
        height: 44px;
        border: 1px solid var(--gray-200);
        border-radius: 14px;
        padding: 0 14px;
        font-size: 14px;
        outline: none;
        background: var(--gray-50);
    }

    .chatbot-input:focus {
        border-color: rgba(215, 0, 24, 0.35);
        background: white;
        box-shadow: 0 0 0 4px rgba(215, 0, 24, 0.08);
    }

    .chatbot-send {
        width: 44px;
        height: 44px;
        border: none;
        border-radius: 14px;
        background: linear-gradient(135deg, var(--primary), #ff4d5f);
        color: white;
        cursor: pointer;
        flex-shrink: 0;
        box-shadow: 0 10px 24px rgba(215, 0, 24, 0.22);
    }

    @keyframes chatbotPulse {
        0% {
            box-shadow: 0 0 0 0 rgba(52, 211, 153, 0.42);
        }
        70% {
            box-shadow: 0 0 0 12px rgba(52, 211, 153, 0);
        }
        100% {
            box-shadow: 0 0 0 0 rgba(52, 211, 153, 0);
        }
    }

    @keyframes chatbotTyping {
        0%, 80%, 100% {
            transform: translateY(0);
            opacity: 0.5;
        }
        40% {
            transform: translateY(-4px);
            opacity: 1;
        }
    }

    @media (max-width: 640px) {
        .chatbot-widget {
            right: 12px;
            bottom: 12px;
        }

        .chatbot-toggle-label {
            display: none;
        }

        .chatbot-panel {
            right: 0;
            bottom: 68px;
            width: min(360px, calc(100vw - 24px));
            height: min(70vh, 520px);
        }

        .chatbot-message {
            max-width: 92%;
        }
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
                    <img src="{{ asset('images/logo.png') }}" alt="XanhStore" style="height: 48px; width: auto; object-fit: contain;">
                </a>
                
                <!-- Search -->
                <div class="header-search">
                    <div class="header-search-input">
                        <i class="fas fa-search"></i>
                        <input type="text" id="searchInput" placeholder="Bạn cần tìm gì..." autocomplete="off">
                        <a href="{{ url('/products') }}" class="advanced-search-btn" title="Tìm kiếm nâng cao" id="advancedSearchBtn">
                            <i class="fas fa-sliders-h"></i>
                        </a>
                    </div>
                    <div class="search-dropdown" id="searchDropdown" style="display: none;">
                        <div class="search-loading" id="searchLoading" style="display: none;">
                            <i class="fas fa-spinner fa-spin"></i> Đang tìm kiếm...
                        </div>
                        <div class="search-results" id="searchResults"></div>
                        <div class="search-empty" id="searchEmpty" style="display: none;">
                            Không tìm thấy sản phẩm nào
                        </div>
                        <div class="search-dropdown-footer">
                            <a href="{{ url('/products') }}" id="advancedSearchDropdownLink">
                                <i class="fas fa-sliders-h"></i> Tìm kiếm nâng cao (Lọc chi tiết)
                            </a>
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
                    <div class="dropdown category-dropdown">
                        <button class="header-nav-btn">
                            <i class="fas fa-bars"></i>
                            Danh mục
                            <i class="fas fa-chevron-down"></i>
                        </button>
                        <div class="dropdown-menu category-menu" style="top: 100%; left: 0;">
                            <!-- iPhone -->
                            <div class="category-item-wrapper">
                                <a href="{{ url('/products?category=iphone') }}" class="category-menu-item">
                                    <span class="item-left">
                                        <i class="fab fa-apple"></i>
                                        <span>iPhone</span>
                                    </span>
                                    <i class="fas fa-chevron-right chevron-right"></i>
                                </a>
                                <div class="category-submenu">
                                    <div class="submenu-column">
                                        <h4>Dòng sản phẩm</h4>
                                        <a href="{{ url('/products?search=iPhone+16') }}">iPhone 16 Series</a>
                                        <a href="{{ url('/products?search=iPhone+15') }}">iPhone 15 Series</a>
                                        <a href="{{ url('/products?search=iPhone+14') }}">iPhone 14 Series</a>
                                        <a href="{{ url('/products?search=iPhone+13') }}">iPhone 13 Series</a>
                                    </div>
                                    <div class="submenu-column">
                                        <h4>Chọn theo mức giá</h4>
                                        <a href="{{ url('/products?category_id=1&price_range=under_10') }}">Dưới 10 triệu</a>
                                        <a href="{{ url('/products?category_id=1&price_range=10_20') }}">10 - 20 triệu</a>
                                        <a href="{{ url('/products?category_id=1&price_range=20_30') }}">20 - 30 triệu</a>
                                        <a href="{{ url('/products?category_id=1&price_range=over_30') }}">Trên 30 triệu</a>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Samsung -->
                            <div class="category-item-wrapper">
                                <a href="{{ url('/products?category=samsung') }}" class="category-menu-item">
                                    <span class="item-left">
                                        <i class="fas fa-mobile-alt"></i>
                                        <span>Samsung</span>
                                    </span>
                                    <i class="fas fa-chevron-right chevron-right"></i>
                                </a>
                                <div class="category-submenu">
                                    <div class="submenu-column">
                                        <h4>Dòng sản phẩm</h4>
                                        <a href="{{ url('/products?search=Galaxy+S') }}">Galaxy S Series</a>
                                        <a href="{{ url('/products?search=Galaxy+Z') }}">Galaxy Z Fold / Flip</a>
                                        <a href="{{ url('/products?search=Galaxy+A') }}">Galaxy A Series</a>
                                        <a href="{{ url('/products?search=Galaxy+M') }}">Galaxy M Series</a>
                                    </div>
                                    <div class="submenu-column">
                                        <h4>Chọn theo mức giá</h4>
                                        <a href="{{ url('/products?category_id=2&price_range=under_5') }}">Dưới 5 triệu</a>
                                        <a href="{{ url('/products?category_id=2&price_range=5_10') }}">5 - 10 triệu</a>
                                        <a href="{{ url('/products?category_id=2&price_range=10_20') }}">10 - 20 triệu</a>
                                        <a href="{{ url('/products?category_id=2&price_range=over_20') }}">Trên 20 triệu</a>
                                    </div>
                                </div>
                            </div>

                            <!-- Xiaomi -->
                            <div class="category-item-wrapper">
                                <a href="{{ url('/products?category=xiaomi') }}" class="category-menu-item">
                                    <span class="item-left">
                                        <i class="fas fa-mobile-alt"></i>
                                        <span>Xiaomi</span>
                                    </span>
                                    <i class="fas fa-chevron-right chevron-right"></i>
                                </a>
                                <div class="category-submenu">
                                    <div class="submenu-column">
                                        <h4>Dòng sản phẩm</h4>
                                        <a href="{{ url('/products?search=Xiaomi+14') }}">Xiaomi Series</a>
                                        <a href="{{ url('/products?search=Redmi+Note') }}">Redmi Note Series</a>
                                        <a href="{{ url('/products?search=POCO') }}">POCO Series</a>
                                        <a href="{{ url('/products?search=Redmi+13') }}">Redmi Series</a>
                                    </div>
                                    <div class="submenu-column">
                                        <h4>Chọn theo mức giá</h4>
                                        <a href="{{ url('/products?category_id=3&price_range=under_5') }}">Dưới 5 triệu</a>
                                        <a href="{{ url('/products?category_id=3&price_range=5_10') }}">5 - 10 triệu</a>
                                        <a href="{{ url('/products?category_id=3&price_range=10_20') }}">10 - 20 triệu</a>
                                        <a href="{{ url('/products?category_id=3&price_range=over_20') }}">Trên 20 triệu</a>
                                    </div>
                                </div>
                            </div>

                            <!-- OPPO -->
                            <div class="category-item-wrapper">
                                <a href="{{ url('/products?category=oppo') }}" class="category-menu-item">
                                    <span class="item-left">
                                        <i class="fas fa-mobile-alt"></i>
                                        <span>OPPO</span>
                                    </span>
                                    <i class="fas fa-chevron-right chevron-right"></i>
                                </a>
                                <div class="category-submenu">
                                    <div class="submenu-column">
                                        <h4>Dòng sản phẩm</h4>
                                        <a href="{{ url('/products?search=Find') }}">Find Series (Gập)</a>
                                        <a href="{{ url('/products?search=Reno') }}">Reno Series</a>
                                        <a href="{{ url('/products?search=OPPO+A') }}">A Series</a>
                                    </div>
                                    <div class="submenu-column">
                                        <h4>Chọn theo mức giá</h4>
                                        <a href="{{ url('/products?category_id=4&price_range=under_5') }}">Dưới 5 triệu</a>
                                        <a href="{{ url('/products?category_id=4&price_range=5_10') }}">5 - 10 triệu</a>
                                        <a href="{{ url('/products?category_id=4&price_range=10_20') }}">10 - 20 triệu</a>
                                        <a href="{{ url('/products?category_id=4&price_range=over_20') }}">Trên 20 triệu</a>
                                    </div>
                                </div>
                            </div>

                            <!-- Vivo -->
                            <div class="category-item-wrapper">
                                <a href="{{ url('/products?category=vivo') }}" class="category-menu-item">
                                    <span class="item-left">
                                        <i class="fas fa-mobile-alt"></i>
                                        <span>Vivo</span>
                                    </span>
                                    <i class="fas fa-chevron-right chevron-right"></i>
                                </a>
                                <div class="category-submenu">
                                    <div class="submenu-column">
                                        <h4>Dòng sản phẩm</h4>
                                        <a href="{{ url('/products?search=Vivo+X') }}">X Series (Cao cấp)</a>
                                        <a href="{{ url('/products?search=Vivo+V') }}">V Series</a>
                                        <a href="{{ url('/products?search=Vivo+Y') }}">Y Series</a>
                                    </div>
                                    <div class="submenu-column">
                                        <h4>Chọn theo mức giá</h4>
                                        <a href="{{ url('/products?category_id=5&price_range=under_5') }}">Dưới 5 triệu</a>
                                        <a href="{{ url('/products?category_id=5&price_range=5_10') }}">5 - 10 triệu</a>
                                        <a href="{{ url('/products?category_id=5&price_range=10_20') }}">10 - 20 triệu</a>
                                        <a href="{{ url('/products?category_id=5&price_range=over_20') }}">Trên 20 triệu</a>
                                    </div>
                                </div>
                            </div>

                            <!-- Accessories -->
                            <div class="category-item-wrapper">
                                <a href="{{ url('/products?category=phu-kien') }}" class="category-menu-item">
                                    <span class="item-left">
                                        <i class="fas fa-headphones"></i>
                                        <span>Phụ kiện</span>
                                    </span>
                                    <i class="fas fa-chevron-right chevron-right"></i>
                                </a>
                                <div class="category-submenu">
                                    <div class="submenu-column">
                                        <h4>Ốp lưng & Miếng dán</h4>
                                        <a href="{{ url('/products?category_id=6') }}">Ốp lưng chống sốc</a>
                                        <a href="{{ url('/products?category_id=10') }}">Kính cường lực</a>
                                        <a href="{{ url('/products?category_id=11') }}">Giá đỡ & Gimbal</a>
                                    </div>
                                    <div class="submenu-column">
                                        <h4>Sạc & Tai nghe</h4>
                                        <a href="{{ url('/products?category_id=7') }}">Cáp sạc, Củ sạc nhanh</a>
                                        <a href="{{ url('/products?category_id=8') }}">Tai nghe Bluetooth</a>
                                        <a href="{{ url('/products?category_id=9') }}">Sạc dự phòng</a>
                                    </div>
                                </div>
                            </div>
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
                        <img src="{{ asset('images/logo.png') }}" alt="XanhStore" style="height: 40px; width: auto; object-fit: contain;">
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

    <!-- Chatbot Widget -->
    <div class="chatbot-widget" id="chatbotWidget">
        <div class="chatbot-panel" id="chatbotPanel" aria-hidden="true">
            <div class="chatbot-header">
                <div class="chatbot-title">
                    <div class="chatbot-avatar">
                        <i class="fas fa-robot"></i>
                    </div>
                    <div>
                        <h3>XanhStore Assistant</h3>
                        <p>Tư vấn nhanh 24/7</p>
                    </div>
                </div>
                <button type="button" class="chatbot-close" id="chatbotClose" aria-label="Đóng chat">
                    <i class="fas fa-times"></i>
                </button>
            </div>

            <div class="chatbot-body">
                <div class="chatbot-messages" id="chatbotMessages"></div>
                <div class="chatbot-typing" id="chatbotTyping" aria-live="polite">
                    <span></span>
                    <span></span>
                    <span></span>
                    <span>Đang trả lời...</span>
                </div>
                <div class="chatbot-footer">
                    <form class="chatbot-form" id="chatbotForm">
                        <input type="text" class="chatbot-input" id="chatbotInput" placeholder="Nhắn cho mình câu hỏi của bạn..." autocomplete="off">
                        <button type="submit" class="chatbot-send" aria-label="Gửi tin nhắn">
                            <i class="fas fa-paper-plane"></i>
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <button type="button" class="chatbot-toggle" id="chatbotToggle" aria-controls="chatbotPanel" aria-expanded="false">
            <span class="chatbot-toggle-icon">
                <i class="fas fa-headset"></i>
            </span>
            <span class="chatbot-toggle-label">Chat tư vấn</span>
            <span class="chatbot-toggle-pulse" aria-hidden="true"></span>
        </button>
    </div>

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}"></script>
    <script>
        // ====== Global fetch interceptor: tự đăng xuất khi token hết hạn hoặc bị khóa ======
        (function () {
            const _originalFetch = window.fetch;
            window.fetch = async function (...args) {
                const response = await _originalFetch(...args);

                if (response.status === 401 || response.status === 403) {
                    // Clone để đọc body mà không tiêu thụ response
                    const clone = response.clone();
                    try {
                        const data = await clone.json();
                        const isBlocked = data.code === 'ACCOUNT_BLOCKED';
                        const isUnauthorized = response.status === 401;

                        if (isBlocked || isUnauthorized) {
                            const hadToken = !!localStorage.getItem('auth_token');
                            if (hadToken) {
                                localStorage.clear();
                                // Truyền lý do qua URL param để login page hiển thị popup
                                const reason = isBlocked ? 'blocked' : 'expired';
                                window.location.href = `/login?reason=${reason}`;
                            }
                        }
                    } catch (_) {}
                }

                return response;
            };
        })();

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
                
                // Update advanced search links dynamically
                const advancedSearchBtn = document.getElementById('advancedSearchBtn');
                const advancedSearchDropdownLink = document.getElementById('advancedSearchDropdownLink');
                const targetUrl = query ? `/products?search=${encodeURIComponent(query)}` : '/products';
                
                if (advancedSearchBtn) advancedSearchBtn.setAttribute('href', targetUrl);
                if (advancedSearchDropdownLink) advancedSearchDropdownLink.setAttribute('href', targetUrl);
                
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
        
        // Get product image url helper
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

        // Display search results
        function displaySearchResults(products) {
            searchResults.innerHTML = products.map(product => `
                <a href="/products/${product.id}" class="search-item">
                    <div class="search-item-image">
                        <img src="${getProductImage(product)}" alt="${product.name}">
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
    <script>
        (function () {
            const widget = document.getElementById('chatbotWidget');

            if (!widget) {
                return;
            }

            const apiUrl = '/api/chat';
            const storageKey = 'xanhstore_chatbot_history';
            const sessionKey = 'xanhstore_chatbot_session_id';
            const toggleButton = document.getElementById('chatbotToggle');
            const panel = document.getElementById('chatbotPanel');
            const closeButton = document.getElementById('chatbotClose');
            const messagesEl = document.getElementById('chatbotMessages');
            const typingEl = document.getElementById('chatbotTyping');
            const form = document.getElementById('chatbotForm');
            const input = document.getElementById('chatbotInput');

            const welcomeMessage = {
                role: 'bot',
                text: 'Chào bạn, mình là trợ lý XanhStore. Bạn cần tư vấn sản phẩm, khuyến mãi, bảo hành hay đổi trả?'
            };

            const state = {
                messages: loadHistory()
            };

            if (!state.messages.length) {
                state.messages.push(welcomeMessage);
            }

            renderMessages();
            bindEvents();
            persistHistory();

            function bindEvents() {
                toggleButton.addEventListener('click', openPanel);
                closeButton.addEventListener('click', closePanel);

                form.addEventListener('submit', function (event) {
                    event.preventDefault();
                    const value = input.value.trim();
                    if (!value) {
                        return;
                    }
                    sendMessage(value);
                });

                input.addEventListener('keydown', function (event) {
                    if (event.key === 'Escape') {
                        closePanel();
                    }
                });

                document.addEventListener('click', function (event) {
                    if (!widget.contains(event.target) && panel.classList.contains('is-open')) {
                        closePanel();
                    }
                });
            }

            function openPanel() {
                panel.classList.add('is-open');
                panel.setAttribute('aria-hidden', 'false');
                toggleButton.setAttribute('aria-expanded', 'true');
                setTimeout(() => input.focus(), 50);
            }

            function closePanel() {
                panel.classList.remove('is-open');
                panel.setAttribute('aria-hidden', 'true');
                toggleButton.setAttribute('aria-expanded', 'false');
            }

            function loadHistory() {
                try {
                    const stored = JSON.parse(localStorage.getItem(storageKey) || '[]');
                    return Array.isArray(stored) ? stored : [];
                } catch (error) {
                    return [];
                }
            }

            function getSessionId() {
                let sessionId = localStorage.getItem(sessionKey);

                if (!sessionId) {
                    sessionId = typeof crypto !== 'undefined' && crypto.randomUUID
                        ? crypto.randomUUID()
                        : `chat-${Date.now()}-${Math.random().toString(16).slice(2)}`;
                    localStorage.setItem(sessionKey, sessionId);
                }

                return sessionId;
            }

            function persistHistory() {
                localStorage.setItem(storageKey, JSON.stringify(state.messages.slice(-20)));
            }

            function renderMessages() {
                messagesEl.innerHTML = '';

                state.messages.forEach((message) => {
                    appendMessage(message.role, message.text, message.suggestions || [], false);
                });

                scrollToBottom();
            }

            function appendMessage(role, text, suggestions = [], save = true) {
                const messageWrapper = document.createElement('div');
                messageWrapper.className = `chatbot-message ${role}`;

                const bubble = document.createElement('div');
                bubble.className = 'chatbot-bubble';
                bubble.textContent = text;
                messageWrapper.appendChild(bubble);

                if (Array.isArray(suggestions) && suggestions.length) {
                    const suggestionWrap = document.createElement('div');
                    suggestionWrap.className = 'chatbot-suggestions';

                    suggestions.forEach((suggestion) => {
                        const chip = document.createElement('button');
                        chip.type = 'button';
                        chip.className = 'chatbot-chip';
                        chip.textContent = suggestion.label;

                        chip.addEventListener('click', () => {
                            if (suggestion.type === 'link') {
                                window.location.href = suggestion.value;
                                return;
                            }

                            sendMessage(suggestion.value);
                        });

                        suggestionWrap.appendChild(chip);
                    });

                    messageWrapper.appendChild(suggestionWrap);
                }

                messagesEl.appendChild(messageWrapper);
                scrollToBottom();

                if (save) {
                    state.messages.push({ role, text, suggestions });
                    persistHistory();
                }
            }

            function scrollToBottom() {
                messagesEl.scrollTop = messagesEl.scrollHeight;
            }

            function setTyping(isVisible) {
                typingEl.style.display = isVisible ? 'flex' : 'none';
                if (isVisible) {
                    scrollToBottom();
                }
            }

            async function sendMessage(text) {
                appendMessage('user', text, [], true);
                input.value = '';
                setTyping(true);

                try {
                    const response = await fetch(apiUrl, {
                        method: 'POST',
                        headers: {
                            'Accept': 'application/json',
                            'Content-Type': 'application/json',
                            'X-Chatbot-Session-Id': getSessionId()
                        },
                        body: JSON.stringify({ message: text })
                    });

                    const payload = await response.json();

                    if (!response.ok || !payload.success) {
                        throw new Error(payload.message || 'Chatbot error');
                    }

                    const reply = payload.data?.reply || 'Mình chưa có câu trả lời phù hợp cho câu hỏi này.';
                    const suggestions = payload.data?.suggestions || [];
                    appendMessage('bot', reply, suggestions, true);
                } catch (error) {
                    const fallback = buildLocalReply(text);
                    appendMessage('bot', fallback.reply, fallback.suggestions, true);
                } finally {
                    setTyping(false);
                }
            }

            function buildLocalReply(message) {
                const normalized = message.toLowerCase();

                if (containsAny(normalized, ['xin chao', 'chao', 'hello', 'hi'])) {
                    return {
                        reply: 'Chào bạn, mình là trợ lý XanhStore. Mình có thể tư vấn sản phẩm, khuyến mãi, bảo hành và thanh toán.',
                        suggestions: commonSuggestions()
                    };
                }

                if (containsAny(normalized, ['bao hanh', 'warranty'])) {
                    return {
                        reply: 'Sản phẩm tại XanhStore được bảo hành chính hãng từ 12 đến 24 tháng tùy model.',
                        suggestions: [messageSuggestion('Xem iPhone', 'Tôi muốn xem iPhone'), messageSuggestion('Xem Samsung', 'Tôi muốn xem Samsung')]
                    };
                }

                if (containsAny(normalized, ['doi tra', 'tra hang', 'return'])) {
                    return {
                        reply: 'XanhStore hỗ trợ đổi trả trong 30 ngày nếu sản phẩm đáp ứng điều kiện bảo hành.',
                        suggestions: [messageSuggestion('Chính sách bảo hành', 'Bảo hành như thế nào?'), messageSuggestion('Liên hệ hỗ trợ', 'Tôi cần liên hệ hỗ trợ')]
                    };
                }

                if (containsAny(normalized, ['thanh toan', 'cod', 'momo', 'vnpay', 'chuyen khoan'])) {
                    return {
                        reply: 'Bạn có thể thanh toán COD, chuyển khoản ngân hàng, MoMo hoặc VNPay.',
                        suggestions: [messageSuggestion('Hướng dẫn mua hàng', 'Hướng dẫn mua hàng'), messageSuggestion('Theo dõi đơn', 'Làm sao xem đơn hàng?')]
                    };
                }

                if (containsAny(normalized, ['iphone', 'apple'])) {
                    return {
                        reply: 'Mình đã mở nhóm iPhone cho bạn.',
                        suggestions: [linkSuggestion('Xem iPhone', '/products?category=iphone'), messageSuggestion('So sánh iPhone', 'Tư vấn chọn iPhone')]
                    };
                }

                if (containsAny(normalized, ['samsung'])) {
                    return {
                        reply: 'Bạn có thể xem các dòng Samsung đang có sẵn và mình sẽ hỗ trợ chọn theo nhu cầu.',
                        suggestions: [linkSuggestion('Xem Samsung', '/products?category=samsung'), messageSuggestion('Samsung tầm trung', 'Gợi ý Samsung tầm trung')]
                    };
                }

                if (containsAny(normalized, ['xiaomi'])) {
                    return {
                        reply: 'Dưới đây là nhóm Xiaomi. Nếu bạn muốn máy pin tốt, cấu hình mạnh, mình sẽ lọc theo nhu cầu tiếp.',
                        suggestions: [linkSuggestion('Xem Xiaomi', '/products?category=xiaomi'), messageSuggestion('Máy pin tốt', 'Gợi ý máy pin tốt')]
                    };
                }

                if (containsAny(normalized, ['oppo'])) {
                    return {
                        reply: 'Mình đã sẵn sàng tư vấn các mẫu OPPO. Bạn có thể xem ngay danh sách sản phẩm.',
                        suggestions: [linkSuggestion('Xem OPPO', '/products?category=oppo'), messageSuggestion('Máy chụp đẹp', 'Gợi ý máy chụp ảnh đẹp')]
                    };
                }

                if (containsAny(normalized, ['vivo'])) {
                    return {
                        reply: 'Bạn có thể xem các mẫu Vivo và mình sẽ hỗ trợ theo tầm giá hoặc nhu cầu chụp ảnh, pin, hiệu năng.',
                        suggestions: [linkSuggestion('Xem Vivo', '/products?category=vivo'), messageSuggestion('Theo tầm giá', 'Tư vấn theo tầm giá')]
                    };
                }

                return {
                    reply: 'Mình có thể hỗ trợ tư vấn sản phẩm, khuyến mãi, đổi trả, bảo hành và thanh toán. Bạn có thể chọn một chủ đề bên dưới hoặc nhắn tên dòng máy bạn đang quan tâm.',
                    suggestions: commonSuggestions()
                };
            }

            function containsAny(message, keywords) {
                return keywords.some((keyword) => message.includes(keyword));
            }

            function commonSuggestions() {
                return [
                    messageSuggestion('Chính sách bảo hành', 'Bảo hành như thế nào?'),
                    messageSuggestion('Chính sách đổi trả', 'Đổi trả như thế nào?'),
                    messageSuggestion('Thanh toán', 'Có những cách thanh toán nào?')
                ];
            }

            function messageSuggestion(label, value) {
                return { label, type: 'message', value };
            }

            function linkSuggestion(label, value) {
                return { label, type: 'link', value };
            }
        })();
    </script>
    @stack('scripts')
</body>
</html>

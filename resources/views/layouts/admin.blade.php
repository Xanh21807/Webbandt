<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin - XanhStore')</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Arimo:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    
    <!-- Styles -->
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    
    @stack('styles')
</head>
<body>
    <div class="admin-layout">
        <!-- Sidebar -->
        <aside class="admin-sidebar">
            <div class="admin-sidebar-header">
                <a href="{{ url('/admin') }}" class="admin-sidebar-logo">
                    <div class="admin-sidebar-logo-icon">
                        <i class="fas fa-mobile-alt"></i>
                    </div>
                    <div class="admin-sidebar-logo-text">
                        <h2>XanhStore</h2>
                        <span>Quản trị viên</span>
                    </div>
                </a>
            </div>
            
            <nav class="admin-sidebar-nav">
                <ul>
                    <li>
                        <a href="{{ url('/admin') }}" class="{{ request()->is('admin') ? 'active' : '' }}">
                            <i class="fas fa-chart-pie"></i>
                            Dashboard
                        </a>
                    </li>
                    <li>
                        <a href="{{ url('/admin/products') }}" class="{{ request()->is('admin/products*') ? 'active' : '' }}">
                            <i class="fas fa-mobile-alt"></i>
                            Sản phẩm
                        </a>
                    </li>
                    <li>
                        <a href="{{ url('/admin/orders') }}" class="{{ request()->is('admin/orders*') ? 'active' : '' }}">
                            <i class="fas fa-shopping-bag"></i>
                            Đơn hàng
                        </a>
                    </li>
                    <li>
                        <a href="{{ url('/admin/users') }}" class="{{ request()->is('admin/users*') ? 'active' : '' }}">
                            <i class="fas fa-users"></i>
                            Người dùng
                        </a>
                    </li>
                    <li>
                        <a href="{{ url('/admin/categories') }}" class="{{ request()->is('admin/categories*') ? 'active' : '' }}">
                            <i class="fas fa-tags"></i>
                            Danh mục
                        </a>
                    </li>
                    <li>
                        <a href="{{ url('/admin/statistics') }}" class="{{ request()->is('admin/statistics*') ? 'active' : '' }}">
                            <i class="fas fa-chart-bar"></i>
                            Thống kê
                        </a>
                    </li>
                </ul>
            </nav>
            
            <div style="padding: 16px; margin-top: auto; border-top: 1px solid #364153;">
                <a href="{{ url('/') }}" style="display: flex; align-items: center; gap: 12px; color: #d1d5dc; font-size: 14px;">
                    <i class="fas fa-external-link-alt"></i>
                    Xem trang chủ
                </a>
            </div>
        </aside>

        <!-- Main Content -->
        <div class="admin-main">
            <!-- Header -->
            <header class="admin-header">
                <div class="admin-header-title">
                    <h1>@yield('page-title', 'Dashboard')</h1>
                </div>
                
                <div class="flex items-center gap-4">
                    <!-- Admin User -->
                    <div class="dropdown" id="adminDropdown">
                        <button class="btn btn-secondary" onclick="toggleAdminDropdown()">
                            <i class="fas fa-user-shield"></i>
                            <span>Admin</span>
                            <i class="fas fa-chevron-down"></i>
                        </button>
                        <div class="dropdown-menu">
                            <a href="#" onclick="logout()">
                                <i class="fas fa-sign-out-alt"></i>
                                Đăng xuất
                            </a>
                        </div>
                    </div>
                </div>
            </header>

            <!-- Content -->
            <div class="admin-content">
                @if(session('success'))
                    <div class="alert alert-success">
                        <i class="fas fa-check-circle"></i>
                        {{ session('success') }}
                    </div>
                @endif

                @if(session('error'))
                    <div class="alert alert-error">
                        <i class="fas fa-exclamation-circle"></i>
                        {{ session('error') }}
                    </div>
                @endif

                @yield('content')
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}"></script>
    <script>
        // Check admin authentication on page load
        document.addEventListener('DOMContentLoaded', function() {
            const token = localStorage.getItem('auth_token');
            const user = JSON.parse(localStorage.getItem('admin_user') || '{}');
            
            if (!token) {
                console.warn('No auth token found. Redirecting to login...');
                window.location.href = '/login';
                return;
            }
            
            if (user.role !== 'admin') {
                console.warn('User is not admin. Redirecting to login...');
                localStorage.clear();
                window.location.href = '/login';
                return;
            }
        });

        function toggleAdminDropdown() {
            document.getElementById('adminDropdown').classList.toggle('active');
        }
        
        // Close dropdown when clicking outside
        document.addEventListener('click', function(e) {
            if (!e.target.closest('#adminDropdown')) {
                document.getElementById('adminDropdown').classList.remove('active');
            }
        });

        function logout() {
            const token = localStorage.getItem('auth_token');
            
            if (token) {
                fetch('/api/admin/logout', {
                    method: 'POST',
                    headers: {
                        'Authorization': `Bearer ${token}`,
                        'Accept': 'application/json'
                    }
                }).finally(() => {
                    localStorage.clear();
                    window.location.href = '/login';
                });
            } else {
                localStorage.clear();
                window.location.href = '/login';
            }
        }

        // Format price helper
        function formatPrice(price) {
            return new Intl.NumberFormat('vi-VN').format(price || 0);
        }

        // Format date helper
        function formatDate(dateString) {
            if (!dateString) return '-';
            const date = new Date(dateString);
            return date.toLocaleDateString('vi-VN');
        }
    </script>
    @stack('scripts')
</body>
</html>

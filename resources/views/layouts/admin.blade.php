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
    <style>
        /* Premium Toast Notification System */
        .admin-toast-container {
            position: fixed;
            top: 24px;
            right: 24px;
            z-index: 999999;
            display: flex;
            flex-direction: column;
            gap: 12px;
            pointer-events: none;
        }
        .admin-toast {
            pointer-events: auto;
            min-width: 320px;
            max-width: 400px;
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border-radius: 12px;
            padding: 14px 20px;
            box-shadow: 0 10px 30px -5px rgba(0, 0, 0, 0.08), 0 8px 16px -6px rgba(0, 0, 0, 0.05);
            display: flex;
            align-items: center;
            gap: 14px;
            transform: translateX(120%);
            transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            position: relative;
            overflow: hidden;
            border-left: 5px solid #3b82f6;
        }
        .admin-toast.active {
            transform: translateX(0);
        }
        .admin-toast.toast-success {
            border-left-color: #10b981;
        }
        .admin-toast.toast-error {
            border-left-color: #ef4444;
        }
        .admin-toast.toast-warning {
            border-left-color: #f59e0b;
        }
        .admin-toast.toast-info {
            border-left-color: #3b82f6;
        }
        .admin-toast-icon {
            font-size: 22px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }
        .toast-success .admin-toast-icon { color: #10b981; }
        .toast-error .admin-toast-icon { color: #ef4444; }
        .toast-warning .admin-toast-icon { color: #f59e0b; }
        .toast-info .admin-toast-icon { color: #3b82f6; }

        .admin-toast-content {
            flex-grow: 1;
        }
        .admin-toast-title {
            font-weight: 700;
            color: #111827;
            font-size: 14px;
            margin-bottom: 2px;
        }
        .admin-toast-message {
            color: #4b5563;
            font-size: 13px;
            line-height: 1.4;
        }
        .admin-toast-close {
            color: #9ca3af;
            cursor: pointer;
            transition: color 0.2s, transform 0.2s;
            font-size: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            width: 20px;
            height: 20px;
            border-radius: 50%;
        }
        .admin-toast-close:hover {
            color: #1f2937;
            background: #f3f4f6;
            transform: scale(1.1);
        }
        .admin-toast-progress {
            position: absolute;
            bottom: 0;
            left: 0;
            height: 3px;
            width: 100%;
            background: rgba(0, 0, 0, 0.03);
        }
        .admin-toast-progress-bar {
            height: 100%;
            width: 100%;
            background: #3b82f6;
            transition: width 3.5s linear;
        }
        .toast-success .admin-toast-progress-bar { background: #10b981; }
        .toast-error .admin-toast-progress-bar { background: #ef4444; }
        .toast-warning .admin-toast-progress-bar { background: #f59e0b; }
        .toast-info .admin-toast-progress-bar { background: #3b82f6; }

        /* Custom Confirm Dialog */
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
        }
        .confirm-dialog p {
            font-size: 14px;
            color: #6b7280;
            line-height: 1.6;
            margin-bottom: 28px;
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
            background: linear-gradient(135deg, #ef4444, #dc2626);
            color: white;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s;
            box-shadow: 0 4px 12px rgba(239, 68, 68, 0.3);
        }
        .confirm-btn-ok:hover {
            background: linear-gradient(135deg, #dc2626, #b91c1c);
            transform: translateY(-1px);
            box-shadow: 0 6px 16px rgba(239, 68, 68, 0.4);
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
    </style>
</head>
<body>
    <div class="admin-layout">
        <!-- Sidebar -->
        <aside class="admin-sidebar">
            <div class="admin-sidebar-header">
                <a href="{{ url('/admin') }}" class="admin-sidebar-logo">
                    <div style="background: white; border-radius: 10px; padding: 4px 8px; display: flex; align-items: center; justify-content: center;">
                        <img src="{{ asset('images/logo.png') }}" alt="XanhStore" style="height: 38px; width: auto; object-fit: contain;">
                    </div>
                    <div class="admin-sidebar-logo-text">
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

        // Toast Notification System
        function showAdminToast(message, type = 'success') {
            let container = document.querySelector('.admin-toast-container');
            if (!container) {
                container = document.createElement('div');
                container.className = 'admin-toast-container';
                document.body.appendChild(container);
            }

            const toast = document.createElement('div');
            toast.className = `admin-toast toast-${type}`;
            
            let icon = 'fa-info-circle';
            let title = 'Thông báo';
            if (type === 'success') {
                icon = 'fa-check-circle';
                title = 'Thành công';
            } else if (type === 'error') {
                icon = 'fa-times-circle';
                title = 'Lỗi';
            } else if (type === 'warning') {
                icon = 'fa-exclamation-triangle';
                title = 'Cảnh báo';
            }

            toast.innerHTML = `
                <div class="admin-toast-icon">
                    <i class="fas ${icon}"></i>
                </div>
                <div class="admin-toast-content">
                    <div class="admin-toast-title">${title}</div>
                    <div class="admin-toast-message">${message}</div>
                </div>
                <div class="admin-toast-close" onclick="this.parentElement.remove()">
                    <i class="fas fa-times"></i>
                </div>
                <div class="admin-toast-progress">
                    <div class="admin-toast-progress-bar" style="width: 100%"></div>
                </div>
            `;

            container.appendChild(toast);

            // Trigger animation
            setTimeout(() => {
                toast.classList.add('active');
                const progressBar = toast.querySelector('.admin-toast-progress-bar');
                if (progressBar) {
                    progressBar.style.width = '0%';
                }
            }, 10);

            // Auto remove after 3.5s
            setTimeout(() => {
                toast.classList.remove('active');
                setTimeout(() => {
                    toast.remove();
                }, 400);
            }, 3500);
        }

        // Override window.alert to automatically show toast notification
        window.alert = function(message) {
            if (!message) return;
            let type = 'info';
            const msgLower = String(message).toLowerCase();
            if (msgLower.includes('thành công') || msgLower.includes('đã xóa') || msgLower.includes('ok') || msgLower.includes('success')) {
                type = 'success';
            } else if (msgLower.includes('lỗi') || msgLower.includes('không thể') || msgLower.includes('thất bại') || msgLower.includes('error')) {
                type = 'error';
            } else if (msgLower.includes('vui lòng') || msgLower.includes('nhập') || msgLower.includes('chọn') || msgLower.includes('yêu cầu')) {
                type = 'warning';
            }
            showAdminToast(message, type);
        };

        // Custom Confirm Dialog (replaces browser confirm())
        function showAdminConfirm(message, onConfirm, options = {}) {
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
    </script>
    @stack('scripts')
</body>
</html>

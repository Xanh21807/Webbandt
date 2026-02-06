<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chuyển hướng - XanhStore</title>
    <script>
        // Redirect về trang login chung
        window.location.href = '/login';
    </script>
</head>
<body>
    <p>Đang chuyển hướng...</p>
</body>
</html>
    <style>
        :root {
            --primary-color: #2563eb;
            --primary-hover: #1d4ed8;
            --error-color: #dc2626;
            --success-color: #16a34a;
            --gray-100: #f3f4f6;
            --gray-200: #e5e7eb;
            --gray-300: #d1d5db;
            --gray-400: #9ca3af;
            --gray-500: #6b7280;
            --gray-600: #4b5563;
            --gray-700: #374151;
            --gray-800: #1f2937;
            --gray-900: #111827;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .login-container {
            width: 100%;
            max-width: 420px;
        }

        .login-card {
            background: white;
            border-radius: 12px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            padding: 40px;
        }

        .login-header {
            text-align: center;
            margin-bottom: 32px;
        }

        .login-logo {
            width: 60px;
            height: 60px;
            background: var(--primary-color);
            border-radius: 12px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 16px;
        }

        .login-logo i {
            font-size: 28px;
            color: white;
        }

        .login-title {
            font-size: 24px;
            font-weight: 700;
            color: var(--gray-900);
            margin-bottom: 8px;
        }

        .login-subtitle {
            font-size: 14px;
            color: var(--gray-500);
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-label {
            display: block;
            font-size: 14px;
            font-weight: 500;
            color: var(--gray-700);
            margin-bottom: 8px;
        }

        .form-input-group {
            position: relative;
        }

        .form-input-icon {
            position: absolute;
            left: 14px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--gray-400);
            font-size: 16px;
        }

        .form-input {
            width: 100%;
            padding: 12px 14px 12px 42px;
            border: 1px solid var(--gray-300);
            border-radius: 8px;
            font-size: 14px;
            transition: all 0.2s;
        }

        .form-input:focus {
            outline: none;
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1);
        }

        .form-input.error {
            border-color: var(--error-color);
        }

        .error-message {
            display: none;
            font-size: 13px;
            color: var(--error-color);
            margin-top: 6px;
        }

        .error-message.show {
            display: block;
        }

        .alert {
            padding: 12px 16px;
            border-radius: 8px;
            font-size: 14px;
            margin-bottom: 20px;
            display: none;
        }

        .alert.show {
            display: block;
        }

        .alert-error {
            background: #fee2e2;
            color: #991b1b;
            border: 1px solid #fecaca;
        }

        .alert-success {
            background: #dcfce7;
            color: #166534;
            border: 1px solid #bbf7d0;
        }

        .btn {
            width: 100%;
            padding: 12px 24px;
            border: none;
            border-radius: 8px;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s;
        }

        .btn-primary {
            background: var(--primary-color);
            color: white;
        }

        .btn-primary:hover:not(:disabled) {
            background: var(--primary-hover);
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(37, 99, 235, 0.3);
        }

        .btn-primary:disabled {
            background: var(--gray-300);
            cursor: not-allowed;
        }

        .btn-loading {
            position: relative;
            color: transparent;
        }

        .btn-loading::after {
            content: "";
            position: absolute;
            width: 16px;
            height: 16px;
            top: 50%;
            left: 50%;
            margin-left: -8px;
            margin-top: -8px;
            border: 2px solid white;
            border-radius: 50%;
            border-top-color: transparent;
            animation: spinner 0.6s linear infinite;
        }

        @keyframes spinner {
            to { transform: rotate(360deg); }
        }

        .login-footer {
            margin-top: 24px;
            text-align: center;
            font-size: 14px;
            color: var(--gray-500);
        }

        .login-footer a {
            color: var(--primary-color);
            text-decoration: none;
            font-weight: 500;
        }

        .login-footer a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="login-card">
            <div class="login-header">
                <div class="login-logo">
                    <i class="fas fa-shield-alt"></i>
                </div>
                <h1 class="login-title">Đăng nhập Admin</h1>
                <p class="login-subtitle">Quản trị hệ thống XanhStore</p>
            </div>

            <div id="alert" class="alert"></div>

            <form id="loginForm">
                <div class="form-group">
                    <label class="form-label" for="email">Email</label>
                    <div class="form-input-group">
                        <i class="fas fa-envelope form-input-icon"></i>
                        <input 
                            type="email" 
                            id="email" 
                            class="form-input" 
                            placeholder="admin@xanhstore.com"
                            required
                        >
                    </div>
                    <div id="emailError" class="error-message"></div>
                </div>

                <div class="form-group">
                    <label class="form-label" for="password">Mật khẩu</label>
                    <div class="form-input-group">
                        <i class="fas fa-lock form-input-icon"></i>
                        <input 
                            type="password" 
                            id="password" 
                            class="form-input" 
                            placeholder="••••••••"
                            required
                        >
                    </div>
                    <div id="passwordError" class="error-message"></div>
                </div>

                <button type="submit" class="btn btn-primary" id="submitBtn">
                    Đăng nhập
                </button>
            </form>

            <div class="login-footer">
                <a href="/">← Về trang chủ</a>
            </div>
        </div>
    </div>

    <script>
        const loginForm = document.getElementById('loginForm');
        const submitBtn = document.getElementById('submitBtn');
        const alertDiv = document.getElementById('alert');

        function showAlert(message, type = 'error') {
            alertDiv.textContent = message;
            alertDiv.className = `alert alert-${type} show`;
            
            if (type === 'success') {
                setTimeout(() => {
                    alertDiv.classList.remove('show');
                }, 3000);
            }
        }

        function hideAlert() {
            alertDiv.classList.remove('show');
        }

        function setLoading(loading) {
            submitBtn.disabled = loading;
            if (loading) {
                submitBtn.classList.add('btn-loading');
            } else {
                submitBtn.classList.remove('btn-loading');
            }
        }

        loginForm.addEventListener('submit', async (e) => {
            e.preventDefault();
            hideAlert();
            
            const email = document.getElementById('email').value;
            const password = document.getElementById('password').value;

            // Validate
            if (!email || !password) {
                showAlert('Vui lòng nhập đầy đủ thông tin', 'error');
                return;
            }

            setLoading(true);

            try {
                const response = await fetch('/api/admin/login', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({ email, password })
                });

                const data = await response.json();

                if (response.ok && data.success) {
                    // Save token and user info
                    localStorage.setItem('auth_token', data.data.access_token);
                    localStorage.setItem('admin_user', JSON.stringify(data.data.user));
                    
                    showAlert('Đăng nhập thành công! Đang chuyển hướng...', 'success');
                    
                    setTimeout(() => {
                        window.location.href = '/admin/dashboard';
                    }, 1000);
                } else {
                    showAlert(data.message || 'Đăng nhập thất bại', 'error');
                }
            } catch (error) {
                console.error('Login error:', error);
                showAlert('Có lỗi xảy ra. Vui lòng thử lại sau.', 'error');
            } finally {
                setLoading(false);
            }
        });

        // Check if already logged in
        if (localStorage.getItem('auth_token')) {
            window.location.href = '/admin/dashboard';
        }
    </script>
</body>
</html>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Đăng ký - XanhStore</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Arimo:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Arimo', sans-serif;
            background: #e8f4fc;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }
        
        .auth-card {
            background: white;
            border-radius: 16px;
            padding: 40px;
            width: 100%;
            max-width: 420px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
        }
        
        .auth-logo {
            text-align: center;
            margin-bottom: 32px;
        }
        
        .auth-logo-icon {
            width: 64px;
            height: 64px;
            background: #d70018;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 12px;
        }
        
        .auth-logo-icon i {
            font-size: 32px;
            color: white;
        }
        
        .auth-logo-text {
            font-size: 18px;
            font-weight: 700;
            color: #1f2937;
        }
        
        .auth-title {
            font-size: 24px;
            font-weight: 700;
            color: #d70018;
            text-align: center;
            margin-bottom: 8px;
        }
        
        .auth-subtitle {
            font-size: 14px;
            color: #6b7280;
            text-align: center;
            margin-bottom: 24px;
        }
        
        .form-group {
            margin-bottom: 20px;
        }
        
        .form-group label {
            display: block;
            font-size: 14px;
            font-weight: 500;
            color: #374151;
            margin-bottom: 6px;
        }
        
        .input-wrapper {
            position: relative;
        }
        
        .input-wrapper i {
            position: absolute;
            left: 14px;
            top: 50%;
            transform: translateY(-50%);
            color: #9ca3af;
            font-size: 16px;
        }
        
        .input-wrapper input {
            width: 100%;
            padding: 12px 14px 12px 42px;
            border: 1px solid #e5e7eb;
            border-radius: 8px;
            font-size: 15px;
            transition: all 0.2s;
        }
        
        .input-wrapper input:focus {
            outline: none;
            border-color: #d70018;
            box-shadow: 0 0 0 3px rgba(215, 0, 24, 0.1);
        }
        
        .input-wrapper .toggle-password {
            position: absolute;
            right: 14px;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            color: #9ca3af;
            cursor: pointer;
            left: auto;
        }
        
        .btn-primary {
            width: 100%;
            padding: 14px;
            background: #d70018;
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: background 0.2s;
        }
        
        .btn-primary:hover {
            background: #b80015;
        }
        
        .btn-primary:disabled {
            background: #f87171;
            cursor: not-allowed;
        }
        
        .divider {
            display: flex;
            align-items: center;
            gap: 16px;
            margin: 24px 0;
            color: #9ca3af;
            font-size: 14px;
        }
        
        .divider::before,
        .divider::after {
            content: '';
            flex: 1;
            height: 1px;
            background: #e5e7eb;
        }
        
        .social-buttons {
            display: flex;
            flex-direction: column;
            gap: 12px;
            margin-bottom: 24px;
        }
        
        .btn-social {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            padding: 12px;
            border: 1px solid #e5e7eb;
            border-radius: 8px;
            background: white;
            font-size: 14px;
            font-weight: 500;
            color: #374151;
            cursor: pointer;
            transition: all 0.2s;
        }
        
        .btn-social:hover {
            background: #f9fafb;
            border-color: #d1d5db;
        }
        
        .btn-social i {
            font-size: 18px;
        }
        
        .btn-social.google i {
            color: #ea4335;
        }
        
        .btn-social.facebook i {
            color: #1877f2;
        }
        
        .auth-footer {
            text-align: center;
            font-size: 14px;
            color: #6b7280;
        }
        
        .auth-footer a {
            color: #d70018;
            font-weight: 600;
            text-decoration: none;
        }
        
        .auth-footer a:hover {
            text-decoration: underline;
        }
        
        .alert {
            padding: 12px 16px;
            border-radius: 8px;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: 14px;
        }
        
        .alert-error {
            background: #fef2f2;
            color: #dc2626;
            border: 1px solid #fecaca;
        }
        
        .alert-success {
            background: #f0fdf4;
            color: #16a34a;
            border: 1px solid #bbf7d0;
        }
    </style>
</head>
<body>
    <div class="auth-card">
        <!-- Logo -->
        <div class="auth-logo">
            <div class="auth-logo-icon">
                <i class="fas fa-mobile-alt"></i>
            </div>
            <div class="auth-logo-text">XanhStore</div>
        </div>
        
        <!-- Title -->
        <h1 class="auth-title">Đăng ký tài khoản</h1>
        <p class="auth-subtitle">Tạo tài khoản mới để bắt đầu mua sắm</p>
        
        <!-- Error Message -->
        <div id="registerError" class="alert alert-error" style="display: none;">
            <i class="fas fa-exclamation-circle"></i>
            <span id="registerErrorMessage"></span>
        </div>
        
        <!-- Register Form -->
        <form id="registerForm">
            <div class="form-group">
                <label for="name">Tên người dùng</label>
                <div class="input-wrapper">
                    <i class="fas fa-user"></i>
                    <input type="text" id="name" name="name" placeholder="Nguyễn Văn A" required>
                </div>
            </div>
            
            <div class="form-group">
                <label for="email">Email</label>
                <div class="input-wrapper">
                    <i class="fas fa-envelope"></i>
                    <input type="email" id="email" name="email" placeholder="email@example.com" required>
                </div>
            </div>
            
            <div class="form-group">
                <label for="password">Mật khẩu</label>
                <div class="input-wrapper">
                    <i class="fas fa-lock"></i>
                    <input type="password" id="password" name="password" placeholder="••••••••" required minlength="8">
                    <button type="button" class="toggle-password" onclick="togglePassword('password')">
                        <i class="fas fa-eye"></i>
                    </button>
                </div>
            </div>
            
            <div class="form-group">
                <label for="password_confirmation">Xác nhận mật khẩu</label>
                <div class="input-wrapper">
                    <i class="fas fa-lock"></i>
                    <input type="password" id="password_confirmation" name="password_confirmation" placeholder="••••••••" required>
                    <button type="button" class="toggle-password" onclick="togglePassword('password_confirmation')">
                        <i class="fas fa-eye"></i>
                    </button>
                </div>
            </div>
            
            <button type="submit" class="btn-primary" id="registerBtn">
                Đăng ký
            </button>
        </form>
        
        <!-- Divider -->
        <div class="divider">Hoặc</div>
        
        <!-- Social Register -->
        <div class="social-buttons">
            <button type="button" class="btn-social google">
                <i class="fab fa-google"></i>
                Đăng ký bằng Google
            </button>
            <button type="button" class="btn-social facebook">
                <i class="fab fa-facebook-f"></i>
                Đăng ký bằng Facebook
            </button>
        </div>
        
        <!-- Login Link -->
        <div class="auth-footer">
            Đã có tài khoản? <a href="{{ url('/login') }}">Đăng nhập</a>
        </div>
    </div>
    
    <script>
        function togglePassword(inputId) {
            const input = document.getElementById(inputId);
            const wrapper = input.closest('.input-wrapper');
            const icon = wrapper.querySelector('.toggle-password i');
            
            if (input.type === 'password') {
                input.type = 'text';
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            } else {
                input.type = 'password';
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            }
        }
        
        document.getElementById('registerForm').addEventListener('submit', async function(e) {
            e.preventDefault();
            
            const btn = document.getElementById('registerBtn');
            const errorDiv = document.getElementById('registerError');
            const errorMessage = document.getElementById('registerErrorMessage');
            
            const password = document.getElementById('password').value;
            const passwordConfirm = document.getElementById('password_confirmation').value;
            
            if (password !== passwordConfirm) {
                errorMessage.textContent = 'Mật khẩu xác nhận không khớp';
                errorDiv.style.display = 'flex';
                return;
            }
            
            btn.disabled = true;
            btn.textContent = 'Đang đăng ký...';
            errorDiv.style.display = 'none';
            
            try {
                const response = await fetch('/api/register', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        name: document.getElementById('name').value,
                        email: document.getElementById('email').value,
                        password: password,
                        password_confirmation: passwordConfirm
                    })
                });
                
                const data = await response.json();
                
                if (response.ok && data.success) {
                    // Xóa localStorage cũ trước khi lưu user mới
                    localStorage.clear();
                    
                    localStorage.setItem('auth_token', data.data.access_token);
                    localStorage.setItem('user', JSON.stringify(data.data.user));
                    window.location.href = '/';
                } else {
                    let message = data.message || 'Đăng ký thất bại';
                    if (data.errors) {
                        message = Object.values(data.errors).flat().join(', ');
                    }
                    errorMessage.textContent = message;
                    errorDiv.style.display = 'flex';
                }
            } catch (error) {
                errorMessage.textContent = 'Đã có lỗi xảy ra. Vui lòng thử lại sau.';
                errorDiv.style.display = 'flex';
            } finally {
                btn.disabled = false;
                btn.textContent = 'Đăng ký';
            }
        });
    </script>
</body>
</html>

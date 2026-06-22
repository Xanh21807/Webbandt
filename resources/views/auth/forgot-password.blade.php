<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Quên mật khẩu - XanhStore</title>
    
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
            max-width: 440px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
            position: relative;
            overflow: hidden;
        }
        
        .auth-logo {
            text-align: center;
            margin-bottom: 28px;
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
            font-size: 22px;
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
            line-height: 1.4;
        }
        
        /* Steps container */
        .step-container {
            display: none;
        }
        
        .step-container.active {
            display: block;
            animation: fadeIn 0.4s ease-in-out forwards;
        }
        
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
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
        
        .auth-footer {
            text-align: center;
            font-size: 14px;
            color: #6b7280;
            margin-top: 24px;
        }
        
        .auth-footer a {
            color: #d70018;
            font-weight: 600;
            text-decoration: none;
        }
        
        .auth-footer a:hover {
            text-decoration: underline;
        }
        
        /* OTP Inputs styling */
        .otp-inputs {
            display: flex;
            justify-content: space-between;
            gap: 8px;
            margin-bottom: 20px;
        }
        
        .otp-input {
            width: 50px;
            height: 50px;
            text-align: center;
            font-size: 22px;
            font-weight: 700;
            border: 1px solid #e5e7eb;
            border-radius: 8px;
            outline: none;
            transition: all 0.2s;
        }
        
        .otp-input:focus {
            border-color: #d70018;
            box-shadow: 0 0 0 3px rgba(215, 0, 24, 0.1);
        }
        
        .timer-container {
            text-align: center;
            font-size: 14px;
            color: #4b5563;
            margin-bottom: 20px;
        }
        
        .timer-num {
            font-weight: 700;
            color: #d70018;
        }
        
        .resend-btn {
            background: none;
            border: none;
            color: #d70018;
            font-weight: 600;
            cursor: pointer;
            text-decoration: underline;
            padding: 0;
            font-size: 14px;
        }
        
        .resend-btn:disabled {
            color: #9ca3af;
            text-decoration: none;
            cursor: not-allowed;
        }
        
        /* Progress line */
        .progress-bar {
            display: flex;
            justify-content: space-between;
            margin-bottom: 30px;
            position: relative;
        }
        
        .progress-bar::before {
            content: '';
            position: absolute;
            top: 50%;
            left: 0;
            right: 0;
            height: 2px;
            background: #e5e7eb;
            z-index: 1;
            transform: translateY(-50%);
        }
        
        .progress-step {
            width: 30px;
            height: 30px;
            border-radius: 50%;
            background: white;
            border: 2px solid #e5e7eb;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 14px;
            font-weight: 700;
            color: #9ca3af;
            position: relative;
            z-index: 2;
            transition: all 0.3s;
        }
        
        .progress-step.active {
            border-color: #d70018;
            background: #d70018;
            color: white;
        }
        
        .progress-step.completed {
            border-color: #16a34a;
            background: #16a34a;
            color: white;
        }
        
        /* Success Screen */
        .success-screen {
            text-align: center;
            padding: 20px 0;
        }
        
        .success-icon {
            width: 72px;
            height: 72px;
            background: #f0fdf4;
            border: 2px solid #bbf7d0;
            color: #16a34a;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 36px;
            margin: 0 auto 20px;
        }
        
        .back-link {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            color: #d70018;
            text-decoration: none;
            font-size: 14px;
            font-weight: 500;
            margin-top: 10px;
        }
        
        .back-link:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="auth-card">
        <!-- Logo -->
        <div class="auth-logo">
            <img src="{{ asset('images/logo.png') }}" alt="XanhStore" style="height: 70px; width: auto; object-fit: contain;">
        </div>
        
        <!-- Progress Bar -->
        <div class="progress-bar">
            <div class="progress-step active" id="prog-1">1</div>
            <div class="progress-step" id="prog-2">2</div>
            <div class="progress-step" id="prog-3">3</div>
        </div>
        
        <!-- General Alert -->
        <div id="authAlert" class="alert" style="display: none;">
            <i class="fas" id="alertIcon"></i>
            <span id="alertMessage"></span>
        </div>
        
        <!-- STEP 1: Enter Email -->
        <div class="step-container active" id="step-email">
            <h1 class="auth-title">Quên mật khẩu</h1>
            <p class="auth-subtitle">Nhập email đăng ký của bạn. Chúng tôi sẽ gửi mã OTP xác nhận về hòm thư này.</p>
            
            <form id="emailForm">
                <div class="form-group">
                    <label for="email">Email tài khoản</label>
                    <div class="input-wrapper">
                        <i class="fas fa-envelope"></i>
                        <input type="email" id="email" placeholder="email@example.com" required>
                    </div>
                </div>
                
                <button type="submit" class="btn-primary" id="emailBtn">
                    Gửi mã xác nhận
                </button>
            </form>
        </div>
        
        <!-- STEP 2: Enter OTP -->
        <div class="step-container" id="step-otp">
            <h1 class="auth-title">Xác thực mã OTP</h1>
            <p class="auth-subtitle" id="otpSubtitle">Chúng tôi đã gửi mã xác thực gồm 6 chữ số đến email của bạn.</p>
            
            <form id="otpForm">
                <div class="form-group">
                    <label>Mã xác thực OTP</label>
                    <div class="otp-inputs">
                        <input type="text" class="otp-input" maxlength="1" data-index="0" required>
                        <input type="text" class="otp-input" maxlength="1" data-index="1" required>
                        <input type="text" class="otp-input" maxlength="1" data-index="2" required>
                        <input type="text" class="otp-input" maxlength="1" data-index="3" required>
                        <input type="text" class="otp-input" maxlength="1" data-index="4" required>
                        <input type="text" class="otp-input" maxlength="1" data-index="5" required>
                    </div>
                    <!-- Hidden field to store fully constructed OTP -->
                    <input type="hidden" id="fullOtp">
                </div>
                
                <div class="timer-container">
                    Mã xác thực có hiệu lực trong <span class="timer-num" id="countdown">10:00</span><br>
                    Không nhận được mã? <button type="button" class="resend-btn" id="resendBtn" disabled>Gửi lại mã</button>
                </div>
                
                <button type="submit" class="btn-primary" id="otpBtn">
                    Xác minh mã OTP
                </button>
            </form>
        </div>
        
        <!-- STEP 3: Create New Password -->
        <div class="step-container" id="step-reset">
            <h1 class="auth-title">Tạo mật khẩu mới</h1>
            <p class="auth-subtitle">Nhập mật khẩu mới cho tài khoản của bạn.</p>
            
            <form id="resetForm">
                <div class="form-group">
                    <label for="password">Mật khẩu mới</label>
                    <div class="input-wrapper">
                        <i class="fas fa-lock"></i>
                        <input type="password" id="password" placeholder="Tối thiểu 8 ký tự" required minlength="8">
                        <button type="button" class="toggle-password" onclick="togglePassword('password')">
                            <i class="fas fa-eye"></i>
                        </button>
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="password_confirmation">Xác nhận mật khẩu mới</label>
                    <div class="input-wrapper">
                        <i class="fas fa-lock"></i>
                        <input type="password" id="password_confirmation" placeholder="••••••••" required>
                        <button type="button" class="toggle-password" onclick="togglePassword('password_confirmation')">
                            <i class="fas fa-eye"></i>
                        </button>
                    </div>
                </div>
                
                <button type="submit" class="btn-primary" id="resetBtn">
                    Cập nhật mật khẩu
                </button>
            </form>
        </div>
        
        <!-- STEP 4: Success Screen -->
        <div class="step-container" id="step-success">
            <div class="success-screen">
                <div class="success-icon">
                    <i class="fas fa-check"></i>
                </div>
                <h1 class="auth-title" style="color: #16a34a; margin-bottom: 12px;">Thành công!</h1>
                <p class="auth-subtitle" style="margin-bottom: 24px;">Mật khẩu của bạn đã được thay đổi thành công. Bây giờ bạn có thể đăng nhập bằng mật khẩu mới.</p>
                <a href="{{ url('/login') }}" class="btn-primary" style="display: block; text-decoration: none; text-align: center; line-height: 1.4;">
                    Đăng nhập ngay
                </a>
            </div>
        </div>
        
        <!-- Footer Links -->
        <div class="auth-footer" id="authFooter">
            <a href="{{ url('/login') }}" class="back-link">
                <i class="fas fa-arrow-left"></i> Quay lại đăng nhập
            </a>
        </div>
    </div>
    
    <script>
        // State variables
        let userEmail = '';
        let verifiedOtp = '';
        let timerInterval = null;
        
        // Show/hide password utility
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
        
        // Alert utility helper
        function showAlert(type, message) {
            const alertDiv = document.getElementById('authAlert');
            const alertIcon = document.getElementById('alertIcon');
            const alertMessage = document.getElementById('alertMessage');
            
            alertDiv.style.display = 'flex';
            alertDiv.className = `alert alert-${type}`;
            alertMessage.textContent = message;
            
            if (type === 'error') {
                alertIcon.className = 'fas fa-exclamation-circle';
            } else {
                alertIcon.className = 'fas fa-check-circle';
            }
        }
        
        function hideAlert() {
            document.getElementById('authAlert').style.display = 'none';
        }
        
        // Countdown timer utility
        function startCountdown(durationSeconds) {
            clearInterval(timerInterval);
            const display = document.getElementById('countdown');
            const resendBtn = document.getElementById('resendBtn');
            resendBtn.disabled = true;
            
            let timer = durationSeconds;
            
            timerInterval = setInterval(() => {
                const minutes = Math.floor(timer / 60);
                const seconds = timer % 60;
                
                display.textContent = `${minutes.toString().padStart(2, '0')}:${seconds.toString().padStart(2, '0')}`;
                
                if (--timer < 0) {
                    clearInterval(timerInterval);
                    display.textContent = "Hết hạn";
                    resendBtn.disabled = false;
                }
            }, 1000);
        }
        
        // Step Transition Utility
        function goToStep(stepNumber) {
            hideAlert();
            
            // Hide all step containers
            document.querySelectorAll('.step-container').forEach(step => {
                step.classList.remove('active');
            });
            
            // Update progress steps styling
            document.querySelectorAll('.progress-step').forEach((pStep, index) => {
                const idx = index + 1;
                pStep.className = 'progress-step';
                if (idx < stepNumber) {
                    pStep.classList.add('completed');
                    pStep.innerHTML = '<i class="fas fa-check"></i>';
                } else if (idx === stepNumber) {
                    pStep.classList.add('active');
                    pStep.innerHTML = idx;
                } else {
                    pStep.innerHTML = idx;
                }
            });
            
            // Show target step
            if (stepNumber === 1) {
                document.getElementById('step-email').classList.add('active');
                document.getElementById('authFooter').style.display = 'block';
            } else if (stepNumber === 2) {
                document.getElementById('step-otp').classList.add('active');
                document.getElementById('authFooter').style.display = 'block';
                // Focus first otp input
                setTimeout(() => document.querySelector('.otp-input').focus(), 100);
            } else if (stepNumber === 3) {
                document.getElementById('step-reset').classList.add('active');
                document.getElementById('authFooter').style.display = 'block';
            } else if (stepNumber === 4) {
                document.getElementById('step-success').classList.add('active');
                document.getElementById('authFooter').style.display = 'none'; // Success screen has its own button
                document.querySelector('.progress-bar').style.display = 'none'; // Hide progress bar on final step
            }
        }
        
        // --- STEP 1: Handle Email Submit ---
        document.getElementById('emailForm').addEventListener('submit', async function(e) {
            e.preventDefault();
            hideAlert();
            
            const btn = document.getElementById('emailBtn');
            const emailInput = document.getElementById('email');
            
            btn.disabled = true;
            btn.textContent = 'Đang xử lý...';
            
            userEmail = emailInput.value.trim();
            
            try {
                const response = await fetch('/api/forgot-password', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({ email: userEmail })
                });
                
                const data = await response.json();
                
                if (response.ok && data.success) {
                    // Update email text in OTP step
                    document.getElementById('otpSubtitle').innerHTML = `Chúng tôi đã gửi mã xác thực gồm 6 chữ số đến email <strong style="color:#1f2937">${userEmail}</strong>.`;
                    
                    showAlert('success', 'Mã xác thực đã được gửi đến email của bạn.');
                    
                    // Transition to step 2 after a brief delay
                    setTimeout(() => {
                        goToStep(2);
                        startCountdown(600); // 10 minutes
                    }, 2000);
                } else {
                    showAlert('error', data.message || 'Email không tồn tại trong hệ thống.');
                }
            } catch (error) {
                showAlert('error', 'Đã xảy ra lỗi kết nối. Vui lòng thử lại sau.');
            } finally {
                btn.disabled = false;
                btn.textContent = 'Gửi mã xác nhận';
            }
        });
        
        // --- OTP Inputs auto focus and navigation logic ---
        const otpInputs = document.querySelectorAll('.otp-input');
        otpInputs.forEach((input, index) => {
            // Focus next on input
            input.addEventListener('input', (e) => {
                // Keep only numeric characters
                input.value = input.value.replace(/[^0-9]/g, '');
                
                if (input.value.length === 1) {
                    if (index < otpInputs.length - 1) {
                        otpInputs[index + 1].focus();
                    }
                }
                updateFullOtpValue();
            });
            
            // Navigate with arrow keys & backspace
            input.addEventListener('keydown', (e) => {
                if (e.key === 'Backspace') {
                    if (input.value.length === 0 && index > 0) {
                        otpInputs[index - 1].focus();
                    }
                } else if (e.key === 'ArrowLeft' && index > 0) {
                    otpInputs[index - 1].focus();
                } else if (e.key === 'ArrowRight' && index < otpInputs.length - 1) {
                    otpInputs[index + 1].focus();
                }
            });
            
            // Paste event support (allows copying the whole OTP code)
            input.addEventListener('paste', (e) => {
                const pasteData = e.clipboardData.getData('text').trim();
                if (/^\d{6}$/.test(pasteData)) {
                    otpInputs.forEach((inp, idx) => {
                        inp.value = pasteData[idx];
                    });
                    otpInputs[5].focus();
                    updateFullOtpValue();
                    e.preventDefault();
                }
            });
        });
        
        function updateFullOtpValue() {
            let combined = '';
            otpInputs.forEach(input => {
                combined += input.value;
            });
            document.getElementById('fullOtp').value = combined;
        }
        
        // Resend OTP trigger
        document.getElementById('resendBtn').addEventListener('click', async function() {
            hideAlert();
            const resend = document.getElementById('resendBtn');
            resend.disabled = true;
            resend.textContent = 'Đang gửi lại...';
            
            try {
                const response = await fetch('/api/forgot-password', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({ email: userEmail })
                });
                
                const data = await response.json();
                
                if (response.ok && data.success) {
                    showAlert('success', 'Mã OTP mới đã được gửi lại vào email.');
                    startCountdown(600);
                } else {
                    showAlert('error', data.message || 'Không thể gửi lại OTP.');
                    resend.disabled = false;
                }
            } catch (error) {
                showAlert('error', 'Lỗi kết nối. Vui lòng thử lại sau.');
                resend.disabled = false;
            } finally {
                resend.textContent = 'Gửi lại mã';
            }
        });
        
        // --- STEP 2: Handle OTP Submit ---
        document.getElementById('otpForm').addEventListener('submit', async function(e) {
            e.preventDefault();
            hideAlert();
            
            const otpCode = document.getElementById('fullOtp').value;
            if (otpCode.length !== 6) {
                showAlert('error', 'Vui lòng nhập đủ 6 chữ số OTP.');
                return;
            }
            
            const btn = document.getElementById('otpBtn');
            btn.disabled = true;
            btn.textContent = 'Đang xác minh...';
            
            try {
                const response = await fetch('/api/verify-otp', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        email: userEmail,
                        otp: otpCode
                    })
                });
                
                const data = await response.json();
                
                if (response.ok && data.success) {
                    verifiedOtp = otpCode; // Store OTP code temporarily
                    showAlert('success', 'Xác thực OTP thành công. Chuyển sang đổi mật khẩu...');
                    
                    setTimeout(() => {
                        goToStep(3);
                    }, 1500);
                } else {
                    showAlert('error', data.message || 'Mã xác thực OTP không đúng hoặc đã hết hạn.');
                }
            } catch (error) {
                showAlert('error', 'Đã xảy ra lỗi kết nối. Vui lòng thử lại sau.');
            } finally {
                btn.disabled = false;
                btn.textContent = 'Xác minh mã OTP';
            }
        });
        
        // --- STEP 3: Handle Reset Password Submit ---
        document.getElementById('resetForm').addEventListener('submit', async function(e) {
            e.preventDefault();
            hideAlert();
            
            const password = document.getElementById('password').value;
            const passwordConfirm = document.getElementById('password_confirmation').value;
            
            if (password !== passwordConfirm) {
                showAlert('error', 'Mật khẩu xác nhận không trùng khớp.');
                return;
            }
            
            const btn = document.getElementById('resetBtn');
            btn.disabled = true;
            btn.textContent = 'Đang đổi mật khẩu...';
            
            try {
                const response = await fetch('/api/reset-password', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        email: userEmail,
                        otp: verifiedOtp,
                        password: password,
                        password_confirmation: passwordConfirm
                    })
                });
                
                const data = await response.json();
                
                if (response.ok && data.success) {
                    goToStep(4);
                } else {
                    showAlert('error', data.message || 'Đặt lại mật khẩu thất bại. Vui lòng kiểm tra lại.');
                }
            } catch (error) {
                showAlert('error', 'Đã xảy ra lỗi kết nối. Vui lòng thử lại sau.');
            } finally {
                btn.disabled = false;
                btn.textContent = 'Cập nhật mật khẩu';
            }
        });
    </script>
</body>
</html>

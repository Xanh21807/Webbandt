@extends('layouts.app')

@section('title', 'Đổi mật khẩu - XanhStore')

@section('content')
<div class="profile-page">
    <div class="container">
        <div class="profile-layout">
            <!-- Sidebar -->
            @include('partials.profile-sidebar')

            <!-- Main Content -->
            <main class="profile-main">
                <div class="profile-section">
                    <div class="section-header">
                        <h2><i class="fas fa-lock"></i> Đổi mật khẩu</h2>
                    </div>
                    
                    <div class="password-form-container">
                        <form id="changePasswordForm" class="password-form">
                            <div class="form-group">
                                <label for="currentPassword">Mật khẩu hiện tại <span class="required">*</span></label>
                                <div class="password-input-wrapper">
                                    <input type="password" id="currentPassword" name="current_password" required>
                                    <button type="button" class="toggle-password" onclick="togglePassword('currentPassword')">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                </div>
                            </div>
                            
                            <div class="form-group">
                                <label for="newPassword">Mật khẩu mới <span class="required">*</span></label>
                                <div class="password-input-wrapper">
                                    <input type="password" id="newPassword" name="password" required minlength="8">
                                    <button type="button" class="toggle-password" onclick="togglePassword('newPassword')">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                </div>
                                <small class="form-hint">Mật khẩu phải có ít nhất 8 ký tự</small>
                            </div>
                            
                            <div class="form-group">
                                <label for="confirmPassword">Xác nhận mật khẩu mới <span class="required">*</span></label>
                                <div class="password-input-wrapper">
                                    <input type="password" id="confirmPassword" name="password_confirmation" required>
                                    <button type="button" class="toggle-password" onclick="togglePassword('confirmPassword')">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                </div>
                            </div>
                            
                            <div id="passwordError" class="error-message" style="display: none;"></div>
                            <div id="passwordSuccess" class="success-message" style="display: none;"></div>
                            
                            <div class="form-actions">
                                <button type="submit" class="btn btn-primary" id="submitBtn">
                                    <i class="fas fa-save"></i> Đổi mật khẩu
                                </button>
                            </div>
                        </form>
                        
                        <div class="password-tips">
                            <h4><i class="fas fa-shield-alt"></i> Gợi ý mật khẩu mạnh</h4>
                            <ul>
                                <li><i class="fas fa-check"></i> Sử dụng ít nhất 8 ký tự</li>
                                <li><i class="fas fa-check"></i> Kết hợp chữ hoa và chữ thường</li>
                                <li><i class="fas fa-check"></i> Thêm số và ký tự đặc biệt</li>
                                <li><i class="fas fa-check"></i> Không sử dụng thông tin cá nhân</li>
                            </ul>
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
.profile-page {
    padding: 24px 0 60px;
    background: #f9fafb;
    min-height: 100vh;
}

.password-form-container {
    display: grid;
    grid-template-columns: 1fr 300px;
    gap: 32px;
    padding: 24px;
}

.password-form {
    max-width: 500px;
}

.form-group {
    margin-bottom: 24px;
}

.form-group label {
    display: block;
    font-weight: 600;
    color: var(--gray-700);
    margin-bottom: 8px;
}

.form-group label .required {
    color: #ef4444;
}

.password-input-wrapper {
    position: relative;
    display: flex;
    align-items: center;
}

.password-input-wrapper input {
    width: 100%;
    padding: 12px 48px 12px 16px;
    border: 1px solid #e5e7eb;
    border-radius: 8px;
    font-size: 15px;
    transition: all 0.2s;
}

.password-input-wrapper input:focus {
    border-color: var(--primary);
    box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1);
    outline: none;
}

.toggle-password {
    position: absolute;
    right: 12px;
    background: none;
    border: none;
    color: var(--gray-400);
    cursor: pointer;
    padding: 4px;
}

.toggle-password:hover {
    color: var(--gray-600);
}

.form-hint {
    display: block;
    margin-top: 6px;
    font-size: 13px;
    color: var(--gray-500);
}

.error-message {
    background: #fef2f2;
    border: 1px solid #fecaca;
    color: #dc2626;
    padding: 12px 16px;
    border-radius: 8px;
    margin-bottom: 16px;
    font-size: 14px;
}

.success-message {
    background: #f0fdf4;
    border: 1px solid #bbf7d0;
    color: #16a34a;
    padding: 12px 16px;
    border-radius: 8px;
    margin-bottom: 16px;
    font-size: 14px;
}

.form-actions {
    margin-top: 32px;
}

.form-actions .btn {
    padding: 12px 32px;
    font-size: 15px;
}

.password-tips {
    background: #f0f9ff;
    border: 1px solid #bae6fd;
    border-radius: 12px;
    padding: 20px;
    height: fit-content;
}

.password-tips h4 {
    font-size: 15px;
    font-weight: 600;
    color: #0369a1;
    margin-bottom: 16px;
    display: flex;
    align-items: center;
    gap: 8px;
}

.password-tips ul {
    list-style: none;
    padding: 0;
    margin: 0;
}

.password-tips li {
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 8px 0;
    font-size: 14px;
    color: var(--gray-600);
}

.password-tips li i {
    color: #0ea5e9;
    font-size: 12px;
}

@media (max-width: 992px) {
    .password-form-container {
        grid-template-columns: 1fr;
    }
    
    .password-tips {
        order: -1;
    }
}

@media (max-width: 768px) {
    .profile-layout {
        grid-template-columns: 1fr;
    }
    
    .profile-sidebar {
        display: none;
    }
    
    .password-form-container {
        padding: 16px;
    }
}
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', () => {
    // Check authentication
    const token = localStorage.getItem('auth_token');
    if (!token) {
        window.location.href = '/login';
        return;
    }
    
    // Handle form submission
    document.getElementById('changePasswordForm').addEventListener('submit', handleChangePassword);
});

function togglePassword(inputId) {
    const input = document.getElementById(inputId);
    const icon = input.parentElement.querySelector('.toggle-password i');
    
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

async function handleChangePassword(e) {
    e.preventDefault();
    
    const errorEl = document.getElementById('passwordError');
    const successEl = document.getElementById('passwordSuccess');
    const submitBtn = document.getElementById('submitBtn');
    
    // Hide messages
    errorEl.style.display = 'none';
    successEl.style.display = 'none';
    
    const currentPassword = document.getElementById('currentPassword').value;
    const newPassword = document.getElementById('newPassword').value;
    const confirmPassword = document.getElementById('confirmPassword').value;
    
    // Validate passwords match
    if (newPassword !== confirmPassword) {
        errorEl.textContent = 'Mật khẩu xác nhận không khớp';
        errorEl.style.display = 'block';
        return;
    }
    
    // Validate password length
    if (newPassword.length < 8) {
        errorEl.textContent = 'Mật khẩu mới phải có ít nhất 8 ký tự';
        errorEl.style.display = 'block';
        return;
    }
    
    // Disable button
    submitBtn.disabled = true;
    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Đang xử lý...';
    
    try {
        const token = localStorage.getItem('auth_token');
        const response = await fetch('/api/user/change-password', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Authorization': `Bearer ${token}`,
                'Accept': 'application/json'
            },
            body: JSON.stringify({
                current_password: currentPassword,
                password: newPassword,
                password_confirmation: confirmPassword
            })
        });
        
        const data = await response.json();
        
        if (response.ok) {
            successEl.textContent = 'Đổi mật khẩu thành công!';
            successEl.style.display = 'block';
            
            // Clear form
            document.getElementById('changePasswordForm').reset();
            
            // Scroll to success message
            successEl.scrollIntoView({ behavior: 'smooth', block: 'center' });
        } else {
            errorEl.textContent = data.message || 'Không thể đổi mật khẩu. Vui lòng kiểm tra lại.';
            errorEl.style.display = 'block';
        }
    } catch (error) {
        console.error('Error changing password:', error);
        errorEl.textContent = 'Đã xảy ra lỗi. Vui lòng thử lại sau.';
        errorEl.style.display = 'block';
    } finally {
        submitBtn.disabled = false;
        submitBtn.innerHTML = '<i class="fas fa-save"></i> Đổi mật khẩu';
    }
}
</script>
@endpush

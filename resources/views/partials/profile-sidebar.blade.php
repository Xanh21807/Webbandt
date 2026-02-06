<!-- Profile Sidebar Partial -->
<aside class="profile-sidebar">
    <div class="profile-avatar">
        <div class="avatar-image" id="avatarImage">
            <i class="fas fa-user"></i>
        </div>
        <h3 id="sidebarUserName">Người dùng</h3>
        <p id="sidebarUserEmail">user@example.com</p>
    </div>
    
    <nav class="profile-nav">
        <a href="/profile" class="{{ request()->is('profile') ? 'active' : '' }}">
            <i class="fas fa-user"></i>
            Thông tin tài khoản
        </a>
        <a href="/orders" class="{{ request()->is('orders*') ? 'active' : '' }}">
            <i class="fas fa-shopping-bag"></i>
            Đơn hàng của tôi
        </a>
        <a href="/favorites" class="{{ request()->is('favorites') ? 'active' : '' }}">
            <i class="fas fa-heart"></i>
            Sản phẩm yêu thích
        </a>
        <a href="/change-password" class="{{ request()->is('change-password') ? 'active' : '' }}">
            <i class="fas fa-lock"></i>
            Đổi mật khẩu
        </a>
        <a href="#" onclick="logout()" class="logout-link">
            <i class="fas fa-sign-out-alt"></i>
            Đăng xuất
        </a>
    </nav>
</aside>

<script>
// Load sidebar user info
document.addEventListener('DOMContentLoaded', () => {
    const user = JSON.parse(localStorage.getItem('user') || '{}');
    if (user.name) {
        const nameEl = document.getElementById('sidebarUserName');
        const emailEl = document.getElementById('sidebarUserEmail');
        const avatarEl = document.getElementById('avatarImage');
        
        if (nameEl) nameEl.textContent = user.name;
        if (emailEl) emailEl.textContent = user.email;
        if (avatarEl) {
            if (user.avatar) {
                avatarEl.innerHTML = `<img src="${user.avatar}" alt="Avatar">`;
            } else {
                avatarEl.innerHTML = user.name ? user.name.charAt(0).toUpperCase() : '<i class="fas fa-user"></i>';
            }
        }
    }
});

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
            localStorage.clear();
            window.location.href = '/login';
        });
    } else {
        localStorage.clear();
        window.location.href = '/login';
    }
}
</script>

<style>
.profile-layout {
    display: grid;
    grid-template-columns: 280px 1fr;
    gap: 24px;
}

.profile-sidebar {
    background: white;
    border-radius: 16px;
    padding: 24px;
    box-shadow: var(--shadow-sm);
    height: fit-content;
    position: sticky;
    top: 100px;
}

.profile-avatar {
    text-align: center;
    padding-bottom: 20px;
    border-bottom: 1px solid #e5e7eb;
    margin-bottom: 20px;
}

.avatar-image {
    width: 100px;
    height: 100px;
    margin: 0 auto 16px;
    background: linear-gradient(135deg, var(--primary) 0%, #ff6b6b 100%);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 40px;
}

.avatar-image img {
    width: 100%;
    height: 100%;
    border-radius: 50%;
    object-fit: cover;
}

.profile-avatar h3 {
    font-size: 18px;
    font-weight: 600;
    color: var(--gray-900);
    margin-bottom: 4px;
}

.profile-avatar p {
    font-size: 14px;
    color: var(--gray-500);
}

.profile-nav {
    display: flex;
    flex-direction: column;
    gap: 4px;
}

.profile-nav a {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 12px 16px;
    border-radius: 8px;
    color: var(--gray-700);
    font-size: 14px;
    font-weight: 500;
    transition: all 0.2s;
}

.profile-nav a:hover {
    background: #f3f4f6;
    color: var(--primary);
}

.profile-nav a.active {
    background: #fef2f2;
    color: var(--primary);
}

.profile-nav a i {
    width: 20px;
    text-align: center;
}

.profile-nav .logout-link {
    color: #ef4444;
    margin-top: 8px;
    border-top: 1px solid #e5e7eb;
    padding-top: 12px;
}

.profile-nav .logout-link:hover {
    background: #fef2f2;
}

.profile-main .profile-section {
    background: white;
    border-radius: 16px;
    padding: 24px;
    box-shadow: var(--shadow-sm);
    margin-bottom: 24px;
}

.profile-main .section-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 24px;
}

.profile-main .section-header h2 {
    font-size: 18px;
    font-weight: 700;
    color: var(--gray-900);
    display: flex;
    align-items: center;
    gap: 10px;
}

.loading {
    text-align: center;
    padding: 40px;
    color: var(--gray-500);
}

.loading i {
    font-size: 32px;
    margin-bottom: 16px;
    display: block;
}

@media (max-width: 1024px) {
    .profile-layout {
        grid-template-columns: 1fr;
    }
    
    .profile-sidebar {
        position: static;
    }
}
</style>

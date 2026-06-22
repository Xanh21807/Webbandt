@extends('layouts.admin')

@section('page-title', 'Dashboard')

@section('content')
<!-- Stats Cards -->
<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-icon" style="background: #dbeafe;">
            <i class="fas fa-shopping-bag" style="color: #2563eb;"></i>
        </div>
        <div class="stat-info">
            <h3 id="totalOrders">0</h3>
            <p>Đơn hàng</p>
        </div>
        <div class="stat-trend up">
            <i class="fas fa-arrow-up"></i>
            <span>12%</span>
        </div>
    </div>
    
    <div class="stat-card">
        <div class="stat-icon" style="background: #d1fae5;">
            <i class="fas fa-dollar-sign" style="color: #059669;"></i>
        </div>
        <div class="stat-info">
            <h3 id="totalRevenue">0₫</h3>
            <p>Doanh thu</p>
        </div>
        <div class="stat-trend up">
            <i class="fas fa-arrow-up"></i>
            <span>8%</span>
        </div>
    </div>
    
    <div class="stat-card">
        <div class="stat-icon" style="background: #fef3c7;">
            <i class="fas fa-users" style="color: #d97706;"></i>
        </div>
        <div class="stat-info">
            <h3 id="totalUsers">0</h3>
            <p>Khách hàng</p>
        </div>
        <div class="stat-trend up">
            <i class="fas fa-arrow-up"></i>
            <span>5%</span>
        </div>
    </div>
    
    <div class="stat-card">
        <div class="stat-icon" style="background: #fee2e2;">
            <i class="fas fa-mobile-alt" style="color: #dc2626;"></i>
        </div>
        <div class="stat-info">
            <h3 id="totalProducts">0</h3>
            <p>Sản phẩm</p>
        </div>
        <div class="stat-trend down">
            <i class="fas fa-arrow-down"></i>
            <span>2%</span>
        </div>
    </div>
</div>

<!-- Charts Row -->
<div class="charts-grid">
    <div class="chart-card">
        <div class="chart-header">
            <h3>Doanh thu theo tháng</h3>
            <select id="revenueYear" onchange="loadRevenueChart()">
                <!-- Năm sẽ được tạo tự động bằng JavaScript -->
            </select>
        </div>
        <div class="chart-body">
            <canvas id="revenueChart"></canvas>
        </div>
    </div>
    
    <div class="chart-card">
        <div class="chart-header">
            <h3>Đơn hàng theo trạng thái</h3>
        </div>
        <div class="chart-body">
            <canvas id="ordersChart"></canvas>
        </div>
    </div>
</div>

<!-- Tables Row -->
<div class="tables-grid">
    <!-- Recent Orders -->
    <div class="table-card">
        <div class="table-header">
            <h3>Đơn hàng gần đây</h3>
            <a href="{{ url('/admin/orders') }}" class="btn btn-outline btn-sm">Xem tất cả</a>
        </div>
        <table class="admin-table">
            <thead>
                <tr>
                    <th>Mã đơn</th>
                    <th>Khách hàng</th>
                    <th>Tổng tiền</th>
                    <th>Trạng thái</th>
                </tr>
            </thead>
            <tbody id="recentOrdersTable">
                <tr>
                    <td colspan="4" class="loading-cell">
                        <i class="fas fa-spinner fa-spin"></i> Đang tải...
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
    
    <!-- Top Products -->
    <div class="table-card">
        <div class="table-header">
            <h3>Sản phẩm bán chạy</h3>
            <a href="{{ url('/admin/products') }}" class="btn btn-outline btn-sm">Xem tất cả</a>
        </div>
        <table class="admin-table">
            <thead>
                <tr>
                    <th>Sản phẩm</th>
                    <th>Đã bán</th>
                    <th>Doanh thu</th>
                </tr>
            </thead>
            <tbody id="topProductsTable">
                <tr>
                    <td colspan="3" class="loading-cell">
                        <i class="fas fa-spinner fa-spin"></i> Đang tải...
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
@endsection

@push('styles')
<style>
.stats-grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 20px;
    margin-bottom: 24px;
}

.stat-card {
    background: white;
    border-radius: 12px;
    padding: 20px;
    display: flex;
    align-items: center;
    gap: 16px;
    box-shadow: var(--shadow-sm);
}

.stat-icon {
    width: 56px;
    height: 56px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 24px;
}

.stat-info {
    flex: 1;
}

.stat-info h3 {
    font-size: 24px;
    font-weight: 700;
    color: var(--gray-900);
    margin-bottom: 4px;
}

.stat-info p {
    font-size: 14px;
    color: var(--gray-500);
}

.stat-trend {
    display: flex;
    align-items: center;
    gap: 4px;
    font-size: 13px;
    font-weight: 500;
}

.stat-trend.up {
    color: #059669;
}

.stat-trend.down {
    color: #dc2626;
}

.charts-grid {
    display: grid;
    grid-template-columns: 2fr 1fr;
    gap: 20px;
    margin-bottom: 24px;
}

.chart-card {
    background: white;
    border-radius: 12px;
    padding: 20px;
    box-shadow: var(--shadow-sm);
}

.chart-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 16px;
}

.chart-header h3 {
    font-size: 16px;
    font-weight: 600;
}

.chart-header select {
    padding: 6px 12px;
    border: 1px solid #e5e7eb;
    border-radius: 6px;
    font-size: 14px;
}

.chart-body {
    height: 300px;
}

.tables-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 20px;
}

.table-card {
    background: white;
    border-radius: 12px;
    padding: 20px;
    box-shadow: var(--shadow-sm);
}

.table-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 16px;
}

.table-header h3 {
    font-size: 16px;
    font-weight: 600;
}

.admin-table {
    width: 100%;
    border-collapse: collapse;
}

.admin-table th,
.admin-table td {
    padding: 12px;
    text-align: left;
    border-bottom: 1px solid #e5e7eb;
}

.admin-table th {
    font-size: 13px;
    font-weight: 600;
    color: var(--gray-600);
    text-transform: uppercase;
}

.admin-table td {
    font-size: 14px;
    color: var(--gray-900);
}

.loading-cell {
    text-align: center;
    color: var(--gray-500);
    padding: 40px !important;
}

.product-cell {
    display: flex;
    align-items: center;
    gap: 12px;
}

.product-cell img {
    width: 40px;
    height: 40px;
    border-radius: 8px;
    object-fit: cover;
}

.status-badge {
    display: inline-block;
    padding: 4px 10px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: 500;
}

.status-badge.pending { background: #fef3c7; color: #d97706; }
.status-badge.processing { background: #dbeafe; color: #2563eb; }
.status-badge.shipping { background: #e0e7ff; color: #4f46e5; }
.status-badge.completed { background: #d1fae5; color: #059669; }
.status-badge.cancelled { background: #fee2e2; color: #dc2626; }

@media (max-width: 1200px) {
    .stats-grid {
        grid-template-columns: repeat(2, 1fr);
    }
    .charts-grid,
    .tables-grid {
        grid-template-columns: 1fr;
    }
}

@media (max-width: 768px) {
    .stats-grid {
        grid-template-columns: 1fr;
    }
}
</style>
@endpush

@push('scripts')
<script>
let revenueChart, ordersChart;

document.addEventListener('DOMContentLoaded', () => {
    initYearSelector();
    loadDashboardStats();
    loadRevenueChart();
    loadOrdersChart();
    loadRecentOrders();
    loadTopProducts();
});

// Initialize year selector
function initYearSelector() {
    const yearSelect = document.getElementById('revenueYear');
    const currentYear = new Date().getFullYear();
    
    // Tạo options từ năm hiện tại về 5 năm trước
    for (let year = currentYear; year >= currentYear - 5; year--) {
        const option = document.createElement('option');
        option.value = year;
        option.textContent = year;
        if (year === currentYear) {
            option.selected = true;
        }
        yearSelect.appendChild(option);
    }
}

// Load dashboard stats
async function loadDashboardStats() {
    const token = localStorage.getItem('auth_token');
    
    if (!token) {
        console.error('No auth token found');
        return;
    }
    
    try {
        const response = await fetch('/api/admin/dashboard/stats', {
            headers: {
                'Authorization': `Bearer ${token}`,
                'Accept': 'application/json'
            }
        });
        
        if (response.ok) {
            const data = await response.json();
            document.getElementById('totalOrders').textContent = data.total_orders || 0;
            document.getElementById('totalRevenue').textContent = formatPrice(data.total_revenue || 0) + '₫';
            document.getElementById('totalUsers').textContent = data.total_users || 0;
            document.getElementById('totalProducts').textContent = data.total_products || 0;
        } else {
            console.error('Stats API failed:', response.status, await response.text());
        }
    } catch (error) {
        console.error('Error loading stats:', error);
    }
}

// Load revenue chart
async function loadRevenueChart() {
    const token = localStorage.getItem('auth_token');
    const year = document.getElementById('revenueYear').value;
    
    try {
        const response = await fetch(`/api/admin/dashboard/revenue?year=${year}`, {
            headers: {
                'Authorization': `Bearer ${token}`,
                'Accept': 'application/json'
            }
        });
        
        let labels = ['T1', 'T2', 'T3', 'T4', 'T5', 'T6', 'T7', 'T8', 'T9', 'T10', 'T11', 'T12'];
        let values = Array(12).fill(0);
        
        if (response.ok) {
            const data = await response.json();
            console.log('Revenue data:', data);
            
            // Sử dụng dữ liệu từ API
            if (data.labels && data.values) {
                labels = data.labels;
                values = data.values;
            }
        } else {
            console.error('Revenue API failed:', response.status);
        }
        
        const ctx = document.getElementById('revenueChart').getContext('2d');
        
        if (revenueChart) revenueChart.destroy();
        
        revenueChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Doanh thu',
                    data: values,
                    borderColor: '#d70018',
                    backgroundColor: 'rgba(215, 0, 24, 0.1)',
                    fill: true,
                    tension: 0.4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: value => formatPrice(value) + '₫'
                        }
                    }
                }
            }
        });
    } catch (error) {
        console.error('Error loading revenue chart:', error);
    }
}

// Load orders chart
async function loadOrdersChart() {
    const token = localStorage.getItem('auth_token');
    
    try {
        const response = await fetch('/api/admin/dashboard/orders-status', {
            headers: {
                'Authorization': `Bearer ${token}`,
                'Accept': 'application/json'
            }
        });
        
        let data = {};
        if (response.ok) {
            data = await response.json();
        }
        
        const ctx = document.getElementById('ordersChart').getContext('2d');
        
        if (ordersChart) ordersChart.destroy();
        
        ordersChart = new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: ['Chờ xác nhận', 'Đang xử lý', 'Đang giao', 'Hoàn thành', 'Đã hủy'],
                datasets: [{
                    data: [
                        data.pending || 0,
                        data.processing || 0,
                        data.shipping || 0,
                        data.completed || 0,
                        data.cancelled || 0
                    ],
                    backgroundColor: ['#fbbf24', '#3b82f6', '#8b5cf6', '#10b981', '#ef4444']
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom'
                    }
                }
            }
        });
    } catch (error) {
        console.error('Error loading orders chart:', error);
    }
}

// Load recent orders
async function loadRecentOrders() {
    const token = localStorage.getItem('auth_token');
    const tbody = document.getElementById('recentOrdersTable');
    
    try {
        const response = await fetch('/api/admin/dashboard/recent-orders', {
            headers: {
                'Authorization': `Bearer ${token}`,
                'Accept': 'application/json'
            }
        });
        
        if (response.ok) {
            const result = await response.json();
            const orders = result.data || [];
            
            if (orders.length === 0) {
                tbody.innerHTML = '<tr><td colspan="4" class="loading-cell">Chưa có đơn hàng</td></tr>';
            } else {
                tbody.innerHTML = orders.map(order => `
                    <tr>
                        <td><a href="/admin/orders/${order.id}">#${order.id}</a></td>
                        <td>${order.customer_name || 'Khách vãng lai'}</td>
                        <td>${formatPrice(order.total)}₫</td>
                        <td><span class="status-badge ${order.status}">${getStatusText(order.status)}</span></td>
                    </tr>
                `).join('');
            }
        }
    } catch (error) {
        console.error('Error loading recent orders:', error);
        tbody.innerHTML = '<tr><td colspan="4" class="loading-cell">Không thể tải dữ liệu</td></tr>';
    }
}

// Load top products
async function loadTopProducts() {
    const token = localStorage.getItem('auth_token');
    const tbody = document.getElementById('topProductsTable');
    
    try {
        const response = await fetch('/api/admin/dashboard/top-products', {
            headers: {
                'Authorization': `Bearer ${token}`,
                'Accept': 'application/json'
            }
        });
        
        if (response.ok) {
            const result = await response.json();
            const products = result.data || [];
            
            if (products.length === 0) {
                tbody.innerHTML = '<tr><td colspan="3" class="loading-cell">Chưa có dữ liệu</td></tr>';
            } else {
                tbody.innerHTML = products.map(product => `
                    <tr>
                        <td>
                            <div class="product-cell">
                                <img src="${product.image_url || 'https://placehold.co/40x40/f5f5f5/999?text=?'}" alt="${product.name}" onerror="this.src='https://placehold.co/40x40/f5f5f5/999?text=?'">
                                <span>${product.name}</span>
                            </div>
                        </td>
                        <td>${product.total_sold || 0}</td>
                        <td>${formatPrice(product.total_revenue || 0)}₫</td>
                    </tr>
                `).join('');
            }
        }
    } catch (error) {
        console.error('Error loading top products:', error);
        tbody.innerHTML = '<tr><td colspan="3" class="loading-cell">Không thể tải dữ liệu</td></tr>';
    }
}

function getStatusText(status) {
    const statusMap = {
        'pending': 'Chờ xác nhận',
        'confirmed': 'Đã xác nhận',
        'processing': 'Đang xử lý',
        'shipping': 'Đang giao',
        'completed': 'Hoàn thành',
        'cancelled': 'Đã hủy'
    };
    return statusMap[status] || status;
}
</script>
@endpush

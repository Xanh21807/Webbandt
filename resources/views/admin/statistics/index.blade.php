@extends('layouts.admin')

@section('page-title', 'Thống kê')

@section('content')
<!-- Date Range Filter -->
<div class="filter-bar">
    <div class="date-range">
        <label>Từ ngày:</label>
        <input type="date" id="startDate" onchange="loadStatistics()">
        <label>Đến ngày:</label>
        <input type="date" id="endDate" onchange="loadStatistics()">
    </div>
    <div class="filter-actions">
        <button class="btn btn-primary" onclick="loadStatistics()">
            <i class="fas fa-sync-alt"></i> Cập nhật thống kê
        </button>
        <button class="btn btn-outline" onclick="exportReport()">
            <i class="fas fa-file-export"></i> Xuất báo cáo
        </button>
    </div>
</div>

<!-- Summary Stats -->
<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-icon" style="background: #dbeafe;">
            <i class="fas fa-shopping-bag" style="color: #2563eb;"></i>
        </div>
        <div class="stat-info">
            <h3 id="totalOrders">0</h3>
            <p>Tổng đơn hàng</p>
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
    </div>
    
    <div class="stat-card">
        <div class="stat-icon" style="background: #fef3c7;">
            <i class="fas fa-users" style="color: #d97706;"></i>
        </div>
        <div class="stat-info">
            <h3 id="totalUsers">0</h3>
            <p>Khách hàng</p>
        </div>
    </div>
    
    <div class="stat-card">
        <div class="stat-icon" style="background: #e0e7ff;">
            <i class="fas fa-user-plus" style="color: #4f46e5;"></i>
        </div>
        <div class="stat-info">
            <h3 id="newUsers">0</h3>
            <p>Khách hàng mới</p>
        </div>
    </div>
</div>

<!-- Charts Row -->
<div class="charts-grid">
    <div class="chart-card wide">
        <div class="chart-header">
            <h3>Doanh thu theo ngày</h3>
        </div>
        <div class="chart-body">
            <canvas id="revenueByDayChart"></canvas>
        </div>
    </div>
</div>

<div class="charts-grid">
    <div class="chart-card">
        <div class="chart-header">
            <h3>Đơn hàng theo trạng thái</h3>
        </div>
        <div class="chart-body">
            <canvas id="ordersStatusChart"></canvas>
        </div>
    </div>
    
    <div class="chart-card">
        <div class="chart-header">
            <h3>Doanh thu theo danh mục</h3>
        </div>
        <div class="chart-body">
            <canvas id="categoryChart"></canvas>
        </div>
    </div>
</div>

<!-- Best Selling Products -->
<div class="table-card">
    <div class="table-header">
        <h3>Sản phẩm bán chạy</h3>
    </div>
    <div class="table-wrapper">
        <table class="admin-table best-selling-table">
            <thead>
                <tr>
                    <th style="width: 35%; text-align: left;">Sản phẩm</th>
                    <th style="width: 20%; text-align: left;">Thương hiệu</th>
                    <th style="width: 15%; text-align: right;">Giá</th>
                    <th style="width: 15%; text-align: right;">Đã bán</th>
                    <th style="width: 15%; text-align: right;">Doanh thu</th>
                </tr>
            </thead>
            <tbody id="bestSellingProducts">
                <tr>
                    <td colspan="5" class="text-center">
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
.filter-bar {
    display: flex;
    justify-content: space-between;
    align-items: center;
    background: white;
    padding: 16px 20px;
    border-radius: 12px;
    margin-bottom: 24px;
    box-shadow: var(--shadow-sm);
}

.date-range {
    display: flex;
    align-items: center;
    gap: 12px;
}

.date-range label {
    font-weight: 500;
    color: var(--gray-600);
}

.date-range input {
    padding: 8px 12px;
    border: 1px solid #e5e7eb;
    border-radius: 8px;
    font-size: 14px;
}

.filter-actions {
    display: flex;
    gap: 12px;
}

.chart-card.wide {
    grid-column: span 2;
}

.table-wrapper {
    overflow-x: auto;
}

.best-selling-table {
    table-layout: fixed;
    width: 100%;
    min-width: 800px;
}

.best-selling-table th,
.best-selling-table td {
    padding: 16px;
    white-space: normal;
    word-wrap: break-word;
}

.best-selling-table th {
    font-weight: 600;
    text-transform: uppercase;
    font-size: 12px;
    letter-spacing: 0.5px;
}

.best-selling-table td {
    font-size: 14px;
}

.best-selling-table .product-name {
    font-weight: 600;
    color: var(--gray-900);
}

.best-selling-table .brand-name {
    color: var(--gray-600);
}

.best-selling-table .price-cell {
    font-weight: 600;
    color: var(--gray-900);
}

.best-selling-table .sold-cell {
    font-weight: 600;
    color: #2563eb;
}

.best-selling-table .revenue-cell {
    font-weight: 700;
    color: var(--primary);
    font-size: 15px;
}

@media (max-width: 992px) {
    .filter-bar {
        flex-direction: column;
        gap: 16px;
    }
    
    .date-range {
        flex-wrap: wrap;
    }
    
    .chart-card.wide {
        grid-column: span 1;
    }
}
</style>
@endpush

@push('scripts')
<script>
let revenueByDayChart, ordersStatusChart, categoryChart;

document.addEventListener('DOMContentLoaded', () => {
    // Set default date range to current month
    const now = new Date();
    const firstDay = new Date(now.getFullYear(), now.getMonth(), 1);
    const lastDay = new Date(now.getFullYear(), now.getMonth() + 1, 0);
    
    document.getElementById('startDate').value = firstDay.toISOString().split('T')[0];
    document.getElementById('endDate').value = lastDay.toISOString().split('T')[0];
    
    loadStatistics();
});

async function loadStatistics() {
    const token = localStorage.getItem('auth_token');
    const startDate = document.getElementById('startDate').value;
    const endDate = document.getElementById('endDate').value;
    
    try {
        const response = await fetch(`/api/admin/statistics/dashboard?start_date=${startDate}&end_date=${endDate}`, {
            headers: {
                'Authorization': `Bearer ${token}`,
                'Accept': 'application/json'
            }
        });
        
        if (response.ok) {
            const data = await response.json();
            const stats = data.data;
            
            // Update summary
            document.getElementById('totalOrders').textContent = stats.summary?.total_orders || 0;
            document.getElementById('totalRevenue').textContent = formatPrice(stats.summary?.total_revenue || 0) + '₫';
            document.getElementById('totalUsers').textContent = stats.summary?.total_users || 0;
            document.getElementById('newUsers').textContent = stats.summary?.new_users || 0;
            
            // Update charts
            updateRevenueByDayChart(stats.revenue_by_day || []);
            updateOrdersStatusChart(stats.order_statistics || []);
            updateBestSellingProducts(stats.best_selling_products || []);
        }
    } catch (error) {
        console.error('Error loading statistics:', error);
    }
    
    // Load category report
    loadCategoryReport();
}

function updateRevenueByDayChart(data) {
    const ctx = document.getElementById('revenueByDayChart').getContext('2d');
    
    if (revenueByDayChart) revenueByDayChart.destroy();
    
    const labels = data.map(d => new Date(d.date).toLocaleDateString('vi-VN'));
    const values = data.map(d => d.revenue);
    
    revenueByDayChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: labels,
            datasets: [{
                label: 'Doanh thu',
                data: values,
                backgroundColor: '#d70018',
                borderRadius: 4
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
}

function updateOrdersStatusChart(data) {
    const ctx = document.getElementById('ordersStatusChart').getContext('2d');
    
    if (ordersStatusChart) ordersStatusChart.destroy();
    
    const statusLabels = {
        'pending': 'Chờ xác nhận',
        'confirmed': 'Đã xác nhận',
        'processing': 'Đang xử lý',
        'shipping': 'Đang giao',
        'completed': 'Hoàn thành',
        'cancelled': 'Đã hủy'
    };
    
    const statusColors = {
        'pending': '#f59e0b',
        'confirmed': '#3b82f6',
        'processing': '#8b5cf6',
        'shipping': '#06b6d4',
        'completed': '#10b981',
        'cancelled': '#ef4444'
    };
    
    const labels = data.map(d => statusLabels[d.status] || d.status);
    const values = data.map(d => d.count);
    const colors = data.map(d => statusColors[d.status] || '#9ca3af');
    
    ordersStatusChart = new Chart(ctx, {
        type: 'doughnut',
        data: {
            labels: labels,
            datasets: [{
                data: values,
                backgroundColor: colors
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
}

async function loadCategoryReport() {
    const token = localStorage.getItem('auth_token');
    const startDate = document.getElementById('startDate').value;
    const endDate = document.getElementById('endDate').value;
    
    try {
        const response = await fetch(`/api/admin/statistics/categories?start_date=${startDate}&end_date=${endDate}`, {
            headers: {
                'Authorization': `Bearer ${token}`,
                'Accept': 'application/json'
            }
        });
        
        if (response.ok) {
            const data = await response.json();
            updateCategoryChart(data.data || []);
        }
    } catch (error) {
        console.error('Error loading category report:', error);
    }
}

function updateCategoryChart(data) {
    const ctx = document.getElementById('categoryChart').getContext('2d');
    
    if (categoryChart) categoryChart.destroy();
    
    const labels = data.map(d => d.name);
    const values = data.map(d => d.total_revenue);
    const colors = ['#d70018', '#3b82f6', '#10b981', '#f59e0b', '#8b5cf6', '#ec4899', '#06b6d4'];
    
    categoryChart = new Chart(ctx, {
        type: 'pie',
        data: {
            labels: labels,
            datasets: [{
                data: values,
                backgroundColor: colors.slice(0, labels.length)
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
}

function updateBestSellingProducts(products) {
    const tbody = document.getElementById('bestSellingProducts');
    
    if (products.length === 0) {
        tbody.innerHTML = `
            <tr>
                <td colspan="5" class="text-center text-muted">Không có dữ liệu</td>
            </tr>
        `;
        return;
    }
    
    tbody.innerHTML = products.map(product => `
        <tr>
            <td class="product-name" style="text-align: left;">${product.name}</td>
            <td class="brand-name" style="text-align: left;">${product.brand || 'N/A'}</td>
            <td class="price-cell" style="text-align: right;">${formatPrice(product.price)}₫</td>
            <td class="sold-cell" style="text-align: right;">${product.total_sold}</td>
            <td class="revenue-cell" style="text-align: right;">${formatPrice(product.total_sold * product.price)}₫</td>
        </tr>
    `).join('');
}

function formatPrice(price) {
    return new Intl.NumberFormat('vi-VN').format(price);
}

function exportReport() {
    const startDate = document.getElementById('startDate').value;
    const endDate = document.getElementById('endDate').value;
    
    // Get all statistics data
    const totalOrders = document.getElementById('totalOrders').textContent;
    const totalRevenue = document.getElementById('totalRevenue').textContent;
    const totalProducts = document.getElementById('totalProducts').textContent;
    const totalCustomers = document.getElementById('totalCustomers').textContent;
    
    // Create print window
    const printWindow = window.open('', '_blank');
    
    const htmlContent = `
        <!DOCTYPE html>
        <html>
        <head>
            <meta charset="UTF-8">
            <title>Báo cáo thống kê - XanhStore</title>
            <style>
                body {
                    font-family: Arial, sans-serif;
                    padding: 20px;
                    max-width: 1000px;
                    margin: 0 auto;
                }
                .header {
                    text-align: center;
                    margin-bottom: 30px;
                    border-bottom: 2px solid #d70018;
                    padding-bottom: 20px;
                }
                .header h1 {
                    color: #d70018;
                    margin: 0;
                }
                .header p {
                    color: #666;
                    margin: 5px 0;
                }
                .stats-grid {
                    display: grid;
                    grid-template-columns: repeat(2, 1fr);
                    gap: 20px;
                    margin-bottom: 30px;
                }
                .stat-card {
                    border: 1px solid #ddd;
                    padding: 15px;
                    border-radius: 8px;
                }
                .stat-card h3 {
                    font-size: 24px;
                    color: #d70018;
                    margin: 0 0 5px 0;
                }
                .stat-card p {
                    color: #666;
                    margin: 0;
                }
                table {
                    width: 100%;
                    border-collapse: collapse;
                    margin-top: 20px;
                }
                table th, table td {
                    border: 1px solid #ddd;
                    padding: 10px;
                    text-align: left;
                }
                table th {
                    background-color: #d70018;
                    color: white;
                }
                .footer {
                    margin-top: 30px;
                    text-align: center;
                    color: #666;
                    font-size: 12px;
                    border-top: 1px solid #ddd;
                    padding-top: 20px;
                }
                @media print {
                    .no-print {
                        display: none;
                    }
                }
            </style>
        </head>
        <body>
            <div class="header">
                <h1>XanhStore</h1>
                <h2>Báo cáo thống kê kinh doanh</h2>
                <p>Từ ngày: ${startDate || 'Không giới hạn'} - Đến ngày: ${endDate || 'Không giới hạn'}</p>
                <p>Ngày xuất: ${new Date().toLocaleDateString('vi-VN', {day: '2-digit', month: '2-digit', year: 'numeric', hour: '2-digit', minute: '2-digit'})}</p>
            </div>
            
            <div class="stats-grid">
                <div class="stat-card">
                    <h3>${totalOrders}</h3>
                    <p>Tổng đơn hàng</p>
                </div>
                <div class="stat-card">
                    <h3>${totalRevenue}</h3>
                    <p>Tổng doanh thu</p>
                </div>
                <div class="stat-card">
                    <h3>${totalProducts}</h3>
                    <p>Tổng sản phẩm</p>
                </div>
                <div class="stat-card">
                    <h3>${totalCustomers}</h3>
                    <p>Tổng khách hàng</p>
                </div>
            </div>
            
            <h3>Sản phẩm bán chạy nhất</h3>
            <table>
                <thead>
                    <tr>
                        <th>Sản phẩm</th>
                        <th>Thương hiệu</th>
                        <th style="text-align: right;">Giá bán</th>
                        <th style="text-align: right;">Đã bán</th>
                        <th style="text-align: right;">Doanh thu</th>
                    </tr>
                </thead>
                <tbody>
                    ${document.getElementById('bestSellingProducts').innerHTML}
                </tbody>
            </table>
            
            <div class="footer">
                <p>Báo cáo này được tạo tự động bởi hệ thống quản trị XanhStore</p>
            </div>
            
            <div class="no-print" style="text-align: center; margin-top: 20px;">
                <button onclick="window.print()" style="padding: 10px 20px; background: #d70018; color: white; border: none; border-radius: 5px; cursor: pointer; font-size: 14px;">
                    <i class="fas fa-print"></i> In báo cáo
                </button>
                <button onclick="window.close()" style="padding: 10px 20px; background: #666; color: white; border: none; border-radius: 5px; cursor: pointer; font-size: 14px; margin-left: 10px;">
                    Đóng
                </button>
            </div>
        </body>
        </html>
    `;
    
    printWindow.document.write(htmlContent);
    printWindow.document.close();
}
</script>
@endpush

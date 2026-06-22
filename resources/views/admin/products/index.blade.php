@extends('layouts.admin')

@section('page-title', 'Quản lý sản phẩm')

@section('content')
<!-- Header -->
<div class="page-actions">
    <div class="search-box">
        <i class="fas fa-search"></i>
        <input type="text" id="searchInput" placeholder="Tìm kiếm sản phẩm..." onkeyup="searchProducts()">
    </div>
    <div class="action-buttons">
        <button class="btn btn-primary" onclick="openModal('add')">
            <i class="fas fa-plus"></i> Thêm sản phẩm
        </button>
    </div>
</div>

<!-- Filters -->
<div class="filters-bar">
    <select id="categoryFilter" onchange="loadProducts()">
        <option value="">Tất cả danh mục</option>
    </select>
    <select id="statusFilter" onchange="loadProducts()">
        <option value="">Tất cả trạng thái</option>
        <option value="active">Đang bán</option>
        <option value="inactive">Ngừng bán</option>
    </select>
    <select id="stockFilter" onchange="loadProducts()">
        <option value="">Tất cả kho</option>
        <option value="in_stock">Còn hàng</option>
        <option value="low_stock">Sắp hết</option>
        <option value="out_of_stock">Hết hàng</option>
    </select>
</div>

<!-- Products Table -->
<div class="table-card">
    <table class="admin-table">
        <thead>
            <tr>
                <th><input type="checkbox" id="selectAll" onchange="toggleSelectAll()"></th>
                <th>Sản phẩm</th>
                <th>Giá</th>
                <th>Kho</th>
                <th>Danh mục</th>
                <th>Trạng thái</th>
                <th>Thao tác</th>
            </tr>
        </thead>
        <tbody id="productsTable">
            <tr>
                <td colspan="8" class="loading-cell">
                    <i class="fas fa-spinner fa-spin"></i> Đang tải...
                </td>
            </tr>
        </tbody>
    </table>
</div>

<!-- Pagination -->
<div class="pagination" id="pagination"></div>

<!-- Product Modal -->
<div class="modal" id="productModal">
    <div class="modal-overlay" onclick="closeModal()"></div>
    <div class="modal-content modal-lg">
        <div class="modal-header">
            <h3 id="modalTitle">Thêm sản phẩm</h3>
            <button class="modal-close" onclick="closeModal()">&times;</button>
        </div>
        <div class="modal-body">
            <form id="productForm">
                <input type="hidden" id="productId">
                
                <div class="form-row">
                    <div class="form-group">
                        <label for="productName">Tên sản phẩm *</label>
                        <input type="text" id="productName" required>
                    </div>
                    <div class="form-group">
                        <label for="productBrand">Thương hiệu *</label>
                        <input type="text" id="productBrand" required>
                    </div>
                </div>
                
                <div class="form-row">
                    <div class="form-group">
                        <label for="productPrice">Giá bán *</label>
                        <input type="number" id="productPrice" required>
                    </div>
                    <div class="form-group">
                        <label for="productCategory">Danh mục *</label>
                        <select id="productCategory" required>
                            <option value="">Chọn danh mục</option>
                        </select>
                    </div>
                </div>
                
                <div class="form-row">
                    <div class="form-group">
                        <label for="productRam">RAM</label>
                        <input type="text" id="productRam" placeholder="Ví dụ: 8GB">
                    </div>
                    <div class="form-group">
                        <label for="productStorage">Bộ nhớ</label>
                        <input type="text" id="productStorage" placeholder="Ví dụ: 256GB">
                    </div>
                </div>
                
                <div class="form-row">
                    <div class="form-group">
                        <label for="productBattery">Pin</label>
                        <input type="text" id="productBattery" placeholder="Ví dụ: 5000mAh">
                    </div>
                    <div class="form-group">
                        <label for="productQuantity">Số lượng tồn kho *</label>
                        <input type="number" id="productQuantity" value="0" required>
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="productStatus">Trạng thái *</label>
                    <select id="productStatus" required>
                        <option value="active">Đang bán</option>
                        <option value="inactive">Ngừng bán</option>
                    </select>
                </div>
                
                <div class="form-group">
                    <label for="productDescription">Mô tả sản phẩm</label>
                    <textarea id="productDescription" rows="4"></textarea>
                </div>
                
                <div class="form-group">
                    <label>Hình ảnh sản phẩm</label>
                    <div class="image-upload" id="imageUpload">
                        <input type="file" id="productImages" accept="image/*" multiple onchange="previewImages(this)">
                        <div class="upload-placeholder">
                            <i class="fas fa-cloud-upload-alt"></i>
                            <p>Kéo thả hoặc click để tải ảnh lên</p>
                        </div>
                    </div>
                    <div class="image-preview" id="imagePreview"></div>
                </div>
                

            </form>
        </div>
        <div class="modal-footer">
            <button class="btn btn-outline" onclick="closeModal()">Hủy</button>
            <button class="btn btn-primary" onclick="saveProduct()">
                <i class="fas fa-save"></i> Lưu sản phẩm
            </button>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
.page-actions {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 20px;
}

.search-box {
    position: relative;
    width: 300px;
}

.search-box i {
    position: absolute;
    left: 14px;
    top: 50%;
    transform: translateY(-50%);
    color: var(--gray-500);
}

.search-box input {
    width: 100%;
    padding: 10px 14px 10px 40px;
    border: 1px solid #e5e7eb;
    border-radius: 8px;
    font-size: 14px;
}

.action-buttons {
    display: flex;
    gap: 12px;
}

.filters-bar {
    display: flex;
    gap: 12px;
    margin-bottom: 20px;
}

.filters-bar select {
    padding: 10px 14px;
    border: 1px solid #e5e7eb;
    border-radius: 8px;
    font-size: 14px;
    min-width: 150px;
}

.table-card {
    background: white;
    border-radius: 12px;
    box-shadow: var(--shadow-sm);
    overflow: hidden;
}

.admin-table {
    width: 100%;
    border-collapse: collapse;
}

.admin-table th,
.admin-table td {
    padding: 14px 16px;
    text-align: left;
    border-bottom: 1px solid #e5e7eb;
}

.admin-table th {
    background: #f9fafb;
    font-size: 13px;
    font-weight: 600;
    color: var(--gray-600);
    text-transform: uppercase;
}

.admin-table td {
    font-size: 14px;
}

.loading-cell {
    text-align: center;
    padding: 60px !important;
    color: var(--gray-500);
}

.product-cell {
    display: flex;
    align-items: center;
    gap: 12px;
}

.product-cell img {
    width: 50px;
    height: 50px;
    border-radius: 8px;
    object-fit: cover;
}

.product-cell .product-name {
    font-weight: 500;
    color: var(--gray-900);
}

.price-cell .price-old {
    font-size: 12px;
    color: var(--gray-500);
    text-decoration: line-through;
}

.price-cell .price-current {
    font-weight: 600;
    color: var(--primary);
}

.stock-badge {
    display: inline-block;
    padding: 4px 10px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: 500;
}

.stock-badge.in-stock { background: #d1fae5; color: #059669; }
.stock-badge.low-stock { background: #fef3c7; color: #d97706; }
.stock-badge.out-of-stock { background: #fee2e2; color: #dc2626; }

.status-badge {
    display: inline-block;
    padding: 6px 12px;
    border-radius: 6px;
    font-size: 13px;
    font-weight: 500;
}

.status-badge.status-active {
    background: #d1fae5;
    color: #059669;
}

.status-badge.status-inactive {
    background: #fee2e2;
    color: #dc2626;
}

.status-toggle {
    position: relative;
    display: inline-block;
    width: 44px;
    height: 24px;
}

.status-toggle input {
    opacity: 0;
    width: 0;
    height: 0;
}

.status-toggle .slider {
    position: absolute;
    cursor: pointer;
    inset: 0;
    background: #e5e7eb;
    border-radius: 24px;
    transition: 0.3s;
}

.status-toggle .slider:before {
    position: absolute;
    content: "";
    height: 18px;
    width: 18px;
    left: 3px;
    bottom: 3px;
    background: white;
    border-radius: 50%;
    transition: 0.3s;
}

.status-toggle input:checked + .slider {
    background: #10b981;
}

.status-toggle input:checked + .slider:before {
    transform: translateX(20px);
}

.action-btns {
    display: flex;
    gap: 8px;
}

.action-btns button {
    width: 32px;
    height: 32px;
    border: none;
    border-radius: 6px;
    cursor: pointer;
    transition: all 0.2s;
}

.action-btns .btn-edit {
    background: #dbeafe;
    color: #2563eb;
}

.action-btns .btn-delete {
    background: #fee2e2;
    color: #dc2626;
}

.action-btns button:hover {
    opacity: 0.8;
}

/* Modal */
.modal {
    display: none !important;
    position: fixed !important;
    top: 0 !important;
    left: 0 !important;
    right: 0 !important;
    bottom: 0 !important;
    z-index: 9999 !important;
    align-items: center !important;
    justify-content: center !important;
    background-color: transparent !important;
    border-radius: 0 !important;
    max-width: none !important;
    max-height: none !important;
    overflow: visible !important;
    transform: none !important;
}

.modal.active {
    display: flex !important;
}

.modal-overlay {
    position: fixed !important;
    top: 0 !important;
    left: 0 !important;
    right: 0 !important;
    bottom: 0 !important;
    background: rgba(0, 0, 0, 0.5) !important;
    z-index: 9999 !important;
}

.modal-content {
    position: relative !important;
    background: white !important;
    border-radius: 16px !important;
    max-width: 600px !important;
    width: 90% !important;
    max-height: 90vh !important;
    overflow: hidden !important;
    display: flex !important;
    flex-direction: column !important;
    z-index: 10000 !important;
    transform: none !important;
    box-shadow: 0 10px 40px rgba(215, 0, 24, 0.15) !important;
    /* Ẩn thanh trượt */
    scrollbar-width: none;
    -ms-overflow-style: none;
}

.modal-content::-webkit-scrollbar {
    display: none;
}

.modal-lg {
    max-width: 800px;
}

.modal-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 20px 24px;
    border-bottom: 1px solid #e5e7eb;
}

.modal-header h3 {
    font-size: 18px;
    font-weight: 700;
}

.modal-close {
    width: 32px;
    height: 32px;
    border: none;
    background: #f3f4f6;
    border-radius: 50%;
    font-size: 20px;
    cursor: pointer;
}

.modal-body {
    padding: 24px;
    overflow-y: auto;
    flex: 1;
    /* Ẩn thanh trượt */
    scrollbar-width: none; /* Firefox */
    -ms-overflow-style: none; /* IE and Edge */
}

.modal-body::-webkit-scrollbar {
    display: none; /* Chrome, Safari, Opera */
}

.modal-footer {
    display: flex;
    justify-content: flex-end;
    gap: 12px;
    padding: 20px 24px;
    border-top: 1px solid #e5e7eb;
}

.form-row {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 16px;
}

.form-group {
    margin-bottom: 16px;
}

.form-group label {
    display: block;
    font-size: 14px;
    font-weight: 500;
    color: var(--gray-700);
    margin-bottom: 6px;
}

.form-group input,
.form-group select,
.form-group textarea {
    width: 100%;
    padding: 10px 14px;
    border: 1px solid #e5e7eb;
    border-radius: 8px;
    font-size: 14px;
}

.image-upload {
    border: 2px dashed #e5e7eb;
    border-radius: 12px;
    padding: 40px;
    text-align: center;
    cursor: pointer;
    position: relative;
}

.image-upload input {
    position: absolute;
    inset: 0;
    opacity: 0;
    cursor: pointer;
}

.upload-placeholder i {
    font-size: 48px;
    color: #d1d5db;
    margin-bottom: 12px;
}

.upload-placeholder p {
    color: var(--gray-500);
}

.image-preview {
    display: flex;
    gap: 12px;
    flex-wrap: wrap;
    margin-top: 12px;
}

.image-preview .preview-item {
    position: relative;
    width: 80px;
    height: 80px;
}

.image-preview .preview-item img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    border-radius: 8px;
}

.image-preview .preview-item button {
    position: absolute;
    top: -8px;
    right: -8px;
    width: 20px;
    height: 20px;
    border-radius: 50%;
    border: none;
    background: #ef4444;
    color: white;
    font-size: 12px;
    cursor: pointer;
}

.pagination {
    display: flex;
    justify-content: center;
    gap: 8px;
    margin-top: 24px;
}

.pagination button {
    min-width: 40px;
    height: 40px;
    border: 1px solid #e5e7eb;
    border-radius: 8px;
    background: white;
    cursor: pointer;
}

.pagination button.active {
    background: var(--primary);
    border-color: var(--primary);
    color: white;
}
</style>
@endpush

@push('scripts')
<script>
let products = [];
let categories = [];
let currentPage = 1;

document.addEventListener('DOMContentLoaded', () => {
    loadCategories();
    loadProducts();
});

// Load categories
async function loadCategories() {
    const token = localStorage.getItem('auth_token');
    
    try {
        const response = await fetch('/api/categories', {
            headers: {
                'Accept': 'application/json'
            }
        });
        
        if (response.ok) {
            const data = await response.json();
            // API returns paginated data: data.data.data
            categories = Array.isArray(data.data?.data) ? data.data.data : 
                        (Array.isArray(data.data) ? data.data : 
                        (Array.isArray(data) ? data : []));
            
            console.log('Categories loaded:', categories);
            
            const filterSelect = document.getElementById('categoryFilter');
            const formSelect = document.getElementById('productCategory');
            
            // Clear existing options except the first one
            filterSelect.innerHTML = '<option value="">Tất cả danh mục</option>';
            formSelect.innerHTML = '<option value="">Chọn danh mục</option>';
            
            // Only process if categories is an array
            if (Array.isArray(categories) && categories.length > 0) {
                categories.forEach(cat => {
                    filterSelect.innerHTML += `<option value="${cat.id}">${cat.name}</option>`;
                    formSelect.innerHTML += `<option value="${cat.id}">${cat.name}</option>`;
                });
            } else {
                console.error('No categories found or invalid format');
            }
        }
    } catch (error) {
        console.error('Error loading categories:', error);
        categories = []; // Set to empty array on error
    }
}

// Load products
async function loadProducts(page = 1) {
    currentPage = page;
    const token = localStorage.getItem('auth_token');
    const tbody = document.getElementById('productsTable');
    
    const params = new URLSearchParams();
    params.append('page', page);
    
    const category = document.getElementById('categoryFilter').value;
    const status = document.getElementById('statusFilter').value;
    const stock = document.getElementById('stockFilter').value;
    const search = document.getElementById('searchInput').value;
    
    if (category) params.append('category_id', category);
    if (status) params.append('status', status);
    if (stock) params.append('stock', stock);
    if (search) params.append('keyword', search);
    
    tbody.innerHTML = '<tr><td colspan="7" class="loading-cell"><i class="fas fa-spinner fa-spin"></i> Đang tải...</td></tr>';
    
    try {
        const response = await fetch(`/api/admin/products?${params.toString()}`, {
            headers: {
                'Authorization': `Bearer ${token}`,
                'Accept': 'application/json'
            }
        });
        
        if (response.ok) {
            const result = await response.json();
            const data = result.data || {};
            products = data.data || [];
            
            if (products.length === 0) {
                tbody.innerHTML = '<tr><td colspan="7" class="loading-cell">Không có sản phẩm nào</td></tr>';
            } else {
                const rows = [];
                products.forEach(product => {
                    const rawImg = product.images && product.images[0] ? product.images[0].image_url : null;
                    // image_url đã là URL đầy đủ (http://...), dùng trực tiếp
                    const imageUrl = rawImg || 'https://placehold.co/50x50/f5f5f5/999?text=?';
                    const stock = product.quantity || product.stock || 0;
                    const categoryName = product.category ? product.category.name : '-';
                    const priceDisplay = product.sale_price 
                        ? '<span class="price-old">' + formatPrice(product.price) + '₫</span><br><span class="price-current">' + formatPrice(product.sale_price) + '₫</span>'
                        : '<span class="price-current">' + formatPrice(product.price) + '₫</span>';
                    
                    const row = '<tr>' +
                        '<td><input type="checkbox" class="product-checkbox" value="' + product.id + '"></td>' +
                        '<td><div class="product-cell">' +
                            '<img src="' + imageUrl + '" alt="' + product.name + '" onerror="this.src=\'https://placehold.co/50x50/f5f5f5/999?text=?\'">' +
                            '<span class="product-name">' + product.name + '</span>' +
                        '</div></td>' +
                        '<td class="price-cell">' + priceDisplay + '</td>' +
                        '<td><span class="stock-badge ' + getStockClass(stock) + '">' + stock + '</span></td>' +
                        '<td>' + categoryName + '</td>' +
                        '<td>' +
                            '<span class="status-badge ' + (product.status === 'active' ? 'status-active' : 'status-inactive') + '">' +
                                (product.status === 'active' ? 'Đang bán' : 'Ngừng bán') +
                            '</span>' +
                        '</td>' +
                        '<td>' +
                            '<div class="action-btns">' +
                                '<button class="btn-edit" onclick="editProduct(' + product.id + ')" title="Sửa">' +
                                    '<i class="fas fa-edit"></i>' +
                                '</button>' +
                                '<button class="btn-delete" onclick="deleteProduct(' + product.id + ')" title="Xóa">' +
                                    '<i class="fas fa-trash"></i>' +
                                '</button>' +
                            '</div>' +
                        '</td>' +
                    '</tr>';
                    rows.push(row);
                });
                tbody.innerHTML = rows.join('');
            }
            
            if (data.last_page) {
                renderPagination(data.current_page, data.last_page);
            }
        }
    } catch (error) {
        console.error('Error loading products:', error);
        tbody.innerHTML = '<tr><td colspan="7" class="loading-cell">Không thể tải dữ liệu</td></tr>';
    }
}

function getStockClass(stock) {
    if (!stock || stock <= 0) return 'out-of-stock';
    if (stock < 10) return 'low-stock';
    return 'in-stock';
}

function searchProducts() {
    clearTimeout(window.searchTimeout);
    window.searchTimeout = setTimeout(() => loadProducts(), 300);
}

function renderPagination(current, total) {
    const container = document.getElementById('pagination');
    let html = '';
    
    html += `<button ${current === 1 ? 'disabled' : ''} onclick="loadProducts(${current - 1})"><i class="fas fa-chevron-left"></i></button>`;
    
    for (let i = 1; i <= total; i++) {
        if (i === 1 || i === total || (i >= current - 2 && i <= current + 2)) {
            html += `<button class="${i === current ? 'active' : ''}" onclick="loadProducts(${i})">${i}</button>`;
        } else if (i === current - 3 || i === current + 3) {
            html += `<span style="padding: 0 8px;">...</span>`;
        }
    }
    
    html += `<button ${current === total ? 'disabled' : ''} onclick="loadProducts(${current + 1})"><i class="fas fa-chevron-right"></i></button>`;
    
    container.innerHTML = html;
}

function openModal(mode = 'add') {
    document.getElementById('productModal').classList.add('active');
    document.getElementById('modalTitle').textContent = mode === 'add' ? 'Thêm sản phẩm' : 'Sửa sản phẩm';
    
    if (mode === 'add') {
        document.getElementById('productForm').reset();
        document.getElementById('productId').value = '';
        document.getElementById('imagePreview').innerHTML = '';
    }
}

function closeModal() {
    document.getElementById('productModal').classList.remove('active');
}

async function editProduct(id) {
    const product = products.find(p => p.id === id);
    if (!product) {
        console.error('Product not found:', id);
        return;
    }
    
    console.log('Editing product:', product);
    console.log('Product category_id:', product.category_id);
    
    document.getElementById('productId').value = product.id;
    document.getElementById('productName').value = product.name;
    document.getElementById('productBrand').value = product.brand || '';
    document.getElementById('productPrice').value = product.price;
    document.getElementById('productRam').value = product.ram || '';
    document.getElementById('productStorage').value = product.storage || '';
    document.getElementById('productBattery').value = product.battery || '';
    document.getElementById('productQuantity').value = product.quantity || 0;
    document.getElementById('productStatus').value = product.status || 'active';
    document.getElementById('productDescription').value = product.description || '';
    
    // Set category after a small delay to ensure dropdown is populated
    setTimeout(() => {
        const categorySelect = document.getElementById('productCategory');
        categorySelect.value = product.category_id || '';
        console.log('Category select value set to:', categorySelect.value);
        console.log('Available options:', Array.from(categorySelect.options).map(o => {
            return {value: o.value, text: o.text};
        }));
    }, 100);
    
    openModal('edit');
}

async function saveProduct() {
    const token = localStorage.getItem('auth_token');
    const id = document.getElementById('productId').value;
    
    // Validate required fields
    const name = document.getElementById('productName').value.trim();
    const brand = document.getElementById('productBrand').value.trim();
    const price = document.getElementById('productPrice').value;
    const categoryId = document.getElementById('productCategory').value;
    const quantity = document.getElementById('productQuantity').value;
    
    if (!name) {
        alert('Vui lòng nhập tên sản phẩm');
        document.getElementById('productName').focus();
        return;
    }
    
    if (!brand) {
        alert('Vui lòng nhập thương hiệu');
        document.getElementById('productBrand').focus();
        return;
    }
    
    if (!price || parseFloat(price) <= 0) {
        alert('Vui lòng nhập giá bán hợp lệ');
        document.getElementById('productPrice').focus();
        return;
    }
    
    if (!categoryId) {
        alert('Vui lòng chọn danh mục');
        document.getElementById('productCategory').focus();
        return;
    }
    
    if (quantity === '' || quantity === null || quantity === undefined || parseInt(quantity) < 0) {
        alert('Vui lòng nhập số lượng hợp lệ (>= 0)');
        document.getElementById('productQuantity').focus();
        return;
    }
    
    // Dùng FormData để có thể upload file ảnh
    const formData = new FormData();
    formData.append('name', name);
    formData.append('brand', brand);
    formData.append('price', parseFloat(price));
    formData.append('category_id', parseInt(categoryId));
    formData.append('quantity', parseInt(quantity));
    formData.append('status', document.getElementById('productStatus').value);
    
    const ram = document.getElementById('productRam').value;
    const storage = document.getElementById('productStorage').value;
    const battery = document.getElementById('productBattery').value;
    const description = document.getElementById('productDescription').value;
    if (ram) formData.append('ram', ram);
    if (storage) formData.append('storage', storage);
    if (battery) formData.append('battery', battery);
    if (description) formData.append('description', description);
    
    // Thêm ảnh vào FormData
    const imageInput = document.getElementById('productImages');
    if (imageInput && imageInput.files.length > 0) {
        Array.from(imageInput.files).forEach(file => {
            formData.append('images[]', file);
        });
    }
    
    console.log('Saving product with FormData, quantity:', parseInt(quantity));
    
    try {
        let url, method;
        if (id) {
            // Laravel không hỗ trợ PUT với FormData, dùng POST + _method
            url = `/api/admin/products/${id}`;
            method = 'POST';
            formData.append('_method', 'PUT');
        } else {
            url = '/api/admin/products';
            method = 'POST';
        }
        
        const response = await fetch(url, {
            method,
            headers: {
                'Authorization': `Bearer ${token}`,
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
                // KHÔNG set Content-Type, để browser tự set multipart/form-data với boundary
            },
            body: formData
        });
        
        const data = await response.json();
        
        if (response.ok) {
            alert(id ? 'Cập nhật sản phẩm thành công!' : 'Thêm sản phẩm thành công!');
            closeModal();
            loadProducts(currentPage);
        } else {
            console.error('Validation error:', data);
            let errorMsg = data.message || 'Có lỗi xảy ra';
            
            if (data.errors) {
                errorMsg += '\n\nChi tiết lỗi:\n';
                for (let field in data.errors) {
                    errorMsg += `- ${field}: ${data.errors[field].join(', ')}\n`;
                }
            }
            
            alert(errorMsg);
        }
    } catch (error) {
        console.error('Error saving product:', error);
        alert('Không thể lưu sản phẩm');
    }
}

async function deleteProduct(id) {
    showAdminConfirm(
        'Sản phẩm này sẽ bị xóa vĩnh viễn và không thể khôi phục.',
        async () => {
            const token = localStorage.getItem('auth_token');
            try {
                const response = await fetch(`/api/admin/products/${id}`, {
                    method: 'DELETE',
                    headers: {
                        'Authorization': `Bearer ${token}`,
                        'Accept': 'application/json'
                    }
                });
                if (response.ok) {
                    alert('Đã xóa sản phẩm thành công');
                    loadProducts(currentPage);
                } else {
                    alert('Không thể xóa sản phẩm');
                }
            } catch (error) {
                console.error('Error deleting product:', error);
            }
        },
        { title: 'Xóa sản phẩm?', confirmText: 'Xóa', type: 'danger' }
    );
}

async function toggleStatus(id) {
    const token = localStorage.getItem('auth_token');
    
    try {
        await fetch(`/api/admin/products/${id}/toggle-status`, {
            method: 'POST',
            headers: {
                'Authorization': `Bearer ${token}`,
                'Accept': 'application/json'
            }
        });
    } catch (error) {
        console.error('Error toggling status:', error);
    }
}

function toggleSelectAll() {
    const checked = document.getElementById('selectAll').checked;
    document.querySelectorAll('.product-checkbox').forEach(cb => cb.checked = checked);
}

function previewImages(input) {
    const preview = document.getElementById('imagePreview');
    preview.innerHTML = '';
    
    Array.from(input.files).forEach((file, index) => {
        const reader = new FileReader();
        reader.onload = function(e) {
            preview.innerHTML += `
                <div class="preview-item">
                    <img src="${e.target.result}" alt="Preview">
                    <button type="button" onclick="removePreview(${index})">&times;</button>
                </div>
            `;
        };
        reader.readAsDataURL(file);
    });
}

function exportProducts() {
    alert('Tính năng xuất Excel đang phát triển');
}
</script>
@endpush

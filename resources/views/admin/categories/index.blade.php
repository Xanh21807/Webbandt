@extends('layouts.admin')

@section('page-title', 'Quản lý danh mục')

@section('content')
<!-- Header -->
<div class="page-actions">
    <div class="search-box">
        <i class="fas fa-search"></i>
        <input type="text" id="searchInput" placeholder="Tìm danh mục..." onkeyup="searchCategories()">
    </div>
    <button class="btn btn-primary" onclick="openModal('add')">
        <i class="fas fa-plus"></i> Thêm danh mục
    </button>
</div>

<!-- Categories Grid -->
<div class="categories-grid" id="categoriesGrid">
    <!-- Loading state -->
    <div class="loading-state">
        <i class="fas fa-spinner fa-spin"></i> Đang tải...
    </div>
</div>

<!-- Category Modal -->
<div class="modal" id="categoryModal">
    <div class="modal-overlay" onclick="closeModal()"></div>
    <div class="modal-content">
        <div class="modal-header">
            <h3 id="modalTitle">Thêm danh mục</h3>
            <button class="modal-close" onclick="closeModal()">&times;</button>
        </div>
        <div class="modal-body">
            <form id="categoryForm">
                <input type="hidden" id="categoryId">
                
                <div class="form-group">
                    <label for="categoryName">Tên danh mục *</label>
                    <input type="text" id="categoryName" required>
                </div>
                
                <div class="form-group">
                    <label for="categorySlug">Slug</label>
                    <input type="text" id="categorySlug" placeholder="Tự động tạo từ tên">
                </div>
                
                <div class="form-group">
                    <label for="categoryParent">Danh mục cha</label>
                    <select id="categoryParent">
                        <option value="">-- Không có --</option>
                    </select>
                </div>
                
                <div class="form-group">
                    <label for="categoryDescription">Mô tả</label>
                    <textarea id="categoryDescription" rows="3"></textarea>
                </div>
                
                <div class="form-group">
                    <label>Hình ảnh</label>
                    <div class="image-upload" onclick="document.getElementById('categoryImage').click()">
                        <input type="file" id="categoryImage" accept="image/*" onchange="previewImage(this)" hidden>
                        <div class="upload-content" id="uploadContent">
                            <i class="fas fa-image"></i>
                            <p>Click để tải ảnh lên</p>
                        </div>
                        <img id="imagePreview" src="" alt="" style="display: none;">
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="categoryOrder">Thứ tự hiển thị</label>
                    <input type="number" id="categoryOrder" value="0">
                </div>
                
                <div class="form-group">
                    <label class="checkbox-wrapper">
                        <input type="checkbox" id="categoryActive" checked>
                        <span class="checkmark"></span>
                        Hiển thị danh mục
                    </label>
                </div>
            </form>
        </div>
        <div class="modal-footer">
            <button class="btn btn-outline" onclick="closeModal()">Hủy</button>
            <button class="btn btn-primary" onclick="saveCategory()">
                <i class="fas fa-save"></i> Lưu
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
    margin-bottom: 24px;
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
}

.categories-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
    gap: 20px;
}

.loading-state {
    grid-column: 1 / -1;
    text-align: center;
    padding: 60px;
    color: var(--gray-500);
}

.category-card {
    background: white;
    border-radius: 12px;
    box-shadow: var(--shadow-sm);
    overflow: hidden;
    transition: all 0.2s;
}

.category-card:hover {
    box-shadow: var(--shadow-md);
    transform: translateY(-2px);
}

.category-image {
    height: 140px;
    background: linear-gradient(135deg, #f0f0f0, #e0e0e0);
    display: flex;
    align-items: center;
    justify-content: center;
    position: relative;
}

.category-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.category-image .placeholder {
    font-size: 48px;
    color: #d1d5db;
}

.category-status {
    position: absolute;
    top: 10px;
    right: 10px;
    width: 10px;
    height: 10px;
    border-radius: 50%;
}

.category-status.active { background: #10b981; }
.category-status.inactive { background: #ef4444; }

.category-content {
    padding: 16px;
}

.category-name {
    font-size: 16px;
    font-weight: 600;
    margin-bottom: 4px;
}

.category-meta {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 12px;
}

.category-count {
    font-size: 13px;
    color: var(--gray-500);
}

.category-parent {
    font-size: 12px;
    background: #f3f4f6;
    padding: 2px 8px;
    border-radius: 4px;
    color: var(--gray-600);
}

.category-actions {
    display: flex;
    gap: 8px;
    padding-top: 12px;
    border-top: 1px solid #e5e7eb;
}

.category-actions button {
    flex: 1;
    padding: 8px;
    border: none;
    border-radius: 6px;
    cursor: pointer;
    font-size: 13px;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 6px;
}

.category-actions .btn-edit {
    background: #dbeafe;
    color: #2563eb;
}

.category-actions .btn-delete {
    background: #fee2e2;
    color: #dc2626;
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
    max-width: 500px !important;
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
    padding: 30px;
    text-align: center;
    cursor: pointer;
    position: relative;
    overflow: hidden;
}

.upload-content i {
    font-size: 40px;
    color: #d1d5db;
    margin-bottom: 8px;
}

.upload-content p {
    color: var(--gray-500);
    font-size: 14px;
}

.image-upload img {
    width: 100%;
    height: 150px;
    object-fit: cover;
    border-radius: 8px;
}
</style>
@endpush

@push('scripts')
<script>
let categories = [];

document.addEventListener('DOMContentLoaded', () => {
    loadCategories();
    
    // Auto generate slug from name
    document.getElementById('categoryName').addEventListener('input', function() {
        const slug = this.value.toLowerCase()
            .normalize('NFD').replace(/[\u0300-\u036f]/g, '')
            .replace(/[đĐ]/g, 'd')
            .replace(/[^a-z0-9\s-]/g, '')
            .replace(/\s+/g, '-')
            .replace(/-+/g, '-');
        document.getElementById('categorySlug').value = slug;
    });
});

// Load categories
async function loadCategories() {
    const token = localStorage.getItem('auth_token');
    const grid = document.getElementById('categoriesGrid');
    
    grid.innerHTML = '<div class="loading-state"><i class="fas fa-spinner fa-spin"></i> Đang tải...</div>';
    
    try {
        const response = await fetch('/api/admin/categories', {
            headers: {
                'Authorization': `Bearer ${token}`,
                'Accept': 'application/json'
            }
        });
        
        if (response.ok) {
            const result = await response.json();
            const data = result.data || {};
            categories = data.data || data || [];
            
            // Update parent select options
            updateParentSelect();
            
            if (categories.length === 0) {
                grid.innerHTML = '<div class="loading-state">Chưa có danh mục nào</div>';
            } else {
                grid.innerHTML = categories.map(cat => `
                    <div class="category-card">
                        <div class="category-image">
                            ${cat.image ? 
                                `<img src="${cat.image}" alt="${cat.name}">` : 
                                `<i class="fas fa-folder placeholder"></i>`
                            }
                            <span class="category-status ${cat.is_active ? 'active' : 'inactive'}"></span>
                        </div>
                        <div class="category-content">
                            <h3 class="category-name">${cat.name}</h3>
                            <div class="category-meta">
                                <span class="category-count">${cat.products_count || 0} sản phẩm</span>
                                ${cat.parent ? `<span class="category-parent">${cat.parent.name}</span>` : ''}
                            </div>
                            <div class="category-actions">
                                <button class="btn-edit" onclick="editCategory(${cat.id})">
                                    <i class="fas fa-edit"></i> Sửa
                                </button>
                                <button class="btn-delete" onclick="deleteCategory(${cat.id})">
                                    <i class="fas fa-trash"></i> Xóa
                                </button>
                            </div>
                        </div>
                    </div>
                `).join('');
            }
        }
    } catch (error) {
        console.error('Error loading categories:', error);
        grid.innerHTML = '<div class="loading-state">Không thể tải dữ liệu</div>';
    }
}

function updateParentSelect() {
    const select = document.getElementById('categoryParent');
    select.innerHTML = '<option value="">-- Không có --</option>';
    
    categories.forEach(cat => {
        if (!cat.parent_id) {
            select.innerHTML += `<option value="${cat.id}">${cat.name}</option>`;
        }
    });
}

function searchCategories() {
    const search = document.getElementById('searchInput').value.toLowerCase();
    const cards = document.querySelectorAll('.category-card');
    
    cards.forEach(card => {
        const name = card.querySelector('.category-name').textContent.toLowerCase();
        card.style.display = name.includes(search) ? 'block' : 'none';
    });
}

function openModal(mode = 'add') {
    document.getElementById('categoryModal').classList.add('active');
    document.getElementById('modalTitle').textContent = mode === 'add' ? 'Thêm danh mục' : 'Sửa danh mục';
    
    if (mode === 'add') {
        document.getElementById('categoryForm').reset();
        document.getElementById('categoryId').value = '';
        document.getElementById('categoryActive').checked = true;
        document.getElementById('imagePreview').style.display = 'none';
        document.getElementById('uploadContent').style.display = 'block';
    }
}

function closeModal() {
    document.getElementById('categoryModal').classList.remove('active');
}

function previewImage(input) {
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById('imagePreview').src = e.target.result;
            document.getElementById('imagePreview').style.display = 'block';
            document.getElementById('uploadContent').style.display = 'none';
        };
        reader.readAsDataURL(input.files[0]);
    }
}

async function editCategory(id) {
    const cat = categories.find(c => c.id === id);
    if (!cat) return;
    
    document.getElementById('categoryId').value = cat.id;
    document.getElementById('categoryName').value = cat.name;
    document.getElementById('categorySlug').value = cat.slug || '';
    document.getElementById('categoryParent').value = cat.parent_id || '';
    document.getElementById('categoryDescription').value = cat.description || '';
    document.getElementById('categoryOrder').value = cat.sort_order || 0;
    document.getElementById('categoryActive').checked = cat.is_active;
    
    if (cat.image) {
        document.getElementById('imagePreview').src = cat.image;
        document.getElementById('imagePreview').style.display = 'block';
        document.getElementById('uploadContent').style.display = 'none';
    } else {
        document.getElementById('imagePreview').style.display = 'none';
        document.getElementById('uploadContent').style.display = 'block';
    }
    
    openModal('edit');
}

async function saveCategory() {
    const token = localStorage.getItem('auth_token');
    const id = document.getElementById('categoryId').value;
    
    const formData = new FormData();
    formData.append('name', document.getElementById('categoryName').value);
    formData.append('slug', document.getElementById('categorySlug').value);
    formData.append('parent_id', document.getElementById('categoryParent').value);
    formData.append('description', document.getElementById('categoryDescription').value);
    formData.append('sort_order', document.getElementById('categoryOrder').value);
    formData.append('is_active', document.getElementById('categoryActive').checked ? 1 : 0);
    
    const imageFile = document.getElementById('categoryImage').files[0];
    if (imageFile) {
        formData.append('image', imageFile);
    }
    
    if (id) {
        formData.append('_method', 'PUT');
    }
    
    try {
        const url = id ? `/api/admin/categories/${id}` : '/api/admin/categories';
        
        const response = await fetch(url, {
            method: 'POST',
            headers: {
                'Authorization': `Bearer ${token}`,
                'Accept': 'application/json'
            },
            body: formData
        });
        
        if (response.ok) {
            alert(id ? 'Cập nhật thành công!' : 'Thêm danh mục thành công!');
            closeModal();
            loadCategories();
        } else {
            const data = await response.json();
            alert(data.message || 'Có lỗi xảy ra');
        }
    } catch (error) {
        console.error('Error saving category:', error);
        alert('Không thể lưu danh mục');
    }
}

async function deleteCategory(id) {
    const cat = categories.find(c => c.id === id);
    if (!cat) return;
    
    if (!confirm(`Bạn có chắc muốn xóa danh mục "${cat.name}"?`)) return;
    
    const token = localStorage.getItem('auth_token');
    
    try {
        const response = await fetch(`/api/admin/categories/${id}`, {
            method: 'DELETE',
            headers: {
                'Authorization': `Bearer ${token}`,
                'Accept': 'application/json'
            }
        });
        
        if (response.ok) {
            alert('Đã xóa danh mục');
            loadCategories();
        } else {
            const data = await response.json();
            alert(data.message || 'Không thể xóa danh mục');
        }
    } catch (error) {
        console.error('Error deleting category:', error);
    }
}
</script>
@endpush

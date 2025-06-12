<?php include 'app/views/shares/header.php'; ?>

<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Danh sách sản phẩm</h1>
        <a href="/webbanhang/Product/add" class="btn btn-success">
            <i class="fas fa-plus"></i> Thêm sản phẩm mới
        </a>
    </div>

    <!-- Loading indicator -->
    <div id="loading" class="text-center" style="display: none;">
        <div class="spinner-border" role="status">
            <span class="sr-only">Đang tải...</span>
        </div>
    </div>

    <!-- Product list -->
    <div class="row" id="product-list">
        <!-- Danh sách sản phẩm sẽ được tải từ API và hiển thị tại đây -->
    </div>

    <!-- Pagination -->
    <nav aria-label="Product pagination" id="pagination-nav" style="display: none;">
        <ul class="pagination justify-content-center" id="pagination">
        </ul>
    </nav>

    <!-- Error message -->
    <div id="error-message" class="alert alert-danger" style="display: none;">
    </div>
</div>

<?php include 'app/views/shares/footer.php'; ?>

<script>
let currentPage = 1;
const productsPerPage = 8;

document.addEventListener("DOMContentLoaded", function() {
    loadProducts(currentPage);
});

function loadProducts(page = 1) {
    showLoading(true);
    
    fetch(`/webbanhang/api/products?page=${page}&limit=${productsPerPage}`)
        .then(response => response.json())
        .then(data => {
            showLoading(false);
            
            if (data.success) {
                displayProducts(data.data.products);
                displayPagination(data.data.pagination);
                hideError();
            } else {
                showError(data.message || 'Có lỗi xảy ra khi tải dữ liệu');
            }
        })
        .catch(error => {
            showLoading(false);
            console.error('Error:', error);
            showError('Không thể kết nối đến server');
        });
}

function displayProducts(products) {
    const productList = document.getElementById('product-list');
    
    if (!products || products.length === 0) {
        productList.innerHTML = '<div class="col-12"><div class="alert alert-info">Không có sản phẩm nào.</div></div>';
        return;
    }
    
    productList.innerHTML = '';
    
    products.forEach(product => {
        const productCard = document.createElement('div');
        productCard.className = 'col-md-6 col-lg-3 mb-4';
        
        productCard.innerHTML = `
            <div class="card h-100">
                <div class="card-body">
                    <h5 class="card-title">
                        <a href="/webbanhang/Product/show/${product.id}" class="text-decoration-none">
                            ${escapeHtml(product.name)}
                        </a>
                    </h5>
                    <p class="card-text text-muted small">${escapeHtml(product.description || '')}</p>
                    <p class="card-text">
                        <strong class="text-primary">${formatPrice(product.price)} VND</strong>
                    </p>
                    <p class="card-text">
                        <small class="text-muted">
                            Danh mục: ${escapeHtml(product.category_name || 'Chưa phân loại')}
                        </small>
                    </p>
                </div>
                <div class="card-footer bg-white border-top-0">
                    <div class="btn-group w-100">
                        <a href="/webbanhang/Product/edit/${product.id}" class="btn btn-warning btn-sm">
                            <i class="fas fa-edit"></i> Sửa
                        </a>
                        <button class="btn btn-danger btn-sm" onclick="deleteProduct(${product.id})">
                            <i class="fas fa-trash"></i> Xóa
                        </button>
                    </div>
                </div>
            </div>
        `;
        
        productList.appendChild(productCard);
    });
}

function displayPagination(pagination) {
    const paginationNav = document.getElementById('pagination-nav');
    const paginationList = document.getElementById('pagination');
    
    if (pagination.total_pages <= 1) {
        paginationNav.style.display = 'none';
        return;
    }
    
    paginationNav.style.display = 'block';
    paginationList.innerHTML = '';
    
    // Previous button
    if (pagination.has_prev) {
        const prevLi = document.createElement('li');
        prevLi.className = 'page-item';
        prevLi.innerHTML = `
            <a class="page-link" href="#" onclick="loadProducts(${pagination.current_page - 1}); return false;">
                <i class="fas fa-chevron-left"></i>
            </a>
        `;
        paginationList.appendChild(prevLi);
    }
    
    // Page numbers
    for (let i = 1; i <= pagination.total_pages; i++) {
        const pageLi = document.createElement('li');
        pageLi.className = `page-item ${i === pagination.current_page ? 'active' : ''}`;
        pageLi.innerHTML = `
            <a class="page-link" href="#" onclick="loadProducts(${i}); return false;">${i}</a>
        `;
        paginationList.appendChild(pageLi);
    }
    
    // Next button
    if (pagination.has_next) {
        const nextLi = document.createElement('li');
        nextLi.className = 'page-item';
        nextLi.innerHTML = `
            <a class="page-link" href="#" onclick="loadProducts(${pagination.current_page + 1}); return false;">
                <i class="fas fa-chevron-right"></i>
            </a>
        `;
        paginationList.appendChild(nextLi);
    }
    
    currentPage = pagination.current_page;
}

function deleteProduct(id) {
    if (!confirm('Bạn có chắc chắn muốn xóa sản phẩm này?')) {
        return;
    }
    
    fetch(`/webbanhang/api/products/${id}`, {
        method: 'DELETE'
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Reload current page
            loadProducts(currentPage);
            showSuccessMessage('Sản phẩm đã được xóa thành công');
        } else {
            showError(data.message || 'Xóa sản phẩm thất bại');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showError('Không thể kết nối đến server');
    });
}

function showLoading(show) {
    document.getElementById('loading').style.display = show ? 'block' : 'none';
}

function showError(message) {
    const errorDiv = document.getElementById('error-message');
    errorDiv.textContent = message;
    errorDiv.style.display = 'block';
    
    // Auto hide after 5 seconds
    setTimeout(() => {
        errorDiv.style.display = 'none';
    }, 5000);
}

function hideError() {
    document.getElementById('error-message').style.display = 'none';
}

function showSuccessMessage(message) {
    // Create temporary success alert
    const alertDiv = document.createElement('div');
    alertDiv.className = 'alert alert-success alert-dismissible fade show';
    alertDiv.innerHTML = `
        ${message}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    `;
    
    const container = document.querySelector('.container');
    container.insertBefore(alertDiv, container.firstChild);
    
    // Auto hide after 3 seconds
    setTimeout(() => {
        alertDiv.remove();
    }, 3000);
}

function formatPrice(price) {
    return new Intl.NumberFormat('vi-VN').format(price);
}

function escapeHtml(text) {
    const map = {
        '&': '&amp;',
        '<': '&lt;',
        '>': '&gt;',
        '"': '&quot;',
        "'": '&#039;'
    };
    return text ? text.replace(/[&<>"']/g, m => map[m]) : '';
}
</script>

<style>
.card {
    transition: transform 0.2s;
}

.card:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(0,0,0,0.1);
}

.spinner-border {
    width: 3rem;
    height: 3rem;
}

.btn-group .btn {
    flex: 1;
}
</style> 
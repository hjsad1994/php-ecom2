<?php include 'app/views/shares/header.php'; ?>

<div class="container mt-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0">
                        <i class="fas fa-plus-circle"></i> Thêm sản phẩm mới
                    </h4>
                </div>
                <div class="card-body">
                    <!-- Loading indicator -->
                    <div id="loading" class="text-center" style="display: none;">
                        <div class="spinner-border text-primary" role="status">
                            <span class="sr-only">Đang xử lý...</span>
                        </div>
                        <p class="mt-2">Đang thêm sản phẩm...</p>
                    </div>

                    <!-- Form -->
                    <form id="add-product-form">
                        <div class="mb-3">
                            <label for="name" class="form-label">Tên sản phẩm <span class="text-danger">*</span></label>
                            <input type="text" id="name" name="name" class="form-control" required>
                            <div class="invalid-feedback" id="name-error"></div>
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label">Mô tả <span class="text-danger">*</span></label>
                            <textarea id="description" name="description" class="form-control" rows="4" required></textarea>
                            <div class="invalid-feedback" id="description-error"></div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="price" class="form-label">Giá (VND) <span class="text-danger">*</span></label>
                                    <input type="number" id="price" name="price" class="form-control" step="1000" min="0" required>
                                    <div class="invalid-feedback" id="price-error"></div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="category_id" class="form-label">Danh mục</label>
                                    <select id="category_id" name="category_id" class="form-select">
                                        <option value="">Chọn danh mục...</option>
                                        <!-- Các danh mục sẽ được tải từ API -->
                                    </select>
                                    <div class="invalid-feedback" id="category_id-error"></div>
                                </div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="/webbanhang/Product/list" class="btn btn-secondary">
                                <i class="fas fa-arrow-left"></i> Quay lại danh sách
                            </a>
                            <button type="submit" class="btn btn-primary" id="submit-btn">
                                <i class="fas fa-save"></i> Thêm sản phẩm
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include 'app/views/shares/footer.php'; ?>

<script>
document.addEventListener("DOMContentLoaded", function() {
    loadCategories();
    
    document.getElementById('add-product-form').addEventListener('submit', function(event) {
        event.preventDefault();
        submitForm();
    });
});

function loadCategories() {
    fetch('/webbanhang/api/categories')
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                populateCategories(data.data);
            } else {
                console.error('Failed to load categories:', data.message);
            }
        })
        .catch(error => {
            console.error('Error loading categories:', error);
        });
}

function populateCategories(categories) {
    const categorySelect = document.getElementById('category_id');
    
    categories.forEach(category => {
        const option = document.createElement('option');
        option.value = category.id;
        option.textContent = category.name;
        categorySelect.appendChild(option);
    });
}

function submitForm() {
    clearErrors();
    showLoading(true);
    
    const formData = new FormData(document.getElementById('add-product-form'));
    const jsonData = {};
    
    formData.forEach((value, key) => {
        if (key === 'price') {
            jsonData[key] = parseFloat(value) || 0;
        } else if (key === 'category_id' && value === '') {
            jsonData[key] = null;
        } else {
            jsonData[key] = value;
        }
    });

    fetch('/webbanhang/api/products', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify(jsonData)
    })
    .then(response => response.json())
    .then(data => {
        showLoading(false);
        
        if (data.success) {
            showSuccessMessage('Sản phẩm đã được thêm thành công!');
            setTimeout(() => {
                window.location.href = '/webbanhang/Product/list';
            }, 1500);
        } else {
            if (data.data && typeof data.data === 'object') {
                // Validation errors
                showValidationErrors(data.data);
            } else {
                showError(data.message || 'Có lỗi xảy ra khi thêm sản phẩm');
            }
        }
    })
    .catch(error => {
        showLoading(false);
        console.error('Error:', error);
        showError('Không thể kết nối đến server');
    });
}

function showValidationErrors(errors) {
    for (const field in errors) {
        const errorElement = document.getElementById(`${field}-error`);
        const inputElement = document.getElementById(field);
        
        if (errorElement && inputElement) {
            inputElement.classList.add('is-invalid');
            errorElement.textContent = errors[field];
        }
    }
}

function clearErrors() {
    const inputs = document.querySelectorAll('.form-control, .form-select');
    inputs.forEach(input => {
        input.classList.remove('is-invalid');
    });
    
    const errors = document.querySelectorAll('.invalid-feedback');
    errors.forEach(error => {
        error.textContent = '';
    });
}

function showLoading(show) {
    const loadingDiv = document.getElementById('loading');
    const form = document.getElementById('add-product-form');
    const submitBtn = document.getElementById('submit-btn');
    
    if (show) {
        loadingDiv.style.display = 'block';
        form.style.display = 'none';
        submitBtn.disabled = true;
    } else {
        loadingDiv.style.display = 'none';
        form.style.display = 'block';
        submitBtn.disabled = false;
    }
}

function showError(message) {
    const alertDiv = document.createElement('div');
    alertDiv.className = 'alert alert-danger alert-dismissible fade show';
    alertDiv.innerHTML = `
        <i class="fas fa-exclamation-triangle"></i> ${message}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    `;
    
    const cardBody = document.querySelector('.card-body');
    cardBody.insertBefore(alertDiv, cardBody.firstChild);
    
    // Auto hide after 5 seconds
    setTimeout(() => {
        alertDiv.remove();
    }, 5000);
}

function showSuccessMessage(message) {
    const alertDiv = document.createElement('div');
    alertDiv.className = 'alert alert-success alert-dismissible fade show';
    alertDiv.innerHTML = `
        <i class="fas fa-check-circle"></i> ${message}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    `;
    
    const cardBody = document.querySelector('.card-body');
    cardBody.insertBefore(alertDiv, cardBody.firstChild);
}

// Real-time price formatting
document.getElementById('price').addEventListener('input', function(e) {
    const value = e.target.value.replace(/[^\d]/g, '');
    if (value) {
        e.target.value = value;
    }
});

// Form validation on input
document.querySelectorAll('input, textarea, select').forEach(input => {
    input.addEventListener('input', function() {
        if (this.classList.contains('is-invalid')) {
            this.classList.remove('is-invalid');
            const errorElement = document.getElementById(`${this.name}-error`);
            if (errorElement) {
                errorElement.textContent = '';
            }
        }
    });
});
</script>

<style>
.card {
    border: none;
    border-radius: 15px;
}

.card-header {
    border-radius: 15px 15px 0 0;
}

.form-control:focus,
.form-select:focus {
    border-color: #0d6efd;
    box-shadow: 0 0 0 0.2rem rgba(13, 110, 253, 0.25);
}

.btn {
    border-radius: 8px;
    padding: 10px 20px;
}

.spinner-border {
    width: 3rem;
    height: 3rem;
}

.alert {
    border-radius: 10px;
}

@media (max-width: 768px) {
    .container {
        padding: 0 10px;
    }
    
    .d-flex.justify-content-between {
        flex-direction: column;
        gap: 10px;
    }
    
    .d-flex.justify-content-between .btn {
        width: 100%;
    }
}
</style> 
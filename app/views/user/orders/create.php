<?php include_once 'app/views/shares/header.php'; ?>

<div class="row mb-4">
    <div class="col-md-8">
        <h1 class="display-6 fw-bold text-primary">
            <i class="bi bi-plus-circle me-2"></i>Đặt hàng mới
        </h1>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="/webbanhang/" class="text-decoration-none">Trang chủ</a></li>
                <li class="breadcrumb-item"><a href="/webbanhang/order" class="text-decoration-none">Đơn hàng</a></li>
                <li class="breadcrumb-item active">Đặt hàng mới</li>
            </ol>
        </nav>
    </div>
    <div class="col-md-4 text-end">
        <a href="/webbanhang/order" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left me-2"></i>Quay lại đơn hàng
        </a>
    </div>
</div>

<?php if (isset($errors) && !empty($errors)): ?>
    <div class="alert alert-danger">
        <h6>Có lỗi xảy ra:</h6>
        <ul class="mb-0">
            <?php foreach ($errors as $error): ?>
                <li><?php echo $error; ?></li>
            <?php endforeach; ?>
        </ul>
    </div>
<?php endif; ?>

<form id="createOrderForm" action="/webbanhang/order/store" method="POST">
    <div class="row">
        <!-- Order Form -->
        <div class="col-lg-8">
            <!-- Customer Information -->
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">
                        <i class="bi bi-person-fill me-2"></i>Thông tin khách hàng
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-bold">Họ và tên <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="name" required 
                                       value="<?php echo htmlspecialchars(SessionHelper::getUsername() ?? '', ENT_QUOTES, 'UTF-8'); ?>">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-bold">Số điện thoại <span class="text-danger">*</span></label>
                                <input type="tel" class="form-control" name="phone" required>
                            </div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Địa chỉ giao hàng <span class="text-danger">*</span></label>
                        <textarea class="form-control" name="address" rows="3" required 
                                  placeholder="Nhập địa chỉ đầy đủ để giao hàng"></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Ghi chú đơn hàng</label>
                        <textarea class="form-control" name="notes" rows="2" 
                                  placeholder="Ghi chú thêm về đơn hàng (không bắt buộc)"></textarea>
                    </div>
                </div>
            </div>

            <!-- Product Selection -->
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0">
                        <i class="bi bi-bag-plus me-2"></i>Chọn sản phẩm
                    </h5>
                </div>
                <div class="card-body">
                    <div id="selectedProducts">
                        <div class="text-center text-muted py-4">
                            <i class="bi bi-box fs-1"></i>
                            <p class="mt-2">Chưa có sản phẩm nào được chọn</p>
                            <button type="button" class="btn btn-primary" onclick="openProductSelector()">
                                <i class="bi bi-plus-circle me-2"></i>Thêm sản phẩm
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Payment & Shipping -->
            <div class="card shadow-sm">
                <div class="card-header bg-info text-white">
                    <h5 class="mb-0">
                        <i class="bi bi-credit-card me-2"></i>Thanh toán & Giao hàng
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Phương thức thanh toán</label>
                            <div class="form-check mb-2">
                                <input class="form-check-input" type="radio" name="payment_method" id="cod" value="cod" checked>
                                <label class="form-check-label" for="cod">
                                    Thanh toán khi nhận hàng (COD)
                                </label>
                            </div>
                            <div class="form-check mb-2">
                                <input class="form-check-input" type="radio" name="payment_method" id="bank_transfer" value="bank_transfer">
                                <label class="form-check-label" for="bank_transfer">
                                    Chuyển khoản ngân hàng
                                </label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Phương thức giao hàng</label>
                            <div class="form-check mb-2">
                                <input class="form-check-input" type="radio" name="shipping_method" id="standard" value="standard" checked>
                                <label class="form-check-label" for="standard">
                                    Giao hàng tiêu chuẩn (3-5 ngày)
                                </label>
                            </div>
                            <div class="form-check mb-2">
                                <input class="form-check-input" type="radio" name="shipping_method" id="express" value="express">
                                <label class="form-check-label" for="express">
                                    Giao hàng nhanh (1-2 ngày) - +30.000đ
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Order Summary -->
        <div class="col-lg-4">
            <div class="sticky-top" style="top: 2rem;">
                <div class="card shadow-sm">
                    <div class="card-header bg-warning">
                        <h5 class="mb-0">
                            <i class="bi bi-receipt me-2"></i>Tóm tắt đơn hàng
                        </h5>
                    </div>
                    <div class="card-body">
                        <div id="orderSummary">
                            <div class="text-center text-muted py-4">
                                <i class="bi bi-cart-x fs-1"></i>
                                <p class="mt-2">Chưa có sản phẩm nào</p>
                            </div>
                        </div>
                        
                        <!-- Voucher -->
                        <div class="mb-3">
                            <label class="form-label fw-bold">Mã giảm giá</label>
                            <div class="input-group">
                                <input type="text" class="form-control" name="voucher_code" placeholder="Nhập mã">
                                <button class="btn btn-outline-secondary" type="button">Áp dụng</button>
                            </div>
                        </div>

                        <!-- Total -->
                        <div class="border-top pt-3">
                            <div class="d-flex justify-content-between mb-2">
                                <span>Tạm tính:</span>
                                <span id="subtotal">0 đ</span>
                            </div>
                            <div class="d-flex justify-content-between mb-2">
                                <span>Phí ship:</span>
                                <span id="shippingFee">Miễn phí</span>
                            </div>
                            <hr>
                            <div class="d-flex justify-content-between mb-3">
                                <span class="h6 fw-bold">Tổng:</span>
                                <span class="h5 fw-bold text-primary" id="total">0 đ</span>
                            </div>

                            <div class="d-grid">
                                <button type="submit" class="btn btn-primary btn-lg" id="submitBtn" disabled>
                                    <i class="bi bi-check-circle me-2"></i>Đặt hàng
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>

<!-- Product Selector Modal -->
<div class="modal fade" id="productModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Chọn sản phẩm</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="row" id="productList">
                    <!-- Products will be loaded here -->
                    <div class="text-center py-4">
                        <div class="spinner-border" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                        <p class="mt-2">Đang tải sản phẩm...</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
let selectedProducts = [];

// Open product selector
function openProductSelector() {
    const modal = new bootstrap.Modal(document.getElementById('productModal'));
    modal.show();
    loadProducts();
}

// Load products via AJAX
function loadProducts() {
    fetch('/webbanhang/product/api/list')
        .then(response => response.json())
        .then(products => {
            const productList = document.getElementById('productList');
            productList.innerHTML = '';
            
            products.forEach(product => {
                const productCard = `
                    <div class="col-md-6 mb-3">
                        <div class="card h-100">
                            <img src="/webbanhang/public/uploads/products/${product.image || 'no-image.jpg'}" 
                                 class="card-img-top" style="height: 150px; object-fit: cover;">
                            <div class="card-body">
                                <h6 class="card-title">${product.name}</h6>
                                <p class="text-primary fw-bold">${formatPrice(product.price)} đ</p>
                                <div class="input-group input-group-sm">
                                    <input type="number" class="form-control" id="qty_${product.id}" 
                                           value="1" min="1" max="10">
                                    <button class="btn btn-primary" onclick="addProduct(${product.id}, '${product.name}', ${product.price}, '${product.image}')">
                                        Thêm
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                `;
                productList.innerHTML += productCard;
            });
        })
        .catch(error => {
            console.error('Error loading products:', error);
            document.getElementById('productList').innerHTML = '<p class="text-center text-danger">Lỗi tải sản phẩm</p>';
        });
}

// Add product to order
function addProduct(id, name, price, image) {
    const quantity = parseInt(document.getElementById(`qty_${id}`).value);
    
    // Check if product already exists
    const existingIndex = selectedProducts.findIndex(p => p.id === id);
    if (existingIndex !== -1) {
        selectedProducts[existingIndex].quantity += quantity;
    } else {
        selectedProducts.push({ id, name, price, image, quantity });
    }
    
    updateOrderDisplay();
    updateOrderSummary();
    
    // Close modal
    bootstrap.Modal.getInstance(document.getElementById('productModal')).hide();
}

// Remove product from order
function removeProduct(id) {
    selectedProducts = selectedProducts.filter(p => p.id !== id);
    updateOrderDisplay();
    updateOrderSummary();
}

// Update quantity
function updateQuantity(id, quantity) {
    const product = selectedProducts.find(p => p.id === id);
    if (product) {
        product.quantity = parseInt(quantity);
        if (product.quantity <= 0) {
            removeProduct(id);
        } else {
            updateOrderSummary();
        }
    }
}

// Update order display
function updateOrderDisplay() {
    const container = document.getElementById('selectedProducts');
    
    if (selectedProducts.length === 0) {
        container.innerHTML = `
            <div class="text-center text-muted py-4">
                <i class="bi bi-box fs-1"></i>
                <p class="mt-2">Chưa có sản phẩm nào được chọn</p>
                <button type="button" class="btn btn-primary" onclick="openProductSelector()">
                    <i class="bi bi-plus-circle me-2"></i>Thêm sản phẩm
                </button>
            </div>
        `;
        return;
    }
    
    let html = '<div class="mb-3">';
    selectedProducts.forEach(product => {
        html += `
            <div class="d-flex align-items-center border-bottom py-2">
                <img src="/webbanhang/public/uploads/products/${product.image || 'no-image.jpg'}" 
                     class="me-3" style="width: 50px; height: 50px; object-fit: cover; border-radius: 8px;">
                <div class="flex-grow-1">
                    <h6 class="mb-1">${product.name}</h6>
                    <small class="text-muted">${formatPrice(product.price)} đ/sản phẩm</small>
                </div>
                <div class="d-flex align-items-center">
                    <input type="number" class="form-control form-control-sm me-2" 
                           style="width: 70px;" value="${product.quantity}" min="1" max="10"
                           onchange="updateQuantity(${product.id}, this.value)">
                    <button type="button" class="btn btn-outline-danger btn-sm" 
                            onclick="removeProduct(${product.id})">
                        <i class="bi bi-trash"></i>
                    </button>
                </div>
                <input type="hidden" name="products[${product.id}][id]" value="${product.id}">
                <input type="hidden" name="products[${product.id}][quantity]" value="${product.quantity}">
                <input type="hidden" name="products[${product.id}][price]" value="${product.price}">
            </div>
        `;
    });
    html += '</div>';
    html += `
        <button type="button" class="btn btn-outline-primary" onclick="openProductSelector()">
            <i class="bi bi-plus-circle me-2"></i>Thêm sản phẩm khác
        </button>
    `;
    
    container.innerHTML = html;
}

// Update order summary
function updateOrderSummary() {
    const container = document.getElementById('orderSummary');
    const submitBtn = document.getElementById('submitBtn');
    
    if (selectedProducts.length === 0) {
        container.innerHTML = `
            <div class="text-center text-muted py-4">
                <i class="bi bi-cart-x fs-1"></i>
                <p class="mt-2">Chưa có sản phẩm nào</p>
            </div>
        `;
        submitBtn.disabled = true;
        return;
    }
    
    let subtotal = 0;
    let html = '';
    
    selectedProducts.forEach(product => {
        const productTotal = product.price * product.quantity;
        subtotal += productTotal;
        
        html += `
            <div class="d-flex justify-content-between mb-2">
                <span class="small">${product.name} x${product.quantity}</span>
                <span class="small fw-bold">${formatPrice(productTotal)} đ</span>
            </div>
        `;
    });
    
    container.innerHTML = html;
    
    // Update totals
    document.getElementById('subtotal').textContent = formatPrice(subtotal) + ' đ';
    document.getElementById('total').textContent = formatPrice(subtotal) + ' đ';
    
    submitBtn.disabled = false;
}

// Format price
function formatPrice(price) {
    return new Intl.NumberFormat('vi-VN').format(price);
}

// Form validation
document.getElementById('createOrderForm').addEventListener('submit', function(e) {
    if (selectedProducts.length === 0) {
        e.preventDefault();
        alert('Vui lòng chọn ít nhất một sản phẩm!');
        return false;
    }
    
    // Show loading
    const submitBtn = document.getElementById('submitBtn');
    submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Đang xử lý...';
    submitBtn.disabled = true;
});
</script>

<?php include_once 'app/views/shares/footer.php'; ?> 
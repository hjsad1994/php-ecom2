<?php include_once 'app/views/shares/header.php'; ?>

<!-- Breadcrumb -->
<nav aria-label="breadcrumb" class="mb-4">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="/webbanhang/" class="text-decoration-none">Trang chủ</a></li>
        <li class="breadcrumb-item"><a href="/webbanhang/product" class="text-decoration-none">Sản phẩm</a></li>
        <?php if (isset($product->category_name)): ?>
            <li class="breadcrumb-item">
                <a href="/webbanhang/product?category=<?php echo $product->category_id; ?>" class="text-decoration-none">
                    <?php echo htmlspecialchars($product->category_name, ENT_QUOTES, 'UTF-8'); ?>
                </a>
            </li>
        <?php endif; ?>
        <li class="breadcrumb-item active"><?php echo htmlspecialchars($product->name, ENT_QUOTES, 'UTF-8'); ?></li>
    </ol>
</nav>

<div class="row">
    <!-- Product Images -->
    <div class="col-lg-6 mb-4">
        <div class="sticky-top" style="top: 2rem;">
            <!-- Main Image -->
            <div class="card shadow-sm">
                <div class="position-relative">
                    <?php 
                    $imagePath = $product->image ? '/webbanhang/public/uploads/products/' . $product->image : 'data:image/svg+xml;base64,' . base64_encode('<svg width="600" height="600" xmlns="http://www.w3.org/2000/svg"><rect width="600" height="600" fill="#f8f9fa"/><circle cx="300" cy="250" r="50" fill="none" stroke="#dee2e6" stroke-width="6"/><path d="M270 250 L300 280 L330 250 M280 260 L320 260" stroke="#dee2e6" stroke-width="6" fill="none"/><text x="300" y="350" text-anchor="middle" font-family="Arial, sans-serif" font-size="24" fill="#6c757d">Không có ảnh</text></svg>');
                    ?>
                    <img src="<?php echo $imagePath; ?>" 
                         class="card-img-top" 
                         alt="<?php echo htmlspecialchars($product->name, ENT_QUOTES, 'UTF-8'); ?>"
                         style="height: 600px; object-fit: cover; border-radius: 12px;"
                         id="mainProductImage">
                    
                    <!-- Product Badges -->
                    <div class="position-absolute top-0 start-0 p-3">
                        <span class="badge bg-success fs-6">Còn hàng</span>
                    </div>

                    <!-- Wishlist Button -->
                    <div class="position-absolute top-0 end-0 p-3">
                        <button class="btn btn-light btn-lg rounded-circle" title="Thêm vào yêu thích">
                            <i class="bi bi-heart text-danger"></i>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Image Gallery -->
            <div class="row mt-3">
                <div class="col-3">
                    <img src="<?php echo $imagePath; ?>" class="img-thumbnail active-thumb" 
                         style="height: 80px; object-fit: cover; cursor: pointer;"
                         onclick="changeMainImage(this.src)">
                </div>
                <div class="col-3">
                    <img src="<?php echo $imagePath; ?>" class="img-thumbnail" 
                         style="height: 80px; object-fit: cover; cursor: pointer;"
                         onclick="changeMainImage(this.src)">
                </div>
                <div class="col-3">
                    <img src="<?php echo $imagePath; ?>" class="img-thumbnail" 
                         style="height: 80px; object-fit: cover; cursor: pointer;"
                         onclick="changeMainImage(this.src)">
                </div>
                <div class="col-3">
                    <img src="<?php echo $imagePath; ?>" class="img-thumbnail" 
                         style="height: 80px; object-fit: cover; cursor: pointer;"
                         onclick="changeMainImage(this.src)">
                </div>
            </div>
        </div>
    </div>

    <!-- Product Information -->
    <div class="col-lg-6">
        <!-- Product Title and Rating -->
        <div class="mb-4">
            <h1 class="display-6 fw-bold mb-2"><?php echo htmlspecialchars($product->name, ENT_QUOTES, 'UTF-8'); ?></h1>
            
            <!-- Rating -->
            <div class="d-flex align-items-center mb-3">
                <div class="text-warning me-2">
                    <i class="bi bi-star-fill"></i>
                    <i class="bi bi-star-fill"></i>
                    <i class="bi bi-star-fill"></i>
                    <i class="bi bi-star-fill"></i>
                    <i class="bi bi-star"></i>
                </div>
                <span class="text-muted me-3">4.0 (25 đánh giá)</span>
            </div>

            <!-- Category -->
            <?php if (isset($product->category_name)): ?>
                <div class="mb-3">
                    <span class="text-muted">Danh mục: </span>
                    <a href="/webbanhang/product?category=<?php echo $product->category_id; ?>" 
                       class="badge bg-primary text-decoration-none">
                        <i class="bi bi-tag me-1"></i><?php echo htmlspecialchars($product->category_name, ENT_QUOTES, 'UTF-8'); ?>
                    </a>
                </div>
            <?php endif; ?>
        </div>

        <!-- Price Section -->
        <div class="bg-light p-4 rounded mb-4">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <span class="text-muted small">Giá bán:</span>
                    <div class="d-flex align-items-center">
                        <span class="h2 text-primary fw-bold mb-0">
                            <?php echo number_format($product->price, 0, ',', '.'); ?> đ
                        </span>
                        <span class="text-muted ms-3 text-decoration-line-through">
                            <?php echo number_format($product->price * 1.2, 0, ',', '.'); ?> đ
                        </span>
                        <span class="badge bg-danger ms-2">-17%</span>
                    </div>
                </div>
                <div class="text-end">
                    <small class="text-muted">Giá đã bao gồm VAT</small>
                </div>
            </div>
        </div>

        <!-- Add to Cart Section -->
        <?php 
        $isAdmin = false;
        if (isset($_SESSION['user_id'])) {
            try {
                $isAdmin = AuthHelper::isAdmin();
            } catch (Exception $e) {
                $isAdmin = false;
            }
        }
        ?>
        
        <?php if (!$isAdmin): ?>
            <div class="card shadow-sm mb-4">
                <div class="card-body">
                    <form id="addToCartForm">
                        <div class="row align-items-center">
                            <div class="col-md-6 mb-3 mb-md-0">
                                <label class="form-label fw-bold">Số lượng:</label>
                                <div class="input-group" style="max-width: 120px;">
                                    <button class="btn btn-outline-secondary btn-sm" type="button" onclick="decreaseQuantity()">
                                        <i class="bi bi-dash fw-bold" style="font-size: 0.8rem;"></i>
                                    </button>
                                    <input type="number" class="form-control form-control-sm text-center fw-bold" 
                                           id="quantity" name="quantity" value="1" min="1" max="10">
                                    <button class="btn btn-outline-secondary btn-sm" type="button" onclick="increaseQuantity()">
                                        <i class="bi bi-plus fw-bold" style="font-size: 0.8rem;"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="d-flex flex-column flex-sm-row gap-2">
                                    <button type="submit" class="btn btn-primary flex-fill py-2">
                                        <i class="bi bi-cart-plus me-2"></i>Thêm vào giỏ
                                    </button>
                                    <button type="button" class="btn btn-success flex-fill py-2" onclick="buyNow()">
                                        <i class="bi bi-lightning-fill me-2"></i>Mua ngay
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        <?php else: ?>
            <!-- Admin Actions -->
            <div class="card shadow-sm mb-4">
                <div class="card-body">
                    <h6 class="fw-bold mb-3">Thao tác quản lý:</h6>
                    <div class="d-flex gap-2">
                        <a href="/webbanhang/admin/products/edit/<?php echo $product->id; ?>" class="btn btn-warning">
                            <i class="bi bi-pencil me-2"></i>Chỉnh sửa
                        </a>
                        <button class="btn btn-danger" onclick="confirmDelete(<?php echo $product->id; ?>, '<?php echo addslashes($product->name); ?>')">
                            <i class="bi bi-trash me-2"></i>Xóa sản phẩm
                        </button>
                        <a href="/webbanhang/admin/products" class="btn btn-outline-secondary">
                            <i class="bi bi-arrow-left me-2"></i>Quay lại
                        </a>
                    </div>
                </div>
            </div>
        <?php endif; ?>

        <!-- Product Features -->
        <div class="row mb-4">
            <div class="col-6">
                <div class="d-flex align-items-center mb-2">
                    <i class="bi bi-truck text-primary me-2"></i>
                    <small>Miễn phí vận chuyển</small>
                </div>
                <div class="d-flex align-items-center mb-2">
                    <i class="bi bi-arrow-return-left text-primary me-2"></i>
                    <small>Đổi trả trong 7 ngày</small>
                </div>
            </div>
            <div class="col-6">
                <div class="d-flex align-items-center mb-2">
                    <i class="bi bi-shield-check text-primary me-2"></i>
                    <small>Bảo hành chính hãng</small>
                </div>
                <div class="d-flex align-items-center mb-2">
                    <i class="bi bi-headset text-primary me-2"></i>
                    <small>Hỗ trợ 24/7</small>
                </div>
            </div>
        </div>

        <!-- Share Section -->
        <div class="border-top pt-4">
            <h6 class="fw-bold mb-3">Chia sẻ sản phẩm:</h6>
            <div class="d-flex gap-2">
                <button class="btn btn-outline-primary btn-sm">
                    <i class="bi bi-facebook"></i> Facebook
                </button>
                <button class="btn btn-outline-info btn-sm">
                    <i class="bi bi-twitter"></i> Twitter
                </button>
                <button class="btn btn-outline-success btn-sm">
                    <i class="bi bi-whatsapp"></i> WhatsApp
                </button>
                <button class="btn btn-outline-secondary btn-sm" onclick="copyLink()">
                    <i class="bi bi-link-45deg"></i> Copy Link
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Product Details Tabs -->
<div class="row mt-5">
    <div class="col-12">
        <div class="card shadow-sm">
            <div class="card-header">
                <ul class="nav nav-tabs card-header-tabs" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#description" type="button">
                            <i class="bi bi-info-circle me-1"></i>Mô tả sản phẩm
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" data-bs-toggle="tab" data-bs-target="#specifications" type="button">
                            <i class="bi bi-list-check me-1"></i>Thông số kỹ thuật
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" data-bs-toggle="tab" data-bs-target="#reviews" type="button">
                            <i class="bi bi-star me-1"></i>Đánh giá (25)
                        </button>
                    </li>
                </ul>
            </div>
            <div class="card-body">
                <div class="tab-content">
                    <!-- Description Tab -->
                    <div class="tab-pane fade show active" id="description">
                        <div class="prose product-description">
                            <?php if ($product->description): ?>
                                <?php echo $product->description; ?>
                            <?php else: ?>
                                <p class="text-muted">Chưa có mô tả cho sản phẩm này.</p>
                            <?php endif; ?>
                        </div>
                    </div>
                    
                    <!-- Specifications Tab -->
                    <div class="tab-pane fade" id="specifications">
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <tbody>
                                    <tr>
                                        <td class="fw-bold" style="width: 30%;">Thương hiệu</td>
                                        <td>Chưa cập nhật</td>
                                    </tr>
                                    <tr>
                                        <td class="fw-bold">Xuất xứ</td>
                                        <td>Việt Nam</td>
                                    </tr>
                                    <tr>
                                        <td class="fw-bold">Bảo hành</td>
                                        <td>12 tháng</td>
                                    </tr>
                                    <tr>
                                        <td class="fw-bold">Kích thước</td>
                                        <td>Chưa cập nhật</td>
                                    </tr>
                                    <tr>
                                        <td class="fw-bold">Trọng lượng</td>
                                        <td>Chưa cập nhật</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    
                    <!-- Reviews Tab -->
                    <div class="tab-pane fade" id="reviews">
                        <!-- Review Summary -->
                        <div class="row mb-4">
                            <div class="col-md-4">
                                <div class="text-center">
                                    <div class="display-4 fw-bold text-primary">4.0</div>
                                    <div class="text-warning mb-2">
                                        <i class="bi bi-star-fill"></i>
                                        <i class="bi bi-star-fill"></i>
                                        <i class="bi bi-star-fill"></i>
                                        <i class="bi bi-star-fill"></i>
                                        <i class="bi bi-star"></i>
                                    </div>
                                    <div class="text-muted">25 đánh giá</div>
                                </div>
                            </div>
                            <div class="col-md-8">
                                <div class="mb-2">
                                    <div class="d-flex align-items-center">
                                        <span class="me-2">5</span>
                                        <i class="bi bi-star-fill text-warning me-2"></i>
                                        <div class="progress flex-grow-1 me-2" style="height: 8px;">
                                            <div class="progress-bar" style="width: 60%"></div>
                                        </div>
                                        <span class="text-muted">15</span>
                                    </div>
                                </div>
                                <div class="mb-2">
                                    <div class="d-flex align-items-center">
                                        <span class="me-2">4</span>
                                        <i class="bi bi-star-fill text-warning me-2"></i>
                                        <div class="progress flex-grow-1 me-2" style="height: 8px;">
                                            <div class="progress-bar" style="width: 25%"></div>
                                        </div>
                                        <span class="text-muted">6</span>
                                    </div>
                                </div>
                                <div class="mb-2">
                                    <div class="d-flex align-items-center">
                                        <span class="me-2">3</span>
                                        <i class="bi bi-star-fill text-warning me-2"></i>
                                        <div class="progress flex-grow-1 me-2" style="height: 8px;">
                                            <div class="progress-bar" style="width: 10%"></div>
                                        </div>
                                        <span class="text-muted">2</span>
                                    </div>
                                </div>
                                <div class="mb-2">
                                    <div class="d-flex align-items-center">
                                        <span class="me-2">2</span>
                                        <i class="bi bi-star-fill text-warning me-2"></i>
                                        <div class="progress flex-grow-1 me-2" style="height: 8px;">
                                            <div class="progress-bar" style="width: 5%"></div>
                                        </div>
                                        <span class="text-muted">1</span>
                                    </div>
                                </div>
                                <div class="mb-2">
                                    <div class="d-flex align-items-center">
                                        <span class="me-2">1</span>
                                        <i class="bi bi-star-fill text-warning me-2"></i>
                                        <div class="progress flex-grow-1 me-2" style="height: 8px;">
                                            <div class="progress-bar" style="width: 0%"></div>
                                        </div>
                                        <span class="text-muted">1</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Individual Reviews -->
                        <div class="border-top pt-4">
                            <div class="mb-4">
                                <div class="d-flex align-items-start">
                                    <div class="me-3">
                                        <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center" 
                                             style="width: 50px; height: 50px;">
                                            <i class="bi bi-person-fill"></i>
                                        </div>
                                    </div>
                                    <div class="flex-grow-1">
                                        <div class="d-flex align-items-center mb-2">
                                            <h6 class="mb-0 me-3">Nguyễn Văn A</h6>
                                            <div class="text-warning me-2">
                                                <i class="bi bi-star-fill"></i>
                                                <i class="bi bi-star-fill"></i>
                                                <i class="bi bi-star-fill"></i>
                                                <i class="bi bi-star-fill"></i>
                                                <i class="bi bi-star-fill"></i>
                                            </div>
                                            <small class="text-muted">2 ngày trước</small>
                                        </div>
                                        <p class="mb-0">Sản phẩm rất tốt, chất lượng như mô tả. Giao hàng nhanh, đóng gói cẩn thận. Sẽ mua lại.</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.sticky-top {
    z-index: 1020;
}

.card {
    border: none;
    border-radius: 12px;
    box-shadow: 0 10px 40px rgba(0,0,0,0.1);
    overflow: hidden;
}

.img-thumbnail.active-thumb {
    border-color: #0d6efd;
    border-width: 3px;
    transform: scale(1.05);
    box-shadow: 0 5px 15px rgba(13,110,253,0.3);
}

.img-thumbnail {
    border-radius: 8px;
    transition: all 0.3s ease;
    cursor: pointer;
}

.img-thumbnail:hover {
    transform: scale(1.1);
    box-shadow: 0 5px 15px rgba(0,0,0,0.2);
}

#mainProductImage {
    transition: transform 0.3s ease;
    box-shadow: 0 15px 50px rgba(0,0,0,0.1);
}

#mainProductImage:hover {
    transform: scale(1.02);
}

.btn {
    border-radius: 8px;
    font-weight: 600;
    padding: 12px 24px;
    transition: all 0.3s ease;
}

.btn-primary:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(13,110,253,0.3);
}

.btn-success:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(25,135,84,0.3);
}

.btn-light:hover {
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(0,0,0,0.1);
}

.prose p {
    margin-bottom: 1rem;
    line-height: 1.6;
}

.prose ul, .prose ol {
    margin-bottom: 1rem;
    padding-left: 2rem;
}

.product-description {
    font-size: 1rem;
    line-height: 1.6;
    color: #333;
}

.product-description h1, 
.product-description h2, 
.product-description h3, 
.product-description h4, 
.product-description h5, 
.product-description h6 {
    margin-top: 1.5rem;
    margin-bottom: 1rem;
    font-weight: 600;
    color: #212529;
}

.product-description p {
    margin-bottom: 1rem;
    text-align: justify;
}

.product-description ul, 
.product-description ol {
    margin-bottom: 1rem;
    padding-left: 2rem;
}

.product-description li {
    margin-bottom: 0.5rem;
}

.product-description strong {
    font-weight: 600;
    color: #212529;
}

.product-description em {
    font-style: italic;
}

.product-description blockquote {
    border-left: 4px solid #007bff;
    padding-left: 1rem;
    margin: 1rem 0;
    font-style: italic;
    background-color: #f8f9fa;
    padding: 1rem;
    border-radius: 4px;
}

.product-description img {
    max-width: 100%;
    height: auto;
    border-radius: 8px;
    margin: 1rem 0;
}

/* Quantity Controls Styling */
.input-group .btn-sm {
    border-width: 1px;
    font-weight: 600;
    padding: 6px 10px;
    transition: all 0.3s ease;
    min-width: 32px;
    height: 34px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 4px;
}

.input-group .btn-sm:hover {
    background-color: #007bff;
    border-color: #007bff;
    color: white;
    transform: scale(1.05);
    z-index: 3;
}

.input-group .form-control-sm:focus {
    border-color: #007bff;
    box-shadow: 0 0 0 0.1rem rgba(0, 123, 255, 0.25);
    outline: none;
    z-index: 3;
}

#quantity {
    font-weight: 700;
    background-color: #f8f9fa;
    border-color: #dee2e6;
    transition: all 0.3s ease;
    text-align: center;
    font-size: 0.9rem !important;
    height: 34px;
    border-radius: 0;
}

#quantity:focus {
    background-color: white;
}

/* Remove spinner arrows from number input */
#quantity::-webkit-outer-spin-button,
#quantity::-webkit-inner-spin-button {
    -webkit-appearance: none;
    margin: 0;
}

#quantity[type=number] {
    -moz-appearance: textfield;
}

/* Fix input group spacing */
.input-group {
    display: flex;
    align-items: center;
    justify-content: flex-start;
}

.input-group > .btn-sm:first-child {
    border-top-right-radius: 0;
    border-bottom-right-radius: 0;
    margin-right: -1px;
}

.input-group > .form-control-sm {
    border-radius: 0;
    margin: 0 -1px;
}

.input-group > .btn-sm:last-child {
    border-top-left-radius: 0;
    border-bottom-left-radius: 0;
    margin-left: -1px;
}

/* Button improvements */
.btn.py-2 {
    padding-top: 12px;
    padding-bottom: 12px;
    font-weight: 600;
    border-radius: 8px;
}

@media (max-width: 768px) {
    #mainProductImage {
        height: 400px !important;
    }
    
    .btn {
        padding: 10px 20px;
        font-size: 0.9rem;
    }
}
</style>

<script>
// Image Gallery Functions
function changeMainImage(src) {
    document.getElementById('mainProductImage').src = src;
    
    // Update active thumbnail
    document.querySelectorAll('.img-thumbnail').forEach(thumb => {
        thumb.classList.remove('active-thumb');
    });
    event.target.classList.add('active-thumb');
}

<?php if (!$isAdmin): ?>
// User and Guest functions

// Quantity Functions
function increaseQuantity() {
    console.log('increaseQuantity called');
    const quantityInput = document.getElementById('quantity');
    if (!quantityInput) {
        console.error('Quantity input not found');
        return;
    }
    
    const currentValue = parseInt(quantityInput.value);
    const maxValue = parseInt(quantityInput.max);
    
    console.log('Current:', currentValue, 'Max:', maxValue);
    
    if (currentValue < maxValue) {
        quantityInput.value = currentValue + 1;
        console.log('Increased to:', quantityInput.value);
        
        // Add visual feedback
        quantityInput.style.backgroundColor = '#d4edda';
        quantityInput.style.transform = 'scale(1.05)';
        setTimeout(() => {
            quantityInput.style.backgroundColor = '#f8f9fa';
            quantityInput.style.transform = 'scale(1)';
        }, 200);
    } else {
        console.log('At maximum value');
        // Flash red if at maximum
        quantityInput.style.backgroundColor = '#f8d7da';
        setTimeout(() => {
            quantityInput.style.backgroundColor = '#f8f9fa';
        }, 200);
    }
}

function decreaseQuantity() {
    console.log('decreaseQuantity called');
    const quantityInput = document.getElementById('quantity');
    if (!quantityInput) {
        console.error('Quantity input not found');
        return;
    }
    
    const currentValue = parseInt(quantityInput.value);
    const minValue = parseInt(quantityInput.min);
    
    console.log('Current:', currentValue, 'Min:', minValue);
    
    if (currentValue > minValue) {
        quantityInput.value = currentValue - 1;
        console.log('Decreased to:', quantityInput.value);
        
        // Add visual feedback
        quantityInput.style.backgroundColor = '#cce5ff';
        quantityInput.style.transform = 'scale(1.05)';
        setTimeout(() => {
            quantityInput.style.backgroundColor = '#f8f9fa';
            quantityInput.style.transform = 'scale(1)';
        }, 200);
    } else {
        console.log('At minimum value');
        // Flash red if at minimum
        quantityInput.style.backgroundColor = '#f8d7da';
        setTimeout(() => {
            quantityInput.style.backgroundColor = '#f8f9fa';
        }, 200);
    }
}

// Buy now function - add to cart then redirect to checkout
function buyNow() {
    const formData = new FormData(document.getElementById('addToCartForm'));
    formData.append('product_id', <?php echo $product->id; ?>);
    
    fetch('/webbanhang/user/cart/add', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Redirect directly to checkout
            window.location.href = '/webbanhang/checkout';
        } else {
            alert(data.message || 'Có lỗi xảy ra');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Có lỗi xảy ra khi xử lý');
    });
}

// Add to cart form handler
document.addEventListener('DOMContentLoaded', function() {
    console.log('DOM loaded, setting up form handler');
    const form = document.getElementById('addToCartForm');
    if (form) {
        console.log('Form found, adding event listener');
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            formData.append('product_id', <?php echo $product->id; ?>);
            
            fetch('/webbanhang/user/cart/add', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Chỉ alert thành công, không redirect
                    alert('✅ ' + data.message);
                    
                    // Reset quantity về 1 sau khi thêm thành công
                    document.getElementById('quantity').value = 1;
                } else {
                    alert('❌ ' + (data.message || 'Có lỗi xảy ra'));
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('❌ Có lỗi xảy ra khi thêm vào giỏ hàng');
            });
        });
    } else {
        console.error('addToCartForm not found');
    }
});
<?php endif; ?>

// Copy Link Function
function copyLink() {
    navigator.clipboard.writeText(window.location.href).then(function() {
        alert('Đã sao chép link sản phẩm!');
    });
}

<?php if ($isAdmin): ?>
// Admin functions
function confirmDelete(productId, productName) {
    if (confirm(`Bạn có chắc chắn muốn xóa sản phẩm "${productName}"?`)) {
        fetch(`/webbanhang/admin/products/delete/${productId}`, {
            method: 'POST'
        })
        .then(response => {
            if (response.ok) {
                alert('Xóa sản phẩm thành công');
                window.location.href = '/webbanhang/admin/products';
            } else {
                alert('Có lỗi xảy ra khi xóa sản phẩm');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Có lỗi xảy ra khi xóa sản phẩm');
        });
    }
}
<?php endif; ?>
</script>

<?php include_once 'app/views/shares/footer.php'; ?> 
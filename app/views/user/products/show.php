<?php include_once 'app/views/shares/header.php'; ?>

<!-- Breadcrumb -->
<nav aria-label="breadcrumb" class="mb-4">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="/webbanhang/" class="text-decoration-none">Trang chủ</a></li>
        <li class="breadcrumb-item"><a href="/webbanhang/user/products" class="text-decoration-none">Sản phẩm</a></li>
        <?php if (isset($product->category_name)): ?>
            <li class="breadcrumb-item">
                <a href="/webbanhang/user/products?category=<?php echo $product->category_id; ?>" class="text-decoration-none">
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
                    $imagePath = $product->image ? '/webbanhang/public/uploads/products/' . $product->image : '/webbanhang/public/images/no-image.jpg';
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
            
            <!-- Rating and Reviews -->
            <div class="d-flex align-items-center mb-3">
                <div class="text-warning me-2">
                    <i class="bi bi-star-fill"></i>
                    <i class="bi bi-star-fill"></i>
                    <i class="bi bi-star-fill"></i>
                    <i class="bi bi-star-fill"></i>
                    <i class="bi bi-star"></i>
                </div>
                <span class="text-muted me-3">4.0 (25 đánh giá)</span>
                <span class="text-muted">|</span>
                <span class="text-muted ms-3">Đã bán: 150+</span>
            </div>

            <!-- Category -->
            <?php if (isset($product->category_name)): ?>
                <div class="mb-3">
                    <span class="text-muted">Danh mục: </span>
                    <a href="/webbanhang/user/products?category=<?php echo $product->category_id; ?>" 
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
        <div class="card shadow-sm mb-4">
            <div class="card-body">
                <form id="addToCartForm">
                    <div class="row align-items-center">
                        <div class="col-md-4">
                            <label class="form-label fw-bold">Số lượng:</label>
                            <div class="input-group">
                                <button class="btn btn-outline-secondary" type="button" onclick="decreaseQuantity()">
                                    <i class="bi bi-dash"></i>
                                </button>
                                <input type="number" class="form-control text-center" 
                                       id="quantity" name="quantity" value="1" min="1" max="10">
                                <button class="btn btn-outline-secondary" type="button" onclick="increaseQuantity()">
                                    <i class="bi bi-plus"></i>
                                </button>
                            </div>
                            <small class="text-muted">Còn lại: 50 sản phẩm</small>
                        </div>
                        <div class="col-md-8">
                            <div class="d-flex gap-2">
                                <button type="submit" class="btn btn-primary flex-fill">
                                    <i class="bi bi-cart-plus me-2"></i>Thêm vào giỏ
                                </button>
                                <button type="button" class="btn btn-success flex-fill" onclick="buyNow()">
                                    <i class="bi bi-lightning-fill me-2"></i>Mua ngay
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>

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
                        <div class="prose">
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

<!-- Related Products -->
<div class="row mt-5">
    <div class="col-12">
        <h3 class="fw-bold mb-4">
            <i class="bi bi-arrow-repeat me-2"></i>Sản phẩm liên quan
        </h3>
        <div class="row">
            <!-- Related product cards would go here -->
            <div class="col-md-3 mb-4">
                <div class="card shadow-sm">
                    <img src="<?php echo $imagePath; ?>" class="card-img-top" style="height: 200px; object-fit: cover;">
                    <div class="card-body">
                        <h6 class="card-title">Sản phẩm liên quan 1</h6>
                        <p class="text-primary fw-bold">500.000 đ</p>
                        <button class="btn btn-outline-primary btn-sm">
                            <i class="bi bi-cart-plus"></i> Thêm vào giỏ
                        </button>
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

.form-check-input:checked {
    background-color: #0d6efd;
    border-color: #0d6efd;
}

.form-check-label {
    cursor: pointer;
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

.progress {
    border-radius: 4px;
}

.badge {
    font-size: 0.9rem;
    padding: 8px 16px;
    border-radius: 8px;
}

.bg-light {
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%) !important;
    border-radius: 12px;
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

// Quantity Functions
function increaseQuantity() {
    const quantityInput = document.getElementById('quantity');
    const currentValue = parseInt(quantityInput.value);
    const maxValue = parseInt(quantityInput.max);
    
    if (currentValue < maxValue) {
        quantityInput.value = currentValue + 1;
    }
}

function decreaseQuantity() {
    const quantityInput = document.getElementById('quantity');
    const currentValue = parseInt(quantityInput.value);
    const minValue = parseInt(quantityInput.min);
    
    if (currentValue > minValue) {
        quantityInput.value = currentValue - 1;
    }
}

// Buy now function - add to cart then redirect to checkout
function buyNow() {
    const formData = new FormData(document.getElementById('addToCartForm'));
    formData.append('product_id', <?php echo $product->id; ?>);
    
    fetch('/webbanhang/cart/add', {
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

// Copy Link Function
function copyLink() {
    navigator.clipboard.writeText(window.location.href).then(function() {
        alert('Đã sao chép link sản phẩm!');
    });
}

// Add to cart form handler
document.getElementById('addToCartForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    formData.append('product_id', <?php echo $product->id; ?>);
    
    fetch('/webbanhang/cart/add', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert(data.message);
            // Redirect to cart
            window.location.href = '/webbanhang/cart';
        } else {
            alert(data.message || 'Có lỗi xảy ra');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Có lỗi xảy ra khi thêm vào giỏ hàng');
    });
});
</script>

<?php include_once 'app/views/shares/footer.php'; ?> 
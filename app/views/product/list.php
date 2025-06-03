<?php include_once 'app/views/shares/header.php'; ?>

<div class="row mb-4">
    <div class="col-md-8">
        <h1 class="display-6 fw-bold text-primary">
            <i class="bi bi-grid-3x3-gap me-2"></i>
            <?php if (AuthHelper::isAdmin()): ?>
                Quản lý sản phẩm
            <?php else: ?>
                Sản phẩm
            <?php endif; ?>
        </h1>
        <p class="text-muted mb-0">
            <?php if (AuthHelper::isAdmin()): ?>
                Quản lý toàn bộ sản phẩm trong hệ thống
            <?php else: ?>
                Khám phá bộ sưu tập sản phẩm chất lượng cao
            <?php endif; ?>
        </p>
    </div>
    <div class="col-md-4 text-end">
        <div class="d-flex justify-content-end align-items-center gap-2">
            <?php if (AuthHelper::isAdmin()): ?>
                <!-- Admin Actions -->
                <a href="/webbanhang/admin/products/create" class="btn btn-success">
                    <i class="bi bi-plus-circle me-1"></i>Thêm sản phẩm
                </a>
                <a href="/webbanhang/admin/dashboard" class="btn btn-outline-secondary">
                    <i class="bi bi-speedometer2 me-1"></i>Dashboard
                </a>
            <?php else: ?>
                <!-- User Actions - Clean layout without search and cart -->
                <a href="/webbanhang/" class="btn btn-outline-primary">
                    <i class="bi bi-house me-1"></i>Trang chủ
                </a>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php if (empty($products)): ?>
    <div class="text-center py-5">
        <i class="bi bi-box-seam display-1 text-muted mb-3"></i>
        <h3 class="text-muted">Không có sản phẩm nào</h3>
        <p class="text-muted">
            <?php if (AuthHelper::isAdmin()): ?>
                Hiện tại chưa có sản phẩm nào trong hệ thống. Hãy thêm sản phẩm mới!
            <?php else: ?>
                Hiện tại chưa có sản phẩm nào được hiển thị.
            <?php endif; ?>
        </p>
        <?php if (AuthHelper::isAdmin()): ?>
            <a href="/webbanhang/admin/products/create" class="btn btn-primary">
                <i class="bi bi-plus-circle me-2"></i>Thêm sản phẩm đầu tiên
            </a>
        <?php else: ?>
            <a href="/webbanhang/" class="btn btn-primary">
                <i class="bi bi-house me-2"></i>Về trang chủ
            </a>
        <?php endif; ?>
    </div>
<?php else: ?>
    <!-- Products Grid -->
    <?php if (AuthHelper::isAdmin()): ?>
        <!-- Admin Table View -->
        <div class="card shadow-sm">
            <div class="card-header bg-light">
                <h5 class="mb-0">
                    <i class="bi bi-list-ul me-2"></i>Danh sách sản phẩm 
                    <span class="badge bg-primary"><?php echo count($products); ?> sản phẩm</span>
                </h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Hình ảnh</th>
                                <th>Tên sản phẩm</th>
                                <th>Danh mục</th>
                                <th>Giá</th>
                                <th>Trạng thái</th>
                                <th>Thao tác</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($products as $product): ?>
                                <tr>
                                    <td><?php echo $product->id; ?></td>
                                    <td>
                                        <?php 
                                        $imagePath = $product->image ? '/webbanhang/public/uploads/products/' . $product->image : 'data:image/svg+xml;base64,' . base64_encode('<svg width="60" height="60" xmlns="http://www.w3.org/2000/svg"><rect width="60" height="60" fill="#f8f9fa"/><text x="30" y="35" text-anchor="middle" font-size="10" fill="#6c757d">No Image</text></svg>');
                                        ?>
                                        <img src="<?php echo $imagePath; ?>" 
                                             class="img-thumbnail" 
                                             style="width: 60px; height: 60px; object-fit: cover;">
                                    </td>
                                    <td>
                                        <strong><?php echo htmlspecialchars($product->name, ENT_QUOTES, 'UTF-8'); ?></strong>
                                        <div class="small text-muted">
                                            <?php echo substr(strip_tags($product->description ?? ''), 0, 50) . '...'; ?>
                                        </div>
                                    </td>
                                    <td>
                                        <?php echo isset($product->category_name) ? htmlspecialchars($product->category_name, ENT_QUOTES, 'UTF-8') : '<em>Chưa phân loại</em>'; ?>
                                    </td>
                                    <td>
                                        <span class="fw-bold text-primary">
                                            <?php echo number_format($product->price, 0, ',', '.'); ?> đ
                                        </span>
                                    </td>
                                    <td>
                                        <span class="badge bg-success">Còn hàng</span>
                                    </td>
                                    <td>
                                        <div class="btn-group btn-group-sm">
                                            <a href="/webbanhang/product/show/<?php echo $product->id; ?>" 
                                               class="btn btn-outline-info" title="Xem">
                                                <i class="bi bi-eye"></i>
                                            </a>
                                            <a href="/webbanhang/admin/products/edit/<?php echo $product->id; ?>" 
                                               class="btn btn-outline-warning" title="Sửa">
                                                <i class="bi bi-pencil"></i>
                                            </a>
                                            <button class="btn btn-outline-danger" 
                                                    title="Xóa"
                                                    onclick="confirmDelete(<?php echo $product->id; ?>, '<?php echo addslashes($product->name); ?>')">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    <?php else: ?>
        <!-- User Grid View -->
        <div class="row">
            <?php foreach ($products as $product): ?>
                <div class="col-lg-4 col-md-6 mb-4">
                    <div class="card h-100 shadow-sm product-card">
                        <!-- Product Image -->
                        <div class="position-relative overflow-hidden product-image-container">
                            <?php 
                            $imagePath = $product->image ? '/webbanhang/public/uploads/products/' . $product->image : 'data:image/svg+xml;base64,' . base64_encode('<svg width="300" height="300" xmlns="http://www.w3.org/2000/svg"><rect width="300" height="300" fill="#f8f9fa"/><circle cx="150" cy="130" r="25" fill="none" stroke="#dee2e6" stroke-width="3"/><path d="M135 130 L150 145 L165 130 M140 135 L160 135" stroke="#dee2e6" stroke-width="3" fill="none"/><text x="150" y="180" text-anchor="middle" font-family="Arial, sans-serif" font-size="16" fill="#6c757d">Không có ảnh</text></svg>');
                            ?>
                            <img src="<?php echo $imagePath; ?>" 
                                 class="card-img-top product-image" 
                                 alt="<?php echo htmlspecialchars($product->name, ENT_QUOTES, 'UTF-8'); ?>"
                                 loading="lazy">
                            
                            <!-- Product Badge -->
                            <div class="position-absolute top-0 start-0 p-2">
                                <span class="badge bg-primary">Mới</span>
                            </div>

                            <!-- Quick Actions -->
                            <div class="position-absolute top-0 end-0 p-2">
                                <div class="btn-group-vertical">
                                    <a href="/webbanhang/product/show/<?php echo $product->id; ?>" 
                                       class="btn btn-sm btn-light" title="Xem chi tiết">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    <button class="btn btn-sm btn-light" title="Yêu thích">
                                        <i class="bi bi-heart"></i>
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- Product Info -->
                        <div class="card-body d-flex flex-column">
                            <h5 class="card-title text-truncate" title="<?php echo htmlspecialchars($product->name, ENT_QUOTES, 'UTF-8'); ?>">
                                <?php echo htmlspecialchars($product->name, ENT_QUOTES, 'UTF-8'); ?>
                            </h5>
                            
                            <!-- Category -->
                            <div class="mb-2">
                                <small class="text-muted">
                                    <i class="bi bi-tag me-1"></i>
                                    <?php echo isset($product->category_name) ? htmlspecialchars($product->category_name, ENT_QUOTES, 'UTF-8') : 'Chưa phân loại'; ?>
                                </small>
                            </div>

                            <!-- Description -->
                            <p class="card-text text-muted small flex-grow-1">
                                <?php 
                                $description = strip_tags($product->description ?? '');
                                echo strlen($description) > 100 ? substr($description, 0, 100) . '...' : $description;
                                ?>
                            </p>

                            <!-- Price -->
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <div>
                                    <span class="h5 text-primary fw-bold">
                                        <?php echo number_format($product->price, 0, ',', '.'); ?> đ
                                    </span>
                                </div>
                                <div class="text-end">
                                    <div class="text-warning small">
                                        <i class="bi bi-star-fill"></i>
                                        <i class="bi bi-star-fill"></i>
                                        <i class="bi bi-star-fill"></i>
                                        <i class="bi bi-star-fill"></i>
                                        <i class="bi bi-star"></i>
                                        <span class="text-muted">(4.0)</span>
                                    </div>
                                </div>
                            </div>

                            <!-- Actions -->
                            <div class="d-grid gap-2">
                                <div class="btn-group">
                                    <a href="/webbanhang/product/show/<?php echo $product->id; ?>" 
                                       class="btn btn-outline-primary">
                                        <i class="bi bi-eye me-1"></i>Chi tiết
                                    </a>
                                    <button class="btn btn-primary" onclick="addToCart(<?php echo $product->id; ?>)">
                                        <i class="bi bi-cart-plus me-1"></i>Thêm vào giỏ
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
<?php endif; ?>

<style>
.product-card {
    transition: all 0.3s ease;
    border: none;
    border-radius: 12px;
    overflow: hidden;
    box-shadow: 0 5px 20px rgba(0,0,0,0.08);
}

.product-card:hover {
    transform: translateY(-8px);
    box-shadow: 0 20px 40px rgba(0,0,0,0.15) !important;
}

.product-image-container {
    height: 300px;
    border-radius: 12px 12px 0 0;
    overflow: hidden;
    background: #f8f9fa;
}

.product-card .product-image {
    width: 100% !important;
    height: 100% !important;
    max-width: none !important;
    object-fit: cover;
    object-position: center;
    transition: transform 0.3s ease;
    display: block;
}

.product-card:hover .product-image {
    transform: scale(1.05);
}

.btn-group-vertical .btn {
    opacity: 0;
    transition: all 0.3s ease;
    border-radius: 8px;
    backdrop-filter: blur(10px);
    background: rgba(255, 255, 255, 0.9);
    border: 1px solid rgba(255, 255, 255, 0.2);
}

.product-card:hover .btn-group-vertical .btn {
    opacity: 1;
}

.btn {
    border-radius: 8px;
    font-weight: 600;
    transition: all 0.3s ease;
}

.btn-primary:hover, .btn-outline-primary:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(13,110,253,0.3);
}

@media (max-width: 768px) {
    .product-card:hover {
        transform: translateY(-4px);
    }
    
    .product-image-container {
        height: 250px;
    }
    
    .btn-group-vertical .btn {
        opacity: 1; /* Always show on mobile */
    }
}
</style>

<script>
<?php if (!AuthHelper::isAdmin()): ?>
// User functions
function addToCart(productId) {
    const formData = new FormData();
    formData.append('product_id', productId);
    formData.append('quantity', 1);
    
    fetch('/webbanhang/user/cart/add', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert(data.message);
            // Optionally redirect to cart
            window.location.href = '/webbanhang/user/cart';
        } else {
            alert(data.message || 'Có lỗi xảy ra');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Có lỗi xảy ra khi thêm vào giỏ hàng');
    });
}
<?php else: ?>
// Admin functions
function confirmDelete(productId, productName) {
    if (confirm(`Bạn có chắc chắn muốn xóa sản phẩm "${productName}"?`)) {
        fetch(`/webbanhang/admin/products/delete/${productId}`, {
            method: 'POST'
        })
        .then(response => {
            if (response.ok) {
                alert('Xóa sản phẩm thành công');
                location.reload();
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
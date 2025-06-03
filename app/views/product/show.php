<?php include 'app/views/shares/header.php'; ?>

<nav aria-label="breadcrumb" class="mb-4">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="/webbanhang" class="text-decoration-none">Trang chủ</a></li>
        <li class="breadcrumb-item"><a href="/webbanhang/Product" class="text-decoration-none">Sản phẩm</a></li>
        <li class="breadcrumb-item active" aria-current="page">Chi tiết sản phẩm</li>
    </ol>
</nav>

<?php if ($product): ?>
<div class="card shadow-sm mb-4 border-0 rounded-3 overflow-hidden">
    <div class="card-body p-0">
        <div class="row g-0">
            <!-- Product Image -->
            <div class="col-md-5">
                <div class="product-gallery p-4 h-100 d-flex align-items-center justify-content-center bg-light">
                    <?php if (!empty($product->image)): ?>
                        <img src="/webbanhang/public/uploads/<?php echo htmlspecialchars($product->image, ENT_QUOTES, 'UTF-8'); ?>" 
                             alt="<?php echo htmlspecialchars($product->name, ENT_QUOTES, 'UTF-8'); ?>" 
                             class="img-fluid rounded-3 product-image">
                    <?php else: ?>
                        <div class="text-center">
                            <i class="bi bi-image text-secondary mb-3" style="font-size: 5rem;"></i>
                            <p class="text-muted">Không có hình ảnh</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
            
            <!-- Product Info -->
            <div class="col-md-7">
                <div class="p-4 p-md-5">
                    <div class="d-flex justify-content-between align-items-start">
                        <h1 class="display-6 fw-bold mb-3"><?php echo htmlspecialchars($product->name, ENT_QUOTES, 'UTF-8'); ?></h1>
                        <?php if (AuthHelper::isAdmin()): ?>
                            <div class="d-flex">
                                <a href="/webbanhang/Product/edit/<?php echo $product->id; ?>" class="btn btn-primary me-2">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <a href="/webbanhang/Product/delete/<?php echo $product->id; ?>" 
                                   onclick="return confirm('Bạn có chắc chắn muốn xóa sản phẩm này?');" 
                                   class="btn btn-danger">
                                    <i class="bi bi-trash"></i>
                                </a>
                            </div>
                        <?php endif; ?>
                    </div>
                    
                    <div class="mb-4">
                        <span class="fs-2 fw-bold text-primary"><?php echo number_format($product->price, 0, ',', '.'); ?> đ</span>
                    </div>
                    
                    <?php 
                    // Get category name
                    $categoryName = '';
                    if (!empty($product->category_id)) {
                        $categoryModel = new CategoryModel((new Database())->getConnection());
                        $category = $categoryModel->getCategoryById($product->category_id);
                        if ($category) {
                            $categoryName = $category->name;
                        }
                    }
                    if (!empty($categoryName)): ?>
                    <div class="mb-4">
                        <h6 class="fw-bold text-muted mb-2">Danh mục:</h6>
                        <span class="badge bg-secondary fs-6">
                            <i class="bi bi-tag-fill me-1"></i>
                            <?php echo htmlspecialchars($categoryName, ENT_QUOTES, 'UTF-8'); ?>
                        </span>
                    </div>
                    <?php endif; ?>
                    
                    <div class="mb-4">
                        <h6 class="fw-bold text-muted mb-2">Mô tả sản phẩm:</h6>
                        <div class="product-description p-3 border rounded-3 bg-light">
                            <?php echo html_entity_decode($product->description); ?>
                        </div>
                    </div>
                    
                    <div class="mt-4 pt-3 border-top">
                        <div class="d-flex gap-2">
                            <a href="/webbanhang/Product/addToCart/<?php echo $product->id; ?>" class="btn btn-success">
                                <i class="bi bi-cart-plus me-2"></i>Thêm vào giỏ hàng
                            </a>
                            <a href="/webbanhang/Product" class="btn btn-outline-secondary">
                                <i class="bi bi-arrow-left me-2"></i> Quay lại danh sách
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php else: ?>
    <div class="alert alert-danger shadow-sm">
        <div class="d-flex align-items-center">
            <i class="bi bi-exclamation-triangle-fill me-2 fs-1"></i>
            <div>
                <h4 class="alert-heading">Không tìm thấy!</h4>
                <p class="mb-0">Không tìm thấy thông tin sản phẩm bạn đang tìm kiếm.</p>
            </div>
        </div>
        <hr>
        <div class="text-end">
            <a href="/webbanhang/Product" class="btn btn-outline-danger">
                <i class="bi bi-arrow-left me-2"></i> Quay lại danh sách sản phẩm
            </a>
        </div>
    </div>
<?php endif; ?>

<style>
.product-image {
    max-height: 400px;
    width: auto;
    object-fit: contain;
}

.product-description img {
    max-width: 100%;
    height: auto;
    border-radius: 4px;
}

.product-gallery {
    background-color: #f8f9fa;
    border-radius: 5px;
    position: relative;
    min-height: 400px;
}

@media (max-width: 767.98px) {
    .product-gallery {
        min-height: 300px;
    }
}
</style>

<?php include 'app/views/shares/footer.php'; ?>
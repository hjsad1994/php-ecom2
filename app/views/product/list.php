<?php include 'app/views/shares/header.php'; ?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="display-5 fw-bold"><i class="bi bi-grid-3x3-gap me-2"></i>Danh sách sản phẩm</h1>
    <a href="/webbanhang/Product/add" class="btn btn-success btn-lg">
        <i class="bi bi-plus-circle me-2"></i>Thêm sản phẩm mới
    </a>
</div>

<?php if (empty($products)): ?>
    <div class="alert alert-info shadow-sm d-flex align-items-center" role="alert">
        <i class="bi bi-info-circle-fill me-2 fs-4"></i>
        <div>Chưa có sản phẩm nào. Hãy thêm sản phẩm mới!</div>
    </div>
<?php else: ?>
    <div class="row row-cols-1 row-cols-md-3 g-4">
        <?php foreach ($products as $product): ?>
        <div class="col">
            <div class="card h-100 shadow-sm">
                <div class="position-relative product-img-wrapper">
                    <?php if (!empty($product->image)): ?>
                        <a href="/webbanhang/Product/show/<?php echo $product->id; ?>" class="product-img-container">
                            <img src="/webbanhang/public/uploads/<?php echo htmlspecialchars($product->image, ENT_QUOTES, 'UTF-8'); ?>" 
                                class="card-img-top" alt="<?php echo htmlspecialchars($product->name, ENT_QUOTES, 'UTF-8'); ?>">
                            <div class="img-overlay"></div>
                        </a>
                    <?php else: ?>
                        <a href="/webbanhang/Product/show/<?php echo $product->id; ?>" class="product-img-container no-image">
                            <div class="d-flex justify-content-center align-items-center h-100">
                                <i class="bi bi-image text-secondary" style="font-size: 3rem;"></i>
                            </div>
                        </a>
                    <?php endif; ?>
                    
                    <div class="position-absolute top-0 end-0 p-2">
                        <span class="badge bg-primary price-badge">
                            <?php echo number_format($product->price, 0, ',', '.'); ?> đ
                        </span>
                    </div>
                </div>
                
                <div class="card-body">
                    <h5 class="card-title text-truncate">
                        <a href="/webbanhang/Product/show/<?php echo $product->id; ?>" class="text-decoration-none text-dark">
                            <?php echo htmlspecialchars($product->name, ENT_QUOTES, 'UTF-8'); ?>
                        </a>
                    </h5>
                    
                    <?php if (!empty($product->category_name)): ?>
                        <p class="mb-3">
                            <span class="badge bg-secondary">
                                <i class="bi bi-tag-fill me-1"></i>
                                <?php echo htmlspecialchars($product->category_name, ENT_QUOTES, 'UTF-8'); ?>
                            </span>
                        </p>
                    <?php endif; ?>
                    
                    <div class="card-text mb-3">
                        <div class="product-description-preview">
                            <?php echo html_entity_decode($product->description); ?>
                        </div>
                        <div class="text-end mt-2">
                            <a href="/webbanhang/Product/show/<?php echo $product->id; ?>" class="text-decoration-none text-primary small">
                                Xem thêm <i class="bi bi-arrow-right"></i>
                            </a>
                        </div>
                    </div>
                    
                    <div class="d-grid gap-2 mt-auto">
                        <a href="/webbanhang/Product/show/<?php echo $product->id; ?>" class="btn btn-outline-primary">
                            <i class="bi bi-eye me-1"></i> Xem chi tiết
                        </a>
                    </div>
                </div>
                
                <div class="card-footer bg-white border-top-0 pt-0">
                    <div class="d-flex justify-content-between">
                        <a href="/webbanhang/Product/edit/<?php echo $product->id; ?>" class="btn btn-sm btn-outline-secondary">
                            <i class="bi bi-pencil"></i> Sửa
                        </a>
                        <a href="/webbanhang/Product/delete/<?php echo $product->id; ?>" 
                           onclick="return confirm('Bạn có chắc chắn muốn xóa sản phẩm này?');" 
                           class="btn btn-sm btn-outline-danger">
                            <i class="bi bi-trash"></i> Xóa
                        </a>
                    </div>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
<?php endif; ?>

<style>
/* Enhanced image handling */
.product-img-wrapper {
    height: 200px;
    overflow: hidden;
    background-color: #f8f9fa;
}

.product-img-container {
    display: block;
    width: 100%;
    height: 100%;
    position: relative;
    overflow: hidden;
}

.product-img-container img {
    width: 100%;
    height: 100%;
    object-fit: contain; /* This preserves aspect ratio */
    transition: transform 0.5s ease, opacity 0.3s ease;
}

.no-image {
    background-color: #f8f9fa;
}

.card:hover .product-img-container img {
    transform: scale(1.05);
}

.img-overlay {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: linear-gradient(0deg, rgba(0,0,0,0.2) 0%, rgba(0,0,0,0) 50%);
    transition: background 0.3s ease;
}

.card:hover .img-overlay {
    background: linear-gradient(0deg, rgba(0,0,0,0.3) 0%, rgba(0,0,0,0.1) 50%);
}

/* Price badge styling */
.price-badge {
    font-size: 0.85rem;
    font-weight: 600;
    padding: 0.5rem 0.75rem;
    box-shadow: 0 2px 5px rgba(0,0,0,0.2);
}

/* Description preview styling */
.product-description-preview {
    max-height: 80px;
    overflow: hidden;
    position: relative;
}

.product-description-preview::after {
    content: '';
    position: absolute;
    bottom: 0;
    left: 0;
    right: 0;
    height: 40px;
    background: linear-gradient(to bottom, rgba(255,255,255,0) 0%, rgba(255,255,255,1) 100%);
}
</style>

<?php include 'app/views/shares/footer.php'; ?>
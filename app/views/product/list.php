<?php include 'app/views/shares/header.php'; ?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h1>Danh sách sản phẩm</h1>
    <a href="/webbanhang/Product/add" class="btn btn-success">
        <i class="bi bi-plus-circle"></i> Thêm sản phẩm mới
    </a>
</div>

<?php if (empty($products)): ?>
    <div class="alert alert-info">
        Chưa có sản phẩm nào. Hãy thêm sản phẩm mới!
    </div>
<?php else: ?>
    <div class="row row-cols-1 row-cols-md-3 g-4">
        <?php foreach ($products as $product): ?>
        <div class="col">
            <div class="card h-100 shadow-sm">
                <?php if (!empty($product->image)): ?>
                    <div class="text-center pt-3">
                        <img src="/webbanhang/public/uploads/<?php echo htmlspecialchars($product->image, ENT_QUOTES, 'UTF-8'); ?>" 
                             class="card-img-top" alt="<?php echo htmlspecialchars($product->name, ENT_QUOTES, 'UTF-8'); ?>"
                             style="max-height: 200px; width: auto; max-width: 100%;">
                    </div>
                <?php endif; ?>
                <div class="card-body">
                    <h5 class="card-title"><?php echo htmlspecialchars($product->name, ENT_QUOTES, 'UTF-8'); ?></h5>
                    <p class="card-text"><?php echo htmlspecialchars($product->description, ENT_QUOTES, 'UTF-8'); ?></p>
                    <p class="card-text">
                        <span class="badge bg-info text-dark">
                            Giá: <?php echo number_format($product->price, 0, ',', '.'); ?> đ
                        </span>
                        <?php if (!empty($product->category_name)): ?>
                        <span class="badge bg-secondary">
                            Danh mục: <?php echo htmlspecialchars($product->category_name, ENT_QUOTES, 'UTF-8'); ?>
                        </span>
                        <?php endif; ?>
                    </p>
                </div>
                <div class="card-footer bg-transparent border-top-0">
                    <div class="d-flex justify-content-between">
                        <a href="/webbanhang/Product/edit/<?php echo $product->id; ?>" class="btn btn-primary btn-sm">
                            <i class="bi bi-pencil"></i> Sửa
                        </a>
                        <a href="/webbanhang/Product/delete/<?php echo $product->id; ?>" 
                           onclick="return confirm('Bạn có chắc chắn muốn xóa sản phẩm này?');" 
                           class="btn btn-danger btn-sm">
                            <i class="bi bi-trash"></i> Xóa
                        </a>
                    </div>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
<?php endif; ?>

<?php include 'app/views/shares/footer.php'; ?>
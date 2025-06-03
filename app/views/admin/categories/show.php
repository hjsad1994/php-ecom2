<?php include_once 'app/views/shares/header.php'; ?>

<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="display-6 fw-bold text-primary mb-2">
                <i class="bi bi-eye me-3"></i>Chi tiết danh mục: <?php echo htmlspecialchars($category->name ?? 'N/A', ENT_QUOTES, 'UTF-8'); ?>
            </h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="/webbanhang/admin/dashboard" class="text-decoration-none">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="/webbanhang/admin/categories" class="text-decoration-none">Danh mục</a></li>
                    <li class="breadcrumb-item active">Chi tiết</li>
                </ol>
            </nav>
        </div>
        
        <div class="btn-group">
            <a href="/webbanhang/admin/categories" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left me-2"></i>Quay lại
            </a>
            <a href="/webbanhang/admin/categories/edit/<?php echo $category->id; ?>" class="btn btn-primary">
                <i class="bi bi-pencil me-2"></i>Chỉnh sửa
            </a>
        </div>
    </div>

    <div class="row">
        <!-- Category Information -->
        <div class="col-lg-4">
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-info text-white">
                    <h5 class="mb-0">
                        <i class="bi bi-info-circle me-2"></i>Thông tin danh mục
                    </h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label fw-bold text-muted">Tên danh mục:</label>
                        <p class="fs-5 text-dark"><?php echo htmlspecialchars($category->name ?? 'N/A', ENT_QUOTES, 'UTF-8'); ?></p>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label fw-bold text-muted">Mô tả:</label>
                        <div class="text-dark">
                            <?php 
                            if (!empty($category->description)) {
                                // Render HTML instead of escaping it
                                echo $category->description;
                            } else {
                                echo '<em class="text-muted">Chưa có mô tả</em>';
                            }
                            ?>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label fw-bold text-muted">Tổng sản phẩm:</label>
                        <span class="badge bg-primary fs-6"><?php echo count($products ?? []); ?> sản phẩm</span>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label fw-bold text-muted">ID danh mục:</label>
                        <code class="bg-light px-2 py-1 rounded">#<?php echo $category->id; ?></code>
                    </div>
                </div>
            </div>
            
            <!-- Quick Actions -->
            <div class="card shadow-sm border-0">
                <div class="card-header bg-success text-white">
                    <h6 class="mb-0">
                        <i class="bi bi-lightning me-2"></i>Thao tác nhanh
                    </h6>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="/webbanhang/admin/products/create?category_id=<?php echo $category->id; ?>" class="btn btn-outline-success">
                            <i class="bi bi-plus-circle me-2"></i>Thêm sản phẩm vào danh mục
                        </a>
                        <a href="/webbanhang/admin/categories/edit/<?php echo $category->id; ?>" class="btn btn-outline-primary">
                            <i class="bi bi-pencil me-2"></i>Chỉnh sửa danh mục
                        </a>
                        <button type="button" class="btn btn-outline-danger" onclick="confirmDelete(<?php echo $category->id; ?>)">
                            <i class="bi bi-trash me-2"></i>Xóa danh mục
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Products in Category -->
        <div class="col-lg-8">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">
                        <i class="bi bi-box me-2"></i>Sản phẩm trong danh mục (<?php echo count($products ?? []); ?>)
                    </h5>
                </div>
                <div class="card-body">
                    <?php if (!empty($products)): ?>
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead class="table-light">
                                    <tr>
                                        <th style="width: 80px;">Ảnh</th>
                                        <th>Tên sản phẩm</th>
                                        <th style="width: 120px;">Giá</th>
                                        <th style="width: 100px;">Trạng thái</th>
                                        <th style="width: 120px;">Thao tác</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($products as $product): ?>
                                    <tr>
                                        <td>
                                            <?php if (!empty($product['image'])): ?>
                                                <img src="/webbanhang/public/uploads/products/<?php echo htmlspecialchars($product['image'], ENT_QUOTES, 'UTF-8'); ?>" 
                                                     alt="Product Image" 
                                                     class="img-thumbnail" 
                                                     style="width: 50px; height: 50px; object-fit: cover;">
                                            <?php else: ?>
                                                <div class="bg-light d-flex align-items-center justify-content-center" 
                                                     style="width: 50px; height: 50px; border-radius: 8px;">
                                                    <i class="bi bi-image text-muted"></i>
                                                </div>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <div>
                                                <strong><?php echo htmlspecialchars($product['name'] ?? 'N/A', ENT_QUOTES, 'UTF-8'); ?></strong>
                                                <?php if (!empty($product['description'])): ?>
                                                    <br><small class="text-muted">
                                                        <?php 
                                                        // Strip HTML tags for preview but preserve some formatting
                                                        $cleanDesc = strip_tags($product['description']);
                                                        echo htmlspecialchars(substr($cleanDesc, 0, 50), ENT_QUOTES, 'UTF-8'); 
                                                        echo strlen($cleanDesc) > 50 ? '...' : ''; 
                                                        ?>
                                                    </small>
                                                <?php endif; ?>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="fw-bold text-primary">
                                                <?php echo number_format($product['price'] ?? 0, 0, ',', '.'); ?> đ
                                            </span>
                                        </td>
                                        <td>
                                            <span class="badge bg-success">Đang bán</span>
                                        </td>
                                        <td>
                                            <div class="btn-group-vertical" role="group">
                                                <a href="/webbanhang/product/show/<?php echo $product['id']; ?>" 
                                                   class="btn btn-sm btn-outline-info mb-1" 
                                                   title="Xem sản phẩm">
                                                    <i class="bi bi-eye"></i>
                                                </a>
                                                <a href="/webbanhang/admin/products/edit/<?php echo $product['id']; ?>" 
                                                   class="btn btn-sm btn-outline-primary" 
                                                   title="Chỉnh sửa">
                                                    <i class="bi bi-pencil"></i>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php else: ?>
                        <div class="text-center py-5">
                            <i class="bi bi-inbox display-1 text-muted mb-3"></i>
                            <h4 class="text-muted">Chưa có sản phẩm nào</h4>
                            <p class="text-muted mb-4">Danh mục này chưa có sản phẩm nào. Hãy thêm sản phẩm để bắt đầu.</p>
                            <a href="/webbanhang/admin/products/create?category_id=<?php echo $category->id; ?>" class="btn btn-primary">
                                <i class="bi bi-plus-circle me-2"></i>Thêm sản phẩm đầu tiên
                            </a>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title">
                    <i class="bi bi-exclamation-triangle me-2"></i>Xác nhận xóa
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>Bạn có chắc chắn muốn xóa danh mục <strong>"<?php echo htmlspecialchars($category->name ?? '', ENT_QUOTES, 'UTF-8'); ?>"</strong> không?</p>
                <div class="alert alert-warning">
                    <i class="bi bi-info-circle me-2"></i>
                    <strong>Lưu ý:</strong> Các sản phẩm trong danh mục này sẽ được chuyển về "Không có danh mục".
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                <a href="#" id="confirmDeleteBtn" class="btn btn-danger">
                    <i class="bi bi-trash me-2"></i>Xóa danh mục
                </a>
            </div>
        </div>
    </div>
</div>

<script>
function confirmDelete(categoryId) {
    const modal = new bootstrap.Modal(document.getElementById('deleteModal'));
    document.getElementById('confirmDeleteBtn').href = '/webbanhang/admin/categories/delete/' + categoryId;
    modal.show();
}
</script>

<?php include_once 'app/views/shares/footer.php'; ?> 
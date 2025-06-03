<?php include_once 'app/views/shares/header.php'; ?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1 class="display-5 fw-bold"><i class="bi bi-box-seam me-2"></i>Quản lý sản phẩm</h1>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="/webbanhang/admin/dashboard">Dashboard</a></li>
                <li class="breadcrumb-item active">Sản phẩm</li>
            </ol>
        </nav>
    </div>
    <div class="d-flex align-items-center">
        <a href="/webbanhang/admin/products/create" class="btn btn-success btn-lg">
            <i class="bi bi-plus-circle me-2"></i>Thêm sản phẩm mới
        </a>
    </div>
</div>

<?php if (empty($products)): ?>
    <div class="alert alert-info shadow-sm d-flex align-items-center" role="alert">
        <i class="bi bi-info-circle-fill me-2 fs-4"></i>
        <div>
            Chưa có sản phẩm nào. Hãy thêm sản phẩm mới!
        </div>
    </div>
<?php else: ?>
    <div class="card shadow-sm">
        <div class="card-body">
            <div class="table-responsive">
                <table id="productsTable" class="table table-striped table-hover w-100">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Hình ảnh</th>
                            <th>Tên sản phẩm</th>
                            <th>Danh mục</th>
                            <th>Giá</th>
                            <th>Ngày tạo</th>
                            <th>Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($products as $product): ?>
                        <tr>
                            <td class="fw-bold">#<?php echo $product->id; ?></td>
                            <td class="text-center">
                                <?php if (!empty($product->image)): ?>
                                    <img src="/webbanhang/public/uploads/products/<?php echo htmlspecialchars($product->image, ENT_QUOTES, 'UTF-8'); ?>" 
                                         alt="<?php echo htmlspecialchars($product->name, ENT_QUOTES, 'UTF-8'); ?>"
                                         class="img-thumbnail product-thumbnail"
                                         loading="lazy"
                                         onerror="this.style.display='none'; this.nextElementSibling.style.display='inline-block';">
                                    <i class="bi bi-image text-secondary" style="font-size: 2rem; display: none;"></i>
                                <?php else: ?>
                                    <i class="bi bi-image text-secondary" style="font-size: 2rem;"></i>
                                    <div class="small text-muted">Chưa có ảnh</div>
                                <?php endif; ?>
                            </td>
                            <td>
                                <a href="/webbanhang/Product/show/<?php echo $product->id; ?>" class="fw-bold text-decoration-none">
                                    <?php echo htmlspecialchars($product->name, ENT_QUOTES, 'UTF-8'); ?>
                                </a>
                                <br>
                                <small class="text-muted">
                                    <?php 
                                        $plainText = strip_tags(html_entity_decode($product->description));
                                        echo strlen($plainText) > 80 ? substr($plainText, 0, 80) . '...' : $plainText;
                                    ?>
                                </small>
                            </td>
                            <td>
                                <?php if (!empty($product->category_name)): ?>
                                    <span class="badge bg-secondary">
                                        <?php echo htmlspecialchars($product->category_name, ENT_QUOTES, 'UTF-8'); ?>
                                    </span>
                                <?php else: ?>
                                    <span class="text-muted">Không có</span>
                                <?php endif; ?>
                            </td>
                            <td class="fw-bold text-end">
                                <?php echo number_format($product->price, 0, ',', '.'); ?> đ
                            </td>
                            <td>
                                <small class="text-muted">
                                    <?php echo date('d/m/Y H:i', strtotime($product->created_at ?? 'now')); ?>
                                </small>
                            </td>
                            <td>
                                <div class="btn-group" role="group">
                                    <a href="/webbanhang/Product/show/<?php echo $product->id; ?>" class="btn btn-sm btn-info me-1" title="Xem chi tiết">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    <a href="/webbanhang/admin/products/edit/<?php echo $product->id; ?>" class="btn btn-sm btn-primary me-1" title="Chỉnh sửa">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <a href="/webbanhang/admin/products/delete/<?php echo $product->id; ?>" 
                                       onclick="return confirm('Bạn có chắc chắn muốn xóa sản phẩm này?');" 
                                       class="btn btn-sm btn-danger" title="Xóa">
                                        <i class="bi bi-trash"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
<?php endif; ?>

<!-- DataTables CSS and JS -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css">
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap5.min.js"></script>

<style>
.product-thumbnail {
    width: 80px;
    height: 80px;
    object-fit: cover;
    border-radius: 8px;
    transition: transform 0.2s ease;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
}

.product-thumbnail:hover {
    transform: scale(1.1);
    box-shadow: 0 4px 16px rgba(0,0,0,0.2);
}

.btn-group .btn {
    border-radius: 4px !important;
    margin-right: 2px;
}

.table td {
    vertical-align: middle;
}

/* Enhanced image fallback styling */
.text-center i.bi-image {
    color: #6c757d;
    background: #f8f9fa;
    padding: 20px;
    border-radius: 8px;
    border: 2px dashed #dee2e6;
}

/* Badge styling improvements */
.badge {
    font-size: 0.85rem;
    padding: 6px 12px;
    border-radius: 6px;
}

/* Price formatting */
.text-end {
    font-weight: 600;
    color: #198754;
}

/* Description text styling */
.text-muted {
    line-height: 1.4;
}
</style>

<script>
$(document).ready(function() {
    $('#productsTable').DataTable({
        language: {
            "search": "Tìm kiếm:",
            "lengthMenu": "Hiển thị _MENU_ sản phẩm trên trang",
            "zeroRecords": "Không tìm thấy sản phẩm nào phù hợp",
            "info": "Hiển thị trang _PAGE_ trên _PAGES_",
            "infoEmpty": "Không tìm thấy sản phẩm nào",
            "infoFiltered": "(lọc từ _MAX_ sản phẩm)",
            "paginate": {
                "first": "Đầu",
                "last": "Cuối",
                "next": "Tiếp",
                "previous": "Trước"
            }
        },
        responsive: true,
        columnDefs: [
            { orderable: false, targets: [1, 6] }, // Disable sorting for image and action columns
            { width: "5%", targets: 0 },
            { width: "10%", targets: 1 },
            { width: "30%", targets: 2 },
            { width: "15%", targets: 3 },
            { width: "15%", targets: 4 },
            { width: "10%", targets: 5 },
            { width: "15%", targets: 6 }
        ],
        order: [[0, 'desc']] // Sort by ID desc (newest first)
    });
});
</script>

<?php include_once 'app/views/shares/footer.php'; ?> 
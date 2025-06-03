<?php include_once 'app/views/shares/header.php'; ?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1 class="display-5 fw-bold"><i class="bi bi-tags me-2"></i>Quản lý danh mục</h1>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="/webbanhang/admin/dashboard">Dashboard</a></li>
                <li class="breadcrumb-item active">Danh mục</li>
            </ol>
        </nav>
    </div>
    <div class="d-flex align-items-center">
        <a href="/webbanhang/admin/categories/create" class="btn btn-success btn-lg">
            <i class="bi bi-plus-circle me-2"></i>Thêm danh mục mới
        </a>
    </div>
</div>

<?php if (empty($categories)): ?>
    <div class="alert alert-info shadow-sm d-flex align-items-center" role="alert">
        <i class="bi bi-info-circle-fill me-2 fs-4"></i>
        <div>
            Chưa có danh mục nào. Hãy thêm danh mục mới!
        </div>
    </div>
<?php else: ?>
    <div class="card shadow-sm">
        <div class="card-body">
            <div class="table-responsive">
                <table id="categoriesTable" class="table table-striped table-hover w-100">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Tên danh mục</th>
                            <th>Mô tả</th>
                            <th>Số sản phẩm</th>
                            <th>Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($categories as $category): ?>
                        <tr>
                            <td class="fw-bold">#<?php echo $category->id; ?></td>
                            <td>
                                <a href="/webbanhang/admin/categories/show/<?php echo $category->id; ?>" class="fw-bold text-decoration-none">
                                    <?php echo htmlspecialchars($category->name, ENT_QUOTES, 'UTF-8'); ?>
                                </a>
                            </td>
                            <td>
                                <small class="text-muted">
                                    <?php 
                                        $description = htmlspecialchars($category->description ?? '', ENT_QUOTES, 'UTF-8');
                                        echo strlen($description) > 100 ? substr($description, 0, 100) . '...' : $description;
                                    ?>
                                </small>
                            </td>
                            <td>
                                <span class="badge bg-primary">
                                    <?php echo $category->product_count ?? 0; ?> sản phẩm
                                </span>
                            </td>
                            <td>
                                <div class="btn-group" role="group">
                                    <a href="/webbanhang/admin/categories/show/<?php echo $category->id; ?>" class="btn btn-sm btn-info me-1" title="Xem chi tiết">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    <a href="/webbanhang/admin/categories/edit/<?php echo $category->id; ?>" class="btn btn-sm btn-primary me-1" title="Chỉnh sửa">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <a href="/webbanhang/admin/categories/delete/<?php echo $category->id; ?>" 
                                       onclick="return confirm('Bạn có chắc chắn muốn xóa danh mục này? Các sản phẩm trong danh mục sẽ được chuyển về không có danh mục.');" 
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
.btn-group .btn {
    border-radius: 4px !important;
    margin-right: 2px;
}
</style>

<script>
$(document).ready(function() {
    $('#categoriesTable').DataTable({
        language: {
            "search": "Tìm kiếm:",
            "lengthMenu": "Hiển thị _MENU_ danh mục trên trang",
            "zeroRecords": "Không tìm thấy danh mục nào phù hợp",
            "info": "Hiển thị trang _PAGE_ trên _PAGES_",
            "infoEmpty": "Không tìm thấy danh mục nào",
            "infoFiltered": "(lọc từ _MAX_ danh mục)",
            "paginate": {
                "first": "Đầu",
                "last": "Cuối",
                "next": "Tiếp",
                "previous": "Trước"
            }
        },
        responsive: true,
        columnDefs: [
            { orderable: false, targets: [4] }, // Disable sorting for action columns
            { width: "10%", targets: 0 },
            { width: "30%", targets: 1 },
            { width: "40%", targets: 2 },
            { width: "15%", targets: 3 },
            { width: "15%", targets: 4 }
        ],
        order: [[0, 'desc']] // Sort by ID desc (newest first)
    });
});
</script>

<?php include_once 'app/views/shares/footer.php'; ?> 
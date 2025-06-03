<?php include_once 'app/views/shares/header.php'; ?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1 class="display-5 fw-bold"><i class="bi bi-ticket-perforated me-2"></i>Quản lý voucher</h1>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="/webbanhang/admin/dashboard">Dashboard</a></li>
                <li class="breadcrumb-item active">Voucher</li>
            </ol>
        </nav>
    </div>
    <div class="d-flex align-items-center">
        <a href="/webbanhang/admin/vouchers/create" class="btn btn-success btn-lg">
            <i class="bi bi-plus-circle me-2"></i>Tạo voucher mới
        </a>
    </div>
</div>

<?php if (empty($vouchers)): ?>
    <div class="alert alert-info shadow-sm d-flex align-items-center" role="alert">
        <i class="bi bi-info-circle-fill me-2 fs-4"></i>
        <div>
            Chưa có voucher nào. Hãy tạo voucher mới!
        </div>
    </div>
<?php else: ?>
    <div class="card shadow-sm">
        <div class="card-body">
            <div class="table-responsive">
                <table id="vouchersTable" class="table table-striped table-hover w-100">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Mã voucher</th>
                            <th>Tên voucher</th>
                            <th>Loại giảm giá</th>
                            <th>Giá trị</th>
                            <th>Đơn tối thiểu</th>
                            <th>Thời gian</th>
                            <th>Trạng thái</th>
                            <th>Sử dụng</th>
                            <th>Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($vouchers as $voucher): ?>
                        <tr>
                            <td class="fw-bold">#<?php echo $voucher->id; ?></td>
                            <td>
                                <code class="bg-primary text-white px-2 py-1 rounded">
                                    <?php echo htmlspecialchars($voucher->code, ENT_QUOTES, 'UTF-8'); ?>
                                </code>
                            </td>
                            <td>
                                <strong><?php echo htmlspecialchars($voucher->name, ENT_QUOTES, 'UTF-8'); ?></strong>
                                <br>
                                <small class="text-muted">
                                    <?php 
                                        $description = htmlspecialchars($voucher->description ?? '', ENT_QUOTES, 'UTF-8');
                                        echo strlen($description) > 50 ? substr($description, 0, 50) . '...' : $description;
                                    ?>
                                </small>
                            </td>
                            <td>
                                <?php if ($voucher->discount_type == 'percentage'): ?>
                                    <span class="badge bg-info">Phần trăm</span>
                                <?php else: ?>
                                    <span class="badge bg-warning">Cố định</span>
                                <?php endif; ?>
                            </td>
                            <td class="fw-bold">
                                <?php if ($voucher->discount_type == 'percentage'): ?>
                                    <?php echo $voucher->discount_value; ?>%
                                <?php else: ?>
                                    <?php echo number_format($voucher->discount_value, 0, ',', '.'); ?> đ
                                <?php endif; ?>
                                <?php if ($voucher->max_discount_amount): ?>
                                    <br><small class="text-muted">Tối đa: <?php echo number_format($voucher->max_discount_amount, 0, ',', '.'); ?> đ</small>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php echo number_format($voucher->min_order_amount, 0, ',', '.'); ?> đ
                            </td>
                            <td>
                                <small>
                                    <strong>Từ:</strong> <?php echo date('d/m/Y H:i', strtotime($voucher->start_date)); ?><br>
                                    <strong>Đến:</strong> <?php echo date('d/m/Y H:i', strtotime($voucher->end_date)); ?>
                                </small>
                            </td>
                            <td>
                                <?php 
                                $now = date('Y-m-d H:i:s');
                                if (!$voucher->is_active): ?>
                                    <span class="badge bg-danger">Tắt</span>
                                <?php elseif ($now < $voucher->start_date): ?>
                                    <span class="badge bg-warning">Chưa bắt đầu</span>
                                <?php elseif ($now > $voucher->end_date): ?>
                                    <span class="badge bg-secondary">Hết hạn</span>
                                <?php else: ?>
                                    <span class="badge bg-success">Hoạt động</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <span class="text-muted">
                                    <?php echo $voucher->used_count; ?>
                                    <?php if ($voucher->usage_limit): ?>
                                        / <?php echo $voucher->usage_limit; ?>
                                    <?php else: ?>
                                        / ∞
                                    <?php endif; ?>
                                </span>
                            </td>
                            <td>
                                <div class="btn-group" role="group">
                                    <a href="/webbanhang/admin/vouchers/edit/<?php echo $voucher->id; ?>" class="btn btn-sm btn-primary me-1" title="Chỉnh sửa">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <a href="/webbanhang/admin/vouchers/delete/<?php echo $voucher->id; ?>" 
                                       onclick="return confirm('Bạn có chắc chắn muốn xóa voucher này?');" 
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
    $('#vouchersTable').DataTable({
        language: {
            "search": "Tìm kiếm:",
            "lengthMenu": "Hiển thị _MENU_ voucher trên trang",
            "zeroRecords": "Không tìm thấy voucher nào",
            "info": "Hiển thị trang _PAGE_ trên _PAGES_",
            "infoEmpty": "Không tìm thấy voucher nào",
            "infoFiltered": "(lọc từ _MAX_ voucher)",
            "paginate": {
                "first": "Đầu",
                "last": "Cuối",
                "next": "Tiếp",
                "previous": "Trước"
            }
        },
        responsive: true,
        columnDefs: [
            { orderable: false, targets: [9] }, // Disable sorting for action column
            { width: "5%", targets: 0 },
            { width: "10%", targets: 1 },
            { width: "15%", targets: 2 },
            { width: "8%", targets: 3 },
            { width: "12%", targets: 4 },
            { width: "10%", targets: 5 },
            { width: "15%", targets: 6 },
            { width: "8%", targets: 7 },
            { width: "8%", targets: 8 },
            { width: "9%", targets: 9 }
        ],
        order: [[0, 'desc']] // Sort by ID desc (newest first)
    });
});
</script>

<?php include_once 'app/views/shares/footer.php'; ?> 
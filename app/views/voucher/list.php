<?php
// filepath: app/views/voucher/list.php
include 'app/views/shares/header.php'; 
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="display-5 fw-bold"><i class="bi bi-ticket-perforated me-2"></i>Quản lý Voucher</h1>
    <a href="/webbanhang/Voucher/add" class="btn btn-success btn-lg">
        <i class="bi bi-plus-circle me-2"></i>Thêm voucher mới
    </a>
</div>

<?php if (empty($vouchers)): ?>
    <div class="alert alert-info shadow-sm d-flex align-items-center" role="alert">
        <i class="bi bi-info-circle-fill me-2 fs-4"></i>
        <div>Chưa có voucher nào. Hãy thêm voucher mới!</div>
    </div>
<?php else: ?>
    <div class="card shadow-sm">
        <div class="card-body">
            <div class="table-responsive">
                <table id="vouchersTable" class="table table-striped table-hover">
                    <thead>
                        <tr>
                            <th>Mã Voucher</th>
                            <th>Tên</th>
                            <th>Loại giảm giá</th>
                            <th>Giá trị</th>
                            <th>Áp dụng cho</th>
                            <th>Thời gian</th>
                            <th>Trạng thái</th>
                            <th>Sử dụng</th>
                            <th>Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($vouchers as $voucher): ?>
                        <tr>
                            <td>
                                <code class="bg-primary text-white px-2 py-1 rounded">
                                    <?php echo htmlspecialchars($voucher->code, ENT_QUOTES, 'UTF-8'); ?>
                                </code>
                            </td>
                            <td><?php echo htmlspecialchars($voucher->name, ENT_QUOTES, 'UTF-8'); ?></td>
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
                            </td>
                            <td>
                                <?php if ($voucher->applies_to == 'all_products'): ?>
                                    <span class="badge bg-success">Tất cả sản phẩm</span>
                                <?php else: ?>
                                    <span class="badge bg-secondary">Sản phẩm cụ thể</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <small>
                                    <strong>Từ:</strong> <?php echo date('d/m/Y', strtotime($voucher->start_date)); ?><br>
                                    <strong>Đến:</strong> <?php echo date('d/m/Y', strtotime($voucher->end_date)); ?>
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
                                    <?php endif; ?>
                                </span>
                            </td>
                            <td>
                                <div class="btn-group" role="group">
                                    <a href="/webbanhang/Voucher/edit/<?php echo $voucher->id; ?>" 
                                       class="btn btn-sm btn-primary">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <a href="/webbanhang/Voucher/delete/<?php echo $voucher->id; ?>" 
                                       onclick="return confirm('Bạn có chắc chắn muốn xóa voucher này?');" 
                                       class="btn btn-sm btn-danger">
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

<!-- DataTables -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css">
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap5.min.js"></script>

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
            { orderable: false, targets: [8] }
        ],
        order: [[5, 'desc']]
    });
});
</script>

<?php include 'app/views/shares/footer.php'; ?>
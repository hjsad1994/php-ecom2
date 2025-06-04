<?php include_once 'app/views/shares/header.php'; ?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1 class="display-5 fw-bold">
            <i class="bi bi-ticket-perforated me-2 text-primary"></i>Quản lý voucher
        </h1>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="/webbanhang/admin/dashboard" class="text-decoration-none">Dashboard</a></li>
                <li class="breadcrumb-item active">Voucher</li>
            </ol>
        </nav>
    </div>
    <div class="d-flex align-items-center gap-2">
        <a href="/webbanhang/admin/vouchers/create" class="btn btn-success btn-lg">
            <i class="bi bi-plus-circle me-2"></i>Tạo voucher mới
        </a>
    </div>
</div>

<!-- Statistics Dashboard -->
<div class="row mb-5">
    <div class="col-lg-3 col-md-6 mb-4">
        <div class="card border-0 shadow-sm h-100 bg-gradient stats-green">
            <div class="card-body text-white">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h2 class="fw-bold mb-0"><?php echo number_format($stats['voucher_stats']->total_vouchers ?? 0); ?></h2>
                        <p class="mb-0 opacity-75">Tổng voucher</p>
                    </div>
                    <i class="bi bi-ticket-perforated fs-1 opacity-75"></i>
                </div>
            </div>
            <div class="card-footer bg-transparent border-0">
                <a href="/webbanhang/admin/vouchers/create" class="text-white text-decoration-none">
                    <small><i class="bi bi-plus-circle me-1"></i>Tạo voucher mới</small>
                </a>
            </div>
        </div>
    </div>
    
    <div class="col-lg-3 col-md-6 mb-4">
        <div class="card border-0 shadow-sm h-100 bg-gradient stats-orange">
            <div class="card-body text-white">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h2 class="fw-bold mb-0"><?php echo number_format($stats['voucher_stats']->active_vouchers ?? 0); ?></h2>
                        <p class="mb-0 opacity-75">Đang hoạt động</p>
                    </div>
                    <i class="bi bi-check-circle fs-1 opacity-75"></i>
                </div>
            </div>
            <div class="card-footer bg-transparent border-0">
                <a href="#activeVouchers" class="text-white text-decoration-none">
                    <small><i class="bi bi-eye me-1"></i>Xem chi tiết</small>
                </a>
            </div>
        </div>
    </div>
    
    <div class="col-lg-3 col-md-6 mb-4">
        <div class="card border-0 shadow-sm h-100 bg-gradient stats-purple">
            <div class="card-body text-white">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h2 class="fw-bold mb-0"><?php echo number_format($stats['voucher_stats']->total_usage ?? 0); ?></h2>
                        <p class="mb-0 opacity-75">Lượt sử dụng</p>
                    </div>
                    <i class="bi bi-graph-up fs-1 opacity-75"></i>
                </div>
            </div>
            <div class="card-footer bg-transparent border-0">
                <a href="#usageStats" class="text-white text-decoration-none">
                    <small><i class="bi bi-bar-chart me-1"></i>Xem thống kê</small>
                </a>
            </div>
        </div>
    </div>
    
    <div class="col-lg-3 col-md-6 mb-4">
        <div class="card border-0 shadow-sm h-100 bg-gradient stats-red">
            <div class="card-body text-white">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h2 class="fw-bold mb-0"><?php echo number_format($stats['voucher_stats']->expired_vouchers ?? 0); ?></h2>
                        <p class="mb-0 opacity-75">Đã hết hạn</p>
                    </div>
                    <i class="bi bi-clock-history fs-1 opacity-75"></i>
                </div>
            </div>
            <div class="card-footer bg-transparent border-0">
                <a href="#expiredVouchers" class="text-white text-decoration-none">
                    <small><i class="bi bi-trash me-1"></i>Dọn dẹp</small>
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Discount Type Statistics -->
<?php if (!empty($stats['discount_types'])): ?>
<div class="row mb-4" id="usageStats">
    <div class="col-12">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-light">
                <h5 class="mb-0"><i class="bi bi-pie-chart me-2"></i>Thống kê theo loại giảm giá</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <?php foreach ($stats['discount_types'] as $type): ?>
                        <div class="col-md-6 text-center">
                            <div class="border-end">
                                <h4 class="<?php echo $type->discount_type == 'percentage' ? 'text-info' : 'text-warning'; ?> mb-1">
                                    <?php echo $type->count; ?>
                                </h4>
                                <small class="text-muted">
                                    <?php echo $type->discount_type == 'percentage' ? 'Phần trăm' : 'Cố định'; ?>
                                    <br>
                                    <span class="badge bg-secondary">
                                        <?php echo number_format($type->total_usage); ?> lượt dùng
                                    </span>
                                </small>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>
</div>
<?php endif; ?>

<!-- Top Used and Expiring Soon -->
<div class="row mb-4">
    <div class="col-md-8">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-light d-flex justify-content-between align-items-center">
                <h5 class="mb-0"><i class="bi bi-star me-2"></i>Voucher được dùng nhiều nhất</h5>
                <span class="badge bg-primary"><?php echo count($stats['top_used']); ?> voucher</span>
            </div>
            <div class="card-body">
                <?php if (!empty($stats['top_used'])): ?>
                    <div class="row g-3">
                        <?php foreach ($stats['top_used'] as $voucher): ?>
                            <div class="col-md-6">
                                <div class="d-flex justify-content-between align-items-center p-3 bg-light rounded">
                                    <div class="d-flex align-items-center">
                                        <div class="bg-success bg-gradient rounded-circle me-3" style="width: 16px; height: 16px;"></div>
                                        <div>
                                            <div class="fw-bold text-dark">
                                                <code class="bg-primary text-white px-2 py-1 rounded small"><?php echo $voucher->code; ?></code>
                                            </div>
                                            <div class="small text-muted">
                                                <?php echo htmlspecialchars(substr($voucher->name, 0, 25), ENT_QUOTES, 'UTF-8'); ?>
                                                <?php echo strlen($voucher->name) > 25 ? '...' : ''; ?>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="text-end">
                                        <span class="badge bg-success fs-6"><?php echo $voucher->used_count; ?></span>
                                        <div class="small text-muted">lượt dùng</div>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php else: ?>
                    <div class="text-center text-muted py-4">
                        <i class="bi bi-star fs-1 mb-3"></i>
                        <p>Chưa có voucher nào được sử dụng</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
    
    <div class="col-md-4">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-light">
                <h5 class="mb-0"><i class="bi bi-exclamation-triangle me-2"></i>Sắp hết hạn</h5>
            </div>
            <div class="card-body">
                <?php if (!empty($stats['expiring_soon'])): ?>
                    <?php foreach ($stats['expiring_soon'] as $voucher): ?>
                        <div class="d-flex align-items-center py-2 border-bottom">
                            <div class="me-3">
                                <div class="bg-warning bg-gradient rounded d-flex align-items-center justify-content-center" 
                                     style="width: 40px; height: 40px;">
                                    <i class="bi bi-clock text-white"></i>
                                </div>
                            </div>
                            <div class="flex-grow-1">
                                <div class="fw-bold small">
                                    <code class="bg-warning text-dark px-1 rounded small"><?php echo $voucher->code; ?></code>
                                </div>
                                <div class="text-danger small">
                                    Hết hạn: <?php echo date('d/m/Y', strtotime($voucher->end_date)); ?>
                                </div>
                                <div class="text-muted small">
                                    Đã dùng: <?php echo $voucher->used_count; ?>/<?php echo $voucher->usage_limit ?: '∞'; ?>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                    <div class="text-center mt-3">
                        <a href="#vouchersTable" class="btn btn-outline-warning btn-sm">
                            <i class="bi bi-eye me-1"></i>Xem tất cả
                        </a>
                    </div>
                <?php else: ?>
                    <div class="text-center text-muted py-4">
                        <i class="bi bi-check-circle fs-1 mb-3"></i>
                        <p>Không có voucher nào sắp hết hạn</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<!-- Search and Filter -->
<div class="row mb-4">
    <div class="col-12">
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col-md-6">
                        <div class="input-group">
                            <span class="input-group-text"><i class="bi bi-search"></i></span>
                            <input type="text" class="form-control" id="searchInput" placeholder="Tìm kiếm voucher...">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <select class="form-select" id="statusFilter">
                            <option value="">Tất cả trạng thái</option>
                            <option value="active">Đang hoạt động</option>
                            <option value="expired">Đã hết hạn</option>
                            <option value="inactive">Đã tắt</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <button class="btn btn-outline-secondary w-100" id="refreshBtn">
                            <i class="bi bi-arrow-clockwise me-1"></i>Làm mới
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Vouchers Table -->
<?php if (empty($vouchers)): ?>
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="text-center py-5">
                <i class="bi bi-ticket-perforated display-1 text-muted mb-4"></i>
                <h3 class="text-muted mb-3">Chưa có voucher nào</h3>
                <p class="text-muted mb-4">Hãy tạo voucher đầu tiên để bắt đầu chương trình khuyến mãi!</p>
                <div class="d-grid gap-2 d-md-block">
                    <a href="/webbanhang/admin/vouchers/create" class="btn btn-success btn-lg">
                        <i class="bi bi-plus-circle me-2"></i>Tạo voucher đầu tiên
                    </a>
                </div>
            </div>
        </div>
    </div>
<?php else: ?>
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-light d-flex justify-content-between align-items-center">
            <h5 class="mb-0"><i class="bi bi-table me-2"></i>Danh sách voucher</h5>
            <span class="badge bg-primary fs-6"><?php echo count($vouchers); ?> voucher</span>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table id="vouchersTable" class="table table-hover w-100">
                    <thead class="table-light">
                        <tr>
                            <th width="5%">ID</th>
                            <th width="10%">Mã voucher</th>
                            <th width="20%">Tên voucher</th>
                            <th width="10%">Loại</th>
                            <th width="12%">Giá trị</th>
                            <th width="10%">Đơn tối thiểu</th>
                            <th width="15%">Thời gian</th>
                            <th width="8%">Trạng thái</th>
                            <th width="8%">Sử dụng</th>
                            <th width="12%">Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($vouchers as $voucher): ?>
                        <tr>
                            <td class="fw-bold text-primary">#<?php echo str_pad($voucher->id, 3, '0', STR_PAD_LEFT); ?></td>
                            <td>
                                <code class="bg-primary text-white px-2 py-1 rounded">
                                    <?php echo htmlspecialchars($voucher->code, ENT_QUOTES, 'UTF-8'); ?>
                                </code>
                            </td>
                            <td>
                                <div class="fw-bold mb-1">
                                    <?php echo htmlspecialchars($voucher->name, ENT_QUOTES, 'UTF-8'); ?>
                                </div>
                                <div class="text-muted small">
                                    <?php 
                                        $description = htmlspecialchars($voucher->description ?? '', ENT_QUOTES, 'UTF-8');
                                        echo strlen($description) > 50 ? substr($description, 0, 50) . '...' : $description;
                                    ?>
                                </div>
                            </td>
                            <td>
                                <?php if ($voucher->discount_type == 'percentage'): ?>
                                    <span class="badge bg-info bg-gradient">
                                        <i class="bi bi-percent me-1"></i>Phần trăm
                                    </span>
                                <?php else: ?>
                                    <span class="badge bg-warning bg-gradient">
                                        <i class="bi bi-currency-dollar me-1"></i>Cố định
                                    </span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <div class="fw-bold text-success">
                                    <?php if ($voucher->discount_type == 'percentage'): ?>
                                        <?php echo $voucher->discount_value; ?>%
                                    <?php else: ?>
                                        <?php echo number_format($voucher->discount_value, 0, ',', '.'); ?> đ
                                    <?php endif; ?>
                                </div>
                                <?php if ($voucher->max_discount_amount): ?>
                                    <div class="small text-muted">
                                        Tối đa: <?php echo number_format($voucher->max_discount_amount, 0, ',', '.'); ?> đ
                                    </div>
                                <?php endif; ?>
                            </td>
                            <td>
                                <div class="small text-muted">
                                    <?php echo number_format($voucher->min_order_amount, 0, ',', '.'); ?> đ
                                </div>
                            </td>
                            <td>
                                <div class="small text-muted">
                                    <strong>Từ:</strong> <?php echo date('d/m/Y', strtotime($voucher->start_date)); ?><br>
                                    <strong>Đến:</strong> <?php echo date('d/m/Y', strtotime($voucher->end_date)); ?>
                                </div>
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
                                <div class="text-center">
                                    <span class="fw-bold"><?php echo $voucher->used_count; ?></span>
                                    <div class="small text-muted">
                                        / <?php echo $voucher->usage_limit ?: '∞'; ?>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div class="btn-group" role="group">
                                    <a href="/webbanhang/admin/vouchers/edit/<?php echo $voucher->id; ?>" 
                                       class="btn btn-sm btn-outline-primary" 
                                       title="Chỉnh sửa">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <a href="/webbanhang/admin/vouchers/delete/<?php echo $voucher->id; ?>" 
                                       onclick="return confirm('Bạn có chắc chắn muốn xóa voucher này?\n\nMã: <?php echo htmlspecialchars($voucher->code, ENT_QUOTES, 'UTF-8'); ?>');" 
                                       class="btn btn-sm btn-outline-danger" 
                                       title="Xóa">
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
/* Force override statistics cards colors */
.stats-green {
    background: linear-gradient(135deg, #28a745 0%, #20c997 100%) !important;
}

.stats-orange {
    background: linear-gradient(135deg, #fd7e14 0%, #ffc107 100%) !important;
}

.stats-purple {
    background: linear-gradient(135deg, #6f42c1 0%, #e83e8c 100%) !important;
}

.stats-red {
    background: linear-gradient(135deg, #dc3545 0%, #fd7e14 100%) !important;
}

/* Remove conflicting CSS */
.bg-gradient.stats-green,
.bg-gradient.stats-orange,
.bg-gradient.stats-purple,
.bg-gradient.stats-red {
    background-size: 100% 100% !important;
    animation: none !important;
}

.card {
    border-radius: 12px;
    transition: transform 0.2s ease, box-shadow 0.2s ease;
    box-shadow: 0 4px 15px rgba(0,0,0,0.1) !important;
}

.card:hover {
    transform: translateY(-3px);
    box-shadow: 0 8px 25px rgba(0,0,0,0.15) !important;
}

/* Statistics Cards Enhancement */
.card.bg-primary,
.card.bg-success,
.card.bg-warning,
.card.bg-info {
    border: none;
    box-shadow: 0 4px 20px rgba(0,0,0,0.2) !important;
}

.card.bg-primary:hover,
.card.bg-success:hover,
.card.bg-warning:hover,
.card.bg-info:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 30px rgba(0,0,0,0.25) !important;
}

.btn-group .btn {
    border-radius: 6px !important;
    margin-right: 2px;
}

.btn-group .btn:last-child {
    margin-right: 0;
}

.table td {
    vertical-align: middle;
    color: #212529 !important;
}

.table th {
    border-top: none;
    font-weight: 600;
    color: #495057 !important;
}

.table .text-dark {
    color: #212529 !important;
}

.table .text-muted {
    color: #6c757d !important;
}

.table .text-success {
    color: #198754 !important;
    font-weight: 600;
}

.table .text-primary {
    color: #0d6efd !important;
}

.badge {
    font-size: 0.85rem;
    padding: 8px 12px;
    border-radius: 8px;
}

.border-bottom:last-child {
    border-bottom: none !important;
}

/* Custom scrollbar for table */
.table-responsive::-webkit-scrollbar {
    height: 8px;
}

.table-responsive::-webkit-scrollbar-track {
    background: #f1f1f1;
    border-radius: 4px;
}

.table-responsive::-webkit-scrollbar-thumb {
    background: #c1c1c1;
    border-radius: 4px;
}

.table-responsive::-webkit-scrollbar-thumb:hover {
    background: #a8a8a8;
}

/* Animation for statistics cards */
@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.card {
    animation: fadeInUp 0.6s ease-out;
}

/* Responsive improvements */
@media (max-width: 768px) {
    .btn-group .btn {
        padding: 0.25rem 0.5rem;
    }
    
    .card-body {
        padding: 1rem;
    }
}
</style>

<script>
$(document).ready(function() {
    // Initialize DataTable
    var table = $('#vouchersTable').DataTable({
        language: {
            "search": "Tìm kiếm:",
            "lengthMenu": "Hiển thị _MENU_ voucher",
            "zeroRecords": "Không tìm thấy voucher nào phù hợp",
            "info": "Hiển thị _START_ đến _END_ trong tổng số _TOTAL_ voucher",
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
            { orderable: false, targets: [9] },
            { className: "text-center", targets: [0, 8, 9] }
        ],
        order: [[0, 'desc']],
        pageLength: 10,
        lengthMenu: [[10, 25, 50, -1], [10, 25, 50, "Tất cả"]]
    });

    // Custom search
    $('#searchInput').on('keyup', function() {
        table.search(this.value).draw();
    });

    // Status filter
    $('#statusFilter').on('change', function() {
        var selectedStatus = this.value;
        
        if (selectedStatus === '') {
            table.column(7).search('').draw();
        } else {
            var statusText = '';
            switch(selectedStatus) {
                case 'active':
                    statusText = 'Hoạt động';
                    break;
                case 'expired':
                    statusText = 'Hết hạn';
                    break;
                case 'inactive':
                    statusText = 'Tắt';
                    break;
            }
            table.column(7).search(statusText, true, false).draw();
        }
    });

    // Refresh button
    $('#refreshBtn').on('click', function() {
        $('#searchInput').val('');
        $('#statusFilter').val('');
        table.search('').columns().search('').draw();
        
        // Add refresh animation
        $(this).find('i').addClass('fa-spin');
        setTimeout(() => {
            $(this).find('i').removeClass('fa-spin');
        }, 1000);
    });

    // Success message from URL parameter
    const urlParams = new URLSearchParams(window.location.search);
    if (urlParams.get('success') === 'created') {
        showToast('Tạo voucher thành công!', 'success');
        window.history.replaceState({}, document.title, window.location.pathname);
    }

    // Tooltip for action buttons
    $('[title]').tooltip();
});

// Toast notification function
function showToast(message, type = 'info') {
    const toastHtml = `
        <div class="toast align-items-center text-white bg-${type} border-0" role="alert">
            <div class="d-flex">
                <div class="toast-body">${message}</div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
            </div>
        </div>
    `;
    
    // Create toast container if it doesn't exist
    if (!document.getElementById('toast-container')) {
        const container = document.createElement('div');
        container.id = 'toast-container';
        container.className = 'toast-container position-fixed top-0 end-0 p-3';
        container.style.zIndex = '1056';
        document.body.appendChild(container);
    }
    
    const container = document.getElementById('toast-container');
    container.insertAdjacentHTML('beforeend', toastHtml);
    
    const toastElement = container.lastElementChild;
    const toast = new bootstrap.Toast(toastElement);
    toast.show();
    
    // Remove toast element after it's hidden
    toastElement.addEventListener('hidden.bs.toast', function() {
        this.remove();
    });
}
</script>

<?php include_once 'app/views/shares/footer.php'; ?> 
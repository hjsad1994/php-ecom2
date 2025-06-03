<?php include_once 'app/views/shares/header.php'; ?>

<!-- Error Display -->
<?php if (!empty($errors)): ?>
<div class="alert alert-danger alert-dismissible fade show" role="alert">
    <strong><i class="bi bi-exclamation-triangle me-2"></i>Có lỗi xảy ra:</strong>
    <ul class="mb-0 mt-2">
        <?php foreach ($errors as $error): ?>
            <li><?php echo htmlspecialchars($error, ENT_QUOTES, 'UTF-8'); ?></li>
        <?php endforeach; ?>
    </ul>
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
<?php endif; ?>

<div class="mb-4">
    <h1 class="display-5 fw-bold text-primary mb-2">
        <i class="bi bi-speedometer2 me-3"></i>Admin Dashboard
    </h1>
    <p class="lead text-muted">Tổng quan hệ thống quản lý website bán hàng</p>
</div>

<!-- Statistics Cards -->
<div class="row mb-5">
    <div class="col-lg-3 col-md-6 mb-4">
        <div class="card border-0 shadow-sm h-100 bg-gradient stats-green">
            <div class="card-body text-white text-center">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h2 class="fw-bold mb-0"><?php echo $stats['products'] ?? 0; ?></h2>
                        <p class="mb-0 opacity-75">Sản phẩm</p>
                    </div>
                    <i class="bi bi-box-seam fs-1 opacity-75"></i>
                </div>
            </div>
            <div class="card-footer bg-transparent border-0">
                <a href="/webbanhang/admin/products" class="text-white text-decoration-none">
                    <small><i class="bi bi-arrow-right me-1"></i>Quản lý sản phẩm</small>
                </a>
            </div>
        </div>
    </div>
    
    <div class="col-lg-3 col-md-6 mb-4">
        <div class="card border-0 shadow-sm h-100 bg-gradient stats-orange">
            <div class="card-body text-white text-center">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h2 class="fw-bold mb-0"><?php echo $stats['categories'] ?? 0; ?></h2>
                        <p class="mb-0 opacity-75">Danh mục</p>
                    </div>
                    <i class="bi bi-tags fs-1 opacity-75"></i>
                </div>
            </div>
            <div class="card-footer bg-transparent border-0">
                <a href="/webbanhang/admin/categories" class="text-white text-decoration-none">
                    <small><i class="bi bi-arrow-right me-1"></i>Quản lý danh mục</small>
                </a>
            </div>
        </div>
    </div>
    
    <div class="col-lg-3 col-md-6 mb-4">
        <div class="card border-0 shadow-sm h-100 bg-gradient stats-purple">
            <div class="card-body text-white text-center">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h2 class="fw-bold mb-0"><?php echo $stats['vouchers'] ?? 0; ?></h2>
                        <p class="mb-0 opacity-75">Voucher</p>
                    </div>
                    <i class="bi bi-ticket-perforated fs-1 opacity-75"></i>
                </div>
            </div>
            <div class="card-footer bg-transparent border-0">
                <a href="/webbanhang/admin/vouchers" class="text-white text-decoration-none">
                    <small><i class="bi bi-arrow-right me-1"></i>Quản lý voucher</small>
                </a>
            </div>
        </div>
    </div>
    
    <div class="col-lg-3 col-md-6 mb-4">
        <div class="card border-0 shadow-sm h-100 bg-gradient stats-red">
            <div class="card-body text-white text-center">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h2 class="fw-bold mb-0"><?php echo $stats['orders']['total_orders'] ?? 0; ?></h2>
                        <p class="mb-0 opacity-75">Đơn hàng</p>
                    </div>
                    <i class="bi bi-receipt fs-1 opacity-75"></i>
                </div>
            </div>
            <div class="card-footer bg-transparent border-0">
                <a href="/webbanhang/admin/orders" class="text-white text-decoration-none">
                    <small><i class="bi bi-arrow-right me-1"></i>Quản lý đơn hàng</small>
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Order Statistics -->
<div class="row mb-5">
    <div class="col-lg-8">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-white border-0 pb-0">
                <h4 class="card-title mb-0">
                    <i class="bi bi-bar-chart me-2 text-primary"></i>Thống kê đơn hàng
                </h4>
            </div>
            <div class="card-body">
                <div class="row text-center">
                    <div class="col-md-3 mb-3">
                        <div class="border-end">
                            <h3 class="text-success fw-bold"><?php echo $stats['orders']['paid_orders'] ?? 0; ?></h3>
                            <p class="text-muted mb-0">Đã thanh toán</p>
                        </div>
                    </div>
                    <div class="col-md-3 mb-3">
                        <div class="border-end">
                            <h3 class="text-warning fw-bold"><?php echo $stats['orders']['unpaid_orders'] ?? 0; ?></h3>
                            <p class="text-muted mb-0">Chưa thanh toán</p>
                        </div>
                    </div>
                    <div class="col-md-3 mb-3">
                        <div class="border-end">
                            <h3 class="text-info fw-bold"><?php echo $stats['orders']['pending_orders'] ?? 0; ?></h3>
                            <p class="text-muted mb-0">Đang xử lý</p>
                        </div>
                    </div>
                    <div class="col-md-3 mb-3">
                        <h3 class="text-danger fw-bold"><?php echo $stats['orders']['cancelled_orders'] ?? 0; ?></h3>
                        <p class="text-muted mb-0">Đã hủy</p>
                    </div>
                </div>
                <hr>
                <div class="text-center">
                    <h4 class="text-primary fw-bold">
                        Tổng doanh thu: <?php echo number_format($stats['orders']['total_revenue'] ?? 0, 0, ',', '.'); ?> đ
                    </h4>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-lg-4">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-white border-0 pb-0">
                <h4 class="card-title mb-0">
                    <i class="bi bi-speedometer2 me-2 text-primary"></i>Truy cập nhanh
                </h4>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    <a href="/webbanhang/admin/products/create" class="btn btn-outline-primary">
                        <i class="bi bi-plus-circle me-2"></i>Thêm sản phẩm mới
                    </a>
                    <a href="/webbanhang/admin/categories/create" class="btn btn-outline-info">
                        <i class="bi bi-plus-circle me-2"></i>Thêm danh mục mới
                    </a>
                    <a href="/webbanhang/admin/vouchers/create" class="btn btn-outline-warning">
                        <i class="bi bi-plus-circle me-2"></i>Tạo voucher mới
                    </a>
                    <a href="/webbanhang/admin/orders" class="btn btn-outline-success">
                        <i class="bi bi-list-check me-2"></i>Xem tất cả đơn hàng
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Recent Orders -->
<?php if (!empty($recentOrders)): ?>
<div class="card shadow-sm border-0">
    <div class="card-header bg-white border-0 pb-0">
        <div class="d-flex justify-content-between align-items-center">
            <h4 class="card-title mb-0">
                <i class="bi bi-clock-history me-2 text-primary"></i>Đơn hàng gần đây
            </h4>
            <a href="/webbanhang/admin/orders" class="btn btn-sm btn-outline-primary">
                Xem tất cả <i class="bi bi-arrow-right ms-1"></i>
            </a>
        </div>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead class="table-light">
                    <tr>
                        <th>ID</th>
                        <th>Khách hàng</th>
                        <th>Tài khoản</th>
                        <th>Tổng tiền</th>
                        <th>Trạng thái</th>
                        <th>Thời gian</th>
                        <th>Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($recentOrders as $order): ?>
                    <tr>
                        <td class="fw-bold">#<?php echo $order['id']; ?></td>
                        <td><?php echo htmlspecialchars($order['name'] ?? 'N/A', ENT_QUOTES, 'UTF-8'); ?></td>
                        <td>
                            <?php if (!empty($order['username'])): ?>
                                <span class="badge bg-info"><?php echo htmlspecialchars($order['username'], ENT_QUOTES, 'UTF-8'); ?></span>
                            <?php else: ?>
                                <span class="text-muted">Khách vãng lai</span>
                            <?php endif; ?>
                        </td>
                        <td class="fw-bold"><?php echo number_format($order['total_amount'] ?? 0, 0, ',', '.'); ?> đ</td>
                        <td>
                            <?php
                            $statusClass = 'secondary';
                            $statusText = 'Không xác định';
                            switch($order['order_status'] ?? '') {
                                case 'paid':
                                    $statusClass = 'success';
                                    $statusText = 'Đã thanh toán';
                                    break;
                                case 'unpaid':
                                    $statusClass = 'warning';
                                    $statusText = 'Chưa thanh toán';
                                    break;
                                case 'pending':
                                    $statusClass = 'info';
                                    $statusText = 'Đang xử lý';
                                    break;
                                case 'cancelled':
                                    $statusClass = 'danger';
                                    $statusText = 'Đã hủy';
                                    break;
                            }
                            ?>
                            <span class="badge bg-<?php echo $statusClass; ?>"><?php echo $statusText; ?></span>
                        </td>
                        <td>
                            <small class="text-muted">
                                <?php echo date('d/m/Y H:i', strtotime($order['created_at'] ?? 'now')); ?>
                            </small>
                        </td>
                        <td>
                            <a href="/webbanhang/admin/orders/view/<?php echo $order['id']; ?>" class="btn btn-sm btn-outline-primary">
                                <i class="bi bi-eye"></i>
                            </a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?php else: ?>
<div class="card shadow-sm border-0">
    <div class="card-body text-center py-5">
        <i class="bi bi-inbox display-1 text-muted mb-3"></i>
        <h4 class="text-muted">Chưa có đơn hàng nào</h4>
        <p class="text-muted">Các đơn hàng mới sẽ xuất hiện ở đây</p>
    </div>
</div>
<?php endif; ?>

<style>
.bg-gradient {
    background-size: 200% 200%;
    animation: gradientShift 3s ease infinite;
}

@keyframes gradientShift {
    0% { background-position: 0% 50%; }
    50% { background-position: 100% 50%; }
    100% { background-position: 0% 50%; }
}

.card:hover {
    transform: translateY(-2px);
    transition: transform 0.2s ease;
}

.border-end {
    border-right: 1px solid #dee2e6;
}

@media (max-width: 768px) {
    .border-end {
        border-right: none;
        border-bottom: 1px solid #dee2e6;
        padding-bottom: 1rem;
        margin-bottom: 1rem;
    }
}

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
</style>

<?php include_once 'app/views/shares/footer.php'; ?> 
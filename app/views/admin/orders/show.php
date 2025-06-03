<?php include_once 'app/views/shares/header.php'; ?>

<div class="container-fluid">
    <!-- Header & Navigation -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="display-6 fw-bold text-primary mb-2">
                <i class="bi bi-receipt me-3"></i>Chi tiết đơn hàng #<?php echo str_pad($order['id'], 5, '0', STR_PAD_LEFT); ?>
            </h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="/webbanhang/admin/dashboard" class="text-decoration-none">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="/webbanhang/admin/orders" class="text-decoration-none">Đơn hàng</a></li>
                    <li class="breadcrumb-item active">Chi tiết #<?php echo $order['id']; ?></li>
                </ol>
            </nav>
        </div>
        
        <div class="btn-group" role="group">
            <a href="/webbanhang/admin/orders" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left me-2"></i>Quay lại
            </a>
            <button type="button" class="btn btn-outline-primary" onclick="printOrder()">
                <i class="bi bi-printer me-2"></i>In đơn hàng
            </button>
        </div>
    </div>

    <div class="row">
        <!-- Order Information -->
        <div class="col-lg-8">
            <!-- Order Status -->
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-white border-0 pb-0">
                    <h5 class="card-title mb-0">
                        <i class="bi bi-info-circle me-2 text-primary"></i>Trạng thái đơn hàng
                    </h5>
                </div>
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <?php
                            $statusClass = 'secondary';
                            $statusText = 'Không xác định';
                            $statusIcon = 'bi-question-circle';
                            
                            switch($order['order_status'] ?? 'pending') {
                                case 'paid':
                                    $statusClass = 'success';
                                    $statusText = 'Đã thanh toán';
                                    $statusIcon = 'bi-check-circle';
                                    break;
                                case 'unpaid':
                                    $statusClass = 'warning';
                                    $statusText = 'Chưa thanh toán';
                                    $statusIcon = 'bi-exclamation-triangle';
                                    break;
                                case 'pending':
                                    $statusClass = 'info';
                                    $statusText = 'Đang xử lý';
                                    $statusIcon = 'bi-clock';
                                    break;
                                case 'cancelled':
                                    $statusClass = 'danger';
                                    $statusText = 'Đã hủy';
                                    $statusIcon = 'bi-x-circle';
                                    break;
                            }
                            ?>
                            <span class="badge bg-<?php echo $statusClass; ?> fs-6 px-3 py-2">
                                <i class="bi <?php echo $statusIcon; ?> me-2"></i><?php echo $statusText; ?>
                            </span>
                        </div>
                        
                        <!-- Status Update Form -->
                        <div class="dropdown">
                            <button class="btn btn-outline-primary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                <i class="bi bi-gear me-2"></i>Cập nhật trạng thái
                            </button>
                            <ul class="dropdown-menu">
                                <li>
                                    <form method="POST" action="/webbanhang/Product/updateOrderStatus" class="dropdown-item-form">
                                        <input type="hidden" name="order_id" value="<?php echo $order['id']; ?>">
                                        <input type="hidden" name="order_status" value="paid">
                                        <button type="submit" class="dropdown-item">
                                            <i class="bi bi-check-circle text-success me-2"></i>Đã thanh toán
                                        </button>
                                    </form>
                                </li>
                                <li>
                                    <form method="POST" action="/webbanhang/Product/updateOrderStatus" class="dropdown-item-form">
                                        <input type="hidden" name="order_id" value="<?php echo $order['id']; ?>">
                                        <input type="hidden" name="order_status" value="unpaid">
                                        <button type="submit" class="dropdown-item">
                                            <i class="bi bi-exclamation-triangle text-warning me-2"></i>Chưa thanh toán
                                        </button>
                                    </form>
                                </li>
                                <li>
                                    <form method="POST" action="/webbanhang/Product/updateOrderStatus" class="dropdown-item-form">
                                        <input type="hidden" name="order_id" value="<?php echo $order['id']; ?>">
                                        <input type="hidden" name="order_status" value="pending">
                                        <button type="submit" class="dropdown-item">
                                            <i class="bi bi-clock text-info me-2"></i>Đang xử lý
                                        </button>
                                    </form>
                                </li>
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <form method="POST" action="/webbanhang/Product/updateOrderStatus" class="dropdown-item-form">
                                        <input type="hidden" name="order_id" value="<?php echo $order['id']; ?>">
                                        <input type="hidden" name="order_status" value="cancelled">
                                        <button type="submit" class="dropdown-item text-danger" 
                                                onclick="return confirm('Bạn có chắc chắn muốn hủy đơn hàng này?');">
                                            <i class="bi bi-x-circle me-2"></i>Hủy đơn hàng
                                        </button>
                                    </form>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Order Items -->
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-white border-0 pb-0">
                    <h5 class="card-title mb-0">
                        <i class="bi bi-bag me-2 text-primary"></i>Sản phẩm đặt hàng
                    </h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th style="width: 80px;">Hình ảnh</th>
                                    <th>Sản phẩm</th>
                                    <th style="width: 100px;" class="text-center">Số lượng</th>
                                    <th style="width: 120px;" class="text-end">Đơn giá</th>
                                    <th style="width: 140px;" class="text-end">Thành tiền</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (!empty($order['items'])): ?>
                                    <?php foreach ($order['items'] as $item): ?>
                                    <tr>
                                        <td>
                                            <?php if (!empty($item['image'])): ?>
                                                <img src="/webbanhang/public/uploads/products/<?php echo htmlspecialchars($item['image'], ENT_QUOTES, 'UTF-8'); ?>" 
                                                     alt="<?php echo htmlspecialchars($item['product_name'], ENT_QUOTES, 'UTF-8'); ?>"
                                                     class="img-thumbnail" style="width: 60px; height: 60px; object-fit: cover;">
                                            <?php else: ?>
                                                <div class="bg-light rounded d-flex align-items-center justify-content-center" 
                                                     style="width: 60px; height: 60px;">
                                                    <i class="bi bi-image text-muted"></i>
                                                </div>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <div>
                                                <h6 class="mb-1"><?php echo htmlspecialchars($item['product_name'], ENT_QUOTES, 'UTF-8'); ?></h6>
                                                <small class="text-muted">ID: <?php echo $item['product_id']; ?></small>
                                            </div>
                                        </td>
                                        <td class="text-center">
                                            <span class="badge bg-light text-dark"><?php echo $item['quantity']; ?></span>
                                        </td>
                                        <td class="text-end">
                                            <strong><?php echo number_format($item['price'], 0, ',', '.'); ?> đ</strong>
                                        </td>
                                        <td class="text-end">
                                            <strong class="text-primary">
                                                <?php echo number_format($item['price'] * $item['quantity'], 0, ',', '.'); ?> đ
                                            </strong>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="5" class="text-center py-4">
                                            <i class="bi bi-inbox display-4 text-muted"></i>
                                            <p class="text-muted mt-2">Không có sản phẩm nào trong đơn hàng</p>
                                        </td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                    
                    <!-- Order Total -->
                    <div class="row justify-content-end mt-4">
                        <div class="col-md-6 col-lg-4">
                            <div class="card bg-light">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between mb-2">
                                        <span>Tạm tính:</span>
                                        <span><?php echo number_format($order['subtotal'] ?? $order['total_amount'], 0, ',', '.'); ?> đ</span>
                                    </div>
                                    
                                    <?php if (!empty($order['voucher_discount']) && $order['voucher_discount'] > 0): ?>
                                    <div class="d-flex justify-content-between mb-2 text-success">
                                        <span>
                                            Voucher 
                                            <?php if (!empty($order['voucher_code'])): ?>
                                                (<?php echo htmlspecialchars($order['voucher_code'], ENT_QUOTES, 'UTF-8'); ?>)
                                            <?php endif; ?>:
                                        </span>
                                        <span>-<?php echo number_format($order['voucher_discount'], 0, ',', '.'); ?> đ</span>
                                    </div>
                                    <?php endif; ?>
                                    
                                    <hr>
                                    <div class="d-flex justify-content-between fw-bold fs-5">
                                        <span>Tổng cộng:</span>
                                        <span class="text-primary"><?php echo number_format($order['total_amount'], 0, ',', '.'); ?> đ</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Customer & Order Information -->
        <div class="col-lg-4">
            <!-- Customer Information -->
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-white border-0 pb-0">
                    <h5 class="card-title mb-0">
                        <i class="bi bi-person me-2 text-primary"></i>Thông tin khách hàng
                    </h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label text-muted small">Họ tên:</label>
                        <p class="mb-0 fw-semibold"><?php echo htmlspecialchars($order['name'], ENT_QUOTES, 'UTF-8'); ?></p>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label text-muted small">Số điện thoại:</label>
                        <p class="mb-0">
                            <a href="tel:<?php echo htmlspecialchars($order['phone'], ENT_QUOTES, 'UTF-8'); ?>" 
                               class="text-decoration-none">
                                <?php echo htmlspecialchars($order['phone'], ENT_QUOTES, 'UTF-8'); ?>
                            </a>
                        </p>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label text-muted small">Địa chỉ:</label>
                        <p class="mb-0"><?php echo htmlspecialchars($order['address'], ENT_QUOTES, 'UTF-8'); ?></p>
                    </div>
                    
                    <?php if (!empty($order['email'])): ?>
                    <div class="mb-3">
                        <label class="form-label text-muted small">Email:</label>
                        <p class="mb-0">
                            <a href="mailto:<?php echo htmlspecialchars($order['email'], ENT_QUOTES, 'UTF-8'); ?>" 
                               class="text-decoration-none">
                                <?php echo htmlspecialchars($order['email'], ENT_QUOTES, 'UTF-8'); ?>
                            </a>
                        </p>
                    </div>
                    <?php endif; ?>
                    
                    <?php if (!empty($order['username'])): ?>
                    <div class="mb-0">
                        <label class="form-label text-muted small">Tài khoản:</label>
                        <p class="mb-0">
                            <span class="badge bg-info"><?php echo htmlspecialchars($order['username'], ENT_QUOTES, 'UTF-8'); ?></span>
                        </p>
                    </div>
                    <?php else: ?>
                    <div class="mb-0">
                        <span class="badge bg-secondary">Khách vãng lai</span>
                    </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Order Information -->
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white border-0 pb-0">
                    <h5 class="card-title mb-0">
                        <i class="bi bi-calendar me-2 text-primary"></i>Thông tin đơn hàng
                    </h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label text-muted small">Mã đơn hàng:</label>
                        <p class="mb-0 fw-bold text-primary">#<?php echo str_pad($order['id'], 5, '0', STR_PAD_LEFT); ?></p>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label text-muted small">Ngày đặt hàng:</label>
                        <p class="mb-0">
                            <i class="bi bi-calendar3 me-1"></i>
                            <?php echo date('d/m/Y', strtotime($order['created_at'])); ?>
                            <br>
                            <small class="text-muted">
                                <i class="bi bi-clock me-1"></i>
                                <?php echo date('H:i:s', strtotime($order['created_at'])); ?>
                            </small>
                        </p>
                    </div>
                    
                    <?php if (!empty($order['notes'])): ?>
                    <div class="mb-0">
                        <label class="form-label text-muted small">Ghi chú:</label>
                        <p class="mb-0 fst-italic"><?php echo htmlspecialchars($order['notes'], ENT_QUOTES, 'UTF-8'); ?></p>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.dropdown-item-form {
    margin: 0;
    padding: 0;
}

.dropdown-item-form .dropdown-item {
    border: none;
    background: none;
    width: 100%;
    text-align: left;
}

.card {
    transition: transform 0.2s ease;
}

.card:hover {
    transform: translateY(-1px);
}

@media print {
    .btn-group, .dropdown, .breadcrumb {
        display: none !important;
    }
}
</style>

<script>
function printOrder() {
    window.print();
}

// Auto-redirect after status update
<?php if (isset($_GET['status_updated'])): ?>
setTimeout(function() {
    window.location.href = '/webbanhang/admin/orders/view/<?php echo $order['id']; ?>';
}, 1500);
<?php endif; ?>
</script>

<?php include_once 'app/views/shares/footer.php'; ?> 
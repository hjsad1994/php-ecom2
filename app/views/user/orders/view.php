<?php include_once 'app/views/shares/header.php'; ?>

<div class="container mt-4">
    <div class="row mb-4">
        <div class="col-12">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="/webbanhang/" class="text-decoration-none">Trang chủ</a></li>
                    <li class="breadcrumb-item"><a href="/webbanhang/user/orders" class="text-decoration-none">Đơn hàng</a></li>
                    <li class="breadcrumb-item active">Chi tiết đơn hàng #<?php echo str_pad($order['id'], 5, '0', STR_PAD_LEFT); ?></li>
                </ol>
            </nav>
        </div>
    </div>

    <div class="row mb-4">
        <div class="col-md-8">
            <h1 class="display-6 fw-bold text-primary">
                <i class="bi bi-receipt-cutoff me-2"></i>Chi tiết đơn hàng #<?php echo str_pad($order['id'], 5, '0', STR_PAD_LEFT); ?>
            </h1>
        </div>
        <div class="col-md-4 text-end">
            <a href="/webbanhang/user/orders" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left me-2"></i>Quay lại
            </a>
        </div>
    </div>

    <div class="row">
        <!-- Order Information -->
        <div class="col-md-8">
            <!-- Order Status -->
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-light">
                    <h5 class="mb-0"><i class="bi bi-info-circle me-2"></i>Trạng thái đơn hàng</h5>
                </div>
                <div class="card-body">
                    <?php
                    $statusClass = '';
                    $statusText = '';
                    $statusIcon = '';
                    switch($order['order_status']) {
                        case 'unpaid':
                            $statusClass = 'warning';
                            $statusText = 'Chưa thanh toán';
                            $statusIcon = 'bi-exclamation-triangle';
                            break;
                        case 'paid':
                            $statusClass = 'success';
                            $statusText = 'Đã thanh toán';
                            $statusIcon = 'bi-check-circle';
                            break;
                        case 'pending':
                            $statusClass = 'info';
                            $statusText = 'Đang xử lý';
                            $statusIcon = 'bi-clock';
                            break;
                        case 'completed':
                            $statusClass = 'primary';
                            $statusText = 'Hoàn thành';
                            $statusIcon = 'bi-check-circle-fill';
                            break;
                        case 'cancelled':
                            $statusClass = 'danger';
                            $statusText = 'Đã hủy';
                            $statusIcon = 'bi-x-circle';
                            break;
                        default:
                            $statusClass = 'secondary';
                            $statusText = 'Không xác định';
                            $statusIcon = 'bi-question-circle';
                    }
                    ?>
                    <div class="d-flex align-items-center">
                        <div class="alert alert-<?php echo $statusClass; ?> mb-0 flex-grow-1 d-flex align-items-center">
                            <i class="bi <?php echo $statusIcon; ?> me-2 fs-4"></i>
                            <div>
                                <strong><?php echo $statusText; ?></strong>
                                <div class="small">
                                    Đặt hàng: <?php echo date('d/m/Y H:i', strtotime($order['created_at'])); ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Order Items -->
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-light">
                    <h5 class="mb-0"><i class="bi bi-box-seam me-2"></i>Sản phẩm đã đặt</h5>
                </div>
                <div class="card-body">
                    <?php if (!empty($order['items'])): ?>
                        <?php foreach ($order['items'] as $item): ?>
                            <div class="d-flex align-items-center border-bottom pb-3 mb-3">
                                <div class="me-3">
                                    <?php 
                                    $imagePath = !empty($item['image']) ? '/webbanhang/public/uploads/products/' . $item['image'] : '/webbanhang/public/images/no-image.jpg';
                                    ?>
                                    <img src="<?php echo $imagePath; ?>" 
                                         alt="<?php echo htmlspecialchars($item['product_name'], ENT_QUOTES, 'UTF-8'); ?>"
                                         class="img-thumbnail"
                                         style="width: 80px; height: 80px; object-fit: cover;">
                                </div>
                                <div class="flex-grow-1">
                                    <h6 class="mb-1"><?php echo htmlspecialchars($item['product_name'], ENT_QUOTES, 'UTF-8'); ?></h6>
                                    <div class="text-muted small mb-2">
                                        Số lượng: <span class="fw-bold"><?php echo $item['quantity']; ?></span>
                                    </div>
                                    <div class="d-flex justify-content-between align-items-center">
                                        <span class="text-primary fw-bold">
                                            <?php echo number_format($item['price'], 0, ',', '.'); ?> đ
                                        </span>
                                        <span class="text-success fw-bold">
                                            Tổng: <?php echo number_format($item['price'] * $item['quantity'], 0, ',', '.'); ?> đ
                                        </span>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <div class="text-center text-muted py-4">
                            <i class="bi bi-box display-4 mb-3"></i>
                            <p>Không có sản phẩm nào trong đơn hàng này.</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Delivery Information -->
            <div class="card shadow-sm">
                <div class="card-header bg-light">
                    <h5 class="mb-0"><i class="bi bi-truck me-2"></i>Thông tin giao hàng</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="text-muted small">Người nhận:</label>
                                <div class="fw-bold"><?php echo htmlspecialchars($order['name'], ENT_QUOTES, 'UTF-8'); ?></div>
                            </div>
                            <div class="mb-3">
                                <label class="text-muted small">Số điện thoại:</label>
                                <div class="fw-bold"><?php echo htmlspecialchars($order['phone'], ENT_QUOTES, 'UTF-8'); ?></div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="text-muted small">Địa chỉ giao hàng:</label>
                                <div class="fw-bold"><?php echo htmlspecialchars($order['address'], ENT_QUOTES, 'UTF-8'); ?></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Order Summary -->
        <div class="col-md-4">
            <div class="card shadow-sm sticky-top">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="bi bi-calculator me-2"></i>Tóm tắt đơn hàng</h5>
                </div>
                <div class="card-body">
                    <div class="d-flex justify-content-between mb-2">
                        <span>Tổng tiền hàng:</span>
                        <span class="fw-bold"><?php echo number_format($order['total_amount'], 0, ',', '.'); ?> đ</span>
                    </div>
                    
                    <?php if (!empty($order['voucher_code'])): ?>
                        <div class="d-flex justify-content-between mb-2 text-success">
                            <span>Voucher (<?php echo $order['voucher_code']; ?>):</span>
                            <span class="fw-bold">-<?php echo number_format($order['voucher_discount'] ?? 0, 0, ',', '.'); ?> đ</span>
                        </div>
                    <?php endif; ?>
                    
                    <hr>
                    <div class="d-flex justify-content-between mb-3">
                        <span class="fw-bold">Tổng thanh toán:</span>
                        <span class="fw-bold text-primary fs-5"><?php echo number_format($order['total_amount'], 0, ',', '.'); ?> đ</span>
                    </div>

                    <!-- Action Buttons -->
                    <div class="d-grid gap-2">
                        <?php if ($order['order_status'] === 'unpaid'): ?>
                            <button class="btn btn-success" onclick="payOrderDetail(<?php echo $order['id']; ?>)">
                                <i class="bi bi-credit-card me-2"></i>Thanh toán ngay
                            </button>
                            <button class="btn btn-outline-danger" onclick="cancelOrderDetail(<?php echo $order['id']; ?>)">
                                <i class="bi bi-x-circle me-2"></i>Hủy đơn hàng
                            </button>
                        <?php endif; ?>
                        
                        <button class="btn btn-outline-primary" onclick="downloadInvoiceDetail(<?php echo $order['id']; ?>)">
                            <i class="bi bi-download me-2"></i>Tải hóa đơn
                        </button>
                        
                        <?php if ($order['order_status'] === 'completed'): ?>
                            <button class="btn btn-outline-success" onclick="reorderDetail(<?php echo $order['id']; ?>)">
                                <i class="bi bi-arrow-repeat me-2"></i>Mua lại
                            </button>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function payOrderDetail(orderId) {
    if (confirm('Bạn muốn thanh toán đơn hàng này?')) {
        window.location.href = `/webbanhang/user/orders/payment/${orderId}`;
    }
}

function cancelOrderDetail(orderId) {
    if (confirm('Bạn có chắc chắn muốn hủy đơn hàng này?')) {
        fetch('/webbanhang/order/cancel', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: `order_id=${orderId}`
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Đơn hàng đã được hủy thành công');
                location.reload();
            } else {
                alert(data.message || 'Có lỗi xảy ra khi hủy đơn hàng');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Có lỗi xảy ra khi hủy đơn hàng');
        });
    }
}

function downloadInvoiceDetail(orderId) {
    window.open(`/webbanhang/user/orders/invoice/${orderId}`, '_blank');
}

function reorderDetail(orderId) {
    if (confirm('Bạn muốn mua lại các sản phẩm trong đơn hàng này?')) {
        fetch('/webbanhang/order/reorder', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: `order_id=${orderId}`
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Đã thêm sản phẩm vào giỏ hàng');
                window.location.href = '/webbanhang/user/cart';
            } else {
                alert(data.message || 'Có lỗi xảy ra khi thêm sản phẩm vào giỏ hàng');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Có lỗi xảy ra khi thêm sản phẩm vào giỏ hàng');
        });
    }
}
</script>

<?php include_once 'app/views/shares/footer.php'; ?> 
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
            <p class="text-muted mb-0">
                <i class="bi bi-calendar me-1"></i>
                Đặt hàng: <?php echo date('d/m/Y H:i', strtotime($order['created_at'])); ?>
            </p>
        </div>
        <div class="col-md-4 text-end">
            <a href="/webbanhang/user/orders" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left me-2"></i>Quay lại danh sách
            </a>
            <?php if ($order['order_status'] === 'unpaid'): ?>
                <a href="/webbanhang/user/orders/payment/<?php echo $order['id']; ?>" class="btn btn-success">
                    <i class="bi bi-credit-card me-2"></i>Thanh toán
                </a>
            <?php endif; ?>
        </div>
    </div>

    <div class="row">
        <!-- Order Information -->
        <div class="col-md-8">
            <!-- Order Status & Timeline -->
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-light">
                    <h5 class="mb-0"><i class="bi bi-truck me-2"></i>Trạng thái đơn hàng</h5>
                </div>
                <div class="card-body">
                    <?php
                    $statusClass = '';
                    $statusText = '';
                    $statusIcon = '';
                    $progressPercent = 25;
                    switch($order['order_status']) {
                        case 'unpaid':
                            $statusClass = 'warning';
                            $statusText = 'Chưa thanh toán';
                            $statusIcon = 'bi-exclamation-triangle';
                            $progressPercent = 25;
                            break;
                        case 'paid':
                            $statusClass = 'info';
                            $statusText = 'Đã thanh toán - Đang chuẩn bị';
                            $statusIcon = 'bi-check-circle';
                            $progressPercent = 50;
                            break;
                        case 'pending':
                            $statusClass = 'primary';
                            $statusText = 'Đang giao hàng';
                            $statusIcon = 'bi-truck';
                            $progressPercent = 75;
                            break;
                        case 'completed':
                            $statusClass = 'success';
                            $statusText = 'Đã hoàn thành';
                            $statusIcon = 'bi-check-circle-fill';
                            $progressPercent = 100;
                            break;
                        case 'cancelled':
                            $statusClass = 'danger';
                            $statusText = 'Đã hủy';
                            $statusIcon = 'bi-x-circle';
                            $progressPercent = 0;
                            break;
                        default:
                            $statusClass = 'secondary';
                            $statusText = 'Không xác định';
                            $statusIcon = 'bi-question-circle';
                            $progressPercent = 25;
                    }
                    ?>
                    <div class="d-flex align-items-center mb-4">
                        <div class="alert alert-<?php echo $statusClass; ?> mb-0 flex-grow-1 d-flex align-items-center">
                            <i class="bi <?php echo $statusIcon; ?> me-2 fs-4"></i>
                            <div>
                                <strong><?php echo $statusText; ?></strong>
                                <div class="small">
                                    Cập nhật: <?php echo date('d/m/Y H:i', strtotime($order['created_at'])); ?>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Progress Timeline -->
                    <div class="order-timeline">
                        <h6 class="text-muted small mb-3">Tiến trình đơn hàng:</h6>
                        <div class="progress mb-3" style="height: 10px;">
                            <div class="progress-bar progress-bar-striped bg-<?php echo $statusClass; ?>" 
                                 style="width: <?php echo $progressPercent; ?>%"
                                 role="progressbar"></div>
                        </div>
                        
                        <div class="row text-center">
                            <div class="col-3">
                                <div class="timeline-step <?php echo $progressPercent >= 25 ? 'active' : ''; ?>">
                                    <i class="bi bi-cart-check fs-4 <?php echo $progressPercent >= 25 ? 'text-success' : 'text-muted'; ?>"></i>
                                    <div class="small mt-1">Đặt hàng</div>
                                </div>
                            </div>
                            <div class="col-3">
                                <div class="timeline-step <?php echo $progressPercent >= 50 ? 'active' : ''; ?>">
                                    <i class="bi bi-credit-card fs-4 <?php echo $progressPercent >= 50 ? 'text-success' : 'text-muted'; ?>"></i>
                                    <div class="small mt-1">Thanh toán</div>
                                </div>
                            </div>
                            <div class="col-3">
                                <div class="timeline-step <?php echo $progressPercent >= 75 ? 'active' : ''; ?>">
                                    <i class="bi bi-truck fs-4 <?php echo $progressPercent >= 75 ? 'text-success' : 'text-muted'; ?>"></i>
                                    <div class="small mt-1">Đang giao</div>
                                </div>
                            </div>
                            <div class="col-3">
                                <div class="timeline-step <?php echo $progressPercent >= 100 ? 'active' : ''; ?>">
                                    <i class="bi bi-check-circle-fill fs-4 <?php echo $progressPercent >= 100 ? 'text-success' : 'text-muted'; ?>"></i>
                                    <div class="small mt-1">Hoàn thành</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Order Items -->
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-light d-flex justify-content-between align-items-center">
                    <h5 class="mb-0"><i class="bi bi-box-seam me-2"></i>Sản phẩm đã đặt</h5>
                    <span class="badge bg-primary"><?php echo count($order['items']); ?> sản phẩm</span>
                </div>
                <div class="card-body">
                    <?php if (!empty($order['items'])): ?>
                        <?php foreach ($order['items'] as $index => $item): ?>
                            <div class="row border-bottom pb-3 mb-3 <?php echo $index === count($order['items']) - 1 ? 'border-bottom-0 mb-0 pb-0' : ''; ?>">
                                <div class="col-md-2">
                                    <?php 
                                    $imagePath = !empty($item['image']) ? '/webbanhang/public/uploads/products/' . $item['image'] : '/webbanhang/public/images/no-image.jpg';
                                    ?>
                                    <img src="<?php echo $imagePath; ?>" 
                                         alt="<?php echo htmlspecialchars($item['product_name'], ENT_QUOTES, 'UTF-8'); ?>"
                                         class="img-thumbnail w-100"
                                         style="height: 100px; object-fit: cover;">
                                </div>
                                <div class="col-md-6">
                                    <h6 class="mb-1 fw-bold"><?php echo htmlspecialchars($item['product_name'], ENT_QUOTES, 'UTF-8'); ?></h6>
                                    <div class="text-muted small mb-2">
                                        <i class="bi bi-tag me-1"></i>Mã sản phẩm: SP<?php echo str_pad($item['product_id'], 5, '0', STR_PAD_LEFT); ?>
                                    </div>
                                    <div class="d-flex align-items-center">
                                        <span class="text-primary fw-bold">
                                            <?php echo number_format($item['price'], 0, ',', '.'); ?> đ
                                        </span>
                                        <span class="text-muted ms-2">x <?php echo $item['quantity']; ?></span>
                                    </div>
                                </div>
                                <div class="col-md-4 text-end">
                                    <div class="text-muted small">Thành tiền:</div>
                                    <div class="fs-5 fw-bold text-success">
                                        <?php echo number_format($item['price'] * $item['quantity'], 0, ',', '.'); ?> đ
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <div class="text-center py-4">
                            <i class="bi bi-box-seam text-muted" style="font-size: 3rem;"></i>
                            <p class="text-muted mt-2">Không có sản phẩm nào trong đơn hàng này</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Shipping Information -->
            <div class="card shadow-sm">
                <div class="card-header bg-light">
                    <h5 class="mb-0"><i class="bi bi-geo-alt me-2"></i>Thông tin giao hàng</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="text-muted small mb-1"><i class="bi bi-person me-1"></i>Người nhận:</label>
                                <div class="fw-bold"><?php echo htmlspecialchars($order['name'], ENT_QUOTES, 'UTF-8'); ?></div>
                            </div>
                            <div class="mb-3">
                                <label class="text-muted small mb-1"><i class="bi bi-telephone me-1"></i>Số điện thoại:</label>
                                <div class="fw-bold">
                                    <a href="tel:<?php echo htmlspecialchars($order['phone'], ENT_QUOTES, 'UTF-8'); ?>" 
                                       class="text-decoration-none">
                                        <?php echo htmlspecialchars($order['phone'], ENT_QUOTES, 'UTF-8'); ?>
                                    </a>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="text-muted small mb-1"><i class="bi bi-geo-alt me-1"></i>Địa chỉ giao hàng:</label>
                                <div class="fw-bold"><?php echo htmlspecialchars($order['address'], ENT_QUOTES, 'UTF-8'); ?></div>
                            </div>
                            <div class="mb-3">
                                <label class="text-muted small mb-1"><i class="bi bi-clock me-1"></i>Thời gian dự kiến:</label>
                                <div class="fw-bold text-primary">
                                    <?php 
                                    if ($order['order_status'] === 'completed') {
                                        echo 'Đã giao hàng';
                                    } elseif ($order['order_status'] === 'pending') {
                                        echo '1-2 ngày làm việc';
                                    } elseif ($order['order_status'] === 'paid') {
                                        echo '2-3 ngày làm việc';
                                    } else {
                                        echo 'Chờ thanh toán';
                                    }
                                    ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <?php if ($order['order_status'] === 'pending' || $order['order_status'] === 'completed'): ?>
                    <div class="border-top pt-3 mt-3">
                        <div class="d-flex align-items-center">
                            <i class="bi bi-truck text-primary me-2"></i>
                            <div>
                                <div class="fw-bold">Đơn vị vận chuyển: Express Delivery</div>
                                <div class="small text-muted">Mã vận đơn: ED<?php echo str_pad($order['id'], 8, '0', STR_PAD_LEFT); ?></div>
                            </div>
                        </div>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Order Summary -->
        <div class="col-md-4">
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="bi bi-receipt me-2"></i>Tóm tắt đơn hàng</h5>
                </div>
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span>Tổng sản phẩm:</span>
                        <span class="fw-bold"><?php echo count($order['items']); ?> sản phẩm</span>
                    </div>
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span>Tạm tính:</span>
                        <span><?php echo number_format($order['subtotal'] ?? $order['total_amount'], 0, ',', '.'); ?> đ</span>
                    </div>
                    
                    <?php if (!empty($order['voucher_code'])): ?>
                    <div class="d-flex justify-content-between align-items-center mb-2 text-success">
                        <span><i class="bi bi-ticket me-1"></i>Voucher (<?php echo htmlspecialchars($order['voucher_code'], ENT_QUOTES, 'UTF-8'); ?>):</span>
                        <span>-<?php echo number_format($order['voucher_discount'] ?? 0, 0, ',', '.'); ?> đ</span>
                    </div>
                    <?php endif; ?>
                    
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span>Phí vận chuyển:</span>
                        <span class="text-success">Miễn phí</span>
                    </div>
                    
                    <hr>
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <strong>Tổng cộng:</strong>
                        <strong class="text-primary fs-5">
                            <?php echo number_format($order['total_amount'] ?? 0, 0, ',', '.'); ?> đ
                        </strong>
                    </div>
                    
                    <div class="d-grid gap-2">
                        <?php if ($order['order_status'] === 'unpaid'): ?>
                            <button class="btn btn-success" onclick="payOrderDetail(<?php echo $order['id']; ?>)">
                                <i class="bi bi-credit-card me-2"></i>Thanh toán ngay
                            </button>
                            <button class="btn btn-outline-danger" onclick="cancelOrderDetail(<?php echo $order['id']; ?>)">
                                <i class="bi bi-x-circle me-2"></i>Hủy đơn hàng
                            </button>
                        <?php elseif ($order['order_status'] === 'completed'): ?>
                            <button class="btn btn-outline-warning" onclick="rateOrderDetail(<?php echo $order['id']; ?>)">
                                <i class="bi bi-star me-2"></i>Đánh giá đơn hàng
                            </button>
                            <button class="btn btn-outline-primary" onclick="reorderDetail(<?php echo $order['id']; ?>)">
                                <i class="bi bi-arrow-repeat me-2"></i>Mua lại
                            </button>
                        <?php endif; ?>
                        
                        <button class="btn btn-outline-secondary" onclick="downloadInvoiceDetail(<?php echo $order['id']; ?>)">
                            <i class="bi bi-download me-2"></i>Tải hóa đơn
                        </button>
                        
                        <button class="btn btn-outline-info" onclick="contactSupport()">
                            <i class="bi bi-headset me-2"></i>Hỗ trợ
                        </button>
                    </div>
                </div>
            </div>
            
            <!-- Order Notes -->
            <div class="card shadow-sm">
                <div class="card-header bg-light">
                    <h6 class="mb-0"><i class="bi bi-chat-dots me-2"></i>Ghi chú đơn hàng</h6>
                </div>
                <div class="card-body">
                    <?php if (!empty($order['notes'])): ?>
                        <p class="mb-0"><?php echo nl2br(htmlspecialchars($order['notes'], ENT_QUOTES, 'UTF-8')); ?></p>
                    <?php else: ?>
                        <p class="text-muted mb-0 small">Không có ghi chú đặc biệt</p>
                    <?php endif; ?>
                    
                    <hr class="my-3">
                    
                    <div class="small text-muted">
                        <div><strong>Chính sách:</strong></div>
                        <ul class="mb-0 ps-3">
                            <li>Miễn phí đổi trả trong 7 ngày</li>
                            <li>Bảo hành theo chính sách nhà sản xuất</li>
                            <li>Hỗ trợ khách hàng 24/7</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.timeline-step.active {
    opacity: 1;
}

.timeline-step {
    opacity: 0.5;
}

.order-timeline .progress-bar-striped {
    animation: progress-bar-stripes 1s linear infinite;
}

@keyframes progress-bar-stripes {
    0% { background-position: 1rem 0; }
    100% { background-position: 0 0; }
}
</style>

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
    if (confirm('Bạn muốn đặt lại đơn hàng này?')) {
        window.location.href = `/webbanhang/order/reorder/${orderId}`;
    }
}

function rateOrderDetail(orderId) {
    alert('Tính năng đánh giá đang được phát triển. Cảm ơn bạn!');
}

function contactSupport() {
    alert('Liên hệ hỗ trợ:\nHotline: 1900-XXX-XXX\nEmail: support@webbanhang.com');
}
</script>

<?php include_once 'app/views/shares/footer.php'; ?> 
<?php 
// Debug info - will be removed after fixing
if (isset($_GET['debug'])) {
    echo "<div style='background: yellow; padding: 10px; margin: 10px;'>";
    echo "<h3>DEBUG INFO:</h3>";
    echo "<p><strong>Order variable type:</strong> " . gettype($order ?? null) . "</p>";
    if (isset($order)) {
        echo "<p><strong>Order ID:</strong> " . ($order['id'] ?? 'not set') . "</p>";
        echo "<p><strong>Order keys:</strong> " . implode(', ', array_keys((array)$order)) . "</p>";
    } else {
        echo "<p style='color: red;'><strong>Order variable not set!</strong></p>";
    }
    echo "</div>";
}

include_once 'app/views/shares/header.php'; ?>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <!-- Success Message -->
            <div class="text-center mb-5">
                <div class="mb-4">
                    <i class="bi bi-check-circle text-success" style="font-size: 4rem;"></i>
                </div>
                <h1 class="display-5 fw-bold text-success mb-3">Đặt hàng thành công!</h1>
                <p class="lead text-muted">Cảm ơn bạn đã tin tưởng và đặt hàng. Đơn hàng của bạn đã được tiếp nhận và đang được xử lý.</p>
            </div>

            <!-- Order Summary Card -->
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">
                        <i class="bi bi-receipt-cutoff me-2"></i>Thông tin đơn hàng #<?php echo $order['id']; ?>
                    </h5>
                </div>
                <div class="card-body">
                    <!-- Order Info -->
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <h6 class="fw-bold text-primary">Thông tin khách hàng</h6>
                            <p class="mb-1"><strong>Họ tên:</strong> <?php echo htmlspecialchars($order['name'], ENT_QUOTES, 'UTF-8'); ?></p>
                            <p class="mb-1"><strong>Điện thoại:</strong> <?php echo htmlspecialchars($order['phone'], ENT_QUOTES, 'UTF-8'); ?></p>
                            <p class="mb-0"><strong>Địa chỉ:</strong> <?php echo htmlspecialchars($order['address'], ENT_QUOTES, 'UTF-8'); ?></p>
                        </div>
                        <div class="col-md-6">
                            <h6 class="fw-bold text-primary">Thông tin đơn hàng</h6>
                            <p class="mb-1"><strong>Mã đơn:</strong> #<?php echo $order['id']; ?></p>
                            <p class="mb-1"><strong>Ngày đặt:</strong> <?php echo date('d/m/Y H:i', strtotime($order['created_at'])); ?></p>
                            <p class="mb-1"><strong>Trạng thái:</strong> 
                                <span class="badge bg-warning">
                                    <?php 
                                    echo $order['order_status'] == 'unpaid' ? 'Chờ thanh toán' : 
                                        ($order['order_status'] == 'paid' ? 'Đã thanh toán' : 
                                        ($order['order_status'] == 'shipping' ? 'Đang giao hàng' : 'Hoàn thành'));
                                    ?>
                                </span>
                            </p>
                            <p class="mb-0"><strong>Tổng tiền:</strong> <span class="text-success fw-bold"><?php echo number_format($order['total_amount'], 0, ',', '.'); ?> đ</span></p>
                        </div>
                    </div>

                    <!-- Products List -->
                    <h6 class="fw-bold text-primary mb-3">Sản phẩm đã đặt</h6>
                    <?php if (isset($order['items']) && !empty($order['items'])): ?>
                        <?php foreach ($order['items'] as $item): ?>
                            <div class="border rounded p-3 mb-2 bg-light">
                                <div class="row align-items-center">
                                    <div class="col-md-8">
                                        <div class="d-flex align-items-center">
                                            <?php 
                                            $imagePath = !empty($item['image']) ? '/webbanhang/public/uploads/products/' . $item['image'] : '/webbanhang/public/images/no-image.jpg';
                                            ?>
                                            <img src="<?php echo $imagePath; ?>" alt="<?php echo htmlspecialchars($item['product_name'], ENT_QUOTES, 'UTF-8'); ?>" 
                                                 class="me-3 rounded" style="width: 72px; height: 72px; object-fit: cover; flex-shrink: 0;">
                                            <div class="flex-grow-1">
                                                <h6 class="mb-0"><?php echo htmlspecialchars($item['product_name'], ENT_QUOTES, 'UTF-8'); ?></h6>
                                                <small class="text-muted">SKU: <?php echo $item['product_id']; ?></small>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="row text-center text-md-end">
                                            <div class="col-6 col-md-12">
                                                <small class="text-muted d-block">Đơn giá</small>
                                                <span><?php echo number_format($item['price'], 0, ',', '.'); ?> đ</span>
                                            </div>
                                            <div class="col-6 col-md-12">
                                                <small class="text-muted d-block">Thành tiền</small>
                                                <span class="fw-bold text-primary"><?php echo number_format($item['price'] * $item['quantity'], 0, ',', '.'); ?> đ</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                        
                        <!-- Total Summary -->
                        <div class="border-top pt-3 mt-3">
                            <div class="row">
                                <div class="col-md-8">
                                    <h6 class="mb-0">Tổng cộng:</h6>
                                </div>
                                <div class="col-md-4 text-md-end">
                                    <h5 class="mb-0 text-success fw-bold"><?php echo number_format($order['total_amount'], 0, ',', '.'); ?> đ</h5>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>

                    <!-- Voucher Info -->
                    <?php if (!empty($order['voucher_code'])): ?>
                        <div class="alert alert-success">
                            <i class="bi bi-ticket-perforated me-2"></i>
                            <strong>Mã giảm giá đã áp dụng:</strong> <?php echo $order['voucher_code']; ?>
                            <span class="float-end">Giảm: -<?php echo number_format($order['voucher_discount'] ?? 0, 0, ',', '.'); ?> đ</span>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Next Steps -->
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-info text-white">
                    <h6 class="mb-0">
                        <i class="bi bi-info-circle me-2"></i>Thông tin quan trọng
                    </h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h6 class="text-primary">📞 Liên hệ hỗ trợ</h6>
                            <p class="small">Hotline: <strong>1900-xxxx</strong><br>
                            Email: <strong>support@example.com</strong></p>
                        </div>
                        <div class="col-md-6">
                            <h6 class="text-primary">🚚 Thời gian giao hàng</h6>
                            <p class="small">Nội thành: <strong>1-2 ngày</strong><br>
                            Ngoại thành: <strong>3-5 ngày</strong></p>
                        </div>
                    </div>
                    <div class="alert alert-warning">
                        <small><strong>Lưu ý:</strong> Vui lòng kiểm tra kỹ thông tin đơn hàng và liên hệ ngay với chúng tôi nếu có bất kỳ thay đổi nào.</small>
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="text-center">
                <div class="d-grid gap-2 d-md-block">
                    <a href="/webbanhang/user/orders/view/<?php echo $order['id']; ?>" class="btn btn-outline-primary btn-lg">
                        <i class="bi bi-eye me-2"></i>Xem chi tiết đơn hàng
                    </a>
                    
                    <?php if ($order['order_status'] === 'unpaid'): ?>
                        <a href="/webbanhang/user/orders/payment/<?php echo $order['id']; ?>" class="btn btn-success btn-lg">
                            <i class="bi bi-credit-card me-2"></i>Thanh toán ngay
                        </a>
                    <?php endif; ?>
                    
                    <a href="/webbanhang/user/products" class="btn btn-outline-secondary btn-lg">
                        <i class="bi bi-arrow-left me-2"></i>Tiếp tục mua sắm
                    </a>
                </div>
            </div>

            <!-- Order Tracking Timeline -->
            <div class="card shadow-sm mt-4">
                <div class="card-header bg-light">
                    <h6 class="mb-0">
                        <i class="bi bi-truck me-2"></i>Trạng thái đơn hàng
                    </h6>
                </div>
                <div class="card-body">
                    <div class="timeline">
                        <div class="timeline-item active">
                            <div class="timeline-marker bg-success"></div>
                            <div class="timeline-content">
                                <h6 class="timeline-title">Đơn hàng đã được đặt</h6>
                                <p class="timeline-description text-muted small">
                                    <?php echo date('d/m/Y H:i', strtotime($order['created_at'])); ?>
                                </p>
                            </div>
                        </div>
                        
                        <div class="timeline-item <?php echo in_array($order['order_status'], ['paid', 'shipping', 'delivered']) ? 'active' : ''; ?>">
                            <div class="timeline-marker <?php echo in_array($order['order_status'], ['paid', 'shipping', 'delivered']) ? 'bg-success' : 'bg-secondary'; ?>"></div>
                            <div class="timeline-content">
                                <h6 class="timeline-title">Xác nhận thanh toán</h6>
                                <p class="timeline-description text-muted small">Chờ xác nhận thanh toán</p>
                            </div>
                        </div>
                        
                        <div class="timeline-item <?php echo in_array($order['order_status'], ['shipping', 'delivered']) ? 'active' : ''; ?>">
                            <div class="timeline-marker <?php echo in_array($order['order_status'], ['shipping', 'delivered']) ? 'bg-success' : 'bg-secondary'; ?>"></div>
                            <div class="timeline-content">
                                <h6 class="timeline-title">Đang giao hàng</h6>
                                <p class="timeline-description text-muted small">Đơn hàng đang được vận chuyển</p>
                            </div>
                        </div>
                        
                        <div class="timeline-item <?php echo $order['order_status'] === 'delivered' ? 'active' : ''; ?>">
                            <div class="timeline-marker <?php echo $order['order_status'] === 'delivered' ? 'bg-success' : 'bg-secondary'; ?>"></div>
                            <div class="timeline-content">
                                <h6 class="timeline-title">Hoàn thành</h6>
                                <p class="timeline-description text-muted small">Đơn hàng đã được giao thành công</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Custom Timeline CSS -->
<style>
.timeline {
    position: relative;
    padding-left: 30px;
}

.timeline::before {
    content: '';
    position: absolute;
    left: 15px;
    top: 0;
    bottom: 0;
    width: 2px;
    background-color: #dee2e6;
}

.timeline-item {
    position: relative;
    margin-bottom: 30px;
}

.timeline-marker {
    position: absolute;
    left: -38px;
    top: 5px;
    width: 15px;
    height: 15px;
    border-radius: 50%;
    border: 3px solid #fff;
    box-shadow: 0 0 0 2px #dee2e6;
}

.timeline-item.active .timeline-marker {
    box-shadow: 0 0 0 2px #28a745;
}

.timeline-title {
    color: #495057;
    margin-bottom: 5px;
}

.timeline-item.active .timeline-title {
    color: #28a745;
    font-weight: 600;
}

.timeline-description {
    margin: 0;
}

.bg-success {
    background-color: #28a745 !important;
}

.bg-secondary {
    background-color: #6c757d !important;
}
</style>

<?php include_once 'app/views/shares/footer.php'; ?> 
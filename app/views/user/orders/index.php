<?php include_once 'app/views/shares/header.php'; ?>

<div class="row mb-4">
    <div class="col-md-8">
        <h1 class="display-6 fw-bold text-primary">
            <i class="bi bi-receipt me-2"></i>Đơn hàng của tôi
        </h1>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="/webbanhang/" class="text-decoration-none">Trang chủ</a></li>
                <li class="breadcrumb-item active">Đơn hàng</li>
            </ol>
        </nav>
    </div>
    <div class="col-md-4 text-end">
        <a href="/webbanhang/user/orders/create" class="btn btn-primary">
            <i class="bi bi-plus-circle me-2"></i>Tạo đơn hàng mới
        </a>
        <a href="/webbanhang/user/profile" class="btn btn-outline-secondary">
            <i class="bi bi-person me-2"></i>Hồ sơ
        </a>
    </div>
</div>

<!-- Order Status Filter -->
<div class="card shadow-sm mb-4">
    <div class="card-body">
        <div class="row align-items-center">
            <div class="col-md-6">
                <h6 class="mb-0"><i class="bi bi-funnel me-2"></i>Lọc theo trạng thái:</h6>
            </div>
            <div class="col-md-6">
                <div class="btn-group w-100" role="group">
                    <input type="radio" class="btn-check" name="statusFilter" id="all" value="all" checked>
                    <label class="btn btn-outline-secondary btn-sm" for="all">Tất cả</label>
                    
                    <input type="radio" class="btn-check" name="statusFilter" id="unpaid" value="unpaid">
                    <label class="btn btn-outline-warning btn-sm" for="unpaid">Chưa thanh toán</label>
                    
                    <input type="radio" class="btn-check" name="statusFilter" id="paid" value="paid">
                    <label class="btn btn-outline-success btn-sm" for="paid">Đã thanh toán</label>
                    
                    <input type="radio" class="btn-check" name="statusFilter" id="pending" value="pending">
                    <label class="btn btn-outline-info btn-sm" for="pending">Đang xử lý</label>
                    
                    <input type="radio" class="btn-check" name="statusFilter" id="cancelled" value="cancelled">
                    <label class="btn btn-outline-danger btn-sm" for="cancelled">Đã hủy</label>
                </div>
            </div>
        </div>
    </div>
</div>

<?php if (empty($orders)): ?>
    <!-- Empty Orders -->
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="text-center py-5">
                <i class="bi bi-receipt display-1 text-muted mb-4"></i>
                <h3 class="text-muted mb-3">Chưa có đơn hàng nào</h3>
                <p class="text-muted mb-4">Bạn chưa có đơn hàng nào. Hãy khám phá sản phẩm và đặt hàng ngay!</p>
                <div class="d-grid gap-2 d-md-block">
                    <a href="/webbanhang/user/products" class="btn btn-primary btn-lg">
                        <i class="bi bi-shop me-2"></i>Mua sắm ngay
                    </a>
                    <!-- Categories temporarily removed for simplified UX -->
                    <!-- <a href="/webbanhang/category" class="btn btn-outline-secondary btn-lg">
                        <i class="bi bi-tags me-2"></i>Xem danh mục
                    </a> -->
                </div>
            </div>
        </div>
    </div>
<?php else: ?>
    <!-- Orders List -->
    <div class="row">
        <?php foreach ($orders as $order): ?>
            <div class="col-12 mb-4 order-item" data-status="<?php echo $order->order_status; ?>">
                <div class="card shadow-sm border-0">
                    <!-- Order Header -->
                    <div class="card-header bg-light d-flex justify-content-between align-items-center">
                        <div class="d-flex align-items-center">
                            <h6 class="mb-0 me-3">
                                <i class="bi bi-receipt-cutoff me-2"></i>
                                Đơn hàng #<?php echo str_pad($order->id, 5, '0', STR_PAD_LEFT); ?>
                            </h6>
                            <?php
                            $statusClass = '';
                            $statusText = '';
                            $statusIcon = '';
                            switch($order->order_status) {
                                case 'unpaid':
                                    $statusClass = 'bg-warning text-dark';
                                    $statusText = 'Chưa thanh toán';
                                    $statusIcon = 'bi-exclamation-triangle';
                                    break;
                                case 'paid':
                                    $statusClass = 'bg-success';
                                    $statusText = 'Đã thanh toán';
                                    $statusIcon = 'bi-check-circle';
                                    break;
                                case 'pending':
                                    $statusClass = 'bg-secondary';
                                    $statusText = 'Đang xử lý';
                                    $statusIcon = 'bi-clock';
                                    break;
                                case 'cancelled':
                                    $statusClass = 'bg-danger';
                                    $statusText = 'Đã hủy';
                                    $statusIcon = 'bi-x-circle';
                                    break;
                                default:
                                    $statusClass = 'bg-secondary';
                                    $statusText = 'Không xác định';
                                    $statusIcon = 'bi-question-circle';
                            }
                            ?>
                            <span class="badge <?php echo $statusClass; ?>">
                                <i class="bi <?php echo $statusIcon; ?> me-1"></i>
                                <?php echo $statusText; ?>
                            </span>
                        </div>
                        <div class="text-end">
                            <small class="text-muted">
                                <i class="bi bi-calendar me-1"></i>
                                <?php echo date('d/m/Y H:i', strtotime($order->created_at)); ?>
                            </small>
                        </div>
                    </div>

                    <div class="card-body">
                        <div class="row">
                            <!-- Order Info -->
                            <div class="col-md-8">
                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <h6 class="text-muted small mb-1">Thông tin nhận hàng:</h6>
                                        <div class="fw-bold"><?php echo htmlspecialchars($order->name, ENT_QUOTES, 'UTF-8'); ?></div>
                                        <div class="small text-muted">
                                            <i class="bi bi-telephone me-1"></i>
                                            <?php echo htmlspecialchars($order->phone, ENT_QUOTES, 'UTF-8'); ?>
                                        </div>
                                        <div class="small text-muted">
                                            <i class="bi bi-geo-alt me-1"></i>
                                            <?php echo htmlspecialchars($order->address, ENT_QUOTES, 'UTF-8'); ?>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <h6 class="text-muted small mb-1">Chi tiết đơn hàng:</h6>
                                        <div class="small">
                                            <div class="d-flex justify-content-between">
                                                <span>Số sản phẩm:</span>
                                                <span class="fw-bold"><?php echo $order->item_count; ?> sản phẩm</span>
                                            </div>
                                            <div class="d-flex justify-content-between">
                                                <span>Tổng tiền:</span>
                                                <span class="fw-bold text-primary">
                                                    <?php echo number_format($order->total_amount ?? 0, 0, ',', '.'); ?> đ
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Order Progress -->
                                <div class="order-progress mb-3">
                                    <h6 class="text-muted small mb-2">Trạng thái đơn hàng:</h6>
                                    <div class="progress-container">
                                        <div class="progress" style="height: 8px;">
                                            <?php
                                            $progressPercent = 25;
                                            switch($order->order_status) {
                                                case 'unpaid': $progressPercent = 25; break;
                                                case 'paid': $progressPercent = 50; break;
                                                case 'pending': $progressPercent = 75; break;
                                                case 'completed': $progressPercent = 100; break;
                                                case 'cancelled': $progressPercent = 0; break;
                                            }
                                            ?>
                                            <div class="progress-bar" style="width: <?php echo $progressPercent; ?>%"></div>
                                        </div>
                                        <div class="d-flex justify-content-between mt-2">
                                            <small class="text-muted">Đặt hàng</small>
                                            <small class="text-muted">Xác nhận</small>
                                            <small class="text-muted">Đang giao</small>
                                            <small class="text-muted">Hoàn thành</small>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Order Actions -->
                            <div class="col-md-4 text-end">
                                <div class="d-grid gap-2">
                                    <a href="/webbanhang/user/orders/view/<?php echo $order->id; ?>" 
                                       class="btn btn-outline-primary btn-sm">
                                        <i class="bi bi-eye me-1"></i>Xem chi tiết
                                    </a>
                                    
                                    <?php if ($order->order_status === 'unpaid'): ?>
                                        <button class="btn btn-success btn-sm" onclick="payOrder(<?php echo $order->id; ?>)">
                                            <i class="bi bi-credit-card me-1"></i>Thanh toán ngay
                                        </button>
                                        <button class="btn btn-outline-danger btn-sm" 
                                                onclick="cancelOrder(<?php echo $order->id; ?>)">
                                            <i class="bi bi-x-circle me-1"></i>Hủy đơn hàng
                                        </button>
                                    <?php elseif ($order->order_status === 'paid'): ?>
                                        <button class="btn btn-outline-info btn-sm" onclick="trackOrder(<?php echo $order->id; ?>)">
                                            <i class="bi bi-truck me-1"></i>Theo dõi đơn hàng
                                        </button>
                                    <?php elseif ($order->order_status === 'completed'): ?>
                                        <button class="btn btn-outline-warning btn-sm" onclick="rateOrder(<?php echo $order->id; ?>)">
                                            <i class="bi bi-star me-1"></i>Đánh giá
                                        </button>
                                        <button class="btn btn-outline-primary btn-sm" onclick="reorder(<?php echo $order->id; ?>)">
                                            <i class="bi bi-arrow-repeat me-1"></i>Mua lại
                                        </button>
                                    <?php endif; ?>
                                    
                                    <button class="btn btn-outline-secondary btn-sm" onclick="downloadInvoice(<?php echo $order->id; ?>)">
                                        <i class="bi bi-download me-1"></i>Tải hóa đơn
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Order Items Preview -->
                    <?php if (isset($order->items) && !empty($order->items)): ?>
                        <div class="card-footer bg-light">
                            <div class="d-flex align-items-center">
                                <span class="text-muted me-3">Sản phẩm:</span>
                                <div class="d-flex flex-wrap gap-2">
                                    <?php 
                                    $displayCount = 0;
                                    foreach ($order->items as $item): 
                                        if ($displayCount >= 3) break;
                                        $displayCount++;
                                    ?>
                                        <div class="d-flex align-items-center bg-white rounded p-2">
                                            <?php 
                                            $imagePath = $item->image ? '/webbanhang/public/uploads/products/' . $item->image : '/webbanhang/public/images/no-image.jpg';
                                            ?>
                                            <img src="<?php echo $imagePath; ?>" 
                                                 class="me-2" 
                                                 style="width: 30px; height: 30px; object-fit: cover; border-radius: 4px;">
                                            <small>
                                                <?php echo htmlspecialchars(substr($item->name, 0, 20), ENT_QUOTES, 'UTF-8'); ?>
                                                <?php echo strlen($item->name) > 20 ? '...' : ''; ?>
                                                <span class="text-muted">(x<?php echo $item->quantity; ?>)</span>
                                            </small>
                                        </div>
                                    <?php endforeach; ?>
                                    <?php if (count($order->items) > 3): ?>
                                        <div class="d-flex align-items-center">
                                            <small class="text-muted">và <?php echo count($order->items) - 3; ?> sản phẩm khác</small>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        <?php endforeach; ?>
    </div>

    <!-- Pagination -->
    <nav aria-label="Order pagination">
        <ul class="pagination justify-content-center">
            <li class="page-item disabled">
                <a class="page-link" href="#" tabindex="-1">Trước</a>
            </li>
            <li class="page-item active">
                <a class="page-link" href="#">1</a>
            </li>
            <li class="page-item">
                <a class="page-link" href="#">2</a>
            </li>
            <li class="page-item">
                <a class="page-link" href="#">3</a>
            </li>
            <li class="page-item">
                <a class="page-link" href="#">Sau</a>
            </li>
        </ul>
    </nav>
<?php endif; ?>

<style>
.order-item {
    transition: transform 0.2s ease;
}

.order-item:hover {
    transform: translateY(-2px);
}

.progress-container {
    position: relative;
}

.card {
    border: none;
    border-radius: 12px;
}

.btn {
    border-radius: 6px;
}

.badge {
    font-size: 0.8rem;
    padding: 6px 12px;
}
</style>

<script>
// Status filter functionality
document.addEventListener('DOMContentLoaded', function() {
    const filterRadios = document.querySelectorAll('input[name="statusFilter"]');
    
    filterRadios.forEach(radio => {
        radio.addEventListener('change', function() {
            const selectedStatus = this.value;
            const orderItems = document.querySelectorAll('.order-item');
            
            orderItems.forEach(item => {
                const orderStatus = item.getAttribute('data-status');
                
                if (selectedStatus === 'all' || orderStatus === selectedStatus) {
                    item.style.display = 'block';
                    item.style.opacity = '1';
                } else {
                    item.style.display = 'none';
                    item.style.opacity = '0';
                }
            });
        });
    });
});

// Order actions
function payOrder(orderId) {
    if (confirm('Bạn muốn thanh toán đơn hàng này?')) {
        window.location.href = `/webbanhang/user/orders/payment/${orderId}`;
    }
}

function cancelOrder(orderId) {
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

function trackOrder(orderId) {
    window.location.href = `/webbanhang/user/orders/track/${orderId}`;
}

function rateOrder(orderId) {
    window.location.href = `/webbanhang/user/orders/rate/${orderId}`;
}

function reorder(orderId) {
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

function downloadInvoice(orderId) {
    window.open(`/webbanhang/user/orders/invoice/${orderId}`, '_blank');
}
</script>

<?php include_once 'app/views/shares/footer.php'; ?> 
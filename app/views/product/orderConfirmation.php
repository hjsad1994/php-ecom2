<?php include 'app/views/shares/header.php'; ?>

<div class="text-center py-5">
    <div class="mb-4">
        <i class="bi bi-check-circle-fill text-success" style="font-size: 5rem;"></i>
    </div>
    
    <h1 class="display-4 text-success mb-3">Đặt hàng thành công!</h1>
    <p class="lead mb-4">Cảm ơn bạn đã đặt hàng. Đơn hàng của bạn đã được xử lý thành công.</p>
    
    <?php if (!empty($orderDetails)): ?>
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card shadow-sm">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0">
                        <i class="bi bi-receipt me-2"></i>
                        Chi tiết đơn hàng #<?php echo str_pad($orderDetails[0]->id, 5, '0', STR_PAD_LEFT); ?>
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <h6 class="fw-bold">Thông tin khách hàng:</h6>
                            <p class="mb-1"><strong>Họ tên:</strong> <?php echo htmlspecialchars($orderDetails[0]->name, ENT_QUOTES, 'UTF-8'); ?></p>
                            <p class="mb-1"><strong>Số điện thoại:</strong> <?php echo htmlspecialchars($orderDetails[0]->phone, ENT_QUOTES, 'UTF-8'); ?></p>
                            <p class="mb-1"><strong>Địa chỉ:</strong> <?php echo htmlspecialchars($orderDetails[0]->address, ENT_QUOTES, 'UTF-8'); ?></p>
                        </div>
                        <div class="col-md-6">
                            <h6 class="fw-bold">Thông tin đơn hàng:</h6>
                            <p class="mb-1"><strong>Ngày đặt:</strong> <?php echo date('d/m/Y H:i', strtotime($orderDetails[0]->created_at)); ?></p>
                            <p class="mb-1"><strong>Trạng thái:</strong> 
                                <?php
                                $statusClass = '';
                                $statusText = '';
                                $statusIcon = '';
                                switch($orderDetails[0]->order_status ?? 'paid') {
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
                                        $statusClass = 'bg-success';
                                        $statusText = 'Đã thanh toán';
                                        $statusIcon = 'bi-check-circle';
                                }
                                ?>
                                <span class="badge <?php echo $statusClass; ?> fs-6">
                                    <i class="bi <?php echo $statusIcon; ?> me-1"></i>
                                    <?php echo $statusText; ?>
                                </span>
                            </p>
                            <?php if (isset($orderDetails[0]->total_amount) && $orderDetails[0]->total_amount > 0): ?>
                                <p class="mb-1"><strong>Tổng đơn hàng:</strong> 
                                    <span class="fw-bold text-success">
                                        <?php echo number_format($orderDetails[0]->total_amount, 0, ',', '.'); ?> đ
                                    </span>
                                </p>
                            <?php endif; ?>
                        </div>
                    </div>
                    
                    <h6 class="fw-bold mb-3">Sản phẩm đã đặt:</h6>
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Sản phẩm</th>
                                    <th class="text-center">Số lượng</th>
                                    <th class="text-end">Đơn giá</th>
                                    <th class="text-end">Thành tiền</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php 
                                $calculated_total = 0;
                                foreach ($orderDetails as $detail): 
                                    $subtotal = $detail->price * $detail->quantity;
                                    $calculated_total += $subtotal;
                                ?>
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <i class="bi bi-box-seam me-2 text-primary"></i>
                                            <?php echo htmlspecialchars($detail->product_name, ENT_QUOTES, 'UTF-8'); ?>
                                        </div>
                                    </td>
                                    <td class="text-center">
                                        <span class="badge bg-info"><?php echo $detail->quantity; ?></span>
                                    </td>
                                    <td class="text-end"><?php echo number_format($detail->price, 0, ',', '.'); ?> đ</td>
                                    <td class="text-end fw-bold"><?php echo number_format($subtotal, 0, ',', '.'); ?> đ</td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                            <tfoot>
                                <?php if (isset($orderDetails[0]->voucher_discount) && $orderDetails[0]->voucher_discount > 0): ?>
                                <tr>
                                    <th colspan="3" class="text-end">Tạm tính:</th>
                                    <th class="text-end"><?php echo number_format($calculated_total, 0, ',', '.'); ?> đ</th>
                                </tr>
                                <tr class="text-success">
                                    <th colspan="3" class="text-end">
                                        <i class="bi bi-ticket-perforated me-1"></i>
                                        Voucher (<?php echo $orderDetails[0]->voucher_code; ?>):
                                    </th>
                                    <th class="text-end">-<?php echo number_format($orderDetails[0]->voucher_discount, 0, ',', '.'); ?> đ</th>
                                </tr>
                                <?php endif; ?>
                                <tr class="table-success">
                                    <th colspan="3" class="text-end fs-5">Tổng cộng:</th>
                                    <th class="text-end fs-5 fw-bold text-success">
                                        <?php echo number_format($orderDetails[0]->total_amount, 0, ',', '.'); ?> đ
                                    </th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                    
                    <!-- Order Status Information -->
                    <div class="alert alert-success mt-4" role="alert">
                        <div class="d-flex align-items-center">
                            <i class="bi bi-info-circle-fill me-2 fs-4"></i>
                            <div>
                                <h6 class="alert-heading mb-1">Thông tin quan trọng:</h6>
                                <p class="mb-0">
                                    <?php if (($orderDetails[0]->order_status ?? 'paid') === 'paid'): ?>
                                        Đơn hàng của bạn đã được thanh toán thành công và sẽ được xử lý trong thời gian sớm nhất.
                                    <?php elseif (($orderDetails[0]->order_status ?? 'paid') === 'pending'): ?>
                                        Đơn hàng của bạn đang được xử lý. Chúng tôi sẽ liên hệ với bạn sớm nhất có thể.
                                    <?php else: ?>
                                        Vui lòng liên hệ với chúng tôi để hoàn tất thanh toán cho đơn hàng này.
                                    <?php endif; ?>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php endif; ?>
    
    <div class="mt-4">
        <a href="/webbanhang/Product" class="btn btn-primary btn-lg me-3">
            <i class="bi bi-shop me-2"></i>Tiếp tục mua sắm
        </a>
        <?php if (!empty($orderDetails)): ?>
            <a href="/webbanhang/Product/orders" class="btn btn-outline-info btn-lg me-3">
                <i class="bi bi-list-check me-2"></i>Xem tất cả đơn hàng
            </a>
        <?php endif; ?>
        <a href="javascript:window.print()" class="btn btn-outline-secondary btn-lg">
            <i class="bi bi-printer me-2"></i>In đơn hàng
        </a>
    </div>
</div>

<style>
@media print {
    .btn, .navbar, footer, .alert {
        display: none !important;
    }
    
    .card {
        border: 2px solid #000 !important;
    }
    
    .badge {
        border: 1px solid #000 !important;
        color: #000 !important;
        background: transparent !important;
    }
}
</style>

<?php include 'app/views/shares/footer.php'; ?>
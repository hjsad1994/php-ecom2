<?php include 'app/views/shares/header.php'; ?>

<nav aria-label="breadcrumb" class="mb-4">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="/webbanhang/Product" class="text-decoration-none">Sản phẩm</a></li>
        <li class="breadcrumb-item"><a href="/webbanhang/Product/orders" class="text-decoration-none">Đơn hàng</a></li>
        <li class="breadcrumb-item active" aria-current="page">Chi tiết đơn hàng</li>
    </ol>
</nav>

<?php if (!empty($orderDetails)): ?>
<div class="row">
    <div class="col-lg-8">
        <div class="card shadow-sm">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0">
                    <i class="bi bi-receipt me-2"></i>
                    Chi tiết đơn hàng #<?php echo str_pad($orderDetails[0]->id, 5, '0', STR_PAD_LEFT); ?>
                </h5>
            </div>
            <div class="card-body">
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
                            $total = 0;
                            foreach ($orderDetails as $detail): 
                                $subtotal = $detail->price * $detail->quantity;
                                $total += $subtotal;
                            ?>
                            <tr>
                                <td><?php echo htmlspecialchars($detail->product_name, ENT_QUOTES, 'UTF-8'); ?></td>
                                <td class="text-center"><?php echo $detail->quantity; ?></td>
                                <td class="text-end"><?php echo number_format($detail->price, 0, ',', '.'); ?> đ</td>
                                <td class="text-end"><?php echo number_format($subtotal, 0, ',', '.'); ?> đ</td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                        <tfoot>
                            <tr class="table-success">
                                <th colspan="3" class="text-end">Tổng cộng:</th>
                                <th class="text-end"><?php echo number_format($total, 0, ',', '.'); ?> đ</th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-lg-4">
        <div class="card shadow-sm">
            <div class="card-header bg-info text-white">
                <h5 class="mb-0"><i class="bi bi-person me-2"></i>Thông tin đơn hàng</h5>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <h6 class="fw-bold">Khách hàng:</h6>
                    <p class="mb-1"><?php echo htmlspecialchars($orderDetails[0]->name, ENT_QUOTES, 'UTF-8'); ?></p>
                </div>
                
                <div class="mb-3">
                    <h6 class="fw-bold">Số điện thoại:</h6>
                    <p class="mb-1"><?php echo htmlspecialchars($orderDetails[0]->phone, ENT_QUOTES, 'UTF-8'); ?></p>
                </div>
                
                <div class="mb-3">
                    <h6 class="fw-bold">Địa chỉ:</h6>
                    <p class="mb-1"><?php echo htmlspecialchars($orderDetails[0]->address, ENT_QUOTES, 'UTF-8'); ?></p>
                </div>
                
                <div class="mb-3">
                    <h6 class="fw-bold">Ngày đặt:</h6>
                    <p class="mb-1"><?php echo date('d/m/Y H:i:s', strtotime($orderDetails[0]->created_at)); ?></p>
                </div>
                
                <div class="mb-3">
                    <h6 class="fw-bold">Trạng thái:</h6>
                    <?php
                    $statusClass = '';
                    $statusText = '';
                    switch($orderDetails[0]->order_status) {
                        case 'unpaid':
                            $statusClass = 'bg-warning text-dark';
                            $statusText = 'Chưa thanh toán';
                            break;
                        case 'paid':
                            $statusClass = 'bg-success';
                            $statusText = 'Đã thanh toán';
                            break;
                        case 'pending':
                            $statusClass = 'bg-secondary';
                            $statusText = 'Đang xử lý';
                            break;
                        case 'cancelled':
                            $statusClass = 'bg-danger';
                            $statusText = 'Đã hủy';
                            break;
                        default:
                            $statusClass = 'bg-secondary';
                            $statusText = 'Không xác định';
                    }
                    ?>
                    <span class="badge <?php echo $statusClass; ?> fs-6">
                        <?php echo $statusText; ?>
                    </span>
                </div>
                
                <hr>
                
                <div class="d-grid">
                    <a href="/webbanhang/Product/orders" class="btn btn-outline-primary">
                        <i class="bi bi-arrow-left me-2"></i>Quay lại danh sách
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
<?php endif; ?>

<?php include 'app/views/shares/footer.php'; ?>
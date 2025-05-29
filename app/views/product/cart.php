<?php include 'app/views/shares/header.php'; ?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="display-5 fw-bold"><i class="bi bi-cart me-2"></i>Giỏ hàng của bạn</h1>
    <a href="/webbanhang/Product" class="btn btn-outline-primary">
        <i class="bi bi-arrow-left me-2"></i>Tiếp tục mua sắm
    </a>
</div>

<?php if (!empty($cart)): ?>
    <div class="row">
        <div class="col-lg-8">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="bi bi-list-ul me-2"></i>Sản phẩm trong giỏ hàng</h5>
                </div>
                <div class="card-body p-0">
                    <?php foreach ($cart as $id => $item): ?>
                    <div class="d-flex align-items-center p-3 border-bottom">
                        <div class="flex-shrink-0 me-3">
                            <?php if (!empty($item['image'])): ?>
                                <img src="/webbanhang/public/uploads/<?php echo htmlspecialchars($item['image'], ENT_QUOTES, 'UTF-8'); ?>" 
                                     alt="<?php echo htmlspecialchars($item['name'], ENT_QUOTES, 'UTF-8'); ?>"
                                     class="img-thumbnail" style="width: 80px; height: 80px; object-fit: contain;">
                            <?php else: ?>
                                <div class="bg-light d-flex align-items-center justify-content-center" style="width: 80px; height: 80px;">
                                    <i class="bi bi-image text-secondary fs-3"></i>
                                </div>
                            <?php endif; ?>
                        </div>
                        <div class="flex-grow-1">
                            <h6 class="mb-1 fw-bold"><?php echo htmlspecialchars($item['name'], ENT_QUOTES, 'UTF-8'); ?></h6>
                            <p class="mb-1 text-primary fw-bold"><?php echo number_format($item['price'], 0, ',', '.'); ?> đ</p>
                            <div class="d-flex align-items-center">
                                <form method="POST" action="/webbanhang/Product/updateCartQuantity" class="d-flex align-items-center me-3">
                                    <input type="hidden" name="product_id" value="<?php echo $id; ?>">
                                    <label for="quantity_<?php echo $id; ?>" class="form-label me-2 mb-0">Số lượng:</label>
                                    <input type="number" id="quantity_<?php echo $id; ?>" name="quantity" 
                                           value="<?php echo $item['quantity']; ?>" min="1" max="99" 
                                           class="form-control form-control-sm me-2" style="width: 70px;"
                                           onchange="this.form.submit()">
                                </form>
                                <a href="/webbanhang/Product/removeFromCart/<?php echo $id; ?>" 
                                   class="btn btn-outline-danger btn-sm"
                                   onclick="return confirm('Bạn có chắc chắn muốn xóa sản phẩm này khỏi giỏ hàng?');">
                                    <i class="bi bi-trash"></i> Xóa
                                </a>
                            </div>
                        </div>
                        <div class="text-end">
                            <p class="mb-0 fw-bold text-success">
                                <?php echo number_format($item['price'] * $item['quantity'], 0, ',', '.'); ?> đ
                            </p>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
        
        <div class="col-lg-4">
            <div class="card shadow-sm">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0"><i class="bi bi-receipt me-2"></i>Tóm tắt đơn hàng</h5>
                </div>
                <div class="card-body">
                    <div class="d-flex justify-content-between mb-2">
                        <span>Tổng sản phẩm:</span>
                        <span><?php echo count($cart); ?> sản phẩm</span>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span>Tổng số lượng:</span>
                        <span><?php echo array_sum(array_column($cart, 'quantity')); ?> cái</span>
                    </div>
                    <hr>
                    
                    <!-- Voucher Section -->
                    <div class="mb-3">
                        <h6 class="fw-bold">Mã giảm giá:</h6>
                        <?php if (isset($_SESSION['applied_voucher'])): ?>
                            <div class="alert alert-success d-flex justify-content-between align-items-center p-2">
                                <span>
                                    <i class="bi bi-ticket-perforated me-1"></i>
                                    <?php echo $_SESSION['applied_voucher']['code']; ?>
                                    (-<?php echo number_format($_SESSION['applied_voucher']['discount'], 0, ',', '.'); ?> đ)
                                </span>
                                <a href="/webbanhang/Voucher/removeVoucher" class="btn btn-sm btn-outline-danger">
                                    <i class="bi bi-x"></i>
                                </a>
                            </div>
                        <?php else: ?>
                            <div class="input-group">
                                <input type="text" class="form-control" id="voucher_code" placeholder="Nhập mã voucher">
                                <button class="btn btn-outline-primary" type="button" onclick="applyVoucher()">
                                    Áp dụng
                                </button>
                            </div>
                            <div id="voucher_message" class="mt-2"></div>
                        <?php endif; ?>
                    </div>
                    
                    <?php 
                    $discount = isset($_SESSION['applied_voucher']) ? $_SESSION['applied_voucher']['discount'] : 0;
                    $finalTotal = $total - $discount;
                    ?>
                    
                    <?php if ($discount > 0): ?>
                    <div class="d-flex justify-content-between mb-2">
                        <span>Tạm tính:</span>
                        <span><?php echo number_format($total, 0, ',', '.'); ?> đ</span>
                    </div>
                    <div class="d-flex justify-content-between mb-2 text-success">
                        <span>Giảm giá:</span>
                        <span>-<?php echo number_format($discount, 0, ',', '.'); ?> đ</span>
                    </div>
                    <hr>
                    <?php endif; ?>
                    
                    <div class="d-flex justify-content-between">
                        <span class="fw-bold fs-5">Tổng tiền:</span>
                        <span class="fw-bold fs-5 text-success"><?php echo number_format($finalTotal, 0, ',', '.'); ?> đ</span>
                    </div>
                    <div class="d-grid">
                        <a href="/webbanhang/Product/checkout" class="btn btn-success btn-lg">
                            <i class="bi bi-credit-card me-2"></i>Thanh toán
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php else: ?>
    <div class="text-center py-5">
        <i class="bi bi-cart-x display-1 text-muted mb-3"></i>
        <h3 class="text-muted">Giỏ hàng của bạn đang trống</h3>
        <p class="text-muted mb-4">Hãy thêm một số sản phẩm vào giỏ hàng để tiếp tục mua sắm.</p>
        <a href="/webbanhang/Product" class="btn btn-primary btn-lg">
            <i class="bi bi-shop me-2"></i>Bắt đầu mua sắm
        </a>
    </div>
<?php endif; ?>

<?php include 'app/views/shares/footer.php'; ?>

<script>
function applyVoucher() {
    const voucherCode = document.getElementById('voucher_code').value.trim();
    const messageDiv = document.getElementById('voucher_message');
    
    if (!voucherCode) {
        messageDiv.innerHTML = '<div class="alert alert-warning p-2">Vui lòng nhập mã voucher</div>';
        return;
    }
    
    const formData = new FormData();
    formData.append('voucher_code', voucherCode);
    formData.append('cart_total', <?php echo $total; ?>);
    
    fetch('/webbanhang/Voucher/validateVoucherCode', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            location.reload();
        } else {
            messageDiv.innerHTML = '<div class="alert alert-danger p-2">' + data.message + '</div>';
        }
    })
    .catch(error => {
        messageDiv.innerHTML = '<div class="alert alert-danger p-2">Có lỗi xảy ra, vui lòng thử lại</div>';
    });
}
</script>
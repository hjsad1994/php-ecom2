<?php include 'app/views/shares/header.php'; ?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="display-5 fw-bold"><i class="bi bi-credit-card me-2"></i>Thanh toán</h1>
    <a href="/webbanhang/Product/cart" class="btn btn-outline-secondary">
        <i class="bi bi-arrow-left me-2"></i>Quay lại giỏ hàng
    </a>
</div>

<?php if (!empty($errors)): ?>
<div class="alert alert-danger">
    <h6 class="alert-heading"><i class="bi bi-exclamation-triangle me-2"></i>Có lỗi xảy ra:</h6>
    <ul class="mb-0">
        <?php foreach ($errors as $error): ?>
            <li><?php echo htmlspecialchars($error, ENT_QUOTES, 'UTF-8'); ?></li>
        <?php endforeach; ?>
    </ul>
</div>
<?php endif; ?>

<div class="row">
    <div class="col-lg-8">
        <div class="card shadow-sm">
            <div class="card-header">
                <h5 class="mb-0"><i class="bi bi-person me-2"></i>Thông tin khách hàng</h5>
            </div>
            <div class="card-body">
                <form method="POST" action="/webbanhang/Product/processCheckout" class="needs-validation" novalidate>
                    <div class="mb-3">
                        <label for="name" class="form-label">Họ và tên: <span class="text-danger">*</span></label>
                        <input type="text" id="name" name="name" class="form-control" 
                               value="<?php echo isset($_POST['name']) ? htmlspecialchars($_POST['name'], ENT_QUOTES, 'UTF-8') : ''; ?>" required>
                        <div class="invalid-feedback">Vui lòng nhập họ và tên.</div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="phone" class="form-label">Số điện thoại: <span class="text-danger">*</span></label>
                        <input type="tel" id="phone" name="phone" class="form-control" 
                               value="<?php echo isset($_POST['phone']) ? htmlspecialchars($_POST['phone'], ENT_QUOTES, 'UTF-8') : ''; ?>" required>
                        <div class="invalid-feedback">Vui lòng nhập số điện thoại.</div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="address" class="form-label">Địa chỉ giao hàng: <span class="text-danger">*</span></label>
                        <textarea id="address" name="address" class="form-control" rows="3" required><?php echo isset($_POST['address']) ? htmlspecialchars($_POST['address'], ENT_QUOTES, 'UTF-8') : ''; ?></textarea>
                        <div class="invalid-feedback">Vui lòng nhập địa chỉ giao hàng.</div>
                    </div>
                    
                    <div class="d-grid">
                        <button type="submit" class="btn btn-success btn-lg">
                            <i class="bi bi-check-circle me-2"></i>Xác nhận đặt hàng
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <div class="col-lg-4">
        <div class="card shadow-sm">
            <div class="card-header bg-info text-white">
                <h5 class="mb-0"><i class="bi bi-list-check me-2"></i>Đơn hàng của bạn</h5>
            </div>
            <div class="card-body">
                <?php if (!empty($cart)): ?>
                    <?php foreach ($cart as $id => $item): ?>
                    <div class="d-flex justify-content-between align-items-center mb-2 pb-2 border-bottom">
                        <div>
                            <h6 class="mb-0"><?php echo htmlspecialchars($item['name'], ENT_QUOTES, 'UTF-8'); ?></h6>
                            <small class="text-muted">Số lượng: <?php echo $item['quantity']; ?></small>
                        </div>
                        <span class="fw-bold"><?php echo number_format($item['price'] * $item['quantity'], 0, ',', '.'); ?> đ</span>
                    </div>
                    <?php endforeach; ?>
                    
                    <hr>
                    <div class="d-flex justify-content-between mb-2">
                        <span>Tổng sản phẩm:</span>
                        <span><?php echo count($cart); ?></span>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span>Phí vận chuyển:</span>
                        <span class="text-success">Miễn phí</span>
                    </div>
                    <hr>
                    <div class="d-flex justify-content-between">
                        <span class="fw-bold fs-5">Tổng cộng:</span>
                        <span class="fw-bold fs-5 text-success"><?php echo number_format($total, 0, ',', '.'); ?> đ</span>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<script>
// Form validation
(function() {
    'use strict';
    var forms = document.querySelectorAll('.needs-validation');
    Array.prototype.slice.call(forms).forEach(function(form) {
        form.addEventListener('submit', function(event) {
            if (!form.checkValidity()) {
                event.preventDefault();
                event.stopPropagation();
            }
            form.classList.add('was-validated');
        }, false);
    });
})();
</script>

<?php include 'app/views/shares/footer.php'; ?>
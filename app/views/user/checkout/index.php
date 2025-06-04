<?php include_once 'app/views/shares/header.php'; ?>

<div class="row mb-4">
    <div class="col-md-8">
        <h1 class="display-6 fw-bold text-primary">
            <i class="bi bi-bag-check me-2"></i>Đặt hàng
        </h1>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="/webbanhang/" class="text-decoration-none">Trang chủ</a></li>
                <li class="breadcrumb-item"><a href="/webbanhang/user/cart" class="text-decoration-none">Giỏ hàng</a></li>
                <li class="breadcrumb-item active">Đặt hàng</li>
            </ol>
        </nav>
    </div>
    <div class="col-md-4 text-end">
        <a href="/webbanhang/user/cart" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left me-2"></i>Quay lại giỏ hàng
        </a>
    </div>
</div>

<form id="checkoutForm" action="/webbanhang/order/store" method="POST">
    <div class="row">
        <!-- Checkout Form -->
        <div class="col-lg-8">
            <!-- Customer Information -->
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">
                        <i class="bi bi-person-fill me-2"></i>Thông tin khách hàng
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-bold">Họ và tên <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="name" required 
                                       value="<?php echo htmlspecialchars(SessionHelper::getUsername() ?? '', ENT_QUOTES, 'UTF-8'); ?>">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-bold">Số điện thoại <span class="text-danger">*</span></label>
                                <input type="tel" class="form-control" name="phone" required>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-bold">Email</label>
                                <input type="email" class="form-control" name="email" 
                                       value="<?php echo htmlspecialchars(SessionHelper::getUserEmail() ?? '', ENT_QUOTES, 'UTF-8'); ?>">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-bold">Tỉnh/Thành phố <span class="text-danger">*</span></label>
                                <select class="form-select" name="province" required>
                                    <option value="">Chọn tỉnh/thành phố</option>
                                    <option value="Ho Chi Minh">TP. Hồ Chí Minh</option>
                                    <option value="Ha Noi">Hà Nội</option>
                                    <option value="Da Nang">Đà Nẵng</option>
                                    <option value="Can Tho">Cần Thơ</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Địa chỉ chi tiết <span class="text-danger">*</span></label>
                        <textarea class="form-control" name="address" rows="2" required 
                                  placeholder="Số nhà, tên đường, phường/xã, quận/huyện..."></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Ghi chú đơn hàng</label>
                        <textarea class="form-control" name="notes" rows="2" 
                                  placeholder="Ghi chú về đơn hàng, ví dụ: thời gian hay chỉ dẫn địa điểm giao hàng chi tiết hơn."></textarea>
                    </div>
                </div>
            </div>

            <!-- Shipping Method -->
            <div class="card shadow-sm">
                <div class="card-header bg-info text-white">
                    <h5 class="mb-0">
                        <i class="bi bi-truck me-2"></i>Phương thức vận chuyển
                    </h5>
                </div>
                <div class="card-body">
                    <div class="form-check mb-3">
                        <input class="form-check-input" type="radio" name="shipping_method" id="standard" value="standard" checked>
                        <label class="form-check-label" for="standard">
                            <strong>Giao hàng tiêu chuẩn - Miễn phí</strong>
                            <br><small class="text-muted">Giao hàng trong 3-5 ngày làm việc</small>
                        </label>
                    </div>
                    <div class="form-check mb-3">
                        <input class="form-check-input" type="radio" name="shipping_method" id="express" value="express">
                        <label class="form-check-label" for="express">
                            <strong>Giao hàng nhanh - 30.000đ</strong>
                            <br><small class="text-muted">Giao hàng trong 1-2 ngày làm việc</small>
                        </label>
                    </div>
                    <div class="form-check mb-3">
                        <input class="form-check-input" type="radio" name="shipping_method" id="same_day" value="same_day">
                        <label class="form-check-label" for="same_day">
                            <strong>Giao hàng trong ngày - 50.000đ</strong>
                            <br><small class="text-muted">Chỉ áp dụng trong nội thành TP.HCM</small>
                        </label>
                    </div>
                </div>
            </div>

            <!-- Voucher Code Section (only show if no voucher applied) -->
            <?php if (!$appliedVoucher): ?>
            <div class="card shadow-sm mt-4">
                <div class="card-header bg-warning text-dark">
                    <h5 class="mb-0">
                        <i class="bi bi-tag me-2"></i>Mã giảm giá
                    </h5>
                </div>
                <div class="card-body">
                    <div class="alert alert-info">
                        <i class="bi bi-info-circle me-2"></i>
                        Bạn có thể áp dụng mã giảm giá trong giỏ hàng trước khi thanh toán.
                        <a href="/webbanhang/user/cart" class="alert-link">Quay lại giỏ hàng</a>
                    </div>
                </div>
            </div>
            <?php else: ?>
            <div class="card shadow-sm mt-4">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0">
                        <i class="bi bi-check-circle me-2"></i>Mã giảm giá đã áp dụng
                    </h5>
                </div>
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="mb-1"><?php echo htmlspecialchars($appliedVoucher['code'], ENT_QUOTES, 'UTF-8'); ?></h6>
                            <small class="text-muted"><?php echo htmlspecialchars($appliedVoucher['name'] ?? '', ENT_QUOTES, 'UTF-8'); ?></small>
                        </div>
                        <div class="text-end">
                            <div class="h6 text-success mb-0">-<?php echo number_format($appliedVoucher['discount'], 0, ',', '.'); ?> đ</div>
                            <a href="/webbanhang/user/cart" class="btn btn-sm btn-outline-secondary">Thay đổi</a>
                        </div>
                    </div>
                </div>
            </div>
            <?php endif; ?>
        </div>

        <!-- Order Summary -->
        <div class="col-lg-4">
            <div class="sticky-top" style="top: 2rem;">
                <div class="card shadow-sm">
                    <div class="card-header bg-warning">
                        <h5 class="mb-0">
                            <i class="bi bi-receipt me-2"></i>Đơn hàng của bạn
                        </h5>
                    </div>
                    <div class="card-body">
                        <!-- Order Items -->
                        <div class="mb-3">
                            <?php 
                            $subtotal = 0;
                            foreach ($cartItems as $item): 
                                $subtotal += $item->subtotal;
                            ?>
                                <div class="d-flex align-items-center mb-3 pb-3 border-bottom">
                                    <img src="<?php echo $item->image ? '/webbanhang/public/uploads/products/' . $item->image : '/webbanhang/public/images/no-image.jpg'; ?>" 
                                         class="me-3" style="width: 50px; height: 50px; object-fit: cover; border-radius: 8px;">
                                    <div class="flex-grow-1">
                                        <h6 class="mb-1"><?php echo htmlspecialchars($item->name, ENT_QUOTES, 'UTF-8'); ?></h6>
                                        <small class="text-muted">Số lượng: <?php echo $item->quantity; ?></small>
                                    </div>
                                    <div class="text-end">
                                        <div class="fw-bold"><?php echo number_format($item->subtotal, 0, ',', '.'); ?> đ</div>
                                    </div>
                                </div>
                                <!-- Hidden inputs for form submission -->
                                <input type="hidden" name="products[<?php echo $item->id; ?>][id]" value="<?php echo $item->id; ?>">
                                <input type="hidden" name="products[<?php echo $item->id; ?>][quantity]" value="<?php echo $item->quantity; ?>">
                                <input type="hidden" name="products[<?php echo $item->id; ?>][price]" value="<?php echo $item->price; ?>">
                            <?php endforeach; ?>
                        </div>

                        <!-- Order Summary -->
                        <div class="border-top pt-3">
                            <?php 
                            $originalTotal = $subtotal;
                            $discountAmount = 0;
                            $finalTotal = $subtotal;
                            $appliedVoucher = $_SESSION['applied_voucher'] ?? null;
                            
                            if ($appliedVoucher) {
                                $discountAmount = $appliedVoucher['discount'];
                                $finalTotal = $appliedVoucher['final_total'];
                            }
                            ?>
                            
                            <div class="d-flex justify-content-between mb-2">
                                <span>Tạm tính:</span>
                                <span><?php echo number_format($originalTotal, 0, ',', '.'); ?> đ</span>
                            </div>
                            <div class="d-flex justify-content-between mb-2">
                                <span>Phí vận chuyển:</span>
                                <span class="text-success" id="shippingFee">Miễn phí</span>
                            </div>
                            <div class="d-flex justify-content-between mb-2">
                                <span>Giảm giá:</span>
                                <span class="text-danger" id="discountAmount">
                                    <?php if ($appliedVoucher): ?>
                                        -<?php echo number_format($discountAmount, 0, ',', '.'); ?> đ
                                        <small class="d-block text-muted">(<?php echo htmlspecialchars($appliedVoucher['code'], ENT_QUOTES, 'UTF-8'); ?>)</small>
                                    <?php else: ?>
                                        -0 đ
                                    <?php endif; ?>
                                </span>
                            </div>
                            <hr>
                            <div class="d-flex justify-content-between mb-3">
                                <span class="h6 fw-bold">Tổng cộng:</span>
                                <span class="h5 fw-bold text-primary" id="totalAmount"><?php echo number_format($finalTotal, 0, ',', '.'); ?> đ</span>
                            </div>

                            <!-- Hidden input for voucher if applied -->
                            <?php if ($appliedVoucher): ?>
                                <input type="hidden" name="voucher_code" value="<?php echo htmlspecialchars($appliedVoucher['code'], ENT_QUOTES, 'UTF-8'); ?>">
                            <?php endif; ?>

                            <!-- Order Button -->
                            <div class="d-grid">
                                <button type="submit" class="btn btn-primary btn-lg">
                                    <i class="bi bi-check-circle me-2"></i>Đặt hàng
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Security Info -->
                <div class="card shadow-sm mt-3">
                    <div class="card-body text-center">
                        <h6 class="card-title">
                            <i class="bi bi-shield-check text-success me-2"></i>Đặt hàng an toàn
                        </h6>
                        <div class="row text-center">
                            <div class="col-4">
                                <i class="bi bi-lock text-success fs-4"></i>
                                <small class="d-block">Bảo mật SSL</small>
                            </div>
                            <div class="col-4">
                                <i class="bi bi-arrow-return-left text-primary fs-4"></i>
                                <small class="d-block">Đổi trả 7 ngày</small>
                            </div>
                            <div class="col-4">
                                <i class="bi bi-headset text-info fs-4"></i>
                                <small class="d-block">Hỗ trợ 24/7</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>

<style>
.sticky-top {
    z-index: 1020;
}

.card {
    border: none;
    border-radius: 12px;
}

.form-check-input:checked {
    background-color: #0d6efd;
    border-color: #0d6efd;
}

.form-check-label {
    cursor: pointer;
}
</style>

<script>
// Update shipping fee and total when shipping method changes
document.querySelectorAll('input[name="shipping_method"]').forEach(radio => {
    radio.addEventListener('change', function() {
        const shippingFeeEl = document.getElementById('shippingFee');
        const totalAmountEl = document.getElementById('totalAmount');
        const subtotal = <?php echo $originalTotal; ?>;
        const discount = <?php echo $discountAmount; ?>;
        let shippingFee = 0;
        
        switch(this.value) {
            case 'standard':
                shippingFee = 0;
                shippingFeeEl.textContent = 'Miễn phí';
                shippingFeeEl.className = 'text-success';
                break;
            case 'express':
                shippingFee = 30000;
                shippingFeeEl.textContent = '30.000 đ';
                shippingFeeEl.className = 'text-warning';
                break;
            case 'same_day':
                shippingFee = 50000;
                shippingFeeEl.textContent = '50.000 đ';
                shippingFeeEl.className = 'text-danger';
                break;
        }
        
        const total = subtotal - discount + shippingFee;
        totalAmountEl.textContent = total.toLocaleString('vi-VN') + ' đ';
    });
});

// Form validation
document.getElementById('checkoutForm').addEventListener('submit', function(e) {
    console.log('=== CHECKOUT FORM SUBMIT DEBUG ===');
    
    // Debug form data
    const formData = new FormData(this);
    console.log('Form data:');
    for (let [key, value] of formData.entries()) {
        console.log(key + ': ' + value);
    }
    
    // Specifically check voucher code
    const voucherCode = this.querySelector('input[name="voucher_code"]').value;
    console.log('Voucher code: "' + voucherCode + '"');
    
    const requiredFields = this.querySelectorAll('input[required], select[required], textarea[required]');
    let hasError = false;
    
    requiredFields.forEach(field => {
        if (!field.value.trim()) {
            field.classList.add('is-invalid');
            hasError = true;
        } else {
            field.classList.remove('is-invalid');
        }
    });
    
    if (hasError) {
        e.preventDefault();
        alert('Vui lòng điền đầy đủ thông tin bắt buộc!');
        return false;
    }
    
    // Show loading state
    const submitBtn = this.querySelector('button[type="submit"]');
    submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Đang xử lý...';
    submitBtn.disabled = true;
    
    console.log('Form submission proceeding...');
});

// Phone number formatting
document.querySelector('input[name="phone"]').addEventListener('input', function() {
    let value = this.value.replace(/\D/g, '');
    if (value.length > 10) {
        value = value.substring(0, 10);
    }
    this.value = value;
});

// Province change handler
document.querySelector('select[name="province"]').addEventListener('change', function() {
    const sameDayRadio = document.getElementById('same_day');
    const sameDayLabel = document.querySelector('label[for="same_day"]');
    
    if (this.value === 'Ho Chi Minh') {
        sameDayRadio.disabled = false;
        sameDayLabel.style.opacity = '1';
    } else {
        sameDayRadio.disabled = true;
        sameDayRadio.checked = false;
        sameDayLabel.style.opacity = '0.5';
        
        // Reset to standard shipping if same day was selected
        document.getElementById('standard').checked = true;
        document.getElementById('standard').dispatchEvent(new Event('change'));
    }
});
</script>

<?php include_once 'app/views/shares/footer.php'; ?> 
<?php include_once 'app/views/shares/header.php'; ?>

<div class="container mt-4">
    <div class="row mb-4">
        <div class="col-12">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="/webbanhang/" class="text-decoration-none">Trang chủ</a></li>
                    <li class="breadcrumb-item"><a href="/webbanhang/user/orders" class="text-decoration-none">Đơn hàng</a></li>
                    <li class="breadcrumb-item active">Thanh toán</li>
                </ol>
            </nav>
        </div>
    </div>

    <div class="row mb-4">
        <div class="col-12">
            <h1 class="display-6 fw-bold text-primary">
                <i class="bi bi-credit-card me-2"></i>Thanh toán đơn hàng #<?php echo str_pad($order['id'], 5, '0', STR_PAD_LEFT); ?>
            </h1>
        </div>
    </div>

    <div class="row">
        <!-- Payment Methods -->
        <div class="col-md-8">
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0"><i class="bi bi-credit-card-fill me-2"></i>Chọn phương thức thanh toán</h5>
                </div>
                <div class="card-body">
                    <form id="paymentForm">
                        <input type="hidden" name="order_id" value="<?php echo $order['id']; ?>">
                        
                        <div class="row">
                            <!-- Bank Transfer -->
                            <div class="col-md-6 mb-3">
                                <div class="card payment-option">
                                    <div class="card-body text-center">
                                        <input type="radio" name="payment_method" value="bank_transfer" id="bank_transfer" checked>
                                        <label for="bank_transfer" class="d-block">
                                            <i class="bi bi-bank display-4 text-primary mb-3"></i>
                                            <h6>Chuyển khoản ngân hàng</h6>
                                            <small class="text-muted">Chuyển khoản qua ngân hàng</small>
                                        </label>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Cash on Delivery -->
                            <div class="col-md-6 mb-3">
                                <div class="card payment-option">
                                    <div class="card-body text-center">
                                        <input type="radio" name="payment_method" value="cod" id="cod">
                                        <label for="cod" class="d-block">
                                            <i class="bi bi-cash display-4 text-success mb-3"></i>
                                            <h6>Thanh toán khi nhận hàng</h6>
                                            <small class="text-muted">Thanh toán bằng tiền mặt</small>
                                        </label>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- E-wallet -->
                            <div class="col-md-6 mb-3">
                                <div class="card payment-option">
                                    <div class="card-body text-center">
                                        <input type="radio" name="payment_method" value="momo" id="momo">
                                        <label for="momo" class="d-block">
                                            <i class="bi bi-phone display-4 text-danger mb-3"></i>
                                            <h6>Ví điện tử MoMo</h6>
                                            <small class="text-muted">Thanh toán qua MoMo</small>
                                        </label>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Credit Card -->
                            <div class="col-md-6 mb-3">
                                <div class="card payment-option">
                                    <div class="card-body text-center">
                                        <input type="radio" name="payment_method" value="credit_card" id="credit_card">
                                        <label for="credit_card" class="d-block">
                                            <i class="bi bi-credit-card-2-front display-4 text-warning mb-3"></i>
                                            <h6>Thẻ tín dụng</h6>
                                            <small class="text-muted">Visa, MasterCard</small>
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Bank Transfer Details -->
                        <div id="bank_details" class="alert alert-info mt-3">
                            <h6><i class="bi bi-info-circle me-2"></i>Thông tin chuyển khoản:</h6>
                            <div class="row">
                                <div class="col-md-6">
                                    <strong>Ngân hàng:</strong> Vietcombank<br>
                                    <strong>Số tài khoản:</strong> 1234567890<br>
                                    <strong>Chủ tài khoản:</strong> CÔNG TY ABC
                                </div>
                                <div class="col-md-6">
                                    <strong>Số tiền:</strong> <?php echo number_format($order['total_amount'], 0, ',', '.'); ?> đ<br>
                                    <strong>Nội dung:</strong> DH<?php echo str_pad($order['id'], 5, '0', STR_PAD_LEFT); ?><br>
                                    <small class="text-danger">* Vui lòng chuyển đúng số tiền và nội dung</small>
                                </div>
                            </div>
                        </div>
                        
                        <div class="d-grid gap-2 d-md-flex justify-content-md-end mt-4">
                            <a href="/webbanhang/user/orders/view/<?php echo $order['id']; ?>" class="btn btn-outline-secondary me-md-2">
                                <i class="bi bi-arrow-left me-2"></i>Quay lại
                            </a>
                            <button type="submit" class="btn btn-success btn-lg">
                                <i class="bi bi-check-circle me-2"></i>Xác nhận thanh toán
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Order Summary -->
        <div class="col-md-4">
            <div class="card shadow-sm sticky-top">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="bi bi-receipt me-2"></i>Thông tin đơn hàng</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <h6 class="text-muted">Thông tin giao hàng:</h6>
                        <div class="fw-bold"><?php echo htmlspecialchars($order['name'], ENT_QUOTES, 'UTF-8'); ?></div>
                        <div class="small text-muted"><?php echo htmlspecialchars($order['phone'], ENT_QUOTES, 'UTF-8'); ?></div>
                        <div class="small text-muted"><?php echo htmlspecialchars($order['address'], ENT_QUOTES, 'UTF-8'); ?></div>
                    </div>
                    
                    <hr>
                    
                    <div class="mb-3">
                        <h6 class="text-muted">Sản phẩm:</h6>
                        <?php if (!empty($order['items'])): ?>
                            <?php foreach ($order['items'] as $item): ?>
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <div class="flex-grow-1">
                                        <div class="small fw-bold"><?php echo htmlspecialchars($item['product_name'], ENT_QUOTES, 'UTF-8'); ?></div>
                                        <div class="small text-muted">x<?php echo $item['quantity']; ?></div>
                                    </div>
                                    <div class="text-end">
                                        <div class="small fw-bold"><?php echo number_format($item['price'] * $item['quantity'], 0, ',', '.'); ?> đ</div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                    
                    <hr>
                    
                    <div class="d-flex justify-content-between mb-2">
                        <span>Tổng tiền hàng:</span>
                        <span class="fw-bold"><?php echo number_format($order['total_amount'], 0, ',', '.'); ?> đ</span>
                    </div>
                    
                    <?php if (!empty($order['voucher_code'])): ?>
                        <div class="d-flex justify-content-between mb-2 text-success">
                            <span>Voucher:</span>
                            <span class="fw-bold">-<?php echo number_format($order['voucher_discount'] ?? 0, 0, ',', '.'); ?> đ</span>
                        </div>
                    <?php endif; ?>
                    
                    <hr>
                    <div class="d-flex justify-content-between">
                        <span class="fw-bold fs-5">Tổng thanh toán:</span>
                        <span class="fw-bold text-primary fs-4"><?php echo number_format($order['total_amount'], 0, ',', '.'); ?> đ</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.payment-option {
    border: 2px solid #dee2e6;
    transition: all 0.3s ease;
    cursor: pointer;
}

.payment-option:hover {
    border-color: #0d6efd;
    box-shadow: 0 4px 8px rgba(0,0,0,0.1);
}

.payment-option input[type="radio"] {
    display: none;
}

.payment-option input[type="radio"]:checked + label {
    color: #0d6efd;
}

.payment-option input[type="radio"]:checked ~ * {
    border-color: #0d6efd;
}

.payment-option.selected {
    border-color: #0d6efd;
    background-color: #f8f9ff;
}

label {
    cursor: pointer;
    margin-bottom: 0;
    width: 100%;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const paymentOptions = document.querySelectorAll('.payment-option');
    const bankDetails = document.getElementById('bank_details');
    
    // Handle payment option selection
    paymentOptions.forEach(option => {
        option.addEventListener('click', function() {
            const radio = this.querySelector('input[type="radio"]');
            radio.checked = true;
            
            // Remove selected class from all options
            paymentOptions.forEach(opt => opt.classList.remove('selected'));
            
            // Add selected class to clicked option
            this.classList.add('selected');
            
            // Show/hide bank details
            if (radio.value === 'bank_transfer') {
                bankDetails.style.display = 'block';
            } else {
                bankDetails.style.display = 'none';
            }
        });
    });
    
    // Set initial state
    document.querySelector('#bank_transfer').closest('.payment-option').classList.add('selected');
    
    // Handle form submission
    document.getElementById('paymentForm').addEventListener('submit', function(e) {
        e.preventDefault();
        
        const formData = new FormData(this);
        const paymentMethod = formData.get('payment_method');
        const orderId = formData.get('order_id');
        
        if (confirm(`Bạn xác nhận thanh toán đơn hàng này bằng ${getPaymentMethodName(paymentMethod)}?`)) {
            // Simulate payment processing
            const submitBtn = this.querySelector('button[type="submit"]');
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<i class="bi bi-hourglass-split me-2"></i>Đang xử lý...';
            
            fetch('/webbanhang/order/processPayment', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: `order_id=${orderId}&payment_method=${paymentMethod}`
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('Thanh toán thành công! Đơn hàng của bạn đã được xác nhận.');
                    window.location.href = `/webbanhang/user/orders/view/${orderId}`;
                } else {
                    alert(data.message || 'Có lỗi xảy ra khi thanh toán');
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = '<i class="bi bi-check-circle me-2"></i>Xác nhận thanh toán';
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Có lỗi xảy ra khi thanh toán');
                submitBtn.disabled = false;
                submitBtn.innerHTML = '<i class="bi bi-check-circle me-2"></i>Xác nhận thanh toán';
            });
        }
    });
});

function getPaymentMethodName(method) {
    switch(method) {
        case 'bank_transfer': return 'chuyển khoản ngân hàng';
        case 'cod': return 'thanh toán khi nhận hàng';
        case 'momo': return 'ví điện tử MoMo';
        case 'credit_card': return 'thẻ tín dụng';
        default: return 'phương thức đã chọn';
    }
}
</script>

<?php include_once 'app/views/shares/footer.php'; ?> 
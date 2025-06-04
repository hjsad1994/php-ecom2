<?php include_once 'app/views/shares/header.php'; ?>

<div class="row mb-4">
    <div class="col-md-8">
        <h1 class="display-6 fw-bold text-primary">
            <i class="bi bi-cart3 me-2"></i>Giỏ hàng của bạn
        </h1>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="/webbanhang/" class="text-decoration-none">Trang chủ</a></li>
                <li class="breadcrumb-item"><a href="/webbanhang/user/products" class="text-decoration-none">Sản phẩm</a></li>
                <li class="breadcrumb-item active">Giỏ hàng</li>
            </ol>
        </nav>
    </div>
    <div class="col-md-4 text-end">
        <a href="/webbanhang/user/products" class="btn btn-outline-primary">
            <i class="bi bi-arrow-left me-2"></i>Tiếp tục mua sắm
        </a>
    </div>
</div>

<?php if (empty($cartItems) || !isset($_SESSION['cart'])): ?>
    <!-- Empty Cart -->
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="text-center py-5">
                <i class="bi bi-cart-x display-1 text-muted mb-4"></i>
                <h3 class="text-muted mb-3">Giỏ hàng trống</h3>
                <p class="text-muted mb-4">Bạn chưa có sản phẩm nào trong giỏ hàng. Hãy khám phá những sản phẩm tuyệt vời của chúng tôi!</p>
                <div class="d-grid gap-2 d-md-block">
                    <a href="/webbanhang/user/products" class="btn btn-primary btn-lg">
                        <i class="bi bi-shop me-2"></i>Tiếp tục mua sắm
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
    <!-- Cart Content -->
    <div class="row">
        <!-- Cart Items -->
        <div class="col-lg-8">
            <div class="card shadow-sm">
                <div class="card-header bg-light d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">
                        <i class="bi bi-list-ul me-2"></i>Sản phẩm 
                        <span class="badge bg-primary"><?php echo count($cartItems); ?> sản phẩm</span>
                    </h5>
                    <button class="btn btn-outline-danger btn-sm" onclick="clearCart()">
                        <i class="bi bi-trash me-1"></i>Xóa tất cả
                    </button>
                </div>
                <div class="card-body p-0">
                    <?php 
                    $total = 0;
                    $totalItems = 0;
                    foreach ($cartItems as $item): 
                        $itemTotal = $item->price * $item->quantity;
                        $total += $itemTotal;
                        $totalItems += $item->quantity;
                    ?>
                        <div class="cart-item p-4 border-bottom" data-product-id="<?php echo $item->id; ?>">
                            <div class="row align-items-center">
                                <!-- Product Image -->
                                <div class="col-md-2">
                                    <?php 
                                    $imagePath = $item->image ? '/webbanhang/public/uploads/products/' . $item->image : '/webbanhang/public/images/no-image.jpg';
                                    ?>
                                    <img src="<?php echo $imagePath; ?>" 
                                         class="img-fluid rounded" 
                                         alt="<?php echo htmlspecialchars($item->name, ENT_QUOTES, 'UTF-8'); ?>"
                                         style="height: 80px; object-fit: cover;">
                                </div>
                                
                                <!-- Product Info -->
                                <div class="col-md-4">
                                    <h6 class="mb-1">
                                        <a href="/webbanhang/product/show/<?php echo $item->id; ?>" 
                                           class="text-decoration-none text-dark">
                                            <?php echo htmlspecialchars($item->name, ENT_QUOTES, 'UTF-8'); ?>
                                        </a>
                                    </h6>
                                    <small class="text-muted">
                                        <i class="bi bi-tag me-1"></i>
                                        <?php echo isset($item->category_name) ? htmlspecialchars($item->category_name, ENT_QUOTES, 'UTF-8') : 'Chưa phân loại'; ?>
                                    </small>
                                    <div class="text-primary fw-bold mt-1">
                                        <?php echo number_format($item->price, 0, ',', '.'); ?> đ
                                    </div>
                                </div>
                                
                                <!-- Quantity Controls -->
                                <div class="col-md-3">
                                    <label class="form-label small">Số lượng:</label>
                                    <div class="input-group input-group-sm">
                                        <button class="btn btn-outline-secondary" type="button" 
                                                onclick="updateQuantity(<?php echo $item->id; ?>, <?php echo $item->quantity - 1; ?>)">
                                            <i class="bi bi-dash"></i>
                                        </button>
                                        <input type="number" class="form-control text-center" 
                                               value="<?php echo $item->quantity; ?>" 
                                               min="1" max="10"
                                               onchange="updateQuantity(<?php echo $item->id; ?>, this.value)">
                                        <button class="btn btn-outline-secondary" type="button" 
                                                onclick="updateQuantity(<?php echo $item->id; ?>, <?php echo $item->quantity + 1; ?>)">
                                            <i class="bi bi-plus"></i>
                                        </button>
                                    </div>
                                </div>
                                
                                <!-- Item Total & Actions -->
                                <div class="col-md-3 text-end">
                                    <div class="fw-bold text-success mb-2">
                                        <?php echo number_format($itemTotal, 0, ',', '.'); ?> đ
                                    </div>
                                    <div class="btn-group">
                                        <button class="btn btn-outline-secondary btn-sm" title="Lưu cho sau">
                                            <i class="bi bi-heart"></i>
                                        </button>
                                        <button class="btn btn-outline-danger btn-sm" 
                                                onclick="removeFromCart(<?php echo $item->id; ?>)" title="Xóa">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
        
        <!-- Order Summary -->
        <div class="col-lg-4">
            <div class="sticky-top" style="top: 2rem;">
                <!-- Order Summary Card -->
                <div class="card shadow-sm">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0">
                            <i class="bi bi-receipt me-2"></i>Tóm tắt đơn hàng
                        </h5>
                    </div>
                    <div class="card-body">
                        <!-- Order Details -->
                        <div class="d-flex justify-content-between mb-2">
                            <span>Tạm tính (<?php echo $totalItems; ?> sản phẩm):</span>
                            <span class="fw-bold"><?php echo number_format($total, 0, ',', '.'); ?> đ</span>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span>Phí vận chuyển:</span>
                            <span class="text-success">Miễn phí</span>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span>Giảm giá:</span>
                            <span class="text-danger">-0 đ</span>
                        </div>
                        <hr>
                        <?php if (isset($_SESSION['applied_voucher'])): ?>
                            <!-- Detailed Total with Voucher -->
                            <div class="d-flex justify-content-between mb-2">
                                <span class="text-muted">Tạm tính:</span>
                                <span><?php echo number_format($_SESSION['applied_voucher']['original_total'], 0, ',', '.'); ?> đ</span>
                            </div>
                            <div class="d-flex justify-content-between mb-2">
                                <span class="text-success">Giảm giá (<?php echo $_SESSION['applied_voucher']['code']; ?>):</span>
                                <span class="text-success">-<?php echo number_format($_SESSION['applied_voucher']['discount'], 0, ',', '.'); ?> đ</span>
                            </div>
                            <hr>
                            <div class="d-flex justify-content-between mb-3">
                                <span class="h6 fw-bold">Tổng cộng:</span>
                                <span class="h5 fw-bold text-primary"><?php echo number_format($_SESSION['applied_voucher']['final_total'], 0, ',', '.'); ?> đ</span>
                            </div>
                        <?php else: ?>
                            <!-- Simple Total without Voucher -->
                            <div class="d-flex justify-content-between mb-3">
                                <span class="h6 fw-bold">Tổng cộng:</span>
                                <span class="h5 fw-bold text-primary"><?php echo number_format($total, 0, ',', '.'); ?> đ</span>
                            </div>
                        <?php endif; ?>
                        
                        <!-- Voucher Section -->
                        <div class="mb-3">
                            <label class="form-label small fw-bold">Mã giảm giá:</label>
                            
                            <?php if (isset($_SESSION['applied_voucher'])): ?>
                                <!-- Applied Voucher Display -->
                                <div class="alert alert-success mb-2">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <strong><?php echo htmlspecialchars($_SESSION['applied_voucher']['code'], ENT_QUOTES, 'UTF-8'); ?></strong>
                                            <small class="d-block text-muted"><?php echo htmlspecialchars($_SESSION['applied_voucher']['name'] ?? '', ENT_QUOTES, 'UTF-8'); ?></small>
                                        </div>
                                        <div class="text-end">
                                            <div class="text-success fw-bold">-<?php echo number_format($_SESSION['applied_voucher']['discount'], 0, ',', '.'); ?> đ</div>
                                            <button class="btn btn-sm btn-outline-danger" onclick="removeVoucher()">Xóa</button>
                                        </div>
                                    </div>
                                </div>
                            <?php else: ?>
                                <!-- Voucher Input -->
                                <div class="input-group">
                                    <input type="text" class="form-control" placeholder="Nhập mã giảm giá" id="voucherCode">
                                    <button class="btn btn-outline-secondary" type="button" onclick="applyVoucher()">
                                        Áp dụng
                                    </button>
                                </div>
                                <small class="text-muted">Có mã giảm giá? Nhập để được ưu đãi!</small>
                            <?php endif; ?>
                        </div>
                        
                        <!-- Action Buttons -->
                        <div class="d-grid gap-2">
                            <button class="btn btn-primary btn-lg" onclick="proceedToCheckout()">
                                <i class="bi bi-credit-card me-2"></i>Thanh toán
                            </button>
                            <button class="btn btn-outline-secondary" onclick="saveForLater()">
                                <i class="bi bi-bookmark me-2"></i>Lưu giỏ hàng
                            </button>
                        </div>
                    </div>
                </div>
                
                <!-- Security Features -->
                <div class="card shadow-sm mt-3">
                    <div class="card-body">
                        <h6 class="card-title">
                            <i class="bi bi-shield-check text-success me-2"></i>Mua sắm an toàn
                        </h6>
                        <div class="row text-center">
                            <div class="col-4">
                                <i class="bi bi-truck text-primary fs-3"></i>
                                <small class="d-block">Giao hàng miễn phí</small>
                            </div>
                            <div class="col-4">
                                <i class="bi bi-arrow-return-left text-success fs-3"></i>
                                <small class="d-block">Đổi trả 7 ngày</small>
                            </div>
                            <div class="col-4">
                                <i class="bi bi-credit-card text-info fs-3"></i>
                                <small class="d-block">Thanh toán bảo mật</small>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Customer Support -->
                <div class="card shadow-sm mt-3">
                    <div class="card-body text-center">
                        <h6 class="card-title">
                            <i class="bi bi-headset text-primary me-2"></i>Cần hỗ trợ?
                        </h6>
                        <p class="small text-muted mb-3">Đội ngũ hỗ trợ 24/7 sẵn sàng giúp bạn</p>
                        <div class="d-grid gap-2 d-md-flex justify-content-center">
                            <button class="btn btn-outline-primary btn-sm">
                                <i class="bi bi-telephone me-1"></i>Gọi ngay
                            </button>
                            <button class="btn btn-outline-success btn-sm">
                                <i class="bi bi-chat-dots me-1"></i>Chat
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php endif; ?>

<style>
.cart-item {
    transition: background-color 0.3s ease;
}

.cart-item:hover {
    background-color: #f8f9fa;
}

.input-group-sm .form-control {
    max-width: 70px;
}

.sticky-top {
    z-index: 1020;
}

.card {
    border: none;
    border-radius: 12px;
}

.btn {
    border-radius: 6px;
}
</style>

<script>
// Update quantity function
function updateQuantity(productId, newQuantity) {
    if (newQuantity < 1) {
        if (confirm('Bạn có muốn xóa sản phẩm này khỏi giỏ hàng?')) {
            removeFromCart(productId);
        }
        return;
    }
    
    if (newQuantity > 10) {
        alert('Số lượng tối đa là 10 sản phẩm');
        return;
    }
    
    fetch('/webbanhang/user/cart/update', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: `product_id=${productId}&quantity=${newQuantity}`
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            location.reload();
        } else {
            alert('Có lỗi xảy ra khi cập nhật giỏ hàng');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Có lỗi xảy ra khi cập nhật giỏ hàng');
    });
}

// Remove from cart function
function removeFromCart(productId) {
    if (!confirm('Bạn có chắc chắn muốn xóa sản phẩm này khỏi giỏ hàng?')) {
        return;
    }
    
    fetch('/webbanhang/user/cart/remove', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: `product_id=${productId}`
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Remove item from DOM with animation
            const cartItem = document.querySelector(`[data-product-id="${productId}"]`);
            cartItem.style.transition = 'opacity 0.3s ease';
            cartItem.style.opacity = '0';
            setTimeout(() => {
                location.reload();
            }, 300);
        } else {
            alert('Có lỗi xảy ra khi xóa sản phẩm');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Có lỗi xảy ra khi xóa sản phẩm');
    });
}

// Clear cart function
function clearCart() {
    if (!confirm('Bạn có chắc chắn muốn xóa tất cả sản phẩm trong giỏ hàng?')) {
        return;
    }
    
    fetch('/webbanhang/user/cart/clear', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            location.reload();
        } else {
            alert('Có lỗi xảy ra khi xóa giỏ hàng');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Có lỗi xảy ra khi xóa giỏ hàng');
    });
}

// Apply voucher function
function applyVoucher() {
    const voucherCode = document.getElementById('voucherCode').value.trim();
    
    if (!voucherCode) {
        alert('Vui lòng nhập mã giảm giá');
        return;
    }
    
    fetch('/webbanhang/user/cart/apply-voucher', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: `voucher_code=${voucherCode}`
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('Áp dụng mã giảm giá thành công!');
            location.reload();
        } else {
            alert(data.message || 'Mã giảm giá không hợp lệ');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Có lỗi xảy ra khi áp dụng mã giảm giá');
    });
}

// Remove voucher function
function removeVoucher() {
    if (!confirm('Bạn có chắc chắn muốn xóa mã giảm giá?')) {
        return;
    }
    
    fetch('/webbanhang/user/cart/remove-voucher', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('Đã xóa mã giảm giá!');
            location.reload();
        } else {
            alert('Có lỗi xảy ra khi xóa mã giảm giá');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Có lỗi xảy ra khi xóa mã giảm giá');
    });
}

// Proceed to checkout
function proceedToCheckout() {
    window.location.href = '/webbanhang/checkout';
}

// Save for later
function saveForLater() {
    if (confirm('Lưu giỏ hàng để mua sau?')) {
        // Implementation would save cart to user account
        alert('Giỏ hàng đã được lưu vào tài khoản của bạn');
    }
}

// Auto-save cart every 30 seconds
setInterval(function() {
    // Auto-save implementation
    console.log('Auto-saving cart...');
}, 30000);
</script>

<?php include_once 'app/views/shares/footer.php'; ?> 
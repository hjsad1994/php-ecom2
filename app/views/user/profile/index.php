<?php include_once 'app/views/shares/header.php'; ?>

<!-- Display Success/Error Messages -->
<?php if (isset($_SESSION['success'])): ?>
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="bi bi-check-circle me-2"></i><?php echo $_SESSION['success']; ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    <?php unset($_SESSION['success']); ?>
<?php endif; ?>

<?php if (isset($_SESSION['errors'])): ?>
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <i class="bi bi-exclamation-triangle me-2"></i>
        <?php foreach ($_SESSION['errors'] as $error): ?>
            <div><?php echo $error; ?></div>
        <?php endforeach; ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    <?php unset($_SESSION['errors']); ?>
<?php endif; ?>

<div class="row mb-4">
    <div class="col-md-8">
        <h1 class="display-6 fw-bold text-primary">
            <i class="bi bi-person-circle me-2"></i>Hồ sơ cá nhân
        </h1>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="/webbanhang/" class="text-decoration-none">Trang chủ</a></li>
                <li class="breadcrumb-item active">Hồ sơ</li>
            </ol>
        </nav>
    </div>
    <div class="col-md-4 text-end">
        <div class="d-flex justify-content-end gap-2">
            <a href="/webbanhang/" class="btn btn-outline-primary">
                <i class="bi bi-house me-1"></i>Trang chủ
            </a>
            <button class="btn btn-outline-secondary" onclick="toggleEditMode()">
                <i class="bi bi-pencil me-2"></i>Chỉnh sửa thông tin
            </button>
        </div>
    </div>
</div>

<div class="row">
    <!-- Profile Sidebar -->
    <div class="col-lg-4 mb-4">
        <div class="card shadow-sm">
            <div class="card-body text-center">
                <!-- Avatar -->
                <div class="mb-3">
                    <div class="bg-primary text-white rounded-circle d-inline-flex align-items-center justify-content-center" 
                         style="width: 100px; height: 100px; font-size: 2rem;">
                        <i class="bi bi-person-fill"></i>
                    </div>
                </div>
                
                <!-- User Info -->
                <h4 class="mb-1"><?php echo htmlspecialchars($user->fullname, ENT_QUOTES, 'UTF-8'); ?></h4>
                <p class="text-muted mb-3">@<?php echo htmlspecialchars($user->username, ENT_QUOTES, 'UTF-8'); ?></p>
                
                <!-- Status Badge -->
                <span class="badge bg-success mb-3">
                    <i class="bi bi-check-circle me-1"></i>Tài khoản đã xác thực
                </span>
                
                <!-- Quick Stats -->
                <div class="row text-center">
                    <div class="col-4">
                        <div class="fw-bold text-primary"><?php echo $orderStats->total_orders ?? 0; ?></div>
                        <small class="text-muted">Đơn hàng</small>
                    </div>
                    <div class="col-4">
                        <div class="fw-bold text-success"><?php echo $orderStats->completed_orders ?? 0; ?></div>
                        <small class="text-muted">Hoàn thành</small>
                    </div>
                    <div class="col-4">
                        <div class="fw-bold text-warning"><?php echo $orderStats->pending_orders ?? 0; ?></div>
                        <small class="text-muted">Đang xử lý</small>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Quick Actions -->
        <div class="card shadow-sm mt-4">
            <div class="card-header">
                <h6 class="mb-0"><i class="bi bi-lightning me-2"></i>Thao tác nhanh</h6>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    <a href="/webbanhang/user/orders" class="btn btn-outline-primary">
                        <i class="bi bi-bag me-2"></i>Đơn hàng của tôi
                    </a>
                    <a href="/webbanhang/user/cart" class="btn btn-outline-success">
                        <i class="bi bi-cart3 me-2"></i>Giỏ hàng
                    </a>
                    <a href="/webbanhang/product" class="btn btn-outline-info">
                        <i class="bi bi-shop me-2"></i>Tiếp tục mua sắm
                    </a>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Profile Content -->
    <div class="col-lg-8">
        <!-- Profile Information -->
        <div class="card shadow-sm">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="bi bi-person-circle me-2"></i>Thông tin cá nhân
                </h5>
            </div>
            <div class="card-body">
                <form action="/webbanhang/user/profile/update" method="POST" id="profileForm">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="username" class="form-label fw-bold">Tên đăng nhập</label>
                            <input type="text" class="form-control" id="username" 
                                   value="<?php echo htmlspecialchars($user->username, ENT_QUOTES, 'UTF-8'); ?>" 
                                   disabled>
                            <small class="text-muted">Tên đăng nhập không thể thay đổi</small>
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label for="fullname" class="form-label fw-bold">Họ và tên <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="fullname" name="fullname" 
                                   value="<?php echo htmlspecialchars($user->fullname, ENT_QUOTES, 'UTF-8'); ?>" 
                                   required>
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label for="email" class="form-label fw-bold">Email</label>
                            <input type="email" class="form-control" id="email" name="email" 
                                   value="<?php echo htmlspecialchars($user->email ?? '', ENT_QUOTES, 'UTF-8'); ?>" 
                                   placeholder="email@example.com">
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label for="phone" class="form-label fw-bold">Số điện thoại</label>
                            <input type="tel" class="form-control" id="phone" name="phone" 
                                   value="<?php echo htmlspecialchars($user->phone ?? '', ENT_QUOTES, 'UTF-8'); ?>" 
                                   placeholder="0123456789">
                        </div>
                        
                        <div class="col-12 mb-3">
                            <label for="address" class="form-label fw-bold">Địa chỉ</label>
                            <textarea class="form-control" id="address" name="address" rows="3" 
                                      placeholder="Nhập địa chỉ của bạn..."><?php echo htmlspecialchars($user->address ?? '', ENT_QUOTES, 'UTF-8'); ?></textarea>
                        </div>
                    </div>
                    
                    <hr>
                    
                    <div class="d-flex justify-content-between align-items-center">
                        <small class="text-muted">
                            <i class="bi bi-info-circle me-1"></i>
                            Thông tin này sẽ được sử dụng cho việc giao hàng và liên hệ
                        </small>
                        <div>
                            <button type="button" class="btn btn-outline-secondary me-2" onclick="resetForm()">
                                <i class="bi bi-arrow-clockwise me-1"></i>Khôi phục
                            </button>
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-check-lg me-1"></i>Cập nhật thông tin
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        
        <!-- Account Security -->
        <div class="card shadow-sm mt-4">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="bi bi-shield-check me-2"></i>Bảo mật tài khoản
                </h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-8">
                        <h6>Đổi mật khẩu</h6>
                        <p class="text-muted mb-0">
                            Thay đổi mật khẩu định kỳ để bảo vệ tài khoản của bạn.
                            Mật khẩu nên có ít nhất 8 ký tự và bao gồm chữ hoa, chữ thường, số.
                        </p>
                    </div>
                    <div class="col-md-4 text-end">
                        <button class="btn btn-warning" onclick="alert('Tính năng đổi mật khẩu sẽ được cập nhật sớm!')">
                            <i class="bi bi-key me-1"></i>Đổi mật khẩu
                        </button>
                    </div>
                </div>
                
                <hr>
                
                <div class="row">
                    <div class="col-md-8">
                        <h6>Đăng xuất khỏi tất cả thiết bị</h6>
                        <p class="text-muted mb-0">
                            Đăng xuất khỏi tất cả các thiết bị đã đăng nhập tài khoản này.
                        </p>
                    </div>
                    <div class="col-md-4 text-end">
                        <a href="/webbanhang/account/logout" class="btn btn-outline-danger" 
                           onclick="return confirm('Bạn có chắc chắn muốn đăng xuất?')">
                            <i class="bi bi-box-arrow-right me-1"></i>Đăng xuất
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function resetForm() {
    if (confirm('Bạn có muốn khôi phục thông tin về trạng thái ban đầu?')) {
        document.getElementById('profileForm').reset();
    }
}

// Form validation
document.getElementById('profileForm').addEventListener('submit', function(e) {
    const fullname = document.getElementById('fullname').value.trim();
    
    if (!fullname) {
        e.preventDefault();
        alert('Vui lòng nhập họ và tên!');
        document.getElementById('fullname').focus();
        return false;
    }
    
    return true;
});
</script>

<style>
.card {
    border: none;
    border-radius: 12px;
    box-shadow: 0 5px 20px rgba(0,0,0,0.08) !important;
}

.card-header {
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
    border-bottom: 1px solid #dee2e6;
    border-radius: 12px 12px 0 0 !important;
}

.form-control:focus {
    border-color: #007bff;
    box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
}

.btn {
    border-radius: 8px;
    font-weight: 600;
    padding: 8px 16px;
    transition: all 0.3s ease;
}

.btn:hover {
    transform: translateY(-2px);
}

.badge {
    padding: 8px 12px;
    border-radius: 20px;
}

@media (max-width: 768px) {
    .btn {
        width: 100%;
        margin-bottom: 0.5rem;
    }
}
</style>

<?php include_once 'app/views/shares/footer.php'; ?> 
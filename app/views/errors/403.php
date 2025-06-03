<?php include_once 'app/views/shares/header.php'; ?>

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-8 col-lg-6">
            <div class="card shadow-lg border-0">
                <div class="card-body text-center p-5">
                    <!-- Error Icon -->
                    <div class="mb-4">
                        <i class="bi bi-shield-x display-1 text-danger"></i>
                    </div>
                    
                    <!-- Error Title -->
                    <h1 class="display-4 fw-bold text-danger mb-3">403</h1>
                    <h2 class="h3 mb-4 text-dark">Truy cập bị từ chối</h2>
                    
                    <!-- Error Message -->
                    <div class="alert alert-danger border-0 mb-4" role="alert">
                        <i class="bi bi-exclamation-triangle me-2"></i>
                        <strong>Bạn không có quyền truy cập vào trang này.</strong><br>
                        <span class="small">Vui lòng đăng nhập với tài khoản có quyền phù hợp.</span>
                    </div>
                    
                    <!-- Additional Info -->
                    <p class="text-muted mb-4">
                        Trang bạn đang cố gắng truy cập yêu cầu quyền quản trị viên. 
                        Nếu bạn tin rằng đây là lỗi, vui lòng liên hệ với quản trị viên hệ thống.
                    </p>
                    
                    <!-- Action Buttons -->
                    <div class="d-grid gap-2 d-md-flex justify-content-center">
                        <button onclick="history.back()" class="btn btn-outline-secondary btn-lg me-md-2">
                            <i class="bi bi-arrow-left me-2"></i>Quay về trang trước
                        </button>
                        <a href="/webbanhang/" class="btn btn-primary btn-lg">
                            <i class="bi bi-house me-2"></i>Về trang chủ
                        </a>
                    </div>
                    
                    <!-- Login Option -->
                    <?php if (!AuthHelper::isLoggedIn()): ?>
                        <div class="mt-4 pt-4 border-top">
                            <p class="text-muted mb-3">Bạn chưa đăng nhập?</p>
                            <a href="/webbanhang/account/login" class="btn btn-success">
                                <i class="bi bi-box-arrow-in-right me-2"></i>Đăng nhập
                            </a>
                        </div>
                    <?php else: ?>
                        <div class="mt-4 pt-4 border-top"> 
                            <div class="d-grid gap-2 d-sm-flex justify-content-center">
                                <a href="/webbanhang/user/orders" class="btn btn-outline-info">
                                    <i class="bi bi-receipt me-2"></i>Đơn hàng của tôi
                                </a>
                                <a href="/webbanhang/account/logout" class="btn btn-outline-warning">
                                    <i class="bi bi-box-arrow-right me-2"></i>Đăng xuất
                                </a>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
            
            <!-- Help Section -->
            <div class="text-center mt-4">
                <small class="text-muted">
                    Cần hỗ trợ? Liên hệ 
                    <a href="mailto:support@webbanhang.com" class="text-decoration-none">support@webbanhang.com</a>
                    hoặc gọi 
                    <a href="tel:+84123456789" class="text-decoration-none">(028) 123-4567</a>
                </small>
            </div>
        </div>
    </div>
</div>

<style>
.card {
    border-radius: 15px;
    overflow: hidden;
    background: linear-gradient(135deg, #fff 0%, #f8f9fa 100%);
}

.display-1 {
    font-size: 5rem;
    opacity: 0.8;
}

.alert {
    border-radius: 10px;
    background: linear-gradient(135deg, #f8d7da 0%, #f5c6cb 100%);
}

.btn {
    border-radius: 8px;
    padding: 12px 24px;
    font-weight: 500;
    transition: all 0.3s ease;
}

.btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0,0,0,0.15);
}

.btn-lg {
    padding: 15px 30px;
    font-size: 1.1rem;
}

@media (max-width: 768px) {
    .display-1 {
        font-size: 3.5rem;
    }
    
    .display-4 {
        font-size: 2.5rem;
    }
    
    .btn-lg {
        padding: 12px 24px;
        font-size: 1rem;
    }
}

/* Animation */
@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(30px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.card {
    animation: fadeInUp 0.6s ease-out;
}
</style>

<script>
// Auto redirect after 30 seconds (optional)
let countdown = 30;
function startCountdown() {
    // Uncomment if you want auto redirect
    // setInterval(() => {
    //     countdown--;
    //     if (countdown <= 0) {
    //         window.location.href = '/webbanhang/';
    //     }
    // }, 1000);
}

// Add some interactivity
document.addEventListener('DOMContentLoaded', function() {
    // Add click sound effect (optional)
    document.querySelectorAll('.btn').forEach(btn => {
        btn.addEventListener('click', function() {
            this.style.transform = 'translateY(-1px)';
            setTimeout(() => {
                this.style.transform = '';
            }, 150);
        });
    });
});
</script>

<?php include_once 'app/views/shares/footer.php'; ?> 
<?php include_once 'app/views/shares/header.php'; ?>

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-8 col-lg-6">
            <div class="card shadow-lg border-0">
                <div class="card-body text-center p-5">
                    <!-- Error Icon -->
                    <div class="mb-4">
                        <i class="bi bi-question-circle display-1 text-warning"></i>
                    </div>
                    
                    <!-- Error Title -->
                    <h1 class="display-4 fw-bold text-warning mb-3">404</h1>
                    <h2 class="h3 mb-4 text-dark">Trang không tồn tại</h2>
                    
                    <!-- Error Message -->
                    <div class="alert alert-warning border-0 mb-4" role="alert">
                        <i class="bi bi-exclamation-triangle me-2"></i>
                        <strong>Không thể tìm thấy trang bạn đang tìm kiếm.</strong><br>
                        <span class="small">URL có thể đã thay đổi hoặc không tồn tại.</span>
                    </div>
                    
                    <!-- Additional Info -->
                    <p class="text-muted mb-4">
                        Trang bạn đang cố gắng truy cập có thể đã bị xóa, đổi tên hoặc tạm thời không khả dụng. 
                        Hãy kiểm tra lại đường dẫn hoặc quay về trang chủ.
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
                    
                    <!-- Quick Links -->
                    <div class="mt-4 pt-4 border-top">
                        <p class="text-muted mb-3">Hoặc bạn có thể:</p>
                        <div class="d-grid gap-2 d-sm-flex justify-content-center">
                            <a href="/webbanhang/user/products" class="btn btn-outline-success">
                                <i class="bi bi-shop me-2"></i>Xem sản phẩm
                            </a>
                            <!-- Categories temporarily removed for simplified UX -->
                            <!-- <a href="/webbanhang/category" class="btn btn-outline-info">
                                <i class="bi bi-tags me-2"></i>Danh mục
                            </a> -->
                            <?php if (AuthHelper::isLoggedIn()): ?>
                                <a href="/webbanhang/user/orders" class="btn btn-outline-warning">
                                    <i class="bi bi-receipt me-2"></i>Đơn hàng
                                </a>
                            <?php else: ?>
                                <a href="/webbanhang/account/login" class="btn btn-outline-warning">
                                    <i class="bi bi-box-arrow-in-right me-2"></i>Đăng nhập
                                </a>
                            <?php endif; ?>
                        </div>
                    </div>
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
    background: linear-gradient(135deg, #fff3cd 0%, #ffeaa7 100%);
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

/* Floating animation for icon */
@keyframes float {
    0%, 100% {
        transform: translateY(0px);
    }
    50% {
        transform: translateY(-10px);
    }
}

.display-1 {
    animation: float 3s ease-in-out infinite;
}
</style>

<script>
// Add some interactivity
document.addEventListener('DOMContentLoaded', function() {
    // Add hover effects
    document.querySelectorAll('.btn').forEach(btn => {
        btn.addEventListener('mouseenter', function() {
            this.style.transform = 'translateY(-3px)';
        });
        
        btn.addEventListener('mouseleave', function() {
            this.style.transform = 'translateY(0)';
        });
        
        btn.addEventListener('click', function() {
            this.style.transform = 'translateY(-1px)';
            setTimeout(() => {
                this.style.transform = '';
            }, 150);
        });
    });
    
    // Optional: Log 404 for analytics
    console.log('404 Error - Page not found:', window.location.href);
});
</script>

<?php include_once 'app/views/shares/footer.php'; ?> 
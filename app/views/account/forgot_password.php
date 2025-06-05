<?php
session_start();
require_once 'app/views/shares/header.php';
?>

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-6 col-lg-5">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white text-center">
                    <h4 class="mb-0">
                        <i class="bi bi-shield-lock me-2"></i>
                        Quên mật khẩu
                    </h4>
                </div>
                <div class="card-body p-4">
                    <p class="text-muted text-center mb-4">
                        Nhập email của bạn và chúng tôi sẽ gửi link đặt lại mật khẩu
                    </p>

                    <?php if (isset($errors) && !empty($errors)): ?>
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                <?php foreach ($errors as $error): ?>
                                    <li><?= htmlspecialchars($error) ?></li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    <?php endif; ?>

                    <form method="POST" action="/webbanhang/account/forgot-password">
                        <div class="mb-3">
                            <label for="email" class="form-label">
                                <i class="bi bi-envelope me-1"></i>
                                Email
                            </label>
                            <input type="email" 
                                   class="form-control <?= isset($errors['email']) ? 'is-invalid' : '' ?>" 
                                   id="email" 
                                   name="email" 
                                   value="<?= htmlspecialchars($_POST['email'] ?? '') ?>"
                                   placeholder="Nhập email của bạn"
                                   required>
                            <?php if (isset($errors['email'])): ?>
                                <div class="invalid-feedback">
                                    <?= htmlspecialchars($errors['email']) ?>
                                </div>
                            <?php endif; ?>
                        </div>

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-send me-2"></i>
                                Gửi link đặt lại mật khẩu
                            </button>
                        </div>
                    </form>

                    <hr class="my-4">

                    <div class="text-center">
                        <a href="/webbanhang/account/login" class="text-decoration-none">
                            <i class="bi bi-arrow-left me-1"></i>
                            Quay lại đăng nhập
                        </a>
                    </div>
                </div>
            </div>

            <!-- Thông tin bảo mật -->
            <div class="card mt-3">
                <div class="card-body">
                    <h6 class="card-title">
                        <i class="bi bi-info-circle text-info me-2"></i>
                        Lưu ý bảo mật
                    </h6>
                    <ul class="small text-muted mb-0">
                        <li>Link đặt lại mật khẩu có hiệu lực trong 1 giờ</li>
                        <li>Chỉ sử dụng được một lần duy nhất</li>
                        <li>Kiểm tra cả thư mục spam nếu không thấy email</li>
                        <li>Không chia sẻ link với người khác</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.card {
    border: none;
    border-radius: 10px;
}

.card-header {
    border-radius: 10px 10px 0 0 !important;
}

.form-control:focus {
    border-color: #0d6efd;
    box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.15);
}

.btn-primary {
    background: linear-gradient(135deg, #007bff 0%, #0056b3 100%);
    border: none;
    transition: all 0.3s ease;
}

.btn-primary:hover {
    transform: translateY(-1px);
    box-shadow: 0 4px 8px rgba(0, 123, 255, 0.3);
}

.text-decoration-none:hover {
    text-decoration: underline !important;
}
</style>

<?php require_once 'app/views/shares/footer.php'; ?> 
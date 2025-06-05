<?php
session_start();
require_once 'app/views/shares/header.php';
$token = $_GET['token'] ?? $_POST['token'] ?? '';
?>

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-6 col-lg-5">
            <div class="card shadow-sm">
                <div class="card-header bg-success text-white text-center">
                    <h4 class="mb-0">
                        <i class="bi bi-key me-2"></i>
                        Đặt lại mật khẩu
                    </h4>
                </div>
                <div class="card-body p-4">
                    <p class="text-muted text-center mb-4">
                        Nhập mật khẩu mới cho tài khoản của bạn
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

                    <form method="POST" action="/webbanhang/account/process-reset-password" id="resetForm">
                        <input type="hidden" name="token" value="<?= htmlspecialchars($token) ?>">

                        <div class="mb-3">
                            <label for="password" class="form-label">
                                <i class="bi bi-lock me-1"></i>
                                Mật khẩu mới
                            </label>
                            <div class="input-group">
                                <input type="password" 
                                       class="form-control <?= isset($errors['password']) ? 'is-invalid' : '' ?>" 
                                       id="password" 
                                       name="password" 
                                       placeholder="Nhập mật khẩu mới"
                                       minlength="6"
                                       required>
                                <button class="btn btn-outline-secondary" type="button" onclick="togglePassword('password')">
                                    <i class="bi bi-eye" id="password-icon"></i>
                                </button>
                                <?php if (isset($errors['password'])): ?>
                                    <div class="invalid-feedback">
                                        <?= htmlspecialchars($errors['password']) ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                            <div class="form-text">
                                <i class="bi bi-info-circle me-1"></i>
                                Mật khẩu phải có ít nhất 6 ký tự
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="confirm_password" class="form-label">
                                <i class="bi bi-lock-fill me-1"></i>
                                Xác nhận mật khẩu
                            </label>
                            <div class="input-group">
                                <input type="password" 
                                       class="form-control <?= isset($errors['confirm_password']) ? 'is-invalid' : '' ?>" 
                                       id="confirm_password" 
                                       name="confirm_password" 
                                       placeholder="Nhập lại mật khẩu mới"
                                       minlength="6"
                                       required>
                                <button class="btn btn-outline-secondary" type="button" onclick="togglePassword('confirm_password')">
                                    <i class="bi bi-eye" id="confirm_password-icon"></i>
                                </button>
                                <?php if (isset($errors['confirm_password'])): ?>
                                    <div class="invalid-feedback">
                                        <?= htmlspecialchars($errors['confirm_password']) ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>

                        <!-- Password strength indicator -->
                        <div class="mb-3">
                            <div class="password-strength-container">
                                <div class="password-strength-bar" id="strength-bar"></div>
                                <small class="password-strength-text text-muted" id="strength-text">
                                    Độ mạnh mật khẩu
                                </small>
                            </div>
                        </div>

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-success">
                                <i class="bi bi-check-circle me-2"></i>
                                Đặt lại mật khẩu
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
                        <i class="bi bi-shield-check text-success me-2"></i>
                        Lời khuyên bảo mật
                    </h6>
                    <ul class="small text-muted mb-0">
                        <li>Sử dụng mật khẩu mạnh với ít nhất 8 ký tự</li>
                        <li>Kết hợp chữ hoa, chữ thường, số và ký tự đặc biệt</li>
                        <li>Không sử dụng thông tin cá nhân trong mật khẩu</li>
                        <li>Không chia sẻ mật khẩu với ai khác</li>
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
    border-color: #198754;
    box-shadow: 0 0 0 0.25rem rgba(25, 135, 84, 0.15);
}

.btn-success {
    background: linear-gradient(135deg, #198754 0%, #146c43 100%);
    border: none;
    transition: all 0.3s ease;
}

.btn-success:hover {
    transform: translateY(-1px);
    box-shadow: 0 4px 8px rgba(25, 135, 84, 0.3);
}

.password-strength-container {
    margin-top: 5px;
}

.password-strength-bar {
    height: 4px;
    background-color: #e9ecef;
    border-radius: 2px;
    transition: all 0.3s ease;
}

.strength-weak {
    background-color: #dc3545 !important;
    width: 25%;
}

.strength-fair {
    background-color: #fd7e14 !important;
    width: 50%;
}

.strength-good {
    background-color: #ffc107 !important;
    width: 75%;
}

.strength-strong {
    background-color: #198754 !important;
    width: 100%;
}

.text-decoration-none:hover {
    text-decoration: underline !important;
}
</style>

<script>
function togglePassword(fieldId) {
    const field = document.getElementById(fieldId);
    const icon = document.getElementById(fieldId + '-icon');
    
    if (field.type === 'password') {
        field.type = 'text';
        icon.classList.remove('bi-eye');
        icon.classList.add('bi-eye-slash');
    } else {
        field.type = 'password';
        icon.classList.remove('bi-eye-slash');
        icon.classList.add('bi-eye');
    }
}

// Password strength checker
document.getElementById('password').addEventListener('input', function() {
    const password = this.value;
    const strengthBar = document.getElementById('strength-bar');
    const strengthText = document.getElementById('strength-text');
    
    let strength = 0;
    let text = '';
    
    if (password.length >= 6) strength++;
    if (password.match(/[a-z]/)) strength++;
    if (password.match(/[A-Z]/)) strength++;
    if (password.match(/[0-9]/)) strength++;
    if (password.match(/[^a-zA-Z0-9]/)) strength++;
    
    strengthBar.classList.remove('strength-weak', 'strength-fair', 'strength-good', 'strength-strong');
    
    switch(strength) {
        case 0:
        case 1:
            strengthBar.classList.add('strength-weak');
            text = 'Yếu';
            break;
        case 2:
            strengthBar.classList.add('strength-fair');
            text = 'Trung bình';
            break;
        case 3:
        case 4:
            strengthBar.classList.add('strength-good');
            text = 'Tốt';
            break;
        case 5:
            strengthBar.classList.add('strength-strong');
            text = 'Mạnh';
            break;
    }
    
    strengthText.textContent = 'Độ mạnh mật khẩu: ' + text;
});

// Form validation
document.getElementById('resetForm').addEventListener('submit', function(e) {
    const password = document.getElementById('password').value;
    const confirmPassword = document.getElementById('confirm_password').value;
    
    if (password !== confirmPassword) {
        e.preventDefault();
        alert('Mật khẩu xác nhận không khớp!');
        return false;
    }
    
    if (password.length < 6) {
        e.preventDefault();
        alert('Mật khẩu phải có ít nhất 6 ký tự!');
        return false;
    }
});
</script>

<?php require_once 'app/views/shares/footer.php'; ?> 
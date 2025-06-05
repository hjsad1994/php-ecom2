<?php include_once 'app/views/shares/header.php'; ?>

<!-- Header -->
<div class="mb-4">
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h1 class="display-5 fw-bold text-primary mb-2">
                <i class="bi bi-person-plus me-3"></i>Tạo tài khoản mới
            </h1>
            <p class="lead text-muted">Thêm tài khoản người dùng mới vào hệ thống</p>
        </div>
        <a href="/webbanhang/admin/accounts" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left me-2"></i>Quay lại
        </a>
    </div>
</div>

<!-- Error Display -->
<?php if (!empty($errors)): ?>
<div class="alert alert-danger alert-dismissible fade show" role="alert">
    <strong><i class="bi bi-exclamation-triangle me-2"></i>Có lỗi xảy ra:</strong>
    <ul class="mb-0 mt-2">
        <?php foreach ($errors as $field => $error): ?>
            <li><?php echo htmlspecialchars($error, ENT_QUOTES, 'UTF-8'); ?></li>
        <?php endforeach; ?>
    </ul>
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
<?php endif; ?>

<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-white border-0 pb-0">
                <h4 class="card-title mb-0">
                    <i class="bi bi-person-badge me-2 text-primary"></i>Thông tin tài khoản
                </h4>
            </div>
            <div class="card-body">
                <form action="/webbanhang/admin/accounts/store" method="POST" id="createAccountForm">
                    <div class="row">
                        <!-- Username -->
                        <div class="col-md-6 mb-3">
                            <label for="username" class="form-label fw-medium">
                                <i class="bi bi-person me-1"></i>Tên đăng nhập <span class="text-danger">*</span>
                            </label>
                            <input type="text" 
                                   class="form-control <?php echo isset($errors['username']) ? 'is-invalid' : ''; ?>" 
                                   id="username" 
                                   name="username" 
                                   value="<?php echo htmlspecialchars($_POST['username'] ?? '', ENT_QUOTES); ?>"
                                   required
                                   autocomplete="username">
                            <?php if (isset($errors['username'])): ?>
                            <div class="invalid-feedback">
                                <?php echo htmlspecialchars($errors['username']); ?>
                            </div>
                            <?php endif; ?>
                        </div>

                        <!-- Full Name -->
                        <div class="col-md-6 mb-3">
                            <label for="fullname" class="form-label fw-medium">
                                <i class="bi bi-person-vcard me-1"></i>Họ và tên <span class="text-danger">*</span>
                            </label>
                            <input type="text" 
                                   class="form-control <?php echo isset($errors['fullname']) ? 'is-invalid' : ''; ?>" 
                                   id="fullname" 
                                   name="fullname" 
                                   value="<?php echo htmlspecialchars($_POST['fullname'] ?? '', ENT_QUOTES); ?>"
                                   required>
                            <?php if (isset($errors['fullname'])): ?>
                            <div class="invalid-feedback">
                                <?php echo htmlspecialchars($errors['fullname']); ?>
                            </div>
                            <?php endif; ?>
                        </div>
                    </div>

                    <div class="row">
                        <!-- Password -->
                        <div class="col-md-6 mb-3">
                            <label for="password" class="form-label fw-medium">
                                <i class="bi bi-key me-1"></i>Mật khẩu <span class="text-danger">*</span>
                            </label>
                            <div class="input-group">
                                <input type="password" 
                                       class="form-control <?php echo isset($errors['password']) ? 'is-invalid' : ''; ?>" 
                                       id="password" 
                                       name="password" 
                                       required
                                       autocomplete="new-password"
                                       minlength="6">
                                <button class="btn btn-outline-secondary" type="button" onclick="togglePassword('password')">
                                    <i class="bi bi-eye" id="password-icon"></i>
                                </button>
                                <?php if (isset($errors['password'])): ?>
                                <div class="invalid-feedback">
                                    <?php echo htmlspecialchars($errors['password']); ?>
                                </div>
                                <?php endif; ?>
                            </div>
                            <small class="text-muted">Tối thiểu 6 ký tự</small>
                        </div>

                        <!-- Confirm Password -->
                        <div class="col-md-6 mb-3">
                            <label for="confirm_password" class="form-label fw-medium">
                                <i class="bi bi-key-fill me-1"></i>Xác nhận mật khẩu <span class="text-danger">*</span>
                            </label>
                            <div class="input-group">
                                <input type="password" 
                                       class="form-control <?php echo isset($errors['confirm_password']) ? 'is-invalid' : ''; ?>" 
                                       id="confirm_password" 
                                       name="confirm_password" 
                                       required
                                       autocomplete="new-password">
                                <button class="btn btn-outline-secondary" type="button" onclick="togglePassword('confirm_password')">
                                    <i class="bi bi-eye" id="confirm_password-icon"></i>
                                </button>
                                <?php if (isset($errors['confirm_password'])): ?>
                                <div class="invalid-feedback">
                                    <?php echo htmlspecialchars($errors['confirm_password']); ?>
                                </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <!-- Email -->
                        <div class="col-md-6 mb-3">
                            <label for="email" class="form-label fw-medium">
                                <i class="bi bi-envelope me-1"></i>Email
                            </label>
                            <input type="email" 
                                   class="form-control <?php echo isset($errors['email']) ? 'is-invalid' : ''; ?>" 
                                   id="email" 
                                   name="email" 
                                   value="<?php echo htmlspecialchars($_POST['email'] ?? '', ENT_QUOTES); ?>"
                                   autocomplete="email">
                            <?php if (isset($errors['email'])): ?>
                            <div class="invalid-feedback">
                                <?php echo htmlspecialchars($errors['email']); ?>
                            </div>
                            <?php endif; ?>
                        </div>

                        <!-- Phone -->
                        <div class="col-md-6 mb-3">
                            <label for="phone" class="form-label fw-medium">
                                <i class="bi bi-telephone me-1"></i>Số điện thoại
                            </label>
                            <input type="tel" 
                                   class="form-control <?php echo isset($errors['phone']) ? 'is-invalid' : ''; ?>" 
                                   id="phone" 
                                   name="phone" 
                                   value="<?php echo htmlspecialchars($_POST['phone'] ?? '', ENT_QUOTES); ?>"
                                   autocomplete="tel">
                            <?php if (isset($errors['phone'])): ?>
                            <div class="invalid-feedback">
                                <?php echo htmlspecialchars($errors['phone']); ?>
                            </div>
                            <?php endif; ?>
                        </div>
                    </div>

                    <!-- Address -->
                    <div class="mb-3">
                        <label for="address" class="form-label fw-medium">
                            <i class="bi bi-geo-alt me-1"></i>Địa chỉ
                        </label>
                        <textarea class="form-control <?php echo isset($errors['address']) ? 'is-invalid' : ''; ?>" 
                                  id="address" 
                                  name="address" 
                                  rows="3"
                                  autocomplete="street-address"><?php echo htmlspecialchars($_POST['address'] ?? '', ENT_QUOTES); ?></textarea>
                        <?php if (isset($errors['address'])): ?>
                        <div class="invalid-feedback">
                            <?php echo htmlspecialchars($errors['address']); ?>
                        </div>
                        <?php endif; ?>
                    </div>

                    <!-- Role -->
                    <div class="mb-4">
                        <label for="role" class="form-label fw-medium">
                            <i class="bi bi-shield-check me-1"></i>Vai trò <span class="text-danger">*</span>
                        </label>
                        <select class="form-select <?php echo isset($errors['role']) ? 'is-invalid' : ''; ?>" 
                                id="role" 
                                name="role" 
                                required>
                            <option value="">Chọn vai trò...</option>
                            <option value="user" <?php echo (($_POST['role'] ?? '') === 'user') ? 'selected' : ''; ?>>
                                <i class="bi bi-person"></i> Người dùng
                            </option>
                            <option value="admin" <?php echo (($_POST['role'] ?? '') === 'admin') ? 'selected' : ''; ?>>
                                <i class="bi bi-shield-check"></i> Quản trị viên
                            </option>
                        </select>
                        <?php if (isset($errors['role'])): ?>
                        <div class="invalid-feedback">
                            <?php echo htmlspecialchars($errors['role']); ?>
                        </div>
                        <?php endif; ?>
                        <small class="text-muted">Quản trị viên có quyền truy cập vào panel admin</small>
                    </div>

                    <!-- Submit Buttons -->
                    <div class="d-flex justify-content-between">
                        <a href="/webbanhang/admin/accounts" class="btn btn-outline-secondary">
                            <i class="bi bi-arrow-left me-2"></i>Hủy
                        </a>
                        <button type="submit" class="btn btn-success">
                            <i class="bi bi-check-circle me-2"></i>Tạo tài khoản
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
function togglePassword(fieldId) {
    const field = document.getElementById(fieldId);
    const icon = document.getElementById(fieldId + '-icon');
    
    if (field.type === 'password') {
        field.type = 'text';
        icon.className = 'bi bi-eye-slash';
    } else {
        field.type = 'password';
        icon.className = 'bi bi-eye';
    }
}

// Real-time password confirmation validation
document.getElementById('confirm_password').addEventListener('input', function() {
    const password = document.getElementById('password').value;
    const confirmPassword = this.value;
    
    if (confirmPassword && password !== confirmPassword) {
        this.classList.add('is-invalid');
        this.classList.remove('is-valid');
    } else if (confirmPassword) {
        this.classList.add('is-valid');
        this.classList.remove('is-invalid');
    } else {
        this.classList.remove('is-invalid', 'is-valid');
    }
});

// Form validation before submit
document.getElementById('createAccountForm').addEventListener('submit', function(e) {
    const password = document.getElementById('password').value;
    const confirmPassword = document.getElementById('confirm_password').value;
    
    if (password !== confirmPassword) {
        e.preventDefault();
        alert('Mật khẩu xác nhận không khớp!');
        document.getElementById('confirm_password').focus();
    }
});
</script>

<style>
.card-header {
    padding: 1.5rem 1.5rem 0.5rem;
}

.form-label {
    color: #495057;
    margin-bottom: 0.5rem;
}

.form-control:focus, .form-select:focus {
    border-color: #86b7fe;
    box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.15);
}

.input-group .btn {
    border-color: #ced4da;
}

.input-group .btn:hover {
    background-color: #f8f9fa;
    border-color: #adb5bd;
}

.is-valid {
    border-color: #198754;
}

.is-invalid {
    border-color: #dc3545;
}
</style>

<?php include_once 'app/views/shares/footer.php'; ?> 
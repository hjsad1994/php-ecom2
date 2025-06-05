<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng ký tài khoản - Website Bán Hàng</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            padding: 2rem 0;
        }
        
        .register-container {
            background: white;
            border-radius: 15px;
            box-shadow: 0 10px 25px rgba(0,0,0,0.1);
            width: 100%;
            max-width: 500px;
            padding: 3rem 2rem;
            border: 1px solid #e9ecef;
        }
        
        .register-header {
            text-align: center;
            margin-bottom: 2rem;
        }
        
        .register-header .logo {
            width: 80px;
            height: 80px;
            background: #007bff;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1rem;
            color: white;
            font-size: 2rem;
        }
        
        .register-header h1 {
            color: #2c3e50;
            font-size: 2rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
        }
        
        .register-header p {
            color: #7f8c8d;
            font-size: 1rem;
            margin-bottom: 0;
        }
        
        .form-floating {
            margin-bottom: 1.5rem;
        }
        
        .form-floating input {
            border: 1px solid #ced4da;
            border-radius: 8px;
            padding: 1rem;
            font-size: 1rem;
        }
        
        .form-floating input:focus {
            border-color: #007bff;
            box-shadow: 0 0 0 0.25rem rgba(0, 123, 255, 0.25);
        }
        
        .form-floating label {
            color: #6c757d;
        }
        
        .btn-register {
            width: 100%;
            padding: 1rem;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 1.1rem;
            font-weight: 600;
        }
        
        .btn-register:hover {
            background-color: #0056b3;
        }
        
        .alert {
            border-radius: 8px;
            border: none;
            padding: 1rem;
            margin-bottom: 1.5rem;
        }
        
        .links {
            text-align: center;
            margin-top: 1.5rem;
        }
        
        .links a {
            color: #007bff;
            text-decoration: none;
            font-weight: 500;
        }
        
        .links a:hover {
            text-decoration: underline;
        }
        
        .password-strength {
            margin-top: 0.5rem;
            font-size: 0.8rem;
        }
        
        .strength-weak { color: #dc3545; }
        .strength-medium { color: #ffc107; }
        .strength-strong { color: #28a745; }
    </style>
</head>
<body>
    <div class="floating-shapes">
        <div class="shape"></div>
        <div class="shape"></div>
        <div class="shape"></div>
    </div>
    
    <div class="register-container">
        <div class="register-header">
            <div class="logo">
                <i class="bi bi-person-plus"></i>
            </div>
            <h1>Tạo tài khoản mới</h1>
            <p>Tham gia cộng đồng mua sắm của chúng tôi</p>
        </div>
        
        <form action="/webbanhang/account/save" method="POST">
            <div class="form-floating">
                <input type="text" class="form-control" id="username" name="username" 
                       placeholder="Tên đăng nhập" value="<?= $_POST['username'] ?? '' ?>" required>
                <label for="username">Tên đăng nhập</label>
                <?php if(isset($errors['username'])): ?>
                    <div class="text-danger mt-1"><small><?= $errors['username'] ?></small></div>
                <?php endif; ?>
            </div>
            
            <div class="form-floating">
                <input type="text" class="form-control" id="fullname" name="fullname" 
                       placeholder="Họ và tên" value="<?= $_POST['fullname'] ?? '' ?>" required>
                <label for="fullname">Họ và tên</label>
                <?php if(isset($errors['fullname'])): ?>
                    <div class="text-danger mt-1"><small><?= $errors['fullname'] ?></small></div>
                <?php endif; ?>
            </div>
            
            <div class="form-floating">
                <input type="email" class="form-control" id="email" name="email" 
                       placeholder="Email" value="<?= $_POST['email'] ?? '' ?>" required>
                <label for="email">Email</label>
                <?php if(isset($errors['email'])): ?>
                    <div class="text-danger mt-1"><small><?= $errors['email'] ?></small></div>
                <?php endif; ?>
            </div>
            
            <div class="form-floating">
                <input type="password" class="form-control" id="password" name="password" 
                       placeholder="Mật khẩu" required onkeyup="checkPasswordStrength()">
                <label for="password">Mật khẩu</label>
                <div class="password-strength">
                    <div class="progress">
                        <div class="progress-bar" id="passwordProgress" role="progressbar" style="width: 0%"></div>
                    </div>
                    <small id="passwordText" class="text-muted">Nhập mật khẩu để kiểm tra độ mạnh</small>
                </div>
                <?php if(isset($errors['password'])): ?>
                    <div class="text-danger mt-1"><small><?= $errors['password'] ?></small></div>
                <?php endif; ?>
            </div>
            
            <div class="form-floating">
                <input type="password" class="form-control" id="confirmpassword" name="confirmpassword" 
                       placeholder="Xác nhận mật khẩu" required onkeyup="checkPasswordMatch()">
                <label for="confirmpassword">Xác nhận mật khẩu</label>
                <div id="passwordMatch" class="mt-1"></div>
                <?php if(isset($errors['confirmPass'])): ?>
                    <div class="text-danger mt-1"><small><?= $errors['confirmPass'] ?></small></div>
                <?php endif; ?>
            </div>
            
            <?php if(isset($errors['account'])): ?>
                <div class="alert alert-danger">
                    <i class="bi bi-exclamation-triangle me-2"></i>
                    <?= $errors['account'] ?>
                </div>
            <?php endif; ?>
            
            <?php if(isset($errors['database'])): ?>
                <div class="alert alert-danger">
                    <i class="bi bi-database-exclamation me-2"></i>
                    <?= $errors['database'] ?>
                </div>
            <?php endif; ?>
            
            <?php if(isset($errors['save'])): ?>
                <div class="alert alert-danger">
                    <i class="bi bi-exclamation-triangle me-2"></i>
                    <?= $errors['save'] ?>
                </div>
            <?php endif; ?>
            
            <?php if(isset($errors['exception'])): ?>
                <div class="alert alert-danger">
                    <i class="bi bi-bug me-2"></i>
                    <?= $errors['exception'] ?>
                </div>
            <?php endif; ?>
            
            <button type="submit" class="btn btn-register">
                <i class="bi bi-person-plus me-2"></i>Tạo tài khoản
            </button>
        </form>
        
        <div class="divider">
            <span>hoặc</span>
        </div>
        
        <div class="links">
            <p class="mb-2">Đã có tài khoản? <a href="/webbanhang/account/login">Đăng nhập ngay</a></p>
            <p class="mb-0"><a href="/webbanhang/"><i class="bi bi-arrow-left me-1"></i>Quay về trang chủ</a></p>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function checkPasswordStrength() {
            const password = document.getElementById('password').value;
            const progressBar = document.getElementById('passwordProgress');
            const text = document.getElementById('passwordText');
            
            let strength = 0;
            let feedback = [];
            
            if (password.length >= 8) strength += 1;
            else feedback.push('ít nhất 8 ký tự');
            
            if (/[a-z]/.test(password)) strength += 1;
            else feedback.push('chữ thường');
            
            if (/[A-Z]/.test(password)) strength += 1;
            else feedback.push('chữ hoa');
            
            if (/[0-9]/.test(password)) strength += 1;
            else feedback.push('số');
            
            if (/[^A-Za-z0-9]/.test(password)) strength += 1;
            else feedback.push('ký tự đặc biệt');
            
            const percentage = (strength / 5) * 100;
            progressBar.style.width = percentage + '%';
            
            if (strength <= 2) {
                progressBar.className = 'progress-bar bg-danger';
                text.textContent = 'Yếu - Cần thêm: ' + feedback.join(', ');
                text.className = 'text-danger';
            } else if (strength <= 3) {
                progressBar.className = 'progress-bar bg-warning';
                text.textContent = 'Trung bình - Cần thêm: ' + feedback.join(', ');
                text.className = 'text-warning';
            } else if (strength <= 4) {
                progressBar.className = 'progress-bar bg-info';
                text.textContent = 'Khá mạnh - Cần thêm: ' + feedback.join(', ');
                text.className = 'text-info';
            } else {
                progressBar.className = 'progress-bar bg-success';
                text.textContent = 'Mật khẩu mạnh!';
                text.className = 'text-success';
            }
        }
        
        function checkPasswordMatch() {
            const password = document.getElementById('password').value;
            const confirmPassword = document.getElementById('confirmpassword').value;
            const matchDiv = document.getElementById('passwordMatch');
            
            if (confirmPassword === '') {
                matchDiv.innerHTML = '';
                return;
            }
            
            if (password === confirmPassword) {
                matchDiv.innerHTML = '<small class="text-success"><i class="bi bi-check-circle me-1"></i>Mật khẩu khớp</small>';
            } else {
                matchDiv.innerHTML = '<small class="text-danger"><i class="bi bi-x-circle me-1"></i>Mật khẩu không khớp</small>';
            }
        }
    </script>
</body>
</html> 
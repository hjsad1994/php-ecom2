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
            background: linear-gradient(135deg, #667eea 0%, #764ba2 50%, #667eea 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            animation: gradientShift 8s ease-in-out infinite;
            padding: 2rem 0;
        }
        
        @keyframes gradientShift {
            0%, 100% { background: linear-gradient(135deg, #667eea 0%, #764ba2 50%, #667eea 100%); }
            50% { background: linear-gradient(135deg, #764ba2 0%, #667eea 50%, #764ba2 100%); }
        }
        
        .register-container {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            border-radius: 20px;
            box-shadow: 0 20px 40px rgba(0,0,0,0.1);
            width: 100%;
            max-width: 500px;
            padding: 3rem 2rem;
            position: relative;
            overflow: hidden;
            border: 1px solid rgba(255, 255, 255, 0.2);
        }
        
        .register-container::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, #667eea, #764ba2, #667eea);
            background-size: 200% 100%;
            animation: shimmer 3s linear infinite;
        }
        
        @keyframes shimmer {
            0% { background-position: 200% 0; }
            100% { background-position: -200% 0; }
        }
        
        .register-header {
            text-align: center;
            margin-bottom: 2rem;
        }
        
        .register-header .logo {
            width: 80px;
            height: 80px;
            background: linear-gradient(135deg, #667eea, #764ba2);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1rem;
            color: white;
            font-size: 2rem;
            box-shadow: 0 10px 30px rgba(102, 126, 234, 0.3);
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
            position: relative;
        }
        
        .form-floating input {
            border: 2px solid #e9ecef;
            border-radius: 12px;
            padding: 1rem;
            font-size: 1rem;
            transition: all 0.3s ease;
            background: rgba(255, 255, 255, 0.9);
        }
        
        .form-floating input:focus {
            border-color: #667eea;
            box-shadow: 0 0 20px rgba(102, 126, 234, 0.2);
            transform: translateY(-2px);
        }
        
        .form-floating label {
            color: #7f8c8d;
            font-size: 0.9rem;
        }
        
        .form-floating input:focus ~ label,
        .form-floating input:not(:placeholder-shown) ~ label {
            font-size: 0.8rem;
        }
        
        .btn-register {
            width: 100%;
            padding: 1rem;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
            border-radius: 12px;
            font-size: 1.1rem;
            font-weight: 600;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }
        
        .btn-register:hover {
            transform: translateY(-3px);
            box-shadow: 0 15px 35px rgba(102, 126, 234, 0.4);
        }
        
        .btn-register:active {
            transform: translateY(-1px);
        }
        
        .alert {
            border-radius: 12px;
            border: none;
            padding: 1rem;
            margin-bottom: 1.5rem;
        }
        
        .alert-danger {
            background: linear-gradient(135deg, #f8d7da 0%, #f5c6cb 100%);
            color: #721c24;
        }
        
        .links {
            text-align: center;
            margin-top: 1.5rem;
        }
        
        .links a {
            color: #667eea;
            text-decoration: none;
            font-weight: 500;
            transition: all 0.3s ease;
        }
        
        .links a:hover {
            color: #764ba2;
            text-decoration: underline;
        }
        
        .divider {
            text-align: center;
            margin: 1rem 0;
            position: relative;
        }
        
        .divider::before {
            content: '';
            position: absolute;
            top: 50%;
            left: 0;
            right: 0;
            height: 1px;
            background: linear-gradient(90deg, transparent, #dee2e6, transparent);
        }
        
        .divider span {
            background: rgba(255, 255, 255, 0.95);
            padding: 0 1rem;
            color: #7f8c8d;
            font-size: 0.9rem;
        }
        
        .floating-shapes {
            position: fixed;
            width: 100%;
            height: 100%;
            pointer-events: none;
            z-index: -1;
        }
        
        .shape {
            position: absolute;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.1);
            animation: float 6s ease-in-out infinite;
        }
        
        .shape:nth-child(1) {
            width: 80px;
            height: 80px;
            top: 20%;
            left: 10%;
            animation-delay: 0s;
        }
        
        .shape:nth-child(2) {
            width: 120px;
            height: 120px;
            top: 60%;
            right: 10%;
            animation-delay: 2s;
        }
        
        .shape:nth-child(3) {
            width: 60px;
            height: 60px;
            bottom: 20%;
            left: 20%;
            animation-delay: 4s;
        }
        
        @keyframes float {
            0%, 100% { transform: translateY(0px) rotate(0deg); }
            50% { transform: translateY(-20px) rotate(180deg); }
        }
        
        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1rem;
        }
        
        .password-strength {
            margin-top: 0.5rem;
        }
        
        .password-strength .progress {
            height: 4px;
            border-radius: 2px;
        }
        
        .password-strength small {
            font-size: 0.75rem;
            margin-top: 0.25rem;
            display: block;
        }
        
        @media (max-width: 576px) {
            .register-container {
                margin: 1rem;
                padding: 2rem 1.5rem;
            }
            
            .register-header h1 {
                font-size: 1.5rem;
            }
            
            .form-row {
                grid-template-columns: 1fr;
                gap: 0;
            }
        }
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
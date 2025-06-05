<?php session_start(); ?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng nhập - Website Bán Hàng</title>
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
        }
        
        .login-container {
            background: white;
            border-radius: 15px;
            box-shadow: 0 10px 25px rgba(0,0,0,0.1);
            width: 100%;
            max-width: 450px;
            padding: 3rem 2rem;
            border: 1px solid #e9ecef;
        }
        
        .login-header {
            text-align: center;
            margin-bottom: 2rem;
        }
        
        .login-header .logo {
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
        
        .login-header h1 {
            color: #2c3e50;
            font-size: 2rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
        }
        
        .login-header p {
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
        
        .btn-login {
            width: 100%;
            padding: 1rem;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 1.1rem;
            font-weight: 600;
        }
        
        .btn-login:hover {
            background-color: #0056b3;
        }
        
        .demo-accounts {
            background: #f8f9fa;
            padding: 1.5rem;
            border-radius: 10px;
            margin-bottom: 2rem;
            border: 1px solid #dee2e6;
        }
        
        .demo-accounts h5 {
            color: #495057;
            margin-bottom: 1rem;
            font-weight: 600;
        }
        
        .demo-account {
            background: white;
            padding: 0.75rem 1rem;
            border-radius: 8px;
            margin-bottom: 0.5rem;
            border-left: 4px solid #007bff;
        }
        
        .demo-account:last-child {
            border-left-color: #28a745;
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
    </style>
</head>
<body>
    <div class="floating-shapes">
        <div class="shape"></div>
        <div class="shape"></div>
        <div class="shape"></div>
    </div>
    
    <div class="login-container">
        <div class="login-header">
            <div class="logo">
                <i class="bi bi-shop"></i>
            </div>
            <h1>Chào mừng trở lại</h1>
            <p>Đăng nhập để tiếp tục mua sắm</p>
        </div>
        
        <!-- Demo accounts info -->
        <div class="demo-accounts">
            <h5><i class="bi bi-info-circle"></i> Tài khoản demo:</h5>
            <div class="demo-account">
                <small><strong>👤 User:</strong> test01 / 111</small>
            </div>
            <div class="demo-account">
                <small><strong>🔧 Admin:</strong> admin / admin</small>
            </div>
        </div>
        
        <?php if(isset($_GET['registered']) && $_GET['registered'] == 'success'): ?>
            <div class="alert alert-success">
                <i class="bi bi-check-circle me-2"></i>
                Đăng ký thành công! Vui lòng đăng nhập.
            </div>
        <?php endif; ?>
        
        <?php if(isset($_SESSION['success'])): ?>
            <div class="alert alert-success">
                <i class="bi bi-check-circle me-2"></i>
                <?= $_SESSION['success'] ?>
            </div>
            <?php unset($_SESSION['success']); ?>
        <?php endif; ?>
        
        <?php if(isset($_SESSION['error'])): ?>
            <div class="alert alert-danger">
                <i class="bi bi-exclamation-triangle me-2"></i>
                <?= $_SESSION['error'] ?>
            </div>
            <?php unset($_SESSION['error']); ?>
        <?php endif; ?>
        
        <form action="/webbanhang/account/checkLogin" method="POST">
            <div class="form-floating">
                <input type="text" class="form-control" id="username" name="username" 
                       placeholder="Tên đăng nhập" value="<?= $_POST['username'] ?? '' ?>" required>
                <label for="username">Tên đăng nhập</label>
                <?php if(isset($errors['username'])): ?>
                    <div class="text-danger mt-1"><small><?= $errors['username'] ?></small></div>
                <?php endif; ?>
            </div>
            
            <div class="form-floating">
                <input type="password" class="form-control" id="password" name="password" 
                       placeholder="Mật khẩu" required>
                <label for="password">Mật khẩu</label>
                <?php if(isset($errors['password'])): ?>
                    <div class="text-danger mt-1"><small><?= $errors['password'] ?></small></div>
                <?php endif; ?>
            </div>
            
            <?php if(isset($errors['login'])): ?>
                <div class="alert alert-danger">
                    <i class="bi bi-exclamation-triangle me-2"></i>
                    <?= $errors['login'] ?>
                </div>
            <?php endif; ?>
            
            <button type="submit" class="btn btn-login">
                <i class="bi bi-box-arrow-in-right me-2"></i>Đăng nhập
            </button>
        </form>
        
        <div class="divider">
            <span>hoặc</span>
        </div>
        
        <div class="links">
            <p class="mb-2">Chưa có tài khoản? <a href="/webbanhang/account/register">Đăng ký ngay</a></p>
            <p class="mb-2"><a href="/webbanhang/account/forgot-password"><i class="bi bi-key me-1"></i>Quên mật khẩu?</a></p>
            <p class="mb-0"><a href="/webbanhang/"><i class="bi bi-arrow-left me-1"></i>Quay về trang chủ</a></p>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html> 
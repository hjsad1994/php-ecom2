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
            background: linear-gradient(135deg, #667eea 0%, #764ba2 50%, #667eea 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            animation: gradientShift 8s ease-in-out infinite;
        }
        
        @keyframes gradientShift {
            0%, 100% { background: linear-gradient(135deg, #667eea 0%, #764ba2 50%, #667eea 100%); }
            50% { background: linear-gradient(135deg, #764ba2 0%, #667eea 50%, #764ba2 100%); }
        }
        
        .login-container {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            border-radius: 20px;
            box-shadow: 0 20px 40px rgba(0,0,0,0.1);
            width: 100%;
            max-width: 450px;
            padding: 3rem 2rem;
            position: relative;
            overflow: hidden;
            border: 1px solid rgba(255, 255, 255, 0.2);
        }
        
        .login-container::before {
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
        
        .login-header {
            text-align: center;
            margin-bottom: 2rem;
        }
        
        .login-header .logo {
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
        
        .btn-login {
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
        
        .btn-login:hover {
            transform: translateY(-3px);
            box-shadow: 0 15px 35px rgba(102, 126, 234, 0.4);
        }
        
        .btn-login:active {
            transform: translateY(-1px);
        }
        
        .demo-accounts {
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            padding: 1.5rem;
            border-radius: 15px;
            margin-bottom: 2rem;
            border: 1px solid #dee2e6;
        }
        
        .demo-accounts h5 {
            color: #495057;
            margin-bottom: 1rem;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        
        .demo-account {
            background: white;
            padding: 0.75rem 1rem;
            border-radius: 8px;
            margin-bottom: 0.5rem;
            border-left: 4px solid #667eea;
            box-shadow: 0 2px 8px rgba(0,0,0,0.05);
        }
        
        .demo-account:last-child {
            margin-bottom: 0;
            border-left-color: #28a745;
        }
        
        .demo-account small {
            font-weight: 500;
        }
        
        .alert {
            border-radius: 12px;
            border: none;
            padding: 1rem;
            margin-bottom: 1.5rem;
        }
        
        .alert-success {
            background: linear-gradient(135deg, #d4edda 0%, #c3e6cb 100%);
            color: #155724;
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
        
        @media (max-width: 576px) {
            .login-container {
                margin: 1rem;
                padding: 2rem 1.5rem;
            }
            
            .login-header h1 {
                font-size: 1.5rem;
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
            <p class="mb-0"><a href="/webbanhang/"><i class="bi bi-arrow-left me-1"></i>Quay về trang chủ</a></p>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html> 
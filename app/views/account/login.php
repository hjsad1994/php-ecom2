<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng nhập - Website Bán Hàng</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .login-container {
            background: white;
            padding: 40px;
            border-radius: 10px;
            box-shadow: 0 15px 25px rgba(0,0,0,0.1);
            width: 100%;
            max-width: 400px;
        }
        
        .login-header {
            text-align: center;
            margin-bottom: 30px;
        }
        
        .login-header h1 {
            color: #333;
            font-size: 28px;
            margin-bottom: 10px;
        }
        
        .login-header p {
            color: #666;
            font-size: 14px;
        }
        
        .form-group {
            margin-bottom: 20px;
        }
        
        .form-group label {
            display: block;
            margin-bottom: 5px;
            color: #333;
            font-weight: 500;
        }
        
        .form-group input {
            width: 100%;
            padding: 12px;
            border: 2px solid #ddd;
            border-radius: 5px;
            font-size: 16px;
            transition: border-color 0.3s;
        }
        
        .form-group input:focus {
            outline: none;
            border-color: #667eea;
        }
        
        .error-message {
            color: #e74c3c;
            font-size: 13px;
            margin-top: 5px;
        }
        
        .success-message {
            color: #27ae60;
            font-size: 14px;
            margin-bottom: 15px;
            text-align: center;
            padding: 10px;
            background: #d5f4e6;
            border-radius: 5px;
        }
        
        .btn-login {
            width: 100%;
            padding: 12px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: transform 0.2s;
        }
        
        .btn-login:hover {
            transform: translateY(-2px);
        }
        
        .register-link {
            text-align: center;
            margin-top: 20px;
        }
        
        .register-link a {
            color: #667eea;
            text-decoration: none;
        }
        
        .register-link a:hover {
            text-decoration: underline;
        }
        
        .back-home {
            text-align: center;
            margin-top: 15px;
        }
        
        .back-home a {
            color: #666;
            text-decoration: none;
            font-size: 14px;
        }
        
        .demo-accounts {
            background: #f8f9fa;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
            font-size: 12px;
        }
        
        .demo-accounts h4 {
            color: #333;
            margin-bottom: 8px;
        }
        
        .demo-accounts p {
            color: #666;
            margin-bottom: 3px;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="login-header">
            <h1>Đăng nhập</h1>
            <p>Đăng nhập để tiếp tục mua sắm</p>
        </div>
        
        <!-- Demo accounts info -->
        <div class="demo-accounts">
            <h4>Tài khoản demo:</h4>
            <p><strong>Admin:</strong> admin / admin123</p>
            <p><strong>User:</strong> user1 / user123</p>
        </div>
        
        <?php if(isset($_GET['registered']) && $_GET['registered'] == 'success'): ?>
            <div class="success-message">
                Đăng ký thành công! Vui lòng đăng nhập.
            </div>
        <?php endif; ?>
        
        <form action="/webbanhang/account/checkLogin" method="POST">
            <div class="form-group">
                <label for="username">Tên đăng nhập:</label>
                <input type="text" id="username" name="username" 
                       value="<?= $_POST['username'] ?? '' ?>" required>
                <?php if(isset($errors['username'])): ?>
                    <div class="error-message"><?= $errors['username'] ?></div>
                <?php endif; ?>
            </div>
            
            <div class="form-group">
                <label for="password">Mật khẩu:</label>
                <input type="password" id="password" name="password" required>
                <?php if(isset($errors['password'])): ?>
                    <div class="error-message"><?= $errors['password'] ?></div>
                <?php endif; ?>
            </div>
            
            <?php if(isset($errors['login'])): ?>
                <div class="error-message" style="margin-bottom: 15px; text-align: center;">
                    <?= $errors['login'] ?>
                </div>
            <?php endif; ?>
            
            <button type="submit" class="btn-login">Đăng nhập</button>
        </form>
        
        <div class="register-link">
            <p>Chưa có tài khoản? <a href="/webbanhang/account/register">Đăng ký ngay</a></p>
        </div>
        
        <div class="back-home">
            <a href="/webbanhang/product">← Quay về trang chủ</a>
        </div>
    </div>
</body>
</html> 
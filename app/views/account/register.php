<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng ký tài khoản - Website Bán Hàng</title>
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
        
        .register-container {
            background: white;
            padding: 40px;
            border-radius: 10px;
            box-shadow: 0 15px 25px rgba(0,0,0,0.1);
            width: 100%;
            max-width: 400px;
        }
        
        .register-header {
            text-align: center;
            margin-bottom: 30px;
        }
        
        .register-header h1 {
            color: #333;
            font-size: 28px;
            margin-bottom: 10px;
        }
        
        .register-header p {
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
        
        .btn-register {
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
        
        .btn-register:hover {
            transform: translateY(-2px);
        }
        
        .login-link {
            text-align: center;
            margin-top: 20px;
        }
        
        .login-link a {
            color: #667eea;
            text-decoration: none;
        }
        
        .login-link a:hover {
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
    </style>
</head>
<body>
    <div class="register-container">
        <div class="register-header">
            <h1>Đăng ký</h1>
            <p>Tạo tài khoản mới để bắt đầu mua sắm</p>
        </div>
        
        <form action="/webbanhang/account/save" method="POST">
            <div class="form-group">
                <label for="username">Tên đăng nhập:</label>
                <input type="text" id="username" name="username" 
                       value="<?= $_POST['username'] ?? '' ?>" required>
                <?php if(isset($errors['username'])): ?>
                    <div class="error-message"><?= $errors['username'] ?></div>
                <?php endif; ?>
            </div>
            
            <div class="form-group">
                <label for="fullname">Họ và tên:</label>
                <input type="text" id="fullname" name="fullname" 
                       value="<?= $_POST['fullname'] ?? '' ?>" required>
                <?php if(isset($errors['fullname'])): ?>
                    <div class="error-message"><?= $errors['fullname'] ?></div>
                <?php endif; ?>
            </div>
            
            <div class="form-group">
                <label for="password">Mật khẩu:</label>
                <input type="password" id="password" name="password" required>
                <?php if(isset($errors['password'])): ?>
                    <div class="error-message"><?= $errors['password'] ?></div>
                <?php endif; ?>
            </div>
            
            <div class="form-group">
                <label for="confirmpassword">Xác nhận mật khẩu:</label>
                <input type="password" id="confirmpassword" name="confirmpassword" required>
                <?php if(isset($errors['confirmPass'])): ?>
                    <div class="error-message"><?= $errors['confirmPass'] ?></div>
                <?php endif; ?>
            </div>
            
            <?php if(isset($errors['account'])): ?>
                <div class="error-message" style="margin-bottom: 15px; text-align: center;">
                    <?= $errors['account'] ?>
                </div>
            <?php endif; ?>
            
            <?php if(isset($errors['database'])): ?>
                <div class="error-message" style="margin-bottom: 15px; text-align: center;">
                    <?= $errors['database'] ?>
                </div>
            <?php endif; ?>
            
            <?php if(isset($errors['save'])): ?>
                <div class="error-message" style="margin-bottom: 15px; text-align: center;">
                    <?= $errors['save'] ?>
                </div>
            <?php endif; ?>
            
            <?php if(isset($errors['exception'])): ?>
                <div class="error-message" style="margin-bottom: 15px; text-align: center;">
                    <?= $errors['exception'] ?>
                </div>
            <?php endif; ?>
            
            <button type="submit" class="btn-register">Đăng ký</button>
        </form>
        
        <div class="login-link">
            <p>Đã có tài khoản? <a href="/webbanhang/account/login">Đăng nhập ngay</a></p>
        </div>
        
        <div class="back-home">
            <a href="/webbanhang/product">← Quay về trang chủ</a>
        </div>
    </div>
</body>
</html> 
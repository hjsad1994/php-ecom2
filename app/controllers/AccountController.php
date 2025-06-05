<?php
require_once('app/config/database.php');
require_once('app/models/AccountModel.php');
require_once('app/models/PasswordResetModel.php');
require_once('app/helpers/AuthHelper.php');
require_once('app/helpers/EmailHelper.php');

class AccountController {
    private $accountModel;
    private $passwordResetModel;
    private $db;
    
    public function __construct() {
        $this->db = (new Database())->getConnection();
        $this->accountModel = new AccountModel($this->db);
        $this->passwordResetModel = new PasswordResetModel($this->db);
    }
    
    function register(){
        include_once 'app/views/account/register.php';
    }
    
    public function login() {
        include_once 'app/views/account/login.php';
    }
    
    function save(){
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $username = $_POST['username'] ?? '';
            $fullName = $_POST['fullname'] ?? '';
            $email = $_POST['email'] ?? '';
            $password = $_POST['password'] ?? '';
            $confirmPassword = $_POST['confirmpassword'] ?? '';
            $errors = [];
            
            // Debug: Log received data
            error_log("AccountController save - received data: username=$username, fullName=$fullName, email=$email");
            
            if(empty($username)){
                $errors['username'] = "Vui lòng nhập username!";
            }
            if(empty($fullName)){
                $errors['fullname'] = "Vui lòng nhập họ tên!";
            }
            if(empty($email)){
                $errors['email'] = "Vui lòng nhập email!";
            } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $errors['email'] = "Email không hợp lệ!";
            }
            if(empty($password)){
                $errors['password'] = "Vui lòng nhập password!";
            }
            if($password != $confirmPassword){
                $errors['confirmPass'] = "Mật khẩu và xác nhận chưa đúng";
            }
            
            // Kiểm tra username và email đã được đăng ký chưa?
            try {
                $account = $this->accountModel->getAccountByUsername($username);
                if($account){
                    $errors['username'] = "Username này đã có người sử dụng!";
                }
                
                // Kiểm tra email
                $accountByEmail = $this->accountModel->getAccountByEmail($email);
                if($accountByEmail){
                    $errors['email'] = "Email này đã được đăng ký!";
                }
            } catch (Exception $e) {
                error_log("AccountController save - getAccountByUsername error: " . $e->getMessage());
                $errors['database'] = "Lỗi kết nối database!";
            }
            
            if(count($errors) > 0){
                error_log("AccountController save - validation errors: " . print_r($errors, true));
                include_once 'app/views/account/register.php';
            } else {
                try {
                    $password = password_hash($password, PASSWORD_BCRYPT, ['cost' => 12]);
                    error_log("AccountController save - attempting to save user: $username");
                    $result = $this->accountModel->save($username, $fullName, $email, $password);
                    if($result){
                        error_log("AccountController save - success, sending welcome email");
                        
                        // Gửi email chào mừng
                        $emailSent = EmailHelper::sendWelcomeEmail($email, $fullName);
                        if ($emailSent) {
                            error_log("AccountController save - welcome email sent successfully");
                        } else {
                            error_log("AccountController save - failed to send welcome email");
                        }
                        
                        $_SESSION['success'] = "Đăng ký thành công! Chúng tôi đã gửi email chào mừng đến $email";
                        header('Location: /webbanhang/account/login');
                        exit;
                    } else {
                        error_log("AccountController save - save failed");
                        $errors['save'] = "Lỗi khi lưu tài khoản!";
                        include_once 'app/views/account/register.php';
                    }
                } catch (Exception $e) {
                    error_log("AccountController save - exception: " . $e->getMessage());
                    $errors['exception'] = "Lỗi hệ thống: " . $e->getMessage();
                    include_once 'app/views/account/register.php';
                }
            }
        }
    }
    
    function logout(){
        session_start();
        unset($_SESSION['username']);
        unset($_SESSION['user_role']);
        unset($_SESSION['user_id']);
        session_destroy();
        header('Location: /webbanhang/product');
        exit;
    }
    
    public function checkLogin(){
        // Kiểm tra xem liệu form đã được submit
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $username = $_POST['username'] ?? '';
            $password = $_POST['password'] ?? '';
            $errors = [];
            
            if(empty($username)){
                $errors['username'] = "Vui lòng nhập username!";
            }
            if(empty($password)){
                $errors['password'] = "Vui lòng nhập password!";
            }
            
            if(count($errors) > 0){
                include_once 'app/views/account/login.php';
                return;
            }
            
            $account = $this->accountModel->getAccountByUsername($username);
            if ($account) {
                $pwd_hashed = $account->password;
                // Check mật khẩu
                if (password_verify($password, $pwd_hashed)) {
                    session_start();
                    $_SESSION['username'] = $account->username;
                    $_SESSION['user_role'] = $account->role ?? 'user';
                    $_SESSION['user_id'] = $account->id;
                    
                    // Redirect dựa trên role
                    if ($account->role === 'admin') {
                        header('Location: /webbanhang/admin/dashboard');
                    } else {
                        header('Location: /webbanhang/product');
                    }
                    exit;
                } else {
                    $errors['login'] = "Mật khẩu không đúng.";
                }
            } else {
                $errors['login'] = "Không tìm thấy tài khoản";
            }
            
            if(count($errors) > 0){
                include_once 'app/views/account/login.php';
            }
        }
    }

    public function profile() {
        // Require user to be logged in
        if (!isset($_SESSION['user_id'])) {
            header('Location: /webbanhang/account/login');
            exit;
        }
        
        // Get user information
        $userId = $_SESSION['user_id'];
        try {
            $query = "SELECT * FROM account WHERE id = :id";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':id', $userId);
            $stmt->execute();
            $user = $stmt->fetch(PDO::FETCH_OBJ);
            
            if (!$user) {
                header('Location: /webbanhang/account/login');
                exit;
            }
            
            // Get user's order statistics
            $orderQuery = "SELECT COUNT(*) as total_orders, 
                                 SUM(CASE WHEN order_status = 'completed' THEN 1 ELSE 0 END) as completed_orders,
                                 SUM(CASE WHEN order_status = 'pending' THEN 1 ELSE 0 END) as pending_orders
                          FROM orders WHERE user_id = :user_id";
            $orderStmt = $this->db->prepare($orderQuery);
            $orderStmt->bindParam(':user_id', $userId);
            $orderStmt->execute();
            $orderStats = $orderStmt->fetch(PDO::FETCH_OBJ);
            
            include_once 'app/views/user/profile/index.php';
            
        } catch (Exception $e) {
            error_log("Profile error: " . $e->getMessage());
            http_response_code(500);
            die('Lỗi hệ thống');
        }
    }

    public function updateProfile() {
        // Require user to be logged in
        if (!isset($_SESSION['user_id'])) {
            header('Location: /webbanhang/account/login');
            exit;
        }
        
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $userId = $_SESSION['user_id'];
            $fullName = $_POST['fullname'] ?? '';
            $email = $_POST['email'] ?? '';
            $phone = $_POST['phone'] ?? '';
            $address = $_POST['address'] ?? '';
            $errors = [];
            
            // Validation
            if (empty($fullName)) {
                $errors['fullname'] = "Vui lòng nhập họ tên!";
            }
            
            if (count($errors) == 0) {
                try {
                    $query = "UPDATE account SET fullname = :fullname, email = :email, phone = :phone, address = :address WHERE id = :id";
                    $stmt = $this->db->prepare($query);
                    $stmt->bindParam(':fullname', $fullName);
                    $stmt->bindParam(':email', $email);
                    $stmt->bindParam(':phone', $phone);
                    $stmt->bindParam(':address', $address);
                    $stmt->bindParam(':id', $userId);
                    
                    if ($stmt->execute()) {
                        $_SESSION['success'] = "Cập nhật thông tin thành công!";
                        header('Location: /webbanhang/user/profile');
                        exit;
                    } else {
                        $errors['update'] = "Lỗi khi cập nhật thông tin!";
                    }
                } catch (Exception $e) {
                    error_log("Update profile error: " . $e->getMessage());
                    $errors['exception'] = "Lỗi hệ thống!";
                }
            }
            
            // If there are errors, redirect back with errors
            $_SESSION['errors'] = $errors;
            header('Location: /webbanhang/user/profile');
            exit;
        }
    }

    /**
     * Hiển thị form quên mật khẩu
     */
    public function forgotPassword() {
        include_once 'app/views/account/forgot_password.php';
    }

    /**
     * Xử lý yêu cầu quên mật khẩu
     */
    public function processForgotPassword() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $email = trim($_POST['email'] ?? '');
            $errors = [];

            // Validation
            if (empty($email)) {
                $errors['email'] = "Vui lòng nhập email!";
            } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $errors['email'] = "Email không hợp lệ!";
            }

            if (count($errors) == 0) {
                // Kiểm tra email có tồn tại trong hệ thống không
                if ($this->passwordResetModel->emailExists($email)) {
                    // Tạo token reset
                    $token = $this->passwordResetModel->createResetToken($email);
                    
                    if ($token) {
                        // Gửi email
                        if (EmailHelper::sendPasswordResetEmail($email, $token)) {
                            $_SESSION['success'] = "Chúng tôi đã gửi link đặt lại mật khẩu đến email của bạn. Vui lòng kiểm tra hộp thư!";
                            header('Location: /webbanhang/account/login');
                            exit;
                        } else {
                            $errors['email'] = "Không thể gửi email. Vui lòng thử lại sau!";
                        }
                    } else {
                        $errors['system'] = "Lỗi hệ thống. Vui lòng thử lại sau!";
                    }
                } else {
                    // Không tìm thấy email, nhưng không tiết lộ thông tin này
                    $_SESSION['success'] = "Nếu email này tồn tại trong hệ thống, chúng tôi đã gửi link đặt lại mật khẩu đến email của bạn.";
                    header('Location: /webbanhang/account/login');
                    exit;
                }
            }

            // Có lỗi, hiển thị lại form
            include_once 'app/views/account/forgot_password.php';
        }
    }

    /**
     * Hiển thị form đặt lại mật khẩu
     */
    public function resetPassword() {
        $token = $_GET['token'] ?? '';
        
        if (empty($token)) {
            $_SESSION['error'] = "Link không hợp lệ!";
            header('Location: /webbanhang/account/login');
            exit;
        }

        // Kiểm tra token có hợp lệ không
        $resetData = $this->passwordResetModel->validateResetToken($token);
        if (!$resetData) {
            $_SESSION['error'] = "Link đã hết hạn hoặc không hợp lệ!";
            header('Location: /webbanhang/account/login');
            exit;
        }

        include_once 'app/views/account/reset_password.php';
    }

    /**
     * Xử lý đặt lại mật khẩu
     */
    public function processResetPassword() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $token = $_POST['token'] ?? '';
            $newPassword = $_POST['password'] ?? '';
            $confirmPassword = $_POST['confirm_password'] ?? '';
            $errors = [];

            // Validation
            if (empty($token)) {
                $errors['token'] = "Token không hợp lệ!";
            }
            if (empty($newPassword)) {
                $errors['password'] = "Vui lòng nhập mật khẩu mới!";
            } elseif (strlen($newPassword) < 6) {
                $errors['password'] = "Mật khẩu phải có ít nhất 6 ký tự!";
            }
            if ($newPassword !== $confirmPassword) {
                $errors['confirm_password'] = "Xác nhận mật khẩu không khớp!";
            }

            if (count($errors) == 0) {
                // Kiểm tra token
                $resetData = $this->passwordResetModel->validateResetToken($token);
                if ($resetData) {
                    // Cập nhật mật khẩu
                    if ($this->passwordResetModel->updatePassword($resetData->email, $newPassword)) {
                        // Đánh dấu token đã sử dụng
                        $this->passwordResetModel->markTokenAsUsed($token);
                        
                        $_SESSION['success'] = "Đặt lại mật khẩu thành công! Vui lòng đăng nhập với mật khẩu mới.";
                        header('Location: /webbanhang/account/login');
                        exit;
                    } else {
                        $errors['system'] = "Lỗi khi cập nhật mật khẩu!";
                    }
                } else {
                    $errors['token'] = "Token đã hết hạn hoặc không hợp lệ!";
                }
            }

            // Có lỗi, hiển thị lại form
            include_once 'app/views/account/reset_password.php';
        }
    }
} 
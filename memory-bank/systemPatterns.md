# System Patterns - Website Bán Hàng

## Kiến trúc tổng thể

### MVC Pattern
Dự án sử dụng kiến trúc MVC (Model-View-Controller) đơn giản:

```
app/
├── controllers/     # Business Logic Layer
│   ├── ProductController.php
│   ├── CategoryController.php
│   ├── VoucherController.php
│   └── AuthController.php      # 📋 NEW: Authentication logic
├── models/          # Data Access Layer
│   ├── ProductModel.php
│   ├── CategoryModel.php
│   ├── VoucherModel.php
│   └── UserModel.php           # 📋 NEW: User data access
└── views/           # Presentation Layer
    ├── product/
    ├── category/
    ├── voucher/
    ├── auth/                   # 📋 NEW: Authentication views
    │   ├── register.php
    │   ├── login.php
    │   └── logout.php
    └── shares/      # Shared components
```

### URL Routing
```php
// Pattern: /controller/action/param1/param2
// Examples:
// /product/index          → ProductController::index()
// /product/detail/123     → ProductController::detail(123)
// /category/list          → CategoryController::list()
// /voucher/create         → VoucherController::create()
// /auth/register          → AuthController::register()      # NEW
// /auth/login             → AuthController::login()         # NEW  
// /auth/logout            → AuthController::logout()        # NEW
```

## Controllers Pattern

### Base Structure
Mỗi controller xử lý một domain cụ thể:

```php
class ProductController {
    public function index() {
        // Hiển thị danh sách sản phẩm
    }
    
    public function detail($id) {
        // Hiển thị chi tiết sản phẩm
    }
    
    public function create() {
        // Form tạo sản phẩm mới
    }
    
    public function store() {
        // Xử lý lưu sản phẩm
    }
}
```

### Authentication Controller Pattern
```php
class AuthController {
    public function register() {
        // Hiển thị form đăng ký hoặc xử lý đăng ký
    }
    
    public function login() {
        // Hiển thị form đăng nhập hoặc xử lý đăng nhập
    }
    
    public function logout() {
        // Xử lý đăng xuất và redirect
    }
    
    private function validateInput($data) {
        // Validation và sanitization
    }
    
    private function hashPassword($password) {
        // Password hashing với security
    }
}
```

### Responsibility Separation
- **ProductController**: Quản lý CRUD sản phẩm, hiển thị danh sách, chi tiết
- **CategoryController**: Quản lý danh mục sản phẩm
- **VoucherController**: Quản lý mã giảm giá, validate voucher
- **AuthController**: 📋 NEW - Quản lý authentication (register/login/logout)

## Models Pattern

### Data Access Layer
Models chịu trách nhiệm tương tác với database:

```php
class ProductModel {
    private $db;
    
    public function getAll() {
        // Lấy tất cả sản phẩm
    }
    
    public function getById($id) {
        // Lấy sản phẩm theo ID
    }
    
    public function create($data) {
        // Tạo sản phẩm mới
    }
    
    public function update($id, $data) {
        // Cập nhật sản phẩm
    }
}
```

### User Model Pattern
```php
class UserModel {
    private $db;
    
    public function create($userData) {
        // Tạo user mới với password hashing
        $hashedPassword = password_hash($userData['password'], PASSWORD_DEFAULT);
        // Insert vào create_account_table
    }
    
    public function findByEmail($email) {
        // Tìm user theo email
    }
    
    public function authenticate($email, $password) {
        // Verify credentials với password_verify()
    }
    
    public function updateLoginTime($userId) {
        // Update last login timestamp
    }
}
```

### Database Connection
- Sử dụng PDO cho database connection
- Prepared statements để tránh SQL injection
- Connection pooling/reuse trong models
- 📋 NEW: User table `create_account_table` integration

## Authentication Patterns

### Session-based Authentication
```php
// Login success
session_start();
$_SESSION['user_id'] = $userId;
$_SESSION['user_email'] = $userEmail;
$_SESSION['logged_in'] = true;

// Check authentication
function isAuthenticated() {
    return isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true;
}

// Logout
session_destroy();
unset($_SESSION['user_id']);
```

### Password Security Pattern
```php
// Registration
$hashedPassword = password_hash($password, PASSWORD_DEFAULT);

// Login verification  
if (password_verify($inputPassword, $storedHashedPassword)) {
    // Login successful
}
```

### Input Validation Pattern
```php
class AuthController {
    private function validateRegistration($data) {
        $errors = [];
        
        // Email validation
        if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            $errors[] = 'Invalid email format';
        }
        
        // Password strength
        if (strlen($data['password']) < 8) {
            $errors[] = 'Password must be at least 8 characters';
        }
        
        return $errors;
    }
}
```

## Views Pattern

### Template Structure
```
views/
├── product/
│   ├── index.php      # Danh sách sản phẩm
│   ├── detail.php     # Chi tiết sản phẩm
│   └── form.php       # Form tạo/sửa
├── auth/              # 📋 NEW: Authentication views
│   ├── register.php   # Registration form
│   ├── login.php      # Login form
│   └── messages.php   # Success/error messages
├── shares/
│   ├── header.php     # Common header (with auth status)
│   ├── footer.php     # Common footer
│   └── sidebar.php    # Navigation (with login/logout)
```

### Authentication View Pattern
```php
// auth/login.php
<form method="POST" action="/auth/login">
    <input type="email" name="email" required>
    <input type="password" name="password" required>
    <button type="submit">Đăng nhập</button>
</form>

// shares/header.php - User context
<?php if (isAuthenticated()): ?>
    <span>Xin chào, <?= htmlspecialchars($_SESSION['user_email']) ?></span>
    <a href="/auth/logout">Đăng xuất</a>
<?php else: ?>
    <a href="/auth/login">Đăng nhập</a>
    <a href="/auth/register">Đăng ký</a>
<?php endif; ?>
```

### Data Passing
Controllers pass data to views thông qua variables:
```php
// In controller
$products = $productModel->getAll();
include 'app/views/product/index.php';

// In view
foreach ($products as $product) {
    echo $product['name'];
}
```

## Security Patterns

### Input Validation
- Sanitize input: `filter_var()`, `htmlspecialchars()`
- Validate data types và ranges
- CSRF protection cho forms
- 📋 NEW: Email/password validation cho authentication

### Database Security
- Prepared statements cho tất cả queries
- Escape output khi hiển thị
- Validate file uploads
- 📋 NEW: Password hashing cho user credentials

### Authentication Security
```php
// CSRF Protection
session_start();
if (!isset($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

// Verify CSRF token
if ($_POST['csrf_token'] !== $_SESSION['csrf_token']) {
    die('CSRF token mismatch');
}

// Rate limiting (simple implementation)
if (isset($_SESSION['login_attempts']) && $_SESSION['login_attempts'] > 5) {
    die('Too many login attempts');
}
```

### Session Security
- Session-based authentication
- Password hashing với `password_hash()`
- Role-based access control
- 📋 NEW: Secure session configuration
- 📋 NEW: Session regeneration on login

## Error Handling Patterns

### Exception Handling
```php
try {
    call_user_func_array([$controller, $action], $params);
} catch (Exception $e) {
    error_log("Error: " . $e->getMessage());
    http_response_code(500);
    die('An error occurred');
}
```

### Authentication Error Handling
```php
// AuthController error handling
try {
    $result = $this->userModel->authenticate($email, $password);
    if (!$result) {
        $error = 'Invalid credentials';
        include 'app/views/auth/login.php';
        return;
    }
    // Success logic
} catch (Exception $e) {
    error_log("Auth error: " . $e->getMessage());
    $error = 'Authentication failed';
    include 'app/views/auth/login.php';
}
```

### HTTP Status Codes
- 404: Controller/Action not found
- 500: Server errors
- 200: Successful requests
- 📋 NEW: 401: Unauthorized access
- 📋 NEW: 403: Forbidden (insufficient permissions)

## File Organization Patterns

### Autoloading Strategy
- Manual includes cho simplicity
- Consistent naming convention
- Clear separation of concerns

### Configuration Management
```
app/config/
├── database.php     # DB connection settings
├── app.php         # Application settings
├── session.php     # 📋 NEW: Session configuration
└── constants.php   # App constants
```

### Session Configuration
```php
// app/config/session.php
return [
    'name' => 'WEBBANHANG_SESSION',
    'lifetime' => 3600, // 1 hour
    'secure' => false, // true for HTTPS
    'httponly' => true,
    'samesite' => 'Strict'
];
```

## Performance Patterns

### Caching Strategy
- Browser caching cho static assets
- Database query optimization
- Image optimization cho product images
- 📋 NEW: Session data caching

### Database Optimization
- Proper indexing
- Efficient queries
- Pagination cho large datasets
- 📋 NEW: User table indexing (email, id)

## Code Quality Patterns

### Naming Conventions
- Controllers: `PascalCase` + "Controller"
- Methods: `camelCase`
- Variables: `snake_case` hoặc `camelCase`
- Constants: `UPPER_CASE`

### Code Organization
- Single Responsibility Principle
- DRY (Don't Repeat Yourself)
- Clear method signatures
- Meaningful variable names

### Authentication Code Standards
```php
// Clear method naming
public function registerUser($userData) { /* */ }
public function authenticateUser($email, $password) { /* */ }
public function logoutUser() { /* */ }

// Consistent error handling
private function handleAuthError($message) {
    error_log("Auth: " . $message);
    // User-friendly error display
}
```

## Extension Points

### Adding New Features
1. Tạo Controller mới trong `app/controllers/`
2. Tạo Model tương ứng trong `app/models/`
3. Tạo views trong `app/views/feature_name/`
4. Update routing nếu cần

### Authentication Extensions
- **Role-based access**: Add user roles to database
- **Email verification**: Send verification emails after registration
- **Password reset**: Forgot password functionality
- **Two-factor authentication**: SMS or email-based 2FA
- **OAuth integration**: Social login (Google, Facebook)

### Third-party Integration
- Payment gateways: Thêm vào controllers
- Email services: Service layer riêng biệt
- SMS/notifications: Observer pattern
- 📋 NEW: Authentication middleware pattern 
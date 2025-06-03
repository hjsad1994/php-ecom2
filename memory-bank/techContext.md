# Tech Context - Website Bán Hàng

## Technology Stack

### Core Technologies
- **Language**: PHP 7.4+ (thuần, không framework)
- **Architecture**: MVC Pattern
- **Server**: Apache (via XAMPP)
- **Database**: MySQL 8.0+ (dự kiến)
- **Frontend**: HTML5, CSS3, JavaScript (Vanilla)

### Development Environment
- **Local Server**: XAMPP
  - Apache: Web server
  - MySQL: Database server
  - PHP: Server-side language
  - phpMyAdmin: Database management
- **OS**: macOS (Darwin 24.5.0)
- **Shell**: Zsh
- **Path**: `/Applications/XAMPP/xamppfiles/htdocs/webbanhang`

## Project Structure

### Directory Layout
```
webbanhang/
├── index.php              # Application entry point
├── .htaccess              # URL rewriting rules
├── .DS_Store              # macOS metadata (ignore)
├── app/
│   ├── controllers/       # Business logic
│   │   ├── ProductController.php
│   │   ├── CategoryController.php
│   │   ├── VoucherController.php
│   │   └── AuthController.php     # 📋 NEW: Authentication controller
│   ├── models/           # Data access layer
│   │   ├── ProductModel.php
│   │   ├── CategoryModel.php
│   │   ├── VoucherModel.php
│   │   └── UserModel.php          # 📋 NEW: User data model
│   ├── views/            # Presentation layer
│   │   ├── product/
│   │   ├── category/
│   │   ├── voucher/
│   │   ├── auth/                  # 📋 NEW: Authentication views
│   │   │   ├── register.php
│   │   │   ├── login.php
│   │   │   └── messages.php
│   │   └── shares/       # Shared components
│   └── config/           # Configuration files
│       ├── database.php  # Database connection
│       └── session.php   # 📋 NEW: Session configuration
├── public/
│   └── uploads/          # User uploaded files
├── .git/                 # Git repository
├── .cursor/              # Cursor editor config
└── memory-bank/          # Project documentation
```

## PHP Configuration

### Requirements
- PHP Version: 7.4+
- Required Extensions:
  - PDO (database connectivity)
  - PDO_MySQL (MySQL driver)
  - GD (image processing)
  - Session (session management)
  - Filter (input validation)
  - JSON (API responses)
  - 📋 NEW: Password hashing support (built-in PHP 7.4+)

### PHP Settings
```ini
# Recommended php.ini settings
upload_max_filesize = 10M
post_max_size = 10M
max_execution_time = 30
memory_limit = 128M
display_errors = On (development)
log_errors = On

# 📋 NEW: Session security settings
session.cookie_httponly = 1
session.cookie_secure = 0 (1 for HTTPS)
session.cookie_samesite = "Strict"
session.use_strict_mode = 1
session.gc_maxlifetime = 3600
```

## Database Design

### Connection Strategy
- **Driver**: PDO MySQL
- **Connection**: Singleton pattern trong base model
- **Configuration**: Environment-based config files
- **Security**: Prepared statements only

### Database Schema (Updated)
```sql
-- Products table
CREATE TABLE products (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(255) NOT NULL,
    description TEXT,
    price DECIMAL(10,2) NOT NULL,
    category_id INT,
    image VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Categories table
CREATE TABLE categories (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(255) NOT NULL,
    description TEXT,
    parent_id INT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Vouchers table
CREATE TABLE vouchers (
    id INT PRIMARY KEY AUTO_INCREMENT,
    code VARCHAR(50) UNIQUE NOT NULL,
    type ENUM('percentage', 'fixed') NOT NULL,
    value DECIMAL(10,2) NOT NULL,
    min_order_value DECIMAL(10,2),
    max_uses INT,
    used_count INT DEFAULT 0,
    valid_from DATE,
    valid_until DATE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- 📋 User table (create_account_table - đã có)
-- Structure cần xác định từ database hiện tại
-- Dự kiến structure:
CREATE TABLE create_account_table (
    id INT PRIMARY KEY AUTO_INCREMENT,
    email VARCHAR(255) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,  -- hashed password
    full_name VARCHAR(255),
    phone VARCHAR(20),
    address TEXT,
    role ENUM('customer', 'admin') DEFAULT 'customer',
    email_verified BOOLEAN DEFAULT FALSE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    last_login TIMESTAMP NULL,
    
    -- Indexes for performance
    INDEX idx_email (email),
    INDEX idx_role (role),
    INDEX idx_created_at (created_at)
);
```

## Authentication Technology Stack

### Password Security
```php
// Password hashing (PHP 7.4+ built-in)
$hashedPassword = password_hash($password, PASSWORD_DEFAULT);

// Password verification
$isValid = password_verify($inputPassword, $storedHash);
```

### Session Management
```php
// Session configuration
session_start([
    'name' => 'WEBBANHANG_SESSION',
    'cookie_lifetime' => 3600,
    'cookie_httponly' => true,
    'cookie_secure' => false, // true for HTTPS
    'cookie_samesite' => 'Strict',
    'use_strict_mode' => true
]);
```

### Security Headers
```php
// Security headers for authentication
header('X-Content-Type-Options: nosniff');
header('X-Frame-Options: DENY');
header('X-XSS-Protection: 1; mode=block');
header('Referrer-Policy: strict-origin-when-cross-origin');
```

## Frontend Technologies

### HTML/CSS
- **HTML5**: Semantic markup
- **CSS3**: Modern styling, Flexbox/Grid
- **Responsive**: Mobile-first design
- **Framework**: Không sử dụng (custom CSS)

### JavaScript
- **Vanilla JS**: Không jQuery hay framework
- **Features**: 
  - Form validation
  - AJAX requests
  - Shopping cart functionality
  - Image upload preview
  - 📋 NEW: Authentication form validation
  - 📋 NEW: Password strength indicator

### Assets Organization
```
public/
├── css/
│   ├── main.css
│   ├── responsive.css
│   ├── admin.css
│   └── auth.css           # 📋 NEW: Authentication styles
├── js/
│   ├── app.js
│   ├── cart.js
│   ├── admin.js
│   └── auth.js            # 📋 NEW: Authentication JavaScript
├── images/
│   ├── logo.png
│   └── icons/
└── uploads/
    ├── products/
    └── categories/
```

## Development Tools

### Version Control
- **Git**: Source control
- **GitHub/GitLab**: Remote repository
- **Branching**: Feature branches, main branch

### Code Editor
- **Cursor**: Primary IDE with AI assistance
- **Extensions**: PHP, HTML, CSS, JavaScript support

### Debugging Tools
- **PHP Error Reporting**: Enabled in development
- **Browser DevTools**: Frontend debugging
- **phpMyAdmin**: Database inspection
- **Error Logs**: Apache error logs

### Authentication Testing Tools
- **Session debugging**: PHP session inspection
- **Password testing**: Hash verification tools
- **Security testing**: Input validation testing

## Deployment Considerations

### Local Development
- XAMPP on macOS
- Document root: `/Applications/XAMPP/xamppfiles/htdocs/`
- URL: `http://localhost/webbanhang`

### Production Requirements
- **Server**: Apache 2.4+ with mod_rewrite
- **PHP**: 7.4+ with required extensions
- **Database**: MySQL 8.0+
- **SSL**: HTTPS certificate (required for secure auth)
- **Backup**: Database and file backups

### Configuration Management
```php
// app/config/database.php
return [
    'host' => $_ENV['DB_HOST'] ?? 'localhost',
    'dbname' => $_ENV['DB_NAME'] ?? 'webbanhang',
    'username' => $_ENV['DB_USER'] ?? 'root',
    'password' => $_ENV['DB_PASS'] ?? '',
    'charset' => 'utf8mb4'
];

// 📋 NEW: app/config/session.php
return [
    'name' => 'WEBBANHANG_SESSION',
    'lifetime' => 3600,
    'secure' => $_ENV['HTTPS'] ?? false,
    'httponly' => true,
    'samesite' => 'Strict',
    'regenerate_id' => true
];
```

## Performance Optimization

### PHP Optimization
- OPcache enabled
- Efficient database queries
- Proper error handling
- Memory management

### Frontend Optimization
- Image optimization
- CSS/JS minification
- Browser caching headers
- Gzip compression

### Database Optimization
- Proper indexing
- Query optimization
- Connection pooling
- Regular maintenance
- 📋 NEW: User table indexing (email, role, created_at)

### Authentication Performance
- **Session optimization**: Efficient session storage
- **Password hashing**: Appropriate cost factor
- **Database queries**: Indexed user lookups
- **Rate limiting**: Prevent brute force attacks

## Security Measures

### Input Security
- Input validation and sanitization
- SQL injection prevention (prepared statements)
- XSS protection (output escaping)
- CSRF protection

### File Security
- Upload restrictions
- File type validation
- Secure file paths
- Directory traversal prevention

### Session Security
- Secure session configuration
- Session regeneration
- Timeout handling
- Secure cookies

### Authentication Security
```php
// Security implementation checklist
// ✅ Password hashing với PASSWORD_DEFAULT
// ✅ Prepared statements cho user queries
// 📋 CSRF token validation
// 📋 Rate limiting cho login attempts
// 📋 Session security configuration
// 📋 Input validation và sanitization
// 📋 Secure password requirements
```

## Testing Strategy

### Manual Testing
- Browser testing (Chrome, Firefox, Safari)
- Mobile testing (responsive design)
- Feature testing (CRUD operations)
- Security testing (input validation)

### Authentication Testing
- **Registration flow**: Valid/invalid data testing
- **Login flow**: Credential validation testing
- **Session management**: Login/logout testing
- **Security testing**: SQL injection, XSS, CSRF testing
- **Password security**: Hash verification testing

### Code Quality
- PHP syntax checking
- Code review process
- Consistent formatting
- Documentation standards

## Environment Variables

### Database Configuration
```bash
# .env file (for production)
DB_HOST=localhost
DB_NAME=webbanhang
DB_USER=app_user
DB_PASS=secure_password

# Session configuration
SESSION_SECURE=true
SESSION_LIFETIME=3600

# Security settings
CSRF_TOKEN_NAME=webbanhang_csrf
PASSWORD_MIN_LENGTH=8
```

### Development vs Production
```php
// Development settings
define('DEBUG_MODE', true);
define('LOG_ERRORS', true);
define('DISPLAY_ERRORS', true);

// Production settings  
define('DEBUG_MODE', false);
define('LOG_ERRORS', true);
define('DISPLAY_ERRORS', false);
define('HTTPS_REQUIRED', true);
``` 
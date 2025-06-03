# Project Brief - Website Bán Hàng

## Tổng quan dự án
Dự án website bán hàng được phát triển bằng PHP thuần, sử dụng kiến trúc MVC (Model-View-Controller) đơn giản. Đây là một ứng dụng e-commerce cơ bản cho phép quản lý sản phẩm, danh mục, voucher và 
**hệ thống xác thực người dùng an toàn**.

## Mục tiêu chính
- Xây dựng website bán hàng online hoàn chỉnh
- Quản lý sản phẩm, danh mục sản phẩm
- Hệ thống voucher/mã giảm giá
- **📋 NEW: Hệ thống đăng ký, đăng nhập và quản lý người dùng**
- Giao diện thân thiện cho người dùng
- Kiến trúc code dễ bảo trì và mở rộng

## Phạm vi dự án
### Tính năng chính
1. **📋 NEW: Xác thực người dùng**
   - Đăng ký tài khoản với validation
   - Đăng nhập/đăng xuất an toàn
   - Quản lý session và bảo mật
   - Phân quyền user/admin

2. **Quản lý sản phẩm**
   - Hiển thị danh sách sản phẩm
   - Chi tiết sản phẩm
   - Thêm/sửa/xóa sản phẩm (admin only)

3. **Quản lý danh mục**
   - Phân loại sản phẩm theo danh mục
   - Quản lý cây danh mục

4. **Hệ thống voucher**
   - Tạo và quản lý mã giảm giá
   - Áp dụng voucher khi mua hàng

5. **Quản lý file**
   - Upload hình ảnh sản phẩm
   - Quản lý media files

6. **📋 NEW: Quản lý đơn hàng**
   - Liên kết đơn hàng với user account
   - Tracking lịch sử mua hàng
   - User dashboard

## Công nghệ sử dụng
- **Backend**: PHP thuần (không framework)
- **Architecture**: MVC pattern
- **Frontend**: HTML, CSS, JavaScript
- **Server**: Apache (XAMPP)
- **Database**: MySQL (với bảng `create_account_table` cho users)
- **📋 NEW: Security**: Session-based authentication, password hashing

## Cấu trúc thư mục
```
webbanhang/
├── index.php (Entry point)
├── app/
│   ├── controllers/ (Business logic)
│   │   └── AuthController.php     # NEW: Authentication logic
│   ├── models/ (Data layer)
│   │   └── UserModel.php          # NEW: User data access
│   ├── views/ (Presentation layer)
│   │   └── auth/                  # NEW: Auth views (login/register)
│   └── config/ (Configuration)
│       └── session.php            # NEW: Session configuration
├── public/
│   └── uploads/ (User uploads)
└── memory-bank/ (Documentation)
```

## Database Requirements

### Existing Tables
- **products**: Quản lý sản phẩm
- **categories**: Danh mục sản phẩm  
- **vouchers**: Mã giảm giá

### 📋 NEW: User Authentication
- **create_account_table**: ✅ Đã có - User accounts và authentication
  - User registration data
  - Hashed passwords
  - Role management (customer/admin)
  - Session tracking

## Đối tượng sử dụng
- **📋 NEW: Khách hàng đã đăng ký**: Đăng nhập để mua hàng và tracking đơn hàng
- **📋 NEW: Khách hàng mới**: Đăng ký tài khoản để sử dụng đầy đủ tính năng
- **Quản trị viên**: Đăng nhập với quyền admin để quản lý sản phẩm, danh mục, voucher và users

## Authentication Implementation Roadmap

### Phase 1: Database Analysis ✅
- [x] Confirmed bảng `create_account_table` tồn tại
- [ ] 📋 Document table structure chi tiết
- [ ] 📋 Verify database connection configuration

### Phase 2: Backend Development 📋
- [ ] Tạo `UserModel.php` - User data access layer
- [ ] Tạo `AuthController.php` - Authentication business logic  
- [ ] Implement password hashing và verification
- [ ] Session management và security

### Phase 3: Frontend Development 📋
- [ ] Tạo registration form với validation
- [ ] Tạo login form
- [ ] User dashboard và profile management
- [ ] Admin panel integration

### Phase 4: Integration & Security 📋
- [ ] Update routing system cho auth endpoints
- [ ] Protect admin routes với authentication
- [ ] Add CSRF protection
- [ ] Security testing và validation

## Tiêu chí thành công
- Website hoạt động ổn định
- Giao diện responsive và dễ sử dụng
- Code clean, có thể bảo trì và mở rộng
- Tính năng đầy đủ theo yêu cầu
- **📋 NEW: Authentication system hoạt động an toàn và user-friendly**
- **📋 NEW: User data được bảo vệ với security best practices**

## Ràng buộc kỹ thuật
- Sử dụng PHP thuần, không framework
- Tương thích với XAMPP
- Code phải tuân thủ chuẩn PSR khi có thể
- Security: Validate input, prevent SQL injection
- **📋 NEW: Password hashing với PHP `password_hash()`**
- **📋 NEW: Session-based authentication với secure configuration**
- **📋 NEW: Input validation và sanitization cho user data**

## Security Requirements
- **Password Security**: Minimum 8 characters, hashed với bcrypt
- **Session Security**: Secure session configuration, auto timeout
- **Input Validation**: Comprehensive validation cho registration/login forms
- **CSRF Protection**: Token-based protection cho sensitive actions
- **SQL Injection Prevention**: Prepared statements cho tất cả database queries
- **XSS Protection**: Output escaping trong views

## Success Metrics cho Authentication
- Users có thể đăng ký thành công với data validation
- Login/logout flow hoạt động smooth
- Session management secure và reliable
- Admin panel chỉ accessible với admin credentials
- Không có security vulnerabilities trong authentication flow 
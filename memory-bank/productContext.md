# Product Context - Website Bán Hàng

## Tại sao dự án này tồn tại?

### Vấn đề cần giải quyết
1. **Nhu cầu bán hàng online**: Nhiều doanh nghiệp nhỏ cần một website bán hàng đơn giản, dễ quản lý
2. **Chi phí thấp**: Không muốn đầu tư vào các platform phức tạp như Shopify, WooCommerce
3. **Tùy chỉnh dễ dàng**: Cần kiểm soát hoàn toàn source code để tùy chỉnh theo nhu cầu
4. **Học tập và phát triển**: Hiểu rõ cách xây dựng e-commerce từ đầu
5. **📋 NEW: User Management**: Cần hệ thống quản lý người dùng an toàn và đơn giản

### Đối tượng mục tiêu
- **Doanh nghiệp nhỏ**: Cần website bán hàng với chi phí thấp
- **Developer**: Muốn học cách xây dựng e-commerce bằng PHP thuần
- **Sinh viên**: Project học tập về web development
- **📋 NEW: End Users**: Khách hàng cần đăng ký tài khoản để mua hàng

## Cách thức hoạt động

### Luồng người dùng chính
1. **Khách hàng mới**:
   - 📋 NEW: Đăng ký tài khoản với email/password
   - 📋 NEW: Xác thực thông tin và đăng nhập
   - Truy cập trang chủ → Duyệt sản phẩm theo danh mục
   - Xem chi tiết sản phẩm → Thêm vào giỏ hàng
   - Nhập mã voucher (nếu có) → Thanh toán
   - Theo dõi đơn hàng

2. **Khách hàng đã có tài khoản**:
   - 📋 NEW: Đăng nhập với email/password
   - Duyệt sản phẩm với thông tin cá nhân hóa
   - Xem lịch sử đơn hàng
   - Quản lý thông tin tài khoản

3. **Quản trị viên**:
   - 📋 NEW: Đăng nhập với quyền admin
   - Quản lý sản phẩm: thêm/sửa/xóa
   - Quản lý danh mục sản phẩm
   - Tạo và quản lý voucher
   - 📋 NEW: Quản lý người dùng và đơn hàng
   - Xem báo cáo bán hàng

### Tính năng cốt lõi
1. **📋 NEW: User Authentication**:
   - Đăng ký tài khoản với validation
   - Đăng nhập/đăng xuất an toàn
   - Quản lý thông tin cá nhân
   - Phân quyền user/admin

2. **Catalog Management**:
   - Hiển thị sản phẩm với hình ảnh, giá, mô tả
   - Phân loại theo danh mục
   - Tìm kiếm và lọc sản phẩm

3. **Shopping Cart**:
   - Thêm/xóa sản phẩm
   - Cập nhật số lượng
   - Tính tổng tiền
   - 📋 NEW: Lưu giỏ hàng theo user account

4. **Voucher System**:
   - Mã giảm giá theo phần trăm hoặc số tiền cố định
   - Điều kiện áp dụng (giá trị đơn hàng tối thiểu)
   - Thời hạn sử dụng

5. **Order Management**:
   - Tạo đơn hàng
   - Tracking trạng thái
   - Lịch sử mua hàng
   - 📋 NEW: Liên kết đơn hàng với user account

## Trải nghiệm người dùng mong muốn

### Khách hàng
- **📋 NEW: Đăng ký dễ dàng**: Form đăng ký đơn giản với validation rõ ràng
- **📋 NEW: Đăng nhập nhanh**: Login form tiện lợi, remember session
- **Dễ dàng**: Tìm kiếm và mua sản phẩm nhanh chóng
- **Tin cậy**: Thông tin rõ ràng, thanh toán an toàn
- **Responsive**: Hoạt động tốt trên mobile và desktop
- **📋 NEW: Cá nhân hóa**: Lịch sử mua hàng, thông tin tài khoản

### Quản trị viên
- **Đơn giản**: Interface quản lý trực quan
- **Hiệu quả**: Thao tác nhanh, ít click
- **Báo cáo**: Theo dõi được doanh số, sản phẩm bán chạy
- **📋 NEW: User Management**: Quản lý users, orders, security

### Security Experience
- **📋 NEW: An toàn**: Password được hash secure
- **📋 NEW: Privacy**: Thông tin cá nhân được bảo vệ
- **📋 NEW: Session Security**: Auto logout khi inactive

## Tiêu chí thành công

### Kỹ thuật
- Website load nhanh (< 3 giây)
- Không có lỗi PHP fatal
- Tương thích cross-browser
- Code dễ đọc và maintain
- **📋 NEW: Security**: Không có lỗ hổng authentication

### Kinh doanh
- Khách hàng có thể hoàn thành mua hàng
- Admin có thể quản lý sản phẩm dễ dàng
- Hệ thống voucher hoạt động chính xác
- **📋 NEW: User Retention**: Khách hàng đăng ký và quay lại

### Người dùng
- UI/UX đơn giản, trực quan
- Responsive trên mọi thiết bị
- Feedback rõ ràng cho mọi hành động
- **📋 NEW: Trust**: Cảm thấy an toàn khi cung cấp thông tin

## Authentication User Stories

### Customer Registration
```
Là khách hàng mới
Tôi muốn đăng ký tài khoản
Để có thể mua hàng và theo dõi đơn hàng

Acceptance Criteria:
- Form đăng ký với email, password, họ tên
- Validation email format và password strength
- Kiểm tra email unique
- Tạo account thành công → redirect to login
- Error messages rõ ràng
```

### Customer Login
```
Là khách hàng đã có tài khoản
Tôi muốn đăng nhập
Để truy cập vào các tính năng cá nhân

Acceptance Criteria:
- Form login với email/password
- Verify credentials với database
- Tạo session khi login thành công
- Redirect về trang trước đó hoặc homepage
- Show error khi credentials sai
```

### Admin Access
```
Là quản trị viên
Tôi muốn đăng nhập với quyền admin
Để quản lý sản phẩm và người dùng

Acceptance Criteria:
- Login với admin credentials
- Verify admin role từ database
- Access admin dashboard
- Protect admin routes từ regular users
```

### Session Management
```
Là user đã đăng nhập
Tôi muốn session được quản lý an toàn
Để bảo vệ tài khoản của tôi

Acceptance Criteria:
- Session timeout sau khoảng thời gian inactive
- Logout button ở mọi page
- Session regeneration khi login
- Clear session data khi logout
```

## Phân biệt với competitors

### So với WooCommerce
- **Đơn giản hơn**: Không phức tạp setup
- **📋 NEW: Auth Security**: Built-in authentication, không plugin
- **Tùy chỉnh cao**: Source code hoàn toàn kiểm soát được

### So với Shopify
- **Chi phí thấp**: Không cần license, hosting đơn giản
- **📋 NEW: Data Ownership**: Database và user data hoàn toàn kiểm soát
- **Học tập**: Code rõ ràng, dễ hiểu cho mục đích giáo dục

### Unique Value Propositions
1. **📋 NEW: Simple Authentication**: PHP thuần, session-based, secure
2. **Educational**: Perfect learning project cho PHP developers
3. **Customizable**: Mọi aspect đều có thể modify
4. **Lightweight**: Minimal dependencies, fast performance
5. **📋 NEW: Security-first**: Built with modern PHP security practices

## User Journey với Authentication

### New Customer Journey
1. **Landing Page** → Browse products
2. **Want to buy** → Prompted to register/login
3. **Registration** → Fill form, validation, success message
4. **Email verification** (optional future feature)
5. **Login** → Access personalized features
6. **Shopping** → Cart, checkout với user info
7. **Order tracking** → View order history

### Returning Customer Journey
1. **Landing Page** → Login link visible
2. **Login** → Quick access với saved credentials
3. **Personalized experience** → Welcome message, order history
4. **Shopping** → Faster checkout với saved info
5. **Account management** → Update profile, view orders

### Admin Journey
1. **Admin login** → Separate admin panel
2. **Dashboard** → User management, product management
3. **User oversight** → View registered users, orders
4. **Content management** → Products, categories, vouchers
5. **Reports** → Sales analytics, user analytics 
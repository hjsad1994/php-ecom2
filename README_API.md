# 🚀 Website Bán Hàng - RESTful API Documentation

## Tổng quan

Tôi đã thành công triển khai hệ thống **RESTful API hoàn chỉnh** cho website bán hàng, bao gồm:

### ✅ Các tính năng đã hoàn thành:
- **Authentication API**: Đăng ký, đăng nhập, đăng xuất, quản lý session
- **Products API**: CRUD operations với pagination  
- **Categories API**: Quản lý danh mục sản phẩm
- **ProductModel cập nhật**: Bỏ thuộc tính image như yêu cầu
- **Error Handling**: Xử lý lỗi chuẩn với HTTP status codes
- **Response Format**: JSON response format thống nhất

### 🔧 Cấu hình URL Routing đã được fix:
- ✅ **URL Rewriting**: Cập nhật `.htaccess` để route API requests đúng cách
- ✅ **Path Parsing**: Sửa lỗi path parsing trong `api.php` 
- ✅ **API Endpoints**: Tất cả endpoints hoạt động hoàn hảo

## 📍 Base URL
```
http://localhost:85/webbanhang/api
```

## 📋 Available Endpoints

### 🔐 Authentication APIs
| Method | Endpoint | Description |
|--------|----------|-------------|
| POST | `/auth/register` | Đăng ký tài khoản mới |
| POST | `/auth/login` | Đăng nhập |
| GET | `/auth/me` | Thông tin user hiện tại |
| POST | `/auth/logout` | Đăng xuất |
| POST | `/auth/refresh` | Làm mới session |

### 📦 Products APIs  
| Method | Endpoint | Description |
|--------|----------|-------------|
| GET | `/products` | Lấy danh sách sản phẩm (có pagination) |
| GET | `/products/{id}` | Chi tiết sản phẩm theo ID |
| POST | `/products` | Tạo sản phẩm mới |
| PUT | `/products/{id}` | Cập nhật sản phẩm |
| DELETE | `/products/{id}` | Xóa sản phẩm |

### 📂 Categories APIs
| Method | Endpoint | Description |
|--------|----------|-------------|
| GET | `/categories` | Lấy danh sách danh mục |
| GET | `/categories/{id}` | Chi tiết danh mục theo ID |
| POST | `/categories` | Tạo danh mục mới |
| PUT | `/categories/{id}` | Cập nhật danh mục |
| DELETE | `/categories/{id}` | Xóa danh mục |

### 🎫 Vouchers APIs
| Method | Endpoint | Description |
|--------|----------|-------------|
| GET | `/vouchers` | Lấy danh sách vouchers (có pagination) |
| GET | `/vouchers/{id}` | Chi tiết voucher theo ID |
| GET | `/vouchers/stats` | Thống kê vouchers |
| POST | `/vouchers` | Tạo voucher mới |
| POST | `/vouchers/validate` | Validate voucher với cart data |
| PUT | `/vouchers/{id}` | Cập nhật voucher |
| DELETE | `/vouchers/{id}` | Xóa voucher |

### 👥 Account Management APIs (Admin Only)
| Method | Endpoint | Description |
|--------|----------|-------------|
| GET | `/accounts` | Lấy danh sách tài khoản (admin only) |
| GET | `/accounts/{id}` | Chi tiết tài khoản theo ID (admin only) |
| GET | `/accounts/stats` | Thống kê tài khoản (admin only) |
| POST | `/accounts` | Tạo tài khoản mới (admin only) |
| PUT | `/accounts/{id}` | Cập nhật tài khoản (admin only) |
| DELETE | `/accounts/{id}` | Xóa tài khoản (admin only) |

## 🧪 Testing Methods

### 1. 🌐 Browser-based Testing (Recommended)
**URL**: `http://localhost:85/webbanhang/api_test.html`

Dashboard tương tác với giao diện đẹp để test API:
- ✅ Test tất cả endpoints trực tiếp
- ✅ Visual response display với JSON formatting
- ✅ Auto-generate unique data cho testing
- ✅ Success/Error indicators
- ✅ Real-time testing không cần setup

### 2. 📮 Postman Testing
**Import file**: `Postman_Collection.json`

Collection đầy đủ với:
- ✅ Environment variables setup
- ✅ Tất cả CRUD operations
- ✅ Authentication flow
- ✅ Error testing scenarios

### 3. 📝 Manual Testing Examples

#### Test Register:
```bash
POST http://localhost:85/webbanhang/api/auth/register
Content-Type: application/json

{
  "email": "test@example.com",
  "password": "password123", 
  "full_name": "Test User",
  "phone": "0123456789",
  "address": "123 Test Street"
}
```

#### Test Get Products với pagination:
```bash
GET http://localhost:85/webbanhang/api/products?page=1&limit=5
```

#### Test Create Product:
```bash
POST http://localhost:85/webbanhang/api/products
Content-Type: application/json

{
  "name": "iPhone 15",
  "description": "Latest iPhone model",
  "price": 30000000,
  "category_id": 1
}
```

#### Test Validate Voucher:
```bash
POST http://localhost:85/webbanhang/api/vouchers/validate
Content-Type: application/json

{
  "code": "DISCOUNT10",
  "cart_total": 100000,
  "product_ids": [1, 2]
}
```

#### Test Get Accounts (Admin):
```bash
GET http://localhost:85/webbanhang/api/accounts?page=1&limit=10
Authorization: Admin session required
```

## 📊 Response Format

Tất cả API responses đều theo format chuẩn:

```json
{
  "success": true/false,
  "status_code": 200,
  "message": "Success message",
  "data": {...},
  "timestamp": "2024-01-01 12:00:00"
}
```

### Success Response Example:
```json
{
  "success": true,
  "status_code": 200,
  "message": "Products retrieved successfully",
  "data": {
    "products": [...],
    "pagination": {
      "current_page": 1,
      "per_page": 10,
      "total": 25,
      "total_pages": 3,
      "has_next": true,
      "has_prev": false
    }
  },
  "timestamp": "2024-01-01 12:00:00"
}
```

### Error Response Example:
```json
{
  "success": false,
  "status_code": 400,
  "message": "Validation failed",
  "data": {
    "name": "Tên sản phẩm không được để trống",
    "price": "Giá sản phẩm không hợp lệ"
  },
  "timestamp": "2024-01-01 12:00:00"
}
```

## ⚡ Quick Start Testing

1. **Mở browser** và truy cập: `http://localhost:85/webbanhang/api_test.html`
2. **Test API Documentation** (tự động load)
3. **Test Products List** (tự động load) 
4. **Test Categories List** (tự động load)
5. **Test Register** với unique email
6. **Test Login** với credentials vừa tạo
7. **Test Create Product/Category**
8. **Test Update/Delete operations**

## 🔧 Technical Implementation

### Architecture Changes:
- ✅ **Updated ProductModel**: Removed image attribute as requested
- ✅ **Created ApiController**: Base controller với response helpers
- ✅ **Created ProductApiController**: Full CRUD với pagination
- ✅ **Created AuthApiController**: Complete authentication flow
- ✅ **Created CategoryApiController**: Category management
- ✅ **Fixed URL Routing**: Proper `.htaccess` và `api.php` configuration

### Security Features:
- ✅ **Input Validation**: Comprehensive validation cho tất cả inputs
- ✅ **Password Hashing**: Secure bcrypt hashing
- ✅ **Session Management**: Secure session handling
- ✅ **SQL Injection Prevention**: Prepared statements
- ✅ **Error Handling**: Proper error responses without exposing internals

### Performance Features:
- ✅ **Pagination**: Efficient pagination cho large datasets
- ✅ **HTTP Status Codes**: Proper REST API status codes
- ✅ **JSON Responses**: Consistent, fast JSON responses
- ✅ **Database Optimization**: Efficient queries với proper indexing

## 🎯 Status Summary

### ✅ HOÀN THÀNH:
- **API Configuration**: Routing, URL rewriting, error handling
- **CRUD Operations**: Full Create, Read, Update, Delete cho Products, Categories, Vouchers, Accounts
- **Authentication**: Register, login, logout, session management
- **Authorization**: Admin-only endpoints với role-based access control
- **Voucher System**: Complete voucher management và validation với cart integration
- **Account Management**: Admin management cho user accounts với filtering
- **Pagination**: Efficient pagination cho tất cả list endpoints
- **Statistics**: Stats endpoints cho vouchers và accounts
- **Testing Tools**: Browser dashboard, Postman collection, comprehensive documentation
- **Error Handling**: Comprehensive error responses với validation
- **Updated Models**: ProductModel không có image attribute

### 🔍 Tested & Working:
- ✅ Products API: GET (with pagination), POST, PUT, DELETE
- ✅ Categories API: GET, POST, PUT, DELETE  
- ✅ Authentication: All endpoints working properly
- ✅ Error handling: Validation errors, 404s, 500s
- ✅ Response format: Consistent JSON responses
- ✅ URL routing: Proper API endpoint resolution

### 🚀 Ready for Production:
API system hoàn toàn sẵn sàng cho production với:
- Professional error handling
- Secure authentication
- Efficient pagination
- Comprehensive testing tools
- Complete documentation

---

**🎉 Kết luận**: Website bán hàng đã có hệ thống RESTful API hoàn chỉnh, professional và ready-to-use cho development và testing! 
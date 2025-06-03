<?php
class AuthHelper 
{
    /**
     * Kiểm tra user có đăng nhập không
     */
    public static function isLoggedIn() 
    {
        return SessionHelper::isLoggedIn();
    }
    
    /**
     * Kiểm tra user có quyền admin không
     */
    public static function isAdmin() 
    {
        return SessionHelper::isAdmin();
    }
    
    /**
     * Kiểm tra user có quyền user thường không
     */
    public static function isUser() 
    {
        return SessionHelper::isLoggedIn() && SessionHelper::getUserRole() === 'user';
    }
    
    /**
     * Lấy user ID từ session
     */
    public static function getUserId()
    {
        return isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;
    }
    
    /**
     * Require admin permission - redirect nếu không phải admin
     */
    public static function requireAdmin($redirectUrl = '/webbanhang/account/login') 
    {
        if (!self::isAdmin()) {
            header("Location: $redirectUrl");
            exit('Bạn không có quyền truy cập trang này.');
        }
    }
    
    /**
     * Require user login - redirect nếu chưa đăng nhập
     */
    public static function requireLogin($redirectUrl = '/webbanhang/account/login') 
    {
        if (!self::isLoggedIn()) {
            header("Location: $redirectUrl");
            exit('Vui lòng đăng nhập để tiếp tục.');
        }
    }
    
    /**
     * Require user permission (not admin) - redirect nếu không phải user
     */
    public static function requireUser($redirectUrl = '/webbanhang') 
    {
        if (!self::isUser()) {
            header("Location: $redirectUrl");
            exit('Trang này chỉ dành cho người dùng.');
        }
    }
    
    /**
     * Kiểm tra quyền CRUD cho sản phẩm (chỉ admin)
     */
    public static function canManageProducts() 
    {
        return self::isAdmin();
    }
    
    /**
     * Kiểm tra quyền CRUD cho danh mục (chỉ admin)
     */
    public static function canManageCategories() 
    {
        return self::isAdmin();
    }
    
    /**
     * Kiểm tra quyền CRUD cho voucher (chỉ admin)
     */
    public static function canManageVouchers() 
    {
        return self::isAdmin();
    }
    
    /**
     * Kiểm tra quyền xem đơn hàng (admin: tất cả, user: chỉ của mình)
     */
    public static function canViewOrder($orderId) 
    {
        if (self::isAdmin()) {
            return true; // Admin xem được tất cả đơn hàng
        }
        
        if (self::isUser()) {
            // User chỉ xem được đơn hàng của mình
            require_once 'app/config/database.php';
            require_once 'app/models/OrderModel.php';
            
            $db = (new Database())->getConnection();
            $orderModel = new OrderModel($db);
            $order = $orderModel->getById($orderId);
            
            return $order && $order['user_id'] == self::getUserId();
        }
        
        return false;
    }
    
    /**
     * Kiểm tra quyền tạo đơn hàng (chỉ user đã đăng nhập)
     */
    public static function canCreateOrder() 
    {
        return self::isLoggedIn(); // Cả admin và user đều có thể tạo đơn hàng
    }
    
    /**
     * Get user permissions as array
     */
    public static function getUserPermissions() 
    {
        $permissions = [];
        
        if (self::isAdmin()) {
            $permissions = [
                'manage_products' => true,
                'manage_categories' => true, 
                'manage_vouchers' => true,
                'view_all_orders' => true,
                'create_orders' => true,
                'admin_ui' => true
            ];
        } elseif (self::isUser()) {
            $permissions = [
                'manage_products' => false,
                'manage_categories' => false,
                'manage_vouchers' => false,
                'view_all_orders' => false,
                'view_own_orders' => true,
                'create_orders' => true,
                'admin_ui' => false
            ];
        } else {
            $permissions = [
                'manage_products' => false,
                'manage_categories' => false,
                'manage_vouchers' => false,
                'view_all_orders' => false,
                'view_own_orders' => false,
                'create_orders' => false,
                'admin_ui' => false
            ];
        }
        
        return $permissions;
    }
    
    /**
     * Block admin UI access for non-admin users
     */
    public static function blockAdminUI() 
    {
        if (!self::isAdmin()) {
            http_response_code(403);
            include 'app/views/errors/403.php';
            exit;
        }
    }
    
    /**
     * Get current user info
     */
    public static function getCurrentUser() 
    {
        if (!self::isLoggedIn()) {
            return null;
        }
        
        return [
            'id' => self::getUserId(),
            'username' => SessionHelper::getUsername(),
            'role' => SessionHelper::getUserRole(),
            'permissions' => self::getUserPermissions()
        ];
    }
}
?> 
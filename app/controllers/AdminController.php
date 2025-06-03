<?php
require_once('app/config/database.php');
require_once('app/models/ProductModel.php');
require_once('app/models/CategoryModel.php');
require_once('app/models/VoucherModel.php');
require_once('app/models/OrderModel.php');
require_once('app/helpers/AuthHelper.php');

class AdminController {
    private $db;
    private $productModel;
    private $categoryModel;
    private $voucherModel;
    private $orderModel;
    
    public function __construct() {
        // Kiểm tra quyền admin ngay khi khởi tạo
        AuthHelper::requireAdmin();
        
        $this->db = (new Database())->getConnection();
        $this->productModel = new ProductModel($this->db);
        $this->categoryModel = new CategoryModel($this->db);
        $this->voucherModel = new VoucherModel($this->db);
        $this->orderModel = new OrderModel($this->db);
    }
    
    /**
     * Dashboard admin
     */
    public function dashboard() {
        try {
            // Lấy thống kê tổng quan
            $stats = [
                'products' => $this->productModel->getProductCount(),
                'categories' => $this->categoryModel->getCategoryCount(),
                'vouchers' => $this->voucherModel->getVoucherCount(),
                'orders' => $this->orderModel->getOrderStats()
            ];
            
            // Lấy đơn hàng gần đây
            $recentOrders = $this->orderModel->getRecentOrders(5);
            
            include_once 'app/views/admin/dashboard.php';
        } catch (Exception $e) {
            // Log error và hiển thị thông báo
            error_log("Dashboard Error: " . $e->getMessage());
            
            // Fallback data để tránh white screen
            $stats = [
                'products' => 0,
                'categories' => 0,
                'vouchers' => 0,
                'orders' => [
                    'total_orders' => 0,
                    'paid_orders' => 0,
                    'unpaid_orders' => 0,
                    'pending_orders' => 0,
                    'cancelled_orders' => 0,
                    'total_revenue' => 0
                ]
            ];
            $recentOrders = [];
            $errors = ['dashboard' => 'Lỗi tải dữ liệu dashboard: ' . $e->getMessage()];
            
            include_once 'app/views/admin/dashboard.php';
        }
    }
    
    // ========== PRODUCT MANAGEMENT ==========
    
    /**
     * Danh sách sản phẩm
     */
    public function products() {
        $products = $this->productModel->getAllWithCategory();
        include_once 'app/views/admin/products/index.php';
    }
    
    /**
     * Form tạo sản phẩm mới
     */
    public function createProduct() {
        $categories = $this->categoryModel->getAll();
        $selectedCategoryId = $_GET['category_id'] ?? null; // Pre-select category if provided
        include_once 'app/views/admin/products/create.php';
    }
    
    /**
     * Lưu sản phẩm mới
     */
    public function storeProduct() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $name = $_POST['name'] ?? '';
            $description = $_POST['description'] ?? '';
            $price = $_POST['price'] ?? 0;
            $categoryId = $_POST['category_id'] ?? null;
            $image = $this->handleImageUpload('image');
            
            $errors = [];
            
            if (empty($name)) {
                $errors['name'] = "Vui lòng nhập tên sản phẩm!";
            }
            if (empty($price) || $price <= 0) {
                $errors['price'] = "Vui lòng nhập giá hợp lệ!";
            }
            
            if (count($errors) > 0) {
                $categories = $this->categoryModel->getAll();
                include_once 'app/views/admin/products/create.php';
                return;
            }
            
            try {
                $result = $this->productModel->save($name, $description, $price, $image, $categoryId);
                if ($result) {
                    header('Location: /webbanhang/admin/products?success=created');
                    exit;
                } else {
                    $errors['save'] = "Lỗi khi lưu sản phẩm!";
                    $categories = $this->categoryModel->getAll();
                    include_once 'app/views/admin/products/create.php';
                }
            } catch (Exception $e) {
                $errors['exception'] = "Lỗi hệ thống: " . $e->getMessage();
                $categories = $this->categoryModel->getAll();
                include_once 'app/views/admin/products/create.php';
            }
        }
    }
    
    /**
     * Form sửa sản phẩm
     */
    public function editProduct($productId) {
        $product = $this->productModel->getById($productId);
        if (!$product) {
            http_response_code(404);
            include 'app/views/errors/404.php';
            return;
        }
        
        $categories = $this->categoryModel->getAll();
        include_once 'app/views/admin/products/edit.php';
    }
    
    /**
     * Cập nhật sản phẩm
     */
    public function updateProduct($productId) {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $product = $this->productModel->getById($productId);
            if (!$product) {
                http_response_code(404);
                include 'app/views/errors/404.php';
                return;
            }
            
            $name = $_POST['name'] ?? '';
            $description = $_POST['description'] ?? '';
            $price = $_POST['price'] ?? 0;
            $categoryId = $_POST['category_id'] ?? null;
            
            // Fix: Handle both object and array format for product data
            $currentImage = is_array($product) ? $product['image'] : $product->image;
            $image = $currentImage; // Giữ ảnh cũ
            
            if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
                $newImage = $this->handleImageUpload('image');
                if ($newImage) {
                    $image = $newImage;
                }
            }
            
            $errors = [];
            
            if (empty($name)) {
                $errors['name'] = "Vui lòng nhập tên sản phẩm!";
            }
            if (empty($price) || $price <= 0) {
                $errors['price'] = "Vui lòng nhập giá hợp lệ!";
            }
            
            if (count($errors) > 0) {
                $categories = $this->categoryModel->getAll();
                include_once 'app/views/admin/products/edit.php';
                return;
            }
            
            try {
                $result = $this->productModel->update($productId, $name, $description, $price, $image, $categoryId);
                if ($result) {
                    header('Location: /webbanhang/admin/products?success=updated');
                    exit;
                } else {
                    $errors['update'] = "Lỗi khi cập nhật sản phẩm!";
                    $categories = $this->categoryModel->getAll();
                    include_once 'app/views/admin/products/edit.php';
                }
            } catch (Exception $e) {
                $errors['exception'] = "Lỗi hệ thống: " . $e->getMessage();
                $categories = $this->categoryModel->getAll();
                include_once 'app/views/admin/products/edit.php';
            }
        }
    }
    
    /**
     * Xóa sản phẩm
     */
    public function deleteProduct($productId) {
        try {
            $result = $this->productModel->delete($productId);
            if ($result) {
                header('Location: /webbanhang/admin/products?success=deleted');
            } else {
                header('Location: /webbanhang/admin/products?error=delete_failed');
            }
        } catch (Exception $e) {
            header('Location: /webbanhang/admin/products?error=exception');
        }
        exit;
    }
    
    // ========== CATEGORY MANAGEMENT ==========
    
    /**
     * Danh sách danh mục
     */
    public function categories() {
        $categories = $this->categoryModel->getAllWithProductCount();
        include_once 'app/views/admin/categories/index.php';
    }
    
    /**
     * Tạo danh mục mới
     */
    public function createCategory() {
        include_once 'app/views/admin/categories/create.php';
    }
    
    /**
     * Lưu danh mục mới
     */
    public function storeCategory() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $name = $_POST['name'] ?? '';
            $description = $_POST['description'] ?? '';
            
            $errors = [];
            
            if (empty($name)) {
                $errors['name'] = "Vui lòng nhập tên danh mục!";
            }
            
            if (count($errors) > 0) {
                include_once 'app/views/admin/categories/create.php';
                return;
            }
            
            try {
                $result = $this->categoryModel->save($name, $description);
                if ($result) {
                    header('Location: /webbanhang/admin/categories?success=created');
                    exit;
                } else {
                    $errors['save'] = "Lỗi khi lưu danh mục!";
                    include_once 'app/views/admin/categories/create.php';
                }
            } catch (Exception $e) {
                $errors['exception'] = "Lỗi hệ thống: " . $e->getMessage();
                include_once 'app/views/admin/categories/create.php';
            }
        }
    }
    
    /**
     * Form sửa danh mục
     */
    public function editCategory($categoryId) {
        $category = $this->categoryModel->getById($categoryId);
        if (!$category) {
            http_response_code(404);
            include 'app/views/errors/404.php';
            return;
        }
        
        include_once 'app/views/admin/categories/edit.php';
    }
    
    /**
     * Cập nhật danh mục
     */
    public function updateCategory($categoryId) {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $category = $this->categoryModel->getById($categoryId);
            if (!$category) {
                http_response_code(404);
                include 'app/views/errors/404.php';
                return;
            }
            
            $name = $_POST['name'] ?? '';
            $description = $_POST['description'] ?? '';
            
            $errors = [];
            
            if (empty($name)) {
                $errors['name'] = "Vui lòng nhập tên danh mục!";
            }
            
            if (count($errors) > 0) {
                include_once 'app/views/admin/categories/edit.php';
                return;
            }
            
            try {
                $result = $this->categoryModel->update($categoryId, $name, $description);
                if ($result) {
                    header('Location: /webbanhang/admin/categories?success=updated');
                    exit;
                } else {
                    $errors['update'] = "Lỗi khi cập nhật danh mục!";
                    include_once 'app/views/admin/categories/edit.php';
                }
            } catch (Exception $e) {
                $errors['exception'] = "Lỗi hệ thống: " . $e->getMessage();
                include_once 'app/views/admin/categories/edit.php';
            }
        }
    }
    
    /**
     * Xem chi tiết danh mục
     */
    public function showCategory($categoryId) {
        // Lấy thông tin danh mục
        $category = $this->categoryModel->getCategoryById($categoryId);
        if (!$category) {
            http_response_code(404);
            include 'app/views/errors/404.php';
            return;
        }
        
        // Lấy tất cả sản phẩm thuộc danh mục này
        $products = $this->productModel->getProductsByCategory($categoryId);
        
        include_once 'app/views/admin/categories/show.php';
    }
    
    /**
     * Xóa danh mục
     */
    public function deleteCategory($categoryId) {
        try {
            $result = $this->categoryModel->deleteCategory($categoryId);
            if ($result) {
                header('Location: /webbanhang/admin/categories?success=deleted');
            } else {
                header('Location: /webbanhang/admin/categories?error=delete_failed');
            }
        } catch (Exception $e) {
            header('Location: /webbanhang/admin/categories?error=exception');
        }
        exit;
    }
    
    // ========== VOUCHER MANAGEMENT ==========
    
    /**
     * Danh sách voucher
     */
    public function vouchers() {
        $vouchers = $this->voucherModel->getAll();
        include_once 'app/views/admin/vouchers/index.php';
    }
    
    /**
     * Tạo voucher mới
     */
    public function createVoucher() {
        include_once 'app/views/admin/vouchers/create.php';
    }
    
    /**
     * Lưu voucher mới
     */
    public function storeVoucher() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $voucherData = [
                'code' => $_POST['code'] ?? '',
                'name' => $_POST['name'] ?? '',
                'description' => $_POST['description'] ?? '',
                'discount_type' => $_POST['discount_type'] ?? 'percentage',
                'discount_value' => $_POST['discount_value'] ?? 0,
                'min_order_amount' => $_POST['min_order_amount'] ?? 0,
                'max_discount_amount' => $_POST['max_discount_amount'] ?? null,
                'usage_limit' => $_POST['usage_limit'] ?? null,
                'start_date' => $_POST['start_date'] ?? '',
                'end_date' => $_POST['end_date'] ?? '',
                'is_active' => isset($_POST['is_active']) ? 1 : 0
            ];
            
            $errors = [];
            
            if (empty($voucherData['code'])) {
                $errors['code'] = "Vui lòng nhập mã voucher!";
            }
            if (empty($voucherData['name'])) {
                $errors['name'] = "Vui lòng nhập tên voucher!";
            }
            if (empty($voucherData['discount_value']) || $voucherData['discount_value'] <= 0) {
                $errors['discount_value'] = "Vui lòng nhập giá trị giảm giá hợp lệ!";
            }
            
            if (count($errors) > 0) {
                include_once 'app/views/admin/vouchers/create.php';
                return;
            }
            
            try {
                $result = $this->voucherModel->save($voucherData);
                if ($result) {
                    header('Location: /webbanhang/admin/vouchers?success=created');
                    exit;
                } else {
                    $errors['save'] = "Lỗi khi lưu voucher!";
                    include_once 'app/views/admin/vouchers/create.php';
                }
            } catch (Exception $e) {
                $errors['exception'] = "Lỗi hệ thống: " . $e->getMessage();
                include_once 'app/views/admin/vouchers/create.php';
            }
        }
    }
    
    /**
     * Form sửa voucher
     */
    public function editVoucher($voucherId) {
        $voucher = $this->voucherModel->getById($voucherId);
        if (!$voucher) {
            http_response_code(404);
            include 'app/views/errors/404.php';
            return;
        }
        
        include_once 'app/views/admin/vouchers/edit.php';
    }
    
    /**
     * Cập nhật voucher
     */
    public function updateVoucher($voucherId) {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $voucher = $this->voucherModel->getById($voucherId);
            if (!$voucher) {
                http_response_code(404);
                include 'app/views/errors/404.php';
                return;
            }
            
            $voucherData = [
                'code' => $_POST['code'] ?? '',
                'name' => $_POST['name'] ?? '',
                'description' => $_POST['description'] ?? '',
                'discount_type' => $_POST['discount_type'] ?? 'percentage',
                'discount_value' => $_POST['discount_value'] ?? 0,
                'min_order_amount' => $_POST['min_order_amount'] ?? 0,
                'max_discount_amount' => $_POST['max_discount_amount'] ?? null,
                'usage_limit' => $_POST['usage_limit'] ?? null,
                'start_date' => $_POST['start_date'] ?? '',
                'end_date' => $_POST['end_date'] ?? '',
                'is_active' => isset($_POST['is_active']) ? 1 : 0
            ];
            
            $errors = [];
            
            if (empty($voucherData['code'])) {
                $errors['code'] = "Vui lòng nhập mã voucher!";
            }
            if (empty($voucherData['name'])) {
                $errors['name'] = "Vui lòng nhập tên voucher!";
            }
            if (empty($voucherData['discount_value']) || $voucherData['discount_value'] <= 0) {
                $errors['discount_value'] = "Vui lòng nhập giá trị giảm giá hợp lệ!";
            }
            
            if (count($errors) > 0) {
                include_once 'app/views/admin/vouchers/edit.php';
                return;
            }
            
            try {
                $result = $this->voucherModel->update($voucherId, $voucherData);
                if ($result) {
                    header('Location: /webbanhang/admin/vouchers?success=updated');
                    exit;
                } else {
                    $errors['update'] = "Lỗi khi cập nhật voucher!";
                    include_once 'app/views/admin/vouchers/edit.php';
                }
            } catch (Exception $e) {
                $errors['exception'] = "Lỗi hệ thống: " . $e->getMessage();
                include_once 'app/views/admin/vouchers/edit.php';
            }
        }
    }
    
    // ========== ORDER MANAGEMENT ==========
    
    /**
     * Danh sách đơn hàng (admin xem tất cả)
     */
    public function orders() {
        $orders = $this->orderModel->getAllOrders();
        include_once 'app/views/admin/orders/index.php';
    }
    
    /**
     * Chi tiết đơn hàng
     */
    public function viewOrder($orderId) {
        $order = $this->orderModel->getOrderWithDetails($orderId);
        if (!$order) {
            http_response_code(404);
            include 'app/views/errors/404.php';
            return;
        }
        
        include_once 'app/views/admin/orders/show.php';
    }
    
    // ========== UTILITIES ==========
    
    /**
     * Xử lý upload ảnh
     */
    private function handleImageUpload($fieldName) {
        if (!isset($_FILES[$fieldName]) || $_FILES[$fieldName]['error'] !== UPLOAD_ERR_OK) {
            return null;
        }
        
        $uploadDir = 'public/uploads/products/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }
        
        $fileName = time() . '_' . basename($_FILES[$fieldName]['name']);
        $targetPath = $uploadDir . $fileName;
        
        if (move_uploaded_file($_FILES[$fieldName]['tmp_name'], $targetPath)) {
            return $fileName;
        }
        
        return null;
    }
}
?> 
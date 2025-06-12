<?php
require_once('app/config/database.php');
require_once('app/models/ProductModel.php');
require_once('app/models/CategoryModel.php');
require_once('app/models/VoucherModel.php');
require_once('app/models/OrderModel.php');
require_once('app/models/AccountModel.php');
require_once('app/helpers/AuthHelper.php');

class AdminController {
    private $db;
    private $productModel;
    private $categoryModel;
    private $voucherModel;
    private $orderModel;
    private $accountModel;
    
    public function __construct() {
        // Kiểm tra quyền admin ngay khi khởi tạo - hiển thị 403 thay vì redirect
        AuthHelper::blockAdminUI();
        
        $this->db = (new Database())->getConnection();
        $this->productModel = new ProductModel($this->db);
        $this->categoryModel = new CategoryModel($this->db);
        $this->voucherModel = new VoucherModel($this->db);
        $this->orderModel = new OrderModel($this->db);
        $this->accountModel = new AccountModel($this->db);
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
     * Danh sách sản phẩm với thống kê chi tiết
     */
    public function products() {
        // Lấy danh sách sản phẩm
        $products = $this->productModel->getAllWithCategory();
        
        // Lấy thống kê tổng quan
        $stats = [
            'total_products' => $this->productModel->getProductCount(),
            'total_categories' => $this->categoryModel->getCategoryCount(),
            'price_range' => $this->productModel->getPriceRange(),
            'category_stats' => $this->productModel->getProductStatsByCategory(),
            'latest_products' => $this->productModel->getLatestProducts(3)
        ];
        
        // Lấy tất cả danh mục cho filter
        $categories = $this->categoryModel->getAll();
        
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
            
            // Debug log before image upload
            error_log("=== AdminController.storeProduct DEBUG ===");
            error_log("Name: " . $name);
            error_log("Price: " . $price);
            error_log("CategoryId: " . ($categoryId ?? 'NULL'));
            error_log("FILES array: " . print_r($_FILES, true));
            
            $image = $this->handleImageUpload('image');
            
            // Debug log
            error_log("AdminController.storeProduct - Image upload result: " . ($image ? $image : 'NULL'));
            
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
                // Fix parameter order: ($name, $description, $price, $image, $categoryId)
                error_log("Calling productModel->save with image: " . ($image ?? 'NULL'));
                $result = $this->productModel->save($name, $description, $price, $image, $categoryId);
                if ($result) {
                    error_log("Product creation successful");
                    header('Location: /webbanhang/admin/products?success=created');
                    exit;
                } else {
                    error_log("Product creation failed");
                    $errors['save'] = "Lỗi khi lưu sản phẩm!";
                    $categories = $this->categoryModel->getAll();
                    include_once 'app/views/admin/products/create.php';
                }
            } catch (Exception $e) {
                error_log("Product creation exception: " . $e->getMessage());
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
            
            // Debug log before image upload
            error_log("=== AdminController.updateProduct DEBUG ===");
            error_log("Product ID: " . $productId);
            error_log("Current image: " . ($currentImage ?? 'NULL'));
            error_log("FILES array: " . print_r($_FILES, true));
            
            if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
                error_log("Image upload detected, calling handleImageUpload...");
                $newImage = $this->handleImageUpload('image');
                error_log("Upload result: " . ($newImage ?? 'NULL'));
                if ($newImage) {
                    $image = $newImage;
                    error_log("Image updated to: " . $image);
                }
            } else {
                error_log("No image upload or upload error");
                if (isset($_FILES['image'])) {
                    error_log("Upload error code: " . $_FILES['image']['error']);
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
                // Fix parameter order: ($id, $name, $description, $price, $image, $categoryId)
                error_log("Calling productModel->update with image: " . ($image ?? 'NULL'));
                $result = $this->productModel->update($productId, $name, $description, $price, $image, $categoryId);
                if ($result) {
                    error_log("Product update successful");
                    header('Location: /webbanhang/admin/products?success=updated');
                    exit;
                } else {
                    error_log("Product update failed");
                    $errors['update'] = "Lỗi khi cập nhật sản phẩm!";
                    $categories = $this->categoryModel->getAll();
                    include_once 'app/views/admin/products/edit.php';
                }
            } catch (Exception $e) {
                error_log("Product update exception: " . $e->getMessage());
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
     * Danh sách voucher với thống kê chi tiết
     */
    public function vouchers() {
        // Lấy danh sách vouchers
        $vouchers = $this->voucherModel->getAll();
        
        // Lấy thống kê tổng quan
        $stats = [
            'voucher_stats' => $this->voucherModel->getVoucherStats(),
            'discount_types' => $this->voucherModel->getDiscountTypeStats(),
            'top_used' => $this->voucherModel->getTopUsedVouchers(3),
            'expiring_soon' => $this->voucherModel->getExpiringSoonVouchers(7)
        ];
        
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
                'code' => strtoupper(trim($_POST['code'] ?? '')),
                'name' => trim($_POST['name'] ?? ''),
                'description' => trim($_POST['description'] ?? ''),
                'discount_type' => $_POST['discount_type'] ?? 'percentage',
                'discount_value' => floatval($_POST['discount_value'] ?? 0),
                'min_order_amount' => floatval($_POST['min_order_amount'] ?? 0),
                'max_discount_amount' => !empty($_POST['max_discount_amount']) ? floatval($_POST['max_discount_amount']) : null,
                'applies_to' => $_POST['applies_to'] ?? 'all_products',
                'product_ids' => null,
                'category_ids' => null,
                'usage_limit' => !empty($_POST['usage_limit']) ? intval($_POST['usage_limit']) : null,
                'start_date' => $_POST['start_date'] ?? '',
                'end_date' => $_POST['end_date'] ?? '',
                'is_active' => isset($_POST['is_active']) ? 1 : 0
            ];
            
            // Handle product selection for specific products
            if ($voucherData['applies_to'] == 'specific_products' && !empty($_POST['product_ids'])) {
                $voucherData['product_ids'] = json_encode($_POST['product_ids']);
            }
            
            // Handle category selection for specific categories
            if ($voucherData['applies_to'] == 'specific_categories' && !empty($_POST['category_ids'])) {
                $voucherData['category_ids'] = json_encode($_POST['category_ids']);
            }
            
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
                if ($result && !is_array($result)) {
                    header('Location: /webbanhang/admin/vouchers?success=created');
                    exit;
                } else {
                    $errors['save'] = is_array($result) ? implode(', ', $result) : "Lỗi khi lưu voucher!";
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
                'code' => strtoupper(trim($_POST['code'] ?? '')),
                'name' => trim($_POST['name'] ?? ''),
                'description' => trim($_POST['description'] ?? ''),
                'discount_type' => $_POST['discount_type'] ?? 'percentage',
                'discount_value' => floatval($_POST['discount_value'] ?? 0),
                'min_order_amount' => floatval($_POST['min_order_amount'] ?? 0),
                'max_discount_amount' => !empty($_POST['max_discount_amount']) ? floatval($_POST['max_discount_amount']) : null,
                'applies_to' => $_POST['applies_to'] ?? 'all_products',
                'product_ids' => null,
                'category_ids' => null,
                'usage_limit' => !empty($_POST['usage_limit']) ? intval($_POST['usage_limit']) : null,
                'start_date' => $_POST['start_date'] ?? '',
                'end_date' => $_POST['end_date'] ?? '',
                'is_active' => isset($_POST['is_active']) ? 1 : 0
            ];
            
            // Handle product selection for specific products
            if ($voucherData['applies_to'] == 'specific_products' && !empty($_POST['product_ids'])) {
                $voucherData['product_ids'] = json_encode($_POST['product_ids']);
            }
            
            // Handle category selection for specific categories
            if ($voucherData['applies_to'] == 'specific_categories' && !empty($_POST['category_ids'])) {
                $voucherData['category_ids'] = json_encode($_POST['category_ids']);
            }
            
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
    
    /**
     * Xóa voucher
     */
    public function deleteVoucher($voucherId) {
        try {
            $result = $this->voucherModel->deleteVoucher($voucherId);
            if ($result) {
                header('Location: /webbanhang/admin/vouchers?success=deleted');
            } else {
                header('Location: /webbanhang/admin/vouchers?error=delete_failed');
            }
        } catch (Exception $e) {
            header('Location: /webbanhang/admin/vouchers?error=exception');
        }
        exit;
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
        // Debug $_FILES
        error_log("=== DEBUG UPLOAD START ===");
        error_log("Field name: " . $fieldName);
        error_log("FILES array: " . print_r($_FILES, true));
        error_log("Current working directory: " . getcwd());
        error_log("Document root: " . $_SERVER['DOCUMENT_ROOT']);
        
        if (!isset($_FILES[$fieldName])) {
            error_log("ERROR: No file field found: " . $fieldName);
            return null;
        }
        
        $file = $_FILES[$fieldName];
        error_log("File info: " . print_r($file, true));
        
        if ($file['error'] !== UPLOAD_ERR_OK) {
            error_log("ERROR: Upload error code: " . $file['error']);
            $uploadErrors = [
                UPLOAD_ERR_INI_SIZE => 'File too large (exceeds upload_max_filesize)',
                UPLOAD_ERR_FORM_SIZE => 'File too large (exceeds MAX_FILE_SIZE)',
                UPLOAD_ERR_PARTIAL => 'File was only partially uploaded',
                UPLOAD_ERR_NO_FILE => 'No file was uploaded',
                UPLOAD_ERR_NO_TMP_DIR => 'Missing temporary folder',
                UPLOAD_ERR_CANT_WRITE => 'Failed to write file to disk',
                UPLOAD_ERR_EXTENSION => 'File upload stopped by extension'
            ];
            error_log("Upload error meaning: " . ($uploadErrors[$file['error']] ?? 'Unknown error'));
            return null;
        }
        
        // Check file type
        $allowedTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/jpg'];
        if (!in_array($file['type'], $allowedTypes)) {
            error_log("ERROR: Invalid file type: " . $file['type']);
            return null;
        }
        
        // Use absolute path for macOS XAMPP
        $uploadDir = $_SERVER['DOCUMENT_ROOT'] . '/webbanhang/public/uploads/products/';
        error_log("Upload directory path: " . $uploadDir);
        
        // Create directory if not exists
        if (!is_dir($uploadDir)) {
            $created = mkdir($uploadDir, 0755, true);
            error_log("Created directory: " . ($created ? 'YES' : 'NO'));
            if (!$created) {
                error_log("ERROR: Could not create directory: " . $uploadDir);
                return null;
            }
        }
        
        // Check directory permissions
        error_log("Directory exists: " . (is_dir($uploadDir) ? 'YES' : 'NO'));
        error_log("Directory readable: " . (is_readable($uploadDir) ? 'YES' : 'NO'));
        error_log("Directory writable: " . (is_writable($uploadDir) ? 'YES' : 'NO'));
        error_log("Directory perms: " . substr(sprintf('%o', fileperms($uploadDir)), -4));
        
        if (!is_writable($uploadDir)) {
            error_log("ERROR: Directory not writable: " . $uploadDir);
            // Try to fix permissions for macOS
            chmod($uploadDir, 0755);
            error_log("After chmod 755 - writable: " . (is_writable($uploadDir) ? 'YES' : 'NO'));
            
            if (!is_writable($uploadDir)) {
                return null;
            }
        }
        
        // Generate unique filename
        $fileName = time() . '_' . uniqid() . '_' . basename($file['name']);
        $targetPath = $uploadDir . $fileName;
        
        error_log("Target path: " . $targetPath);
        error_log("Source temp file: " . $file['tmp_name']);
        error_log("Source file exists: " . (file_exists($file['tmp_name']) ? 'YES' : 'NO'));
        error_log("Source file size: " . (file_exists($file['tmp_name']) ? filesize($file['tmp_name']) : 'N/A'));
        
        // Attempt upload
        if (move_uploaded_file($file['tmp_name'], $targetPath)) {
            error_log("SUCCESS: Upload completed");
            error_log("Final file exists: " . (file_exists($targetPath) ? 'YES' : 'NO'));
            error_log("Final file size: " . (file_exists($targetPath) ? filesize($targetPath) : 'N/A'));
            error_log("Returned filename: " . $fileName);
            error_log("=== DEBUG UPLOAD END ===");
            return $fileName;
        } else {
            error_log("ERROR: move_uploaded_file failed");
            $lastError = error_get_last();
            error_log("Last PHP error: " . ($lastError['message'] ?? 'No error'));
            error_log("=== DEBUG UPLOAD END ===");
            return null;
        }
    }
    
    // ========== ACCOUNT MANAGEMENT ==========
    
    /**
     * Danh sách tài khoản
     */
    public function accounts() {
        try {
            // Lấy tất cả tài khoản
            $accounts = $this->accountModel->getAllAccounts();
            
            // Thống kê tài khoản
            $accountStats = $this->accountModel->getAccountStats();
            
            include_once 'app/views/admin/accounts/index.php';
        } catch (Exception $e) {
            error_log("Accounts Error: " . $e->getMessage());
            $accounts = [];
            $accountStats = [
                'total_accounts' => 0,
                'admin_accounts' => 0,
                'user_accounts' => 0,
                'recent_registrations' => 0
            ];
            $errors = ['accounts' => 'Lỗi tải dữ liệu tài khoản: ' . $e->getMessage()];
            include_once 'app/views/admin/accounts/index.php';
        }
    }
    
    /**
     * Form tạo tài khoản mới
     */
    public function createAccount() {
        include_once 'app/views/admin/accounts/create.php';
    }
    
    /**
     * Lưu tài khoản mới
     */
    public function storeAccount() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $username = trim($_POST['username'] ?? '');
            $password = $_POST['password'] ?? '';
            $confirmPassword = $_POST['confirm_password'] ?? '';
            $fullname = trim($_POST['fullname'] ?? '');
            $email = trim($_POST['email'] ?? '');
            $phone = trim($_POST['phone'] ?? '');
            $address = trim($_POST['address'] ?? '');
            $role = $_POST['role'] ?? 'user';
            
            $errors = [];
            
            // Validation
            if (empty($username)) {
                $errors['username'] = "Vui lòng nhập tên đăng nhập!";
            } elseif ($this->accountModel->checkUsernameExists($username)) {
                $errors['username'] = "Tên đăng nhập đã tồn tại!";
            }
            
            if (empty($password)) {
                $errors['password'] = "Vui lòng nhập mật khẩu!";
            } elseif (strlen($password) < 6) {
                $errors['password'] = "Mật khẩu phải có ít nhất 6 ký tự!";
            }
            
            if ($password !== $confirmPassword) {
                $errors['confirm_password'] = "Mật khẩu xác nhận không khớp!";
            }
            
            if (empty($fullname)) {
                $errors['fullname'] = "Vui lòng nhập họ tên!";
            }
            
            if (!empty($email) && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $errors['email'] = "Email không hợp lệ!";
            } elseif (!empty($email) && $this->accountModel->checkEmailExists($email)) {
                $errors['email'] = "Email đã được sử dụng!";
            }
            
            if (!in_array($role, ['user', 'admin'])) {
                $errors['role'] = "Vai trò không hợp lệ!";
            }
            
            if (count($errors) > 0) {
                include_once 'app/views/admin/accounts/create.php';
                return;
            }
            
            try {
                $result = $this->accountModel->save($username, $password, $fullname, $email, $phone, $address, $role);
                if ($result) {
                    header('Location: /webbanhang/admin/accounts?success=created');
                    exit;
                } else {
                    $errors['save'] = "Lỗi khi tạo tài khoản!";
                    include_once 'app/views/admin/accounts/create.php';
                }
            } catch (Exception $e) {
                $errors['exception'] = "Lỗi hệ thống: " . $e->getMessage();
                include_once 'app/views/admin/accounts/create.php';
            }
        }
    }
    
    /**
     * Form chỉnh sửa tài khoản
     */
    public function editAccount($accountId) {
        $account = $this->accountModel->getById($accountId);
        if (!$account) {
            http_response_code(404);
            include 'app/views/errors/404.php';
            return;
        }
        
        include_once 'app/views/admin/accounts/edit.php';
    }
    
    /**
     * Cập nhật tài khoản
     */
    public function updateAccount($accountId) {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $account = $this->accountModel->getById($accountId);
            if (!$account) {
                http_response_code(404);
                include 'app/views/errors/404.php';
                return;
            }
            
            $username = trim($_POST['username'] ?? '');
            $password = $_POST['password'] ?? '';
            $confirmPassword = $_POST['confirm_password'] ?? '';
            $fullname = trim($_POST['fullname'] ?? '');
            $email = trim($_POST['email'] ?? '');
            $phone = trim($_POST['phone'] ?? '');
            $address = trim($_POST['address'] ?? '');
            $role = $_POST['role'] ?? 'user';
            
            $errors = [];
            
            // Validation
            if (empty($username)) {
                $errors['username'] = "Vui lòng nhập tên đăng nhập!";
            } elseif ($this->accountModel->checkUsernameExists($username, $accountId)) {
                $errors['username'] = "Tên đăng nhập đã tồn tại!";
            }
            
            if (!empty($password) && strlen($password) < 6) {
                $errors['password'] = "Mật khẩu phải có ít nhất 6 ký tự!";
            }
            
            if (!empty($password) && $password !== $confirmPassword) {
                $errors['confirm_password'] = "Mật khẩu xác nhận không khớp!";
            }
            
            if (empty($fullname)) {
                $errors['fullname'] = "Vui lòng nhập họ tên!";
            }
            
            if (!empty($email) && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $errors['email'] = "Email không hợp lệ!";
            } elseif (!empty($email) && $this->accountModel->checkEmailExists($email, $accountId)) {
                $errors['email'] = "Email đã được sử dụng!";
            }
            
            if (!in_array($role, ['user', 'admin'])) {
                $errors['role'] = "Vai trò không hợp lệ!";
            }
            
            if (count($errors) > 0) {
                include_once 'app/views/admin/accounts/edit.php';
                return;
            }
            
            try {
                $result = $this->accountModel->updateAccount($accountId, [
                    'username' => $username,
                    'password' => !empty($password) ? password_hash($password, PASSWORD_BCRYPT, ['cost' => 12]) : null,
                    'fullname' => $fullname,
                    'email' => !empty($email) ? $email : null,
                    'phone' => !empty($phone) ? $phone : null,
                    'address' => !empty($address) ? $address : null,
                    'role' => $role
                ]);
                
                if ($result) {
                    header('Location: /webbanhang/admin/accounts?success=updated');
                    exit;
                } else {
                    $errors['update'] = "Lỗi khi cập nhật tài khoản!";
                    include_once 'app/views/admin/accounts/edit.php';
                }
            } catch (Exception $e) {
                $errors['exception'] = "Lỗi hệ thống: " . $e->getMessage();
                include_once 'app/views/admin/accounts/edit.php';
            }
        }
    }
    
    /**
     * Toggle trạng thái tài khoản (active/disabled)
     */
    public function toggleAccountStatus($accountId) {
        try {
            error_log("=== Toggle Account Status DEBUG ===");
            error_log("Account ID: " . $accountId);
            
            $account = $this->accountModel->getById($accountId);
            if (!$account) {
                error_log("Account not found: " . $accountId);
                http_response_code(404);
                include 'app/views/errors/404.php';
                return;
            }
            
            error_log("Account found: " . print_r($account, true));
            
            // Không cho phép disable tài khoản admin
            if ($account['role'] === 'admin') {
                error_log("Cannot disable admin account");
                header('Location: /webbanhang/admin/accounts?error=cannot_disable_admin');
                exit;
            }
            
            // Lấy trạng thái hiện tại và đảo ngược
            $currentStatus = $account['status'] ?? 'active';
            $newStatus = ($currentStatus === 'active') ? 'disabled' : 'active';
            
            error_log("Current status: " . $currentStatus);
            error_log("New status: " . $newStatus);
            
            // Cập nhật trạng thái
            $result = $this->accountModel->updateAccount($accountId, [
                'status' => $newStatus
            ]);
            
            error_log("Update result: " . ($result ? 'true' : 'false'));
            
            if ($result) {
                $statusText = ($newStatus === 'active') ? 'kích hoạt' : 'vô hiệu hóa';
                error_log("Success - redirecting with status: " . $statusText);
                header("Location: /webbanhang/admin/accounts?success=status_updated&action={$statusText}");
                exit;
            } else {
                error_log("Update failed");
                header('Location: /webbanhang/admin/accounts?error=update_failed');
                exit;
            }
        } catch (Exception $e) {
            error_log("Toggle account status error: " . $e->getMessage());
            header('Location: /webbanhang/admin/accounts?error=system_error');
            exit;
        }
    }

    /**
     * Xóa tài khoản
     */
    public function deleteAccount($accountId) {
        try {
            // Không cho phép xóa tài khoản admin hiện tại
            $currentUser = SessionHelper::getUser();
            if ($currentUser['id'] == $accountId) {
                header('Location: /webbanhang/admin/accounts?error=cannot_delete_self');
                exit;
            }
            
            $result = $this->accountModel->deleteAccount($accountId);
            if ($result) {
                header('Location: /webbanhang/admin/accounts?success=deleted');
            } else {
                header('Location: /webbanhang/admin/accounts?error=delete_failed');
            }
        } catch (Exception $e) {
            header('Location: /webbanhang/admin/accounts?error=exception');
        }
        exit;
    }
}
?> 
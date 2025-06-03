<?php
require_once('app/config/database.php');
require_once('app/models/OrderModel.php');
require_once('app/helpers/AuthHelper.php');
require_once('app/helpers/SessionHelper.php');

class OrderController {
    private $orderModel;
    private $db;
    
    public function __construct() {
        $this->db = (new Database())->getConnection();
        $this->orderModel = new OrderModel($this->db);
    }
    
    /**
     * Hiển thị form đặt hàng (chỉ cho user đã đăng nhập)
     */
    public function create() {
        AuthHelper::requireLogin();
        
        include_once 'app/views/user/orders/create.php';
    }
    
    /**
     * Xử lý đặt hàng (chỉ cho user đã đăng nhập)
     */
    public function store() {
        AuthHelper::requireLogin();
        
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $userId = AuthHelper::getUserId();
            $name = $_POST['name'] ?? '';
            $phone = $_POST['phone'] ?? '';
            $address = $_POST['address'] ?? '';
            $products = $_POST['products'] ?? [];
            $voucherCode = $_POST['voucher_code'] ?? '';
            
            $errors = [];
            
            // Validation
            if (empty($name)) {
                $errors['name'] = "Vui lòng nhập họ tên!";
            }
            if (empty($phone)) {
                $errors['phone'] = "Vui lòng nhập số điện thoại!";
            }
            if (empty($address)) {
                $errors['address'] = "Vui lòng nhập địa chỉ!";
            }
            if (empty($products)) {
                $errors['products'] = "Vui lòng chọn sản phẩm!";
            }
            
            if (count($errors) > 0) {
                include_once 'app/views/user/orders/create.php';
                return;
            }
            
            try {
                // Tính tổng tiền đơn hàng
                $totalAmount = $this->calculateOrderTotal($products);
                
                // Xử lý voucher nếu có
                $voucherDiscount = 0;
                $voucherId = null;
                if (!empty($voucherCode)) {
                    $voucher = $this->orderModel->getValidVoucher($voucherCode, $totalAmount);
                    if ($voucher) {
                        $voucherId = $voucher['id'];
                        $voucherDiscount = $this->calculateVoucherDiscount($voucher, $totalAmount);
                    }
                }
                
                $finalAmount = $totalAmount - $voucherDiscount;
                
                // Tạo đơn hàng
                $orderId = $this->orderModel->createOrder([
                    'user_id' => $userId,
                    'name' => $name,
                    'phone' => $phone,
                    'address' => $address,
                    'total_amount' => $finalAmount,
                    'voucher_id' => $voucherId,
                    'voucher_code' => $voucherCode,
                    'voucher_discount' => $voucherDiscount,
                    'order_status' => 'unpaid'
                ]);
                
                // Thêm chi tiết đơn hàng
                foreach ($products as $product) {
                    $this->orderModel->addOrderDetail($orderId, [
                        'product_id' => $product['id'],
                        'quantity' => $product['quantity'],
                        'price' => $product['price']
                    ]);
                }
                
                // Redirect đến trang xác nhận
                header("Location: /webbanhang/order/confirm/$orderId");
                exit;
                
            } catch (Exception $e) {
                $errors['order'] = "Lỗi khi tạo đơn hàng: " . $e->getMessage();
                include_once 'app/views/user/orders/create.php';
            }
        }
    }
    
    /**
     * Xem đơn hàng (user chỉ xem được của mình, admin xem tất cả)
     */
    public function show($orderId) {
        AuthHelper::requireLogin();
        
        if (!AuthHelper::canViewOrder($orderId)) {
            http_response_code(403);
            include 'app/views/errors/403.php';
            exit;
        }
        
        $order = $this->orderModel->getOrderWithDetails($orderId);
        if (!$order) {
            http_response_code(404);
            include 'app/views/errors/404.php';
            exit;
        }
        
        include_once 'app/views/orders/show.php';
    }
    
    /**
     * Danh sách đơn hàng (user: của mình, admin: tất cả)
     */
    public function index() {
        AuthHelper::requireLogin();
        
        if (AuthHelper::isAdmin()) {
            // Admin xem tất cả đơn hàng
            $orders = $this->orderModel->getAllOrders();
            include_once 'app/views/admin/orders/index.php';
        } else {
            // User chỉ xem đơn hàng của mình
            $userId = AuthHelper::getUserId();
            $orders = $this->orderModel->getOrdersByUserId($userId);
            
            // Convert to objects for view compatibility và add item details
            $orderObjects = [];
            foreach ($orders as $order) {
                $orderDetails = $this->orderModel->getOrderWithDetails($order['id']);
                
                $orderObj = (object)[
                    'id' => $order['id'],
                    'order_status' => $order['order_status'],
                    'created_at' => $order['created_at'],
                    'name' => $order['name'],
                    'phone' => $order['phone'],
                    'address' => $order['address'],
                    'total_amount' => $order['total_amount'],
                    'item_count' => count($orderDetails['items'] ?? []),
                    'items' => []
                ];
                
                // Add product details for display
                if (isset($orderDetails['items'])) {
                    foreach ($orderDetails['items'] as $item) {
                        $orderObj->items[] = (object)[
                            'name' => $item['product_name'],
                            'quantity' => $item['quantity'],
                            'image' => $item['image'] ?? 'no-image.jpg'
                        ];
                    }
                }
                
                $orderObjects[] = $orderObj;
            }
            
            $orders = $orderObjects;
            include_once 'app/views/user/orders/index.php';
        }
    }
    
    /**
     * User orders page - specifically for /user/orders route
     */
    public function userIndex() {
        AuthHelper::requireLogin();
        
        $userId = AuthHelper::getUserId();
        $orders = $this->orderModel->getOrdersByUserId($userId);
        
        // Convert to objects for view compatibility và add item details
        $orderObjects = [];
        foreach ($orders as $order) {
            $orderDetails = $this->orderModel->getOrderWithDetails($order['id']);
            
            $orderObj = (object)[
                'id' => $order['id'],
                'order_status' => $order['order_status'],
                'created_at' => $order['created_at'],
                'name' => $order['name'],
                'phone' => $order['phone'],
                'address' => $order['address'],
                'total_amount' => $order['total_amount'],
                'item_count' => count($orderDetails['items'] ?? []),
                'items' => []
            ];
            
            // Add product details for display
            if (isset($orderDetails['items'])) {
                foreach ($orderDetails['items'] as $item) {
                    $orderObj->items[] = (object)[
                        'name' => $item['product_name'],
                        'quantity' => $item['quantity'],
                        'image' => $item['image'] ?? 'no-image.jpg'
                    ];
                }
            }
            
            $orderObjects[] = $orderObj;
        }
        
        $orders = $orderObjects;
        include_once 'app/views/user/orders/index.php';
    }

    /**
     * Checkout page - for /checkout route
     */
    public function checkout() {
        // Get cart items from session
        $cartItems = [];
        $total = 0;
        
        if (isset($_SESSION['cart']) && !empty($_SESSION['cart'])) {
            $productModel = new ProductModel($this->db);
            
            foreach ($_SESSION['cart'] as $productId => $item) {
                $product = $productModel->getProductById($productId);
                if ($product) {
                    $cartItem = (object)[
                        'id' => $productId,
                        'name' => $product->name,
                        'price' => $product->price,
                        'image' => $product->image,
                        'quantity' => $item['quantity'],
                        'subtotal' => $product->price * $item['quantity']
                    ];
                    $cartItems[] = $cartItem;
                    $total += $cartItem->subtotal;
                }
            }
        }
        
        // If cart is empty, redirect to cart page
        if (empty($cartItems)) {
            header('Location: /webbanhang/cart');
            exit;
        }
        
        include_once 'app/views/user/checkout/index.php';
    }
    
    /**
     * Trang xác nhận đơn hàng đã đặt
     */
    public function confirm($orderId = null) {
        // Enable error reporting for debugging
        error_reporting(E_ALL);
        ini_set('display_errors', 1);
        
        // Debug info
        error_log("OrderController::confirm called with orderId: " . ($orderId ?? 'null'));
        
        // Nếu không có orderId, thử lấy từ URL hoặc session
        if (!$orderId) {
            // Lấy từ URL params
            $urlParts = explode('/', trim($_SERVER['REQUEST_URI'], '/'));
            $orderId = end($urlParts);
            error_log("OrderController::confirm - orderId from URL: " . $orderId);
        }
        
        if (!$orderId || !is_numeric($orderId)) {
            error_log("OrderController::confirm - Invalid orderId: " . $orderId);
            echo "<h1>Error: Invalid Order ID</h1>";
            echo "<p>Order ID '{$orderId}' is not valid</p>";
            echo "<p><a href='/webbanhang/'>Go to Homepage</a></p>";
            exit;
        }
        
        // Check if user is logged in first
        if (!AuthHelper::isLoggedIn()) {
            error_log("OrderController::confirm - User not logged in");
            echo "<h1>Error: Not Logged In</h1>";
            echo "<p>You must be logged in to view this page</p>";
            echo "<p><a href='/webbanhang/account/login'>Login</a></p>";
            exit;
        }
        
        try {
            // Check if user can view this order
            if (!AuthHelper::canViewOrder($orderId)) {
                error_log("OrderController::confirm - User cannot view order: " . $orderId);
                echo "<h1>Error: Access Denied</h1>";
                echo "<p>You don't have permission to view this order</p>";
                echo "<p><a href='/webbanhang/'>Go to Homepage</a></p>";
                exit;
            }
            
            // Get order with details
            $order = $this->orderModel->getOrderWithDetails($orderId);
            if (!$order) {
                error_log("OrderController::confirm - Order not found: " . $orderId);
                echo "<h1>Error: Order Not Found</h1>";
                echo "<p>Order #{$orderId} was not found</p>";
                echo "<p><a href='/webbanhang/'>Go to Homepage</a></p>";
                exit;
            }
            
            error_log("OrderController::confirm - Order loaded successfully: " . $orderId);
            
            // Check if view file exists
            $viewFile = 'app/views/orders/confirm.php';
            if (!file_exists($viewFile)) {
                error_log("OrderController::confirm - View file not found: " . $viewFile);
                echo "<h1>Error: View File Missing</h1>";
                echo "<p>The confirm view file is missing</p>";
                exit;
            }
            
            // Include the view
            include_once $viewFile;
            
        } catch (Exception $e) {
            error_log("OrderController::confirm - Exception: " . $e->getMessage());
            echo "<h1>System Error</h1>";
            echo "<p>An error occurred: " . htmlspecialchars($e->getMessage()) . "</p>";
            echo "<p><strong>File:</strong> " . $e->getFile() . "</p>";
            echo "<p><strong>Line:</strong> " . $e->getLine() . "</p>";
            echo "<pre>" . htmlspecialchars($e->getTraceAsString()) . "</pre>";
            exit;
        }
    }
    
    /**
     * Cập nhật trạng thái đơn hàng (chỉ admin)
     */
    public function updateStatus() {
        AuthHelper::requireAdmin();
        
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $orderId = $_POST['order_id'] ?? '';
            $status = $_POST['status'] ?? '';
            
            if (empty($orderId) || empty($status)) {
                echo json_encode(['success' => false, 'message' => 'Thiếu thông tin!']);
                return;
            }
            
            try {
                $result = $this->orderModel->updateOrderStatus($orderId, $status);
                if ($result) {
                    echo json_encode(['success' => true, 'message' => 'Cập nhật thành công!']);
                } else {
                    echo json_encode(['success' => false, 'message' => 'Cập nhật thất bại!']);
                }
            } catch (Exception $e) {
                echo json_encode(['success' => false, 'message' => $e->getMessage()]);
            }
        }
    }
    
    /**
     * Tính tổng tiền đơn hàng
     */
    private function calculateOrderTotal($products) {
        $total = 0;
        foreach ($products as $product) {
            $total += $product['price'] * $product['quantity'];
        }
        return $total;
    }
    
    /**
     * Tính discount từ voucher
     */
    private function calculateVoucherDiscount($voucher, $orderTotal) {
        if ($voucher['discount_type'] === 'percentage') {
            $discount = ($orderTotal * $voucher['discount_value']) / 100;
            if ($voucher['max_discount_amount'] && $discount > $voucher['max_discount_amount']) {
                $discount = $voucher['max_discount_amount'];
            }
        } else {
            $discount = $voucher['discount_value'];
        }
        
        return min($discount, $orderTotal);
    }
    
    /**
     * Xem chi tiết đơn hàng (user view detail)
     */
    public function view($orderId) {
        AuthHelper::requireLogin();
        
        $userId = AuthHelper::getUserId();
        $order = $this->orderModel->getOrderWithDetails($orderId);
        
        // Kiểm tra quyền xem
        if (!$order || (!AuthHelper::isAdmin() && $order['user_id'] != $userId)) {
            http_response_code(403);
            include 'app/views/errors/403.php';
            exit;
        }
        
        include_once 'app/views/user/orders/view.php';
    }
    
    /**
     * Thanh toán đơn hàng
     */
    public function payment($orderId) {
        AuthHelper::requireLogin();
        
        $userId = AuthHelper::getUserId();
        $order = $this->orderModel->getOrderWithDetails($orderId);
        
        // Kiểm tra quyền và trạng thái
        if (!$order || (!AuthHelper::isAdmin() && $order['user_id'] != $userId)) {
            http_response_code(403);
            include 'app/views/errors/403.php';
            exit;
        }
        
        if ($order['order_status'] !== 'unpaid') {
            header('Location: /webbanhang/user/orders');
            exit;
        }
        
        include_once 'app/views/user/orders/payment.php';
    }
    
    /**
     * Xử lý thanh toán
     */
    public function processPayment() {
        AuthHelper::requireLogin();
        
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $orderId = $_POST['order_id'] ?? '';
            $paymentMethod = $_POST['payment_method'] ?? '';
            
            $userId = AuthHelper::getUserId();
            $order = $this->orderModel->getOrderWithDetails($orderId);
            
            // Kiểm tra quyền
            if (!$order || (!AuthHelper::isAdmin() && $order['user_id'] != $userId)) {
                http_response_code(403);
                echo json_encode(['success' => false, 'message' => 'Không có quyền truy cập']);
                exit;
            }
            
            // Cập nhật trạng thái thanh toán
            $updated = $this->orderModel->updateOrderStatus($orderId, 'paid');
            
            if ($updated) {
                echo json_encode(['success' => true, 'message' => 'Thanh toán thành công']);
            } else {
                echo json_encode(['success' => false, 'message' => 'Có lỗi xảy ra khi thanh toán']);
            }
        }
    }
    
    /**
     * Hủy đơn hàng
     */
    public function cancel() {
        AuthHelper::requireLogin();
        
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $orderId = $_POST['order_id'] ?? '';
            $userId = AuthHelper::getUserId();
            $order = $this->orderModel->getOrderWithDetails($orderId);
            
            // Kiểm tra quyền
            if (!$order || (!AuthHelper::isAdmin() && $order['user_id'] != $userId)) {
                echo json_encode(['success' => false, 'message' => 'Không có quyền truy cập']);
                exit;
            }
            
            // Chỉ có thể hủy đơn hàng chưa thanh toán
            if ($order['order_status'] !== 'unpaid') {
                echo json_encode(['success' => false, 'message' => 'Không thể hủy đơn hàng đã thanh toán']);
                exit;
            }
            
            $updated = $this->orderModel->updateOrderStatus($orderId, 'cancelled');
            
            if ($updated) {
                echo json_encode(['success' => true, 'message' => 'Đơn hàng đã được hủy']);
            } else {
                echo json_encode(['success' => false, 'message' => 'Có lỗi xảy ra khi hủy đơn hàng']);
            }
        }
    }
    
    /**
     * Tải hóa đơn
     */
    public function invoice($orderId) {
        AuthHelper::requireLogin();
        
        $userId = AuthHelper::getUserId();
        $order = $this->orderModel->getOrderWithDetails($orderId);
        
        // Kiểm tra quyền
        if (!$order || (!AuthHelper::isAdmin() && $order['user_id'] != $userId)) {
            http_response_code(403);
            include 'app/views/errors/403.php';
            exit;
        }
        
        // Generate PDF invoice
        $this->generateInvoicePDF($order);
    }
    
    /**
     * Generate PDF hóa đơn
     */
    private function generateInvoicePDF($order) {
        // Set headers for PDF download
        header('Content-Type: text/html; charset=utf-8');
        header('Content-Disposition: inline; filename="hoa-don-' . $order['id'] . '.html"');
        
        // Include invoice template
        include_once 'app/views/user/orders/invoice.php';
    }
    
    /**
     * Mua lại đơn hàng
     */
    public function reorder() {
        AuthHelper::requireLogin();
        
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $orderId = $_POST['order_id'] ?? '';
            $userId = AuthHelper::getUserId();
            $order = $this->orderModel->getOrderWithDetails($orderId);
            
            // Kiểm tra quyền
            if (!$order || (!AuthHelper::isAdmin() && $order['user_id'] != $userId)) {
                echo json_encode(['success' => false, 'message' => 'Không có quyền truy cập']);
                exit;
            }
            
            // Thêm các sản phẩm vào giỏ hàng
            if (!isset($_SESSION['cart'])) {
                $_SESSION['cart'] = [];
            }
            
            foreach ($order['items'] as $item) {
                $productId = $item['product_id'];
                if (isset($_SESSION['cart'][$productId])) {
                    $_SESSION['cart'][$productId]['quantity'] += $item['quantity'];
                } else {
                    $_SESSION['cart'][$productId] = [
                        'name' => $item['product_name'],
                        'price' => $item['price'],
                        'quantity' => $item['quantity'],
                        'image' => $item['image'] ?? 'no-image.jpg'
                    ];
                }
            }
            
            echo json_encode(['success' => true, 'message' => 'Đã thêm sản phẩm vào giỏ hàng']);
        }
    }
}
?> 
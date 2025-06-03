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
        
        include_once 'app/views/orders/create.php';
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
                include_once 'app/views/orders/create.php';
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
                include_once 'app/views/orders/create.php';
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
            include_once 'app/views/orders/index.php';
        }
    }
    
    /**
     * Xác nhận đơn hàng
     */
    public function confirm($orderId) {
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
        
        include_once 'app/views/orders/confirm.php';
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
}
?> 
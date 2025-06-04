<?php
// API để validate voucher code
session_start();
header('Content-Type: application/json');

require_once 'app/config/database.php';
require_once 'app/models/OrderModel.php';
require_once 'app/helpers/AuthHelper.php';

try {
    // Check if user is logged in
    if (!AuthHelper::isLoggedIn()) {
        http_response_code(401);
        echo json_encode(['success' => false, 'message' => 'Vui lòng đăng nhập']);
        exit;
    }
    
    // Get JSON input
    $input = json_decode(file_get_contents('php://input'), true);
    
    if (!$input) {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'Dữ liệu không hợp lệ']);
        exit;
    }
    
    $voucherCode = $input['voucher_code'] ?? '';
    $orderAmount = $input['order_amount'] ?? 0;
    
    if (empty($voucherCode)) {
        echo json_encode(['success' => false, 'message' => 'Vui lòng nhập mã giảm giá']);
        exit;
    }
    
    if ($orderAmount <= 0) {
        echo json_encode(['success' => false, 'message' => 'Số tiền đơn hàng không hợp lệ']);
        exit;
    }
    
    // Initialize database and models
    $database = new Database();
    $db = $database->getConnection();
    $orderModel = new OrderModel($db);
    
    // Validate voucher
    $voucher = $orderModel->getValidVoucher($voucherCode, $orderAmount);
    
    if (!$voucher) {
        echo json_encode([
            'success' => false, 
            'message' => 'Mã giảm giá không tồn tại hoặc không còn hiệu lực'
        ]);
        exit;
    }
    
    // Calculate discount
    if ($voucher['discount_type'] === 'percentage') {
        $discount = ($orderAmount * $voucher['discount_value']) / 100;
        if ($voucher['max_discount_amount'] && $discount > $voucher['max_discount_amount']) {
            $discount = $voucher['max_discount_amount'];
        }
        $discountText = $voucher['discount_value'] . '% (tối đa ' . number_format($voucher['max_discount_amount'] ?? $discount, 0, ',', '.') . ' đ)';
    } else {
        $discount = min($voucher['discount_value'], $orderAmount);
        $discountText = number_format($discount, 0, ',', '.') . ' đ';
    }
    
    // Return success response
    echo json_encode([
        'success' => true,
        'message' => 'Áp dụng mã giảm giá thành công! Giảm ' . $discountText,
        'discount' => $discount,
        'voucher' => [
            'id' => $voucher['id'],
            'code' => $voucher['code'],
            'name' => $voucher['name'],
            'type' => $voucher['discount_type'],
            'value' => $voucher['discount_value']
        ]
    ]);
    
} catch (Exception $e) {
    error_log("API validate voucher error: " . $e->getMessage());
    http_response_code(500);
    echo json_encode([
        'success' => false, 
        'message' => 'Có lỗi xảy ra khi kiểm tra mã giảm giá'
    ]);
}
?> 
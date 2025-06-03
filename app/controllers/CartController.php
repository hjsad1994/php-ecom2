<?php
require_once 'app/models/ProductModel.php';
require_once 'app/helpers/SessionHelper.php';
require_once 'app/config/database.php';

class CartController {
    private $productModel;
    private $db;
    
    public function __construct() {
        $this->db = (new Database())->getConnection();
        $this->productModel = new ProductModel($this->db);
    }
    
    // Display cart page
    public function index() {
        $cartItems = $this->getCartItems();
        include 'app/views/user/cart/index.php';
    }
    
    // Add product to cart
    public function add() {
        header('Content-Type: application/json');
        
        $productId = $_POST['product_id'] ?? null;
        $quantity = $_POST['quantity'] ?? 1;
        
        if (!$productId) {
            echo json_encode(['success' => false, 'message' => 'Product ID is required']);
            return;
        }
        
        // Get product info
        $product = $this->productModel->getProductById($productId);
        if (!$product) {
            echo json_encode(['success' => false, 'message' => 'Product not found']);
            return;
        }
        
        // Initialize cart if not exists
        if (!isset($_SESSION['cart'])) {
            $_SESSION['cart'] = [];
        }
        
        // Add or update item in cart
        if (isset($_SESSION['cart'][$productId])) {
            $_SESSION['cart'][$productId]['quantity'] += $quantity;
        } else {
            $_SESSION['cart'][$productId] = [
                'product_id' => $productId,
                'quantity' => $quantity,
                'price' => $product->price,
                'name' => $product->name,
                'image' => $product->image
            ];
        }
        
        echo json_encode([
            'success' => true, 
            'message' => 'Product added to cart',
            'cart_count' => $this->getCartItemCount()
        ]);
    }
    
    // Update cart item quantity
    public function update() {
        header('Content-Type: application/json');
        
        $productId = $_POST['product_id'] ?? null;
        $quantity = $_POST['quantity'] ?? 1;
        
        if (!$productId || !isset($_SESSION['cart'][$productId])) {
            echo json_encode(['success' => false, 'message' => 'Item not found in cart']);
            return;
        }
        
        if ($quantity <= 0) {
            unset($_SESSION['cart'][$productId]);
        } else {
            $_SESSION['cart'][$productId]['quantity'] = $quantity;
        }
        
        echo json_encode([
            'success' => true,
            'message' => 'Cart updated',
            'cart_count' => $this->getCartItemCount()
        ]);
    }
    
    // Remove item from cart
    public function remove() {
        header('Content-Type: application/json');
        
        $productId = $_POST['product_id'] ?? null;
        
        if (!$productId || !isset($_SESSION['cart'][$productId])) {
            echo json_encode(['success' => false, 'message' => 'Item not found in cart']);
            return;
        }
        
        unset($_SESSION['cart'][$productId]);
        
        echo json_encode([
            'success' => true,
            'message' => 'Item removed from cart',
            'cart_count' => $this->getCartItemCount()
        ]);
    }
    
    // Clear entire cart
    public function clear() {
        header('Content-Type: application/json');
        
        $_SESSION['cart'] = [];
        
        echo json_encode([
            'success' => true,
            'message' => 'Cart cleared',
            'cart_count' => 0
        ]);
    }
    
    // Apply voucher
    public function applyVoucher() {
        header('Content-Type: application/json');
        
        try {
            $voucherCode = strtoupper(trim($_POST['voucher_code'] ?? ''));
            
            if (empty($voucherCode)) {
                echo json_encode(['success' => false, 'message' => 'Vui lòng nhập mã giảm giá']);
                return;
            }
            
            // Calculate cart total
            $cartTotal = 0;
            $productIds = [];
            
            if (isset($_SESSION['cart']) && !empty($_SESSION['cart'])) {
                foreach ($_SESSION['cart'] as $productId => $item) {
                    $cartTotal += $item['price'] * $item['quantity'];
                    $productIds[] = $productId;
                }
            }
            
            if ($cartTotal <= 0) {
                echo json_encode(['success' => false, 'message' => 'Giỏ hàng trống']);
                return;
            }
            
            // Use VoucherModel for validation
            require_once 'app/config/database.php';
            require_once 'app/models/VoucherModel.php';
            $db = (new Database())->getConnection();
            $voucherModel = new VoucherModel($db);
            
            $result = $voucherModel->validateVoucher($voucherCode, $cartTotal, $productIds);
            
            if ($result['valid']) {
                $discount = $voucherModel->calculateDiscount($result['voucher'], $cartTotal);
                
                $_SESSION['applied_voucher'] = [
                    'id' => $result['voucher']->id,
                    'code' => $result['voucher']->code,
                    'name' => $result['voucher']->name,
                    'discount' => $discount,
                    'type' => $result['voucher']->discount_type,
                    'original_total' => $cartTotal,
                    'final_total' => $cartTotal - $discount
                ];
                
                echo json_encode([
                    'success' => true, 
                    'message' => 'Áp dụng voucher thành công!',
                    'discount' => $discount,
                    'voucher_name' => $result['voucher']->name,
                    'original_total' => $cartTotal,
                    'final_total' => $cartTotal - $discount
                ]);
            } else {
                echo json_encode(['success' => false, 'message' => $result['message']]);
            }
            
        } catch (Exception $e) {
            error_log("CartController::applyVoucher error: " . $e->getMessage());
            echo json_encode(['success' => false, 'message' => 'Có lỗi xảy ra khi áp dụng mã giảm giá']);
        }
    }
    
    // Remove voucher
    public function removeVoucher() {
        header('Content-Type: application/json');
        
        try {
            if (isset($_SESSION['applied_voucher'])) {
                unset($_SESSION['applied_voucher']);
                echo json_encode(['success' => true, 'message' => 'Đã xóa mã giảm giá']);
            } else {
                echo json_encode(['success' => false, 'message' => 'Không có mã giảm giá nào để xóa']);
            }
        } catch (Exception $e) {
            error_log("CartController::removeVoucher error: " . $e->getMessage());
            echo json_encode(['success' => false, 'message' => 'Có lỗi xảy ra khi xóa mã giảm giá']);
        }
    }
    
    // Get cart items with product details
    private function getCartItems() {
        if (!isset($_SESSION['cart']) || empty($_SESSION['cart'])) {
            return [];
        }
        
        $cartItems = [];
        foreach ($_SESSION['cart'] as $productId => $item) {
            // Get updated product info
            $product = $this->productModel->getProductById($productId);
            if ($product) {
                $cartItem = (object) [
                    'id' => $productId,
                    'name' => $product->name,
                    'price' => $product->price,
                    'image' => $product->image,
                    'quantity' => $item['quantity'],
                    'category_name' => $product->category_name ?? null
                ];
                $cartItems[] = $cartItem;
            }
        }
        
        return $cartItems;
    }
    
    // Get total number of items in cart
    private function getCartItemCount() {
        if (!isset($_SESSION['cart'])) {
            return 0;
        }
        
        $count = 0;
        foreach ($_SESSION['cart'] as $item) {
            $count += $item['quantity'];
        }
        
        return $count;
    }
    
    // Get cart total amount
    public function getCartTotal() {
        if (!isset($_SESSION['cart'])) {
            return 0;
        }
        
        $total = 0;
        foreach ($_SESSION['cart'] as $item) {
            $total += $item['price'] * $item['quantity'];
        }
        
        // Apply voucher discount if any
        if (isset($_SESSION['applied_voucher'])) {
            $voucher = $_SESSION['applied_voucher'];
            if ($voucher['type'] === 'percentage') {
                $total = $total * (1 - $voucher['discount'] / 100);
            } elseif ($voucher['type'] === 'fixed') {
                $total = max(0, $total - $voucher['discount']);
            }
        }
        
        return $total;
    }
}
?> 
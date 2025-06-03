<?php
class OrderModel {
    private $db;
    
    public function __construct($database) {
        $this->db = $database;
    }
    
    /**
     * Tạo đơn hàng mới
     */
    public function createOrder($orderData) {
        $sql = "INSERT INTO orders (user_id, name, phone, address, total_amount, voucher_id, voucher_code, voucher_discount, order_status) 
                VALUES (:user_id, :name, :phone, :address, :total_amount, :voucher_id, :voucher_code, :voucher_discount, :order_status)";
        
        $stmt = $this->db->prepare($sql);
        
        $success = $stmt->execute([
            ':user_id' => $orderData['user_id'],
            ':name' => $orderData['name'],
            ':phone' => $orderData['phone'],
            ':address' => $orderData['address'],
            ':total_amount' => $orderData['total_amount'],
            ':voucher_id' => $orderData['voucher_id'],
            ':voucher_code' => $orderData['voucher_code'],
            ':voucher_discount' => $orderData['voucher_discount'],
            ':order_status' => $orderData['order_status']
        ]);
        
        if ($success) {
            return $this->db->lastInsertId();
        }
        
        return false;
    }
    
    /**
     * Thêm chi tiết đơn hàng
     */
    public function addOrderDetail($orderId, $detailData) {
        $sql = "INSERT INTO order_details (order_id, product_id, quantity, price) 
                VALUES (:order_id, :product_id, :quantity, :price)";
        
        $stmt = $this->db->prepare($sql);
        
        return $stmt->execute([
            ':order_id' => $orderId,
            ':product_id' => $detailData['product_id'],
            ':quantity' => $detailData['quantity'],
            ':price' => $detailData['price']
        ]);
    }
    
    /**
     * Lấy đơn hàng theo ID
     */
    public function getById($orderId) {
        $sql = "SELECT * FROM orders WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':id' => $orderId]);
        
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    /**
     * Lấy đơn hàng với chi tiết sản phẩm
     */
    public function getOrderWithDetails($orderId) {
        try {
            error_log("OrderModel::getOrderWithDetails - Start with orderId: " . $orderId);
            
            // Lấy thông tin đơn hàng - sử dụng 'account' table
            $orderSql = "SELECT o.*, a.username 
                         FROM orders o 
                         LEFT JOIN account a ON o.user_id = a.id 
                         WHERE o.id = :id";
            
            $stmt = $this->db->prepare($orderSql);
            $stmt->execute([':id' => $orderId]);
            $order = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if (!$order) {
                error_log("OrderModel::getOrderWithDetails - Order not found: " . $orderId);
                return null;
            }
            
            error_log("OrderModel::getOrderWithDetails - Order found: " . json_encode($order));
            
            // Lấy chi tiết đơn hàng
            $detailsSql = "SELECT od.*, p.name as product_name, p.image
                           FROM order_details od
                           LEFT JOIN product p ON od.product_id = p.id
                           WHERE od.order_id = :order_id";
            
            $stmt = $this->db->prepare($detailsSql);
            $stmt->execute([':order_id' => $orderId]);
            $items = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            error_log("OrderModel::getOrderWithDetails - Items found: " . count($items));
            
            // Nếu không có items, tạo items rỗng để tránh lỗi
            $order['items'] = $items ?: [];
            
            // Tính subtotal
            $order['subtotal'] = 0;
            foreach ($order['items'] as $item) {
                $order['subtotal'] += $item['price'] * $item['quantity'];
            }
            
            error_log("OrderModel::getOrderWithDetails - Success, returning order");
            return $order;
            
        } catch (Exception $e) {
            error_log("OrderModel::getOrderWithDetails - Exception: " . $e->getMessage());
            return null;
        }
    }
    
    /**
     * Lấy tất cả đơn hàng (admin)
     */
    public function getAllOrders($limit = 50, $offset = 0) {
        $sql = "SELECT o.*, a.username,
                       COUNT(od.id) as item_count
                FROM orders o 
                LEFT JOIN account a ON o.user_id = a.id 
                LEFT JOIN order_details od ON o.id = od.order_id
                GROUP BY o.id
                ORDER BY o.created_at DESC 
                LIMIT :limit OFFSET :offset";
        
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();
        
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }
    
    /**
     * Lấy đơn hàng theo user ID (user chỉ xem được của mình)
     */
    public function getOrdersByUserId($userId, $limit = 20, $offset = 0) {
        $sql = "SELECT o.* FROM orders o 
                WHERE o.user_id = :user_id 
                ORDER BY o.created_at DESC 
                LIMIT :limit OFFSET :offset";
        
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
        $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    /**
     * Cập nhật trạng thái đơn hàng (chỉ admin)
     */
    public function updateOrderStatus($orderId, $status) {
        $allowedStatuses = ['unpaid', 'paid', 'pending', 'cancelled'];
        
        if (!in_array($status, $allowedStatuses)) {
            throw new Exception("Trạng thái không hợp lệ!");
        }
        
        $sql = "UPDATE orders SET order_status = :status, updated_at = NOW() WHERE id = :id";
        
        $stmt = $this->db->prepare($sql);
        
        return $stmt->execute([
            ':status' => $status,
            ':id' => $orderId
        ]);
    }
    
    /**
     * Lấy voucher hợp lệ
     */
    public function getValidVoucher($voucherCode, $orderAmount) {
        $sql = "SELECT * FROM vouchers 
                WHERE code = :code 
                AND is_active = 1 
                AND start_date <= NOW() 
                AND end_date >= NOW() 
                AND (usage_limit IS NULL OR used_count < usage_limit)
                AND min_order_amount <= :order_amount";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            ':code' => $voucherCode,
            ':order_amount' => $orderAmount
        ]);
        
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    /**
     * Cập nhật số lần sử dụng voucher
     */
    public function incrementVoucherUsage($voucherId) {
        $sql = "UPDATE vouchers SET used_count = used_count + 1 WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        
        return $stmt->execute([':id' => $voucherId]);
    }
    
    /**
     * Thống kê đơn hàng (admin)
     */
    public function getOrderStats() {
        $sql = "SELECT 
                    COUNT(*) as total_orders,
                    SUM(CASE WHEN order_status = 'paid' THEN total_amount ELSE 0 END) as total_revenue,
                    COUNT(CASE WHEN order_status = 'unpaid' THEN 1 END) as unpaid_orders,
                    COUNT(CASE WHEN order_status = 'paid' THEN 1 END) as paid_orders,
                    COUNT(CASE WHEN order_status = 'pending' THEN 1 END) as pending_orders,
                    COUNT(CASE WHEN order_status = 'cancelled' THEN 1 END) as cancelled_orders
                FROM orders";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    /**
     * Lấy đơn hàng gần đây (admin dashboard)
     */
    public function getRecentOrders($limit = 10) {
        $sql = "SELECT o.*, a.username 
                FROM orders o 
                LEFT JOIN account a ON o.user_id = a.id 
                ORDER BY o.created_at DESC 
                LIMIT :limit";
        
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    /**
     * Lấy tất cả orders (alias cho getAllOrders)
     */
    public function getAll($limit = 50) {
        return $this->getAllOrders($limit);
    }
}
?> 
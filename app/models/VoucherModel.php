<?php
// Set timezone to Vietnam
date_default_timezone_set('Asia/Ho_Chi_Minh');

class VoucherModel
{
    private $conn;
    private $table_name = "vouchers";
    
    public function __construct($db)
    {
        $this->conn = $db;
    }
    
    public function getVouchers()
    {
        $query = "SELECT * FROM " . $this->table_name . " ORDER BY created_at DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }
    
    public function getVoucherById($id)
    {
        $query = "SELECT * FROM " . $this->table_name . " WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_OBJ);
    }
    
    public function getVoucherByCode($code)
    {
        // Remove the is_active filter here to get voucher regardless of status
        $query = "SELECT * FROM " . $this->table_name . " WHERE code = :code";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':code', $code);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_OBJ);
    }
    
    public function validateVoucher($code, $cartTotal, $productIds = [])
    {
        error_log("=== VoucherModel::validateVoucher DEBUG ===");
        error_log("Code: " . $code);
        error_log("Cart Total: " . $cartTotal);
        error_log("Product IDs: " . print_r($productIds, true));
        
        $voucher = $this->getVoucherByCode($code);
        
        if (!$voucher) {
            error_log("Voucher not found: " . $code);
            return ['valid' => false, 'message' => 'Mã voucher không tồn tại'];
        }
        
        error_log("Voucher found: " . print_r($voucher, true));
        
        // Check if voucher is active FIRST
        if (!$voucher->is_active) {
            error_log("Voucher inactive: " . $code);
            return ['valid' => false, 'message' => 'Mã voucher đã bị vô hiệu hóa'];
        }
        
        // Check date range - sử dụng timestamp để so sánh chính xác
        $now = time();
        $startTime = strtotime($voucher->start_date);
        $endTime = strtotime($voucher->end_date);
        
        error_log("Date check - Now: " . date('Y-m-d H:i:s', $now) . " Start: " . date('Y-m-d H:i:s', $startTime) . " End: " . date('Y-m-d H:i:s', $endTime));
        
        if ($now < $startTime) {
            error_log("Voucher not yet active");
            return ['valid' => false, 'message' => 'Mã voucher chưa có hiệu lực. Bắt đầu từ: ' . date('d/m/Y H:i', $startTime)];
        }
        
        if ($now > $endTime) {
            error_log("Voucher expired");
            return ['valid' => false, 'message' => 'Mã voucher đã hết hạn vào: ' . date('d/m/Y H:i', $endTime)];
        }
        
        // Check usage limit
        if ($voucher->usage_limit && $voucher->used_count >= $voucher->usage_limit) {
            error_log("Voucher usage limit exceeded");
            return ['valid' => false, 'message' => 'Mã voucher đã hết lượt sử dụng'];
        }
        
        // Check minimum order amount
        if ($cartTotal < $voucher->min_order_amount) {
            error_log("Cart total too low: {$cartTotal} < {$voucher->min_order_amount}");
            return ['valid' => false, 'message' => 'Đơn hàng chưa đạt giá trị tối thiểu ' . number_format($voucher->min_order_amount, 0, ',', '.') . ' đ để sử dụng voucher'];
        }
        
        // Check product/category applicability
        if ($voucher->applies_to == 'specific_products' && !empty($voucher->product_ids)) {
            error_log("Checking specific products");
            $allowedProducts = json_decode($voucher->product_ids, true);
            error_log("Allowed products: " . print_r($allowedProducts, true));
            if (!array_intersect($productIds, $allowedProducts)) {
                error_log("No products match");
                return ['valid' => false, 'message' => 'Voucher không áp dụng cho các sản phẩm trong giỏ hàng'];
            }
        } elseif ($voucher->applies_to == 'specific_categories' && !empty($voucher->category_ids)) {
            error_log("Checking specific categories");
            // Get categories of products in cart
            $allowedCategories = json_decode($voucher->category_ids, true);
            $cartCategories = $this->getProductCategories($productIds);
            
            error_log("Allowed categories: " . print_r($allowedCategories, true));
            error_log("Cart categories: " . print_r($cartCategories, true));
            
            if (!array_intersect($cartCategories, $allowedCategories)) {
                error_log("No categories match");
                return ['valid' => false, 'message' => 'Voucher không áp dụng cho danh mục sản phẩm trong giỏ hàng'];
            }
        }
        
        error_log("Voucher validation successful");
        return ['valid' => true, 'voucher' => $voucher];
    }

    private function getProductCategories($productIds)
    {
        if (empty($productIds)) {
            return [];
        }
        
        $placeholders = str_repeat('?,', count($productIds) - 1) . '?';
        $query = "SELECT DISTINCT category_id FROM product WHERE id IN ($placeholders) AND category_id IS NOT NULL";
        $stmt = $this->conn->prepare($query);
        $stmt->execute($productIds);
        
        return $stmt->fetchAll(PDO::FETCH_COLUMN);
    }
    
    public function addVoucher($data)
    {
        $errors = [];
        
        // Validation
        if (empty($data['code'])) {
            $errors[] = 'Mã voucher không được để trống';
        }
        if (empty($data['name'])) {
            $errors[] = 'Tên voucher không được để trống';
        }
        if (!in_array($data['discount_type'], ['percentage', 'fixed'])) {
            $errors[] = 'Loại giảm giá không hợp lệ';
        }
        if (!is_numeric($data['discount_value']) || $data['discount_value'] <= 0) {
            $errors[] = 'Giá trị giảm giá phải là số dương';
        }
        if ($data['discount_type'] == 'percentage' && $data['discount_value'] > 100) {
            $errors[] = 'Phần trăm giảm giá không được vượt quá 100%';
        }
        
        // Validate applies_to
        $validAppliesTo = ['all_products', 'specific_products', 'specific_categories'];
        if (!in_array($data['applies_to'], $validAppliesTo)) {
            $errors[] = 'Loại áp dụng voucher không hợp lệ';
        }
        
        if (!empty($errors)) {
            return $errors;
        }
        
        // Check if code already exists
        $checkQuery = "SELECT id FROM " . $this->table_name . " WHERE code = :code";
        $checkStmt = $this->conn->prepare($checkQuery);
        $checkStmt->bindParam(':code', $data['code']);
        $checkStmt->execute();
        if ($checkStmt->fetch()) {
            return ['Mã voucher đã tồn tại'];
        }
        
        try {
            // Debug log để kiểm tra dữ liệu
            error_log("Adding voucher with applies_to: " . $data['applies_to']);
            
            $query = "INSERT INTO " . $this->table_name . " 
                      (code, name, description, discount_type, discount_value, min_order_amount, 
                       max_discount_amount, applies_to, product_ids, category_ids, usage_limit, start_date, end_date, is_active) 
                      VALUES (:code, :name, :description, :discount_type, :discount_value, :min_order_amount, 
                              :max_discount_amount, :applies_to, :product_ids, :category_ids, :usage_limit, :start_date, :end_date, :is_active)";
            
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':code', $data['code']);
            $stmt->bindParam(':name', $data['name']);
            $stmt->bindParam(':description', $data['description']);
            $stmt->bindParam(':discount_type', $data['discount_type']);
            $stmt->bindParam(':discount_value', $data['discount_value']);
            $stmt->bindParam(':min_order_amount', $data['min_order_amount']);
            $stmt->bindParam(':max_discount_amount', $data['max_discount_amount']);
            $stmt->bindParam(':applies_to', $data['applies_to']);
            $stmt->bindParam(':product_ids', $data['product_ids']);
            $stmt->bindParam(':category_ids', $data['category_ids']);
            $stmt->bindParam(':usage_limit', $data['usage_limit']);
            $stmt->bindParam(':start_date', $data['start_date']);
            $stmt->bindParam(':end_date', $data['end_date']);
            $stmt->bindParam(':is_active', $data['is_active']);
            
            $result = $stmt->execute();
            
            if (!$result) {
                $errorInfo = $stmt->errorInfo();
                error_log("Database error: " . print_r($errorInfo, true));
                return ['Lỗi database: ' . $errorInfo[2]];
            }
            
            return $result;
        } catch (Exception $e) {
            error_log("VoucherModel::addVoucher error: " . $e->getMessage());
            return ['Đã xảy ra lỗi khi thêm voucher: ' . $e->getMessage()];
        }
    }
    
    public function updateVoucher($id, $data)
    {
        $query = "UPDATE " . $this->table_name . " SET 
                  name = :name, description = :description, discount_type = :discount_type, 
                  discount_value = :discount_value, min_order_amount = :min_order_amount, 
                  max_discount_amount = :max_discount_amount, applies_to = :applies_to, 
                  product_ids = :product_ids, category_ids = :category_ids, usage_limit = :usage_limit, 
                  start_date = :start_date, end_date = :end_date, is_active = :is_active 
                  WHERE id = :id";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->bindParam(':name', $data['name']);
        $stmt->bindParam(':description', $data['description']);
        $stmt->bindParam(':discount_type', $data['discount_type']);
        $stmt->bindParam(':discount_value', $data['discount_value']);
        $stmt->bindParam(':min_order_amount', $data['min_order_amount']);
        $stmt->bindParam(':max_discount_amount', $data['max_discount_amount']);
        $stmt->bindParam(':applies_to', $data['applies_to']);
        $stmt->bindParam(':product_ids', $data['product_ids']);
        $stmt->bindParam(':category_ids', $data['category_ids']);
        $stmt->bindParam(':usage_limit', $data['usage_limit']);
        $stmt->bindParam(':start_date', $data['start_date']);
        $stmt->bindParam(':end_date', $data['end_date']);
        $stmt->bindParam(':is_active', $data['is_active']);
        
        return $stmt->execute();
    }
    
    public function deleteVoucher($id)
    {
        $query = "DELETE FROM " . $this->table_name . " WHERE id=:id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        return $stmt->execute();
    }
    
    public function validateVoucherOld($code, $cartTotal, $productIds = [])
    {
        $voucher = $this->getVoucherByCode($code);
        
        if (!$voucher) {
            return ['valid' => false, 'message' => 'Mã voucher không tồn tại'];
        }
        
        // Check if voucher is active FIRST
        if (!$voucher->is_active) {
            return ['valid' => false, 'message' => 'Mã voucher đã bị vô hiệu hóa'];
        }
        
        // Check date range - sử dụng timestamp để so sánh chính xác
        $now = time();
        $startTime = strtotime($voucher->start_date);
        $endTime = strtotime($voucher->end_date);
        
        // Debug - bạn có thể bỏ comment để kiểm tra
        // error_log("Current: " . date('Y-m-d H:i:s', $now) . " Start: " . date('Y-m-d H:i:s', $startTime) . " End: " . date('Y-m-d H:i:s', $endTime));
        
        if ($now < $startTime) {
            return ['valid' => false, 'message' => 'Mã voucher chưa có hiệu lực. Bắt đầu từ: ' . date('d/m/Y H:i', $startTime)];
        }
        
        if ($now > $endTime) {
            return ['valid' => false, 'message' => 'Mã voucher đã hết hạn vào: ' . date('d/m/Y H:i', $endTime)];
        }
        
        // Check usage limit
        if ($voucher->usage_limit && $voucher->used_count >= $voucher->usage_limit) {
            return ['valid' => false, 'message' => 'Mã voucher đã hết lượt sử dụng'];
        }
        
        // Check minimum order amount
        if ($cartTotal < $voucher->min_order_amount) {
            return ['valid' => false, 'message' => 'Đơn hàng chưa đạt giá trị tối thiểu ' . number_format($voucher->min_order_amount, 0, ',', '.') . ' đ để sử dụng voucher'];
        }
        
        // Check product applicability
        if ($voucher->applies_to == 'specific_products' && !empty($voucher->product_ids)) {
            $allowedProducts = json_decode($voucher->product_ids, true);
            if (!array_intersect($productIds, $allowedProducts)) {
                return ['valid' => false, 'message' => 'Voucher không áp dụng cho các sản phẩm trong giỏ hàng'];
            }
        }
        
        return ['valid' => true, 'voucher' => $voucher];
    }
    
    public function calculateDiscount($voucher, $cartTotal)
    {
        $discount = 0;
        
        if ($voucher->discount_type == 'percentage') {
            $discount = ($cartTotal * $voucher->discount_value) / 100;
        } else {
            $discount = $voucher->discount_value;
        }
        
        // Apply maximum discount limit if set
        if ($voucher->max_discount_amount && $discount > $voucher->max_discount_amount) {
            $discount = $voucher->max_discount_amount;
        }
        
        // Discount cannot exceed cart total
        if ($discount > $cartTotal) {
            $discount = $cartTotal;
        }
        
        return $discount;
    }
    
    public function incrementUsage($id)
    {
        $query = "UPDATE " . $this->table_name . " SET used_count = used_count + 1 WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        return $stmt->execute();
    }
    
    /**
     * Đếm tổng số voucher
     */
    public function getVoucherCount()
    {
        $query = "SELECT COUNT(*) as count FROM " . $this->table_name;
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['count'];
    }
    
    /**
     * Method for admin functionality
     */
    public function getAll()
    {
        return $this->getVouchers();
    }
    
    public function save($data)
    {
        return $this->addVoucher($data);
    }
    
    /**
     * Alias cho getVoucherById
     */
    public function getById($voucherId)
    {
        $voucher = $this->getVoucherById($voucherId);
        return $voucher ? (array)$voucher : null;
    }
    
    /**
     * Alias cho updateVoucher
     */
    public function update($voucherId, $data)
    {
        return $this->updateVoucher($voucherId, $data);
    }
    
    /**
     * Lấy thống kê tổng quan voucher
     */
    public function getVoucherStats()
    {
        $sql = "SELECT 
                    COUNT(*) as total_vouchers,
                    SUM(CASE WHEN is_active = 1 THEN 1 ELSE 0 END) as active_vouchers,
                    SUM(CASE WHEN NOW() BETWEEN start_date AND end_date AND is_active = 1 THEN 1 ELSE 0 END) as valid_vouchers,
                    SUM(CASE WHEN NOW() > end_date THEN 1 ELSE 0 END) as expired_vouchers,
                    SUM(used_count) as total_usage,
                    AVG(discount_value) as avg_discount
                FROM vouchers";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_OBJ);
    }
    
    /**
     * Lấy thống kê theo loại discount
     */
    public function getDiscountTypeStats()
    {
        $sql = "SELECT 
                    discount_type,
                    COUNT(*) as count,
                    AVG(discount_value) as avg_value,
                    SUM(used_count) as total_usage
                FROM vouchers 
                GROUP BY discount_type
                ORDER BY count DESC";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }
    
    /**
     * Lấy voucher được sử dụng nhiều nhất
     */
    public function getTopUsedVouchers($limit = 5)
    {
        $sql = "SELECT code, name, used_count, usage_limit, discount_type, discount_value
                FROM vouchers 
                WHERE used_count > 0
                ORDER BY used_count DESC 
                LIMIT :limit";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }
    
    /**
     * Lấy voucher sắp hết hạn
     */
    public function getExpiringSoonVouchers($days = 7)
    {
        $sql = "SELECT code, name, end_date, is_active, used_count, usage_limit
                FROM vouchers 
                WHERE end_date BETWEEN NOW() AND DATE_ADD(NOW(), INTERVAL :days DAY)
                AND is_active = 1
                ORDER BY end_date ASC";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':days', $days, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }
}
?>
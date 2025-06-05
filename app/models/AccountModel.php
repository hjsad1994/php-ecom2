<?php
class AccountModel
{
    private $conn;
    private $table_name = "account";
    
    public function __construct($db)
    {
        $this->conn = $db;
    }
    
    public function getAccountByUsername($username)
    {
        $query = "SELECT * FROM account WHERE username = :username";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':username', $username, PDO::PARAM_STR);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_OBJ);
        return $result;
    }
    
    public function getAccountByEmail($email)
    {
        $query = "SELECT * FROM account WHERE email = :email";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':email', $email, PDO::PARAM_STR);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_OBJ);
        return $result;
    }
    
    function save($username, $password, $fullName, $email = null, $phone = null, $address = null, $role = "user") {
        // Hash password if not already hashed
        if (!password_get_info($password)['algo']) {
            $password = password_hash($password, PASSWORD_BCRYPT, ['cost' => 12]);
        }
        
        $query = "INSERT INTO " . $this->table_name . " (username, fullname, email, phone, address, password, role) VALUES (:username, :fullname, :email, :phone, :address, :password, :role)";
        
        try {
            $stmt = $this->conn->prepare($query);
            
            // Làm sạch dữ liệu
            $username = htmlspecialchars(strip_tags($username));
            $fullName = htmlspecialchars(strip_tags($fullName));
            $email = !empty($email) ? htmlspecialchars(strip_tags($email)) : null;
            $phone = !empty($phone) ? htmlspecialchars(strip_tags($phone)) : null;
            $address = !empty($address) ? htmlspecialchars(strip_tags($address)) : null;
            
            // Gán dữ liệu vào câu lệnh
            $stmt->bindParam(':username', $username);
            $stmt->bindParam(':fullname', $fullName);
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':phone', $phone);
            $stmt->bindParam(':address', $address);
            $stmt->bindParam(':password', $password);
            $stmt->bindParam(':role', $role);
            
            // Thực thi câu lệnh
            if ($stmt->execute()) {
                return true;
            } else {
                // Debug: In lỗi nếu có
                error_log("AccountModel save error: " . print_r($stmt->errorInfo(), true));
                return false;
            }
        } catch (PDOException $e) {
            // Debug: In lỗi exception
            error_log("AccountModel save exception: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Lấy tất cả tài khoản
     */
    public function getAllAccounts() {
        $query = "SELECT id, username, fullname, email, phone, address, role, created_at FROM " . $this->table_name . " ORDER BY created_at DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    /**
     * Lấy tài khoản theo ID
     */
    public function getById($id) {
        $query = "SELECT * FROM " . $this->table_name . " WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    /**
     * Kiểm tra username đã tồn tại
     */
    public function checkUsernameExists($username, $excludeId = null) {
        $query = "SELECT id FROM " . $this->table_name . " WHERE username = :username";
        if ($excludeId) {
            $query .= " AND id != :exclude_id";
        }
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':username', $username, PDO::PARAM_STR);
        if ($excludeId) {
            $stmt->bindParam(':exclude_id', $excludeId, PDO::PARAM_INT);
        }
        $stmt->execute();
        return $stmt->rowCount() > 0;
    }
    
    /**
     * Kiểm tra email đã tồn tại
     */
    public function checkEmailExists($email, $excludeId = null) {
        $query = "SELECT id FROM " . $this->table_name . " WHERE email = :email";
        if ($excludeId) {
            $query .= " AND id != :exclude_id";
        }
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':email', $email, PDO::PARAM_STR);
        if ($excludeId) {
            $stmt->bindParam(':exclude_id', $excludeId, PDO::PARAM_INT);
        }
        $stmt->execute();
        return $stmt->rowCount() > 0;
    }
    
    /**
     * Cập nhật tài khoản
     */
    public function updateAccount($id, $data) {
        $fields = [];
        $params = [':id' => $id];
        
        if (isset($data['username'])) {
            $fields[] = "username = :username";
            $params[':username'] = $data['username'];
        }
        
        if (isset($data['password']) && !empty($data['password'])) {
            $fields[] = "password = :password";
            $params[':password'] = $data['password'];
        }
        
        if (isset($data['fullname'])) {
            $fields[] = "fullname = :fullname";
            $params[':fullname'] = $data['fullname'];
        }
        
        if (isset($data['email'])) {
            $fields[] = "email = :email";
            $params[':email'] = $data['email'];
        }
        
        if (isset($data['phone'])) {
            $fields[] = "phone = :phone";
            $params[':phone'] = $data['phone'];
        }
        
        if (isset($data['address'])) {
            $fields[] = "address = :address";
            $params[':address'] = $data['address'];
        }
        
        if (isset($data['role'])) {
            $fields[] = "role = :role";
            $params[':role'] = $data['role'];
        }
        
        if (empty($fields)) {
            return false;
        }
        
        $query = "UPDATE " . $this->table_name . " SET " . implode(', ', $fields) . " WHERE id = :id";
        
        try {
            $stmt = $this->conn->prepare($query);
            return $stmt->execute($params);
        } catch (PDOException $e) {
            error_log("AccountModel updateAccount exception: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Xóa tài khoản
     */
    public function deleteAccount($id) {
        try {
            $query = "DELETE FROM " . $this->table_name . " WHERE id = :id";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("AccountModel deleteAccount exception: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Lấy thống kê tài khoản
     */
    public function getAccountStats() {
        try {
            // Tổng số tài khoản
            $query = "SELECT COUNT(*) as total FROM " . $this->table_name;
            $stmt = $this->conn->prepare($query);
            $stmt->execute();
            $total = $stmt->fetch(PDO::FETCH_ASSOC)['total'];
            
            // Số admin
            $query = "SELECT COUNT(*) as admin_count FROM " . $this->table_name . " WHERE role = 'admin'";
            $stmt = $this->conn->prepare($query);
            $stmt->execute();
            $adminCount = $stmt->fetch(PDO::FETCH_ASSOC)['admin_count'];
            
            // Số user
            $query = "SELECT COUNT(*) as user_count FROM " . $this->table_name . " WHERE role = 'user'";
            $stmt = $this->conn->prepare($query);
            $stmt->execute();
            $userCount = $stmt->fetch(PDO::FETCH_ASSOC)['user_count'];
            
            // Đăng ký gần đây (7 ngày)
            $query = "SELECT COUNT(*) as recent FROM " . $this->table_name . " WHERE created_at >= DATE_SUB(NOW(), INTERVAL 7 DAY)";
            $stmt = $this->conn->prepare($query);
            $stmt->execute();
            $recentRegistrations = $stmt->fetch(PDO::FETCH_ASSOC)['recent'];
            
            return [
                'total_accounts' => $total,
                'admin_accounts' => $adminCount,
                'user_accounts' => $userCount,
                'recent_registrations' => $recentRegistrations
            ];
        } catch (PDOException $e) {
            error_log("AccountModel getAccountStats exception: " . $e->getMessage());
            return [
                'total_accounts' => 0,
                'admin_accounts' => 0,
                'user_accounts' => 0,
                'recent_registrations' => 0
            ];
        }
    }
} 
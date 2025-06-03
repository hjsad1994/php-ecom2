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
    
    function save($username, $fullName, $password, $role = "user") {
        // Note: $fullName parameter không được sử dụng vì database table không có field này
        $query = "INSERT INTO " . $this->table_name . " (username, password, role) VALUES (:username, :password, :role)";
        
        try {
            $stmt = $this->conn->prepare($query);
            
            // Làm sạch dữ liệu
            $username = htmlspecialchars(strip_tags($username));
            
            // Gán dữ liệu vào câu lệnh
            $stmt->bindParam(':username', $username);
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
} 
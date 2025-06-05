<?php

class PasswordResetModel {
    private $db;
    
    public function __construct($database) {
        $this->db = $database;
    }
    
    /**
     * Tạo token reset password mới
     */
    public function createResetToken($email) {
        try {
            // Xóa các token cũ của email này
            $this->cleanupOldTokens($email);
            
            // Tạo token ngẫu nhiên
            $token = bin2hex(random_bytes(32));
            
            // Token có hiệu lực trong 1 giờ - sử dụng MySQL function để tránh vấn đề timezone
            $query = "INSERT INTO password_resets (email, token, expires_at) VALUES (:email, :token, DATE_ADD(NOW(), INTERVAL 1 HOUR))";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':token', $token);
            
            if ($stmt->execute()) {
                return $token;
            }
            return false;
        } catch (Exception $e) {
            error_log("PasswordResetModel createResetToken error: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Xác thực token reset password
     */
    public function validateResetToken($token) {
        try {
            $query = "SELECT * FROM password_resets 
                     WHERE token = :token 
                     AND expires_at > NOW() 
                     AND used = 0";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':token', $token);
            $stmt->execute();
            
            return $stmt->fetch(PDO::FETCH_OBJ);
        } catch (Exception $e) {
            error_log("PasswordResetModel validateResetToken error: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Đánh dấu token đã được sử dụng
     */
    public function markTokenAsUsed($token) {
        try {
            $query = "UPDATE password_resets SET used = 1 WHERE token = :token";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':token', $token);
            
            return $stmt->execute();
        } catch (Exception $e) {
            error_log("PasswordResetModel markTokenAsUsed error: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Xóa các token cũ của email
     */
    private function cleanupOldTokens($email) {
        try {
            $query = "DELETE FROM password_resets 
                     WHERE email = :email 
                     OR expires_at < NOW()";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':email', $email);
            $stmt->execute();
        } catch (Exception $e) {
            error_log("PasswordResetModel cleanupOldTokens error: " . $e->getMessage());
        }
    }
    
    /**
     * Kiểm tra email có tồn tại trong hệ thống không
     */
    public function emailExists($email) {
        try {
            $query = "SELECT id FROM account WHERE email = :email";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':email', $email);
            $stmt->execute();
            
            return $stmt->fetch(PDO::FETCH_OBJ) !== false;
        } catch (Exception $e) {
            error_log("PasswordResetModel emailExists error: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Cập nhật mật khẩu mới cho user
     */
    public function updatePassword($email, $newPassword) {
        try {
            $hashedPassword = password_hash($newPassword, PASSWORD_BCRYPT, ['cost' => 12]);
            
            $query = "UPDATE account SET password = :password WHERE email = :email";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':password', $hashedPassword);
            $stmt->bindParam(':email', $email);
            
            return $stmt->execute();
        } catch (Exception $e) {
            error_log("PasswordResetModel updatePassword error: " . $e->getMessage());
            return false;
        }
    }
} 
<?php
class SessionHelper {
    
    public static function isLoggedIn() {
        return isset($_SESSION['username']);
    }
    
    public static function isAdmin() {
        return isset($_SESSION['username']) && $_SESSION['user_role'] === 'admin';
    }
    
    public static function getUsername() {
        return $_SESSION['username'] ?? null;
    }
    
    public static function getUserRole() {
        return $_SESSION['user_role'] ?? null;
    }
    
    public static function getUserEmail() {
        return $_SESSION['user_email'] ?? null;
    }
    
    public static function getUserId() {
        return $_SESSION['user_id'] ?? null;
    }
    
    public static function getUser() {
        if (!self::isLoggedIn()) {
            return null;
        }
        
        return [
            'id' => $_SESSION['user_id'] ?? null,
            'username' => $_SESSION['username'] ?? null,
            'role' => $_SESSION['user_role'] ?? null,
            'email' => $_SESSION['user_email'] ?? null
        ];
    }
    
    public static function logout() {
        unset($_SESSION['username']);
        unset($_SESSION['user_role']);
        unset($_SESSION['user_email']);
        unset($_SESSION['user_id']);
        session_destroy();
    }
} 
-- SQL Script để tạo bảng users cho authentication system
-- Database: my_store

CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(255) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Hoặc nếu muốn sử dụng tên bảng 'account' như trong code:
CREATE TABLE account (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(255) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    role VARCHAR(50) DEFAULT 'user',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Insert admin account mẫu (password: admin123)
INSERT INTO account (username, password, role) VALUES 
('admin', '$2y$12$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin');

-- Insert user account mẫu (password: user123)  
INSERT INTO account (username, password, role) VALUES 
('user1', '$2y$12$TttskdkIXe6uFJAMwqq5UuZYvs4E9blqMQCavYkvQXuFdTrf7I1W6', 'user'); 
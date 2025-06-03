-- Tạo bảng orders cho hệ thống đặt hàng
CREATE TABLE IF NOT EXISTS `orders` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT NULL,
  `name` varchar(255) NOT NULL,
  `phone` varchar(20) NOT NULL,
  `address` text NOT NULL,
  `total_amount` decimal(10,2) NOT NULL DEFAULT 0.00,
  `voucher_id` int(11) DEFAULT NULL,
  `voucher_code` varchar(50) DEFAULT NULL,
  `voucher_discount` decimal(10,2) DEFAULT 0.00,
  `order_status` enum('unpaid','paid','pending','cancelled','completed') DEFAULT 'unpaid',
  `payment_method` varchar(50) DEFAULT 'cod',
  `shipping_method` varchar(50) DEFAULT 'standard',
  `notes` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_user_id` (`user_id`),
  KEY `idx_order_status` (`order_status`),
  KEY `idx_created_at` (`created_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Tạo bảng order_details cho chi tiết đơn hàng
CREATE TABLE IF NOT EXISTS `order_details` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `order_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL DEFAULT 1,
  `price` decimal(10,2) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_order_id` (`order_id`),
  KEY `idx_product_id` (`product_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Insert sample data for testing
INSERT IGNORE INTO `orders` (`id`, `user_id`, `name`, `phone`, `address`, `total_amount`, `order_status`, `created_at`) VALUES
(1, 1, 'Nguyễn Văn A', '0123456789', '123 Đường ABC, Quận 1, TP.HCM', 500000.00, 'paid', '2024-01-15 10:30:00'),
(2, 1, 'Nguyễn Văn A', '0123456789', '123 Đường ABC, Quận 1, TP.HCM', 750000.00, 'unpaid', '2024-01-16 14:20:00');

INSERT IGNORE INTO `order_details` (`order_id`, `product_id`, `quantity`, `price`) VALUES
(1, 1, 1, 250000.00),
(1, 2, 1, 250000.00),
(2, 3, 2, 300000.00),
(2, 1, 1, 150000.00); 
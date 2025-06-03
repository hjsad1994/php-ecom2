-- Create vouchers table if not exists
CREATE TABLE IF NOT EXISTS `vouchers` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `code` varchar(50) NOT NULL UNIQUE,
  `name` varchar(255) NOT NULL,
  `description` text,
  `discount_type` enum('percentage','fixed') NOT NULL DEFAULT 'percentage',
  `discount_value` decimal(10,2) NOT NULL,
  `min_order_amount` decimal(10,2) DEFAULT 0,
  `max_discount_amount` decimal(10,2) DEFAULT NULL,
  `applies_to` enum('all_products','specific_products','specific_categories') NOT NULL DEFAULT 'all_products',
  `product_ids` text,
  `category_ids` text,
  `usage_limit` int(11) DEFAULT NULL,
  `used_count` int(11) DEFAULT 0,
  `start_date` datetime NOT NULL,
  `end_date` datetime NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `code` (`code`),
  KEY `is_active` (`is_active`),
  KEY `start_date` (`start_date`),
  KEY `end_date` (`end_date`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Insert sample vouchers for testing
INSERT INTO `vouchers` (`code`, `name`, `description`, `discount_type`, `discount_value`, `min_order_amount`, `max_discount_amount`, `applies_to`, `usage_limit`, `start_date`, `end_date`, `is_active`) VALUES
('GIAMGIA10', 'Giảm giá 10%', 'Voucher giảm 10% cho tất cả sản phẩm', 'percentage', 10.00, 100000, 50000, 'all_products', 100, NOW(), DATE_ADD(NOW(), INTERVAL 30 DAY), 1),
('FREESHIP', 'Miễn phí vận chuyển', 'Voucher miễn phí vận chuyển cho đơn hàng trên 200k', 'fixed', 30000.00, 200000, 30000, 'all_products', 50, NOW(), DATE_ADD(NOW(), INTERVAL 60 DAY), 1),
('NEWCUSTOMER', 'Khách hàng mới', 'Giảm 15% cho khách hàng mới', 'percentage', 15.00, 50000, 100000, 'all_products', 200, NOW(), DATE_ADD(NOW(), INTERVAL 90 DAY), 1); 
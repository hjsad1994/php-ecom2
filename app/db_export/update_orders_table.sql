-- Cập nhật bảng orders để liên kết với user
-- Thêm cột user_id để track user nào đặt đơn hàng

-- Thêm cột user_id vào bảng orders
ALTER TABLE `orders` 
ADD COLUMN `user_id` int(11) DEFAULT NULL AFTER `id`,
ADD INDEX `user_id` (`user_id`);

-- Thêm foreign key constraint
ALTER TABLE `orders`
ADD CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `account` (`id`) ON DELETE SET NULL;

-- Cập nhật comment cho rõ ràng
ALTER TABLE `orders` COMMENT = 'Bảng đơn hàng với thông tin user đặt hàng'; 
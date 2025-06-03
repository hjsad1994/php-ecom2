-- Add profile columns to account table
ALTER TABLE `account` 
ADD COLUMN `fullname` VARCHAR(255) NULL AFTER `password`,
ADD COLUMN `email` VARCHAR(255) NULL AFTER `fullname`,
ADD COLUMN `phone` VARCHAR(20) NULL AFTER `email`, 
ADD COLUMN `address` TEXT NULL AFTER `phone`;

-- Update existing accounts with default fullname if null
UPDATE `account` SET `fullname` = `username` WHERE `fullname` IS NULL; 
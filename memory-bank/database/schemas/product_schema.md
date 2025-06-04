# Product Schema Documentation

## Schema Information
- **Database:** my_store
- **Table:** product
- **Last Updated:** 2024-12-19

## Table Structure
| Field | Type | Null | Key | Default | Extra |
|-------|------|------|-----|---------|-------|
| id | int(11) | NO | PRI | null | auto_increment |
| name | varchar(100) | NO | | null | |
| description | text | YES | | null | |
| price | decimal(10,2) | NO | | null | |
| image | varchar(255) | YES | | null | |
| category_id | int(11) | YES | MUL | null | |

## Sample Data
```sql
-- Top 5 sản phẩm mới nhất
SELECT p.id, p.name, p.description, p.price, p.image, p.category_id, c.name as category_name 
FROM product p 
LEFT JOIN category c ON p.category_id = c.id 
ORDER BY p.id DESC LIMIT 5;
```

| ID | Name | Description | Price | Category |
|----|------|-------------|-------|----------|
| 5 | test122 | 111 | 10,000.00 | điện thoại |
| 4 | test | test | 10,000.00 | xe dien |
| 3 | dien thoai | 123 | 20,000.00 | điện thoại |
| 2 | xe điện | xe | 20,000.00 | xe dien |

## Common Queries

### Lấy sản phẩm mới nhất
```sql
SELECT p.*, c.name as category_name 
FROM product p 
LEFT JOIN category c ON p.category_id = c.id 
ORDER BY p.id DESC LIMIT 10;
```

### Lấy sản phẩm theo danh mục
```sql
SELECT p.*, c.name as category_name 
FROM product p 
LEFT JOIN category c ON p.category_id = c.id 
WHERE p.category_id = ? 
ORDER BY p.name;
```

### Tìm kiếm sản phẩm theo tên
```sql
SELECT p.*, c.name as category_name 
FROM product p 
LEFT JOIN category c ON p.category_id = c.id 
WHERE p.name LIKE CONCAT('%', ?, '%') 
ORDER BY p.name;
```

### Thống kê sản phẩm theo giá
```sql
SELECT 
    COUNT(*) as total_products,
    MIN(price) as min_price,
    MAX(price) as max_price,
    AVG(price) as avg_price
FROM product;
```

## Relationships
- **category_id** → **category.id** (Many-to-One)
  - Một sản phẩm thuộc về một danh mục
  - Một danh mục có thể có nhiều sản phẩm

## Usage Notes
- **ID Field:** Auto-increment primary key, sử dụng để xác định sản phẩm mới nhất
- **Name Field:** Required, tối đa 100 ký tự
- **Description:** Optional, có thể chứa HTML formatting
- **Price:** Required, định dạng decimal(10,2) cho tiền tệ
- **Image:** Optional, lưu trữ tên file ảnh trong thư mục uploads/products/
- **Category_ID:** Optional foreign key, nên sử dụng LEFT JOIN khi query

## File Storage
- **Images:** Lưu tại `public/uploads/products/`
- **Format:** Tên file được encode với timestamp và ID unique

## Business Rules
- Sản phẩm có thể tồn tại mà không có danh mục (category_id = null)
- Giá sản phẩm phải > 0
- Tên sản phẩm không được trùng lặp (nên thêm unique constraint)
- Ảnh sản phẩm nên được validate format và size

---
*Auto-generated từ MCP MariaDB integration rule* 
<?php
class ProductModel
{
    private $conn;
    private $table_name = "product";
    
    public function __construct($db)
    {
        $this->conn = $db;
    }
    
    public function getProducts()
    {
        $query = "SELECT p.id, p.name, p.description, p.price, c.name as category_name
                  FROM " . $this->table_name . " p
                  LEFT JOIN category c ON p.category_id = c.id";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_OBJ);
        return $result;
    }
    
    public function getProductById($id)
    {
        $query = "SELECT * FROM " . $this->table_name . " WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_OBJ);
        return $result;
    }
    
    public function addProduct($name, $description, $price, $category_id)
    {
        $errors = [];
        if (empty($name)) {
            $errors['name'] = 'Tên sản phẩm không được để trống';
        }
        if (empty($description)) {
            $errors['description'] = 'Mô tả không được để trống';
        }
        if (!is_numeric($price) || $price < 0) {
            $errors['price'] = 'Giá sản phẩm không hợp lệ';
        }
        if (count($errors) > 0) {
            return $errors;
        }
        
        $query = "INSERT INTO " . $this->table_name . " (name, description, price, category_id) 
                  VALUES (:name, :description, :price, :category_id)";
        $stmt = $this->conn->prepare($query);
        $name = htmlspecialchars(strip_tags($name));
        $description = htmlspecialchars(strip_tags($description));
        $price = htmlspecialchars(strip_tags($price));
        
        // Check if category_id is null before applying string functions
        if ($category_id !== null && $category_id !== '') {
            $category_id = htmlspecialchars(strip_tags($category_id));
        } else {
            $category_id = null; // Make sure it's explicitly null for database
        }
        
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':description', $description);
        $stmt->bindParam(':price', $price);
        $stmt->bindParam(':category_id', $category_id, PDO::PARAM_INT); // Use PDO::PARAM_INT to handle NULL properly
        
        if ($stmt->execute()) {
            return true;
        }
        return false;
    }
    
    public function updateProduct($id, $name, $description, $price, $category_id)
    {
        $query = "UPDATE " . $this->table_name . " 
                  SET name=:name, description=:description, price=:price, category_id=:category_id 
                  WHERE id=:id";
        
        $stmt = $this->conn->prepare($query);
        $name = htmlspecialchars(strip_tags($name));
        $description = htmlspecialchars(strip_tags($description));
        $price = htmlspecialchars(strip_tags($price));
        
        if ($category_id !== null) {
            $category_id = htmlspecialchars(strip_tags($category_id));
        }
        
        $stmt->bindParam(':id', $id);
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':description', $description);
        $stmt->bindParam(':price', $price);
        $stmt->bindParam(':category_id', $category_id, PDO::PARAM_INT);
        
        if ($stmt->execute()) {
            return true;
        }
        return false;
    }
    
    public function deleteProduct($id)
    {
        $currentProduct = $this->getProductById($id);
        
        $query = "DELETE FROM " . $this->table_name . " WHERE id=:id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        
        if ($stmt->execute()) {
            if ($currentProduct && !empty($currentProduct->image) && file_exists('public/uploads/products/' . $currentProduct->image)) {
                unlink('public/uploads/products/' . $currentProduct->image);
            }
            return true;
        }
        return false;
    }
    
    /**
     * Đếm tổng số sản phẩm
     */
    public function getProductCount()
    {
        $query = "SELECT COUNT(*) as count FROM " . $this->table_name;
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['count'];
    }
    
    /**
     * Lấy tất cả sản phẩm với thông tin category
     */
    public function getAllWithCategory()
    {
        return $this->getProducts(); // Đã có sẵn logic này
    }
    
    /**
     * Lấy sản phẩm theo category
     */
    public function getProductsByCategory($categoryId)
    {
        $query = "SELECT p.id, p.name, p.description, p.price, p.image, c.name as category_name
                  FROM " . $this->table_name . " p
                  LEFT JOIN category c ON p.category_id = c.id
                  WHERE p.category_id = :category_id
                  ORDER BY p.id DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':category_id', $categoryId);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);  // Trả về FETCH_ASSOC để dùng trong view
    }
    
    /**
     * Tìm kiếm sản phẩm với khả năng lọc theo danh mục
     */
    public function searchProducts($keyword, $categoryId = null)
    {
        $sql = "SELECT p.*, c.name as category_name 
                FROM product p 
                LEFT JOIN category c ON p.category_id = c.id 
                WHERE p.name LIKE :keyword OR p.description LIKE :keyword";
        
        $params = [':keyword' => "%$keyword%"];
        
        if ($categoryId) {
            $sql .= " AND p.category_id = :category_id";
            $params[':category_id'] = $categoryId;
        }
        
        $sql .= " ORDER BY p.id DESC";
        
        $stmt = $this->conn->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }
    
    /**
     * Methods for admin functionality
     */
    public function save($name, $description, $price, $image, $categoryId)
    {
        return $this->addProduct($name, $description, $price, $categoryId, $image);
    }
    
    public function update($id, $name, $description, $price, $image, $categoryId)
    {
        return $this->updateProduct($id, $name, $description, $price, $categoryId, $image);
    }
    
    public function delete($id)
    {
        return $this->deleteProduct($id);
    }
    
    public function getById($id)
    {
        return $this->getProductById($id);
    }
    
    /**
     * Lấy sản phẩm nổi bật cho trang chủ
     */
    public function getFeaturedProducts($limit = 8)
    {
        $query = "SELECT p.id, p.name, p.description, p.price, p.image, c.name as category_name, p.category_id
                  FROM " . $this->table_name . " p
                  LEFT JOIN category c ON p.category_id = c.id
                  ORDER BY p.id DESC
                  LIMIT :limit";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }
    
    /**
     * Lấy categories của một product
     */
    public function getProductCategories($productIds)
    {
        if (empty($productIds)) {
            return [];
        }
        
        $placeholders = str_repeat('?,', count($productIds) - 1) . '?';
        $query = "SELECT DISTINCT p.category_id 
                  FROM " . $this->table_name . " p 
                  WHERE p.id IN ($placeholders) AND p.category_id IS NOT NULL";
        
        $stmt = $this->conn->prepare($query);
        $stmt->execute($productIds);
        return $stmt->fetchAll(PDO::FETCH_COLUMN);
    }

    /**
     * Lấy thống kê sản phẩm theo danh mục
     */
    public function getProductStatsByCategory()
    {
        $sql = "SELECT 
                    c.id, 
                    c.name as category_name,
                    COUNT(p.id) as product_count,
                    AVG(p.price) as avg_price,
                    MIN(p.price) as min_price,
                    MAX(p.price) as max_price,
                    SUM(CASE WHEN p.image IS NOT NULL AND p.image != '' THEN 1 ELSE 0 END) as products_with_image
                FROM category c 
                LEFT JOIN product p ON c.id = p.category_id 
                GROUP BY c.id, c.name
                ORDER BY product_count DESC";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    /**
     * Lấy sản phẩm mới nhất
     */
    public function getLatestProducts($limit = 5)
    {
        $sql = "SELECT p.*, c.name as category_name 
                FROM product p 
                LEFT JOIN category c ON p.category_id = c.id 
                ORDER BY p.id DESC 
                LIMIT :limit";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    /**
     * Lấy sản phẩm có giá cao nhất và thấp nhất
     */
    public function getPriceRange()
    {
        $sql = "SELECT 
                    MIN(price) as min_price,
                    MAX(price) as max_price,
                    AVG(price) as avg_price,
                    COUNT(*) as total_products,
                    SUM(CASE WHEN image IS NOT NULL AND image != '' THEN 1 ELSE 0 END) as products_with_image,
                    SUM(CASE WHEN image IS NULL OR image = '' THEN 1 ELSE 0 END) as products_without_image
                FROM product";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_OBJ);
    }
}
?>
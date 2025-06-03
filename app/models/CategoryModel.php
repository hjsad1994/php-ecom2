<?php
class CategoryModel
{
    private $conn;
    private $table_name = "category";
    
    public function __construct($db)
    {
        $this->conn = $db;
    }
    
    public function getCategories()
    {
        $query = "SELECT id, name, description FROM " . $this->table_name;
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_OBJ);
        return $result;
    }
    
    public function getCategoryById($id)
    {
        $query = "SELECT id, name, description FROM " . $this->table_name . " WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_OBJ);
        return $result;
    }
    
    public function addCategory($name, $description)
    {
        $errors = [];
        if (empty($name)) {
            $errors['name'] = 'Tên danh mục không được để trống';
        }
        
        if (count($errors) > 0) {
            return $errors;
        }
        
        $query = "INSERT INTO " . $this->table_name . " (name, description) VALUES (:name, :description)";
        $stmt = $this->conn->prepare($query);
        $name = htmlspecialchars(strip_tags($name));
        $description = htmlspecialchars(strip_tags($description));
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':description', $description);
        
        if ($stmt->execute()) {
            return true;
        }
        return false;
    }
    
    public function updateCategory($id, $name, $description)
    {
        $query = "UPDATE " . $this->table_name . " SET name=:name, description=:description WHERE id=:id";
        $stmt = $this->conn->prepare($query);
        $name = htmlspecialchars(strip_tags($name));
        $description = htmlspecialchars(strip_tags($description));
        $stmt->bindParam(':id', $id);
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':description', $description);
        
        if ($stmt->execute()) {
            return true;
        }
        return false;
    }
    
    public function deleteCategory($id)
    {
        try {
            // First, begin a transaction
            $this->conn->beginTransaction();
            
            // Update all products in this category to have NULL category_id
            $update_query = "UPDATE product SET category_id = NULL WHERE category_id = :category_id";
            $update_stmt = $this->conn->prepare($update_query);
            $update_stmt->bindParam(':category_id', $id);
            $update_stmt->execute();
            
            // Then delete the category
            $delete_query = "DELETE FROM " . $this->table_name . " WHERE id = :id";
            $delete_stmt = $this->conn->prepare($delete_query);
            $delete_stmt->bindParam(':id', $id);
            $delete_stmt->execute();
            
            // If everything worked, commit the transaction
            $this->conn->commit();
            return true;
        } catch (PDOException $e) {
            // If anything went wrong, roll back the transaction
            $this->conn->rollBack();
            return false;
        }
    }
    
    /**
     * Đếm tổng số danh mục
     */
    public function getCategoryCount()
    {
        $query = "SELECT COUNT(*) as count FROM " . $this->table_name;
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['count'];
    }
    
    /**
     * Methods for admin functionality
     */
    public function getAll()
    {
        return $this->getCategories();
    }
    
    public function save($name, $description)
    {
        return $this->addCategory($name, $description);
    }
    
    /**
     * Lấy danh mục theo ID
     */
    public function getById($categoryId) {
        $sql = "SELECT * FROM category WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':id' => $categoryId]);
        
        return $stmt->fetch(PDO::FETCH_OBJ);
    }
    
    /**
     * Cập nhật danh mục
     */
    public function update($categoryId, $name, $description) {
        $sql = "UPDATE category SET name = :name, description = :description WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        
        // Preserve HTML in description, only sanitize name
        $name = htmlspecialchars(strip_tags($name));
        $description = $description; // Keep HTML tags intact
        
        return $stmt->execute([
            ':name' => $name,
            ':description' => $description,
            ':id' => $categoryId
        ]);
    }
    
    /**
     * Lấy tất cả danh mục với số lượng sản phẩm
     */
    public function getAllWithProductCount() {
        $query = "SELECT c.*, COUNT(p.id) as product_count
                  FROM " . $this->table_name . " c
                  LEFT JOIN product p ON c.id = p.category_id
                  GROUP BY c.id, c.name, c.description
                  ORDER BY c.id DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }
}
?>
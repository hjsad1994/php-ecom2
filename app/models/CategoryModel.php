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
}
?>
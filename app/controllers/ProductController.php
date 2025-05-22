<?php
require_once('app/config/database.php');
require_once('app/models/ProductModel.php');
require_once('app/models/CategoryModel.php');

class ProductController
{
    private $productModel;
    private $db;
    private $uploadDir;
    
    public function __construct()
    {
        $this->db = (new Database())->getConnection();
        $this->productModel = new ProductModel($this->db);
        
        // Use absolute path
        $this->uploadDir = $_SERVER['DOCUMENT_ROOT'] . '/webbanhang/public/uploads/';
        
        // Create the upload directory if it doesn't exist
        if (!file_exists($this->uploadDir)) {
            mkdir($this->uploadDir, 0777, true);
        }
    }
    
    public function index()
    {
        $products = $this->productModel->getProducts();
        include 'app/views/product/list.php';
    }
    
    public function show($id)
    {
        $product = $this->productModel->getProductById($id);
        if ($product) {
            include 'app/views/product/show.php';
        } else {
            echo "Không thấy sản phẩm.";
        }
    }
    
    public function add()
    {
        $categories = (new CategoryModel($this->db))->getCategories();
        include_once 'app/views/product/add.php';
    }
    
    public function save()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $name = $_POST['name'] ?? '';
            $description = $_POST['description'] ?? '';
            $price = $_POST['price'] ?? '';
            $category_id = $_POST['category_id'] ?? null;
            
            // Handle image upload
            $image_name = null;
            $errors = [];
            if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
                $allowed_types = ['image/jpeg', 'image/png', 'image/gif'];
                if (in_array($_FILES['image']['type'], $allowed_types)) {
                    // Create upload directory if it doesn't exist
                    if (!file_exists($this->uploadDir)) {
                        mkdir($this->uploadDir, 0777, true);
                    }
                    
                    $image_name = time() . '_' . $_FILES['image']['name'];
                    $upload_path = $this->uploadDir . $image_name;
                    
                    // Use the debug function
                    $debug_result = $this->debugUpload($_FILES['image'], $upload_path);
                    
                    if (!isset($debug_result['success'])) {
                        $errors['image'] = 'Không thể lưu file ảnh. Vui lòng thử lại!';
                    }
                } else {
                    $errors['image'] = 'Chỉ chấp nhận file ảnh (JPG, PNG, GIF)';
                }
            }
            
            $result = $this->productModel->addProduct($name, $description, $price, $category_id, $image_name);
            if (is_array($result)) {
                $errors = array_merge($errors, $result);
                $categories = (new CategoryModel($this->db))->getCategories();
                include 'app/views/product/add.php';
            } else {
                header('Location: /webbanhang/Product');
            }
        }
    }
    
    public function edit($id)
    {
        $product = $this->productModel->getProductById($id);
        $categories = (new CategoryModel($this->db))->getCategories();
        if ($product) {
            include 'app/views/product/edit.php';
        } else {
            echo "Không thấy sản phẩm.";
        }
    }
    
    public function update()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['id'];
            $name = $_POST['name'];
            $description = $_POST['description'];
            $price = $_POST['price'];
            
            // Convert empty string to NULL for category_id
            $category_id = !empty($_POST['category_id']) ? $_POST['category_id'] : null;
            
            // Handle image upload
            $image_name = null;
            if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
                $allowed_types = ['image/jpeg', 'image/png', 'image/gif'];
                if (in_array($_FILES['image']['type'], $allowed_types)) {
                    // Get current product to check if it has an image to delete
                    $currentProduct = $this->productModel->getProductById($id);
                    if ($currentProduct && !empty($currentProduct->image)) {
                        $old_image_path = $this->uploadDir . $currentProduct->image;
                        if (file_exists($old_image_path)) {
                            unlink($old_image_path);
                        }
                    }
                    
                    $image_name = time() . '_' . $_FILES['image']['name'];
                    $upload_path = $this->uploadDir . $image_name;
                    if (!move_uploaded_file($_FILES['image']['tmp_name'], $upload_path)) {
                        echo "Không thể lưu file ảnh. Vui lòng thử lại!";
                        return;
                    }
                } else {
                    echo "Chỉ chấp nhận file ảnh (JPG, PNG, GIF)";
                    return;
                }
            }
            
            $edit = $this->productModel->updateProduct($id, $name, $description, $price, $category_id, $image_name);
            if ($edit) {
                header('Location: /webbanhang/Product');
            } else {
                echo "Đã xảy ra lỗi khi lưu sản phẩm.";
            }
        }
    }
    
    public function delete($id)
    {
        if ($this->productModel->deleteProduct($id)) {
            header('Location: /webbanhang/Product');
        } else {
            echo "Đã xảy ra lỗi khi xóa sản phẩm.";
        }
    }

    private function debugUpload($file, $target_path)
    {
        $debug_info = [
            'file_info' => $file,
            'target_path' => $target_path,
            'directory_exists' => file_exists(dirname($target_path)),
            'directory_writable' => is_writable(dirname($target_path)),
            'error_message' => ''
        ];
        
        if (!move_uploaded_file($file['tmp_name'], $target_path)) {
            $debug_info['error_message'] = error_get_last()['message'] ?? 'Unknown error';
        } else {
            $debug_info['success'] = true;
            $debug_info['file_exists'] = file_exists($target_path);
            $debug_info['file_size'] = filesize($target_path);
        }
        
        // Log the debug info
        file_put_contents('upload_debug.log', print_r($debug_info, true), FILE_APPEND);
        
        return $debug_info;
    }
}
?>
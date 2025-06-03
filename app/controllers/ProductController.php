<?php
require_once('app/config/database.php');
require_once('app/models/ProductModel.php');
require_once('app/models/CategoryModel.php');
require_once('app/helpers/AuthHelper.php');

class ProductController
{
    private $productModel;
    private $db;
    private $uploadDir;
    
    public function __construct()
    {
        // Start session if not already started
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        
        $this->db = (new Database())->getConnection();
        $this->productModel = new ProductModel($this->db);
        
        // Use absolute path for products folder
        $this->uploadDir = $_SERVER['DOCUMENT_ROOT'] . '/webbanhang/public/uploads/products/';
        
        // Create the upload directory if it doesn't exist
        if (!file_exists($this->uploadDir)) {
            mkdir($this->uploadDir, 0777, true);
        }
    }
    
    // PUBLIC - Xem danh sách sản phẩm (cho mọi người)
    public function index()
    {
        $products = $this->productModel->getProducts();
        include 'app/views/product/list.php';
    }
    
    // USER - Xem danh sách sản phẩm (user interface)
    public function userIndex()
    {
        $products = $this->productModel->getProducts();
        include 'app/views/product/list.php';
    }
    
    // PUBLIC - Xem chi tiết sản phẩm (cho mọi người)
    public function show($id)
    {
        // Get product with category information
        $query = "SELECT p.*, c.name as category_name 
                  FROM product p 
                  LEFT JOIN category c ON p.category_id = c.id 
                  WHERE p.id = :id";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        $product = $stmt->fetch(PDO::FETCH_OBJ);
        
        if ($product) {
            include 'app/views/product/show.php';
        } else {
            echo "Không thấy sản phẩm.";
        }
    }
    
    // ADMIN ONLY - Thêm sản phẩm (DEPRECATED - use AdminController)
    public function add()
    {
        AuthHelper::requireAdmin('/webbanhang/account/login');
        
        // Redirect to proper admin interface
        header('Location: /webbanhang/admin/products/create');
        exit;
    }
    
    // ADMIN ONLY - Lưu sản phẩm mới
    public function save()
    {
        AuthHelper::requireAdmin('/webbanhang/account/login');
        
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
                    
                    // Upload file directly
                    if (!move_uploaded_file($_FILES['image']['tmp_name'], $upload_path)) {
                        $errors['image'] = 'Không thể lưu file ảnh. Vui lòng thử lại!';
                        $image_name = null; // Reset image name on error
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
                // Check for image upload errors
                if (count($errors) > 0) {
                    $categories = (new CategoryModel($this->db))->getCategories();
                    include 'app/views/product/add.php';
                    return;
                }
                
                header('Location: /webbanhang/admin/products');
                exit;
            }
        }
    }
    
    // ADMIN ONLY - Form sửa sản phẩm (DEPRECATED - use AdminController)
    public function edit($id)
    {
        AuthHelper::requireAdmin('/webbanhang/account/login');
        
        // Redirect to proper admin interface
        header('Location: /webbanhang/admin/products/edit/' . $id);
        exit;
    }
    
    // ADMIN ONLY - Cập nhật sản phẩm
    public function update()
    {
        AuthHelper::requireAdmin('/webbanhang/account/login');
        
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
                header('Location: /webbanhang/admin/products');
                exit;
            } else {
                echo "Đã xảy ra lỗi khi lưu sản phẩm.";
            }
        }
    }
    
    // ADMIN ONLY - Xóa sản phẩm
    public function delete($id)
    {
        AuthHelper::requireAdmin('/webbanhang/account/login');
        
        if ($this->productModel->deleteProduct($id)) {
            header('Location: /webbanhang/admin/products');
            exit;
        } else {
            echo "Đã xảy ra lỗi khi xóa sản phẩm.";
        }
    }

    // ========== USER SHOPPING FUNCTIONS ==========
    
    // USER - Thêm vào giỏ hàng
    public function addToCart($id)
    {
        $product = $this->productModel->getProductById($id);
        if (!$product) {
            echo "Không tìm thấy sản phẩm.";
            return;
        }

        if (!isset($_SESSION['cart'])) {
            $_SESSION['cart'] = [];
        }

        if (isset($_SESSION['cart'][$id])) {
            $_SESSION['cart'][$id]['quantity']++;
        } else {
            $_SESSION['cart'][$id] = [
                'name' => $product->name,
                'price' => $product->price,
                'quantity' => 1,
                'image' => $product->image
            ];
        }

        // Check if it's a buy now action
        if (isset($_GET['buy_now']) && $_GET['buy_now'] == '1') {
            header('Location: /webbanhang/checkout');
        } else {
            header('Location: /webbanhang/user/cart');
        }
        exit;
    }

    // USER - Xóa khỏi giỏ hàng
    public function removeFromCart($id)
    {
        if (isset($_SESSION['cart'][$id])) {
            unset($_SESSION['cart'][$id]);
        }
        header('Location: /webbanhang/user/cart');
        exit;
    }

    public function updateCartQuantity()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $id = $_POST['product_id'];
            $quantity = (int)$_POST['quantity'];
            
            if (isset($_SESSION['cart'][$id])) {
                if ($quantity > 0) {
                    $_SESSION['cart'][$id]['quantity'] = $quantity;
                } else {
                    unset($_SESSION['cart'][$id]);
                }
            }
        }
        header('Location: /webbanhang/user/cart');
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

    /**
     * API endpoint to get products list for order form
     */
    public function apiList() {
        header('Content-Type: application/json');
        
        try {
            $products = $this->productModel->getProducts();
            
            // Convert to simple array for JSON
            $productsArray = [];
            foreach ($products as $product) {
                $productsArray[] = [
                    'id' => $product->id,
                    'name' => $product->name,
                    'price' => (float)$product->price,
                    'image' => $product->image
                ];
            }
            
            echo json_encode($productsArray);
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(['error' => 'Unable to load products']);
        }
    }
}
?>
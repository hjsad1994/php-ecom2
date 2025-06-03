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
        
        // Use absolute path
        $this->uploadDir = $_SERVER['DOCUMENT_ROOT'] . '/webbanhang/public/uploads/';
        
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
    
    // PUBLIC - Xem chi tiết sản phẩm (cho mọi người)
    public function show($id)
    {
        $product = $this->productModel->getProductById($id);
        if ($product) {
            include 'app/views/product/show.php';
        } else {
            echo "Không thấy sản phẩm.";
        }
    }
    
    // ADMIN ONLY - Thêm sản phẩm
    public function add()
    {
        AuthHelper::requireAdmin('/webbanhang/account/login');
        
        $categories = (new CategoryModel($this->db))->getCategories();
        include_once 'app/views/product/add.php';
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
                header('Location: /webbanhang/admin/products');
                exit;
            }
        }
    }
    
    // ADMIN ONLY - Form sửa sản phẩm
    public function edit($id)
    {
        AuthHelper::requireAdmin('/webbanhang/account/login');
        
        $product = $this->productModel->getProductById($id);
        $categories = (new CategoryModel($this->db))->getCategories();
        if ($product) {
            include 'app/views/product/edit.php';
        } else {
            echo "Không thấy sản phẩm.";
        }
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

        header('Location: /webbanhang/product/cart');
        exit;
    }

    // USER - Xóa khỏi giỏ hàng
    public function removeFromCart($id)
    {
        if (isset($_SESSION['cart'][$id])) {
            unset($_SESSION['cart'][$id]);
        }
        header('Location: /webbanhang/product/cart');
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
        header('Location: /webbanhang/Product/cart');
    }

    public function cart()
    {
        $cart = isset($_SESSION['cart']) ? $_SESSION['cart'] : [];
        $total = 0;
        foreach ($cart as $item) {
            $total += $item['price'] * $item['quantity'];
        }
        include 'app/views/product/cart.php';
    }

    public function checkout()
    {
        if (!isset($_SESSION['cart']) || empty($_SESSION['cart'])) {
            header('Location: /webbanhang/Product/cart');
            return;
        }
        
        $cart = $_SESSION['cart'];
        $total = 0;
        foreach ($cart as $item) {
            $total += $item['price'] * $item['quantity'];
        }
        
        // Apply voucher discount if exists
        $discount = 0;
        if (isset($_SESSION['applied_voucher'])) {
            $discount = $_SESSION['applied_voucher']['discount'];
        }
        $finalTotal = $total - $discount;
        
        include 'app/views/product/checkout.php';
    }

    public function processCheckout()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $name = trim($_POST['name'] ?? '');
            $phone = trim($_POST['phone'] ?? '');
            $address = trim($_POST['address'] ?? '');
            
            $errors = [];
            
            // Validation
            if (empty($name)) {
                $errors[] = 'Tên khách hàng không được để trống';
            }
            if (empty($phone)) {
                $errors[] = 'Số điện thoại không được để trống';
            }
            if (empty($address)) {
                $errors[] = 'Địa chỉ không được để trống';
            }

            // Check if cart is empty
            if (!isset($_SESSION['cart']) || empty($_SESSION['cart'])) {
                $errors[] = 'Giỏ hàng trống';
            }

            if (!empty($errors)) {
                $cart = $_SESSION['cart'] ?? [];
                $total = 0;
                foreach ($cart as $item) {
                    $total += $item['price'] * $item['quantity'];
                }
                
                // Apply voucher discount
                $discount = 0;
                if (isset($_SESSION['applied_voucher'])) {
                    $discount = $_SESSION['applied_voucher']['discount'];
                }
                $finalTotal = $total - $discount;
                
                include 'app/views/product/checkout.php';
                return;
            }

            // Calculate total amount INCLUDING voucher discount
            $total_amount = 0;
            foreach ($_SESSION['cart'] as $item) {
                $total_amount += $item['price'] * $item['quantity'];
            }
            
            // Apply voucher discount to total
            $voucher_discount = 0;
            $voucher_id = null;
            $voucher_code = null;
            if (isset($_SESSION['applied_voucher'])) {
                $voucher_discount = $_SESSION['applied_voucher']['discount'];
                $voucher_id = $_SESSION['applied_voucher']['id'];
                $voucher_code = $_SESSION['applied_voucher']['code'];
                $total_amount -= $voucher_discount;
            }

            // Begin transaction
            $this->db->beginTransaction();
            
            try {
                // Insert order with voucher information
                $query = "INSERT INTO orders (name, phone, address, total_amount, voucher_id, voucher_code, voucher_discount, order_status, created_at) 
                          VALUES (:name, :phone, :address, :total_amount, :voucher_id, :voucher_code, :voucher_discount, 'paid', NOW())";
                $stmt = $this->db->prepare($query);
                $stmt->bindParam(':name', $name);
                $stmt->bindParam(':phone', $phone);
                $stmt->bindParam(':address', $address);
                $stmt->bindParam(':total_amount', $total_amount);
                $stmt->bindParam(':voucher_id', $voucher_id);
                $stmt->bindParam(':voucher_code', $voucher_code);
                $stmt->bindParam(':voucher_discount', $voucher_discount);
                $stmt->execute();
                
                $order_id = $this->db->lastInsertId();

                // Insert order details (sử dụng đúng tên bảng order_details)
                foreach ($_SESSION['cart'] as $product_id => $item) {
                    $query = "INSERT INTO order_details (order_id, product_id, quantity, price) 
                             VALUES (:order_id, :product_id, :quantity, :price)";
                    $stmt = $this->db->prepare($query);
                    $stmt->bindParam(':order_id', $order_id);
                    $stmt->bindParam(':product_id', $product_id);
                    $stmt->bindParam(':quantity', $item['quantity']);
                    $stmt->bindParam(':price', $item['price']);
                    $stmt->execute();
                }

                // Update voucher usage count if voucher was used
                if ($voucher_id) {
                    require_once('app/models/VoucherModel.php');
                    $voucherModel = new VoucherModel($this->db);
                    $voucherModel->incrementUsage($voucher_id);
                }

                // Clear cart and voucher after successful order
                unset($_SESSION['cart']);
                unset($_SESSION['applied_voucher']);

                // Commit transaction
                $this->db->commit();

                // Redirect to order confirmation
                $_SESSION['last_order_id'] = $order_id;
                header('Location: /webbanhang/Product/orderConfirmation');
            } catch (Exception $e) {
                // Rollback transaction on error
                $this->db->rollBack();
                echo "Đã xảy ra lỗi khi xử lý đơn hàng: " . $e->getMessage();
            }
        }
    }

    public function orderConfirmation()
    {
        $order_id = $_SESSION['last_order_id'] ?? null;
        if ($order_id) {
            try {
                // Get order details - sửa từ products thành product
                $query = "SELECT o.*, od.product_id, od.quantity, od.price, p.name AS product_name
                          FROM orders o
                          LEFT JOIN order_details od ON o.id = od.order_id
                          LEFT JOIN product p ON od.product_id = p.id
                          WHERE o.id = :order_id";
                $stmt = $this->db->prepare($query);
                $stmt->bindParam(':order_id', $order_id);
                $stmt->execute();
                $orderDetails = $stmt->fetchAll(PDO::FETCH_OBJ);
                
                if (empty($orderDetails)) {
                    echo "Không tìm thấy đơn hàng.";
                    return;
                }
                
                include 'app/views/product/orderConfirmation.php';
            } catch (Exception $e) {
                echo "Lỗi khi tải thông tin đơn hàng: " . $e->getMessage();
            }
        } else {
            header('Location: /webbanhang/Product');
        }
    }

    public function orders()
    {
        // Display all orders with their status
        $query = "SELECT o.*, COUNT(od.id) as item_count
                 FROM orders o 
                 LEFT JOIN order_details od ON o.id = od.order_id
                 GROUP BY o.id
                 ORDER BY o.created_at DESC";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        $orders = $stmt->fetchAll(PDO::FETCH_OBJ);
        
        include 'app/views/product/orders.php';
    }

    public function orderDetail($id)
    {
        // Get specific order details - sửa từ products thành product
        $query = "SELECT o.*, od.product_id, od.quantity, od.price, p.name as product_name
                 FROM orders o 
                 LEFT JOIN order_details od ON o.id = od.order_id
                 LEFT JOIN product p ON od.product_id = p.id
                 WHERE o.id = :order_id";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':order_id', $id);
        $stmt->execute();
        $orderDetails = $stmt->fetchAll(PDO::FETCH_OBJ);
        
        if (!empty($orderDetails)) {
            include 'app/views/product/orderDetail.php';
        } else {
            echo "Không tìm thấy đơn hàng.";
        }
    }

    public function updateOrderStatus()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $order_id = $_POST['order_id'];
            $new_status = $_POST['order_status'];
            
            $query = "UPDATE orders SET order_status = :order_status WHERE id = :order_id";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':order_status', $new_status);
            $stmt->bindParam(':order_id', $order_id);
            
            if ($stmt->execute()) {
                header('Location: /webbanhang/Product/orders');
            } else {
                echo "Không thể cập nhật trạng thái đơn hàng.";
            }
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
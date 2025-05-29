<?php
// filepath: app/controllers/VoucherController.php
require_once('app/config/database.php');
require_once('app/models/VoucherModel.php');
require_once('app/models/ProductModel.php');

class VoucherController
{
    private $voucherModel;
    private $productModel;
    private $db;
    
    public function __construct()
    {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        
        $this->db = (new Database())->getConnection();
        $this->voucherModel = new VoucherModel($this->db);
        $this->productModel = new ProductModel($this->db);
    }
    
    public function index()
    {
        $vouchers = $this->voucherModel->getVouchers();
        include 'app/views/voucher/list.php';
    }
    
    public function add()
    {
        $products = $this->productModel->getProducts();
        // Thêm categories
        require_once('app/models/CategoryModel.php');
        $categoryModel = new CategoryModel($this->db);
        $categories = $categoryModel->getCategories();
        include 'app/views/voucher/add.php';
    }
    
    public function save()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $data = [
                'code' => strtoupper(trim($_POST['code'] ?? '')),
                'name' => trim($_POST['name'] ?? ''),
                'description' => trim($_POST['description'] ?? ''),
                'discount_type' => $_POST['discount_type'] ?? 'percentage',
                'discount_value' => floatval($_POST['discount_value'] ?? 0),
                'min_order_amount' => floatval($_POST['min_order_amount'] ?? 0),
                'max_discount_amount' => !empty($_POST['max_discount_amount']) ? floatval($_POST['max_discount_amount']) : null,
                'applies_to' => $_POST['applies_to'] ?? 'all_products',
                'product_ids' => null,
                'category_ids' => null,
                'usage_limit' => !empty($_POST['usage_limit']) ? intval($_POST['usage_limit']) : null,
                'start_date' => $_POST['start_date'] ?? '',
                'end_date' => $_POST['end_date'] ?? '',
                'is_active' => isset($_POST['is_active']) ? 1 : 0
            ];
            
            // Handle product selection for specific products
            if ($data['applies_to'] == 'specific_products' && !empty($_POST['product_ids'])) {
                $data['product_ids'] = json_encode($_POST['product_ids']);
            }
            
            // Handle category selection for specific categories
            if ($data['applies_to'] == 'specific_categories' && !empty($_POST['category_ids'])) {
                $data['category_ids'] = json_encode($_POST['category_ids']);
            }
            
            $result = $this->voucherModel->addVoucher($data);
            if (is_array($result)) {
                $errors = $result;
                $products = $this->productModel->getProducts();
                // Thêm categories khi có lỗi
                require_once('app/models/CategoryModel.php');
                $categoryModel = new CategoryModel($this->db);
                $categories = $categoryModel->getCategories();
                include 'app/views/voucher/add.php';
            } else {
                header('Location: /webbanhang/Voucher');
                exit;
            }
        }
    }
    
    public function edit($id)
    {
        $voucher = $this->voucherModel->getVoucherById($id);
        $products = $this->productModel->getProducts();
        // Thêm categories
        require_once('app/models/CategoryModel.php');
        $categoryModel = new CategoryModel($this->db);
        $categories = $categoryModel->getCategories();
        
        if ($voucher) {
            include 'app/views/voucher/edit.php';
        } else {
            echo "Không tìm thấy voucher.";
        }
    }
    
    public function update()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['id'];
            $data = [
                'name' => trim($_POST['name'] ?? ''),
                'description' => trim($_POST['description'] ?? ''),
                'discount_type' => $_POST['discount_type'] ?? 'percentage',
                'discount_value' => floatval($_POST['discount_value'] ?? 0),
                'min_order_amount' => floatval($_POST['min_order_amount'] ?? 0),
                'max_discount_amount' => !empty($_POST['max_discount_amount']) ? floatval($_POST['max_discount_amount']) : null,
                'applies_to' => $_POST['applies_to'] ?? 'all_products',
                'product_ids' => null,
                'category_ids' => null,
                'usage_limit' => !empty($_POST['usage_limit']) ? intval($_POST['usage_limit']) : null,
                'start_date' => $_POST['start_date'] ?? '',
                'end_date' => $_POST['end_date'] ?? '',
                'is_active' => isset($_POST['is_active']) ? 1 : 0
            ];
            
            if ($data['applies_to'] == 'specific_products' && !empty($_POST['product_ids'])) {
                $data['product_ids'] = json_encode($_POST['product_ids']);
            }
            
            // Thêm xử lý category_ids
            if ($data['applies_to'] == 'specific_categories' && !empty($_POST['category_ids'])) {
                $data['category_ids'] = json_encode($_POST['category_ids']);
            }
            
            if ($this->voucherModel->updateVoucher($id, $data)) {
                header('Location: /webbanhang/Voucher');
                exit;
            } else {
                echo "Đã xảy ra lỗi khi cập nhật voucher.";
            }
        }
    }
    
    public function delete($id)
    {
        if ($this->voucherModel->deleteVoucher($id)) {
            header('Location: /webbanhang/Voucher');
        } else {
            echo "Đã xảy ra lỗi khi xóa voucher.";
        }
    }
    
    public function validateVoucherCode()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $code = trim($_POST['voucher_code'] ?? '');
            $cartTotal = floatval($_POST['cart_total'] ?? 0);
            
            // Get product IDs from cart
            $productIds = [];
            if (isset($_SESSION['cart'])) {
                $productIds = array_keys($_SESSION['cart']);
            }
            
            $result = $this->voucherModel->validateVoucher($code, $cartTotal, $productIds);
            
            if ($result['valid']) {
                $discount = $this->voucherModel->calculateDiscount($result['voucher'], $cartTotal);
                $_SESSION['applied_voucher'] = [
                    'id' => $result['voucher']->id,
                    'code' => $result['voucher']->code,
                    'discount' => $discount
                ];
                echo json_encode(['success' => true, 'discount' => $discount, 'message' => 'Áp dụng voucher thành công']);
            } else {
                echo json_encode(['success' => false, 'message' => $result['message']]);
            }
        }
    }
    
    public function removeVoucher()
    {
        unset($_SESSION['applied_voucher']);
        header('Location: /webbanhang/Product/cart');
    }
}
?>
<?php
// filepath: app/controllers/VoucherController.php
require_once('app/config/database.php');
require_once('app/models/VoucherModel.php');
require_once('app/models/ProductModel.php');
require_once('app/helpers/AuthHelper.php');

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
    
    // ADMIN ONLY - Xem danh sách voucher
    public function index()
    {
        AuthHelper::requireAdmin('/webbanhang/account/login');
        
        $vouchers = $this->voucherModel->getVouchers();
        include 'app/views/voucher/list.php';
    }
    
    // ADMIN ONLY - Thêm voucher (DEPRECATED - use AdminController)
    public function add()
    {
        AuthHelper::requireAdmin('/webbanhang/account/login');
        
        // Redirect to proper admin interface
        header('Location: /webbanhang/admin/vouchers/create');
        exit;
    }
    
    // ADMIN ONLY - Lưu voucher mới
    public function save()
    {
        AuthHelper::requireAdmin('/webbanhang/account/login');
        
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
                // Redirect to admin create with error handling
                header('Location: /webbanhang/admin/vouchers/create');
                exit;
            } else {
                header('Location: /webbanhang/admin/vouchers');
                exit;
            }
        }
    }
    
    // ADMIN ONLY - Form sửa voucher (DEPRECATED - use AdminController)
    public function edit($id)
    {
        AuthHelper::requireAdmin('/webbanhang/account/login');
        
        // Redirect to proper admin interface
        header('Location: /webbanhang/admin/vouchers/edit/' . $id);
        exit;
    }
    
    // ADMIN ONLY - Cập nhật voucher
    public function update()
    {
        AuthHelper::requireAdmin('/webbanhang/account/login');
        
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
                header('Location: /webbanhang/admin/vouchers');
                exit;
            } else {
                echo "Đã xảy ra lỗi khi cập nhật voucher.";
            }
        }
    }
    
    // ADMIN ONLY - Xóa voucher
    public function delete($id)
    {
        AuthHelper::requireAdmin('/webbanhang/account/login');
        
        if ($this->voucherModel->deleteVoucher($id)) {
            header('Location: /webbanhang/admin/vouchers');
            exit;
        } else {
            echo "Đã xảy ra lỗi khi xóa voucher.";
        }
    }
    
    // PUBLIC - Validate voucher code (user có thể sử dụng)
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
    
    // PUBLIC - Remove voucher (user có thể sử dụng)
    public function removeVoucher()
    {
        unset($_SESSION['applied_voucher']);
        header('Location: /webbanhang/user/cart');
        exit;
    }
}
?>
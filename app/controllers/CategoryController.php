<?php
require_once('app/config/database.php');
require_once('app/models/ProductModel.php');
require_once('app/models/CategoryModel.php');
require_once('app/helpers/AuthHelper.php');


class CategoryController
{
    private $categoryModel;
    private $db;

    public function __construct()
    {
        $this->db = (new Database())->getConnection();
        $this->categoryModel = new CategoryModel($this->db);
    }

    // PUBLIC - Xem danh sách danh mục (cho mọi người)
    public function index()
    {
        $categories = $this->categoryModel->getCategories();
        include 'app/views/category/list.php';
    }

    // ADMIN ONLY - Thêm danh mục
    public function add()
    {
        AuthHelper::requireAdmin('/webbanhang/account/login');
        
        include 'app/views/category/add.php';
    }

    // ADMIN ONLY - Lưu danh mục mới
    public function save()
    {
        AuthHelper::requireAdmin('/webbanhang/account/login');
        
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $name = $_POST['name'] ?? '';
            $description = $_POST['description'] ?? '';
            $result = $this->categoryModel->addCategory($name, $description);
            if (is_array($result)) {
                $errors = $result;
                include 'app/views/category/add.php';
            } else {
                header('Location: /webbanhang/admin/categories');
                exit;
            }
        }
    }

    // ADMIN ONLY - Form sửa danh mục
    public function edit($id)
    {
        AuthHelper::requireAdmin('/webbanhang/account/login');
        
        $category = $this->categoryModel->getCategoryById($id);
        if ($category) {
            include 'app/views/category/edit.php';
        } else {
            echo "Không tìm thấy danh mục.";
        }
    }

    // ADMIN ONLY - Cập nhật danh mục
    public function update()
    {
        AuthHelper::requireAdmin('/webbanhang/account/login');
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['id'];
            $name = $_POST['name'];
            $description = $_POST['description'];
            $edit = $this->categoryModel->updateCategory($id, $name, $description);
            if ($edit) {
                header('Location: /webbanhang/admin/categories');
                exit;
            } else {
                echo "Đã xảy ra lỗi khi lưu danh mục.";
            }
        }
    }

    // ADMIN ONLY - Xóa danh mục
    public function delete($id)
    {
        AuthHelper::requireAdmin('/webbanhang/account/login');
        
        if ($this->categoryModel->deleteCategory($id)) {
            header('Location: /webbanhang/admin/categories');
            exit;
        } else {
            echo "Đã xảy ra lỗi khi xóa danh mục. Các sản phẩm trong danh mục này sẽ được giữ nguyên và chuyển thành không có danh mục.";
        }
    }
}
?>
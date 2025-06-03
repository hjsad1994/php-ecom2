<?php
require_once('app/config/database.php');
require_once('app/models/ProductModel.php');
require_once('app/models/CategoryModel.php');

class HomeController
{
    private $productModel;
    private $categoryModel;
    private $db;
    
    public function __construct()
    {
        // Start session if not already started
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        
        $this->db = (new Database())->getConnection();
        $this->productModel = new ProductModel($this->db);
        $this->categoryModel = new CategoryModel($this->db);
    }
    
    /**
     * Trang chủ
     */
    public function index()
    {
        // Get featured products (latest 8 products)
        $featuredProducts = $this->productModel->getFeaturedProducts(8);
        
        // Get categories
        $categories = $this->categoryModel->getCategories();
        
        include 'app/views/home/index.php';
    }
}
?> 
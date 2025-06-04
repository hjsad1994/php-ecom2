<?php
// Test script để kiểm tra admin products
require_once('app/config/database.php');
require_once('app/models/ProductModel.php');
require_once('app/models/CategoryModel.php');

echo "<h2>Testing Admin Products Functionality</h2>";

try {
    // Kết nối database
    $db = (new Database())->getConnection();
    $productModel = new ProductModel($db);
    $categoryModel = new CategoryModel($db);
    
    echo "<h3>1. Testing ProductModel methods:</h3>";
    
    // Test getProductCount
    $productCount = $productModel->getProductCount();
    echo "Total products: " . $productCount . "<br>";
    
    // Test getCategoryCount  
    $categoryCount = $categoryModel->getCategoryCount();
    echo "Total categories: " . $categoryCount . "<br>";
    
    // Test getPriceRange
    $priceRange = $productModel->getPriceRange();
    if ($priceRange) {
        echo "Price range: " . number_format($priceRange->min_price) . " - " . number_format($priceRange->max_price) . "<br>";
        echo "Average price: " . number_format($priceRange->avg_price) . "<br>";
        echo "Products with image: " . $priceRange->products_with_image . "<br>";
    }
    
    // Test getProductStatsByCategory
    echo "<h3>2. Category Statistics:</h3>";
    $categoryStats = $productModel->getProductStatsByCategory();
    if (!empty($categoryStats)) {
        foreach ($categoryStats as $category) {
            echo "- {$category->category_name}: {$category->product_count} products<br>";
        }
    } else {
        echo "No category statistics found<br>";
    }
    
    // Test getLatestProducts
    echo "<h3>3. Latest Products:</h3>";
    $latestProducts = $productModel->getLatestProducts(3);
    if (!empty($latestProducts)) {
        foreach ($latestProducts as $product) {
            echo "- {$product->name} (ID: {$product->id})<br>";
        }
    } else {
        echo "No products found<br>";
    }
    
    echo "<h3>✅ All tests passed!</h3>";
    
} catch (Exception $e) {
    echo "<h3 style='color: red;'>❌ Error: " . $e->getMessage() . "</h3>";
    echo "<pre>" . $e->getTraceAsString() . "</pre>";
}

echo "<br><a href='/webbanhang/admin/products'>Try accessing admin products page</a>";
?> 
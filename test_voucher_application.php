<?php
session_start();
require_once 'app/config/database.php';
require_once 'app/models/VoucherModel.php';
require_once 'app/models/ProductModel.php';

$db = (new Database())->getConnection();
$voucherModel = new VoucherModel($db);
$productModel = new ProductModel($db);

echo "<h2>Voucher Application Debug</h2>";

// Simulate cart with products
if (!isset($_SESSION['cart'])) {
    // Get some sample products
    $products = $productModel->getProducts();
    if (!empty($products)) {
        $_SESSION['cart'] = [
            $products[0]->id => [
                'product_id' => $products[0]->id,
                'quantity' => 2,
                'price' => $products[0]->price,
                'name' => $products[0]->name,
                'image' => $products[0]->image
            ]
        ];
        if (isset($products[1])) {
            $_SESSION['cart'][$products[1]->id] = [
                'product_id' => $products[1]->id,
                'quantity' => 1,
                'price' => $products[1]->price,
                'name' => $products[1]->name,
                'image' => $products[1]->image
            ];
        }
    }
}

// Display current cart
echo "<h3>Current Cart:</h3>";
if (isset($_SESSION['cart']) && !empty($_SESSION['cart'])) {
    $cartTotal = 0;
    $productIds = [];
    
    echo "<table border='1' style='border-collapse: collapse;'>";
    echo "<tr><th>ID</th><th>Product</th><th>Price</th><th>Quantity</th><th>Subtotal</th></tr>";
    
    foreach ($_SESSION['cart'] as $productId => $item) {
        $subtotal = $item['price'] * $item['quantity'];
        $cartTotal += $subtotal;
        $productIds[] = $productId;
        
        echo "<tr>";
        echo "<td>{$productId}</td>";
        echo "<td>{$item['name']}</td>";
        echo "<td>" . number_format($item['price'], 0, ',', '.') . " đ</td>";
        echo "<td>{$item['quantity']}</td>";
        echo "<td>" . number_format($subtotal, 0, ',', '.') . " đ</td>";
        echo "</tr>";
    }
    
    echo "<tr style='font-weight: bold;'>";
    echo "<td colspan='4'>TOTAL</td>";
    echo "<td>" . number_format($cartTotal, 0, ',', '.') . " đ</td>";
    echo "</tr>";
    echo "</table>";
    
    echo "<p><strong>Cart Total:</strong> " . number_format($cartTotal, 0, ',', '.') . " đ</p>";
    echo "<p><strong>Product IDs:</strong> [" . implode(', ', $productIds) . "]</p>";
    
} else {
    echo "<p>Cart is empty</p>";
    $cartTotal = 0;
    $productIds = [];
}

// Get all vouchers
echo "<hr><h3>Available Vouchers:</h3>";
$vouchers = $voucherModel->getAll();

if (!empty($vouchers)) {
    echo "<table border='1' style='border-collapse: collapse;'>";
    echo "<tr><th>Code</th><th>Name</th><th>Type</th><th>Value</th><th>Min Order</th><th>Applies To</th><th>Active</th><th>Test</th></tr>";
    
    foreach ($vouchers as $voucher) {
        echo "<tr>";
        echo "<td>{$voucher->code}</td>";
        echo "<td>{$voucher->name}</td>";
        echo "<td>{$voucher->discount_type}</td>";
        echo "<td>{$voucher->discount_value}" . ($voucher->discount_type == 'percentage' ? '%' : ' đ') . "</td>";
        echo "<td>" . number_format($voucher->min_order_amount, 0, ',', '.') . " đ</td>";
        echo "<td>{$voucher->applies_to}</td>";
        echo "<td>" . ($voucher->is_active ? 'Yes' : 'No') . "</td>";
        echo "<td><a href='?test_voucher={$voucher->code}'>Test</a></td>";
        echo "</tr>";
    }
    echo "</table>";
} else {
    echo "<p>No vouchers available</p>";
}

// Test specific voucher
if (isset($_GET['test_voucher'])) {
    $testCode = $_GET['test_voucher'];
    
    echo "<hr><h3>Testing Voucher: {$testCode}</h3>";
    
    $result = $voucherModel->validateVoucher($testCode, $cartTotal, $productIds);
    
    if ($result['valid']) {
        $discount = $voucherModel->calculateDiscount($result['voucher'], $cartTotal);
        $finalTotal = $cartTotal - $discount;
        
        echo "<div style='color: green; padding: 10px; border: 1px solid green; background: #f0fff0;'>";
        echo "<h4>✅ Voucher Valid!</h4>";
        echo "<p><strong>Original Total:</strong> " . number_format($cartTotal, 0, ',', '.') . " đ</p>";
        echo "<p><strong>Discount:</strong> " . number_format($discount, 0, ',', '.') . " đ</p>";
        echo "<p><strong>Final Total:</strong> " . number_format($finalTotal, 0, ',', '.') . " đ</p>";
        echo "</div>";
        
        // Test application to session
        $_SESSION['applied_voucher'] = [
            'id' => $result['voucher']->id,
            'code' => $result['voucher']->code,
            'name' => $result['voucher']->name,
            'discount' => $discount,
            'type' => $result['voucher']->discount_type,
            'original_total' => $cartTotal,
            'final_total' => $finalTotal
        ];
        
        echo "<p><strong>Session updated!</strong></p>";
        
    } else {
        echo "<div style='color: red; padding: 10px; border: 1px solid red; background: #fff0f0;'>";
        echo "<h4>❌ Voucher Invalid!</h4>";
        echo "<p><strong>Error:</strong> {$result['message']}</p>";
        echo "</div>";
    }
}

// Show applied voucher
if (isset($_SESSION['applied_voucher'])) {
    echo "<hr><h3>Currently Applied Voucher:</h3>";
    $applied = $_SESSION['applied_voucher'];
    
    echo "<div style='color: blue; padding: 10px; border: 1px solid blue; background: #f0f8ff;'>";
    echo "<p><strong>Code:</strong> {$applied['code']}</p>";
    echo "<p><strong>Name:</strong> {$applied['name']}</p>";
    echo "<p><strong>Discount:</strong> " . number_format($applied['discount'], 0, ',', '.') . " đ</p>";
    echo "<p><strong>Final Total:</strong> " . number_format($applied['final_total'], 0, ',', '.') . " đ</p>";
    echo "<a href='?remove_voucher=1'>Remove Voucher</a>";
    echo "</div>";
}

// Remove voucher
if (isset($_GET['remove_voucher'])) {
    unset($_SESSION['applied_voucher']);
    echo "<p style='color: orange;'>Voucher removed!</p>";
    echo "<script>setTimeout(() => location.href = 'test_voucher_application.php', 1000);</script>";
}

echo "<hr>";
echo "<p><a href='/webbanhang/cart'>Go to Cart</a></p>";
echo "<p><a href='/webbanhang/admin/vouchers'>Manage Vouchers</a></p>";
?> 
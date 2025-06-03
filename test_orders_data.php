<?php
session_start();
require_once 'app/config/database.php';
require_once 'app/models/OrderModel.php';
require_once 'app/helpers/AuthHelper.php';

$db = (new Database())->getConnection();
$orderModel = new OrderModel($db);

echo "<h2>Orders Data Debug</h2>";

// Check user authentication
if (!AuthHelper::isLoggedIn()) {
    echo "<p style='color: red'>❌ User not logged in. <a href='/webbanhang/quick_login_test.php'>Login first</a></p>";
    exit;
}

$userId = AuthHelper::getUserId();
echo "<strong>Current User ID:</strong> $userId<br>";

echo "<hr>";

// Get orders for current user
$orders = $orderModel->getOrdersByUserId($userId);
echo "<strong>Orders count for user:</strong> " . count($orders) . "<br><br>";

if (empty($orders)) {
    echo "<p style='color: orange'>⚠️ No orders found. <a href='/webbanhang/order/create'>Create test order</a></p>";
    
    // Show all orders in database for debugging
    $allOrders = $orderModel->getAllOrders();
    echo "<strong>Total orders in database:</strong> " . count($allOrders) . "<br>";
    
    if (!empty($allOrders)) {
        echo "<h3>All Orders (for debugging):</h3>";
        foreach ($allOrders as $order) {
            echo "Order ID: {$order['id']}, User ID: {$order['user_id']}, Status: {$order['order_status']}<br>";
        }
    }
} else {
    echo "<p style='color: green'>✅ Found orders:</p>";
    foreach ($orders as $order) {
        echo "<div style='border: 1px solid #ccc; padding: 10px; margin: 10px;'>";
        echo "<strong>Order #" . str_pad($order['id'], 5, '0', STR_PAD_LEFT) . "</strong><br>";
        echo "Status: {$order['order_status']}<br>";
        echo "Total: " . number_format($order['total_amount'], 0, ',', '.') . " đ<br>";
        echo "Created: {$order['created_at']}<br>";
        echo "<a href='/webbanhang/user/orders/view/{$order['id']}'>View Details</a> | ";
        echo "<a href='/webbanhang/user/orders/payment/{$order['id']}'>Payment</a> | ";
        echo "<a href='/webbanhang/user/orders/invoice/{$order['id']}'>Invoice</a>";
        echo "</div>";
    }
}

echo "<hr>";
echo "<a href='/webbanhang/user/orders'>Go to Orders List</a>";
?> 
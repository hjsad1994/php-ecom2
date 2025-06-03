<?php
session_start();
require_once 'app/config/database.php';
require_once 'app/models/OrderModel.php';
require_once 'app/helpers/AuthHelper.php';

echo "<h2>Order Confirm Debug</h2>";

// Check if user is logged in
echo "<h3>1. Authentication Check:</h3>";
if (AuthHelper::isLoggedIn()) {
    echo "<p style='color: green'>✅ User is logged in</p>";
    echo "<p><strong>User ID:</strong> " . AuthHelper::getUserId() . "</p>";
    echo "<p><strong>Is Admin:</strong> " . (AuthHelper::isAdmin() ? 'Yes' : 'No') . "</p>";
} else {
    echo "<p style='color: red'>❌ User is not logged in</p>";
    echo "<p><a href='/webbanhang/account/login'>Login here</a></p>";
}

// Check database connection
echo "<h3>2. Database Connection:</h3>";
try {
    $db = (new Database())->getConnection();
    $orderModel = new OrderModel($db);
    echo "<p style='color: green'>✅ Database connection: OK</p>";
} catch (Exception $e) {
    echo "<p style='color: red'>❌ Database error: " . $e->getMessage() . "</p>";
    exit;
}

// List all orders
echo "<h3>3. Available Orders:</h3>";
try {
    $query = "SELECT o.*, u.username FROM orders o LEFT JOIN users u ON o.user_id = u.id ORDER BY o.created_at DESC LIMIT 10";
    $stmt = $db->prepare($query);
    $stmt->execute();
    $orders = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    if (!empty($orders)) {
        echo "<table border='1' style='border-collapse: collapse; width: 100%;'>";
        echo "<tr><th>ID</th><th>User</th><th>Name</th><th>Total</th><th>Status</th><th>Date</th><th>Test</th></tr>";
        
        foreach ($orders as $order) {
            echo "<tr>";
            echo "<td>{$order['id']}</td>";
            echo "<td>{$order['username']}</td>";
            echo "<td>{$order['name']}</td>";
            echo "<td>" . number_format($order['total_amount'], 0, ',', '.') . " đ</td>";
            echo "<td>{$order['order_status']}</td>";
            echo "<td>" . date('d/m/Y H:i', strtotime($order['created_at'])) . "</td>";
            echo "<td><a href='?test_order={$order['id']}'>Test Confirm</a></td>";
            echo "</tr>";
        }
        echo "</table>";
    } else {
        echo "<p>No orders found</p>";
    }
} catch (Exception $e) {
    echo "<p style='color: red'>❌ Error loading orders: " . $e->getMessage() . "</p>";
}

// Test specific order
if (isset($_GET['test_order'])) {
    $orderId = $_GET['test_order'];
    
    echo "<hr><h3>4. Testing Order #{$orderId}:</h3>";
    
    // Check permissions
    echo "<h4>Permission Check:</h4>";
    if (AuthHelper::isLoggedIn()) {
        try {
            $canView = AuthHelper::canViewOrder($orderId);
            echo "<p><strong>Can view order:</strong> " . ($canView ? 'Yes' : 'No') . "</p>";
        } catch (Exception $e) {
            echo "<p style='color: red'>❌ Permission check error: " . $e->getMessage() . "</p>";
        }
    }
    
    // Get order with details
    echo "<h4>Order Data:</h4>";
    try {
        $order = $orderModel->getOrderWithDetails($orderId);
        if ($order) {
            echo "<div style='background: #f0f8ff; padding: 10px; border: 1px solid #0066cc;'>";
            echo "<p><strong>Order found!</strong></p>";
            echo "<p><strong>ID:</strong> {$order['id']}</p>";
            echo "<p><strong>Name:</strong> {$order['name']}</p>";
            echo "<p><strong>Total:</strong> " . number_format($order['total_amount'], 0, ',', '.') . " đ</p>";
            echo "<p><strong>Status:</strong> {$order['order_status']}</p>";
            echo "<p><strong>User ID:</strong> {$order['user_id']}</p>";
            
            if (isset($order['items'])) {
                echo "<p><strong>Items:</strong> " . count($order['items']) . " products</p>";
            }
            
            echo "<p><a href='/webbanhang/order/confirm/{$orderId}' target='_blank'>Test Confirm Page</a></p>";
            echo "</div>";
        } else {
            echo "<p style='color: red'>❌ Order not found</p>";
        }
    } catch (Exception $e) {
        echo "<p style='color: red'>❌ Error loading order: " . $e->getMessage() . "</p>";
    }
}

echo "<hr>";
echo "<p><a href='/webbanhang/admin/orders'>Admin Orders</a></p>";
echo "<p><a href='/webbanhang/user/orders'>User Orders</a></p>";
echo "<p><a href='/webbanhang/'>Homepage</a></p>";
?> 
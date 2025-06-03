<?php
session_start();
require_once 'app/config/database.php';
require_once 'app/models/OrderModel.php';
require_once 'app/helpers/AuthHelper.php';

echo "<h2>Order Confirm Direct Test</h2>";

// Auto login as admin for testing
if (!AuthHelper::isLoggedIn()) {
    $_SESSION['user_id'] = 1;
    $_SESSION['username'] = 'admin';
    $_SESSION['role'] = 'admin';
    echo "<p style='color: green'>✅ Auto-logged in as admin for testing</p>";
}

$orderId = $_GET['order_id'] ?? 12;
echo "<h3>Testing Order ID: {$orderId}</h3>";

echo "<h4>1. Database Connection:</h4>";
try {
    $db = (new Database())->getConnection();
    $orderModel = new OrderModel($db);
    echo "<p style='color: green'>✅ Database connected</p>";
} catch (Exception $e) {
    echo "<p style='color: red'>❌ Database error: " . $e->getMessage() . "</p>";
    exit;
}

echo "<h4>2. Authentication Check:</h4>";
echo "<p>User ID: " . (AuthHelper::getUserId() ?? 'null') . "</p>";
echo "<p>Is Admin: " . (AuthHelper::isAdmin() ? 'Yes' : 'No') . "</p>";
echo "<p>Is Logged In: " . (AuthHelper::isLoggedIn() ? 'Yes' : 'No') . "</p>";

echo "<h4>3. Permission Check:</h4>";
try {
    $canView = AuthHelper::canViewOrder($orderId);
    echo "<p>Can view order {$orderId}: " . ($canView ? 'Yes' : 'No') . "</p>";
} catch (Exception $e) {
    echo "<p style='color: red'>❌ Permission check error: " . $e->getMessage() . "</p>";
}

echo "<h4>4. Order Data Check:</h4>";
try {
    $order = $orderModel->getOrderWithDetails($orderId);
    
    if ($order) {
        echo "<div style='background: #f0f8ff; padding: 10px; border: 1px solid #0066cc;'>";
        echo "<p style='color: green'>✅ Order found!</p>";
        echo "<p><strong>Order ID:</strong> {$order['id']}</p>";
        echo "<p><strong>Name:</strong> {$order['name']}</p>";
        echo "<p><strong>Phone:</strong> {$order['phone']}</p>";
        echo "<p><strong>Address:</strong> {$order['address']}</p>";
        echo "<p><strong>Total:</strong> " . number_format($order['total_amount'], 0, ',', '.') . " đ</p>";
        echo "<p><strong>Status:</strong> {$order['order_status']}</p>";
        echo "<p><strong>User ID:</strong> {$order['user_id']}</p>";
        echo "<p><strong>Username:</strong> " . ($order['username'] ?? 'N/A') . "</p>";
        echo "<p><strong>Items Count:</strong> " . count($order['items']) . "</p>";
        
        if (!empty($order['items'])) {
            echo "<h5>Order Items:</h5>";
            echo "<ul>";
            foreach ($order['items'] as $item) {
                echo "<li>{$item['product_name']} - Qty: {$item['quantity']} - Price: " . number_format($item['price'], 0, ',', '.') . " đ</li>";
            }
            echo "</ul>";
        }
        echo "</div>";
        
        echo "<h4>5. Test Confirm View:</h4>";
        echo "<p><a href='/webbanhang/order/confirm/{$orderId}' target='_blank'>🔗 Test Actual Confirm Page</a></p>";
        echo "<p><a href='?test_view=1&order_id={$orderId}'>🧪 Test View Rendering Below</a></p>";
        
    } else {
        echo "<p style='color: red'>❌ Order not found</p>";
    }
} catch (Exception $e) {
    echo "<p style='color: red'>❌ Order loading error: " . $e->getMessage() . "</p>";
    echo "<p><strong>Stack trace:</strong><br>" . nl2br($e->getTraceAsString()) . "</p>";
}

// Test view rendering
if (isset($_GET['test_view']) && $order) {
    echo "<hr><h4>6. View Rendering Test:</h4>";
    try {
        echo "<div style='border: 2px solid #007cba; padding: 20px; margin: 20px 0;'>";
        echo "<h5>Rendering confirm.php view with order data:</h5>";
        
        // Simulate what OrderController does
        ob_start();
        include_once 'app/views/orders/confirm.php';
        $viewOutput = ob_get_clean();
        
        if (!empty($viewOutput)) {
            echo "<p style='color: green'>✅ View rendered successfully!</p>";
            echo "<details><summary>Click to see rendered HTML</summary>";
            echo "<pre>" . htmlspecialchars(substr($viewOutput, 0, 500)) . "...</pre>";
            echo "</details>";
            
            // Actually display the view
            echo "<hr>";
            echo $viewOutput;
        } else {
            echo "<p style='color: red'>❌ View rendering produced no output</p>";
        }
        
        echo "</div>";
    } catch (Exception $e) {
        echo "<p style='color: red'>❌ View rendering error: " . $e->getMessage() . "</p>";
        echo "<p><strong>Stack trace:</strong><br>" . nl2br($e->getTraceAsString()) . "</p>";
    }
}

echo "<hr>";
echo "<h4>Debug Links:</h4>";
echo "<p><a href='quick_order_test.php'>Order Test</a></p>";
echo "<p><a href='test_routing_debug.php?url=order/confirm/{$orderId}'>Routing Debug</a></p>";
echo "<p><a href='/webbanhang/'>Homepage</a></p>";

// Show error logs
echo "<h4>Recent Error Logs:</h4>";
$errorLogPath = ini_get('error_log');
if ($errorLogPath && file_exists($errorLogPath)) {
    $logs = array_slice(file($errorLogPath), -20);
    echo "<pre style='background: #f0f0f0; padding: 10px; font-size: 12px;'>";
    echo implode('', $logs);
    echo "</pre>";
} else {
    echo "<p>Error log path not found or not accessible</p>";
}
?> 
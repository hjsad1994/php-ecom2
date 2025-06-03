<?php
session_start();
require_once 'app/config/database.php';
require_once 'app/models/OrderModel.php';
require_once 'app/helpers/AuthHelper.php';

echo "<h2>Quick Order Test</h2>";

// Auto login as admin for testing
if (!AuthHelper::isLoggedIn()) {
    $_SESSION['user_id'] = 1;
    $_SESSION['username'] = 'admin';
    $_SESSION['role'] = 'admin';
    echo "<p style='color: green'>✅ Auto-logged in as admin for testing</p>";
}

$db = (new Database())->getConnection();
$orderModel = new OrderModel($db);

// Check database tables
echo "<h3>Database Check:</h3>";
try {
    // Check if tables exist
    $tables = ['orders', 'order_details', 'account', 'product'];
    foreach ($tables as $table) {
        $stmt = $db->prepare("SHOW TABLES LIKE '$table'");
        $stmt->execute();
        $exists = $stmt->fetch();
        echo "<p><strong>Table '$table':</strong> " . ($exists ? '✅ Exists' : '❌ Missing') . "</p>";
    }
    
    // Check orders table structure
    $stmt = $db->prepare("DESCRIBE orders");
    $stmt->execute();
    $columns = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo "<p><strong>Orders table columns:</strong> " . implode(', ', array_column($columns, 'Field')) . "</p>";
    
} catch (Exception $e) {
    echo "<p style='color: red'>❌ Database check error: " . $e->getMessage() . "</p>";
}

// Create test order if not exists
if (isset($_GET['create_test_order'])) {
    echo "<h3>Creating Test Order:</h3>";
    
    try {
        // First, ensure we have a user
        $userCheck = $db->prepare("SELECT id FROM account LIMIT 1");
        $userCheck->execute();
        $user = $userCheck->fetch();
        
        if (!$user) {
            // Create test user
            $createUser = $db->prepare("INSERT INTO account (username, password, role) VALUES ('testuser', 'password', 'user')");
            $createUser->execute();
            $userId = $db->lastInsertId();
            echo "<p style='color: blue'>ℹ️ Created test user with ID: $userId</p>";
        } else {
            $userId = $user['id'];
            echo "<p style='color: blue'>ℹ️ Using existing user with ID: $userId</p>";
        }
        
        // Create order
        $testOrderData = [
            'user_id' => $userId,
            'name' => 'Test Customer',
            'phone' => '0123456789',
            'address' => '123 Test Street, Test City',
            'total_amount' => 500000,
            'order_status' => 'unpaid'
        ];
        
        $query = "INSERT INTO orders (user_id, name, phone, address, total_amount, order_status, created_at) 
                  VALUES (:user_id, :name, :phone, :address, :total_amount, :order_status, NOW())";
        $stmt = $db->prepare($query);
        $stmt->execute($testOrderData);
        $newOrderId = $db->lastInsertId();
        
        echo "<p style='color: green'>✅ Order #{$newOrderId} created successfully!</p>";
        
        // Add test order items (only if product table exists)
        $productCheck = $db->prepare("SELECT id, name, price FROM product LIMIT 2");
        $productCheck->execute();
        $products = $productCheck->fetchAll(PDO::FETCH_ASSOC);
        
        if (!empty($products)) {
            foreach ($products as $index => $product) {
                $testItem = [
                    'order_id' => $newOrderId,
                    'product_id' => $product['id'],
                    'quantity' => $index + 1,
                    'price' => $product['price']
                ];
                
                $query = "INSERT INTO order_details (order_id, product_id, quantity, price) 
                          VALUES (:order_id, :product_id, :quantity, :price)";
                $stmt = $db->prepare($query);
                $stmt->execute($testItem);
            }
            echo "<p style='color: green'>✅ Added " . count($products) . " order items</p>";
        } else {
            echo "<p style='color: orange'>⚠️ No products found, order created without items</p>";
        }
        
        echo "<div style='background: #f0f8ff; padding: 10px; border: 1px solid #0066cc; margin: 10px 0;'>";
        echo "<p><strong>🎉 Order Successfully Created!</strong></p>";
        echo "<p><a href='/webbanhang/order/confirm/{$newOrderId}' target='_blank' style='margin-right: 10px;'>🔗 Test Confirm Page</a></p>";
        echo "<p><a href='/webbanhang/order/confirm/{$newOrderId}?debug=1' target='_blank'>🐛 Test Confirm Page (with debug)</a></p>";
        echo "</div>";
        
    } catch (Exception $e) {
        echo "<p style='color: red'>❌ Error creating test order: " . $e->getMessage() . "</p>";
        echo "<p><strong>SQL Error:</strong> " . print_r($e->errorInfo ?? [], true) . "</p>";
    }
}

// List existing orders
echo "<h3>Existing Orders:</h3>";
try {
    $orders = $orderModel->getAll();
    
    if (!empty($orders)) {
        echo "<table border='1' style='border-collapse: collapse; width: 100%;'>";
        echo "<tr><th>ID</th><th>Name</th><th>Total</th><th>Status</th><th>User ID</th><th>Actions</th></tr>";
        
        foreach ($orders as $order) {
            echo "<tr>";
            echo "<td>{$order->id}</td>";
            echo "<td>{$order->name}</td>";
            echo "<td>" . number_format($order->total_amount, 0, ',', '.') . " đ</td>";
            echo "<td>{$order->order_status}</td>";
            echo "<td>{$order->user_id}</td>";
            echo "<td style='white-space: nowrap;'>";
            echo "<a href='/webbanhang/order/confirm/{$order->id}' target='_blank'>Confirm</a> | ";
            echo "<a href='/webbanhang/order/confirm/{$order->id}?debug=1' target='_blank'>Debug</a>";
            echo "</td>";
            echo "</tr>";
        }
        echo "</table>";
    } else {
        echo "<p>No orders found.</p>";
        echo "<p><a href='?create_test_order=1' style='background: #007cba; color: white; padding: 10px 15px; text-decoration: none; border-radius: 5px;'>➕ Create Test Order</a></p>";
    }
} catch (Exception $e) {
    echo "<p style='color: red'>❌ Error loading orders: " . $e->getMessage() . "</p>";
    echo "<p><a href='?create_test_order=1' style='background: #007cba; color: white; padding: 10px 15px; text-decoration: none; border-radius: 5px;'>➕ Create Test Order</a></p>";
}

if (!isset($_GET['create_test_order'])) {
    echo "<hr>";
    echo "<p><a href='?create_test_order=1' style='background: #28a745; color: white; padding: 10px 15px; text-decoration: none; border-radius: 5px;'>➕ Create New Test Order</a></p>";
}

echo "<hr>";
echo "<p><a href='test_routing_debug.php?url=order/confirm/1'>🔧 Test Routing Debug</a></p>";
echo "<p><a href='/webbanhang/admin/orders'>👨‍💼 Admin Orders</a></p>";
echo "<p><a href='/webbanhang/'>🏠 Homepage</a></p>";
?> 
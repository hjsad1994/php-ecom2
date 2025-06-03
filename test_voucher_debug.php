<?php
session_start();
require_once 'app/config/database.php';
require_once 'app/models/VoucherModel.php';

$db = (new Database())->getConnection();
$voucherModel = new VoucherModel($db);

echo "<h2>Voucher System Debug</h2>";

// Test database connection
try {
    $vouchers = $voucherModel->getAll();
    echo "<p style='color: green'>✅ Database connection: OK</p>";
    echo "<p><strong>Total vouchers:</strong> " . count($vouchers) . "</p>";
} catch (Exception $e) {
    echo "<p style='color: red'>❌ Database error: " . $e->getMessage() . "</p>";
}

// Check if vouchers table exists and structure
try {
    $query = "DESCRIBE vouchers";
    $stmt = $db->prepare($query);
    $stmt->execute();
    $columns = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo "<h3>Vouchers Table Structure:</h3>";
    echo "<table border='1' style='border-collapse: collapse;'>";
    echo "<tr><th>Column</th><th>Type</th><th>Null</th><th>Key</th><th>Default</th></tr>";
    foreach ($columns as $column) {
        echo "<tr>";
        echo "<td>{$column['Field']}</td>";
        echo "<td>{$column['Type']}</td>";
        echo "<td>{$column['Null']}</td>";
        echo "<td>{$column['Key']}</td>";
        echo "<td>{$column['Default']}</td>";
        echo "</tr>";
    }
    echo "</table>";
    
} catch (Exception $e) {
    echo "<p style='color: red'>❌ Table structure error: " . $e->getMessage() . "</p>";
}

// Test voucher creation
echo "<hr><h3>Test Voucher Creation:</h3>";

$testVoucherData = [
    'code' => 'TEST' . time(),
    'name' => 'Test Voucher ' . date('Y-m-d H:i:s'),
    'description' => 'Test voucher for debugging',
    'discount_type' => 'percentage',
    'discount_value' => 10,
    'min_order_amount' => 100000,
    'max_discount_amount' => 50000,
    'applies_to' => 'all_products',
    'product_ids' => null,
    'category_ids' => null,
    'usage_limit' => 100,
    'start_date' => date('Y-m-d H:i:s'),
    'end_date' => date('Y-m-d H:i:s', strtotime('+30 days')),
    'is_active' => 1
];

echo "<strong>Test Data:</strong><br>";
echo "<pre>" . print_r($testVoucherData, true) . "</pre>";

try {
    $result = $voucherModel->save($testVoucherData);
    
    if ($result && !is_array($result)) {
        echo "<p style='color: green'>✅ Voucher created successfully! ID: " . $result . "</p>";
        
        // Test retrieval
        $newVoucher = $voucherModel->getVoucherById($db->lastInsertId());
        if ($newVoucher) {
            echo "<p style='color: green'>✅ Voucher retrieved successfully!</p>";
            echo "<pre>" . print_r($newVoucher, true) . "</pre>";
        }
    } else {
        echo "<p style='color: red'>❌ Voucher creation failed:</p>";
        if (is_array($result)) {
            foreach ($result as $error) {
                echo "<p style='color: red'>- " . htmlspecialchars($error) . "</p>";
            }
        } else {
            echo "<p style='color: red'>- Unknown error</p>";
        }
    }
} catch (Exception $e) {
    echo "<p style='color: red'>❌ Exception during voucher creation: " . $e->getMessage() . "</p>";
    echo "<p style='color: red'>Stack trace:</p>";
    echo "<pre>" . $e->getTraceAsString() . "</pre>";
}

// Show existing vouchers
echo "<hr><h3>Existing Vouchers:</h3>";
if (!empty($vouchers)) {
    echo "<table border='1' style='border-collapse: collapse;'>";
    echo "<tr><th>ID</th><th>Code</th><th>Name</th><th>Type</th><th>Value</th><th>Active</th><th>Actions</th></tr>";
    foreach ($vouchers as $voucher) {
        echo "<tr>";
        echo "<td>{$voucher->id}</td>";
        echo "<td>{$voucher->code}</td>";
        echo "<td>{$voucher->name}</td>";
        echo "<td>{$voucher->discount_type}</td>";
        echo "<td>{$voucher->discount_value}</td>";
        echo "<td>" . ($voucher->is_active ? 'Yes' : 'No') . "</td>";
        echo "<td><a href='/webbanhang/admin/vouchers/edit/{$voucher->id}'>Edit</a> | <a href='/webbanhang/admin/vouchers/delete/{$voucher->id}' onclick='return confirm(\"Delete?\")'>Delete</a></td>";
        echo "</tr>";
    }
    echo "</table>";
} else {
    echo "<p>No vouchers found.</p>";
}

echo "<hr>";
echo "<p><a href='/webbanhang/admin/vouchers'>Go to Admin Vouchers</a></p>";
echo "<p><a href='/webbanhang/admin/vouchers/create'>Create New Voucher</a></p>";
?> 
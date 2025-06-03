<?php
require_once 'app/config/database.php';

echo "<h2>SQL Import Tool</h2>";

try {
    $db = (new Database())->getConnection();
    
    // Read and execute SQL file
    $sqlFile = 'create_orders_table.sql';
    
    if (file_exists($sqlFile)) {
        $sql = file_get_contents($sqlFile);
        
        // Split by semicolon to execute each statement
        $statements = array_filter(array_map('trim', explode(';', $sql)));
        
        echo "<h3>Executing SQL Statements:</h3>";
        
        foreach ($statements as $statement) {
            if (!empty($statement)) {
                try {
                    $db->exec($statement);
                    echo "<p style='color: green;'>✅ Executed: " . substr($statement, 0, 50) . "...</p>";
                } catch (Exception $e) {
                    echo "<p style='color: orange;'>⚠️ Warning: " . $e->getMessage() . "</p>";
                    echo "<p><small>" . substr($statement, 0, 100) . "...</small></p>";
                }
            }
        }
        
        echo "<h3>Tables Created Successfully!</h3>";
        echo "<p><a href='quick_order_test.php'>Continue to Order Test</a></p>";
        
    } else {
        echo "<p style='color: red;'>❌ SQL file not found: $sqlFile</p>";
    }
    
} catch (Exception $e) {
    echo "<p style='color: red;'>❌ Database error: " . $e->getMessage() . "</p>";
}

echo "<hr>";
echo "<p><a href='quick_order_test.php'>Order Test</a></p>";
echo "<p><a href='/webbanhang/'>Homepage</a></p>";
?> 
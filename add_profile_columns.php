<?php
require_once 'app/config/database.php';

try {
    $db = (new Database())->getConnection();
    
    echo "<h2>Adding Profile Columns to Account Table</h2>";
    
    // Add columns one by one to handle any existing columns
    $sql_statements = [
        "ALTER TABLE `account` ADD COLUMN `fullname` VARCHAR(255) NULL AFTER `password`",
        "ALTER TABLE `account` ADD COLUMN `email` VARCHAR(255) NULL AFTER `fullname`", 
        "ALTER TABLE `account` ADD COLUMN `phone` VARCHAR(20) NULL AFTER `email`",
        "ALTER TABLE `account` ADD COLUMN `address` TEXT NULL AFTER `phone`",
        "UPDATE `account` SET `fullname` = `username` WHERE `fullname` IS NULL OR `fullname` = ''"
    ];
    
    foreach ($sql_statements as $sql) {
        try {
            $db->exec($sql);
            echo "<p style='color: green;'>✅ Executed: " . substr($sql, 0, 50) . "...</p>";
        } catch (PDOException $e) {
            if (strpos($e->getMessage(), 'Duplicate column name') !== false) {
                echo "<p style='color: orange;'>⚠️ Column already exists: " . substr($sql, 0, 50) . "...</p>";
            } else {
                echo "<p style='color: red;'>❌ Error: " . $e->getMessage() . "</p>";
                echo "<p>SQL: " . $sql . "</p>";
            }
        }
    }
    
    echo "<h3>Checking Account Table Structure:</h3>";
    $stmt = $db->prepare('DESCRIBE account');
    $stmt->execute();
    $columns = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo "<table border='1' style='border-collapse: collapse;'>";
    echo "<tr><th>Field</th><th>Type</th><th>Null</th><th>Key</th><th>Default</th></tr>";
    foreach($columns as $col) {
        echo "<tr>";
        echo "<td>{$col['Field']}</td>";
        echo "<td>{$col['Type']}</td>";  
        echo "<td>{$col['Null']}</td>";
        echo "<td>{$col['Key']}</td>";
        echo "<td>{$col['Default']}</td>";
        echo "</tr>";
    }
    echo "</table>";
    
    echo "<h3>Success! Profile columns added.</h3>";
    echo "<p><a href='/webbanhang/user/profile'>Test Profile Page</a></p>";
    
} catch (Exception $e) {
    echo "<h2>Error:</h2>";
    echo "<p>Error message: " . $e->getMessage() . "</p>";
    echo "<p>Error code: " . $e->getCode() . "</p>";
}
?> 
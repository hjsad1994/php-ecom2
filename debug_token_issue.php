<?php
session_start();
require_once('app/config/database.php');
require_once('app/models/PasswordResetModel.php');

echo "<h1>Debug Token và Timezone Issues</h1>\n";

echo "<h2>1. Timezone Information</h2>\n";
echo "<div style='background: #f8f9fa; padding: 15px; border-radius: 5px;'>";
echo "<strong>PHP Timezone:</strong> " . date_default_timezone_get() . "<br>";
echo "<strong>PHP Current Time:</strong> " . date('Y-m-d H:i:s') . "<br>";
echo "<strong>PHP Current Timestamp:</strong> " . time() . "<br>";
echo "<strong>PHP Time + 1 hour:</strong> " . date('Y-m-d H:i:s', time() + 3600) . "<br>";
echo "</div>";

// Test database time
try {
    $database = new Database();
    $db = $database->getConnection();
    
    $stmt = $db->prepare("SELECT NOW() as db_current_time, UNIX_TIMESTAMP(NOW()) as db_timestamp");
    $stmt->execute();
    $dbTime = $stmt->fetch(PDO::FETCH_OBJ);
    
    echo "<h2>2. Database Time Information</h2>\n";
    echo "<div style='background: #e7f3ff; padding: 15px; border-radius: 5px;'>";
    echo "<strong>Database Current Time:</strong> " . $dbTime->db_current_time . "<br>";
    echo "<strong>Database Timestamp:</strong> " . $dbTime->db_timestamp . "<br>";
    echo "</div>";
    
    $timeDiff = time() - $dbTime->db_timestamp;
    echo "<div style='background: " . ($timeDiff == 0 ? '#d4edda' : '#f8d7da') . "; padding: 15px; border-radius: 5px; margin: 10px 0;'>";
    echo "<strong>Time Difference:</strong> " . $timeDiff . " seconds";
    if ($timeDiff != 0) {
        echo " (⚠️ PHP và Database timezone khác nhau!)";
    } else {
        echo " (✅ PHP và Database timezone đồng bộ)";
    }
    echo "</div>";
    
} catch (Exception $e) {
    echo "<div style='background: #f8d7da; padding: 15px; border-radius: 5px;'>";
    echo "❌ Database connection error: " . $e->getMessage();
    echo "</div>";
}

echo "<h2>3. Token Generation Test</h2>\n";

try {
    $passwordResetModel = new PasswordResetModel($db);
    $testEmail = 'debug@test.com';
    
    echo "<strong>Testing with email:</strong> $testEmail<br>";
    
    // Tạo token mới
    $token = $passwordResetModel->createResetToken($testEmail);
    
    if ($token) {
        echo "<div style='background: #d4edda; padding: 15px; border-radius: 5px; margin: 10px 0;'>";
        echo "✅ <strong>Token created successfully!</strong><br>";
        echo "<strong>Token:</strong> $token<br>";
        echo "<strong>Token Length:</strong> " . strlen($token) . " characters<br>";
        echo "</div>";
        
        // Kiểm tra token trong database
        $stmt = $db->prepare("SELECT *, LENGTH(token) as token_length, (expires_at > NOW()) as is_valid FROM password_resets WHERE email = ? ORDER BY created_at DESC LIMIT 1");
        $stmt->execute([$testEmail]);
        $dbToken = $stmt->fetch(PDO::FETCH_OBJ);
        
        if ($dbToken) {
            echo "<h2>4. Database Token Information</h2>\n";
            echo "<div style='background: #f8f9fa; padding: 15px; border-radius: 5px;'>";
            echo "<strong>Email:</strong> " . $dbToken->email . "<br>";
            echo "<strong>Token in DB:</strong> " . $dbToken->token . "<br>";
            echo "<strong>Token Length in DB:</strong> " . $dbToken->token_length . "<br>";
            echo "<strong>Created At:</strong> " . $dbToken->created_at . "<br>";
            echo "<strong>Expires At:</strong> " . $dbToken->expires_at . "<br>";
            echo "<strong>Used:</strong> " . ($dbToken->used ? 'Yes' : 'No') . "<br>";
            echo "<strong>Is Valid (time):</strong> " . ($dbToken->is_valid ? 'Yes' : 'No') . "<br>";
            echo "</div>";
            
            // Test validation
            echo "<h2>5. Token Validation Test</h2>\n";
            $validationResult = $passwordResetModel->validateResetToken($token);
            
            if ($validationResult) {
                echo "<div style='background: #d4edda; color: #155724; padding: 15px; border-radius: 5px;'>";
                echo "✅ <strong>Token validation PASSED!</strong>";
                echo "</div>";
            } else {
                echo "<div style='background: #f8d7da; color: #721c24; padding: 15px; border-radius: 5px;'>";
                echo "❌ <strong>Token validation FAILED!</strong><br>";
                echo "Possible causes:<br>";
                echo "- Token expired (expires_at <= NOW())<br>";
                echo "- Token already used (used = 1)<br>";
                echo "- Token not found in database<br>";
                echo "</div>";
            }
            
            // Test với token bị cắt ngắn (như trong URL của bạn)
            echo "<h2>6. Test with Truncated Token</h2>\n";
            $truncatedToken = "183908a6e8cfb27d6a6193538f8770ba5faa5685545e01bd";
            echo "<strong>Testing with truncated token:</strong> $truncatedToken<br>";
            echo "<strong>Length:</strong> " . strlen($truncatedToken) . " characters<br>";
            
            $truncatedResult = $passwordResetModel->validateResetToken($truncatedToken);
            if ($truncatedResult) {
                echo "<div style='background: #d4edda; padding: 10px; border-radius: 5px;'>";
                echo "✅ Truncated token found and valid";
                echo "</div>";
            } else {
                echo "<div style='background: #f8d7da; padding: 10px; border-radius: 5px;'>";
                echo "❌ Truncated token not found or invalid (expected result)";
                echo "</div>";
            }
        }
        
    } else {
        echo "<div style='background: #f8d7da; padding: 15px; border-radius: 5px;'>";
        echo "❌ Failed to create token";
        echo "</div>";
    }
    
} catch (Exception $e) {
    echo "<div style='background: #f8d7da; padding: 15px; border-radius: 5px;'>";
    echo "❌ Error in token test: " . $e->getMessage();
    echo "</div>";
}

// Sửa timezone nếu cần
echo "<h2>7. Timezone Fix</h2>\n";
echo "<div style='background: #fff3cd; padding: 15px; border-radius: 5px;'>";
echo "<strong>💡 Fix suggestions:</strong><br>";
echo "1. Set PHP timezone: <code>date_default_timezone_set('Asia/Ho_Chi_Minh');</code><br>";
echo "2. Or use UTC for both PHP and MySQL: <code>date_default_timezone_set('UTC');</code><br>";
echo "3. Update PasswordResetModel to use proper timezone handling";
echo "</div>";

echo "<p style='margin-top: 30px;'>";
echo "<a href='/webbanhang/account/forgot-password'>← Back to Forgot Password</a> | ";
echo "<a href='/webbanhang/test_password_reset_flow.php'>Password Reset Flow Test</a>";
echo "</p>";
?>

<style>
body {
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    max-width: 900px;
    margin: 0 auto;
    padding: 20px;
    line-height: 1.6;
}
h1 {
    color: #333;
    border-bottom: 2px solid #007bff;
    padding-bottom: 10px;
}
h2 {
    color: #555;
    margin-top: 30px;
}
code {
    background-color: #f8f9fa;
    padding: 2px 6px;
    border-radius: 3px;
    font-family: 'Courier New', monospace;
}
</style> 
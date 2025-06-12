<?php
require_once('app/config/database.php');
?>
<!DOCTYPE html>
<html>
<head>
    <title>Sửa lỗi dữ liệu Account</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        .result { background: #f0f0f0; padding: 10px; margin: 10px 0; border-radius: 5px; }
        .success { background: #d4edda; color: #155724; }
        .error { background: #f8d7da; color: #721c24; }
    </style>
</head>
<body>
    <h1>Sửa lỗi dữ liệu Account</h1>
    
<?php
if (isset($_GET['fix'])) {
    echo "<h2>Bắt đầu sửa lỗi dữ liệu account...</h2>";
    
    $db = (new Database())->getConnection();
    
    // Lấy các records bị lỗi
    $query = "SELECT id, username, fullname, email, password FROM account WHERE email LIKE '$2y$%'";
    $stmt = $db->prepare($query);
    $stmt->execute();
    $corruptedAccounts = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo "<div class='result'>Tìm thấy " . count($corruptedAccounts) . " tài khoản bị lỗi:</div>";
    
    foreach ($corruptedAccounts as $account) {
        echo "<div class='result'>";
        echo "<strong>ID: {$account['id']}, Username: {$account['username']}</strong><br>";
        echo "- Fullname hiện tại: {$account['fullname']}<br>";
        echo "- Email hiện tại (bị lỗi): " . substr($account['email'], 0, 20) . "...<br>";
        echo "- Password hiện tại (bị lỗi): " . substr($account['password'], 0, 20) . "...<br>";
        
        // Dữ liệu đúng
        $correctFullname = $account['username']; // Tạm thời dùng username làm fullname
        $correctEmail = $account['fullname']; // Email thực tế đang ở trường fullname
        $correctPassword = $account['email']; // Password thực tế đang ở trường email
        
        // Cập nhật
        $updateQuery = "UPDATE account SET fullname = :fullname, email = :email, password = :password WHERE id = :id";
        $updateStmt = $db->prepare($updateQuery);
        $updateStmt->bindParam(':fullname', $correctFullname);
        $updateStmt->bindParam(':email', $correctEmail);
        $updateStmt->bindParam(':password', $correctPassword);
        $updateStmt->bindParam(':id', $account['id']);
        
        if ($updateStmt->execute()) {
            echo "<div class='success'>✅ Đã sửa thành công ID: {$account['id']}<br>";
            echo "- Fullname mới: {$correctFullname}<br>";
            echo "- Email mới: {$correctEmail}<br>";
            echo "- Password đã được khôi phục</div>";
        } else {
            echo "<div class='error'>❌ Lỗi khi sửa ID: {$account['id']}</div>";
        }
        echo "</div>";
    }
    
    echo "<div class='result success'><strong>Hoàn thành!</strong></div>";
    echo "<p><a href='admin_fix.php'>Quay lại</a> | <a href='admin_fix.php?check=1'>Kiểm tra lại</a></p>";
    
} elseif (isset($_GET['check'])) {
    echo "<h2>Kiểm tra trạng thái tài khoản</h2>";
    
    $db = (new Database())->getConnection();
    $query = "SELECT id, username, fullname, email, LEFT(password, 20) as password_preview FROM account ORDER BY id";
    $stmt = $db->prepare($query);
    $stmt->execute();
    $accounts = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo "<table border='1' cellpadding='5' cellspacing='0'>";
    echo "<tr><th>ID</th><th>Username</th><th>Fullname</th><th>Email</th><th>Password Preview</th></tr>";
    
    foreach ($accounts as $account) {
        echo "<tr>";
        echo "<td>{$account['id']}</td>";
        echo "<td>{$account['username']}</td>";
        echo "<td>{$account['fullname']}</td>";
        echo "<td>{$account['email']}</td>";
        echo "<td>{$account['password_preview']}...</td>";
        echo "</tr>";
    }
    echo "</table>";
    
    echo "<p><a href='admin_fix.php'>Quay lại</a></p>";
    
} else {
?>
    <p>Công cụ này sẽ sửa lỗi dữ liệu account bị lưu sai do bug thứ tự tham số.</p>
    <p><strong>Vấn đề:</strong> Email và password bị lưu sai vị trí, khiến đăng nhập thất bại.</p>
    
    <p><a href="admin_fix.php?check=1" style="background: #007bff; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px;">Kiểm tra tài khoản</a></p>
    <p><a href="admin_fix.php?fix=1" style="background: #28a745; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px;">Sửa lỗi ngay</a></p>
    
<?php } ?>

</body>
</html> 
<?php
require_once('app/config/database.php');

echo "Bắt đầu sửa lỗi dữ liệu account...\n";

$db = (new Database())->getConnection();

// Lấy các records bị lỗi
$query = "SELECT id, username, fullname, email, password FROM account WHERE email LIKE '$2y$%'";
$stmt = $db->prepare($query);
$stmt->execute();
$corruptedAccounts = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo "Tìm thấy " . count($corruptedAccounts) . " tài khoản bị lỗi:\n";

foreach ($corruptedAccounts as $account) {
    echo "ID: {$account['id']}, Username: {$account['username']}\n";
    echo "  - Fullname hiện tại: {$account['fullname']}\n";
    echo "  - Email hiện tại: {$account['email']}\n";
    echo "  - Password hiện tại: {$account['password']}\n";
    
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
        echo "  ✅ Đã sửa thành công ID: {$account['id']}\n";
        echo "  - Fullname mới: {$correctFullname}\n";
        echo "  - Email mới: {$correctEmail}\n";
        echo "  - Password đã được khôi phục\n\n";
    } else {
        echo "  ❌ Lỗi khi sửa ID: {$account['id']}\n\n";
    }
}

echo "Hoàn thành!\n";
?> 
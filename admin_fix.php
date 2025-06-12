<?php
require_once('app/config/database.php');
?>
<!DOCTYPE html>
<html>
<head>
    <title>Sửa lỗi dữ liệu Account & Thêm tính năng Disable</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        .result { background: #f0f0f0; padding: 10px; margin: 10px 0; border-radius: 5px; }
        .success { background: #d4edda; color: #155724; }
        .error { background: #f8d7da; color: #721c24; }
        .warning { background: #fff3cd; color: #856404; }
        table { border-collapse: collapse; width: 100%; margin: 10px 0; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
        .btn { background: #007bff; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px; margin: 5px; }
        .btn-success { background: #28a745; }
        .btn-warning { background: #ffc107; color: black; }
        .btn-danger { background: #dc3545; }
    </style>
</head>
<body>
    <h1>Admin Tools - Sửa lỗi Account & Quản lý trạng thái</h1>
    
<?php
$db = (new Database())->getConnection();

if (isset($_GET['add_status_column'])) {
    echo "<h2>Thêm cột status cho bảng account...</h2>";
    
    try {
        // Kiểm tra xem cột status đã tồn tại chưa
        $checkQuery = "SHOW COLUMNS FROM account LIKE 'status'";
        $checkStmt = $db->prepare($checkQuery);
        $checkStmt->execute();
        
        if ($checkStmt->rowCount() > 0) {
            echo "<div class='result warning'>⚠️ Cột 'status' đã tồn tại trong bảng account!</div>";
        } else {
            $addColumnQuery = "ALTER TABLE account ADD COLUMN status ENUM('active', 'disabled') DEFAULT 'active'";
            $addStmt = $db->prepare($addColumnQuery);
            if ($addStmt->execute()) {
                echo "<div class='result success'>✅ Đã thêm cột 'status' thành công!</div>";
            } else {
                echo "<div class='result error'>❌ Lỗi khi thêm cột 'status'</div>";
            }
        }
    } catch (Exception $e) {
        echo "<div class='result error'>❌ Lỗi: " . $e->getMessage() . "</div>";
    }
    
    echo "<p><a href='admin_fix.php' class='btn'>Quay lại</a></p>";

} elseif (isset($_GET['fix'])) {
    echo "<h2>Bắt đầu sửa lỗi dữ liệu account...</h2>";
    
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
    echo "<p><a href='admin_fix.php' class='btn'>Quay lại</a> | <a href='admin_fix.php?check=1' class='btn'>Kiểm tra lại</a></p>";
    
} elseif (isset($_GET['toggle_status']) && isset($_GET['user_id'])) {
    $userId = (int)$_GET['user_id'];
    $newStatus = $_GET['status'] ?? 'disabled';
    
    echo "<h2>Cập nhật trạng thái tài khoản</h2>";
    
    try {
        $updateQuery = "UPDATE account SET status = :status WHERE id = :id";
        $updateStmt = $db->prepare($updateQuery);
        $updateStmt->bindParam(':status', $newStatus);
        $updateStmt->bindParam(':id', $userId);
        
        if ($updateStmt->execute()) {
            echo "<div class='result success'>✅ Đã cập nhật trạng thái tài khoản ID {$userId} thành '{$newStatus}'</div>";
        } else {
            echo "<div class='result error'>❌ Lỗi khi cập nhật trạng thái</div>";
        }
    } catch (Exception $e) {
        echo "<div class='result error'>❌ Lỗi: " . $e->getMessage() . "</div>";
    }
    
    echo "<p><a href='admin_fix.php?check=1' class='btn'>Quay lại danh sách</a></p>";
    
} elseif (isset($_GET['check'])) {
    echo "<h2>Kiểm tra trạng thái tài khoản</h2>";
    
    // Kiểm tra xem cột status có tồn tại không
    $checkStatusColumn = "SHOW COLUMNS FROM account LIKE 'status'";
    $checkStmt = $db->prepare($checkStatusColumn);
    $checkStmt->execute();
    $hasStatusColumn = $checkStmt->rowCount() > 0;
    
    if ($hasStatusColumn) {
        $query = "SELECT id, username, fullname, email, status, LEFT(password, 20) as password_preview FROM account ORDER BY id";
    } else {
        $query = "SELECT id, username, fullname, email, LEFT(password, 20) as password_preview FROM account ORDER BY id";
        echo "<div class='result warning'>⚠️ Cột 'status' chưa tồn tại. <a href='admin_fix.php?add_status_column=1' class='btn btn-warning'>Thêm cột status</a></div>";
    }
    
    $stmt = $db->prepare($query);
    $stmt->execute();
    $accounts = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo "<table>";
    echo "<tr><th>ID</th><th>Username</th><th>Fullname</th><th>Email</th>";
    if ($hasStatusColumn) echo "<th>Status</th><th>Actions</th>";
    echo "<th>Password Preview</th></tr>";
    
    foreach ($accounts as $account) {
        echo "<tr>";
        echo "<td>{$account['id']}</td>";
        echo "<td>{$account['username']}</td>";
        echo "<td>{$account['fullname']}</td>";
        echo "<td>{$account['email']}</td>";
        
        if ($hasStatusColumn) {
            $status = $account['status'] ?? 'active';
            $statusColor = $status === 'active' ? 'green' : 'red';
            echo "<td style='color: {$statusColor}; font-weight: bold;'>{$status}</td>";
            
            echo "<td>";
            if ($account['role'] !== 'admin') { // Không cho disable admin
                if ($status === 'active') {
                    echo "<a href='admin_fix.php?toggle_status=1&user_id={$account['id']}&status=disabled' class='btn btn-danger' onclick='return confirm(\"Bạn có chắc muốn vô hiệu hóa tài khoản này?\")'>Disable</a>";
                } else {
                    echo "<a href='admin_fix.php?toggle_status=1&user_id={$account['id']}&status=active' class='btn btn-success'>Enable</a>";
                }
            } else {
                echo "<em>Admin account</em>";
            }
            echo "</td>";
        }
        
        echo "<td>{$account['password_preview']}...</td>";
        echo "</tr>";
    }
    echo "</table>";
    
    echo "<p><a href='admin_fix.php' class='btn'>Quay lại</a></p>";
    
} else {
?>
    <div class="result">
        <h3>🔧 Công cụ Admin</h3>
        <p>Trang này cung cấp các công cụ để:</p>
        <ul>
            <li><strong>Sửa lỗi dữ liệu account:</strong> Email và password bị lưu sai vị trí</li>
            <li><strong>Quản lý trạng thái tài khoản:</strong> Enable/Disable user accounts</li>
        </ul>
    </div>
    
    <h3>🛠️ Sửa lỗi dữ liệu</h3>
    <p><a href="admin_fix.php?check=1" class="btn">Kiểm tra tài khoản</a></p>
    <p><a href="admin_fix.php?fix=1" class="btn btn-success">Sửa lỗi ngay</a></p>
    
    <h3>👥 Quản lý trạng thái tài khoản</h3>
    <p><a href="admin_fix.php?add_status_column=1" class="btn btn-warning">Thêm cột Status</a></p>
    <p><a href="admin_fix.php?check=1" class="btn">Xem & Quản lý Users</a></p>
    
<?php } ?>

</body>
</html> 
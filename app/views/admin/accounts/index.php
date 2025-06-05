<?php include_once 'app/views/shares/header.php'; ?>

<!-- Error/Success Messages -->
<?php if (isset($_GET['success'])): ?>
<div class="alert alert-success alert-dismissible fade show" role="alert">
    <i class="bi bi-check-circle me-2"></i>
    <?php 
    switch($_GET['success']) {
        case 'created': echo 'Tạo tài khoản thành công!'; break;
        case 'updated': echo 'Cập nhật tài khoản thành công!'; break;
        case 'deleted': echo 'Xóa tài khoản thành công!'; break;
        default: echo 'Thao tác thành công!';
    }
    ?>
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
<?php endif; ?>

<?php if (isset($_GET['error'])): ?>
<div class="alert alert-danger alert-dismissible fade show" role="alert">
    <i class="bi bi-exclamation-triangle me-2"></i>
    <?php 
    switch($_GET['error']) {
        case 'cannot_delete_self': echo 'Không thể xóa tài khoản hiện tại!'; break;
        case 'delete_failed': echo 'Lỗi khi xóa tài khoản!'; break;
        case 'exception': echo 'Lỗi hệ thống!'; break;
        default: echo 'Đã xảy ra lỗi!';
    }
    ?>
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
<?php endif; ?>

<?php if (!empty($errors)): ?>
<div class="alert alert-danger alert-dismissible fade show" role="alert">
    <strong><i class="bi bi-exclamation-triangle me-2"></i>Có lỗi xảy ra:</strong>
    <ul class="mb-0 mt-2">
        <?php foreach ($errors as $error): ?>
            <li><?php echo htmlspecialchars($error, ENT_QUOTES, 'UTF-8'); ?></li>
        <?php endforeach; ?>
    </ul>
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
<?php endif; ?>

<!-- Header -->
<div class="mb-4">
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h1 class="display-5 fw-bold text-primary mb-2">
                <i class="bi bi-people me-3"></i>Quản lý tài khoản
            </h1>
            <p class="lead text-muted">Quản lý tài khoản người dùng và phân quyền</p>
        </div>
        <a href="/webbanhang/admin/accounts/create" class="btn btn-success btn-lg">
            <i class="bi bi-plus-circle me-2"></i>Tạo tài khoản mới
        </a>
    </div>
</div>

<!-- Statistics Cards -->
<div class="row mb-5">
    <div class="col-lg-3 col-md-6 mb-4">
        <div class="card border-0 shadow-sm h-100 bg-gradient stats-blue">
            <div class="card-body text-white text-center">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h2 class="fw-bold mb-0"><?php echo $accountStats['total_accounts'] ?? 0; ?></h2>
                        <p class="mb-0 opacity-75">Tổng tài khoản</p>
                    </div>
                    <i class="bi bi-people fs-1 opacity-75"></i>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-lg-3 col-md-6 mb-4">
        <div class="card border-0 shadow-sm h-100 bg-gradient stats-green">
            <div class="card-body text-white text-center">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h2 class="fw-bold mb-0"><?php echo $accountStats['admin_accounts'] ?? 0; ?></h2>
                        <p class="mb-0 opacity-75">Quản trị viên</p>
                    </div>
                    <i class="bi bi-shield-check fs-1 opacity-75"></i>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-lg-3 col-md-6 mb-4">
        <div class="card border-0 shadow-sm h-100 bg-gradient stats-orange">
            <div class="card-body text-white text-center">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h2 class="fw-bold mb-0"><?php echo $accountStats['user_accounts'] ?? 0; ?></h2>
                        <p class="mb-0 opacity-75">Người dùng</p>
                    </div>
                    <i class="bi bi-person fs-1 opacity-75"></i>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-lg-3 col-md-6 mb-4">
        <div class="card border-0 shadow-sm h-100 bg-gradient stats-purple">
            <div class="card-body text-white text-center">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h2 class="fw-bold mb-0"><?php echo $accountStats['recent_registrations'] ?? 0; ?></h2>
                        <p class="mb-0 opacity-75">Đăng ký tuần này</p>
                    </div>
                    <i class="bi bi-person-plus fs-1 opacity-75"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Accounts Table -->
<div class="card shadow-sm border-0">
    <div class="card-header bg-white border-0 pb-0">
        <h4 class="card-title mb-0">
            <i class="bi bi-table me-2 text-primary"></i>Danh sách tài khoản
        </h4>
    </div>
    <div class="card-body">
        <?php if (!empty($accounts)): ?>
        <div class="table-responsive">
            <table class="table table-hover">
                <thead class="table-light">
                    <tr>
                        <th>ID</th>
                        <th>Tên đăng nhập</th>
                        <th>Họ tên</th>
                        <th>Email</th>
                        <th>Điện thoại</th>
                        <th>Vai trò</th>
                        <th>Ngày tạo</th>
                        <th>Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($accounts as $account): ?>
                    <tr>
                        <td class="fw-bold">#<?php echo htmlspecialchars($account['id']); ?></td>
                        <td>
                            <span class="fw-medium"><?php echo htmlspecialchars($account['username']); ?></span>
                        </td>
                        <td>
                            <?php echo htmlspecialchars($account['fullname'] ?? 'Chưa cập nhật'); ?>
                        </td>
                        <td>
                            <?php if (!empty($account['email'])): ?>
                                <a href="mailto:<?php echo htmlspecialchars($account['email']); ?>" class="text-decoration-none">
                                    <?php echo htmlspecialchars($account['email']); ?>
                                </a>
                            <?php else: ?>
                                <span class="text-muted">Chưa có</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <?php if (!empty($account['phone'])): ?>
                                <a href="tel:<?php echo htmlspecialchars($account['phone']); ?>" class="text-decoration-none">
                                    <?php echo htmlspecialchars($account['phone']); ?>
                                </a>
                            <?php else: ?>
                                <span class="text-muted">Chưa có</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <?php if ($account['role'] === 'admin'): ?>
                                <span class="badge bg-warning text-dark">
                                    <i class="bi bi-shield-check me-1"></i>Admin
                                </span>
                            <?php else: ?>
                                <span class="badge bg-info">
                                    <i class="bi bi-person me-1"></i>User
                                </span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <small class="text-muted">
                                <?php echo date('d/m/Y H:i', strtotime($account['created_at'])); ?>
                            </small>
                        </td>
                        <td>
                            <div class="btn-group" role="group">
                                <a href="/webbanhang/admin/accounts/edit/<?php echo $account['id']; ?>" 
                                   class="btn btn-sm btn-outline-primary" title="Chỉnh sửa">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <?php if ($account['id'] != SessionHelper::getUserId()): ?>
                                <button type="button" class="btn btn-sm btn-outline-danger" 
                                        onclick="confirmDelete(<?php echo $account['id']; ?>, '<?php echo htmlspecialchars($account['username']); ?>')"
                                        title="Xóa">
                                    <i class="bi bi-trash"></i>
                                </button>
                                <?php else: ?>
                                <button type="button" class="btn btn-sm btn-outline-secondary" disabled title="Không thể xóa chính mình">
                                    <i class="bi bi-trash"></i>
                                </button>
                                <?php endif; ?>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <?php else: ?>
        <div class="text-center py-5">
            <i class="bi bi-people text-muted" style="font-size: 4rem;"></i>
            <h4 class="text-muted mt-3">Chưa có tài khoản nào</h4>
            <p class="text-muted">Bắt đầu bằng cách tạo tài khoản mới.</p>
            <a href="/webbanhang/admin/accounts/create" class="btn btn-primary">
                <i class="bi bi-plus-circle me-2"></i>Tạo tài khoản đầu tiên
            </a>
        </div>
        <?php endif; ?>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="bi bi-exclamation-triangle text-danger me-2"></i>Xác nhận xóa
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>Bạn có chắc chắn muốn xóa tài khoản <strong id="deleteUsername"></strong>?</p>
                <p class="text-danger"><small>
                    <i class="bi bi-warning me-1"></i>Hành động này không thể hoàn tác!
                </small></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                <a href="#" id="deleteLink" class="btn btn-danger">
                    <i class="bi bi-trash me-2"></i>Xóa tài khoản
                </a>
            </div>
        </div>
    </div>
</div>

<script>
function confirmDelete(accountId, username) {
    document.getElementById('deleteUsername').textContent = username;
    document.getElementById('deleteLink').href = '/webbanhang/admin/accounts/delete/' + accountId;
    new bootstrap.Modal(document.getElementById('deleteModal')).show();
}
</script>

<style>
.stats-blue {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
}

.stats-green {
    background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
}

.stats-orange {
    background: linear-gradient(135deg, #fa709a 0%, #fee140 100%);
}

.stats-purple {
    background: linear-gradient(135deg, #a8edea 0%, #fed6e3 100%);
}

.table th {
    border-top: none;
    font-weight: 600;
    color: #495057;
    font-size: 0.875rem;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.btn-group .btn {
    margin: 0 1px;
}

.card-header {
    padding: 1.5rem 1.5rem 0.5rem;
}

/* Gradient colors for statistics cards */
.bg-gradient {
    background-size: 200% 200%;
    animation: gradientShift 3s ease infinite;
}

@keyframes gradientShift {
    0% { background-position: 0% 50%; }
    50% { background-position: 100% 50%; }
    100% { background-position: 0% 50%; }
}

.stats-blue {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%) !important;
}

.stats-green {
    background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%) !important;
}

.stats-orange {
    background: linear-gradient(135deg, #fa709a 0%, #fee140 100%) !important;
}

.stats-purple {
    background: linear-gradient(135deg, #a8edea 0%, #fed6e3 100%) !important;
}
</style>

<?php include_once 'app/views/shares/footer.php'; ?> 
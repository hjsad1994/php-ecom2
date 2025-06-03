<?php include_once 'app/views/shares/header.php'; ?>

<div class="row mb-4">
    <div class="col-md-8">
        <h1 class="display-6 fw-bold text-primary">
            <i class="bi bi-person-circle me-2"></i>Hồ sơ cá nhân
        </h1>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="/webbanhang/" class="text-decoration-none">Trang chủ</a></li>
                <li class="breadcrumb-item active">Hồ sơ</li>
            </ol>
        </nav>
    </div>
    <div class="col-md-4 text-end">
        <button class="btn btn-outline-secondary" onclick="toggleEditMode()">
            <i class="bi bi-pencil me-2"></i>Chỉnh sửa thông tin
        </button>
    </div>
</div>

<div class="row">
    <!-- Profile Sidebar -->
    <div class="col-lg-3">
        <div class="card shadow-sm">
            <div class="card-body text-center">
                <!-- Profile Picture -->
                <div class="position-relative mb-3">
                    <img src="/webbanhang/public/images/default-avatar.jpg" 
                         class="rounded-circle" 
                         style="width: 120px; height: 120px; object-fit: cover;"
                         alt="Profile Picture"
                         id="profilePicture">
                    <div class="position-absolute bottom-0 end-0">
                        <button class="btn btn-primary btn-sm rounded-circle" 
                                style="width: 35px; height: 35px;" 
                                onclick="changeProfilePicture()">
                            <i class="bi bi-camera"></i>
                        </button>
                    </div>
                </div>
                
                <!-- User Info -->
                <h5 class="fw-bold"><?php echo htmlspecialchars(SessionHelper::getUsername(), ENT_QUOTES, 'UTF-8'); ?></h5>
                <p class="text-muted small">Thành viên từ: <?php echo date('m/Y'); ?></p>
                
                <!-- Profile Stats -->
                <div class="row text-center">
                    <div class="col-4">
                        <div class="fw-bold text-primary">0</div>
                        <small class="text-muted">Đơn hàng</small>
                    </div>
                    <div class="col-4">
                        <div class="fw-bold text-success">0đ</div>
                        <small class="text-muted">Đã mua</small>
                    </div>
                    <div class="col-4">
                        <div class="fw-bold text-warning">0</div>
                        <small class="text-muted">Điểm tích lũy</small>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="card shadow-sm mt-4">
            <div class="card-header">
                <h6 class="mb-0"><i class="bi bi-lightning me-2"></i>Thao tác nhanh</h6>
            </div>
            <div class="list-group list-group-flush">
                <a href="/webbanhang/user/orders" class="list-group-item list-group-item-action">
                    <i class="bi bi-receipt me-2 text-primary"></i>Đơn hàng của tôi
                </a>
                <a href="/webbanhang/user/cart" class="list-group-item list-group-item-action">
                    <i class="bi bi-cart me-2 text-success"></i>Giỏ hàng
                </a>
                <a href="/webbanhang/user/products" class="list-group-item list-group-item-action">
                    <i class="bi bi-heart me-2 text-danger"></i>Sản phẩm yêu thích
                </a>
                <a href="#changePassword" class="list-group-item list-group-item-action">
                    <i class="bi bi-shield-lock me-2 text-warning"></i>Đổi mật khẩu
                </a>
            </div>
        </div>
    </div>

    <!-- Profile Content -->
    <div class="col-lg-9">
        <!-- Profile Information Form -->
        <div class="card shadow-sm">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0"><i class="bi bi-person-lines-fill me-2"></i>Thông tin cá nhân</h5>
                <span class="badge bg-success">Đã xác minh</span>
            </div>
            <div class="card-body">
                <form id="profileForm">
                    <div class="row">
                        <!-- Basic Information -->
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-bold">Tên đăng nhập</label>
                                <input type="text" class="form-control" 
                                       value="<?php echo htmlspecialchars(SessionHelper::getUsername(), ENT_QUOTES, 'UTF-8'); ?>" 
                                       readonly>
                                <small class="text-muted">Tên đăng nhập không thể thay đổi</small>
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label fw-bold">Họ và tên <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="full_name" 
                                       value="Nguyễn Văn A" disabled>
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label fw-bold">Email <span class="text-danger">*</span></label>
                                <input type="email" class="form-control" name="email" 
                                       value="nguyenvana@example.com" disabled>
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label fw-bold">Số điện thoại</label>
                                <input type="tel" class="form-control" name="phone" 
                                       value="0123456789" disabled>
                            </div>
                        </div>
                        
                        <!-- Additional Information -->
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-bold">Ngày sinh</label>
                                <input type="date" class="form-control" name="birthday" 
                                       value="1990-01-01" disabled>
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label fw-bold">Giới tính</label>
                                <select class="form-select" name="gender" disabled>
                                    <option value="">Chưa chọn</option>
                                    <option value="male" selected>Nam</option>
                                    <option value="female">Nữ</option>
                                    <option value="other">Khác</option>
                                </select>
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label fw-bold">Địa chỉ</label>
                                <textarea class="form-control" name="address" rows="2" disabled>123 Đường ABC, Quận 1, TP.HCM</textarea>
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label fw-bold">Giới thiệu bản thân</label>
                                <textarea class="form-control" name="bio" rows="2" disabled 
                                          placeholder="Viết vài dòng về bản thân..."></textarea>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Form Actions -->
                    <div class="text-end" id="formActions" style="display: none;">
                        <button type="button" class="btn btn-outline-secondary me-2" onclick="cancelEdit()">
                            <i class="bi bi-x-lg me-1"></i>Hủy
                        </button>
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-check-lg me-1"></i>Lưu thay đổi
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Password Change Section -->
        <div class="card shadow-sm mt-4" id="changePassword">
            <div class="card-header">
                <h5 class="mb-0"><i class="bi bi-shield-lock me-2"></i>Đổi mật khẩu</h5>
            </div>
            <div class="card-body">
                <form id="passwordForm">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label class="form-label fw-bold">Mật khẩu hiện tại <span class="text-danger">*</span></label>
                                <input type="password" class="form-control" name="current_password" required>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label class="form-label fw-bold">Mật khẩu mới <span class="text-danger">*</span></label>
                                <input type="password" class="form-control" name="new_password" required>
                                <small class="text-muted">Ít nhất 6 ký tự</small>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label class="form-label fw-bold">Xác nhận mật khẩu mới <span class="text-danger">*</span></label>
                                <input type="password" class="form-control" name="confirm_password" required>
                            </div>
                        </div>
                    </div>
                    
                    <div class="text-end">
                        <button type="submit" class="btn btn-warning">
                            <i class="bi bi-shield-check me-1"></i>Đổi mật khẩu
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Account Settings -->
        <div class="card shadow-sm mt-4">
            <div class="card-header">
                <h5 class="mb-0"><i class="bi bi-gear me-2"></i>Cài đặt tài khoản</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <h6 class="fw-bold mb-3">Thông báo</h6>
                        <div class="form-check form-switch mb-2">
                            <input class="form-check-input" type="checkbox" id="emailNotif" checked>
                            <label class="form-check-label" for="emailNotif">
                                Nhận thông báo qua email
                            </label>
                        </div>
                        <div class="form-check form-switch mb-2">
                            <input class="form-check-input" type="checkbox" id="smsNotif">
                            <label class="form-check-label" for="smsNotif">
                                Nhận thông báo qua SMS
                            </label>
                        </div>
                        <div class="form-check form-switch mb-2">
                            <input class="form-check-input" type="checkbox" id="orderNotif" checked>
                            <label class="form-check-label" for="orderNotif">
                                Thông báo đơn hàng
                            </label>
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <h6 class="fw-bold mb-3">Bảo mật</h6>
                        <div class="form-check form-switch mb-2">
                            <input class="form-check-input" type="checkbox" id="twoFactor">
                            <label class="form-check-label" for="twoFactor">
                                Xác thực 2 bước
                            </label>
                        </div>
                        <div class="form-check form-switch mb-2">
                            <input class="form-check-input" type="checkbox" id="loginNotif" checked>
                            <label class="form-check-label" for="loginNotif">
                                Thông báo đăng nhập
                            </label>
                        </div>
                        
                        <div class="mt-3">
                            <button class="btn btn-outline-danger btn-sm" onclick="downloadData()">
                                <i class="bi bi-download me-1"></i>Tải dữ liệu cá nhân
                            </button>
                        </div>
                    </div>
                </div>
                
                <hr>
                
                <!-- Danger Zone -->
                <div class="alert alert-danger">
                    <h6 class="alert-heading"><i class="bi bi-exclamation-triangle me-2"></i>Vùng nguy hiểm</h6>
                    <p class="mb-2">Hành động này không thể hoàn tác. Vui lòng cân nhắc kỹ trước khi thực hiện.</p>
                    <button class="btn btn-outline-danger btn-sm" onclick="confirmDeleteAccount()">
                        <i class="bi bi-trash me-1"></i>Xóa tài khoản
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Profile Picture Modal -->
<div class="modal fade" id="profilePictureModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Thay đổi ảnh đại diện</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="text-center mb-3">
                    <img id="previewImage" src="/webbanhang/public/images/default-avatar.jpg" 
                         class="rounded-circle" style="width: 150px; height: 150px; object-fit: cover;">
                </div>
                <div class="mb-3">
                    <label class="form-label">Chọn ảnh mới:</label>
                    <input type="file" class="form-control" id="imageUpload" accept="image/*">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                <button type="button" class="btn btn-primary" onclick="uploadProfilePicture()">Lưu</button>
            </div>
        </div>
    </div>
</div>

<style>
.card {
    border: none;
    border-radius: 12px;
}

.form-control:disabled, .form-select:disabled {
    background-color: #f8f9fa;
    border-color: #e9ecef;
}

.list-group-item {
    border: none;
    padding: 0.75rem 1rem;
}

.list-group-item:hover {
    background-color: #f8f9fa;
}

.form-check-input:checked {
    background-color: #0d6efd;
    border-color: #0d6efd;
}
</style>

<script>
let editMode = false;

// Toggle edit mode
function toggleEditMode() {
    editMode = !editMode;
    const formInputs = document.querySelectorAll('#profileForm input:not([readonly]), #profileForm select, #profileForm textarea');
    const formActions = document.getElementById('formActions');
    
    formInputs.forEach(input => {
        input.disabled = !editMode;
    });
    
    formActions.style.display = editMode ? 'block' : 'none';
    
    // Update button text
    const editButton = document.querySelector('button[onclick="toggleEditMode()"]');
    if (editMode) {
        editButton.innerHTML = '<i class="bi bi-x-lg me-2"></i>Hủy chỉnh sửa';
        editButton.className = 'btn btn-outline-danger';
    } else {
        editButton.innerHTML = '<i class="bi bi-pencil me-2"></i>Chỉnh sửa thông tin';
        editButton.className = 'btn btn-outline-secondary';
    }
}

// Cancel edit
function cancelEdit() {
    toggleEditMode();
    // Reset form to original values
    document.getElementById('profileForm').reset();
}

// Profile form submission
document.getElementById('profileForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    
    fetch('/webbanhang/account/update-profile', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('Cập nhật thông tin thành công!');
            toggleEditMode();
        } else {
            alert(data.message || 'Có lỗi xảy ra khi cập nhật thông tin');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Có lỗi xảy ra khi cập nhật thông tin');
    });
});

// Password form submission
document.getElementById('passwordForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    
    // Check if new passwords match
    if (formData.get('new_password') !== formData.get('confirm_password')) {
        alert('Mật khẩu mới không khớp!');
        return;
    }
    
    fetch('/webbanhang/account/change-password', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('Đổi mật khẩu thành công!');
            this.reset();
        } else {
            alert(data.message || 'Có lỗi xảy ra khi đổi mật khẩu');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Có lỗi xảy ra khi đổi mật khẩu');
    });
});

// Profile picture functions
function changeProfilePicture() {
    const modal = new bootstrap.Modal(document.getElementById('profilePictureModal'));
    modal.show();
}

// Image preview
document.getElementById('imageUpload').addEventListener('change', function(e) {
    const file = e.target.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById('previewImage').src = e.target.result;
        };
        reader.readAsDataURL(file);
    }
});

function uploadProfilePicture() {
    const fileInput = document.getElementById('imageUpload');
    const file = fileInput.files[0];
    
    if (!file) {
        alert('Vui lòng chọn ảnh!');
        return;
    }
    
    const formData = new FormData();
    formData.append('profile_picture', file);
    
    fetch('/webbanhang/account/upload-avatar', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            document.getElementById('profilePicture').src = data.avatar_url;
            bootstrap.Modal.getInstance(document.getElementById('profilePictureModal')).hide();
            alert('Cập nhật ảnh đại diện thành công!');
        } else {
            alert(data.message || 'Có lỗi xảy ra khi tải ảnh lên');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Có lỗi xảy ra khi tải ảnh lên');
    });
}

// Download personal data
function downloadData() {
    if (confirm('Bạn muốn tải xuống dữ liệu cá nhân?')) {
        window.open('/webbanhang/account/download-data', '_blank');
    }
}

// Delete account
function confirmDeleteAccount() {
    if (confirm('Bạn có chắc chắn muốn xóa tài khoản? Hành động này không thể hoàn tác!')) {
        if (confirm('Xác nhận lần cuối: Tất cả dữ liệu sẽ bị xóa vĩnh viễn!')) {
            fetch('/webbanhang/account/delete-account', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('Tài khoản đã được xóa. Bạn sẽ được chuyển về trang chủ.');
                    window.location.href = '/webbanhang/';
                } else {
                    alert(data.message || 'Có lỗi xảy ra khi xóa tài khoản');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Có lỗi xảy ra khi xóa tài khoản');
            });
        }
    }
}

// Auto-save settings
document.querySelectorAll('.form-check-input').forEach(checkbox => {
    checkbox.addEventListener('change', function() {
        const setting = this.id;
        const value = this.checked;
        
        fetch('/webbanhang/account/update-settings', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({
                setting: setting,
                value: value
            })
        })
        .then(response => response.json())
        .then(data => {
            if (!data.success) {
                console.error('Failed to save setting:', data.message);
                // Revert the checkbox state
                this.checked = !value;
            }
        })
        .catch(error => {
            console.error('Error saving setting:', error);
            // Revert the checkbox state
            this.checked = !value;
        });
    });
});
</script>

<?php include_once 'app/views/shares/footer.php'; ?> 
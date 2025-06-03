<?php include_once 'app/views/shares/header.php'; ?>

<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="display-6 fw-bold text-primary mb-2">
                <i class="bi bi-pencil-square me-3"></i>Sửa voucher
            </h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="/webbanhang/admin/dashboard" class="text-decoration-none">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="/webbanhang/admin/vouchers" class="text-decoration-none">Voucher</a></li>
                    <li class="breadcrumb-item active">Sửa</li>
                </ol>
            </nav>
        </div>
        
        <a href="/webbanhang/admin/vouchers" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left me-2"></i>Quay lại
        </a>
    </div>

    <!-- Error Display -->
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

    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">
                        <i class="bi bi-ticket-perforated me-2"></i>Thông tin voucher
                    </h5>
                </div>
                <div class="card-body">
                    <form method="POST" action="/webbanhang/admin/vouchers/update/<?php echo $voucher['id']; ?>">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="code" class="form-label">
                                        <i class="bi bi-upc me-1"></i>Mã voucher <span class="text-danger">*</span>
                                    </label>
                                    <input type="text" 
                                           class="form-control <?php echo isset($errors['code']) ? 'is-invalid' : ''; ?>" 
                                           id="code" 
                                           name="code" 
                                           value="<?php echo htmlspecialchars($voucher['code'] ?? '', ENT_QUOTES, 'UTF-8'); ?>" 
                                           placeholder="Nhập mã voucher (VD: GIAMGIA10)" 
                                           required>
                                    <?php if (isset($errors['code'])): ?>
                                        <div class="invalid-feedback">
                                            <?php echo $errors['code']; ?>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="name" class="form-label">
                                        <i class="bi bi-tag me-1"></i>Tên voucher <span class="text-danger">*</span>
                                    </label>
                                    <input type="text" 
                                           class="form-control <?php echo isset($errors['name']) ? 'is-invalid' : ''; ?>" 
                                           id="name" 
                                           name="name" 
                                           value="<?php echo htmlspecialchars($voucher['name'] ?? '', ENT_QUOTES, 'UTF-8'); ?>" 
                                           placeholder="Nhập tên voucher" 
                                           required>
                                    <?php if (isset($errors['name'])): ?>
                                        <div class="invalid-feedback">
                                            <?php echo $errors['name']; ?>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label">
                                <i class="bi bi-card-text me-1"></i>Mô tả
                            </label>
                            <textarea class="form-control" 
                                      id="description" 
                                      name="description" 
                                      rows="3" 
                                      placeholder="Nhập mô tả cho voucher"><?php echo htmlspecialchars($voucher['description'] ?? '', ENT_QUOTES, 'UTF-8'); ?></textarea>
                        </div>

                        <div class="row">
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="discount_type" class="form-label">
                                        <i class="bi bi-percent me-1"></i>Loại giảm giá
                                    </label>
                                    <select class="form-select" id="discount_type" name="discount_type">
                                        <option value="percentage" <?php echo ($voucher['discount_type'] ?? '') === 'percentage' ? 'selected' : ''; ?>>Phần trăm (%)</option>
                                        <option value="fixed" <?php echo ($voucher['discount_type'] ?? '') === 'fixed' ? 'selected' : ''; ?>>Số tiền cố định (VNĐ)</option>
                                    </select>
                                </div>
                            </div>
                            
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="discount_value" class="form-label">
                                        <i class="bi bi-cash me-1"></i>Giá trị giảm <span class="text-danger">*</span>
                                    </label>
                                    <input type="number" 
                                           class="form-control <?php echo isset($errors['discount_value']) ? 'is-invalid' : ''; ?>" 
                                           id="discount_value" 
                                           name="discount_value" 
                                           value="<?php echo $voucher['discount_value'] ?? ''; ?>" 
                                           min="1" 
                                           placeholder="Nhập giá trị" 
                                           required>
                                    <?php if (isset($errors['discount_value'])): ?>
                                        <div class="invalid-feedback">
                                            <?php echo $errors['discount_value']; ?>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                            
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="max_discount_amount" class="form-label">
                                        <i class="bi bi-cash-coin me-1"></i>Giảm tối đa (VNĐ)
                                    </label>
                                    <input type="number" 
                                           class="form-control" 
                                           id="max_discount_amount" 
                                           name="max_discount_amount" 
                                           value="<?php echo $voucher['max_discount_amount'] ?? ''; ?>" 
                                           min="0" 
                                           placeholder="Không giới hạn">
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="min_order_amount" class="form-label">
                                        <i class="bi bi-cart me-1"></i>Giá trị đơn hàng tối thiểu (VNĐ)
                                    </label>
                                    <input type="number" 
                                           class="form-control" 
                                           id="min_order_amount" 
                                           name="min_order_amount" 
                                           value="<?php echo $voucher['min_order_amount'] ?? 0; ?>" 
                                           min="0" 
                                           placeholder="0">
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="usage_limit" class="form-label">
                                        <i class="bi bi-arrow-repeat me-1"></i>Giới hạn sử dụng
                                    </label>
                                    <input type="number" 
                                           class="form-control" 
                                           id="usage_limit" 
                                           name="usage_limit" 
                                           value="<?php echo $voucher['usage_limit'] ?? ''; ?>" 
                                           min="1" 
                                           placeholder="Không giới hạn">
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="start_date" class="form-label">
                                        <i class="bi bi-calendar me-1"></i>Ngày bắt đầu <span class="text-danger">*</span>
                                    </label>
                                    <input type="date" 
                                           class="form-control" 
                                           id="start_date" 
                                           name="start_date" 
                                           value="<?php echo date('Y-m-d', strtotime($voucher['start_date'] ?? 'now')); ?>" 
                                           required>
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="end_date" class="form-label">
                                        <i class="bi bi-calendar-x me-1"></i>Ngày kết thúc <span class="text-danger">*</span>
                                    </label>
                                    <input type="date" 
                                           class="form-control" 
                                           id="end_date" 
                                           name="end_date" 
                                           value="<?php echo date('Y-m-d', strtotime($voucher['end_date'] ?? '+1 month')); ?>" 
                                           required>
                                </div>
                            </div>
                        </div>

                        <div class="mb-4">
                            <div class="form-check">
                                <input class="form-check-input" 
                                       type="checkbox" 
                                       id="is_active" 
                                       name="is_active" 
                                       <?php echo ($voucher['is_active'] ?? 1) ? 'checked' : ''; ?>>
                                <label class="form-check-label" for="is_active">
                                    <i class="bi bi-toggle-on me-1"></i>Kích hoạt voucher
                                </label>
                            </div>
                        </div>

                        <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                            <a href="/webbanhang/admin/vouchers" class="btn btn-outline-secondary me-md-2">
                                <i class="bi bi-x-circle me-2"></i>Hủy
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-check-circle me-2"></i>Cập nhật voucher
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include_once 'app/views/shares/footer.php'; ?> 
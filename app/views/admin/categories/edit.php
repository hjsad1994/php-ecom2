<?php include_once 'app/views/shares/header.php'; ?>

<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="display-6 fw-bold text-primary mb-2">
                <i class="bi bi-pencil me-3"></i>Chỉnh sửa danh mục
            </h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="/webbanhang/admin/dashboard" class="text-decoration-none">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="/webbanhang/admin/categories" class="text-decoration-none">Danh mục</a></li>
                    <li class="breadcrumb-item active">Chỉnh sửa</li>
                </ol>
            </nav>
        </div>
        
        <div class="btn-group">
            <a href="/webbanhang/admin/categories" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left me-2"></i>Quay lại
            </a>
            <a href="/webbanhang/admin/categories/show/<?php echo $category->id; ?>" class="btn btn-outline-info">
                <i class="bi bi-eye me-2"></i>Xem chi tiết
            </a>
        </div>
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
        <div class="col-lg-8">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">
                        <i class="bi bi-tags me-2"></i>Thông tin danh mục
                    </h5>
                </div>
                <div class="card-body">
                    <form method="POST" action="/webbanhang/admin/categories/update/<?php echo $category->id; ?>">
                        <div class="mb-3">
                            <label for="name" class="form-label">
                                <i class="bi bi-tag me-1"></i>Tên danh mục <span class="text-danger">*</span>
                            </label>
                            <input type="text" 
                                   class="form-control <?php echo isset($errors['name']) ? 'is-invalid' : ''; ?>" 
                                   id="name" 
                                   name="name" 
                                   value="<?php echo htmlspecialchars($category->name ?? '', ENT_QUOTES, 'UTF-8'); ?>" 
                                   placeholder="Nhập tên danh mục" 
                                   required>
                            <?php if (isset($errors['name'])): ?>
                                <div class="invalid-feedback">
                                    <?php echo $errors['name']; ?>
                                </div>
                            <?php endif; ?>
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label">
                                <i class="bi bi-card-text me-1"></i>Mô tả danh mục
                            </label>
                            <textarea class="form-control" 
                                      id="description" 
                                      name="description" 
                                      rows="4" 
                                      placeholder="Nhập mô tả cho danh mục"><?php echo htmlspecialchars($category->description ?? '', ENT_QUOTES, 'UTF-8'); ?></textarea>
                            <div class="form-text">Có thể sử dụng HTML tags như &lt;p&gt;, &lt;br&gt;, &lt;strong&gt; để định dạng.</div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label text-muted">
                                <i class="bi bi-info-circle me-1"></i>Thông tin bổ sung
                            </label>
                            <div class="bg-light p-3 rounded">
                                <div class="row">
                                    <div class="col-md-6">
                                        <small class="text-muted">ID danh mục:</small>
                                        <code class="d-block">#<?php echo $category->id; ?></code>
                                    </div>
                                    <div class="col-md-6">
                                        <small class="text-muted">Ngày tạo:</small>
                                        <div class="fw-bold">
                                            <?php 
                                            if (isset($category->created_at)) {
                                                echo date('d/m/Y H:i', strtotime($category->created_at));
                                            } else {
                                                echo 'N/A';
                                            }
                                            ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                            <a href="/webbanhang/admin/categories" class="btn btn-outline-secondary me-md-2">
                                <i class="bi bi-x-circle me-2"></i>Hủy
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-check-circle me-2"></i>Cập nhật danh mục
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Quick Actions Card -->
            <div class="card shadow-sm border-0 mt-4">
                <div class="card-header bg-success text-white">
                    <h6 class="mb-0">
                        <i class="bi bi-lightning me-2"></i>Thao tác nhanh
                    </h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <a href="/webbanhang/admin/products/create?category_id=<?php echo $category->id; ?>" class="btn btn-outline-success w-100 mb-2">
                                <i class="bi bi-plus-circle me-2"></i>Thêm sản phẩm vào danh mục
                            </a>
                        </div>
                        <div class="col-md-6">
                            <a href="/webbanhang/admin/categories/show/<?php echo $category->id; ?>" class="btn btn-outline-info w-100 mb-2">
                                <i class="bi bi-eye me-2"></i>Xem chi tiết danh mục
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include_once 'app/views/shares/footer.php'; ?> 
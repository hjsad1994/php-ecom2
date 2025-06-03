<?php include_once 'app/views/shares/header.php'; ?>

<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="display-6 fw-bold text-primary mb-2">
                <i class="bi bi-pencil me-3"></i>Chỉnh sửa sản phẩm
            </h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="/webbanhang/admin/dashboard" class="text-decoration-none">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="/webbanhang/admin/products" class="text-decoration-none">Sản phẩm</a></li>
                    <li class="breadcrumb-item active">Chỉnh sửa</li>
                </ol>
            </nav>
        </div>
        
        <div class="btn-group">
            <a href="/webbanhang/admin/products" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left me-2"></i>Quay lại
            </a>
            <a href="/webbanhang/product/show/<?php echo $product->id; ?>" class="btn btn-outline-info" target="_blank">
                <i class="bi bi-eye me-2"></i>Xem sản phẩm
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
        <div class="col-lg-10">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">
                        <i class="bi bi-box me-2"></i>Thông tin sản phẩm
                    </h5>
                </div>
                <div class="card-body">
                    <form method="POST" action="/webbanhang/admin/products/update/<?php echo $product->id; ?>" enctype="multipart/form-data">
                        <div class="row">
                            <div class="col-md-8">
                                <div class="mb-3">
                                    <label for="name" class="form-label">
                                        <i class="bi bi-tag me-1"></i>Tên sản phẩm <span class="text-danger">*</span>
                                    </label>
                                    <input type="text" 
                                           class="form-control <?php echo isset($errors['name']) ? 'is-invalid' : ''; ?>" 
                                           id="name" 
                                           name="name" 
                                           value="<?php echo htmlspecialchars($product->name ?? '', ENT_QUOTES, 'UTF-8'); ?>" 
                                           placeholder="Nhập tên sản phẩm" 
                                           required>
                                    <?php if (isset($errors['name'])): ?>
                                        <div class="invalid-feedback">
                                            <?php echo $errors['name']; ?>
                                        </div>
                                    <?php endif; ?>
                                </div>

                                <div class="mb-3">
                                    <label for="description" class="form-label">
                                        <i class="bi bi-card-text me-1"></i>Mô tả sản phẩm
                                    </label>
                                    <textarea class="form-control" 
                                              id="description" 
                                              name="description" 
                                              rows="6" 
                                              placeholder="Nhập mô tả chi tiết cho sản phẩm"><?php echo $product->description ?? ''; ?></textarea>
                                    <div class="form-text">Có thể sử dụng HTML tags như &lt;p&gt;, &lt;br&gt;, &lt;strong&gt; để định dạng.</div>
                                </div>
                            </div>
                            
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="price" class="form-label">
                                        <i class="bi bi-currency-dollar me-1"></i>Giá sản phẩm <span class="text-danger">*</span>
                                    </label>
                                    <div class="input-group">
                                        <input type="number" 
                                               class="form-control <?php echo isset($errors['price']) ? 'is-invalid' : ''; ?>" 
                                               id="price" 
                                               name="price" 
                                               value="<?php echo $product->price ?? ''; ?>" 
                                               min="0" 
                                               step="1000"
                                               placeholder="0" 
                                               required>
                                        <span class="input-group-text">VNĐ</span>
                                        <?php if (isset($errors['price'])): ?>
                                            <div class="invalid-feedback">
                                                <?php echo $errors['price']; ?>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label for="category_id" class="form-label">
                                        <i class="bi bi-tags me-1"></i>Danh mục
                                    </label>
                                    <select class="form-select" id="category_id" name="category_id">
                                        <option value="">Chọn danh mục</option>
                                        <?php if (!empty($categories)): ?>
                                            <?php foreach ($categories as $category): ?>
                                                <option value="<?php echo $category->id; ?>" 
                                                        <?php echo ($product->category_id == $category->id) ? 'selected' : ''; ?>>
                                                    <?php echo htmlspecialchars($category->name, ENT_QUOTES, 'UTF-8'); ?>
                                                </option>
                                            <?php endforeach; ?>
                                        <?php endif; ?>
                                    </select>
                                </div>

                                <div class="mb-3">
                                    <label for="image" class="form-label">
                                        <i class="bi bi-image me-1"></i>Hình ảnh sản phẩm
                                    </label>
                                    
                                    <!-- Current Image Display -->
                                    <?php if (!empty($product->image)): ?>
                                        <div class="mb-2">
                                            <img src="/webbanhang/public/uploads/products/<?php echo htmlspecialchars($product->image, ENT_QUOTES, 'UTF-8'); ?>" 
                                                 alt="Current Product Image" 
                                                 class="img-thumbnail" 
                                                 style="max-width: 200px; max-height: 200px; object-fit: cover;">
                                            <div class="form-text">Ảnh hiện tại</div>
                                        </div>
                                    <?php endif; ?>
                                    
                                    <input type="file" 
                                           class="form-control" 
                                           id="image" 
                                           name="image" 
                                           accept="image/*"
                                           onchange="previewImage(this)">
                                    <div class="form-text">Chọn file ảnh mới (JPG, PNG, GIF). Để trống nếu không thay đổi.</div>
                                    
                                    <!-- New Image Preview -->
                                    <div id="imagePreview" class="mt-2" style="display: none;">
                                        <img id="preview" src="" alt="Preview" class="img-thumbnail" style="max-width: 200px; max-height: 200px;">
                                        <div class="form-text">Ảnh mới (chưa lưu)</div>
                                    </div>
                                </div>

                                <!-- Product Info -->
                                <div class="mb-3">
                                    <label class="form-label text-muted">
                                        <i class="bi bi-info-circle me-1"></i>Thông tin bổ sung
                                    </label>
                                    <div class="bg-light p-3 rounded">
                                        <small class="text-muted d-block">ID sản phẩm:</small>
                                        <code class="d-block mb-2">#<?php echo $product->id; ?></code>
                                        
                                        <small class="text-muted d-block">Ảnh hiện tại:</small>
                                        <code class="d-block"><?php echo $product->image ?: 'Chưa có ảnh'; ?></code>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="d-grid gap-2 d-md-flex justify-content-md-end mt-4">
                            <a href="/webbanhang/admin/products" class="btn btn-outline-secondary me-md-2">
                                <i class="bi bi-x-circle me-2"></i>Hủy
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-check-circle me-2"></i>Cập nhật sản phẩm
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function previewImage(input) {
    const preview = document.getElementById('preview');
    const previewDiv = document.getElementById('imagePreview');
    
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        
        reader.onload = function(e) {
            preview.src = e.target.result;
            previewDiv.style.display = 'block';
        };
        
        reader.readAsDataURL(input.files[0]);
    } else {
        previewDiv.style.display = 'none';
    }
}
</script>

<?php include_once 'app/views/shares/footer.php'; ?> 
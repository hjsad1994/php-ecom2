<?php
// filepath: app/views/voucher/edit.php
include 'app/views/shares/header.php'; 
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h1>Sửa voucher</h1>
    <a href="/webbanhang/Voucher" class="btn btn-outline-secondary">
        <i class="bi bi-arrow-left me-2"></i>Quay lại
    </a>
</div>

<?php if (!empty($errors)): ?>
<div class="alert alert-danger">
    <ul class="mb-0">
        <?php foreach ($errors as $error): ?>
            <li><?php echo htmlspecialchars($error, ENT_QUOTES, 'UTF-8'); ?></li>
        <?php endforeach; ?>
    </ul>
</div>
<?php endif; ?>

<div class="card shadow-sm">
    <div class="card-body">
        <form method="POST" action="/webbanhang/Voucher/update" class="needs-validation" novalidate>
            <input type="hidden" name="id" value="<?php echo $voucher->id; ?>">
            
            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="code" class="form-label">Mã voucher: <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="code" name="code" 
                               value="<?php echo htmlspecialchars($voucher->code, ENT_QUOTES, 'UTF-8'); ?>"
                               style="text-transform: uppercase;" readonly>
                        <div class="form-text">Mã voucher không thể thay đổi sau khi tạo</div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="name" class="form-label">Tên voucher: <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="name" name="name" 
                               value="<?php echo htmlspecialchars($voucher->name, ENT_QUOTES, 'UTF-8'); ?>" required>
                    </div>
                </div>
            </div>
            
            <div class="mb-3">
                <label for="description" class="form-label">Mô tả:</label>
                <textarea class="form-control" id="description" name="description" rows="3"><?php echo htmlspecialchars($voucher->description, ENT_QUOTES, 'UTF-8'); ?></textarea>
            </div>
            
            <div class="row">
                <div class="col-md-4">
                    <div class="mb-3">
                        <label for="discount_type" class="form-label">Loại giảm giá: <span class="text-danger">*</span></label>
                        <select class="form-select" id="discount_type" name="discount_type" required>
                            <option value="percentage" <?php echo ($voucher->discount_type == 'percentage') ? 'selected' : ''; ?>>Phần trăm (%)</option>
                            <option value="fixed" <?php echo ($voucher->discount_type == 'fixed') ? 'selected' : ''; ?>>Cố định (đ)</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="mb-3">
                        <label for="discount_value" class="form-label">Giá trị giảm: <span class="text-danger">*</span></label>
                        <input type="number" class="form-control" id="discount_value" name="discount_value" 
                               value="<?php echo $voucher->discount_value; ?>" step="0.01" min="0" required>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="mb-3">
                        <label for="max_discount_amount" class="form-label">Giảm tối đa (đ):</label>
                        <input type="number" class="form-control" id="max_discount_amount" name="max_discount_amount" 
                               value="<?php echo $voucher->max_discount_amount; ?>" step="0.01" min="0">
                        <div class="form-text">Để trống nếu không giới hạn</div>
                    </div>
                </div>
            </div>
            
            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="min_order_amount" class="form-label">Giá trị đơn hàng tối thiểu (đ):</label>
                        <input type="number" class="form-control" id="min_order_amount" name="min_order_amount" 
                               value="<?php echo $voucher->min_order_amount; ?>" step="0.01" min="0">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="usage_limit" class="form-label">Giới hạn sử dụng:</label>
                        <input type="number" class="form-control" id="usage_limit" name="usage_limit" 
                               value="<?php echo $voucher->usage_limit; ?>" min="1">
                        <div class="form-text">Để trống nếu không giới hạn</div>
                    </div>
                </div>
            </div>
            
            <div class="mb-3">
                <label class="form-label">Áp dụng cho:</label>
                <div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="applies_to" id="all_products" 
                               value="all_products" <?php echo ($voucher->applies_to == 'all_products') ? 'checked' : ''; ?>>
                        <label class="form-check-label" for="all_products">
                            Tất cả sản phẩm
                        </label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="applies_to" id="specific_products" 
                               value="specific_products" <?php echo ($voucher->applies_to == 'specific_products') ? 'checked' : ''; ?>>
                        <label class="form-check-label" for="specific_products">
                            Sản phẩm cụ thể
                        </label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="applies_to" id="specific_categories" 
                               value="specific_categories" <?php echo ($voucher->applies_to == 'specific_categories') ? 'checked' : ''; ?>>
                        <label class="form-check-label" for="specific_categories">
                            Danh mục cụ thể
                        </label>
                    </div>
                </div>
            </div>
            
            <div class="mb-3" id="products_selection" style="display: <?php echo ($voucher->applies_to == 'specific_products') ? 'block' : 'none'; ?>;">
                <label class="form-label">Chọn sản phẩm:</label>
                <div class="border rounded p-3" style="max-height: 200px; overflow-y: auto;">
                    <?php 
                    $selectedProducts = [];
                    if (!empty($voucher->product_ids)) {
                        $selectedProducts = json_decode($voucher->product_ids, true);
                    }
                    ?>
                    <?php foreach ($products as $product): ?>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="product_ids[]" 
                               value="<?php echo $product->id; ?>" id="product_<?php echo $product->id; ?>"
                               <?php echo in_array($product->id, $selectedProducts) ? 'checked' : ''; ?>>
                        <label class="form-check-label" for="product_<?php echo $product->id; ?>">
                            <?php echo htmlspecialchars($product->name, ENT_QUOTES, 'UTF-8'); ?>
                            <small class="text-muted">(<?php echo number_format($product->price, 0, ',', '.'); ?> đ)</small>
                        </label>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
            
            <div class="mb-3" id="categories_selection" style="display: <?php echo ($voucher->applies_to == 'specific_categories') ? 'block' : 'none'; ?>;">
                <label class="form-label">Chọn danh mục:</label>
                <div class="border rounded p-3" style="max-height: 200px; overflow-y: auto;">
                    <?php 
                    $selectedCategories = [];
                    if (!empty($voucher->category_ids)) {
                        $selectedCategories = json_decode($voucher->category_ids, true);
                    }
                    ?>
                    <?php foreach ($categories as $category): ?>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="category_ids[]" 
                               value="<?php echo $category->id; ?>" id="category_<?php echo $category->id; ?>"
                               <?php echo in_array($category->id, $selectedCategories) ? 'checked' : ''; ?>>
                        <label class="form-check-label" for="category_<?php echo $category->id; ?>">
                            <i class="bi bi-tag-fill me-1"></i>
                            <?php echo htmlspecialchars($category->name, ENT_QUOTES, 'UTF-8'); ?>
                            <?php if (!empty($category->description)): ?>
                                <small class="text-muted d-block"><?php echo htmlspecialchars($category->description, ENT_QUOTES, 'UTF-8'); ?></small>
                            <?php endif; ?>
                        </label>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
            
            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="start_date" class="form-label">Ngày bắt đầu: <span class="text-danger">*</span></label>
                        <input type="datetime-local" class="form-control" id="start_date" name="start_date" 
                               value="<?php echo date('Y-m-d\TH:i', strtotime($voucher->start_date)); ?>" required>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="end_date" class="form-label">Ngày kết thúc: <span class="text-danger">*</span></label>
                        <input type="datetime-local" class="form-control" id="end_date" name="end_date" 
                               value="<?php echo date('Y-m-d\TH:i', strtotime($voucher->end_date)); ?>" required>
                    </div>
                </div>
            </div>
            
            <div class="mb-3">
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" id="is_active" name="is_active" 
                           <?php echo $voucher->is_active ? 'checked' : ''; ?>>
                    <label class="form-check-label" for="is_active">
                        Kích hoạt voucher
                    </label>
                </div>
            </div>
            
            <!-- Display usage statistics -->
            <div class="alert alert-info">
                <h6 class="alert-heading"><i class="bi bi-info-circle me-2"></i>Thống kê sử dụng</h6>
                <div class="row">
                    <div class="col-md-4">
                        <strong>Số lần đã sử dụng:</strong> <?php echo $voucher->used_count; ?>
                    </div>
                    <div class="col-md-4">
                        <strong>Giới hạn sử dụng:</strong> <?php echo $voucher->usage_limit ? $voucher->usage_limit : 'Không giới hạn'; ?>
                    </div>
                    <div class="col-md-4">
                        <strong>Ngày tạo:</strong> <?php echo date('d/m/Y H:i', strtotime($voucher->created_at)); ?>
                    </div>
                </div>
            </div>
            
            <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                <a href="/webbanhang/Voucher" class="btn btn-light me-md-2">
                    <i class="bi bi-x-circle"></i> Hủy
                </a>
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-save"></i> Cập nhật voucher
                </button>
            </div>
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const appliesTo = document.querySelectorAll('input[name="applies_to"]');
    const productsSelection = document.getElementById('products_selection');
    const categoriesSelection = document.getElementById('categories_selection');
    
    appliesTo.forEach(radio => {
        radio.addEventListener('change', function() {
            if (this.value === 'specific_products') {
                productsSelection.style.display = 'block';
                categoriesSelection.style.display = 'none';
            } else if (this.value === 'specific_categories') {
                productsSelection.style.display = 'none';
                categoriesSelection.style.display = 'block';
            } else {
                productsSelection.style.display = 'none';
                categoriesSelection.style.display = 'none';
            }
        });
    });
    
    // Form validation
    (function() {
        'use strict';
        var forms = document.querySelectorAll('.needs-validation');
        Array.prototype.slice.call(forms).forEach(function(form) {
            form.addEventListener('submit', function(event) {
                if (!form.checkValidity()) {
                    event.preventDefault();
                    event.stopPropagation();
                }
                form.classList.add('was-validated');
            }, false);
        });
    })();
    
    // Warn if changing voucher that has been used
    <?php if ($voucher->used_count > 0): ?>
    const form = document.querySelector('form');
    form.addEventListener('submit', function(event) {
        if (!confirm('Voucher này đã được sử dụng <?php echo $voucher->used_count; ?> lần. Bạn có chắc chắn muốn thay đổi?')) {
            event.preventDefault();
        }
    });
    <?php endif; ?>
});
</script>

<?php include 'app/views/shares/footer.php'; ?>
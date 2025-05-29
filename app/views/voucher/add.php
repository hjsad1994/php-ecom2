<?php
// filepath: app/views/voucher/add.php
include 'app/views/shares/header.php'; 
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h1>Thêm voucher mới</h1>
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
        <form method="POST" action="/webbanhang/Voucher/save" class="needs-validation" novalidate>
            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="code" class="form-label">Mã voucher: <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="code" name="code" 
                               style="text-transform: uppercase;" required>
                        <div class="form-text">Mã voucher sẽ được chuyển thành chữ in hoa</div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="name" class="form-label">Tên voucher: <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="name" name="name" required>
                    </div>
                </div>
            </div>
            
            <div class="mb-3">
                <label for="description" class="form-label">Mô tả:</label>
                <textarea class="form-control" id="description" name="description" rows="3"></textarea>
            </div>
            
            <div class="row">
                <div class="col-md-4">
                    <div class="mb-3">
                        <label for="discount_type" class="form-label">Loại giảm giá: <span class="text-danger">*</span></label>
                        <select class="form-select" id="discount_type" name="discount_type" required>
                            <option value="percentage">Phần trăm (%)</option>
                            <option value="fixed">Cố định (đ)</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="mb-3">
                        <label for="discount_value" class="form-label">Giá trị giảm: <span class="text-danger">*</span></label>
                        <input type="number" class="form-control" id="discount_value" name="discount_value" 
                               step="0.01" min="0" required>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="mb-3">
                        <label for="max_discount_amount" class="form-label">Giảm tối đa (đ):</label>
                        <input type="number" class="form-control" id="max_discount_amount" name="max_discount_amount" 
                               step="0.01" min="0">
                        <div class="form-text">Để trống nếu không giới hạn</div>
                    </div>
                </div>
            </div>
            
            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="min_order_amount" class="form-label">Giá trị đơn hàng tối thiểu (đ):</label>
                        <input type="number" class="form-control" id="min_order_amount" name="min_order_amount" 
                               step="0.01" min="0" value="0">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="usage_limit" class="form-label">Giới hạn sử dụng:</label>
                        <input type="number" class="form-control" id="usage_limit" name="usage_limit" min="1">
                        <div class="form-text">Để trống nếu không giới hạn</div>
                    </div>
                </div>
            </div>
            
            <div class="mb-3">
                <label class="form-label">Áp dụng cho:</label>
                <div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="applies_to" id="all_products" 
                               value="all_products" checked>
                        <label class="form-check-label" for="all_products">
                            Tất cả sản phẩm
                        </label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="applies_to" id="specific_products" 
                               value="specific_products">
                        <label class="form-check-label" for="specific_products">
                            Sản phẩm cụ thể
                        </label>
                    </div>
                </div>
            </div>
            
            <div class="mb-3" id="products_selection" style="display: none;">
                <label class="form-label">Chọn sản phẩm:</label>
                <div class="border rounded p-3" style="max-height: 200px; overflow-y: auto;">
                    <?php foreach ($products as $product): ?>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="product_ids[]" 
                               value="<?php echo $product->id; ?>" id="product_<?php echo $product->id; ?>">
                        <label class="form-check-label" for="product_<?php echo $product->id; ?>">
                            <?php echo htmlspecialchars($product->name, ENT_QUOTES, 'UTF-8'); ?>
                            <small class="text-muted">(<?php echo number_format($product->price, 0, ',', '.'); ?> đ)</small>
                        </label>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
            
            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="start_date" class="form-label">Ngày bắt đầu: <span class="text-danger">*</span></label>
                        <input type="datetime-local" class="form-control" id="start_date" name="start_date" required>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="end_date" class="form-label">Ngày kết thúc: <span class="text-danger">*</span></label>
                        <input type="datetime-local" class="form-control" id="end_date" name="end_date" required>
                    </div>
                </div>
            </div>
            
            <div class="mb-3">
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" id="is_active" name="is_active" checked>
                    <label class="form-check-label" for="is_active">
                        Kích hoạt voucher
                    </label>
                </div>
            </div>
            
            <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                <a href="/webbanhang/Voucher" class="btn btn-light me-md-2">
                    <i class="bi bi-x-circle"></i> Hủy
                </a>
                <button type="submit" class="btn btn-success">
                    <i class="bi bi-plus-circle"></i> Thêm voucher
                </button>
            </div>
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const appliesTo = document.querySelectorAll('input[name="applies_to"]');
    const productsSelection = document.getElementById('products_selection');
    
    appliesTo.forEach(radio => {
        radio.addEventListener('change', function() {
            if (this.value === 'specific_products') {
                productsSelection.style.display = 'block';
            } else {
                productsSelection.style.display = 'none';
            }
        });
    });
    
    // Set default datetime
    const now = new Date();
    now.setMinutes(now.getMinutes() - now.getTimezoneOffset());
    document.getElementById('start_date').value = now.toISOString().slice(0, 16);
    
    const endDate = new Date(now.getTime() + (30 * 24 * 60 * 60 * 1000)); // 30 days later
    document.getElementById('end_date').value = endDate.toISOString().slice(0, 16);
    
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
});
</script>

<?php include 'app/views/shares/footer.php'; ?>
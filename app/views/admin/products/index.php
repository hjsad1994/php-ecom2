<?php include_once 'app/views/shares/header.php'; ?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1 class="display-5 fw-bold">
            <i class="bi bi-box-seam me-2 text-primary"></i>Quản lý sản phẩm
        </h1>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="/webbanhang/admin/dashboard" class="text-decoration-none">Dashboard</a></li>
                <li class="breadcrumb-item active">Sản phẩm</li>
            </ol>
        </nav>
    </div>
    <div class="d-flex align-items-center gap-2">
        <a href="/webbanhang/admin/categories" class="btn btn-outline-secondary">
            <i class="bi bi-tags me-2"></i>Quản lý danh mục
        </a>
        <a href="/webbanhang/admin/products/create" class="btn btn-success btn-lg">
            <i class="bi bi-plus-circle me-2"></i>Thêm sản phẩm mới
        </a>
    </div>
</div>

<!-- Statistics Dashboard -->
<div class="row mb-5">
    <div class="col-lg-3 col-md-6 mb-4">
        <div class="card border-0 shadow-sm h-100 bg-gradient stats-green">
            <div class="card-body text-white">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h2 class="fw-bold mb-0"><?php echo number_format($stats['total_products']); ?></h2>
                        <p class="mb-0 opacity-75">Sản phẩm</p>
                    </div>
                    <i class="bi bi-box-seam fs-1 opacity-75"></i>
                </div>
            </div>
            <div class="card-footer bg-transparent border-0">
                <a href="/webbanhang/admin/products/create" class="text-white text-decoration-none">
                    <small><i class="bi bi-plus-circle me-1"></i>Thêm sản phẩm mới</small>
                </a>
            </div>
        </div>
    </div>
    
    <div class="col-lg-3 col-md-6 mb-4">
        <div class="card border-0 shadow-sm h-100 bg-gradient stats-orange">
            <div class="card-body text-white">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h2 class="fw-bold mb-0"><?php echo number_format($stats['total_categories']); ?></h2>
                        <p class="mb-0 opacity-75">Danh mục</p>
                    </div>
                    <i class="bi bi-tags fs-1 opacity-75"></i>
                </div>
            </div>
            <div class="card-footer bg-transparent border-0">
                <a href="/webbanhang/admin/categories" class="text-white text-decoration-none">
                    <small><i class="bi bi-arrow-right me-1"></i>Quản lý danh mục</small>
                </a>
            </div>
        </div>
    </div>
    
    <div class="col-lg-3 col-md-6 mb-4">
        <div class="card border-0 shadow-sm h-100 bg-gradient stats-purple">
            <div class="card-body text-white">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h2 class="fw-bold mb-0">
                            <?php echo $stats['price_range'] ? number_format($stats['price_range']->avg_price, 0, ',', '.') : '0'; ?>đ
                        </h2>
                        <p class="mb-0 opacity-75">Giá trung bình</p>
                    </div>
                    <i class="bi bi-currency-dollar fs-1 opacity-75"></i>
                </div>
            </div>
            <div class="card-footer bg-transparent border-0">
                <a href="#priceRange" class="text-white text-decoration-none">
                    <small><i class="bi bi-graph-up me-1"></i>Xem phân tích giá</small>
                </a>
            </div>
        </div>
    </div>
    
    <div class="col-lg-3 col-md-6 mb-4">
        <div class="card border-0 shadow-sm h-100 bg-gradient stats-red">
            <div class="card-body text-white">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h2 class="fw-bold mb-0">
                            <?php echo $stats['price_range'] ? $stats['price_range']->products_with_image : '0'; ?>
                        </h2>
                        <p class="mb-0 opacity-75">Có hình ảnh</p>
                    </div>
                    <i class="bi bi-image fs-1 opacity-75"></i>
                </div>
            </div>
            <div class="card-footer bg-transparent border-0">
                <a href="#categoryStats" class="text-white text-decoration-none">
                    <small><i class="bi bi-eye me-1"></i>Xem chi tiết</small>
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Price Range Info -->
<?php if ($stats['price_range']): ?>
<div class="row mb-4" id="priceRange">
    <div class="col-12">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-light">
                <h5 class="mb-0"><i class="bi bi-graph-up me-2"></i>Thông tin giá sản phẩm</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-3 text-center">
                        <div class="border-end">
                            <h4 class="text-success mb-1"><?php echo number_format($stats['price_range']->min_price, 0, ',', '.'); ?>đ</h4>
                            <small class="text-muted">Giá thấp nhất</small>
                        </div>
                    </div>
                    <div class="col-md-3 text-center">
                        <div class="border-end">
                            <h4 class="text-danger mb-1"><?php echo number_format($stats['price_range']->max_price, 0, ',', '.'); ?>đ</h4>
                            <small class="text-muted">Giá cao nhất</small>
                        </div>
                    </div>
                    <div class="col-md-3 text-center">
                        <div class="border-end">
                            <h4 class="text-primary mb-1"><?php echo $stats['price_range']->products_with_image; ?></h4>
                            <small class="text-muted">Có hình ảnh</small>
                        </div>
                    </div>
                    <div class="col-md-3 text-center">
                        <h4 class="text-secondary mb-1"><?php echo $stats['price_range']->products_without_image; ?></h4>
                        <small class="text-muted">Chưa có hình ảnh</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php endif; ?>

<!-- Category Statistics -->
<div class="row mb-4" id="categoryStats">
    <div class="col-md-8">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-light d-flex justify-content-between align-items-center">
                <h5 class="mb-0"><i class="bi bi-pie-chart me-2"></i>Thống kê theo danh mục</h5>
                <span class="badge bg-primary"><?php echo count($stats['category_stats']); ?> danh mục</span>
            </div>
            <div class="card-body">
                <?php if (!empty($stats['category_stats'])): ?>
                    <div class="row g-3">
                        <?php foreach ($stats['category_stats'] as $index => $category): ?>
                            <div class="col-md-6">
                                <div class="d-flex justify-content-between align-items-center p-3 bg-light rounded">
                                    <div class="d-flex align-items-center">
                                        <div class="bg-primary bg-gradient rounded-circle me-3" style="width: 16px; height: 16px;"></div>
                                        <div>
                                            <div class="fw-bold text-dark"><?php echo htmlspecialchars($category->category_name, ENT_QUOTES, 'UTF-8'); ?></div>
                                            <div class="small text-muted">
                                                Giá TB: <?php echo $category->avg_price ? number_format($category->avg_price, 0, ',', '.') . 'đ' : 'N/A'; ?>
                                                <span class="ms-2">
                                                    <i class="bi bi-image me-1"></i><?php echo $category->products_with_image; ?> có ảnh
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="text-end">
                                        <span class="badge bg-primary fs-6"><?php echo $category->product_count; ?></span>
                                        <div class="small text-muted">sản phẩm</div>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php else: ?>
                    <div class="text-center text-muted py-4">
                        <i class="bi bi-tags fs-1 mb-3"></i>
                        <p>Chưa có danh mục nào</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
    
    <div class="col-md-4">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-light">
                <h5 class="mb-0"><i class="bi bi-clock-history me-2"></i>Sản phẩm mới nhất</h5>
            </div>
            <div class="card-body">
                <?php if (!empty($stats['latest_products'])): ?>
                    <?php foreach ($stats['latest_products'] as $product): ?>
                        <div class="d-flex align-items-center py-2 border-bottom">
                            <div class="me-3">
                                <?php if (!empty($product->image)): ?>
                                    <img src="/webbanhang/public/uploads/products/<?php echo htmlspecialchars($product->image, ENT_QUOTES, 'UTF-8'); ?>" 
                                         alt="<?php echo htmlspecialchars($product->name, ENT_QUOTES, 'UTF-8'); ?>"
                                         class="rounded"
                                         style="width: 50px; height: 50px; object-fit: cover;">
                                <?php else: ?>
                                    <div class="bg-light rounded d-flex align-items-center justify-content-center" 
                                         style="width: 50px; height: 50px;">
                                        <i class="bi bi-image text-muted"></i>
                                    </div>
                                <?php endif; ?>
                            </div>
                            <div class="flex-grow-1">
                                <div class="fw-bold small">
                                    <?php echo htmlspecialchars(substr($product->name, 0, 25), ENT_QUOTES, 'UTF-8'); ?>
                                    <?php echo strlen($product->name) > 25 ? '...' : ''; ?>
                                </div>
                                <div class="text-primary small"><?php echo number_format($product->price, 0, ',', '.'); ?>đ</div>
                                <div class="text-muted small">
                                    <?php echo $product->category_name ? $product->category_name : 'Chưa phân loại'; ?>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                    <div class="text-center mt-3">
                        <a href="#productsTable" class="btn btn-outline-primary btn-sm">
                            <i class="bi bi-eye me-1"></i>Xem tất cả
                        </a>
                    </div>
                <?php else: ?>
                    <div class="text-center text-muted py-4">
                        <i class="bi bi-box fs-1 mb-3"></i>
                        <p>Chưa có sản phẩm nào</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<!-- Search and Filter -->
<div class="row mb-4">
    <div class="col-12">
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col-md-6">
                        <div class="input-group">
                            <span class="input-group-text"><i class="bi bi-search"></i></span>
                            <input type="text" class="form-control" id="searchInput" placeholder="Tìm kiếm sản phẩm...">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <select class="form-select" id="categoryFilter">
                            <option value="">Tất cả danh mục</option>
                            <?php if (!empty($categories)): ?>
                                <?php foreach ($categories as $category): ?>
                                    <option value="<?php echo $category->id; ?>">
                                        <?php echo htmlspecialchars($category->name, ENT_QUOTES, 'UTF-8'); ?>
                                    </option>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <button class="btn btn-outline-secondary w-100" id="refreshBtn">
                            <i class="bi bi-arrow-clockwise me-1"></i>Làm mới
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Products Table -->
<?php if (empty($products)): ?>
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="text-center py-5">
                <i class="bi bi-box-seam display-1 text-muted mb-4"></i>
                <h3 class="text-muted mb-3">Chưa có sản phẩm nào</h3>
                <p class="text-muted mb-4">Hãy thêm sản phẩm đầu tiên để bắt đầu quản lý cửa hàng của bạn!</p>
                <div class="d-grid gap-2 d-md-block">
                    <a href="/webbanhang/admin/products/create" class="btn btn-success btn-lg">
                        <i class="bi bi-plus-circle me-2"></i>Thêm sản phẩm đầu tiên
                    </a>
                    <a href="/webbanhang/admin/categories" class="btn btn-outline-secondary btn-lg">
                        <i class="bi bi-tags me-2"></i>Quản lý danh mục
                    </a>
                </div>
            </div>
        </div>
    </div>
<?php else: ?>
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-light d-flex justify-content-between align-items-center">
            <h5 class="mb-0"><i class="bi bi-table me-2"></i>Danh sách sản phẩm</h5>
            <span class="badge bg-primary fs-6"><?php echo count($products); ?> sản phẩm</span>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table id="productsTable" class="table table-hover w-100">
                    <thead class="table-light">
                        <tr>
                            <th width="5%">ID</th>
                            <th width="10%">Hình ảnh</th>
                            <th width="30%">Tên sản phẩm</th>
                            <th width="15%">Danh mục</th>
                            <th width="15%">Giá</th>
                            <th width="10%">Thông tin</th>
                            <th width="15%">Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($products as $product): ?>
                        <tr>
                            <td class="fw-bold text-primary">#<?php echo str_pad($product->id, 3, '0', STR_PAD_LEFT); ?></td>
                            <td class="text-center">
                                <?php if (!empty($product->image)): ?>
                                    <img src="/webbanhang/public/uploads/products/<?php echo htmlspecialchars($product->image, ENT_QUOTES, 'UTF-8'); ?>" 
                                         alt="<?php echo htmlspecialchars($product->name, ENT_QUOTES, 'UTF-8'); ?>"
                                         class="img-thumbnail product-thumbnail"
                                         loading="lazy"
                                         onerror="this.style.display='none'; this.nextElementSibling.style.display='inline-block';">
                                    <div class="text-muted small" style="display: none;">
                                        <i class="bi bi-image fs-4"></i>
                                        <div>Lỗi tải ảnh</div>
                                    </div>
                                <?php else: ?>
                                    <div class="d-flex flex-column align-items-center text-muted">
                                        <i class="bi bi-image fs-4 mb-1"></i>
                                        <small>Chưa có ảnh</small>
                                    </div>
                                <?php endif; ?>
                            </td>
                            <td>
                                <div class="fw-bold mb-1">
                                    <a href="/webbanhang/product/show/<?php echo $product->id; ?>" 
                                       class="text-decoration-none text-dark" 
                                       target="_blank">
                                        <?php echo htmlspecialchars($product->name, ENT_QUOTES, 'UTF-8'); ?>
                                        <i class="bi bi-box-arrow-up-right small text-muted ms-1"></i>
                                    </a>
                                </div>
                                <div class="text-muted small">
                                    <?php 
                                        $plainText = strip_tags(html_entity_decode($product->description));
                                        echo strlen($plainText) > 80 ? substr($plainText, 0, 80) . '...' : $plainText;
                                    ?>
                                </div>
                            </td>
                            <td>
                                <?php if (!empty($product->category_name)): ?>
                                    <span class="badge bg-secondary bg-gradient">
                                        <i class="bi bi-tag me-1"></i>
                                        <?php echo htmlspecialchars($product->category_name, ENT_QUOTES, 'UTF-8'); ?>
                                    </span>
                                <?php else: ?>
                                    <span class="text-muted small">
                                        <i class="bi bi-dash-circle me-1"></i>Chưa phân loại
                                    </span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <div class="fw-bold text-success">
                                    <?php echo number_format($product->price, 0, ',', '.'); ?> đ
                                </div>
                            </td>
                            <td>
                                <div class="small text-muted">
                                    <i class="bi bi-hash me-1"></i>
                                    ID: <?php echo str_pad($product->id, 3, '0', STR_PAD_LEFT); ?>
                                </div>
                                <div class="small text-muted">
                                    Sản phẩm số <?php echo $product->id; ?>
                                </div>
                            </td>
                            <td>
                                <div class="btn-group" role="group">
                                    <a href="/webbanhang/product/show/<?php echo $product->id; ?>" 
                                       class="btn btn-sm btn-outline-info" 
                                       title="Xem chi tiết"
                                       target="_blank">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    <a href="/webbanhang/admin/products/edit/<?php echo $product->id; ?>" 
                                       class="btn btn-sm btn-outline-primary" 
                                       title="Chỉnh sửa">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <a href="/webbanhang/admin/products/delete/<?php echo $product->id; ?>" 
                                       onclick="return confirm('Bạn có chắc chắn muốn xóa sản phẩm này?\n\nSản phẩm: <?php echo htmlspecialchars($product->name, ENT_QUOTES, 'UTF-8'); ?>');" 
                                       class="btn btn-sm btn-outline-danger" 
                                       title="Xóa">
                                        <i class="bi bi-trash"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
<?php endif; ?>

<!-- DataTables CSS and JS -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css">
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap5.min.js"></script>

<style>
.product-thumbnail {
    width: 80px;
    height: 80px;
    object-fit: cover;
    border-radius: 8px;
    transition: transform 0.2s ease, box-shadow 0.2s ease;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
}

.product-thumbnail:hover {
    transform: scale(1.1);
    box-shadow: 0 4px 16px rgba(0,0,0,0.2);
    z-index: 1000;
    position: relative;
}

.btn-group .btn {
    border-radius: 6px !important;
    margin-right: 2px;
}

.btn-group .btn:last-child {
    margin-right: 0;
}

.table td {
    vertical-align: middle;
    color: #212529 !important;
}

.table th {
    border-top: none;
    font-weight: 600;
    color: #495057 !important;
}

.table .text-dark {
    color: #212529 !important;
}

.table .text-muted {
    color: #6c757d !important;
}

.table .text-success {
    color: #198754 !important;
    font-weight: 600;
}

.table .text-primary {
    color: #0d6efd !important;
}

.card {
    border-radius: 12px;
    transition: transform 0.2s ease, box-shadow 0.2s ease;
    box-shadow: 0 4px 15px rgba(0,0,0,0.1) !important;
}

.card:hover {
    transform: translateY(-3px);
    box-shadow: 0 8px 25px rgba(0,0,0,0.15) !important;
}

/* Statistics Cards Enhancement */
.card.bg-primary,
.card.bg-success,
.card.bg-warning,
.card.bg-info {
    border: none;
    box-shadow: 0 4px 20px rgba(0,0,0,0.2) !important;
}

.card.bg-primary:hover,
.card.bg-success:hover,
.card.bg-warning:hover,
.card.bg-info:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 30px rgba(0,0,0,0.25) !important;
}

.badge {
    font-size: 0.85rem;
    padding: 8px 12px;
    border-radius: 8px;
}

.bg-gradient {
    background-size: 200% 200%;
    animation: gradientShift 3s ease infinite;
}

@keyframes gradientShift {
    0% { background-position: 0% 50%; }
    50% { background-position: 100% 50%; }
    100% { background-position: 0% 50%; }
}

/* Force override statistics cards colors */
.stats-green {
    background: linear-gradient(135deg, #28a745 0%, #20c997 100%) !important;
}

.stats-orange {
    background: linear-gradient(135deg, #fd7e14 0%, #ffc107 100%) !important;
}

.stats-purple {
    background: linear-gradient(135deg, #6f42c1 0%, #e83e8c 100%) !important;
}

.stats-red {
    background: linear-gradient(135deg, #dc3545 0%, #fd7e14 100%) !important;
}

/* Custom scrollbar for table */
.table-responsive::-webkit-scrollbar {
    height: 8px;
}

.table-responsive::-webkit-scrollbar-track {
    background: #f1f1f1;
    border-radius: 4px;
}

.table-responsive::-webkit-scrollbar-thumb {
    background: #c1c1c1;
    border-radius: 4px;
}

.table-responsive::-webkit-scrollbar-thumb:hover {
    background: #a8a8a8;
}

/* Animation for statistics cards */
@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.card {
    animation: fadeInUp 0.6s ease-out;
}

/* Responsive improvements */
@media (max-width: 768px) {
    .product-thumbnail {
        width: 60px;
        height: 60px;
    }
    
    .btn-group .btn {
        padding: 0.25rem 0.5rem;
    }
    
    .card-body {
        padding: 1rem;
    }
}
</style>

<script>
$(document).ready(function() {
    // Initialize DataTable
    var table = $('#productsTable').DataTable({
        language: {
            "search": "Tìm kiếm:",
            "lengthMenu": "Hiển thị _MENU_ sản phẩm",
            "zeroRecords": "Không tìm thấy sản phẩm nào phù hợp",
            "info": "Hiển thị _START_ đến _END_ trong tổng số _TOTAL_ sản phẩm",
            "infoEmpty": "Không tìm thấy sản phẩm nào",
            "infoFiltered": "(lọc từ _MAX_ sản phẩm)",
            "paginate": {
                "first": "Đầu",
                "last": "Cuối",
                "next": "Tiếp",
                "previous": "Trước"
            }
        },
        responsive: true,
        columnDefs: [
            { orderable: false, targets: [1, 6] },
            { className: "text-center", targets: [0, 1, 6] }
        ],
        order: [[0, 'desc']],
        pageLength: 10,
        lengthMenu: [[10, 25, 50, -1], [10, 25, 50, "Tất cả"]]
    });

    // Custom search
    $('#searchInput').on('keyup', function() {
        table.search(this.value).draw();
    });

    // Category filter with improved logic
    $('#categoryFilter').on('change', function() {
        var selectedValue = this.value;
        var selectedText = $(this).find('option:selected').text().trim();
        
        console.log('Category filter changed:', selectedValue, selectedText);
        
        if (selectedValue === '') {
            // Clear filter to show all products
            table.column(3).search('').draw();
        } else {
            // Filter by exact category name match
            // Use regex to match the category name within the badge
            table.column(3).search(selectedText, true, false).draw();
        }
    });

    // Refresh button
    $('#refreshBtn').on('click', function() {
        $('#searchInput').val('');
        $('#categoryFilter').val('');
        table.search('').columns().search('').draw();
        
        // Add refresh animation
        $(this).find('i').addClass('fa-spin');
        setTimeout(() => {
            $(this).find('i').removeClass('fa-spin');
        }, 1000);
    });

    // Success message from URL parameter
    const urlParams = new URLSearchParams(window.location.search);
    if (urlParams.get('success') === 'created') {
        showToast('Thêm sản phẩm thành công!', 'success');
        // Remove the parameter from URL
        window.history.replaceState({}, document.title, window.location.pathname);
    }

    // Tooltip for action buttons
    $('[title]').tooltip();
});

// Toast notification function
function showToast(message, type = 'info') {
    const toastHtml = `
        <div class="toast align-items-center text-white bg-${type} border-0" role="alert">
            <div class="d-flex">
                <div class="toast-body">${message}</div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
            </div>
        </div>
    `;
    
    // Create toast container if it doesn't exist
    if (!document.getElementById('toast-container')) {
        const container = document.createElement('div');
        container.id = 'toast-container';
        container.className = 'toast-container position-fixed top-0 end-0 p-3';
        container.style.zIndex = '1056';
        document.body.appendChild(container);
    }
    
    const container = document.getElementById('toast-container');
    container.insertAdjacentHTML('beforeend', toastHtml);
    
    const toastElement = container.lastElementChild;
    const toast = new bootstrap.Toast(toastElement);
    toast.show();
    
    // Remove toast element after it's hidden
    toastElement.addEventListener('hidden.bs.toast', function() {
        this.remove();
    });
}
</script>

<?php include_once 'app/views/shares/footer.php'; ?> 
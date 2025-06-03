<?php include_once 'app/views/shares/header.php'; ?>

<div class="row mb-4">
    <div class="col-md-8">
        <h1 class="display-6 fw-bold text-primary">
            <i class="bi bi-grid-3x3-gap me-2"></i>Sản phẩm
        </h1>
        <p class="text-muted mb-0">Khám phá bộ sưu tập sản phẩm chất lượng cao</p>
    </div>
    <div class="col-md-4 text-end">
        <div class="d-flex justify-content-end align-items-center gap-2">
            <!-- Search Box -->
            <div class="input-group me-3" style="max-width: 250px;">
                <input type="text" class="form-control" placeholder="Tìm kiếm sản phẩm..." id="productSearch">
                <button class="btn btn-outline-primary" type="button">
                    <i class="bi bi-search"></i>
                </button>
            </div>
            <!-- Cart Link -->
            <a href="/webbanhang/cart" class="btn btn-primary position-relative">
                <i class="bi bi-cart3 me-1"></i>
                Giỏ hàng
                <?php if (isset($_SESSION['cart']) && !empty($_SESSION['cart'])): ?>
                    <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                        <?php echo array_sum(array_column($_SESSION['cart'], 'quantity')); ?>
                    </span>
                <?php endif; ?>
            </a>
            <!-- Categories temporarily removed for simplified UX -->
            <!-- <a href="/webbanhang/category" class="btn btn-primary">
                <i class="bi bi-tag-fill me-1"></i>Danh mục
            </a> -->
            <div class="dropdown">
                <button class="btn btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                    <i class="bi bi-funnel me-1"></i>Lọc
                </button>
                <ul class="dropdown-menu dropdown-menu-end">
                    <li><a class="dropdown-item" href="?sort=name_asc">Tên A-Z</a></li>
                    <li><a class="dropdown-item" href="?sort=name_desc">Tên Z-A</a></li>
                    <li><a class="dropdown-item" href="?sort=price_asc">Giá thấp → cao</a></li>
                    <li><a class="dropdown-item" href="?sort=price_desc">Giá cao → thấp</a></li>
                </ul>
            </div>
        </div>
    </div>
</div>

<!-- Filter Row -->
<div class="row mb-4">
    <div class="col-md-3">
        <div class="card h-100">
            <div class="card-header bg-light">
                <h6 class="mb-0"><i class="bi bi-funnel me-2"></i>Bộ lọc</h6>
            </div>
            <div class="card-body">
                <!-- Category Filter -->
                <div class="mb-3">
                    <label class="form-label fw-bold">Danh mục</label>
                    <select class="form-select" id="categoryFilter">
                        <option value="">Tất cả danh mục</option>
                        <?php if (isset($categories) && !empty($categories)): ?>
                            <?php foreach ($categories as $category): ?>
                                <option value="<?php echo $category->id; ?>" <?php echo (isset($_GET['category']) && $_GET['category'] == $category->id) ? 'selected' : ''; ?>>
                                    <?php echo htmlspecialchars($category->name, ENT_QUOTES, 'UTF-8'); ?>
                                </option>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </select>
                </div>

                <!-- Price Filter -->
                <div class="mb-3">
                    <label class="form-label fw-bold">Khoảng giá</label>
                    <div class="row g-2">
                        <div class="col-6">
                            <input type="number" class="form-control form-control-sm" placeholder="Từ" id="priceFrom">
                        </div>
                        <div class="col-6">
                            <input type="number" class="form-control form-control-sm" placeholder="Đến" id="priceTo">
                        </div>
                    </div>
                </div>

                <!-- Sort Options -->
                <div class="mb-3">
                    <label class="form-label fw-bold">Sắp xếp theo</label>
                    <select class="form-select" id="sortBy">
                        <option value="newest">Mới nhất</option>
                        <option value="price_asc">Giá: Thấp đến cao</option>
                        <option value="price_desc">Giá: Cao đến thấp</option>
                        <option value="name_asc">Tên: A-Z</option>
                        <option value="name_desc">Tên: Z-A</option>
                    </select>
                </div>

                <!-- Filter Actions -->
                <div class="d-grid gap-2">
                    <button class="btn btn-primary btn-sm" onclick="applyFilters()">
                        <i class="bi bi-check2 me-1"></i>Áp dụng
                    </button>
                    <button class="btn btn-outline-secondary btn-sm" onclick="clearFilters()">
                        <i class="bi bi-x-lg me-1"></i>Xóa bộ lọc
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-9">
        <?php if (empty($products)): ?>
            <div class="text-center py-5">
                <i class="bi bi-box-seam display-1 text-muted mb-3"></i>
                <h3 class="text-muted">Không có sản phẩm nào</h3>
                <p class="text-muted">Hiện tại chưa có sản phẩm nào được hiển thị.</p>
                <a href="/webbanhang/category" class="btn btn-primary">
                    <i class="bi bi-tags me-2"></i>Xem danh mục
                </a>
            </div>
        <?php else: ?>
            <!-- Products Grid -->
            <div class="row" id="productsGrid">
                <?php foreach ($products as $product): ?>
                    <div class="col-lg-4 col-md-6 mb-4 product-item" 
                         data-category="<?php echo $product->category_id ?? ''; ?>" 
                         data-price="<?php echo $product->price; ?>"
                         data-name="<?php echo strtolower($product->name); ?>">
                        <div class="card h-100 shadow-sm product-card">
                            <!-- Product Image -->
                            <div class="position-relative overflow-hidden product-image-container">
                                <?php 
                                $imagePath = $product->image ? '/webbanhang/public/uploads/products/' . $product->image : 'data:image/svg+xml;base64,' . base64_encode('<svg width="300" height="300" xmlns="http://www.w3.org/2000/svg"><rect width="300" height="300" fill="#f8f9fa"/><circle cx="150" cy="130" r="25" fill="none" stroke="#dee2e6" stroke-width="3"/><path d="M135 130 L150 145 L165 130 M140 135 L160 135" stroke="#dee2e6" stroke-width="3" fill="none"/><text x="150" y="180" text-anchor="middle" font-family="Arial, sans-serif" font-size="16" fill="#6c757d">Không có ảnh</text></svg>');
                                ?>
                                <img src="<?php echo $imagePath; ?>" 
                                     class="card-img-top product-image" 
                                     alt="<?php echo htmlspecialchars($product->name, ENT_QUOTES, 'UTF-8'); ?>"
                                     loading="lazy"
                                     onerror="this.src='data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMzAwIiBoZWlnaHQ9IjMwMCIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj48cmVjdCB3aWR0aD0iMzAwIiBoZWlnaHQ9IjMwMCIgZmlsbD0iI2Y4ZjlmYSIvPjxjaXJjbGUgY3g9IjE1MCIgY3k9IjEzMCIgcj0iMjUiIGZpbGw9Im5vbmUiIHN0cm9rZT0iI2RlZTJlNiIgc3Ryb2tlLXdpZHRoPSIzIi8+PHBhdGggZD0iTTEzNSAxMzAgTDE1MDE0NSBMMTY1IDEzMCBNMTQwIDEzNSBMMTYwIDEzNSIgc3Ryb2tlPSIjZGVlMmU2IiBzdHJva2Utd2lkdGg9IjMiIGZpbGw9Im5vbmUiLz48dGV4dCB4PSIxNTAiIHk9IjE4MCIgdGV4dC1hbmNob3I9Im1pZGRsZSIgZm9udC1mYW1pbHk9IkFyaWFsLCBzYW5zLXNlcmlmIiBmb250LXNpemU9IjE2IiBmaWxsPSIjNmM3NTdkIj5LaG9uZyBjbyBhbmg8L3RleHQ+PC9zdmc+';">
                                
                                <!-- Product Badge -->
                                <div class="position-absolute top-0 start-0 p-2">
                                    <span class="badge bg-primary">Mới</span>
                                </div>

                                <!-- Quick Actions -->
                                <div class="position-absolute top-0 end-0 p-2">
                                    <div class="btn-group-vertical">
                                        <a href="/webbanhang/product/show/<?php echo $product->id; ?>" 
                                           class="btn btn-sm btn-light" title="Xem chi tiết">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                        <button class="btn btn-sm btn-light" title="Yêu thích">
                                            <i class="bi bi-heart"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <!-- Product Info -->
                            <div class="card-body d-flex flex-column">
                                <h5 class="card-title text-truncate" title="<?php echo htmlspecialchars($product->name, ENT_QUOTES, 'UTF-8'); ?>">
                                    <?php echo htmlspecialchars($product->name, ENT_QUOTES, 'UTF-8'); ?>
                                </h5>
                                
                                <!-- Category -->
                                <div class="mb-2">
                                    <small class="text-muted">
                                        <i class="bi bi-tag me-1"></i>
                                        <?php echo isset($product->category_name) ? htmlspecialchars($product->category_name, ENT_QUOTES, 'UTF-8') : 'Chưa phân loại'; ?>
                                    </small>
                                </div>

                                <!-- Description -->
                                <p class="card-text text-muted small flex-grow-1">
                                    <?php 
                                    $description = strip_tags($product->description ?? '');
                                    echo strlen($description) > 100 ? substr($description, 0, 100) . '...' : $description;
                                    ?>
                                </p>

                                <!-- Price -->
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <div>
                                        <span class="h5 text-primary fw-bold">
                                            <?php echo number_format($product->price, 0, ',', '.'); ?> đ
                                        </span>
                                    </div>
                                    <div class="text-end">
                                        <div class="text-warning small">
                                            <i class="bi bi-star-fill"></i>
                                            <i class="bi bi-star-fill"></i>
                                            <i class="bi bi-star-fill"></i>
                                            <i class="bi bi-star-fill"></i>
                                            <i class="bi bi-star"></i>
                                            <span class="text-muted">(4.0)</span>
                                        </div>
                                    </div>
                                </div>

                                <!-- Actions -->
                                <div class="d-grid gap-2">
                                    <div class="btn-group">
                                        <a href="/webbanhang/product/show/<?php echo $product->id; ?>" 
                                           class="btn btn-outline-primary">
                                            <i class="bi bi-eye me-1"></i>Chi tiết
                                        </a>
                                        <button class="btn btn-primary" onclick="addToCart(<?php echo $product->id; ?>)">
                                            <i class="bi bi-cart-plus me-1"></i>Thêm vào giỏ
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>

            <!-- Pagination -->
            <nav aria-label="Product pagination">
                <ul class="pagination justify-content-center">
                    <li class="page-item disabled">
                        <a class="page-link" href="#" tabindex="-1">Trước</a>
                    </li>
                    <li class="page-item active">
                        <a class="page-link" href="#">1</a>
                    </li>
                    <li class="page-item">
                        <a class="page-link" href="#">2</a>
                    </li>
                    <li class="page-item">
                        <a class="page-link" href="#">3</a>
                    </li>
                    <li class="page-item">
                        <a class="page-link" href="#">Sau</a>
                    </li>
                </ul>
            </nav>
        <?php endif; ?>
    </div>
</div>

<style>
.product-card {
    transition: all 0.3s ease;
    border: none;
    border-radius: 12px;
    overflow: hidden;
    box-shadow: 0 5px 20px rgba(0,0,0,0.08);
}

.product-card:hover {
    transform: translateY(-8px);
    box-shadow: 0 20px 40px rgba(0,0,0,0.15) !important;
}

.product-image-container {
    height: 300px;
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
    border-radius: 12px 12px 0 0;
}

.product-image {
    width: 100%;
    height: 100%;
    object-fit: cover;
    object-position: center;
    transition: transform 0.3s ease;
    border-radius: 12px 12px 0 0;
}

.product-card:hover .product-image {
    transform: scale(1.05);
}

.btn-group-vertical .btn {
    opacity: 0;
    transition: all 0.3s ease;
    border-radius: 8px;
    backdrop-filter: blur(10px);
    background: rgba(255, 255, 255, 0.9);
    border: 1px solid rgba(255, 255, 255, 0.2);
}

.product-card:hover .btn-group-vertical .btn {
    opacity: 1;
}

.btn {
    border-radius: 8px;
    font-weight: 600;
    transition: all 0.3s ease;
}

.btn-primary:hover, .btn-outline-primary:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(13,110,253,0.3);
}

.badge {
    font-size: 0.8rem;
    padding: 6px 12px;
    border-radius: 8px;
    font-weight: 600;
    backdrop-filter: blur(10px);
    background: rgba(13, 110, 253, 0.9);
    border: 1px solid rgba(255, 255, 255, 0.2);
}

.card-body {
    padding: 1.5rem;
}

.text-truncate {
    font-weight: 600;
    color: #2c3e50;
}

.h5.text-primary {
    color: #e74c3c !important;
    font-weight: 700;
}

.text-warning {
    color: #f39c12 !important;
}

@media (max-width: 768px) {
    .product-card:hover {
        transform: translateY(-4px);
    }
    
    .product-image-container {
        height: 250px;
    }
    
    .btn-group-vertical .btn {
        opacity: 1; /* Always show on mobile */
    }
}
</style>

<script>
// Filter Functions
function applyFilters() {
    const categoryFilter = document.getElementById('categoryFilter').value;
    const priceFrom = document.getElementById('priceFrom').value;
    const priceTo = document.getElementById('priceTo').value;
    const sortBy = document.getElementById('sortBy').value;
    
    // Build query parameters
    const params = new URLSearchParams();
    if (categoryFilter) params.append('category', categoryFilter);
    if (priceFrom) params.append('price_from', priceFrom);
    if (priceTo) params.append('price_to', priceTo);
    if (sortBy) params.append('sort', sortBy);
    
    // Redirect with filters
    window.location.href = '/webbanhang/user/products?' + params.toString();
}

function clearFilters() {
    window.location.href = '/webbanhang/user/products';
}

// Add to Cart Function
function addToCart(productId) {
    const formData = new FormData();
    formData.append('product_id', productId);
    formData.append('quantity', 1);
    
    fetch('/webbanhang/cart/add', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert(data.message);
            // Optionally redirect to cart
            window.location.href = '/webbanhang/cart';
        } else {
            alert(data.message || 'Có lỗi xảy ra');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Có lỗi xảy ra khi thêm vào giỏ hàng');
    });
}

// Search Function
document.getElementById('productSearch').addEventListener('keypress', function(e) {
    if (e.key === 'Enter') {
        const searchTerm = this.value;
        if (searchTerm) {
            window.location.href = '/webbanhang/user/products?search=' + encodeURIComponent(searchTerm);
        }
    }
});
</script>

<?php include_once 'app/views/shares/footer.php'; ?> 
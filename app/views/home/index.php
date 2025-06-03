<?php include_once 'app/views/shares/header.php'; ?>

<!-- Hero Section -->
<div class="row mb-5">
    <div class="col-12">
        <div class="bg-primary text-white rounded-4 p-5 position-relative overflow-hidden">
            <div class="row align-items-center">
                <div class="col-lg-6">
                    <h1 class="display-4 fw-bold mb-4">
                        Chào mừng đến với <br>
                        <span class="text-warning">Cửa Hàng Online</span>
                    </h1>
                    <p class="lead mb-4">
                        Khám phá hàng ngàn sản phẩm chất lượng cao với giá cả hợp lý. 
                        Mua sắm dễ dàng, giao hàng nhanh chóng!
                    </p>
                    <div class="d-flex gap-3">
                        <a href="/webbanhang/user/products" class="btn btn-warning btn-lg">
                            <i class="bi bi-shop me-2"></i>Mua sắm ngay
                        </a>
                        <a href="#featured-products" class="btn btn-outline-light btn-lg">
                            <i class="bi bi-arrow-down me-2"></i>Khám phá
                        </a>
                    </div>
                </div>
                <div class="col-lg-6 text-center">
                    <i class="bi bi-cart-check display-1 opacity-75"></i>
                </div>
            </div>
            <!-- Background decoration -->
            <div class="position-absolute top-0 end-0 opacity-25">
                <i class="bi bi-bag-fill" style="font-size: 15rem; transform: rotate(15deg);"></i>
            </div>
        </div>
    </div>
</div>

<!-- Features Section -->
<div class="row mb-5">
    <div class="col-12">
        <h2 class="text-center fw-bold mb-5">
            <i class="bi bi-star-fill text-warning me-2"></i>
            Tại sao chọn chúng tôi?
        </h2>
    </div>
    <div class="col-md-3 mb-4">
        <div class="card border-0 shadow-sm h-100 text-center p-4">
            <div class="card-body">
                <i class="bi bi-truck display-4 text-primary mb-3"></i>
                <h5 class="fw-bold">Giao hàng miễn phí</h5>
                <p class="text-muted">Miễn phí vận chuyển cho đơn hàng trên 500.000đ</p>
            </div>
        </div>
    </div>
    <div class="col-md-3 mb-4">
        <div class="card border-0 shadow-sm h-100 text-center p-4">
            <div class="card-body">
                <i class="bi bi-shield-check display-4 text-success mb-3"></i>
                <h5 class="fw-bold">Bảo hành chính hãng</h5>
                <p class="text-muted">Cam kết sản phẩm chính hãng 100%</p>
            </div>
        </div>
    </div>
    <div class="col-md-3 mb-4">
        <div class="card border-0 shadow-sm h-100 text-center p-4">
            <div class="card-body">
                <i class="bi bi-arrow-return-left display-4 text-info mb-3"></i>
                <h5 class="fw-bold">Đổi trả dễ dàng</h5>
                <p class="text-muted">Đổi trả trong vòng 7 ngày nếu không hài lòng</p>
            </div>
        </div>
    </div>
    <div class="col-md-3 mb-4">
        <div class="card border-0 shadow-sm h-100 text-center p-4">
            <div class="card-body">
                <i class="bi bi-headset display-4 text-warning mb-3"></i>
                <h5 class="fw-bold">Hỗ trợ 24/7</h5>
                <p class="text-muted">Đội ngũ hỗ trợ khách hàng luôn sẵn sàng</p>
            </div>
        </div>
    </div>
</div>

<!-- Featured Products Section -->
<div class="row mb-5" id="featured-products">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="fw-bold">
                <i class="bi bi-lightning-fill text-warning me-2"></i>
                Sản phẩm nổi bật
            </h2>
            <a href="/webbanhang/user/products" class="btn btn-outline-primary">
                Xem tất cả <i class="bi bi-arrow-right ms-1"></i>
            </a>
        </div>
    </div>
    
    <?php if (!empty($featuredProducts)): ?>
        <?php foreach ($featuredProducts as $product): ?>
            <div class="col-lg-3 col-md-6 mb-4">
                <div class="card h-100 shadow-sm product-card">
                    <div class="position-relative overflow-hidden">
                        <?php 
                        $imagePath = $product->image ? '/webbanhang/public/uploads/products/' . $product->image : 'data:image/svg+xml;base64,' . base64_encode('<svg width="300" height="250" xmlns="http://www.w3.org/2000/svg"><rect width="300" height="250" fill="#f8f9fa"/><circle cx="150" cy="110" r="25" fill="none" stroke="#dee2e6" stroke-width="3"/><path d="M135 110 L150 125 L165 110 M140 115 L160 115" stroke="#dee2e6" stroke-width="3" fill="none"/><text x="150" y="160" text-anchor="middle" font-family="Arial, sans-serif" font-size="16" fill="#6c757d">Không có ảnh</text></svg>');
                        ?>
                        <img src="<?php echo $imagePath; ?>" 
                             class="card-img-top" 
                             alt="<?php echo htmlspecialchars($product->name, ENT_QUOTES, 'UTF-8'); ?>"
                             style="height: 250px; object-fit: cover;">
                        
                        <div class="position-absolute top-0 start-0 p-2">
                            <span class="badge bg-danger">HOT</span>
                        </div>
                        
                        <div class="position-absolute top-0 end-0 p-2">
                            <button class="btn btn-sm btn-light rounded-circle" title="Yêu thích">
                                <i class="bi bi-heart"></i>
                            </button>
                        </div>
                    </div>
                    
                    <div class="card-body">
                        <h6 class="card-title text-truncate">
                            <?php echo htmlspecialchars($product->name, ENT_QUOTES, 'UTF-8'); ?>
                        </h6>
                        <div class="mb-2">
                            <small class="text-muted">
                                <i class="bi bi-tag me-1"></i>
                                <?php echo isset($product->category_name) ? htmlspecialchars($product->category_name, ENT_QUOTES, 'UTF-8') : 'Chưa phân loại'; ?>
                            </small>
                        </div>
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <span class="h6 text-primary fw-bold">
                                <?php echo number_format($product->price, 0, ',', '.'); ?> đ
                            </span>
                            <div class="text-warning small">
                                <i class="bi bi-star-fill"></i>
                                <i class="bi bi-star-fill"></i>
                                <i class="bi bi-star-fill"></i>
                                <i class="bi bi-star-fill"></i>
                                <i class="bi bi-star"></i>
                            </div>
                        </div>
                        <div class="d-grid gap-2">
                            <a href="/webbanhang/product/show/<?php echo $product->id; ?>" 
                               class="btn btn-outline-primary btn-sm">
                                <i class="bi bi-eye me-1"></i>Xem chi tiết
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <div class="col-12">
            <div class="text-center py-5">
                <i class="bi bi-box-seam display-4 text-muted mb-3"></i>
                <h5 class="text-muted">Chưa có sản phẩm nổi bật</h5>
                <p class="text-muted">Sản phẩm sẽ được cập nhật sớm nhất!</p>
            </div>
        </div>
    <?php endif; ?>
</div>

<!-- Categories Section -->
<div class="row mb-5">
    <div class="col-12">
        <h2 class="fw-bold mb-4 text-center">
            <i class="bi bi-grid-3x3-gap text-primary me-2"></i>
            Danh mục sản phẩm
        </h2>
    </div>
    
    <?php if (!empty($categories)): ?>
        <?php foreach ($categories as $category): ?>
            <div class="col-lg-2 col-md-4 col-6 mb-4">
                <a href="/webbanhang/user/products?category=<?php echo $category->id; ?>" 
                   class="text-decoration-none">
                    <div class="card border-0 shadow-sm text-center category-card">
                        <div class="card-body">
                            <i class="bi bi-tag-fill display-4 text-primary mb-3"></i>
                            <h6 class="fw-bold text-dark">
                                <?php echo htmlspecialchars($category->name, ENT_QUOTES, 'UTF-8'); ?>
                            </h6>
                            <small class="text-muted">Khám phá ngay</small>
                        </div>
                    </div>
                </a>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <div class="col-12">
            <div class="text-center py-4">
                <i class="bi bi-tags display-4 text-muted mb-3"></i>
                <h5 class="text-muted">Chưa có danh mục nào</h5>
            </div>
        </div>
    <?php endif; ?>
</div>

<!-- Stats Section -->
<div class="row mb-5">
    <div class="col-12">
        <div class="bg-light rounded-4 p-5">
            <div class="row text-center">
                <div class="col-md-3 mb-4">
                    <h2 class="display-4 fw-bold text-primary">1000+</h2>
                    <p class="text-muted">Sản phẩm</p>
                </div>
                <div class="col-md-3 mb-4">
                    <h2 class="display-4 fw-bold text-success">5000+</h2>
                    <p class="text-muted">Khách hàng hài lòng</p>
                </div>
                <div class="col-md-3 mb-4">
                    <h2 class="display-4 fw-bold text-warning">99%</h2>
                    <p class="text-muted">Đánh giá tích cực</p>
                </div>
                <div class="col-md-3 mb-4">
                    <h2 class="display-4 fw-bold text-info">24/7</h2>
                    <p class="text-muted">Hỗ trợ khách hàng</p>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Newsletter Section -->
<div class="row mb-5">
    <div class="col-12">
        <div class="bg-gradient text-white rounded-4 p-5 text-center" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
            <h2 class="fw-bold mb-3">Đăng ký nhận tin tức</h2>
            <p class="lead mb-4">Nhận thông tin về sản phẩm mới và ưu đãi đặc biệt</p>
            <div class="row justify-content-center">
                <div class="col-md-6">
                    <div class="input-group">
                        <input type="email" class="form-control" placeholder="Nhập email của bạn...">
                        <button class="btn btn-warning fw-bold" type="button">
                            <i class="bi bi-envelope me-1"></i>Đăng ký
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.product-card {
    transition: all 0.3s ease;
    border: none;
    border-radius: 12px;
    overflow: hidden;
}

.product-card:hover {
    transform: translateY(-8px);
    box-shadow: 0 20px 40px rgba(0,0,0,0.15) !important;
}

.category-card {
    transition: all 0.3s ease;
    border-radius: 12px;
}

.category-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 15px 30px rgba(0,0,0,0.1) !important;
}

.hero-bg {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
}

.btn {
    border-radius: 8px;
    font-weight: 600;
}

.card {
    border-radius: 12px;
}

@media (max-width: 768px) {
    .display-4 {
        font-size: 2.5rem;
    }
    
    .product-card:hover, .category-card:hover {
        transform: translateY(-3px);
    }
}
</style>

<script>
// Smooth scrolling for anchor links
document.querySelectorAll('a[href^="#"]').forEach(anchor => {
    anchor.addEventListener('click', function (e) {
        e.preventDefault();
        const target = document.querySelector(this.getAttribute('href'));
        if (target) {
            target.scrollIntoView({
                behavior: 'smooth',
                block: 'start'
            });
        }
    });
});

// Newsletter subscription
document.querySelector('.btn-warning').addEventListener('click', function() {
    const email = this.previousElementSibling.value;
    if (email && email.includes('@')) {
        alert('Cảm ơn bạn đã đăng ký! Chúng tôi sẽ gửi thông tin mới nhất đến email của bạn.');
        this.previousElementSibling.value = '';
    } else {
        alert('Vui lòng nhập địa chỉ email hợp lệ.');
    }
});

// Add animation on scroll
window.addEventListener('scroll', function() {
    const cards = document.querySelectorAll('.card');
    cards.forEach(card => {
        const cardTop = card.getBoundingClientRect().top;
        const cardBottom = card.getBoundingClientRect().bottom;
        
        if (cardTop < window.innerHeight && cardBottom > 0) {
            card.style.opacity = '1';
            card.style.transform = 'translateY(0)';
        }
    });
});

// Initialize cards with fade-in effect
document.addEventListener('DOMContentLoaded', function() {
    const cards = document.querySelectorAll('.card');
    cards.forEach((card, index) => {
        card.style.opacity = '0';
        card.style.transform = 'translateY(20px)';
        card.style.transition = 'all 0.6s ease';
        
        setTimeout(() => {
            card.style.opacity = '1';
            card.style.transform = 'translateY(0)';
        }, index * 100);
    });
});
</script>

<?php include_once 'app/views/shares/footer.php'; ?> 
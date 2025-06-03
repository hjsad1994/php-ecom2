<?php include_once 'app/views/shares/header.php'; ?>

<div class="row mb-4">
    <div class="col-md-8">
        <h1 class="display-6 fw-bold text-primary">
            <i class="bi bi-tags me-2"></i>Danh mục sản phẩm
        </h1>
        <p class="text-muted mb-0">Khám phá sản phẩm theo từng danh mục</p>
    </div>
    <div class="col-md-4 text-end">
        <div class="d-flex justify-content-end align-items-center">
            <!-- Search Box -->
            <div class="input-group me-3" style="max-width: 250px;">
                <input type="text" class="form-control" placeholder="Tìm kiếm danh mục..." id="categorySearch">
                <button class="btn btn-outline-primary" type="button">
                    <i class="bi bi-search"></i>
                </button>
            </div>
        </div>
    </div>
</div>

<?php if (empty($categories)): ?>
    <div class="text-center py-5">
        <i class="bi bi-tags display-1 text-muted mb-3"></i>
        <h3 class="text-muted">Chưa có danh mục nào</h3>
        <p class="text-muted">Hiện tại chưa có danh mục sản phẩm nào được hiển thị.</p>
        <a href="/webbanhang/user/products" class="btn btn-primary">
            <i class="bi bi-grid-3x3-gap me-2"></i>Xem tất cả sản phẩm
        </a>
    </div>
<?php else: ?>
    <!-- Categories Grid -->
    <div class="row">
        <?php foreach ($categories as $category): ?>
            <div class="col-lg-4 col-md-6 mb-4">
                <div class="card h-100 shadow-sm category-card">
                    <!-- Category Image/Icon -->
                    <div class="card-img-top d-flex align-items-center justify-content-center bg-light position-relative" 
                         style="height: 200px;">
                        <?php if (isset($category->image) && $category->image): ?>
                            <img src="/webbanhang/public/uploads/categories/<?php echo $category->image; ?>" 
                                 class="img-fluid" 
                                 alt="<?php echo htmlspecialchars($category->name, ENT_QUOTES, 'UTF-8'); ?>"
                                 style="max-height: 180px; object-fit: cover;">
                        <?php else: ?>
                            <div class="text-center">
                                <i class="bi bi-tag display-4 text-primary mb-3"></i>
                                <h5 class="text-muted"><?php echo htmlspecialchars($category->name, ENT_QUOTES, 'UTF-8'); ?></h5>
                            </div>
                        <?php endif; ?>
                        
                        <!-- Product Count Badge -->
                        <div class="position-absolute top-0 end-0 p-3">
                            <span class="badge bg-primary rounded-pill">
                                <?php echo isset($category->product_count) ? $category->product_count : 0; ?> sản phẩm
                            </span>
                        </div>
                        
                        <!-- Category Overlay -->
                        <div class="category-overlay position-absolute w-100 h-100 d-flex align-items-center justify-content-center">
                            <a href="/webbanhang/user/products?category=<?php echo $category->id; ?>" 
                               class="btn btn-primary btn-lg opacity-0">
                                <i class="bi bi-arrow-right me-2"></i>Xem sản phẩm
                            </a>
                        </div>
                    </div>

                    <!-- Category Info -->
                    <div class="card-body d-flex flex-column">
                        <h5 class="card-title text-center mb-3">
                            <a href="/webbanhang/user/categories/show/<?php echo $category->id; ?>" 
                               class="text-decoration-none text-dark">
                                <?php echo htmlspecialchars($category->name, ENT_QUOTES, 'UTF-8'); ?>
                            </a>
                        </h5>
                        
                        <!-- Description -->
                        <?php if (isset($category->description) && !empty($category->description)): ?>
                            <p class="card-text text-muted text-center small flex-grow-1">
                                <?php 
                                $description = strip_tags($category->description);
                                echo strlen($description) > 120 ? substr($description, 0, 120) . '...' : $description;
                                ?>
                            </p>
                        <?php else: ?>
                            <p class="card-text text-muted text-center small flex-grow-1">
                                Khám phá các sản phẩm trong danh mục này
                            </p>
                        <?php endif; ?>

                        <!-- Category Stats -->
                        <div class="row text-center mb-3">
                            <div class="col-6">
                                <div class="border-end">
                                    <div class="fw-bold text-primary"><?php echo isset($category->product_count) ? $category->product_count : 0; ?></div>
                                    <small class="text-muted">Sản phẩm</small>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="fw-bold text-success">★ 4.5</div>
                                <small class="text-muted">Đánh giá</small>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="d-grid gap-2">
                            <a href="/webbanhang/user/products?category=<?php echo $category->id; ?>" 
                               class="btn btn-primary">
                                <i class="bi bi-grid me-2"></i>Xem sản phẩm
                            </a>
                            <a href="/webbanhang/user/categories/show/<?php echo $category->id; ?>" 
                               class="btn btn-outline-secondary">
                                <i class="bi bi-info-circle me-2"></i>Chi tiết danh mục
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>

    <!-- Popular Categories Section -->
    <div class="row mt-5">
        <div class="col-12">
            <h3 class="fw-bold mb-4">
                <i class="bi bi-fire me-2 text-danger"></i>Danh mục phổ biến
            </h3>
            <div class="row">
                <?php 
                // Show top 3 categories with most products
                $popularCategories = array_slice($categories, 0, 3);
                foreach ($popularCategories as $category): 
                ?>
                    <div class="col-md-4 mb-3">
                        <div class="card border-0 bg-light h-100">
                            <div class="card-body text-center">
                                <i class="bi bi-tag display-6 text-primary mb-3"></i>
                                <h5 class="card-title"><?php echo htmlspecialchars($category->name, ENT_QUOTES, 'UTF-8'); ?></h5>
                                <p class="text-muted small">
                                    <?php echo isset($category->product_count) ? $category->product_count : 0; ?> sản phẩm có sẵn
                                </p>
                                <a href="/webbanhang/user/products?category=<?php echo $category->id; ?>" 
                                   class="btn btn-outline-primary btn-sm">
                                    Khám phá ngay
                                </a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>

    <!-- Categories Quick Links -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h6 class="mb-0">
                        <i class="bi bi-lightning me-2"></i>Truy cập nhanh
                    </h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <?php foreach ($categories as $category): ?>
                            <div class="col-md-3 col-6 mb-2">
                                <a href="/webbanhang/user/products?category=<?php echo $category->id; ?>" 
                                   class="d-block text-decoration-none">
                                    <div class="d-flex align-items-center p-2 rounded hover-bg-light">
                                        <i class="bi bi-tag text-primary me-2"></i>
                                        <span class="small"><?php echo htmlspecialchars($category->name, ENT_QUOTES, 'UTF-8'); ?></span>
                                        <span class="badge bg-light text-dark ms-auto">
                                            <?php echo isset($category->product_count) ? $category->product_count : 0; ?>
                                        </span>
                                    </div>
                                </a>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php endif; ?>

<style>
.category-card {
    transition: all 0.3s ease;
    border: none;
    border-radius: 15px;
}

.category-card:hover {
    transform: translateY(-10px);
    box-shadow: 0 20px 40px rgba(0,0,0,0.1) !important;
}

.category-overlay {
    background: rgba(0,0,0,0.7);
    opacity: 0;
    transition: opacity 0.3s ease;
    border-radius: 15px 15px 0 0;
}

.category-card:hover .category-overlay {
    opacity: 1;
}

.category-card:hover .category-overlay .btn {
    opacity: 1 !important;
    transform: scale(1);
}

.hover-bg-light:hover {
    background-color: #f8f9fa !important;
    transition: background-color 0.2s ease;
}

.card {
    border: none;
    border-radius: 12px;
}

.btn {
    border-radius: 8px;
}

.badge {
    font-size: 0.75rem;
}

.card-img-top {
    border-radius: 15px 15px 0 0;
}
</style>

<script>
// Search functionality
document.getElementById('categorySearch').addEventListener('keypress', function(e) {
    if (e.key === 'Enter') {
        const searchTerm = this.value.toLowerCase();
        const categoryCards = document.querySelectorAll('.category-card');
        
        categoryCards.forEach(card => {
            const categoryName = card.querySelector('.card-title a').textContent.toLowerCase();
            const categoryDescription = card.querySelector('.card-text').textContent.toLowerCase();
            
            if (categoryName.includes(searchTerm) || categoryDescription.includes(searchTerm)) {
                card.closest('.col-lg-4').style.display = 'block';
            } else {
                card.closest('.col-lg-4').style.display = 'none';
            }
        });
        
        // Show/hide empty message
        const visibleCards = Array.from(categoryCards).filter(card => 
            card.closest('.col-lg-4').style.display !== 'none'
        );
        
        if (visibleCards.length === 0 && searchTerm !== '') {
            showNoResultsMessage();
        } else {
            hideNoResultsMessage();
        }
    }
});

// Clear search on input clear
document.getElementById('categorySearch').addEventListener('input', function() {
    if (this.value === '') {
        const categoryCards = document.querySelectorAll('.category-card');
        categoryCards.forEach(card => {
            card.closest('.col-lg-4').style.display = 'block';
        });
        hideNoResultsMessage();
    }
});

function showNoResultsMessage() {
    hideNoResultsMessage(); // Remove existing message
    
    const noResultsHtml = `
        <div id="noResultsMessage" class="col-12 text-center py-5">
            <i class="bi bi-search display-1 text-muted mb-3"></i>
            <h3 class="text-muted">Không tìm thấy danh mục</h3>
            <p class="text-muted">Vui lòng thử từ khóa khác hoặc xem tất cả sản phẩm</p>
            <a href="/webbanhang/user/products" class="btn btn-primary">
                <i class="bi bi-grid-3x3-gap me-2"></i>Xem tất cả sản phẩm
            </a>
        </div>
    `;
    
    document.querySelector('.row').insertAdjacentHTML('beforeend', noResultsHtml);
}

function hideNoResultsMessage() {
    const existingMessage = document.getElementById('noResultsMessage');
    if (existingMessage) {
        existingMessage.remove();
    }
}

// Add smooth scroll for category links
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

// Intersection Observer for animations
const observerOptions = {
    threshold: 0.1,
    rootMargin: '0px 0px -50px 0px'
};

const observer = new IntersectionObserver(function(entries) {
    entries.forEach(entry => {
        if (entry.isIntersecting) {
            entry.target.style.opacity = '1';
            entry.target.style.transform = 'translateY(0)';
        }
    });
}, observerOptions);

// Observe all category cards
document.querySelectorAll('.category-card').forEach(card => {
    card.style.opacity = '0';
    card.style.transform = 'translateY(20px)';
    card.style.transition = 'opacity 0.6s ease, transform 0.6s ease';
    observer.observe(card);
});
</script>

<?php include_once 'app/views/shares/footer.php'; ?> 
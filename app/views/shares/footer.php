</div>
    
<footer class="py-4 mt-5 bg-light">
    <div class="container">
        <div class="row">
            <div class="col-md-6">
                <h5 class="mb-3 fw-bold">Ứng dụng quản lý sản phẩm</h5>
                <p class="text-muted">Hệ thống quản lý sản phẩm và danh mục hiệu quả, dễ sử dụng.</p>
            </div>
            <div class="col-md-3">
                <h5 class="mb-3 fw-bold">Liên kết</h5>
                <ul class="nav flex-column">
                    <li class="nav-item mb-2"><a href="/webbanhang/Product/" class="nav-link p-0 text-muted">Sản phẩm</a></li>
                    <li class="nav-item mb-2"><a href="/webbanhang/Category/" class="nav-link p-0 text-muted">Danh mục</a></li>
                </ul>
            </div>
            <div class="col-md-3">
                <h5 class="mb-3 fw-bold">Liên hệ</h5>
                <ul class="nav flex-column">
                    <li class="nav-item mb-2 text-muted"><i class="bi bi-envelope me-2"></i>support@example.com</li>
                    <li class="nav-item mb-2 text-muted"><i class="bi bi-telephone me-2"></i>+84 0778946513</li>
                </ul>
            </div>
        </div>
        <div class="d-flex flex-column flex-sm-row justify-content-between py-4 mt-4 border-top">
            <p>&copy; <?php echo date('Y'); ?> Quản lý sản phẩm. All rights reserved.</p>
            <ul class="list-unstyled d-flex">
                <li class="ms-3"><a class="text-muted" href="#"><i class="bi bi-facebook fs-5"></i></a></li>
                <li class="ms-3"><a class="text-muted" href="#"><i class="bi bi-instagram fs-5"></i></a></li>
                <li class="ms-3"><a class="text-muted" href="#"><i class="bi bi-twitter fs-5"></i></a></li>
            </ul>
        </div>
    </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
<script>
// Add animation on page load
document.addEventListener('DOMContentLoaded', function() {
    const cards = document.querySelectorAll('.card');
    cards.forEach((card, index) => {
        setTimeout(() => {
            card.style.opacity = '1';
            card.style.transform = 'translateY(0)';
        }, 100 * index);
    });
});
</script>
</body>
</html>
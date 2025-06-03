<?php include_once 'app/views/shares/header.php'; ?>

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-6 text-center">
            <div class="card border-danger">
                <div class="card-body">
                    <i class="bi bi-shield-x text-danger" style="font-size: 4rem;"></i>
                    <h1 class="mt-3 text-danger">403</h1>
                    <h4>Truy cập bị từ chối</h4>
                    <p class="text-muted">
                        Bạn không có quyền truy cập vào trang này.
                        <br>Vui lòng đăng nhập với tài khoản có quyền phù hợp.
                    </p>
                    <div class="mt-4">
                        <a href="/webbanhang" class="btn btn-primary me-2">
                            <i class="bi bi-house"></i> Về trang chủ
                        </a>
                        <a href="/webbanhang/account/login" class="btn btn-success">
                            <i class="bi bi-box-arrow-in-right"></i> Đăng nhập
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include_once 'app/views/shares/footer.php'; ?> 
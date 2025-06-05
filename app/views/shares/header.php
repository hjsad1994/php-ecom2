<?php
require_once 'app/helpers/SessionHelper.php';
require_once 'app/helpers/AuthHelper.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Website Bán Hàng</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Roboto', sans-serif;
            background-color: #f8f9fa;
        }
        
        .navbar {
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        
        .card {
            transition: transform 0.3s, box-shadow 0.3s;
            border: none;
            border-radius: 10px;
            overflow: hidden;
        }
        
        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0,0,0,0.1);
        }
        
        .btn {
            border-radius: 5px;
            font-weight: 500;
        }
        
        .btn-success {
            background-color: #28a745;
            border-color: #28a745;
            box-shadow: 0 4px 6px rgba(40, 167, 69, 0.2);
        }
        
        .btn-success:hover {
            background-color: #218838;
            border-color: #1e7e34;
            transform: translateY(-1px);
        }
        
        .btn-primary {
            box-shadow: 0 4px 6px rgba(0, 123, 255, 0.2);
        }
        
        .btn-primary:hover {
            transform: translateY(-1px);
        }
        
        .btn-danger {
            box-shadow: 0 4px 6px rgba(220, 53, 69, 0.2);
        }
        
        .btn-danger:hover {
            transform: translateY(-1px);
        }
        
        .btn-info {
            box-shadow: 0 4px 6px rgba(23, 162, 184, 0.2);
        }
        
        .btn-info:hover {
            transform: translateY(-1px);
        }
        
        h1, h2, h3, h4, h5, h6 {
            font-weight: 700;
            color: #343a40;
        }
        
        .card-title {
            font-weight: 600;
            color: #212529;
        }
        
        .card-text {
            color: #6c757d;
        }
        
        .badge {
            font-weight: 500;
            padding: 5px 10px;
            border-radius: 30px;
        }
        
        .table thead {
            background-color: #f8f9fa;
        }
        
        .alert {
            border-radius: 10px;
            border: none;
        }
        
        .form-control:focus {
            box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.15);
        }
        
        .form-select:focus {
            box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.15);
        }
        
        .nav-link {
            font-weight: 500;
            white-space: nowrap;
        }
        
        .nav-link:hover {
            color: #fff !important;
            background-color: rgba(255,255,255,0.1);
            border-radius: 5px;
        }
        
        .active-nav {
            background-color: rgba(255,255,255,0.2);
            border-radius: 5px;
        }
        
        .container {
            max-width: 1200px∏
        }
        
        .user-greeting {
            color: rgba(255,255,255,0.9);
            font-weight: 500;
            white-space: nowrap;
        }
        
        .admin-badge {
            background-color: #ffc107;
            color: #000;
            padding: 2px 8px;
            border-radius: 10px;
            font-size: 11px;
            font-weight: 600;
        }
        
        .user-badge {
            background-color: #17a2b8;
            color: #fff;
            padding: 2px 8px;
            border-radius: 10px;
            font-size: 11px;
            font-weight: 600;
        }
        
        .product-image {
            max-width: 100px;
            height: auto;
        }
        
        .admin-navbar {
            background: linear-gradient(135deg, #2c3e50 0%, #34495e 25%, #3498db 75%, #2980b9 100%);
        }
        
        .user-navbar {
            background: linear-gradient(135deg, #2c3e50 0%, #34495e 25%, #3498db 75%, #2980b9 100%);
        }
        
        .dropdown-menu {
            background-color: rgba(255,255,255,0.95);
            backdrop-filter: blur(10px);
            border: none;
            box-shadow: 0 8px 32px rgba(0,0,0,0.2);
        }
        
        .dropdown-item:hover {
            background-color: rgba(0,0,0,0.05);
        }
        
        .navbar-nav .nav-item {
            margin: 0 2px;
        }
        
        .compact-nav .nav-link {
            padding: 0.4rem 0.6rem !important;
            font-size: 0.85rem;
        }
        
        .navbar-toggler {
            border: none;
            padding: 0.25rem 0.5rem;
        }
        
        .navbar-toggler:focus {
            box-shadow: none;
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark mb-4 py-2 <?php echo AuthHelper::isAdmin() ? 'admin-navbar' : 'user-navbar'; ?>">
        <div class="container">
            <a class="navbar-brand d-flex align-items-center" href="/webbanhang">
                <i class="bi bi-shop me-2 fs-4"></i>
                <span class="fw-bold">
                    <?php if (AuthHelper::isAdmin()): ?>
                        Admin Panel
                    <?php else: ?>
                        Cửa Hàng Online
                    <?php endif; ?>
                </span>
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto compact-nav">
                    
                    <?php if (AuthHelper::isAdmin()): ?>
                        <!-- ADMIN MENU - Flat Structure -->
                        <li class="nav-item">
                            <a class="nav-link" href="/webbanhang/admin/dashboard">
                                <i class="bi bi-speedometer2 me-1"></i>Dashboard
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="/webbanhang/admin/products">
                                <i class="bi bi-box me-1"></i>Sản phẩm
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="/webbanhang/admin/categories">
                                <i class="bi bi-tags me-1"></i>Danh mục
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="/webbanhang/admin/vouchers">
                                <i class="bi bi-ticket-perforated me-1"></i>Voucher
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="/webbanhang/admin/orders">
                                <i class="bi bi-list-check me-1"></i>Đơn hàng
                            </a>
                        </li>
                        
                    <?php elseif (AuthHelper::isUser()): ?>
                        <!-- USER MENU - Shopping Functions -->
                        <li class="nav-item">
                            <a class="nav-link" href="/webbanhang/product">
                                <i class="bi bi-grid me-1"></i>Sản phẩm
                            </a>
                        </li>
                        <!-- Categories temporarily removed for simplified UX -->
                        <!-- <li class="nav-item">
                            <a class="nav-link" href="/webbanhang/category">
                                <i class="bi bi-tag me-1"></i>Danh mục
                            </a>
                        </li> -->
                        <li class="nav-item">
                            <a class="nav-link position-relative" href="/webbanhang/user/cart">
                                <i class="bi bi-cart me-1"></i>Giỏ hàng
                                <?php if (isset($_SESSION['cart']) && !empty($_SESSION['cart'])): ?>
                                    <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                                        <?php echo array_sum(array_column($_SESSION['cart'], 'quantity')); ?>
                                    </span>
                                <?php endif; ?>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="/webbanhang/user/orders">
                                <i class="bi bi-receipt me-1"></i>Đơn hàng
                            </a>
                        </li>
                        
                    <?php else: ?>
                        <!-- GUEST MENU - Public Access -->
                        <li class="nav-item">
                            <a class="nav-link" href="/webbanhang/product">
                                <i class="bi bi-grid me-1"></i>Sản phẩm
                            </a>
                        </li>
                        <!-- Categories temporarily removed for simplified UX -->
                        <!-- <li class="nav-item">
                            <a class="nav-link" href="/webbanhang/category">
                                <i class="bi bi-tag me-1"></i>Danh mục
                            </a>
                        </li> -->
                    <?php endif; ?>
                    
                    <!-- Authentication Links -->
                    <?php if (SessionHelper::isLoggedIn()): ?>
                        <!-- User is logged in -->
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" role="button" data-bs-toggle="dropdown">
                                <i class="bi bi-person-circle me-1"></i>
                                <?= htmlspecialchars(SessionHelper::getUsername()) ?>
                                <?php if (SessionHelper::isAdmin()): ?>
                                    <span class="admin-badge ms-1">ADMIN</span>
                                <?php else: ?>
                                    <span class="user-badge ms-1">USER</span>
                                <?php endif; ?>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li><a class="dropdown-item" href="/webbanhang/account/profile">
                                    <i class="bi bi-person me-2"></i>Hồ sơ
                                </a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li><a class="dropdown-item" href="/webbanhang/account/logout">
                                    <i class="bi bi-box-arrow-right me-2"></i>Đăng xuất
                                </a></li>
                            </ul>
                        </li>
                    <?php else: ?>
                        <!-- User is not logged in -->
                        <li class="nav-item">
                            <a class="nav-link <?php echo (strpos($_SERVER['REQUEST_URI'], '/account/login') !== false) ? 'active-nav' : ''; ?>" href="/webbanhang/account/login">
                                <i class="bi bi-box-arrow-in-right me-1"></i>Đăng nhập
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link <?php echo (strpos($_SERVER['REQUEST_URI'], '/account/register') !== false) ? 'active-nav' : ''; ?>" href="/webbanhang/account/register">
                                <i class="bi bi-person-plus me-1"></i>Đăng ký
                            </a>
                        </li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </nav>
    <div class="container mt-4">
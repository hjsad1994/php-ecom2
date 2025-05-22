<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản lý sản phẩm</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Roboto', sans-serif;
            background-color: #f8f9fa;
            padding-bottom: 50px;
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
            max-width: 1200px;
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary mb-4 py-3">
        <div class="container">
            <a class="navbar-brand d-flex align-items-center" href="/webbanhang">
                <i class="bi bi-shop me-2 fs-3"></i>
                <span class="fw-bold">Quản lý sản phẩm</span>
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item mx-1">
                        <a class="nav-link px-3 py-2 <?php echo (strpos($_SERVER['REQUEST_URI'], '/Product') !== false && strpos($_SERVER['REQUEST_URI'], '/Product/add') === false) ? 'active-nav' : ''; ?>" href="/webbanhang/Product/">
                            <i class="bi bi-grid me-1"></i> Sản phẩm
                        </a>
                    </li>
                    <li class="nav-item mx-1">
                        <a class="nav-link px-3 py-2 <?php echo (strpos($_SERVER['REQUEST_URI'], '/Product/add') !== false) ? 'active-nav' : ''; ?>" href="/webbanhang/Product/add">
                            <i class="bi bi-plus-circle me-1"></i> Thêm SP
                        </a>
                    </li>
                    <li class="nav-item mx-1">
                        <a class="nav-link px-3 py-2 <?php echo (strpos($_SERVER['REQUEST_URI'], '/Category') !== false) ? 'active-nav' : ''; ?>" href="/webbanhang/Category/">
                            <i class="bi bi-tag me-1"></i> Danh mục
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
    <div class="container mt-4">
<?php

// API Entry Point cho RESTful API

// Error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Include autoloader hoặc require controllers
require_once __DIR__ . '/app/controllers/ApiController.php';
require_once __DIR__ . '/app/controllers/AuthApiController.php';
require_once __DIR__ . '/app/controllers/ProductApiController.php';
require_once __DIR__ . '/app/controllers/CategoryApiController.php';
require_once __DIR__ . '/app/controllers/VoucherApiController.php';
require_once __DIR__ . '/app/controllers/AccountManagementApiController.php';

// Start output buffering để tránh header conflicts
ob_start();

try {
    // Parse URL để xác định endpoint từ REQUEST_URI
    $requestUri = $_SERVER['REQUEST_URI'] ?? '';
    
    // Remove query string
    $requestUri = strtok($requestUri, '?');
    
    // Get the path after /webbanhang/
    $basePath = '/webbanhang/';
    $path = '';
    
    if (strpos($requestUri, $basePath) === 0) {
        $path = substr($requestUri, strlen($basePath));
    } else {
        $path = $requestUri;
    }
    
    $path = trim($path, '/');
    
    // Parse API path
    $pathSegments = explode('/', $path);
    
    // Kiểm tra API prefix
    if (!isset($pathSegments[0]) || $pathSegments[0] !== 'api') {
        header('Content-Type: application/json');
        http_response_code(404);
        echo json_encode([
            'success' => false,
            'status_code' => 404,
            'message' => 'API endpoint not found. Use /api/{resource}',
            'data' => null,
            'timestamp' => date('Y-m-d H:i:s')
        ], JSON_UNESCAPED_UNICODE);
        exit;
    }
    
    // Remove 'api' prefix
    array_shift($pathSegments);
    
    // Xác định resource
    $resource = $pathSegments[0] ?? '';
    
    // Set PATH_INFO cho controllers
    $_SERVER['PATH_INFO'] = '/' . implode('/', $pathSegments);
    
    // Route đến appropriate controller
    switch ($resource) {
        case 'auth':
            $controller = new AuthApiController();
            $controller->handleRequest();
            break;
            
        case 'products':
            $controller = new ProductApiController();
            $controller->handleRequest();
            break;
            
        case 'categories':
            $controller = new CategoryApiController();
            $controller->handleRequest();
            break;
            
        case 'vouchers':
            $controller = new VoucherApiController();
            $controller->handleRequest();
            break;
            
        case 'accounts':
            $controller = new AccountManagementApiController();
            $controller->handleRequest();
            break;
            
        case 'cart':
            // TODO: Implement CartApiController
            header('Content-Type: application/json');
            http_response_code(501);
            echo json_encode([
                'success' => false,
                'status_code' => 501,
                'message' => 'Cart API not implemented yet',
                'data' => null,
                'timestamp' => date('Y-m-d H:i:s')
            ], JSON_UNESCAPED_UNICODE);
            break;
            
        case 'orders':
            // TODO: Implement OrderApiController
            header('Content-Type: application/json');
            http_response_code(501);
            echo json_encode([
                'success' => false,
                'status_code' => 501,
                'message' => 'Orders API not implemented yet',
                'data' => null,
                'timestamp' => date('Y-m-d H:i:s')
            ], JSON_UNESCAPED_UNICODE);
            break;
            
        case 'vouchers':
            // TODO: Implement VoucherApiController
            header('Content-Type: application/json');
            http_response_code(501);
            echo json_encode([
                'success' => false,
                'status_code' => 501,
                'message' => 'Vouchers API not implemented yet',
                'data' => null,
                'timestamp' => date('Y-m-d H:i:s')
            ], JSON_UNESCAPED_UNICODE);
            break;
            
        case '':
            // API Documentation
            header('Content-Type: application/json');
            echo json_encode([
                'success' => true,
                'status_code' => 200,
                'message' => 'Website Bán Hàng RESTful API',
                'data' => [
                    'version' => '1.0.0',
                    'endpoints' => [
                        'auth' => [
                            'POST /api/auth/login' => 'Đăng nhập',
                            'POST /api/auth/register' => 'Đăng ký',
                            'POST /api/auth/logout' => 'Đăng xuất',
                            'GET /api/auth/me' => 'Thông tin user hiện tại',
                            'POST /api/auth/refresh' => 'Làm mới session'
                        ],
                        'products' => [
                            'GET /api/products' => 'Danh sách sản phẩm (với pagination)',
                            'GET /api/products/{id}' => 'Chi tiết sản phẩm',
                            'POST /api/products' => 'Tạo sản phẩm (admin only)',
                            'PUT /api/products/{id}' => 'Cập nhật sản phẩm (admin only)',
                            'DELETE /api/products/{id}' => 'Xóa sản phẩm (admin only)'
                        ],
                        'categories' => [
                            'GET /api/categories' => 'Danh sách danh mục',
                            'GET /api/categories/{id}' => 'Chi tiết danh mục',
                            'POST /api/categories' => 'Tạo danh mục (admin only)',
                            'PUT /api/categories/{id}' => 'Cập nhật danh mục (admin only)',
                            'DELETE /api/categories/{id}' => 'Xóa danh mục (admin only)'
                        ],
                        'vouchers' => [
                            'GET /api/vouchers' => 'Danh sách vouchers (với pagination)',
                            'GET /api/vouchers/{id}' => 'Chi tiết voucher',
                            'GET /api/vouchers/stats' => 'Thống kê vouchers',
                            'POST /api/vouchers' => 'Tạo voucher (admin only)',
                            'POST /api/vouchers/validate' => 'Validate voucher với cart',
                            'PUT /api/vouchers/{id}' => 'Cập nhật voucher (admin only)',
                            'DELETE /api/vouchers/{id}' => 'Xóa voucher (admin only)'
                        ],
                        'accounts' => [
                            'GET /api/accounts' => 'Danh sách tài khoản (admin only)',
                            'GET /api/accounts/{id}' => 'Chi tiết tài khoản (admin only)',
                            'GET /api/accounts/stats' => 'Thống kê tài khoản (admin only)',
                            'POST /api/accounts' => 'Tạo tài khoản (admin only)',
                            'PUT /api/accounts/{id}' => 'Cập nhật tài khoản (admin only)',
                            'DELETE /api/accounts/{id}' => 'Xóa tài khoản (admin only)'
                        ],
                        'cart' => [
                            'status' => 'Coming soon'
                        ],
                        'orders' => [
                            'status' => 'Coming soon'
                        ],
                        'vouchers' => [
                            'status' => 'Coming soon'
                        ]
                    ],
                    'authentication' => 'Session-based authentication',
                    'response_format' => [
                        'success' => 'boolean',
                        'status_code' => 'integer',
                        'message' => 'string',
                        'data' => 'object|array|null',
                        'timestamp' => 'string (Y-m-d H:i:s)'
                    ]
                ],
                'timestamp' => date('Y-m-d H:i:s')
            ], JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
            break;
            
        default:
            header('Content-Type: application/json');
            http_response_code(404);
            echo json_encode([
                'success' => false,
                'status_code' => 404,
                'message' => "API resource '{$resource}' not found",
                'data' => [
                    'available_resources' => ['auth', 'products', 'categories', 'vouchers', 'accounts', 'cart', 'orders'],
                    'note' => 'Currently implemented: auth, products, categories, vouchers, accounts'
                ],
                'timestamp' => date('Y-m-d H:i:s')
            ], JSON_UNESCAPED_UNICODE);
    }
    
} catch (Exception $e) {
    // Clean output buffer
    ob_clean();
    
    // Log error
    error_log("API Error: " . $e->getMessage() . " in " . $e->getFile() . " on line " . $e->getLine());
    
    // Return error response
    header('Content-Type: application/json');
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'status_code' => 500,
        'message' => 'Internal server error',
        'data' => null,
        'timestamp' => date('Y-m-d H:i:s')
    ], JSON_UNESCAPED_UNICODE);
}

// End output buffering
ob_end_flush(); 
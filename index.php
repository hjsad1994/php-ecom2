<?php
// Start session at the beginning of each request
session_start();
require_once 'app/models/ProductModel.php';
require_once 'app/helpers/SessionHelper.php';
require_once 'app/helpers/AuthHelper.php';

// Get URL parameter
$url = $_GET['url'] ?? '';
$url = rtrim($url, '/');
$url = filter_var($url, FILTER_SANITIZE_URL);
$url = explode('/', $url);

// Determine controller name - default to ProductController if empty
$controllerName = isset($url[0]) && $url[0] != '' ? ucfirst($url[0]) . 'Controller' : 'ProductController';

// Determine action - default to index if empty
$action = isset($url[1]) && $url[1] != '' ? $url[1] : 'index';

// Special routing for admin panel
if (isset($url[0]) && $url[0] === 'admin') {
    $controllerName = 'AdminController';
    $action = isset($url[1]) && $url[1] != '' ? $url[1] : 'dashboard';
    
    // For admin sub-actions like /admin/products/create
    if (isset($url[2]) && $url[2] != '') {
        if (in_array($url[1], ['products', 'categories', 'vouchers', 'orders'])) {
            // Special case for orders view
            if ($url[1] === 'orders' && $url[2] === 'view') {
                $action = 'viewOrder';
                // Pass ID as third parameter
                if (isset($url[3])) {
                    $url[2] = $url[3];
                }
            } 
            // Special case for categories show
            elseif ($url[1] === 'categories' && $url[2] === 'show') {
                $action = 'showCategory';
                // Pass ID as third parameter
                if (isset($url[3])) {
                    $url[2] = $url[3];
                }
            }
            // Special case for delete actions with ID  
            elseif ($url[2] === 'delete' && isset($url[3])) {
                // Map plural to singular properly
                $singular = ($url[1] === 'categories') ? 'Category' : ucfirst(rtrim($url[1], 's'));
                $action = 'delete' . $singular; // e.g., deleteCategory, deleteVoucher
                $url[2] = $url[3]; // Pass ID as parameter
            }
            // Special case for edit actions with ID
            elseif ($url[2] === 'edit' && isset($url[3])) {
                // Map plural to singular properly
                $singular = ($url[1] === 'categories') ? 'Category' : ucfirst(rtrim($url[1], 's'));
                $action = 'edit' . $singular; // e.g., editCategory, editVoucher
                $url[2] = $url[3]; // Pass ID as parameter
            }
            // Special case for update actions with ID
            elseif ($url[2] === 'update' && isset($url[3])) {
                // Map plural to singular properly
                $singular = ($url[1] === 'categories') ? 'Category' : ucfirst(rtrim($url[1], 's'));
                $action = 'update' . $singular; // e.g., updateCategory, updateVoucher
                $url[2] = $url[3]; // Pass ID as parameter
            }
            // Special case for create actions
            elseif ($url[2] === 'create') {
                // Map plural to singular properly
                $singular = ($url[1] === 'categories') ? 'Category' : ucfirst(rtrim($url[1], 's'));
                $action = 'create' . $singular; // e.g., createCategory, createVoucher
            }
            // Special case for store actions
            elseif ($url[2] === 'store') {
                // Map plural to singular properly
                $singular = ($url[1] === 'categories') ? 'Category' : ucfirst(rtrim($url[1], 's'));
                $action = 'store' . $singular; // e.g., storeCategory, storeVoucher
            }
            // Default action mapping
            else {
                $action = $url[2] . ucfirst(rtrim($url[1], 's')); // e.g., createProduct, editProduct
                if (isset($url[3])) {
                    // Pass ID as parameter for edit/delete actions
                    $url[2] = $url[3];
                }
            }
        }
    } else {
        // Map admin sub-routes
        switch ($url[1] ?? '') {
            case 'products':
                $action = 'products';
                break;
            case 'categories':
                $action = 'categories';
                break;
            case 'vouchers':
                $action = 'vouchers';
                break;
            case 'orders':
                $action = 'orders';
                break;
            default:
                $action = 'dashboard';
        }
    }
}

// Special routing for order management
if (isset($url[0]) && $url[0] === 'order') {
    $controllerName = 'OrderController';
    $action = isset($url[1]) && $url[1] != '' ? $url[1] : 'index';
}

// Check if controller file exists
if (!file_exists('app/controllers/' . $controllerName . '.php')) {
    // Handle controller not found
    http_response_code(404);
    die('Trang không tồn tại');
}

// Include and instantiate the controller
require_once 'app/controllers/' . $controllerName . '.php';

// Check if controller class exists
if (!class_exists($controllerName)) {
    http_response_code(500);
    die('Lỗi hệ thống');
}

$controller = new $controllerName();

// Check if action method exists
if (!method_exists($controller, $action)) {
    // Handle action not found
    http_response_code(404);
    die('Trang không tồn tại');
}

// Call the action with remaining parameters (if any)
call_user_func_array([$controller, $action], array_slice($url, 2));
?>
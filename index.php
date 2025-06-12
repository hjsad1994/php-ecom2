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

// Handle API routes first
if (isset($url[0]) && $url[0] === 'api') {
    switch ($url[1] ?? '') {
        case 'validate-voucher':
            include 'api_validate_voucher.php';
            exit;
        default:
            http_response_code(404);
            echo json_encode(['error' => 'API endpoint not found']);
            exit;
    }
}

// Handle empty URL (homepage)
if (empty($url[0])) {
    $controllerName = 'HomeController';
    $action = 'index';
} else {
    // Determine controller name - default to ProductController if empty
    $controllerName = isset($url[0]) && $url[0] != '' ? ucfirst($url[0]) . 'Controller' : 'ProductController';
    
    // Determine action - default to index if empty
    $action = isset($url[1]) && $url[1] != '' ? $url[1] : 'index';
}

// Special routing for account management
if (isset($url[0]) && $url[0] === 'account') {
    $controllerName = 'AccountController';
    $action = isset($url[1]) && $url[1] != '' ? $url[1] : 'index';
    
    // Handle hyphenated actions
    if ($action === 'forgot-password') {
        $action = $_SERVER['REQUEST_METHOD'] === 'POST' ? 'processForgotPassword' : 'forgotPassword';
    } elseif ($action === 'reset-password') {
        $action = $_SERVER['REQUEST_METHOD'] === 'POST' ? 'processResetPassword' : 'resetPassword';
    } elseif ($action === 'process-reset-password') {
        $action = 'processResetPassword';
    } elseif ($action === 'change-password') {
        $action = 'changePassword';
    }
}

// Special routing for admin panel
if (isset($url[0]) && $url[0] === 'admin') {
    $controllerName = 'AdminController';
    $action = isset($url[1]) && $url[1] != '' ? $url[1] : 'dashboard';
    
    // For admin sub-actions like /admin/products/create
            if (isset($url[2]) && $url[2] != '') {
            if (in_array($url[1], ['products', 'categories', 'vouchers', 'orders', 'accounts'])) {
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
            // Special case for toggle status actions with ID (for accounts)
            elseif ($url[2] === 'toggle-status' && isset($url[3])) {
                $singular = ($url[1] === 'accounts') ? 'Account' : ucfirst(rtrim($url[1], 's'));
                $action = 'toggle' . $singular . 'Status'; // e.g., toggleAccountStatus
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
            case 'accounts':
                $action = 'accounts';
                break;
            default:
                $action = 'dashboard';
        }
    }
}

// Special routing for cart management
if (isset($url[0]) && $url[0] === 'cart') {
    $controllerName = 'CartController';
    $action = isset($url[1]) && $url[1] != '' ? $url[1] : 'index';
    
    // Handle hyphenated actions
    if ($action === 'apply-voucher') {
        $action = 'applyVoucher';
    } elseif ($action === 'remove-voucher') {
        $action = 'removeVoucher';
    }
}

// Special routing for order management
if (isset($url[0]) && $url[0] === 'order') {
    $controllerName = 'OrderController';
    $action = isset($url[1]) && $url[1] != '' ? $url[1] : 'index';
    
    // Map create action
    if ($action === 'create') {
        $action = 'create';
    }
    // Map confirm action with ID
    elseif ($action === 'confirm') {
        $action = 'confirm';
        // If there's an ID after confirm, pass it as parameter
        if (isset($url[2]) && $url[2] != '') {
            // Keep the ID for the confirm method
        }
    }
    // Map payment processing actions
    elseif ($action === 'processPayment') {
        $action = 'processPayment';
    }
    elseif ($action === 'cancel') {
        $action = 'cancel';
    }
    elseif ($action === 'reorder') {
        $action = 'reorder';
    }
}

// Special routing for checkout
if (isset($url[0]) && $url[0] === 'checkout') {
    $controllerName = 'OrderController';
    $action = 'checkout';
}

// Special routing for user section
if (isset($url[0]) && $url[0] === 'user') {
    switch ($url[1] ?? '') {
        case 'products':
            $controllerName = 'ProductController';
            $action = isset($url[2]) && $url[2] != '' ? $url[2] : 'userIndex';
            break;
        case 'categories':
            $controllerName = 'CategoryController';
            $action = isset($url[2]) && $url[2] != '' ? $url[2] : 'userIndex';
            break;
        case 'orders':
            $controllerName = 'OrderController';
            if (isset($url[2]) && $url[2] != '') {
                // Handle user order sub-actions: view, payment, invoice, create
                if ($url[2] === 'view' && isset($url[3])) {
                    $action = 'view';
                    $url[2] = $url[3]; // Pass order ID as parameter
                } elseif ($url[2] === 'payment' && isset($url[3])) {
                    $action = 'payment';
                    $url[2] = $url[3]; // Pass order ID as parameter
                } elseif ($url[2] === 'invoice' && isset($url[3])) {
                    $action = 'invoice';
                    $url[2] = $url[3]; // Pass order ID as parameter
                } elseif ($url[2] === 'create') {
                    $action = 'create';
                } else {
                    $action = $url[2];
                }
            } else {
                $action = 'userIndex';
            }
            break;
        case 'cart':
            $controllerName = 'CartController';
            $action = isset($url[2]) && $url[2] != '' ? $url[2] : 'index';
            
            // Handle hyphenated actions
            if ($action === 'apply-voucher') {
                $action = 'applyVoucher';
            } elseif ($action === 'remove-voucher') {
                $action = 'removeVoucher';
            }
            break;
        case 'profile':
            $controllerName = 'AccountController';
            if (isset($url[2]) && $url[2] === 'update') {
                $action = 'updateProfile';
            } else {
                $action = isset($url[2]) && $url[2] != '' ? $url[2] : 'profile';
            }
            break;
        default:
            $controllerName = 'ProductController';
            $action = 'userIndex';
    }
}

// Check if controller name is for API endpoints
if ($controllerName === 'ProductController' && isset($url[1]) && $url[1] === 'api') {
    $action = isset($url[2]) && $url[2] != '' ? 'api' . ucfirst($url[2]) : 'api';
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
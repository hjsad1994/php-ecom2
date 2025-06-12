<?php

require_once(__DIR__ . '/../config/database.php');
require_once(__DIR__ . '/../models/VoucherModel.php');
require_once(__DIR__ . '/ApiController.php');

class VoucherApiController extends ApiController
{
    private $voucherModel;
    private $db;

    public function __construct()
    {
        $this->db = (new Database())->getConnection();
        $this->voucherModel = new VoucherModel($this->db);
    }

    public function handleRequest()
    {
        $method = $_SERVER['REQUEST_METHOD'];
        $pathInfo = $_SERVER['PATH_INFO'] ?? '';
        $pathSegments = explode('/', trim($pathInfo, '/'));
        
        // Extract ID/action from path - find 'vouchers' and get next segment
        $vouchersIndex = array_search('vouchers', $pathSegments);
        $actionOrId = null;
        if ($vouchersIndex !== false && isset($pathSegments[$vouchersIndex + 1])) {
            $actionOrId = $pathSegments[$vouchersIndex + 1];
        }

        switch ($method) {
            case 'GET':
                if ($actionOrId === 'stats') {
                    $this->getStats();
                } elseif ($actionOrId && is_numeric($actionOrId)) {
                    $this->show($actionOrId);
                } else {
                    $this->index();
                }
                break;
                
            case 'POST':
                if ($actionOrId === 'validate') {
                    $this->validateVoucher();
                } else {
                    $this->store();
                }
                break;
                
            case 'PUT':
                if ($actionOrId && is_numeric($actionOrId)) {
                    $this->update($actionOrId);
                } else {
                    $this->sendError('ID is required for PUT requests', 400);
                }
                break;
                
            case 'DELETE':
                if ($actionOrId && is_numeric($actionOrId)) {
                    $this->destroy($actionOrId);
                } else {
                    $this->sendError('ID is required for DELETE requests', 400);
                }
                break;
                
            default:
                $this->sendError('Method not allowed', 405);
        }
    }

    // GET /api/vouchers - Lấy danh sách vouchers
    public function index()
    {
        try {
            $page = $_GET['page'] ?? 1;
            $limit = $_GET['limit'] ?? 10;
            $offset = ($page - 1) * $limit;

            $vouchers = $this->voucherModel->getVouchers();
            
            // Simple pagination
            $totalVouchers = count($vouchers);
            $paginatedVouchers = array_slice($vouchers, $offset, $limit);
            
            $pagination = [
                'current_page' => (int)$page,
                'per_page' => (int)$limit,
                'total' => $totalVouchers,
                'total_pages' => ceil($totalVouchers / $limit),
                'has_next' => $page < ceil($totalVouchers / $limit),
                'has_prev' => $page > 1
            ];

            $this->sendResponse([
                'vouchers' => $paginatedVouchers,
                'pagination' => $pagination
            ], 'Vouchers retrieved successfully');
            
        } catch (Exception $e) {
            $this->sendError('Failed to retrieve vouchers: ' . $e->getMessage(), 500);
        }
    }

    // GET /api/vouchers/{id} - Lấy thông tin voucher theo ID
    public function show($id)
    {
        try {
            if (!is_numeric($id)) {
                $this->sendError('Invalid voucher ID', 400);
            }

            $voucher = $this->voucherModel->getVoucherById($id);
            
            if ($voucher) {
                $this->sendResponse($voucher, 'Voucher retrieved successfully');
            } else {
                $this->sendError('Voucher not found', 404);
            }
            
        } catch (Exception $e) {
            $this->sendError('Failed to retrieve voucher: ' . $e->getMessage(), 500);
        }
    }

    // POST /api/vouchers - Tạo voucher mới
    public function store()
    {
        try {
            $data = $this->getJsonInput();
            
            if (!$data) {
                $this->sendError('Invalid JSON data', 400);
            }

            $required = ['code', 'name', 'discount_type', 'discount_value', 'start_date', 'end_date'];
            $missing = $this->validateRequired($data, $required);
            
            if (!empty($missing)) {
                $this->sendError('Missing required fields: ' . implode(', ', $missing), 400);
            }

            // Prepare voucher data with defaults
            $voucherData = [
                'code' => strtoupper(trim($data['code'])),
                'name' => trim($data['name']),
                'description' => $data['description'] ?? '',
                'discount_type' => $data['discount_type'],
                'discount_value' => $data['discount_value'],
                'min_order_amount' => $data['min_order_amount'] ?? 0,
                'max_discount_amount' => $data['max_discount_amount'] ?? null,
                'applies_to' => $data['applies_to'] ?? 'all_products',
                'product_ids' => isset($data['product_ids']) ? json_encode($data['product_ids']) : null,
                'category_ids' => isset($data['category_ids']) ? json_encode($data['category_ids']) : null,
                'usage_limit' => $data['usage_limit'] ?? null,
                'start_date' => $data['start_date'],
                'end_date' => $data['end_date'],
                'is_active' => $data['is_active'] ?? 1
            ];

            // Validate discount type and value
            if (!in_array($voucherData['discount_type'], ['percentage', 'fixed'])) {
                $this->sendError('Invalid discount type. Must be percentage or fixed', 400);
            }

            if (!is_numeric($voucherData['discount_value']) || $voucherData['discount_value'] <= 0) {
                $this->sendError('Discount value must be a positive number', 400);
            }

            if ($voucherData['discount_type'] === 'percentage' && $voucherData['discount_value'] > 100) {
                $this->sendError('Percentage discount cannot exceed 100%', 400);
            }

            $result = $this->voucherModel->addVoucher($voucherData);

            if (is_array($result)) {
                // Validation errors
                $this->sendError('Validation failed', 400, $result);
            } else if ($result) {
                $this->sendResponse(null, 'Voucher created successfully', 201);
            } else {
                $this->sendError('Failed to create voucher', 500);
            }
            
        } catch (Exception $e) {
            $this->sendError('Failed to create voucher: ' . $e->getMessage(), 500);
        }
    }

    // PUT /api/vouchers/{id} - Cập nhật voucher theo ID
    public function update($id)
    {
        try {
            if (!is_numeric($id)) {
                $this->sendError('Invalid voucher ID', 400);
            }

            // Check if voucher exists
            $existingVoucher = $this->voucherModel->getVoucherById($id);
            if (!$existingVoucher) {
                $this->sendError('Voucher not found', 404);
            }

            $data = $this->getJsonInput();
            
            if (!$data) {
                $this->sendError('Invalid JSON data', 400);
            }

            // Prepare update data (only fields that are provided)
            $updateData = [];
            
            if (isset($data['code'])) {
                $updateData['code'] = strtoupper(trim($data['code']));
            }
            if (isset($data['name'])) {
                $updateData['name'] = trim($data['name']);
            }
            if (isset($data['description'])) {
                $updateData['description'] = $data['description'];
            }
            if (isset($data['discount_type'])) {
                if (!in_array($data['discount_type'], ['percentage', 'fixed'])) {
                    $this->sendError('Invalid discount type. Must be percentage or fixed', 400);
                }
                $updateData['discount_type'] = $data['discount_type'];
            }
            if (isset($data['discount_value'])) {
                if (!is_numeric($data['discount_value']) || $data['discount_value'] <= 0) {
                    $this->sendError('Discount value must be a positive number', 400);
                }
                $updateData['discount_value'] = $data['discount_value'];
            }
            if (isset($data['min_order_amount'])) {
                $updateData['min_order_amount'] = $data['min_order_amount'];
            }
            if (isset($data['max_discount_amount'])) {
                $updateData['max_discount_amount'] = $data['max_discount_amount'];
            }
            if (isset($data['applies_to'])) {
                $updateData['applies_to'] = $data['applies_to'];
            }
            if (isset($data['product_ids'])) {
                $updateData['product_ids'] = json_encode($data['product_ids']);
            }
            if (isset($data['category_ids'])) {
                $updateData['category_ids'] = json_encode($data['category_ids']);
            }
            if (isset($data['usage_limit'])) {
                $updateData['usage_limit'] = $data['usage_limit'];
            }
            if (isset($data['start_date'])) {
                $updateData['start_date'] = $data['start_date'];
            }
            if (isset($data['end_date'])) {
                $updateData['end_date'] = $data['end_date'];
            }
            if (isset($data['is_active'])) {
                $updateData['is_active'] = $data['is_active'];
            }

            // Additional validation for percentage
            if (isset($updateData['discount_type']) && $updateData['discount_type'] === 'percentage' 
                && isset($updateData['discount_value']) && $updateData['discount_value'] > 100) {
                $this->sendError('Percentage discount cannot exceed 100%', 400);
            }

            $result = $this->voucherModel->updateVoucher($id, $updateData);

            if (is_array($result)) {
                // Validation errors
                $this->sendError('Validation failed', 400, $result);
            } else if ($result) {
                $this->sendResponse(null, 'Voucher updated successfully');
            } else {
                $this->sendError('Failed to update voucher', 500);
            }
            
        } catch (Exception $e) {
            $this->sendError('Failed to update voucher: ' . $e->getMessage(), 500);
        }
    }

    // DELETE /api/vouchers/{id} - Xóa voucher theo ID
    public function destroy($id)
    {
        try {
            if (!is_numeric($id)) {
                $this->sendError('Invalid voucher ID', 400);
            }

            // Check if voucher exists
            $existingVoucher = $this->voucherModel->getVoucherById($id);
            if (!$existingVoucher) {
                $this->sendError('Voucher not found', 404);
            }

            $result = $this->voucherModel->deleteVoucher($id);

            if ($result) {
                $this->sendResponse(null, 'Voucher deleted successfully');
            } else {
                $this->sendError('Failed to delete voucher', 500);
            }
            
        } catch (Exception $e) {
            $this->sendError('Failed to delete voucher: ' . $e->getMessage(), 500);
        }
    }

    // POST /api/vouchers/validate - Validate voucher với cart data
    public function validateVoucher()
    {
        try {
            $data = $this->getJsonInput();
            
            if (!$data) {
                $this->sendError('Invalid JSON data', 400);
            }

            $required = ['code', 'cart_total'];
            $missing = $this->validateRequired($data, $required);
            
            if (!empty($missing)) {
                $this->sendError('Missing required fields: ' . implode(', ', $missing), 400);
            }

            $code = strtoupper(trim($data['code']));
            $cartTotal = $data['cart_total'];
            $productIds = $data['product_ids'] ?? [];

            // Validate cart total
            if (!is_numeric($cartTotal) || $cartTotal < 0) {
                $this->sendError('Invalid cart total', 400);
            }

            $validation = $this->voucherModel->validateVoucher($code, $cartTotal, $productIds);

            if ($validation['valid']) {
                // Calculate discount amount
                $discount = $this->voucherModel->calculateDiscount($validation['voucher'], $cartTotal);
                
                $this->sendResponse([
                    'valid' => true,
                    'voucher' => $validation['voucher'],
                    'discount_amount' => $discount,
                    'final_total' => max(0, $cartTotal - $discount)
                ], 'Voucher is valid');
            } else {
                $this->sendResponse([
                    'valid' => false,
                    'message' => $validation['message']
                ], 'Voucher validation failed', 200);
            }
            
        } catch (Exception $e) {
            $this->sendError('Failed to validate voucher: ' . $e->getMessage(), 500);
        }
    }

    // GET /api/vouchers/stats - Lấy thống kê vouchers
    public function getStats()
    {
        try {
            $stats = $this->voucherModel->getVoucherStats();
            $discountTypeStats = $this->voucherModel->getDiscountTypeStats();
            $topUsedVouchers = $this->voucherModel->getTopUsedVouchers(5);
            $expiringSoon = $this->voucherModel->getExpiringSoonVouchers(7);

            $this->sendResponse([
                'general_stats' => $stats,
                'discount_type_stats' => $discountTypeStats,
                'top_used_vouchers' => $topUsedVouchers,
                'expiring_soon' => $expiringSoon
            ], 'Voucher statistics retrieved successfully');
            
        } catch (Exception $e) {
            $this->sendError('Failed to retrieve voucher statistics: ' . $e->getMessage(), 500);
        }
    }
} 
<?php

require_once(__DIR__ . '/../config/database.php');
require_once(__DIR__ . '/../models/AccountModel.php');
require_once(__DIR__ . '/ApiController.php');

class AccountManagementApiController extends ApiController
{
    private $accountModel;
    private $db;

    public function __construct()
    {
        $this->db = (new Database())->getConnection();
        $this->accountModel = new AccountModel($this->db);
    }

    public function handleRequest()
    {
        // Check admin authentication
        if (!$this->requireAdminAuth()) {
            return;
        }

        $method = $_SERVER['REQUEST_METHOD'];
        $pathInfo = $_SERVER['PATH_INFO'] ?? '';
        $pathSegments = explode('/', trim($pathInfo, '/'));
        
        // Extract ID/action from path - find 'accounts' and get next segment
        $accountsIndex = array_search('accounts', $pathSegments);
        $actionOrId = null;
        if ($accountsIndex !== false && isset($pathSegments[$accountsIndex + 1])) {
            $actionOrId = $pathSegments[$accountsIndex + 1];
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
                $this->store();
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

    private function requireAdminAuth()
    {
        try {
            if (session_status() === PHP_SESSION_NONE) {
                session_start();
            }

            if (!isset($_SESSION['logged_in']) || !$_SESSION['logged_in']) {
                $this->sendError('Authentication required', 401);
                return false;
            }

            if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
                $this->sendError('Admin access required', 403);
                return false;
            }

            return true;
        } catch (Exception $e) {
            $this->sendError('Authentication check failed', 500);
            return false;
        }
    }

    // GET /api/accounts - Lấy danh sách tài khoản (admin only)
    public function index()
    {
        try {
            $page = $_GET['page'] ?? 1;
            $limit = $_GET['limit'] ?? 10;
            $role = $_GET['role'] ?? null; // Filter by role
            $search = $_GET['search'] ?? null; // Search by username, email, fullname

            $accounts = $this->accountModel->getAllAccounts();
            
            // Apply filters
            if ($role) {
                $accounts = array_filter($accounts, function($account) use ($role) {
                    return $account['role'] === $role;
                });
            }

            if ($search) {
                $searchLower = strtolower($search);
                $accounts = array_filter($accounts, function($account) use ($searchLower) {
                    return strpos(strtolower($account['username']), $searchLower) !== false ||
                           strpos(strtolower($account['fullname']), $searchLower) !== false ||
                           strpos(strtolower($account['email'] ?? ''), $searchLower) !== false;
                });
            }

            // Remove passwords from response
            $accounts = array_map(function($account) {
                unset($account['password']);
                return $account;
            }, $accounts);

            // Simple pagination
            $totalAccounts = count($accounts);
            $offset = ($page - 1) * $limit;
            $paginatedAccounts = array_slice($accounts, $offset, $limit);
            
            $pagination = [
                'current_page' => (int)$page,
                'per_page' => (int)$limit,
                'total' => $totalAccounts,
                'total_pages' => ceil($totalAccounts / $limit),
                'has_next' => $page < ceil($totalAccounts / $limit),
                'has_prev' => $page > 1
            ];

            $this->sendResponse([
                'accounts' => array_values($paginatedAccounts),
                'pagination' => $pagination,
                'filters' => [
                    'role' => $role,
                    'search' => $search
                ]
            ], 'Accounts retrieved successfully');
            
        } catch (Exception $e) {
            $this->sendError('Failed to retrieve accounts: ' . $e->getMessage(), 500);
        }
    }

    // GET /api/accounts/{id} - Lấy thông tin tài khoản theo ID (admin only)
    public function show($id)
    {
        try {
            if (!is_numeric($id)) {
                $this->sendError('Invalid account ID', 400);
            }

            $account = $this->accountModel->getById($id);
            
            if ($account) {
                // Remove password from response
                unset($account['password']);
                $this->sendResponse($account, 'Account retrieved successfully');
            } else {
                $this->sendError('Account not found', 404);
            }
            
        } catch (Exception $e) {
            $this->sendError('Failed to retrieve account: ' . $e->getMessage(), 500);
        }
    }

    // POST /api/accounts - Tạo tài khoản mới (admin only)
    public function store()
    {
        try {
            $data = $this->getJsonInput();
            
            if (!$data) {
                $this->sendError('Invalid JSON data', 400);
            }

            $required = ['username', 'password', 'fullname'];
            $missing = $this->validateRequired($data, $required);
            
            if (!empty($missing)) {
                $this->sendError('Missing required fields: ' . implode(', ', $missing), 400);
            }

            $username = trim($data['username']);
            $password = $data['password'];
            $fullname = trim($data['fullname']);
            $email = $data['email'] ?? '';
            $phone = $data['phone'] ?? '';
            $address = $data['address'] ?? '';
            $role = $data['role'] ?? 'customer';

            // Validate role
            if (!in_array($role, ['admin', 'customer'])) {
                $this->sendError('Invalid role. Must be admin or customer', 400);
            }

            // Validate email format if provided
            if (!empty($email) && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $this->sendError('Invalid email format', 400);
            }

            // Check if username already exists
            if ($this->accountModel->checkUsernameExists($username)) {
                $this->sendError('Username already exists', 409);
            }

            // Check if email already exists (if provided)
            if (!empty($email) && $this->accountModel->checkEmailExists($email)) {
                $this->sendError('Email already exists', 409);
            }

            // Validate password strength
            if (strlen($password) < 6) {
                $this->sendError('Password must be at least 6 characters long', 400);
            }

            $result = $this->accountModel->save($username, $password, $fullname, $email, $phone, $address, $role);

            if ($result) {
                $this->sendResponse(null, 'Account created successfully', 201);
            } else {
                $this->sendError('Failed to create account', 500);
            }
            
        } catch (Exception $e) {
            $this->sendError('Failed to create account: ' . $e->getMessage(), 500);
        }
    }

    // PUT /api/accounts/{id} - Cập nhật tài khoản theo ID (admin only)
    public function update($id)
    {
        try {
            if (!is_numeric($id)) {
                $this->sendError('Invalid account ID', 400);
            }

            // Check if account exists
            $existingAccount = $this->accountModel->getById($id);
            if (!$existingAccount) {
                $this->sendError('Account not found', 404);
            }

            $data = $this->getJsonInput();
            
            if (!$data) {
                $this->sendError('Invalid JSON data', 400);
            }

            // Prepare update data
            $updateData = [];

            if (isset($data['username'])) {
                $username = trim($data['username']);
                if ($this->accountModel->checkUsernameExists($username, $id)) {
                    $this->sendError('Username already exists', 409);
                }
                $updateData['username'] = $username;
            }

            if (isset($data['password']) && !empty($data['password'])) {
                if (strlen($data['password']) < 6) {
                    $this->sendError('Password must be at least 6 characters long', 400);
                }
                $updateData['password'] = password_hash($data['password'], PASSWORD_BCRYPT, ['cost' => 12]);
            }

            if (isset($data['fullname'])) {
                $updateData['fullname'] = trim($data['fullname']);
            }

            if (isset($data['email'])) {
                $email = trim($data['email']);
                if (!empty($email)) {
                    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                        $this->sendError('Invalid email format', 400);
                    }
                    if ($this->accountModel->checkEmailExists($email, $id)) {
                        $this->sendError('Email already exists', 409);
                    }
                }
                $updateData['email'] = $email;
            }

            if (isset($data['phone'])) {
                $updateData['phone'] = trim($data['phone']);
            }

            if (isset($data['address'])) {
                $updateData['address'] = trim($data['address']);
            }

            if (isset($data['role'])) {
                if (!in_array($data['role'], ['admin', 'customer'])) {
                    $this->sendError('Invalid role. Must be admin or customer', 400);
                }
                
                // Prevent removing last admin
                if ($existingAccount['role'] === 'admin' && $data['role'] !== 'admin') {
                    $adminCount = count(array_filter($this->accountModel->getAllAccounts(), function($account) {
                        return $account['role'] === 'admin';
                    }));
                    
                    if ($adminCount <= 1) {
                        $this->sendError('Cannot remove the last admin account', 400);
                    }
                }
                
                $updateData['role'] = $data['role'];
            }

            if (empty($updateData)) {
                $this->sendError('No data provided for update', 400);
            }

            $result = $this->accountModel->updateAccount($id, $updateData);

            if ($result) {
                $this->sendResponse(null, 'Account updated successfully');
            } else {
                $this->sendError('Failed to update account', 500);
            }
            
        } catch (Exception $e) {
            $this->sendError('Failed to update account: ' . $e->getMessage(), 500);
        }
    }

    // DELETE /api/accounts/{id} - Xóa tài khoản theo ID (admin only)
    public function destroy($id)
    {
        try {
            if (!is_numeric($id)) {
                $this->sendError('Invalid account ID', 400);
            }

            // Check if account exists
            $existingAccount = $this->accountModel->getById($id);
            if (!$existingAccount) {
                $this->sendError('Account not found', 404);
            }

            // Prevent self-deletion
            if (isset($_SESSION['user_id']) && $_SESSION['user_id'] == $id) {
                $this->sendError('Cannot delete your own account', 400);
            }

            // Prevent deleting last admin
            if ($existingAccount['role'] === 'admin') {
                $adminCount = count(array_filter($this->accountModel->getAllAccounts(), function($account) {
                    return $account['role'] === 'admin';
                }));
                
                if ($adminCount <= 1) {
                    $this->sendError('Cannot delete the last admin account', 400);
                }
            }

            $result = $this->accountModel->deleteAccount($id);

            if ($result) {
                $this->sendResponse(null, 'Account deleted successfully');
            } else {
                $this->sendError('Failed to delete account', 500);
            }
            
        } catch (Exception $e) {
            $this->sendError('Failed to delete account: ' . $e->getMessage(), 500);
        }
    }

    // GET /api/accounts/stats - Lấy thống kê tài khoản (admin only)
    public function getStats()
    {
        try {
            $stats = $this->accountModel->getAccountStats();
            
            $this->sendResponse($stats, 'Account statistics retrieved successfully');
            
        } catch (Exception $e) {
            $this->sendError('Failed to retrieve account statistics: ' . $e->getMessage(), 500);
        }
    }
} 
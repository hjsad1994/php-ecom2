<?php

require_once(__DIR__ . '/../config/database.php');
require_once(__DIR__ . '/../models/AccountModel.php');
require_once(__DIR__ . '/ApiController.php');

class AuthApiController extends ApiController
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
        $method = $_SERVER['REQUEST_METHOD'];
        $pathInfo = $_SERVER['PATH_INFO'] ?? '';
        $pathSegments = explode('/', trim($pathInfo, '/'));
        
        // Extract action from path - find 'auth' and get next segment
        $authIndex = array_search('auth', $pathSegments);
        $action = '';
        if ($authIndex !== false && isset($pathSegments[$authIndex + 1])) {
            $action = $pathSegments[$authIndex + 1];
        }

        switch ($method) {
            case 'POST':
                switch ($action) {
                    case 'login':
                        $this->login();
                        break;
                    case 'register':
                        $this->register();
                        break;
                    case 'logout':
                        $this->logout();
                        break;
                    case 'refresh':
                        $this->refresh();
                        break;
                    default:
                        $this->sendError('Invalid auth action', 400);
                }
                break;
                
            case 'GET':
                if ($action === 'me') {
                    $this->me();
                } else {
                    $this->sendError('Invalid auth action', 400);
                }
                break;
                
            default:
                $this->sendError('Method not allowed', 405);
        }
    }

    // POST /api/auth/login - Đăng nhập
    public function login()
    {
        try {
            $data = $this->getJsonInput();
            
            if (!$data) {
                $this->sendError('Invalid JSON data', 400);
            }

            // Accept both email and username for login
            $identifier = $data['email'] ?? $data['username'] ?? '';
            $password = $data['password'] ?? '';
            
            if (empty($identifier) || empty($password)) {
                $this->sendError('Username/email and password are required', 400);
            }

            // Try to find user by email first, then by username
            $user = null;
            if (filter_var($identifier, FILTER_VALIDATE_EMAIL)) {
                $user = $this->accountModel->getAccountByEmail($identifier);
            }
            
            if (!$user) {
                $user = $this->accountModel->getAccountByUsername($identifier);
            }
            
            if (!$user || !password_verify($password, $user->password)) {
                $this->sendError('Invalid credentials', 401);
            }

            // Start session
            if (session_status() === PHP_SESSION_NONE) {
                session_start();
            }
            
            $_SESSION['user_id'] = $user->id;
            $_SESSION['user_email'] = $user->email ?? '';
            $_SESSION['user_username'] = $user->username ?? '';
            $_SESSION['user_role'] = $user->role ?? 'customer';
            $_SESSION['logged_in'] = true;

            // Convert object to array and remove password
            $userData = (array) $user;
            unset($userData['password']);

            $this->sendResponse([
                'user' => $userData,
                'session_id' => session_id()
            ], 'Login successful');
            
        } catch (Exception $e) {
            $this->sendError('Login failed: ' . $e->getMessage(), 500);
        }
    }

    // POST /api/auth/register - Đăng ký
    public function register()
    {
        try {
            $data = $this->getJsonInput();
            
            if (!$data) {
                $this->sendError('Invalid JSON data', 400);
            }

            // For registration, username is required (can also accept email)
            $username = $data['username'] ?? $data['email'] ?? '';
            $password = $data['password'] ?? '';
            $fullName = $data['full_name'] ?? $data['fullname'] ?? '';
            $email = $data['email'] ?? '';
            $phone = $data['phone'] ?? '';
            $address = $data['address'] ?? '';

            if (empty($username) || empty($password) || empty($fullName)) {
                $this->sendError('Username, password, and full name are required', 400);
            }

            // Validate email format if provided
            if (!empty($email) && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $this->sendError('Invalid email format', 400);
            }

            // Check if username already exists
            if ($this->accountModel->getAccountByUsername($username)) {
                $this->sendError('Username already exists', 409);
            }

            // Check if email already exists (if provided)
            if (!empty($email) && $this->accountModel->getAccountByEmail($email)) {
                $this->sendError('Email already exists', 409);
            }

            // Validate password strength
            if (strlen($password) < 6) {
                $this->sendError('Password must be at least 6 characters long', 400);
            }

            $result = $this->accountModel->save($username, $password, $fullName, $email, $phone, $address, 'customer');

            if ($result) {
                $this->sendResponse(null, 'Registration successful', 201);
            } else {
                $this->sendError('Registration failed', 500);
            }
            
        } catch (Exception $e) {
            $this->sendError('Registration failed: ' . $e->getMessage(), 500);
        }
    }

    // POST /api/auth/logout - Đăng xuất
    public function logout()
    {
        try {
            if (session_status() === PHP_SESSION_NONE) {
                session_start();
            }
            
            session_destroy();
            
            $this->sendResponse(null, 'Logout successful');
            
        } catch (Exception $e) {
            $this->sendError('Logout failed: ' . $e->getMessage(), 500);
        }
    }

    // GET /api/auth/me - Thông tin user hiện tại
    public function me()
    {
        try {
            if (session_status() === PHP_SESSION_NONE) {
                session_start();
            }
            
            if (!isset($_SESSION['logged_in']) || !$_SESSION['logged_in']) {
                $this->sendError('Not authenticated', 401);
            }

            $userId = $_SESSION['user_id'];
            $user = $this->accountModel->getById($userId);
            
            if (!$user) {
                $this->sendError('User not found', 404);
            }

            // Remove password from response
            unset($user['password']);

            $this->sendResponse($user, 'User information retrieved successfully');
            
        } catch (Exception $e) {
            $this->sendError('Failed to get user info: ' . $e->getMessage(), 500);
        }
    }

    // POST /api/auth/refresh - Làm mới session
    public function refresh()
    {
        try {
            if (session_status() === PHP_SESSION_NONE) {
                session_start();
            }
            
            if (!isset($_SESSION['logged_in']) || !$_SESSION['logged_in']) {
                $this->sendError('Not authenticated', 401);
            }

            // Regenerate session ID for security
            session_regenerate_id(true);

            $this->sendResponse([
                'session_id' => session_id()
            ], 'Session refreshed successfully');
            
        } catch (Exception $e) {
            $this->sendError('Session refresh failed: ' . $e->getMessage(), 500);
        }
    }
}
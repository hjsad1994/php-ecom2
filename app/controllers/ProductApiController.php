<?php

require_once(__DIR__ . '/../config/database.php');
require_once(__DIR__ . '/../models/ProductModel.php');
require_once(__DIR__ . '/ApiController.php');

class ProductApiController extends ApiController
{
    private $productModel;
    private $db;

    public function __construct()
    {
        $this->db = (new Database())->getConnection();
        $this->productModel = new ProductModel($this->db);
    }

    public function handleRequest()
    {
        $method = $_SERVER['REQUEST_METHOD'];
        $pathInfo = $_SERVER['PATH_INFO'] ?? '';
        $pathSegments = explode('/', trim($pathInfo, '/'));
        
        // Extract ID from path - find 'products' and get next segment  
        $productsIndex = array_search('products', $pathSegments);
        $id = null;
        if ($productsIndex !== false && isset($pathSegments[$productsIndex + 1])) {
            $id = $pathSegments[$productsIndex + 1];
        }

        switch ($method) {
            case 'GET':
                if ($id) {
                    $this->show($id);
                } else {
                    $this->index();
                }
                break;
                
            case 'POST':
                $this->store();
                break;
                
            case 'PUT':
                if ($id) {
                    $this->update($id);
                } else {
                    $this->sendError('ID is required for PUT requests', 400);
                }
                break;
                
            case 'DELETE':
                if ($id) {
                    $this->destroy($id);
                } else {
                    $this->sendError('ID is required for DELETE requests', 400);
                }
                break;
                
            default:
                $this->sendError('Method not allowed', 405);
        }
    }

    // GET /api/products - Lấy danh sách sản phẩm
    public function index()
    {
        try {
            $page = $_GET['page'] ?? 1;
            $limit = $_GET['limit'] ?? 10;
            $offset = ($page - 1) * $limit;

            $products = $this->productModel->getProducts();
            
            // Simple pagination
            $totalProducts = count($products);
            $paginatedProducts = array_slice($products, $offset, $limit);
            
            $pagination = [
                'current_page' => (int)$page,
                'per_page' => (int)$limit,
                'total' => $totalProducts,
                'total_pages' => ceil($totalProducts / $limit),
                'has_next' => $page < ceil($totalProducts / $limit),
                'has_prev' => $page > 1
            ];

            $this->sendResponse([
                'products' => $paginatedProducts,
                'pagination' => $pagination
            ], 'Products retrieved successfully');
            
        } catch (Exception $e) {
            $this->sendError('Failed to retrieve products: ' . $e->getMessage(), 500);
        }
    }

    // GET /api/products/{id} - Lấy thông tin sản phẩm theo ID
    public function show($id)
    {
        try {
            if (!is_numeric($id)) {
                $this->sendError('Invalid product ID', 400);
            }

            $product = $this->productModel->getProductById($id);
            
            if ($product) {
                $this->sendResponse($product, 'Product retrieved successfully');
            } else {
                $this->sendError('Product not found', 404);
            }
            
        } catch (Exception $e) {
            $this->sendError('Failed to retrieve product: ' . $e->getMessage(), 500);
        }
    }

    // POST /api/products - Thêm sản phẩm mới
    public function store()
    {
        try {
            $data = $this->getJsonInput();
            
            if (!$data) {
                $this->sendError('Invalid JSON data', 400);
            }

            $required = ['name', 'description', 'price'];
            $missing = $this->validateRequired($data, $required);
            
            if (!empty($missing)) {
                $this->sendError('Missing required fields: ' . implode(', ', $missing), 400);
            }

            $name = $data['name'];
            $description = $data['description'];
            $price = $data['price'];
            $category_id = $data['category_id'] ?? null;

            // Validate price
            if (!is_numeric($price) || $price < 0) {
                $this->sendError('Invalid price format', 400);
            }

            $result = $this->productModel->addProduct($name, $description, $price, $category_id);

            if (is_array($result)) {
                // Validation errors
                $this->sendError('Validation failed', 400, $result);
            } else if ($result) {
                $this->sendResponse(null, 'Product created successfully', 201);
            } else {
                $this->sendError('Failed to create product', 500);
            }
            
        } catch (Exception $e) {
            $this->sendError('Failed to create product: ' . $e->getMessage(), 500);
        }
    }

    // PUT /api/products/{id} - Cập nhật sản phẩm theo ID
    public function update($id)
    {
        try {
            if (!is_numeric($id)) {
                $this->sendError('Invalid product ID', 400);
            }

            // Check if product exists
            $existingProduct = $this->productModel->getProductById($id);
            if (!$existingProduct) {
                $this->sendError('Product not found', 404);
            }

            $data = $this->getJsonInput();
            
            if (!$data) {
                $this->sendError('Invalid JSON data', 400);
            }

            $name = $data['name'] ?? $existingProduct->name;
            $description = $data['description'] ?? $existingProduct->description;
            $price = $data['price'] ?? $existingProduct->price;
            $category_id = $data['category_id'] ?? $existingProduct->category_id;

            // Validate price if provided
            if (isset($data['price']) && (!is_numeric($price) || $price < 0)) {
                $this->sendError('Invalid price format', 400);
            }

            $result = $this->productModel->updateProduct($id, $name, $description, $price, $category_id);

            if ($result) {
                $this->sendResponse(null, 'Product updated successfully');
            } else {
                $this->sendError('Failed to update product', 500);
            }
            
        } catch (Exception $e) {
            $this->sendError('Failed to update product: ' . $e->getMessage(), 500);
        }
    }

    // DELETE /api/products/{id} - Xóa sản phẩm theo ID
    public function destroy($id)
    {
        try {
            if (!is_numeric($id)) {
                $this->sendError('Invalid product ID', 400);
            }

            // Check if product exists
            $existingProduct = $this->productModel->getProductById($id);
            if (!$existingProduct) {
                $this->sendError('Product not found', 404);
            }

            $result = $this->productModel->deleteProduct($id);

            if ($result) {
                $this->sendResponse(null, 'Product deleted successfully');
            } else {
                $this->sendError('Failed to delete product', 500);
            }
            
        } catch (Exception $e) {
            $this->sendError('Failed to delete product: ' . $e->getMessage(), 500);
        }
    }
} 
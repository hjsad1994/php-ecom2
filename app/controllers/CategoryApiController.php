<?php

require_once(__DIR__ . '/../config/database.php');
require_once(__DIR__ . '/../models/CategoryModel.php');
require_once(__DIR__ . '/ApiController.php');

class CategoryApiController extends ApiController
{
    private $categoryModel;
    private $db;

    public function __construct()
    {
        $this->db = (new Database())->getConnection();
        $this->categoryModel = new CategoryModel($this->db);
    }

    public function handleRequest()
    {
        $method = $_SERVER['REQUEST_METHOD'];
        $pathInfo = $_SERVER['PATH_INFO'] ?? '';
        $pathSegments = explode('/', trim($pathInfo, '/'));
        
        // Extract ID from path - find 'categories' and get next segment
        $categoriesIndex = array_search('categories', $pathSegments);
        $id = null;
        if ($categoriesIndex !== false && isset($pathSegments[$categoriesIndex + 1])) {
            $id = $pathSegments[$categoriesIndex + 1];
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

    // GET /api/categories - Lấy danh sách danh mục
    public function index()
    {
        try {
            $categories = $this->categoryModel->getCategories();
            $this->sendResponse($categories, 'Categories retrieved successfully');
            
        } catch (Exception $e) {
            $this->sendError('Failed to retrieve categories: ' . $e->getMessage(), 500);
        }
    }

    // GET /api/categories/{id} - Lấy thông tin danh mục theo ID
    public function show($id)
    {
        try {
            if (!is_numeric($id)) {
                $this->sendError('Invalid category ID', 400);
            }

            $category = $this->categoryModel->getCategoryById($id);
            
            if ($category) {
                $this->sendResponse($category, 'Category retrieved successfully');
            } else {
                $this->sendError('Category not found', 404);
            }
            
        } catch (Exception $e) {
            $this->sendError('Failed to retrieve category: ' . $e->getMessage(), 500);
        }
    }

    // POST /api/categories - Thêm danh mục mới
    public function store()
    {
        try {
            $data = $this->getJsonInput();
            
            if (!$data) {
                $this->sendError('Invalid JSON data', 400);
            }

            $required = ['name'];
            $missing = $this->validateRequired($data, $required);
            
            if (!empty($missing)) {
                $this->sendError('Missing required fields: ' . implode(', ', $missing), 400);
            }

            $name = $data['name'];
            $description = $data['description'] ?? '';
            $parent_id = $data['parent_id'] ?? null;

            $result = $this->categoryModel->addCategory($name, $description, $parent_id);

            if ($result) {
                $this->sendResponse(null, 'Category created successfully', 201);
            } else {
                $this->sendError('Failed to create category', 500);
            }
            
        } catch (Exception $e) {
            $this->sendError('Failed to create category: ' . $e->getMessage(), 500);
        }
    }

    // PUT /api/categories/{id} - Cập nhật danh mục theo ID
    public function update($id)
    {
        try {
            if (!is_numeric($id)) {
                $this->sendError('Invalid category ID', 400);
            }

            // Check if category exists
            $existingCategory = $this->categoryModel->getCategoryById($id);
            if (!$existingCategory) {
                $this->sendError('Category not found', 404);
            }

            $data = $this->getJsonInput();
            
            if (!$data) {
                $this->sendError('Invalid JSON data', 400);
            }

            $name = $data['name'] ?? $existingCategory->name;
            $description = $data['description'] ?? ($existingCategory->description ?? '');
            $parent_id = $data['parent_id'] ?? ($existingCategory->parent_id ?? null);

            $result = $this->categoryModel->updateCategory($id, $name, $description, $parent_id);

            if ($result) {
                $this->sendResponse(null, 'Category updated successfully');
            } else {
                $this->sendError('Failed to update category', 500);
            }
            
        } catch (Exception $e) {
            $this->sendError('Failed to update category: ' . $e->getMessage(), 500);
        }
    }

    // DELETE /api/categories/{id} - Xóa danh mục theo ID
    public function destroy($id)
    {
        try {
            if (!is_numeric($id)) {
                $this->sendError('Invalid category ID', 400);
            }

            // Check if category exists
            $existingCategory = $this->categoryModel->getCategoryById($id);
            if (!$existingCategory) {
                $this->sendError('Category not found', 404);
            }

            $result = $this->categoryModel->deleteCategory($id);

            if ($result) {
                $this->sendResponse(null, 'Category deleted successfully');
            } else {
                $this->sendError('Failed to delete category', 500);
            }
            
        } catch (Exception $e) {
            $this->sendError('Failed to delete category: ' . $e->getMessage(), 500);
        }
    }
} 
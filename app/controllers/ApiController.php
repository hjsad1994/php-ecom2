<?php

class ApiController
{
    protected function sendResponse($data = null, $message = 'Success', $statusCode = 200)
    {
        header('Content-Type: application/json');
        http_response_code($statusCode);
        
        $response = [
            'success' => $statusCode >= 200 && $statusCode < 300,
            'status_code' => $statusCode,
            'message' => $message,
            'data' => $data,
            'timestamp' => date('Y-m-d H:i:s')
        ];
        
        echo json_encode($response, JSON_UNESCAPED_UNICODE);
        exit;
    }
    
    protected function sendError($message = 'Internal Server Error', $statusCode = 500, $data = null)
    {
        header('Content-Type: application/json');
        http_response_code($statusCode);
        
        $response = [
            'success' => false,
            'status_code' => $statusCode,
            'message' => $message,
            'data' => $data,
            'timestamp' => date('Y-m-d H:i:s')
        ];
        
        echo json_encode($response, JSON_UNESCAPED_UNICODE);
        exit;
    }
    
    protected function getJsonInput()
    {
        $json = file_get_contents("php://input");
        return json_decode($json, true);
    }
    
    protected function validateRequired($data, $fields)
    {
        $missing = [];
        foreach ($fields as $field) {
            if (!isset($data[$field]) || empty($data[$field])) {
                $missing[] = $field;
            }
        }
        return $missing;
    }
    
    public function handleRequest()
    {
        $this->sendError('Base controller method not implemented', 501);
    }
} 
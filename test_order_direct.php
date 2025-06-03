<?php
// Test direct access to order page
session_start();

// Set fake session for testing
$_SESSION['username'] = 'testuser';
$_SESSION['user_role'] = 'user';
$_SESSION['user_id'] = 1;

// Include required files
require_once 'app/controllers/OrderController.php';

echo "Testing Order Controller...\n";

try {
    $controller = new OrderController();
    $controller->index();
    echo "Order page loaded successfully!\n";
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
    echo "Stack trace: " . $e->getTraceAsString() . "\n";
}
?> 
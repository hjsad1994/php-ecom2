<?php
session_start();

// Quick login for testing
$_SESSION['username'] = 'testuser';
$_SESSION['user_role'] = 'user';
$_SESSION['user_id'] = 1;  // Important: Set user_id for orders
$_SESSION['user_email'] = 'test@example.com';

echo "<h3>Test Session Created</h3>";
echo "<strong>Session set:</strong><br>";
echo "Username: " . ($_SESSION['username'] ?? 'Not set') . "<br>";
echo "Role: " . ($_SESSION['user_role'] ?? 'Not set') . "<br>";
echo "User ID: " . ($_SESSION['user_id'] ?? 'Not set') . "<br>";
echo "Email: " . ($_SESSION['user_email'] ?? 'Not set') . "<br>";

echo "<hr>";
echo "<h4>Test Links</h4>";
echo "<a href='/webbanhang/order' class='btn btn-primary'>Test Order Page</a><br><br>";
echo "<a href='/webbanhang/order/create' class='btn btn-success'>Test Create Order</a><br><br>";
echo "<a href='/webbanhang/cart' class='btn btn-info'>Test Cart Page</a><br><br>";
echo "<a href='/webbanhang/user/orders' class='btn btn-warning'>Test User Orders</a><br><br>";
echo "<a href='debug_session.php' class='btn btn-secondary'>Debug Session</a><br><br>";

echo "<style>
.btn { 
    display: inline-block; 
    padding: 8px 16px; 
    margin: 4px; 
    text-decoration: none; 
    border-radius: 4px; 
    color: white; 
}
.btn-primary { background-color: #007bff; }
.btn-success { background-color: #28a745; }
.btn-info { background-color: #17a2b8; }
.btn-warning { background-color: #ffc107; color: black; }
.btn-secondary { background-color: #6c757d; }
</style>";
?> 
<?php
session_start();
require_once 'app/helpers/AuthHelper.php';

echo "<h2>Authentication Debug</h2>";
echo "<strong>Session ID:</strong> " . session_id() . "<br>";
echo "<strong>Session Status:</strong> " . session_status() . "<br>";
echo "<strong>Session Data:</strong><br>";
print_r($_SESSION);

echo "<hr>";

echo "<strong>AuthHelper::isLoggedIn():</strong> " . (AuthHelper::isLoggedIn() ? 'true' : 'false') . "<br>";
echo "<strong>AuthHelper::isAdmin():</strong> " . (AuthHelper::isAdmin() ? 'true' : 'false') . "<br>";
echo "<strong>AuthHelper::getUserId():</strong> " . (AuthHelper::getUserId() ?: 'null') . "<br>";

echo "<hr>";

if (AuthHelper::isLoggedIn()) {
    echo "<p style='color: green'>✅ User is logged in</p>";
    echo "<a href='/webbanhang/user/orders'>Go to Orders</a><br>";
} else {
    echo "<p style='color: red'>❌ User is NOT logged in</p>";
    echo "<a href='/webbanhang/account/login'>Login</a><br>";
}

echo "<br><a href='/webbanhang/quick_login_test.php'>Quick Login for Testing</a>";
?> 
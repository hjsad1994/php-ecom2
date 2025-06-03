<?php
session_start();

echo "<h3>Debug Session Information</h3>";
echo "<pre>";
echo "Session ID: " . session_id() . "\n";
echo "Session Data:\n";
print_r($_SESSION);

// Test AuthHelper methods
require_once 'app/helpers/AuthHelper.php';
require_once 'app/helpers/SessionHelper.php';

echo "\nAuthHelper Tests:\n";
echo "isLoggedIn(): " . (AuthHelper::isLoggedIn() ? 'true' : 'false') . "\n";
echo "getUserId(): " . (AuthHelper::getUserId() ?? 'NULL') . "\n";
echo "isAdmin(): " . (AuthHelper::isAdmin() ? 'true' : 'false') . "\n";

echo "\nSessionHelper Tests:\n";
echo "getUsername(): " . (SessionHelper::getUsername() ?? 'NULL') . "\n";
echo "getUserRole(): " . (SessionHelper::getUserRole() ?? 'NULL') . "\n";
echo "getUserEmail(): " . (SessionHelper::getUserEmail() ?? 'NULL') . "\n";

echo "</pre>";

// Quick login link for testing
echo "<hr>";
echo "<h4>Quick Test Login</h4>";
echo "<a href='quick_login_test.php'>Set Test Session</a><br>";
echo "<a href='/webbanhang/account/login'>Go to Login Page</a><br>";
echo "<a href='/webbanhang/order/create'>Test Order Create (requires login)</a><br>";
?> 
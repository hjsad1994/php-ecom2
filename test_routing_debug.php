<?php
echo "<h2>Routing Debug</h2>";
echo "<p><strong>REQUEST_URI:</strong> " . $_SERVER['REQUEST_URI'] . "</p>";
echo "<p><strong>GET url:</strong> " . ($_GET['url'] ?? 'not set') . "</p>";

$url = $_GET['url'] ?? '';
$url = rtrim($url, '/');
$url = filter_var($url, FILTER_SANITIZE_URL);
$url = explode('/', $url);

echo "<p><strong>URL parts:</strong> " . print_r($url, true) . "</p>";

if (isset($url[0]) && $url[0] === 'order') {
    echo "<p style='color: green'>✅ Order routing detected</p>";
    echo "<p><strong>Controller:</strong> OrderController</p>";
    echo "<p><strong>Action:</strong> " . ($url[1] ?? 'index') . "</p>";
    
    if (isset($url[1]) && $url[1] === 'confirm') {
        echo "<p style='color: green'>✅ Confirm action detected</p>";
        echo "<p><strong>Order ID:</strong> " . ($url[2] ?? 'not provided') . "</p>";
        
        if (isset($url[2]) && is_numeric($url[2])) {
            echo "<p style='color: green'>✅ Valid Order ID provided</p>";
            echo "<p><a href='/webbanhang/order/confirm/{$url[2]}'>Test this URL</a></p>";
        } else {
            echo "<p style='color: red'>❌ Invalid or missing Order ID</p>";
        }
    }
}

echo "<hr>";
echo "<h3>Test URLs:</h3>";
echo "<p><a href='/webbanhang/order/confirm/1'>Test /order/confirm/1</a></p>";
echo "<p><a href='/webbanhang/order/confirm/2'>Test /order/confirm/2</a></p>";
echo "<p><a href='/webbanhang/order/confirm/'>Test /order/confirm/ (no ID)</a></p>";
echo "<p><a href='test_order_confirm.php'>Debug Order Data</a></p>";
?> 
<?php

class EmailHelper {
    private static $apiKey = 're_Y6Dk3sdy_FsSdW6Vsv334mhCNm1MkG1fG';
    private static $apiUrl = 'https://api.resend.com/emails';
    
    /**
     * Gửi email reset password
     */
    public static function sendPasswordResetEmail($email, $token) {
        $resetUrl = self::getBaseUrl() . "/account/reset-password?token=" . $token;
        
        $emailData = [
            'from' => 'no-reply@honeysocial.click',
            'to' => [$email],
            'subject' => 'Đặt lại mật khẩu - Cửa Hàng Online',
            'html' => self::getPasswordResetEmailTemplate($resetUrl)
        ];
        
        return self::sendEmail($emailData);
    }
    
    /**
     * Gửi email chào mừng khi đăng ký
     */
    public static function sendWelcomeEmail($email, $fullName) {
        $emailData = [
            'from' => 'noreply@honeysocial.click',
            'to' => [$email],
            'subject' => 'Chào mừng bạn đến với Cửa Hàng Online!',
            'html' => self::getWelcomeEmailTemplate($fullName)
        ];
        
        return self::sendEmail($emailData);
    }
    
    /**
     * Gửi email qua Resend API (theo chuẩn chính thức)
     */
    private static function sendEmail($emailData) {
        $ch = curl_init();
        
        // Cấu hình cURL theo chuẩn Resend
        curl_setopt_array($ch, [
            CURLOPT_URL => self::$apiUrl,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => json_encode($emailData, JSON_UNESCAPED_UNICODE),
            CURLOPT_HTTPHEADER => [
                'Authorization: Bearer ' . self::$apiKey,
                'Content-Type: application/json',
                'User-Agent: webbanhang-honeysocial/1.0'
            ],
            CURLOPT_SSL_VERIFYPEER => true,
            CURLOPT_SSL_VERIFYHOST => 2,
        ]);
        
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $error = curl_error($ch);
        curl_close($ch);
        
        // Logging chi tiết theo chuẩn Resend
        $logData = [
            'domain' => 'honeysocial.click',
            'api_endpoint' => self::$apiUrl,
            'http_code' => $httpCode,
            'email_to' => $emailData['to'] ?? 'unknown',
            'email_from' => $emailData['from'] ?? 'unknown',
            'subject' => $emailData['subject'] ?? 'unknown',
            'response_body' => $response,
            'curl_error' => $error,
            'timestamp' => date('Y-m-d H:i:s')
        ];
        
        error_log("Resend API (honeysocial.click): " . json_encode($logData, JSON_UNESCAPED_UNICODE));
        
        if ($error) {
            error_log("Resend cURL Error (honeysocial.click): " . $error);
            return false;
        }
        
        // Kiểm tra HTTP status code theo Resend API spec
        if ($httpCode >= 200 && $httpCode < 300) {
            $responseData = json_decode($response, true);
            if (isset($responseData['id'])) {
                error_log("Resend Success (honeysocial.click): Email sent with ID " . $responseData['id']);
                return true;
            } else {
                error_log("Resend Unexpected Response (honeysocial.click): " . $response);
                return false;
            }
        } else {
            $errorDetails = json_decode($response, true);
            $errorMessage = isset($errorDetails['message']) ? $errorDetails['message'] : 'Unknown error';
            $errorName = isset($errorDetails['name']) ? $errorDetails['name'] : 'UnknownError';
            
            error_log("Resend API Error (honeysocial.click): HTTP $httpCode, Error: $errorName, Message: $errorMessage");
            return false;
        }
    }
    
    /**
     * Template email reset password
     */
    private static function getPasswordResetEmailTemplate($resetUrl) {
        return '
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đặt lại mật khẩu</title>
    <style>
        body {
            font-family: "Segoe UI", Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            background-color: #f4f4f4;
        }
        .container {
            background-color: #ffffff;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 0 20px rgba(0,0,0,0.1);
        }
        .header {
            text-align: center;
            border-bottom: 2px solid #007bff;
            padding-bottom: 20px;
            margin-bottom: 30px;
        }
        .header h1 {
            color: #007bff;
            margin: 0;
            font-size: 24px;
        }
        .content {
            margin-bottom: 30px;
        }
        .reset-button {
            display: inline-block;
            background-color: #007bff;
            color: white;
            padding: 12px 30px;
            text-decoration: none;
            border-radius: 5px;
            font-weight: bold;
            margin: 20px 0;
        }
        .reset-button:hover {
            background-color: #0056b3;
        }
        .warning {
            background-color: #fff3cd;
            color: #856404;
            padding: 15px;
            border-left: 4px solid #ffc107;
            margin: 20px 0;
        }
        .footer {
            text-align: center;
            color: #666;
            font-size: 12px;
            border-top: 1px solid #eee;
            padding-top: 20px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>🛒 Cửa Hàng Online</h1>
        </div>
        
        <div class="content">
            <h2>Đặt lại mật khẩu</h2>
            <p>Xin chào,</p>
            <p>Chúng tôi nhận được yêu cầu đặt lại mật khẩu cho tài khoản của bạn. Vui lòng nhấp vào nút bên dưới để tạo mật khẩu mới:</p>
            
            <div style="text-align: center;">
                <a href="' . $resetUrl . '" class="reset-button">Đặt lại mật khẩu</a>
            </div>
            
            <div class="warning">
                <strong>⚠️ Lưu ý quan trọng:</strong>
                <ul>
                    <li>Link này chỉ có hiệu lực trong <strong>1 giờ</strong></li>
                    <li>Nếu bạn không yêu cầu đặt lại mật khẩu, vui lòng bỏ qua email này</li>
                    <li>Không chia sẻ link này với người khác</li>
                </ul>
            </div>
            
            <p>Nếu nút không hoạt động, bạn có thể copy và dán link sau vào trình duyệt:</p>
            <p style="word-break: break-all; background-color: #f8f9fa; padding: 10px; border-radius: 5px;">
                <small>' . $resetUrl . '</small>
            </p>
        </div>
        
        <div class="footer">
            <p>Email này được gửi tự động từ hệ thống Cửa Hàng Online</p>
            <p>Vui lòng không trả lời email này</p>
        </div>
    </div>
</body>
</html>';
    }
    
    /**
     * Template email chào mừng
     */
    private static function getWelcomeEmailTemplate($fullName) {
        return '
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chào mừng đến với Cửa Hàng Online</title>
    <style>
        body {
            font-family: "Segoe UI", Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            background-color: #f4f4f4;
        }
        .container {
            background-color: #ffffff;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 0 20px rgba(0,0,0,0.1);
        }
        .header {
            text-align: center;
            border-bottom: 2px solid #28a745;
            padding-bottom: 20px;
            margin-bottom: 30px;
        }
        .header h1 {
            color: #28a745;
            margin: 0;
            font-size: 24px;
        }
        .content {
            margin-bottom: 30px;
        }
        .welcome-button {
            display: inline-block;
            background-color: #28a745;
            color: white;
            padding: 12px 30px;
            text-decoration: none;
            border-radius: 5px;
            font-weight: bold;
            margin: 20px 0;
        }
        .welcome-button:hover {
            background-color: #218838;
        }
        .features {
            background-color: #f8f9fa;
            padding: 20px;
            border-radius: 8px;
            margin: 20px 0;
        }
        .features h3 {
            color: #495057;
            margin-top: 0;
        }
        .features ul {
            margin: 0;
            padding-left: 20px;
        }
        .features li {
            margin: 8px 0;
        }
        .footer {
            text-align: center;
            color: #666;
            font-size: 12px;
            border-top: 1px solid #eee;
            padding-top: 20px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>🛒 Cửa Hàng Online</h1>
        </div>
        
        <div class="content">
            <h2>Xin chào ' . htmlspecialchars($fullName) . '!</h2>
            <p>Cảm ơn bạn đã đăng ký tài khoản tại <strong>Cửa Hàng Online</strong>. Chúng tôi rất vui mừng được chào đón bạn vào cộng đồng mua sắm của chúng tôi!</p>
            
            <div style="text-align: center;">
                <a href="' . self::getBaseUrl() . '" class="welcome-button">Bắt đầu mua sắm ngay</a>
            </div>
            
            <div class="features">
                <h3>🌟 Tại sao chọn chúng tôi?</h3>
                <ul>
                    <li>🚚 <strong>Giao hàng nhanh chóng:</strong> Đơn hàng được xử lý trong 24h</li>
                    <li>💯 <strong>Sản phẩm chất lượng:</strong> Cam kết 100% hàng chính hãng</li>
                    <li>🎁 <strong>Ưu đãi độc quyền:</strong> Voucher và khuyến mãi đặc biệt cho thành viên</li>
                    <li>🔒 <strong>Mua sắm an toàn:</strong> Bảo mật thông tin tuyệt đối</li>
                    <li>📞 <strong>Hỗ trợ 24/7:</strong> Đội ngũ chăm sóc khách hàng luôn sẵn sàng</li>
                </ul>
            </div>
            
            <h3>🎯 Các bước tiếp theo:</h3>
            <ol>
                <li><strong>Hoàn thiện thông tin:</strong> Cập nhật địa chỉ giao hàng trong tài khoản</li>
                <li><strong>Khám phá sản phẩm:</strong> Duyệt qua hàng nghìn sản phẩm đa dạng</li>
                <li><strong>Đặt hàng đầu tiên:</strong> Nhận voucher giảm giá 10% cho đơn hàng đầu</li>
            </ol>
            
            <p>Nếu bạn có bất kỳ câu hỏi nào, đừng ngần ngại liên hệ với chúng tôi. Chúng tôi luôn sẵn sàng hỗ trợ!</p>
            
            <p>Chúc bạn có những trải nghiệm mua sắm tuyệt vời!</p>
            
            <p><strong>Trân trọng,</strong><br>
            Đội ngũ Cửa Hàng Online</p>
        </div>
        
        <div class="footer">
            <p>Email này được gửi tự động từ hệ thống Cửa Hàng Online</p>
            <p>Vui lòng không trả lời email này</p>
        </div>
    </div>
</body>
</html>';
    }
    
    /**
     * Lấy base URL của website
     */
    private static function getBaseUrl() {
        $protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http';
        $host = $_SERVER['HTTP_HOST'] ?? 'localhost:85';
        
        // Simple approach: always use /webbanhang as the base path
        // This works whether we're calling from root or subdirectory
        $path = '/webbanhang';
        
        return $protocol . '://' . $host . $path;
    }
} 
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hóa đơn #<?php echo str_pad($order['id'], 5, '0', STR_PAD_LEFT); ?></title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            font-size: 14px;
            line-height: 1.6;
            color: #333;
            background: #fff;
        }
        
        .invoice {
            max-width: 800px;
            margin: 20px auto;
            padding: 30px;
            border: 1px solid #ddd;
            background: #fff;
        }
        
        .invoice-header {
            border-bottom: 2px solid #007bff;
            padding-bottom: 20px;
            margin-bottom: 30px;
        }
        
        .company-info {
            text-align: center;
            margin-bottom: 20px;
        }
        
        .company-name {
            font-size: 28px;
            font-weight: bold;
            color: #007bff;
            margin-bottom: 5px;
        }
        
        .company-details {
            color: #666;
            font-size: 12px;
        }
        
        .invoice-title {
            text-align: center;
            font-size: 24px;
            font-weight: bold;
            color: #333;
            margin: 20px 0;
        }
        
        .invoice-meta {
            display: flex;
            justify-content: space-between;
            margin-bottom: 30px;
        }
        
        .invoice-info, .customer-info {
            width: 48%;
        }
        
        .section-title {
            font-weight: bold;
            color: #007bff;
            margin-bottom: 10px;
            border-bottom: 1px solid #eee;
            padding-bottom: 5px;
        }
        
        .info-row {
            margin-bottom: 5px;
        }
        
        .label {
            font-weight: bold;
            color: #555;
            display: inline-block;
            width: 120px;
        }
        
        .items-table {
            width: 100%;
            border-collapse: collapse;
            margin: 30px 0;
        }
        
        .items-table th {
            background: #007bff;
            color: white;
            padding: 12px 8px;
            text-align: left;
            font-weight: bold;
        }
        
        .items-table td {
            padding: 12px 8px;
            border-bottom: 1px solid #eee;
        }
        
        .items-table tr:nth-child(even) {
            background: #f8f9fa;
        }
        
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        
        .total-section {
            margin-top: 30px;
            float: right;
            width: 300px;
        }
        
        .total-row {
            display: flex;
            justify-content: space-between;
            padding: 8px 0;
            border-bottom: 1px solid #eee;
        }
        
        .total-row.final {
            border-top: 2px solid #007bff;
            border-bottom: 2px solid #007bff;
            font-weight: bold;
            font-size: 16px;
            color: #007bff;
        }
        
        .footer {
            clear: both;
            margin-top: 50px;
            padding-top: 20px;
            border-top: 1px solid #ddd;
            text-align: center;
            color: #666;
            font-size: 12px;
        }
        
        .status-badge {
            display: inline-block;
            padding: 5px 15px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: bold;
            text-transform: uppercase;
        }
        
        .status-unpaid { background: #fff3cd; color: #856404; }
        .status-paid { background: #d1e7dd; color: #0f5132; }
        .status-pending { background: #d1ecf1; color: #0c5460; }
        .status-completed { background: #d1e7dd; color: #0f5132; }
        .status-cancelled { background: #f8d7da; color: #721c24; }
        
        @media print {
            body { background: white; }
            .invoice { border: none; margin: 0; padding: 20px; }
            .no-print { display: none; }
        }
        
        .print-buttons {
            text-align: center;
            margin: 20px 0;
        }
        
        .btn {
            display: inline-block;
            padding: 10px 20px;
            margin: 0 10px;
            background: #007bff;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            border: none;
            cursor: pointer;
            font-size: 14px;
        }
        
        .btn:hover {
            background: #0056b3;
        }
        
        .btn-secondary {
            background: #6c757d;
        }
        
        .btn-secondary:hover {
            background: #545b62;
        }
    </style>
</head>
<body>
    <div class="print-buttons no-print">
        <button class="btn" onclick="window.print()">🖨️ In hóa đơn</button>
        <button class="btn btn-secondary" onclick="window.close()">❌ Đóng</button>
    </div>

    <div class="invoice">
        <!-- Header -->
        <div class="invoice-header">
            <div class="company-info">
                <div class="company-name">CÔNG TY TNHH ABC</div>
                <div class="company-details">
                    Địa chỉ: 123 Đường ABC, Quận XYZ, TP. Hồ Chí Minh<br>
                    Điện thoại: (028) 1234 5678 | Email: info@abc.com<br>
                    MST: 0123456789
                </div>
            </div>
            
            <div class="invoice-title">HÓA ĐƠN BÁN HÀNG</div>
        </div>

        <!-- Invoice Meta -->
        <div class="invoice-meta">
            <div class="invoice-info">
                <div class="section-title">THÔNG TIN HÓA ĐƠN</div>
                <div class="info-row">
                    <span class="label">Số hóa đơn:</span>
                    #<?php echo str_pad($order['id'], 5, '0', STR_PAD_LEFT); ?>
                </div>
                <div class="info-row">
                    <span class="label">Ngày tạo:</span>
                    <?php echo date('d/m/Y H:i', strtotime($order['created_at'])); ?>
                </div>
                <div class="info-row">
                    <span class="label">Trạng thái:</span>
                    <?php
                    $statusClass = '';
                    $statusText = '';
                    switch($order['order_status']) {
                        case 'unpaid':
                            $statusClass = 'status-unpaid';
                            $statusText = 'Chưa thanh toán';
                            break;
                        case 'paid':
                            $statusClass = 'status-paid';
                            $statusText = 'Đã thanh toán';
                            break;
                        case 'pending':
                            $statusClass = 'status-pending';
                            $statusText = 'Đang xử lý';
                            break;
                        case 'completed':
                            $statusClass = 'status-completed';
                            $statusText = 'Hoàn thành';
                            break;
                        case 'cancelled':
                            $statusClass = 'status-cancelled';
                            $statusText = 'Đã hủy';
                            break;
                        default:
                            $statusClass = 'status-pending';
                            $statusText = 'Không xác định';
                    }
                    ?>
                    <span class="status-badge <?php echo $statusClass; ?>"><?php echo $statusText; ?></span>
                </div>
            </div>
            
            <div class="customer-info">
                <div class="section-title">THÔNG TIN KHÁCH HÀNG</div>
                <div class="info-row">
                    <span class="label">Họ tên:</span>
                    <?php echo htmlspecialchars($order['name'], ENT_QUOTES, 'UTF-8'); ?>
                </div>
                <div class="info-row">
                    <span class="label">Điện thoại:</span>
                    <?php echo htmlspecialchars($order['phone'], ENT_QUOTES, 'UTF-8'); ?>
                </div>
                <div class="info-row">
                    <span class="label">Địa chỉ:</span>
                    <?php echo htmlspecialchars($order['address'], ENT_QUOTES, 'UTF-8'); ?>
                </div>
            </div>
        </div>

        <!-- Items Table -->
        <table class="items-table">
            <thead>
                <tr>
                    <th width="5%">STT</th>
                    <th width="50%">Tên sản phẩm</th>
                    <th width="10%" class="text-center">Số lượng</th>
                    <th width="15%" class="text-right">Đơn giá</th>
                    <th width="20%" class="text-right">Thành tiền</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($order['items'])): ?>
                    <?php $stt = 1; ?>
                    <?php foreach ($order['items'] as $item): ?>
                        <tr>
                            <td class="text-center"><?php echo $stt++; ?></td>
                            <td><?php echo htmlspecialchars($item['product_name'], ENT_QUOTES, 'UTF-8'); ?></td>
                            <td class="text-center"><?php echo $item['quantity']; ?></td>
                            <td class="text-right"><?php echo number_format($item['price'], 0, ',', '.'); ?> đ</td>
                            <td class="text-right"><?php echo number_format($item['price'] * $item['quantity'], 0, ',', '.'); ?> đ</td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="5" class="text-center">Không có sản phẩm nào</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>

        <!-- Total Section -->
        <div class="total-section">
            <div class="total-row">
                <span>Tổng tiền hàng:</span>
                <span><?php echo number_format($order['total_amount'], 0, ',', '.'); ?> đ</span>
            </div>
            
            <?php if (!empty($order['voucher_code'])): ?>
                <div class="total-row">
                    <span>Giảm giá (<?php echo $order['voucher_code']; ?>):</span>
                    <span>-<?php echo number_format($order['voucher_discount'] ?? 0, 0, ',', '.'); ?> đ</span>
                </div>
            <?php endif; ?>
            
            <div class="total-row">
                <span>Phí vận chuyển:</span>
                <span>0 đ</span>
            </div>
            
            <div class="total-row final">
                <span>TỔNG CỘNG:</span>
                <span><?php echo number_format($order['total_amount'], 0, ',', '.'); ?> đ</span>
            </div>
        </div>

        <!-- Footer -->
        <div class="footer">
            <p><strong>Cảm ơn quý khách đã mua hàng!</strong></p>
            <p>Mọi thắc mắc xin liên hệ: (028) 1234 5678 hoặc email: support@abc.com</p>
            <p style="margin-top: 10px;">
                <small>Hóa đơn này được tạo tự động từ hệ thống vào ngày <?php echo date('d/m/Y H:i'); ?></small>
            </p>
        </div>
    </div>

    <script>
        // Auto focus for printing
        window.onload = function() {
            window.focus();
        };
    </script>
</body>
</html> 
<?php include 'app/views/shares/header.php'; ?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="display-5 fw-bold"><i class="bi bi-list-check me-2"></i>Quản lý đơn hàng</h1>
    <div class="d-flex align-items-center">
        <div class="me-3">
            <span class="badge bg-info fs-6">
                Tổng: <?php echo !empty($orders) ? count($orders) : 0; ?> đơn hàng
            </span>
        </div>
        <a href="/webbanhang/Product" class="btn btn-outline-primary">
            <i class="bi bi-arrow-left me-2"></i>Quay lại sản phẩm
        </a>
    </div>
</div>

<?php if (empty($orders)): ?>
    <div class="text-center py-5">
        <i class="bi bi-receipt display-1 text-muted mb-3"></i>
        <h3 class="text-muted">Chưa có đơn hàng nào</h3>
        <p class="text-muted mb-4">Các đơn hàng của khách hàng sẽ xuất hiện ở đây.</p>
        <a href="/webbanhang/Product" class="btn btn-primary btn-lg">
            <i class="bi bi-shop me-2"></i>Xem sản phẩm
        </a>
    </div>
<?php else: ?>
    <!-- Order Statistics -->
    <div class="row mb-4">
        <?php
        $stats = [
            'total' => 0,
            'paid' => 0,
            'unpaid' => 0,
            'pending' => 0,
            'cancelled' => 0,
            'total_amount' => 0
        ];
        
        foreach ($orders as $order) {
            $stats['total']++;
            $stats[$order->order_status]++;
            $stats['total_amount'] += $order->total_amount ?? 0;
        }
        ?>
        
        <div class="col-md-2">
            <div class="card bg-primary text-white">
                <div class="card-body text-center">
                    <i class="bi bi-receipt-cutoff fs-1 mb-2"></i>
                    <h4><?php echo $stats['total']; ?></h4>
                    <p class="mb-0">Tổng đơn hàng</p>
                </div>
            </div>
        </div>
        
        <div class="col-md-2">
            <div class="card bg-success text-white">
                <div class="card-body text-center">
                    <i class="bi bi-check-circle-fill fs-1 mb-2"></i>
                    <h4><?php echo $stats['paid']; ?></h4>
                    <p class="mb-0">Đã thanh toán</p>
                </div>
            </div>
        </div>
        
        <div class="col-md-2">
            <div class="card bg-warning text-dark">
                <div class="card-body text-center">
                    <i class="bi bi-exclamation-triangle-fill fs-1 mb-2"></i>
                    <h4><?php echo $stats['unpaid']; ?></h4>
                    <p class="mb-0">Chưa thanh toán</p>
                </div>
            </div>
        </div>
        
        <div class="col-md-2">
            <div class="card bg-secondary text-white">
                <div class="card-body text-center">
                    <i class="bi bi-clock-fill fs-1 mb-2"></i>
                    <h4><?php echo $stats['pending']; ?></h4>
                    <p class="mb-0">Đang xử lý</p>
                </div>
            </div>
        </div>
        
        <div class="col-md-2">
            <div class="card bg-danger text-white">
                <div class="card-body text-center">
                    <i class="bi bi-x-circle-fill fs-1 mb-2"></i>
                    <h4><?php echo $stats['cancelled']; ?></h4>
                    <p class="mb-0">Đã hủy</p>
                </div>
            </div>
        </div>
        
        <div class="col-md-2">
            <div class="card bg-info text-white">
                <div class="card-body text-center">
                    <i class="bi bi-currency-dollar fs-1 mb-2"></i>
                    <h5><?php echo number_format($stats['total_amount'], 0, ',', '.'); ?> đ</h5>
                    <p class="mb-0">Tổng doanh thu</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Orders Table -->
    <div class="card shadow-sm">
        <div class="card-header bg-light">
            <div class="row align-items-center">
                <div class="col">
                    <h5 class="mb-0"><i class="bi bi-table me-2"></i>Danh sách đơn hàng</h5>
                </div>
                <div class="col-auto">
                    <div class="btn-group" role="group">
                        <input type="radio" class="btn-check" name="statusFilter" id="all" value="all" checked>
                        <label class="btn btn-outline-secondary btn-sm" for="all">Tất cả</label>
                        
                        <input type="radio" class="btn-check" name="statusFilter" id="paid" value="paid">
                        <label class="btn btn-outline-success btn-sm" for="paid">Đã thanh toán</label>
                        
                        <input type="radio" class="btn-check" name="statusFilter" id="unpaid" value="unpaid">
                        <label class="btn btn-outline-warning btn-sm" for="unpaid">Chưa thanh toán</label>
                        
                        <input type="radio" class="btn-check" name="statusFilter" id="pending" value="pending">
                        <label class="btn btn-outline-info btn-sm" for="pending">Đang xử lý</label>
                        
                        <input type="radio" class="btn-check" name="statusFilter" id="cancelled" value="cancelled">
                        <label class="btn btn-outline-danger btn-sm" for="cancelled">Đã hủy</label>
                    </div>
                </div>
            </div>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table id="ordersTable" class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Mã đơn hàng</th>
                            <th>Khách hàng</th>
                            <th>Số điện thoại</th>
                            <th>Địa chỉ</th>
                            <th>Số sản phẩm</th>
                            <th>Tổng tiền</th>
                            <th>Trạng thái</th>
                            <th>Ngày đặt</th>
                            <th>Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($orders as $order): ?>
                        <tr data-status="<?php echo $order->order_status; ?>">
                            <td>
                                <strong class="text-primary">
                                    #<?php echo str_pad($order->id, 5, '0', STR_PAD_LEFT); ?>
                                </strong>
                            </td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <i class="bi bi-person-circle me-2 text-secondary"></i>
                                    <span class="fw-bold"><?php echo htmlspecialchars($order->name, ENT_QUOTES, 'UTF-8'); ?></span>
                                </div>
                            </td>
                            <td>
                                <a href="tel:<?php echo htmlspecialchars($order->phone, ENT_QUOTES, 'UTF-8'); ?>" 
                                   class="text-decoration-none">
                                    <i class="bi bi-telephone me-1"></i>
                                    <?php echo htmlspecialchars($order->phone, ENT_QUOTES, 'UTF-8'); ?>
                                </a>
                            </td>
                            <td class="text-truncate" style="max-width: 200px;">
                                <i class="bi bi-geo-alt me-1 text-secondary"></i>
                                <span title="<?php echo htmlspecialchars($order->address, ENT_QUOTES, 'UTF-8'); ?>">
                                    <?php echo htmlspecialchars($order->address, ENT_QUOTES, 'UTF-8'); ?>
                                </span>
                            </td>
                            <td class="text-center">
                                <span class="badge bg-info">
                                    <?php echo $order->item_count; ?> sản phẩm
                                </span>
                            </td>
                            <td class="text-end">
                                <strong class="text-success">
                                    <?php echo number_format($order->total_amount ?? 0, 0, ',', '.'); ?> đ
                                </strong>
                            </td>
                            <td>
                                <?php
                                $statusClass = '';
                                $statusText = '';
                                $statusIcon = '';
                                switch($order->order_status) {
                                    case 'unpaid':
                                        $statusClass = 'bg-warning text-dark';
                                        $statusText = 'Chưa thanh toán';
                                        $statusIcon = 'bi-exclamation-triangle';
                                        break;
                                    case 'paid':
                                        $statusClass = 'bg-success';
                                        $statusText = 'Đã thanh toán';
                                        $statusIcon = 'bi-check-circle';
                                        break;
                                    case 'pending':
                                        $statusClass = 'bg-secondary';
                                        $statusText = 'Đang xử lý';
                                        $statusIcon = 'bi-clock';
                                        break;
                                    case 'cancelled':
                                        $statusClass = 'bg-danger';
                                        $statusText = 'Đã hủy';
                                        $statusIcon = 'bi-x-circle';
                                        break;
                                    default:
                                        $statusClass = 'bg-secondary';
                                        $statusText = 'Không xác định';
                                        $statusIcon = 'bi-question-circle';
                                }
                                ?>
                                <div class="dropdown">
                                    <button class="btn btn-sm badge <?php echo $statusClass; ?> dropdown-toggle" 
                                            type="button" data-bs-toggle="dropdown">
                                        <i class="bi <?php echo $statusIcon; ?> me-1"></i>
                                        <?php echo $statusText; ?>
                                    </button>
                                    <ul class="dropdown-menu">
                                        <li>
                                            <form method="POST" action="/webbanhang/Product/updateOrderStatus" class="dropdown-item-form">
                                                <input type="hidden" name="order_id" value="<?php echo $order->id; ?>">
                                                <input type="hidden" name="order_status" value="paid">
                                                <button type="submit" class="dropdown-item">
                                                    <i class="bi bi-check-circle text-success me-2"></i>Đã thanh toán
                                                </button>
                                            </form>
                                        </li>
                                        <li>
                                            <form method="POST" action="/webbanhang/Product/updateOrderStatus" class="dropdown-item-form">
                                                <input type="hidden" name="order_id" value="<?php echo $order->id; ?>">
                                                <input type="hidden" name="order_status" value="unpaid">
                                                <button type="submit" class="dropdown-item">
                                                    <i class="bi bi-exclamation-triangle text-warning me-2"></i>Chưa thanh toán
                                                </button>
                                            </form>
                                        </li>
                                        <li>
                                            <form method="POST" action="/webbanhang/Product/updateOrderStatus" class="dropdown-item-form">
                                                <input type="hidden" name="order_id" value="<?php echo $order->id; ?>">
                                                <input type="hidden" name="order_status" value="pending">
                                                <button type="submit" class="dropdown-item">
                                                    <i class="bi bi-clock text-secondary me-2"></i>Đang xử lý
                                                </button>
                                            </form>
                                        </li>
                                        <li>
                                            <form method="POST" action="/webbanhang/Product/updateOrderStatus" class="dropdown-item-form">
                                                <input type="hidden" name="order_id" value="<?php echo $order->id; ?>">
                                                <input type="hidden" name="order_status" value="cancelled">
                                                <button type="submit" class="dropdown-item" 
                                                        onclick="return confirm('Bạn có chắc chắn muốn hủy đơn hàng này?');">
                                                    <i class="bi bi-x-circle text-danger me-2"></i>Hủy đơn hàng
                                                </button>
                                            </form>
                                        </li>
                                    </ul>
                                </div>
                            </td>
                            <td>
                                <small class="text-muted">
                                    <i class="bi bi-calendar3 me-1"></i>
                                    <?php echo date('d/m/Y', strtotime($order->created_at)); ?>
                                    <br>
                                    <i class="bi bi-clock me-1"></i>
                                    <?php echo date('H:i', strtotime($order->created_at)); ?>
                                </small>
                            </td>
                            <td>
                                <div class="btn-group" role="group">
                                    <a href="/webbanhang/Product/orderDetail/<?php echo $order->id; ?>" 
                                       class="btn btn-sm btn-outline-primary" title="Xem chi tiết">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    <button type="button" class="btn btn-sm btn-outline-secondary" 
                                            onclick="printOrder(<?php echo $order->id; ?>)" title="In đơn hàng">
                                        <i class="bi bi-printer"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
<?php endif; ?>

<!-- Add DataTables CSS and JS -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css">
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap5.min.js"></script>

<style>
.dropdown-item-form {
    margin: 0;
    padding: 0;
}

.dropdown-item-form .dropdown-item {
    border: none;
    background: none;
    width: 100%;
    text-align: left;
    padding: 0.375rem 1rem;
}

.dropdown-item-form .dropdown-item:hover {
    background-color: #f8f9fa;
}

.card {
    transition: transform 0.2s;
}

.card:hover {
    transform: translateY(-2px);
}

.badge.dropdown-toggle::after {
    margin-left: 0.5rem;
}

/* Custom table styling */
#ordersTable tbody tr:hover {
    background-color: #f8f9fa;
}

.text-truncate {
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}
</style>

<script>
$(document).ready(function() {
    // Initialize DataTables
    var table = $('#ordersTable').DataTable({
        language: {
            "search": "Tìm kiếm:",
            "lengthMenu": "Hiển thị _MENU_ đơn hàng trên trang",
            "zeroRecords": "Không tìm thấy đơn hàng nào phù hợp",
            "info": "Hiển thị trang _PAGE_ trên _PAGES_",
            "infoEmpty": "Không tìm thấy đơn hàng nào",
            "infoFiltered": "(lọc từ _MAX_ đơn hàng)",
            "paginate": {
                "first": "Đầu",
                "last": "Cuối",
                "next": "Tiếp",
                "previous": "Trước"
            }
        },
        responsive: true,
        columnDefs: [
            { orderable: false, targets: [8] }, // Disable sorting for action column
            { type: 'date', targets: [7] } // Date sorting for created_at column
        ],
        order: [[7, 'desc']], // Sort by date descending (newest first)
        pageLength: 25
    });
    
    // Custom filter function
    $.fn.dataTable.ext.search.push(
        function(settings, data, dataIndex) {
            // Get the selected filter value
            var selectedStatus = $('input[name="statusFilter"]:checked').val();
            
            // If "all" is selected, show all rows
            if (selectedStatus === 'all') {
                return true;
            }
            
            // Get the status from the data-status attribute of the row
            var row = $(table.row(dataIndex).node());
            var rowStatus = row.attr('data-status');
            
            // Return true if the row status matches the selected filter
            return rowStatus === selectedStatus;
        }
    );
    
    // Status filter functionality
    $('input[name="statusFilter"]').change(function() {
        // Redraw the table with the new filter
        table.draw();
    });
    
    // Auto-refresh every 30 seconds
    setInterval(function() {
        // Only refresh if user is on the page (not in background)
        if (!document.hidden) {
            location.reload();
        }
    }, 30000);
});

// Print order function
function printOrder(orderId) {
    var printWindow = window.open('/webbanhang/Product/orderDetail/' + orderId, '_blank');
    printWindow.onload = function() {
        printWindow.print();
    };
}

// Confirm status change
function confirmStatusChange(orderId, newStatus, statusText) {
    if (confirm('Bạn có chắc chắn muốn thay đổi trạng thái đơn hàng thành "' + statusText + '"?')) {
        // Submit the form
        document.getElementById('statusForm_' + orderId + '_' + newStatus).submit();
    }
}
</script>

<?php include 'app/views/shares/footer.php'; ?>
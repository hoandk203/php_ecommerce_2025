<?php
include_once "views/layouts/header.php";
$path = "/admin";
?>

<?php include_once "views/layouts/admin_sidebar.php"; ?>

<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Dashboard</h1>
</div>

<div class="row">
    <!-- Tổng sản phẩm -->
    <div class="col-md-3 mb-4">
        <div class="card text-white bg-primary shadow-sm">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="card-title">Tổng sản phẩm</h6>
                        <h2 class="card-text mb-0"><?php echo $productCount; ?></h2>
                    </div>
                    <i class="fas fa-box fa-3x opacity-50"></i>
                </div>
                <a href="/admin/products" class="btn btn-sm btn-outline-light mt-3">Xem chi tiết</a>
            </div>
        </div>
    </div>

    <!-- Tổng danh mục -->
    <div class="col-md-3 mb-4">
        <div class="card text-white bg-success shadow-sm">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="card-title">Tổng danh mục</h6>
                        <h2 class="card-text mb-0"><?php echo $categoryCount; ?></h2>
                    </div>
                    <i class="fas fa-list fa-3x opacity-50"></i>
                </div>
                <a href="/admin/categories" class="btn btn-sm btn-outline-light mt-3">Xem chi tiết</a>
            </div>
        </div>
    </div>

    <!-- Tổng đơn hàng -->
    <div class="col-md-3 mb-4">
        <div class="card text-white bg-info shadow-sm">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="card-title">Tổng đơn hàng</h6>
                        <h2 class="card-text mb-0"><?php echo $orderCount; ?></h2>
                    </div>
                    <i class="fas fa-shopping-cart fa-3x opacity-50"></i>
                </div>
                <a href="/admin/orders" class="btn btn-sm btn-outline-light mt-3">Xem chi tiết</a>
            </div>
        </div>
    </div>

    <!-- Tổng người dùng -->
    <div class="col-md-3 mb-4">
        <div class="card text-white bg-warning shadow-sm">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="card-title">Tổng người dùng</h6>
                        <h2 class="card-text mb-0"><?php echo $userCount; ?></h2>
                    </div>
                    <i class="fas fa-users fa-3x opacity-50"></i>
                </div>
                <a href="/admin/users" class="btn btn-sm btn-outline-light mt-3">Xem chi tiết</a>
            </div>
        </div>
    </div>
</div>

<!-- Biểu đồ thống kê doanh thu theo tháng -->
<div class="row mb-4">
    <div class="col-md-12">
        <div class="card shadow-sm">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Thống kê doanh thu theo tháng</h5>
                <div>
                    <select id="chartType" class="form-select form-select-sm" style="width: auto; display: inline-block;">
                        <option value="bar">Biểu đồ cột</option>
                        <option value="line">Biểu đồ đường</option>
                    </select>
                </div>
            </div>
            <div class="card-body">
                <canvas id="revenueChart" height="100"></canvas>
            </div>
        </div>
    </div>
</div>

<!-- Biểu đồ thống kê doanh thu sản phẩm theo tháng -->
<div class="row mb-4">
    <div class="col-md-12">
        <div class="card shadow-sm">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Thống kê doanh thu sản phẩm theo tháng</h5>
                <div>
                    <select id="monthSelector" class="form-select form-select-sm" style="width: auto; display: inline-block;">
                        <?php for ($i = 1; $i <= 12; $i++): ?>
                            <option value="<?php echo $i; ?>" <?php echo date('n') == $i ? 'selected' : ''; ?>>
                                Tháng <?php echo $i; ?>
                            </option>
                        <?php endfor; ?>
                    </select>
                </div>
            </div>
            <div class="card-body">
                <canvas id="productRevenueChart" height="100"></canvas>
                <div id="noDataMessage" class="text-center py-5 d-none">
                    <p class="text-muted">Không có dữ liệu doanh thu sản phẩm cho tháng này</p>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Đơn hàng gần đây -->
<div class="row">
    <div class="col-md-12">
        <div class="card shadow-sm">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Đơn hàng gần đây</h5>
                <a href="/admin/orders" class="btn btn-sm btn-outline-primary">Xem tất cả</a>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped table-hover">
                        <thead>
                        <tr>
                            <th>Mã đơn hàng</th>
                            <th>Khách hàng</th>
                            <th>Tổng tiền</th>
                            <th>Phương thức</th>
                            <th>Trạng thái</th>
                            <th>Ngày đặt</th>
                            <th>Thao tác</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php while ($order = $recentOrderStmt->fetch(PDO::FETCH_ASSOC)): ?>
                            <tr>
                                <td>#<?php echo $order['id']; ?></td>
                                <td><?php echo $order['user_name']; ?></td>
                                <td><?php echo number_format($order['total_amount'], 0, ',', '.'); ?> VND</td>
                                <td>
                                    <?php
                                    $payment_method = 'Chưa thanh toán';
                                    switch ($order['payment_method']) {
                                        case 'cod':
                                            $payment_method = 'COD';
                                            break;
                                        case 'vnpay':
                                            $payment_method = 'VNPAY';
                                            break;
                                    }
                                    echo $payment_method;
                                    ?>
                                </td>
                                <td>
                                    <?php
                                    $status_badge = 'secondary';
                                    $status_text = 'Đang xử lý';

                                    switch ($order['status']) {
                                        case 'pending':
                                            $status_badge = 'warning';
                                            $status_text = 'Đang xử lý';
                                            break;
                                        case 'processing':
                                            $status_badge = 'info';
                                            $status_text = 'Đang chuẩn bị';
                                            break;
                                        case 'shipping':
                                            $status_badge = 'primary';
                                            $status_text = 'Đang giao hàng';
                                            break;
                                        case 'completed':
                                            $status_badge = 'success';
                                            $status_text = 'Hoàn thành';
                                            break;
                                        case 'cancelled':
                                            $status_badge = 'danger';
                                            $status_text = 'Đã hủy';
                                            break;
                                    }
                                    ?>
                                    <span class="badge bg-<?php echo $status_badge; ?>"><?php echo $status_text; ?></span>
                                </td>
                                <td><?php echo date('d/m/Y H:i', strtotime($order['created_at'])); ?></td>
                                <td>
                                    <a href="/admin/orders/view?id=<?php echo $order['id']; ?>" class="btn btn-sm btn-outline-primary">
                                        <i class="fas fa-eye"></i> Chi tiết
                                    </a>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
// Dữ liệu doanh thu theo tháng
const monthLabels = <?php echo $monthLabelsJson; ?>;
const revenueData = <?php echo $monthlyRevenueJson; ?>;
const productRevenueData = <?php echo $productRevenueJson; ?>;

// Biểu đồ doanh thu theo tháng
const revenueCtx = document.getElementById('revenueChart').getContext('2d');
let revenueChart = new Chart(revenueCtx, {
    type: 'bar',
    data: {
        labels: monthLabels,
        datasets: [{
            label: 'Doanh thu (VND)',
            data: revenueData,
            backgroundColor: 'rgba(54, 162, 235, 0.5)',
            borderColor: 'rgba(54, 162, 235, 1)',
            borderWidth: 1
        }]
    },
    options: {
        responsive: true,
        scales: {
            y: {
                beginAtZero: true,
                ticks: {
                    callback: function(value) {
                        return new Intl.NumberFormat('vi-VN', { 
                            style: 'currency', 
                            currency: 'VND',
                            maximumFractionDigits: 0
                        }).format(value);
                    }
                }
            }
        },
        plugins: {
            tooltip: {
                callbacks: {
                    label: function(context) {
                        return new Intl.NumberFormat('vi-VN', { 
                            style: 'currency', 
                            currency: 'VND',
                            maximumFractionDigits: 0
                        }).format(context.raw);
                    }
                }
            }
        }
    }
});

// Thay đổi loại biểu đồ
document.getElementById('chartType').addEventListener('change', function() {
    revenueChart.destroy();
    revenueChart = new Chart(revenueCtx, {
        type: this.value,
        data: {
            labels: monthLabels,
            datasets: [{
                label: 'Doanh thu (VND)',
                data: revenueData,
                backgroundColor: 'rgba(54, 162, 235, 0.5)',
                borderColor: 'rgba(54, 162, 235, 1)',
                borderWidth: 1,
                tension: 0.1
            }]
        },
        options: {
            responsive: true,
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: function(value) {
                            return new Intl.NumberFormat('vi-VN', { 
                                style: 'currency', 
                                currency: 'VND',
                                maximumFractionDigits: 0
                            }).format(value);
                        }
                    }
                }
            },
            plugins: {
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            return new Intl.NumberFormat('vi-VN', { 
                                style: 'currency', 
                                currency: 'VND',
                                maximumFractionDigits: 0
                            }).format(context.raw);
                        }
                    }
                }
            }
        }
    });
});

// Biểu đồ doanh thu sản phẩm theo tháng
const productRevenueCtx = document.getElementById('productRevenueChart').getContext('2d');
let productRevenueChart;

function updateProductRevenueChart(month) {
    // Nếu đã có biểu đồ, hủy nó trước
    if (productRevenueChart) {
        productRevenueChart.destroy();
    }
    
    const noDataMessage = document.getElementById('noDataMessage');
    
    // Kiểm tra xem có dữ liệu cho tháng đã chọn không
    if (!productRevenueData[month] || productRevenueData[month].length === 0) {
        noDataMessage.classList.remove('d-none');
        return;
    }
    
    noDataMessage.classList.add('d-none');
    
    // Chuẩn bị dữ liệu cho biểu đồ
    const labels = productRevenueData[month].map(item => item.product_name);
    const data = productRevenueData[month].map(item => item.revenue);
    
    // Tạo biểu đồ mới
    productRevenueChart = new Chart(productRevenueCtx, {
        type: 'bar',
        data: {
            labels: labels,
            datasets: [{
                label: 'Doanh thu (VND)',
                data: data,
                backgroundColor: [
                    'rgba(255, 99, 132, 0.5)',
                    'rgba(54, 162, 235, 0.5)',
                    'rgba(255, 206, 86, 0.5)',
                    'rgba(75, 192, 192, 0.5)',
                    'rgba(153, 102, 255, 0.5)'
                ],
                borderColor: [
                    'rgba(255, 99, 132, 1)',
                    'rgba(54, 162, 235, 1)',
                    'rgba(255, 206, 86, 1)',
                    'rgba(75, 192, 192, 1)',
                    'rgba(153, 102, 255, 1)'
                ],
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            indexAxis: 'y',
            scales: {
                x: {
                    beginAtZero: true,
                    ticks: {
                        callback: function(value) {
                            return new Intl.NumberFormat('vi-VN', { 
                                style: 'currency', 
                                currency: 'VND',
                                maximumFractionDigits: 0
                            }).format(value);
                        }
                    }
                }
            },
            plugins: {
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            return new Intl.NumberFormat('vi-VN', { 
                                style: 'currency', 
                                currency: 'VND',
                                maximumFractionDigits: 0
                            }).format(context.raw);
                        }
                    }
                }
            }
        }
    });
}

// Khởi tạo biểu đồ với tháng hiện tại
const currentMonth = new Date().getMonth() + 1; // JavaScript tháng bắt đầu từ 0
updateProductRevenueChart(currentMonth);

// Cập nhật biểu đồ khi thay đổi tháng
document.getElementById('monthSelector').addEventListener('change', function() {
    updateProductRevenueChart(parseInt(this.value));
});
</script>

<?php include_once "views/layouts/footer.php"; ?>

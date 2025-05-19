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

<?php include_once "views/layouts/footer.php"; ?>

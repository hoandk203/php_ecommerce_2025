<?php include_once "views/layouts/header.php"; ?>

    <div class="row">
        <div class="col-md-12 mb-3">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="/">Trang chủ</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Đơn hàng của tôi</li>
                </ol>
            </nav>
        </div>
    </div>

    <div class="row">
        <div class="col-md-4">
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">Menu tài khoản</h5>
                </div>
                <div class="card-body">
                    <div class="list-group">
                        <a href="/profile" class="list-group-item list-group-item-action">Thông tin tài khoản</a>
                        <a href="/orders" class="list-group-item list-group-item-action active">Đơn hàng của tôi</a>
                        <a href="/logout" class="list-group-item list-group-item-action text-danger">Đăng xuất</a>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-8">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">Đơn hàng của tôi</h5>
                </div>
                <div class="card-body">
                    <?php if ($orders->rowCount() > 0): ?>
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                <tr>
                                    <th>Mã đơn hàng</th>
                                    <th>Ngày đặt</th>
                                    <th>Tổng tiền</th>
                                    <th>Trạng thái</th>
                                    <th>Thao tác</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php while ($order = $orders->fetch(PDO::FETCH_ASSOC)): ?>
                                    <tr>
                                        <td>#<?php echo $order['id']; ?></td>
                                        <td><?php echo date('d/m/Y H:i', strtotime($order['created_at'])); ?></td>
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
                                        <td>
                                            <a href="/orders/view?id=<?php echo $order['id']; ?>" class="btn btn-sm btn-outline-primary">
                                                <i class="fas fa-eye"></i> Chi tiết
                                            </a>
                                        </td>
                                    </tr>
                                <?php endwhile; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php else: ?>
                        <div class="alert alert-info text-center">
                            <p>Bạn chưa có đơn hàng nào.</p>
                            <a href="/products" class="btn btn-primary mt-2">Mua sắm ngay</a>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

<?php include_once "views/layouts/footer.php"; ?>
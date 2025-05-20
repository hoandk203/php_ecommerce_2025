<?php
include_once "views/layouts/header.php";
$path = "/admin/orders";
?>

<?php include_once "views/layouts/admin_sidebar.php"; ?>

    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">Quản lý đơn hàng</h1>
    </div>

    <div class="card shadow-sm">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead>
                    <tr>
                        <th width="50">ID</th>
                        <th>Khách hàng</th>
                        <th>Email</th>
                        <th>Tổng tiền</th>
                        <th>Trạng thái</th>
                        <th>Ngày đặt</th>
                        <th width="100">Thao tác</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php while ($order = $stmt->fetch(PDO::FETCH_ASSOC)): ?>
                        <tr>
                            <td>#<?php echo $order['id']; ?></td>
                            <td><?php echo $order['name']; ?></td>
                            <td><?php echo $order['email']; ?></td>
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
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="/admin/orders/delete?id=<?php echo $order['id']; ?>"
                                   class="btn btn-sm btn-outline-danger"
                                   onclick="return confirm('Bạn có chắc chắn muốn xóa đơn hàng này? Hành động này không thể hoàn tác!');">
                                    <i class="fas fa-trash"></i>
                                </a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    </main>
    </div>
    </div>

<?php include_once "views/layouts/footer.php"; ?>
<?php
include_once "views/layouts/header.php";
$path = "/admin/orders";
?>

<style>
    .pagination .page-item.active .page-link {
        background-color: #0d6efd;
        border-color: #0d6efd;
        color: #fff;
    }
    
    .pagination .page-link {
        color: #0d6efd;
    }
    
    .pagination .page-link:hover {
        color: #fff;
        background-color: #0d6efd;
        border-color: #0d6efd;
    }

    .status-filter .btn {
        border-radius: 20px;
        margin-right: 5px;
        margin-bottom: 5px;
    }

    .status-filter .btn.active {
        color: white;
    }
</style>

<?php include_once "views/layouts/admin_sidebar.php"; ?>

    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">Quản lý đơn hàng</h1>
    </div>

    <div class="card shadow-sm">
        <div class="card-body">
            <!-- Bộ lọc trạng thái -->
            <div class="status-filter mb-4">
                <a href="/admin/orders" class="btn <?php echo !isset($_GET['status']) ? 'btn-primary active' : 'btn-outline-primary'; ?>">
                    Tất cả
                </a>
                <a href="/admin/orders?status=pending" class="btn <?php echo isset($_GET['status']) && $_GET['status'] == 'pending' ? 'btn-warning active' : 'btn-outline-warning'; ?>">
                    Đang xử lý
                </a>
                <a href="/admin/orders?status=processing" class="btn <?php echo isset($_GET['status']) && $_GET['status'] == 'processing' ? 'btn-info active' : 'btn-outline-info'; ?>">
                    Đang chuẩn bị
                </a>
                <a href="/admin/orders?status=shipping" class="btn <?php echo isset($_GET['status']) && $_GET['status'] == 'shipping' ? 'btn-primary active' : 'btn-outline-primary'; ?>">
                    Đang giao
                </a>
                <a href="/admin/orders?status=completed" class="btn <?php echo isset($_GET['status']) && $_GET['status'] == 'completed' ? 'btn-success active' : 'btn-outline-success'; ?>">
                    Hoàn thành
                </a>
                <a href="/admin/orders?status=cancelled" class="btn <?php echo isset($_GET['status']) && $_GET['status'] == 'cancelled' ? 'btn-danger active' : 'btn-outline-danger'; ?>">
                    Đã hủy
                </a>
            </div>

            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead>
                    <tr>
                        <th width="50">ID</th>
                        <th>Khách hàng</th>
                        <th>Email</th>
                        <th>Tổng tiền</th>
                        <th>Phương thức</th>
                        <th>Trạng thái</th>
                        <th>Ngày đặt</th>
                        <th width="100">Thao tác</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php while ($order = $orders->fetch(PDO::FETCH_ASSOC)): ?>
                        <tr>
                            <td>#<?php echo $order['id']; ?></td>
                            <td><?php echo $order['name']; ?></td>
                            <td><?php echo $order['email']; ?></td>
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

                <!-- Phân trang -->
                <?php if ($total_pages > 1): ?>
                <div class="d-flex justify-content-center mt-4">
                    <nav aria-label="Page navigation">
                        <ul class="pagination">
                            <?php if ($current_page > 1): ?>
                                <li class="page-item">
                                    <a class="page-link" href="?<?php 
                                        $params = $_GET;
                                        $params['page'] = $current_page - 1;
                                        echo http_build_query($params);
                                    ?>" aria-label="Previous">
                                        <span aria-hidden="true">&laquo;</span>
                                    </a>
                                </li>
                            <?php endif; ?>

                            <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                                <li class="page-item <?php echo $i == $current_page ? 'active' : ''; ?>">
                                    <a class="page-link" href="?<?php 
                                        $params = $_GET;
                                        $params['page'] = $i;
                                        echo http_build_query($params);
                                    ?>"><?php echo $i; ?></a>
                                </li>
                            <?php endfor; ?>

                            <?php if ($current_page < $total_pages): ?>
                                <li class="page-item">
                                    <a class="page-link" href="?<?php 
                                        $params = $_GET;
                                        $params['page'] = $current_page + 1;
                                        echo http_build_query($params);
                                    ?>" aria-label="Next">
                                        <span aria-hidden="true">&raquo;</span>
                                    </a>
                                </li>
                            <?php endif; ?>
                        </ul>
                    </nav>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    </main>
    </div>
    </div>

<?php include_once "views/layouts/footer.php"; ?>
<?php include_once "views/layouts/header.php"; ?>

    <div class="row">
        <div class="col-md-12 mb-3">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="/">Trang chủ</a></li>
                    <li class="breadcrumb-item"><a href="/orders">Đơn hàng của tôi</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Chi tiết đơn hàng #<?php echo $order->id; ?></li>
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
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Chi tiết đơn hàng #<?php echo $order->id; ?></h5>
                    <a href="/orders" class="btn btn-light btn-sm">Quay lại</a>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <h6 class="fw-bold">Ngày đặt hàng:</h6>
                            <p><?php echo date('d/m/Y H:i', strtotime($order->created_at)); ?></p>
                        </div>
                        <div class="col-md-6">
                            <h6 class="fw-bold">Trạng thái:</h6>
                            <?php
                            $status_badge = 'secondary';
                            $status_text = 'Đang xử lý';

                            switch ($order->status) {
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
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <h6 class="fw-bold">Phương thức thanh toán:</h6>
                            <p><?php echo $order->payment_method === 'cod' ? 'Thanh toán khi nhận hàng (COD)' : 'Chuyển khoản ngân hàng'; ?></p>
                        </div>
                        <div class="col-md-6">
                            <h6 class="fw-bold">Tổng tiền:</h6>
                            <p class="text-danger fw-bold"><?php echo number_format($order->total_amount, 0, ',', '.'); ?> VND</p>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <h6 class="fw-bold">Tên người nhận:</h6>
                            <p><?php echo $order->name; ?></p>
                        </div>
                        <div class="col-md-6">
                            <h6 class="fw-bold">Email:</h6>
                            <p><?php echo $order->email; ?></p>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <h6 class="fw-bold">Số điện thoại:</h6>
                            <p><?php echo $order->phone; ?></p>
                        </div>
                        <div class="col-md-6">
                            <h6 class="fw-bold">Địa chỉ giao hàng:</h6>
                            <p><?php echo $order->address; ?></p>
                        </div>
                    </div>

                    <h6 class="fw-bold mb-3">Chi tiết đơn hàng:</h6>
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover">
                            <thead class="table-light">
                            <tr>
                                <th>Sản phẩm</th>
                                <th width="100">Đơn giá</th>
                                <th width="80">Số lượng</th>
                                <th width="120">Thành tiền</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php while ($item = $order_items->fetch(PDO::FETCH_ASSOC)): ?>
                                <tr>
                                    <td><?php echo $item['product_name']; ?></td>
                                    <td><?php echo number_format($item['price'], 0, ',', '.'); ?> VND</td>
                                    <td class="text-center"><?php echo $item['quantity']; ?></td>
                                    <td><?php echo number_format($item['price'] * $item['quantity'], 0, ',', '.'); ?> VND</td>
                                </tr>
                            <?php endwhile; ?>
                            </tbody>
                            <tfoot>
                            <tr>
                                <td colspan="3" class="text-end fw-bold">Tổng cộng:</td>
                                <td class="fw-bold text-danger"><?php echo number_format($order->total_amount, 0, ',', '.'); ?> VND</td>
                            </tr>
                            </tfoot>
                        </table>
                    </div>
                    <?php if ($order->status === 'pending'): ?>
                        <button type="button" class="btn btn-danger btn-sm ms-2" onclick="confirmCancelOrder(<?php echo $order->id; ?>)">
                            Hủy đơn hàng
                        </button>
                    <?php endif; ?>

                    <!-- Add this JavaScript at the bottom of the file, before the footer -->
                    <script>
                        function confirmCancelOrder(orderId) {
                            if (confirm('Bạn có chắc chắn muốn hủy đơn hàng này không?')) {
                                window.location.href = '/orders/cancel?id=' + orderId;
                            }
                        }
                    </script>
                </div>
            </div>
        </div>
    </div>

<?php include_once "views/layouts/footer.php"; ?>
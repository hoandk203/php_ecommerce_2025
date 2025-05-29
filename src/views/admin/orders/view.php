<?php
include_once "views/layouts/header.php";
$path = "/admin/orders";
?>

<?php include_once "views/layouts/admin_sidebar.php"; ?>

    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">Chi tiết đơn hàng #<?php echo $order->id; ?></h1>
        <div>
            <a href="/admin/orders" class="btn btn-sm btn-outline-secondary">
                <i class="fas fa-arrow-left"></i> Quay lại
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-md-8">
            <div class="card shadow-sm mb-4">
                <div class="card-header">
                    <h5 class="mb-0">Thông tin đơn hàng</h5>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <h6>Mã đơn hàng:</h6>
                            <p>#<?php echo $order->id; ?></p>
                        </div>
                        <div class="col-md-6">
                            <h6>Ngày đặt:</h6>
                            <p><?php echo date('d/m/Y H:i', strtotime($order->created_at)); ?></p>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <h6>Tổng tiền:</h6>
                            <p class="text-danger fw-bold"><?php echo number_format($order->total_amount, 0, ',', '.'); ?> VND</p>
                        </div>
                        <div class="col-md-6">
                            <h6>Phương thức thanh toán:</h6>
                            <p><?php echo $order->payment_method === 'cod' ? 'Thanh toán khi nhận hàng (COD)' : 'Chuyển khoản ngân hàng'; ?></p>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-12">
                            <h6>Trạng thái đơn hàng:</h6>
                            <form method="POST" action="/admin/orders/update-status" class="d-flex align-items-center">
                                <input type="hidden" name="order_id" value="<?php echo $order->id; ?>">
                                <select name="status" class="form-select me-2">
                                    <option value="pending" <?php echo $order->status === 'pending' ? 'selected' : ''; ?>>Đang xử lý</option>
                                    <option value="processing" <?php echo $order->status === 'processing' ? 'selected' : ''; ?>>Đang chuẩn bị</option>
                                    <option value="shipping" <?php echo $order->status === 'shipping' ? 'selected' : ''; ?>>Đang giao hàng</option>
                                    <option value="completed" <?php echo $order->status === 'completed' ? 'selected' : ''; ?>>Hoàn thành</option>
                                </select>
                                <button type="submit" class="btn btn-primary col-2">Cập nhật</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card shadow-sm mb-4">
                <div class="card-header">
                    <h5 class="mb-0">Chi tiết đơn hàng</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                            <tr>
                                <th>Sản phẩm</th>
                                <th width="100">Hình ảnh</th>
                                <th width="120">Đơn giá</th>
                                <th width="80">SL</th>
                                <th width="150">Thành tiền</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php while ($item = $order_items->fetch(PDO::FETCH_ASSOC)): ?>
                                <tr>
                                    <td><?php echo $item['product_name']; ?></td>
                                    <td>
                                        <img src="<?php echo !empty($item['product_image']) ? $item['product_image'] : 'https://via.placeholder.com/80x80?text=No+Image'; ?>" alt="<?php echo $item['product_name']; ?>" class="img-thumbnail" width="80">
                                    </td>
                                    <td><?php echo number_format($item['price'], 0, ',', '.'); ?> VND</td>
                                    <td><?php echo $item['quantity']; ?></td>
                                    <td><?php echo number_format($item['price'] * $item['quantity'], 0, ',', '.'); ?> VND</td>
                                </tr>
                            <?php endwhile; ?>
                            </tbody>
                            <tfoot>
                            <tr>
                                <td colspan="4" class="text-end fw-bold">Tổng cộng:</td>
                                <td class="fw-bold text-danger"><?php echo number_format($order->total_amount, 0, ',', '.'); ?> VND</td>
                            </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card shadow-sm mb-4">
                <div class="card-header">
                    <h5 class="mb-0">Thông tin khách hàng</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <h6>Họ tên:</h6>
                        <p><?php echo $order->name; ?></p>
                    </div>
                    <div class="mb-3">
                        <h6>Email:</h6>
                        <p><?php echo $order->email; ?></p>
                    </div>
                    <div class="mb-3">
                        <h6>Số điện thoại:</h6>
                        <p><?php echo $order->phone; ?></p>
                    </div>
                    <div class="mb-3">
                        <h6>Địa chỉ giao hàng:</h6>
                        <p><?php echo $order->address; ?></p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    </main>
    </div>
    </div>

<?php include_once "views/layouts/footer.php"; ?>
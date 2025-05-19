<?php include_once "layouts/header.php"; ?>

    <div class="row">
        <div class="col-md-12 mb-3">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="/">Trang chủ</a></li>
                    <li class="breadcrumb-item"><a href="/cart">Giỏ hàng</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Thanh toán</li>
                </ol>
            </nav>
        </div>
    </div>

    <div class="row">
        <div class="col-md-8">
            <div class="card shadow-sm mb-4">
                <div class="card-header">
                    <h4 class="mb-0">Thông tin đặt hàng</h4>
                </div>
                <div class="card-body">
                    <form method="POST" action="/checkout">
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="name" class="form-label">Họ tên</label>
                                <input type="text" class="form-control" id="name" name="name" value="<?php echo $user->name; ?>" required>
                            </div>
                            <div class="col-md-6">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" class="form-control" id="email" name="email" value="<?php echo $user->email; ?>" required>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="phone" class="form-label">Số điện thoại</label>
                                <input type="tel" class="form-control" id="phone" name="phone" required>
                            </div>
                            <div class="col-md-6">
                                <label for="address" class="form-label">Địa chỉ giao hàng</label>
                                <input type="text" class="form-control" id="address" name="address" required>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Phương thức thanh toán</label>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="payment_method" id="payment-cod" value="cod" checked>
                                <label class="form-check-label" for="payment-cod">
                                    Thanh toán khi nhận hàng (COD)
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="payment_method" id="payment-vnpay" value="vnpay">
                                <label class="form-check-label" for="payment-vnpay">
                                    Thanh toán VNPAY
                                </label>
                            </div>
                        </div>
                        <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                            <a href="/cart" class="btn btn-outline-secondary me-md-2">Quay lại giỏ hàng</a>
                            <button type="submit" class="btn btn-success">Đặt hàng</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card shadow-sm mb-4">
                <div class="card-header">
                    <h4 class="mb-0">Đơn hàng của bạn</h4>
                </div>
                <div class="card-body">
                    <table class="table table-striped">
                        <thead>
                        <tr>
                            <th>Sản phẩm</th>
                            <th width="80">Số lượng</th>
                            <th width="120">Thành tiền</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($cart_items as $item): ?>
                            <tr>
                                <td><?php echo $item['name']; ?></td>
                                <td class="text-center"><?php echo $item['quantity']; ?></td>
                                <td><?php echo number_format($item['subtotal'], 0, ',', '.'); ?> VND</td>
                            </tr>
                        <?php endforeach; ?>
                        </tbody>
                        <tfoot>
                        <tr>
                            <td colspan="2" class="text-end fw-bold">Tổng cộng:</td>
                            <td class="fw-bold text-danger"><?php echo number_format($total, 0, ',', '.'); ?> VND</td>
                        </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>

<?php include_once "layouts/footer.php"; ?>
<?php include_once "layouts/header.php"; ?>

    <div class="row">
        <div class="col-md-12 mb-3">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="/">Trang chủ</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Giỏ hàng</li>
                </ol>
            </nav>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="card shadow-sm">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">Giỏ hàng của bạn</h4>
                    <?php if (!empty($cart_items)): ?>
                        <a href="/cart/clear" class="btn btn-sm btn-outline-danger" onclick="return confirm('Bạn có chắc chắn muốn xóa toàn bộ giỏ hàng?')">
                            <i class="fas fa-trash"></i> Xóa tất cả
                        </a>
                    <?php endif; ?>
                </div>
                <div class="card-body">
                    <?php if (!empty($cart_items)): ?>
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                <tr>
                                    <th>Sản phẩm</th>
                                    <th width="120">Hình ảnh</th>
                                    <th width="150">Đơn giá</th>
                                    <th width="120">Số lượng</th>
                                    <th width="150">Thành tiền</th>
                                    <th width="90">Thao tác</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php foreach ($cart_items as $item): ?>
                                    <tr>
                                        <td>
                                            <a style="text-decoration: none" href="/products/detail?id=<?php echo $item['id']; ?>"><?php echo $item['name']; ?></a>
                                        </td>
                                        <td>
                                            <img src="<?php echo !empty($item['image']) ? $item['image'] : 'https://via.placeholder.com/80x80?text=No+Image'; ?>" alt="<?php echo $item['name']; ?>" class="img-thumbnail" width="80">
                                        </td>
                                        <td><?php echo number_format($item['price'], 0, ',', '.'); ?> VND</td>
                                        <td>
                                            <form method="POST" action="/cart/update" class="d-flex">
                                                <input type="hidden" name="product_id" value="<?php echo $item['product_id']; ?>">
                                                <input type="number" name="quantity" value="<?php echo $item['quantity']; ?>" min="1" class="form-control form-control-sm me-2" style="width: 60px">
                                                <button type="submit" class="btn btn-sm btn-outline-primary">
                                                    <i class="fas fa-sync-alt"></i>
                                                </button>
                                            </form>
                                        </td>
                                        <td><?php echo number_format($item['subtotal'], 0, ',', '.'); ?> VND</td>
                                        <td>
                                            <a href="/cart/remove?id=<?php echo $item['product_id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Bạn có chắc chắn muốn xóa sản phẩm này?')">
                                                <i class="fas fa-trash"></i>
                                            </a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                                </tbody>
                                <tfoot>
                                <tr>
                                    <td colspan="4" class="text-end fw-bold">Tổng cộng:</td>
                                    <td class="fw-bold text-danger"><?php echo number_format($total, 0, ',', '.'); ?> VND</td>
                                    <td></td>
                                </tr>
                                </tfoot>
                            </table>
                        </div>

                        <div class="d-flex justify-content-between mt-4">
                            <a href="/products" class="btn btn-outline-primary">
                                <i class="fas fa-arrow-left"></i> Tiếp tục mua hàng
                            </a>
                            <a href="/checkout" class="btn btn-success">
                                <i class="fas fa-credit-card"></i> Thanh toán
                            </a>
                        </div>
                    <?php else: ?>
                        <div class="alert alert-info text-center py-4">
                            <i class="fas fa-shopping-cart fa-3x mb-3"></i>
                            <p>Giỏ hàng của bạn đang trống.</p>
                            <a href="/products" class="btn btn-primary mt-2">Mua sắm ngay</a>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

<?php include_once "layouts/footer.php"; ?>
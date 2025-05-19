<?php include_once "views/layouts/header.php"; ?>

<div class="row">
    <div class="col-md-12 mb-3">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="/">Trang chủ</a></li>
                <li class="breadcrumb-item"><a href="/products">Sản phẩm</a></li>
                <li class="breadcrumb-item active" aria-current="page"><?php echo $product->name; ?></li>
            </ol>
        </nav>
    </div>
</div>

<div class="row mb-5">
    <div class="col-md-5">
        <img src="<?php echo !empty($product->image) ? $product->image : 'https://via.placeholder.com/500x500?text=No+Image'; ?>" class="img-fluid rounded shadow" alt="<?php echo $product->name; ?>">
    </div>
    <div class="col-md-7">
        <h2><?php echo $product->name; ?></h2>
        <p class="text-muted">Danh mục: <?php echo $product->category_name; ?></p>

        <div class="my-3">
            <span class="h4 text-danger"><?php echo number_format($product->price, 0, ',', '.'); ?> VND</span>
        </div>

        <div class="my-3">
            <p>Tình trạng:
                <?php if ($product->stock > 0): ?>
                    <span class="badge bg-success">Còn hàng (<?php echo $product->stock; ?>)</span>
                <?php else: ?>
                    <span class="badge bg-danger">Hết hàng</span>
                <?php endif; ?>
            </p>
        </div>

        <div class="my-3">
            <p><?php echo nl2br($product->description); ?></p>
        </div>

        <?php if ($product->stock > 0): ?>
            <form method="POST" action="/cart/add" class="my-4">
                <input type="hidden" name="product_id" value="<?php echo $product->id; ?>">
                <div class="row g-3 align-items-center">
                    <div class="col-auto">
                        <label for="quantity" class="col-form-label">Số lượng:</label>
                    </div>
                    <div class="col-auto">
                        <input type="number" id="quantity" name="quantity" class="form-control" value="1" min="1" max="<?php echo $product->stock; ?>">
                    </div>
                    <div class="col-auto">
                        <button type="submit" class="btn text-white" style="background-color: #fb5858">
                            <i class="fas fa-cart-plus"></i> Thêm vào giỏ hàng
                        </button>
                    </div>
                </div>
            </form>
        <?php else: ?>
            <div class="alert alert-warning">Sản phẩm này hiện đã hết hàng.</div>
        <?php endif; ?>
        <span class="mt-3 p-3 rounded" style="background-color: rgba(173,250,218,0.44); border: 1px solid rgba(40,167,69,0.33);">
                    <i class="fas fa-truck"></i> Miễn phí giao hàng cho đơn hàng trên 300.000 VNĐ
                </span>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <h3 class="mb-4">Sản phẩm liên quan</h3>
        <div class="row row-cols-1 row-cols-md-2 row-cols-lg-4 g-4">
            <?php
            $count = 0;
            while ($related = $related_products->fetch(PDO::FETCH_ASSOC)):
            // Bỏ qua sản phẩm hiện tại
            if ($related['id'] == $product->id) continue;

            $count++;
            if ($count > 4) break; // Chỉ hiển thị tối đa 4 sản phẩm liên quan
            ?>
            <div class="col">
                <div class="card h-100 product-card shadow-sm">
                    <img src="<?php echo !empty($related['image']) ? $related['image'] : 'https://via.placeholder.com/300x200?text=No+Image'; ?>" class="card-img-top" alt="<?php echo $related['name']; ?>">
                    <div class="card-body">
                        <h5 class="card-title"><?php echo $related['name']; ?></h5>
                        <p class="card-text text-truncate"><?php echo $related['description']; ?></p>
                        <p class="card-text text-danger fw-bold"><?php echo number_format($related['price'], 0, ',', '.'); ?> VND</p>
                        <div class="d-flex justify-content-between">
                            <a href="/products/detail?id=<?php echo $related['id']; ?>" class="btn text-white" style="background-color: #fb5858">Chi tiết</a>
                            <form method="POST" action="/cart/add">
                                <input type="hidden" name="product_id" value="<?php echo $related['id']; ?>">
                                <input type="hidden" name="quantity" value="1">
                                <button type="submit" class="btn btn-outline-success">
                                    <i class="fas fa-cart-plus"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <?php endwhile; ?>
        </div>
    </div>
</div>

<?php include_once "views/layouts/footer.php"; ?>

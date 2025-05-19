<?php include_once "views/layouts/header.php"; ?>

    <div class="row">
        <div class="col-md-3">
            <div class="card mb-4">
                <div class="card-header">
                    <h5>Danh mục</h5>
                </div>
                <div class="card-body">
                    <div class="list-group">
                        <a href="/products" class="list-group-item list-group-item-action <?php echo empty($_GET['category']) ? 'active' : ''; ?>">
                            Tất cả sản phẩm
                        </a>
                        <?php while ($category = $categories->fetch(PDO::FETCH_ASSOC)): ?>
                            <a href="/products?category=<?php echo $category['id']; ?>" class="list-group-item list-group-item-action <?php echo (isset($_GET['category']) && $_GET['category'] == $category['id']) ? 'active' : ''; ?>">
                                <?php echo $category['name']; ?>
                            </a>
                        <?php endwhile; ?>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-9">
            <div class="card mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5>Danh sách sản phẩm</h5>
                    <?php if (!empty($_GET['search'])): ?>
                        <span>Kết quả tìm kiếm cho: "<?php echo htmlspecialchars($_GET['search']); ?>"</span>
                    <?php elseif (!empty($_GET['category'])): ?>
                        <?php
                        $current_category = "";
                        foreach ($categories as $cat) {
                            if ($cat['id'] == $_GET['category']) {
                                $current_category = $cat['name'];
                                break;
                            }
                        }
                        ?>
                        <span>Danh mục: <?php echo $current_category; ?></span>
                    <?php endif; ?>
                </div>
                <div class="card-body">
                    <?php if ($products->rowCount() > 0): ?>
                        <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
                            <?php while ($product = $products->fetch(PDO::FETCH_ASSOC)): ?>
                                <div class="col">
                                    <div class="card h-100 product-card shadow-sm">
                                        <img src="<?php echo !empty($product['image']) ? $product['image'] : 'https://via.placeholder.com/300x200?text=No+Image'; ?>" class="card-img-top" alt="<?php echo $product['name']; ?>">
                                        <div class="card-body">
                                            <h5 class="card-title"><?php echo $product['name']; ?></h5>
                                            <p class="card-text text-truncate"><?php echo $product['description']; ?></p>
                                            <p class="card-text text-danger fw-bold"><?php echo number_format($product['price'], 0, ',', '.'); ?> VND</p>
                                            <div class="d-flex justify-content-between">
                                                <a href="/products/detail?id=<?php echo $product['id']; ?>" class="btn text-white" style="background-color: #fb5858">Chi tiết</a>
                                                <form method="POST" action="/cart/add">
                                                    <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">
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
                    <?php else: ?>
                        <div class="alert alert-info">
                            Không tìm thấy sản phẩm nào.
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

<?php include_once "views/layouts/footer.php"; ?>
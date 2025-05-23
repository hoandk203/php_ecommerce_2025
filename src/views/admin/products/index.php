<?php
include_once "views/layouts/header.php";
$path = "/admin/products";
?>

<?php include_once "views/layouts/admin_sidebar.php"; ?>

    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">Quản lý sản phẩm</h1>
        <a href="/admin/products/create" class="btn btn-sm btn-primary">
            <i class="fas fa-plus"></i> Thêm sản phẩm
        </a>
    </div>

    <div class="card shadow-sm">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead>
                    <tr>
                        <th width="50">ID</th>
                        <th width="100">Hình ảnh</th>
                        <th>Tên sản phẩm</th>
                        <th>Danh mục</th>
                        <th>Giá</th>
                        <th>Giảm giá(%)</th>
                        <th>Tồn kho</th>
                        <th width="120">Thao tác</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php while ($product = $stmt->fetch(PDO::FETCH_ASSOC)): ?>
                        <tr>
                            <td><?php echo $product['id']; ?></td>
                            <td>
                                <img src="<?php echo !empty($product['image']) ? $product['image'] : 'https://via.placeholder.com/80x80?text=No+Image'; ?>" alt="<?php echo $product['name']; ?>" class="img-thumbnail" width="80">
                            </td>
                            <td><?php echo $product['name']; ?></td>
                            <td><?php echo $product['category_name']; ?></td>
                            <td><?php echo number_format($product['price'], 0, ',', '.'); ?> VND</td>
                            <td><?php echo $product['discount']; ?>%</td>
                            <td><?php echo $product['stock']; ?></td>
                            <td>
                                <a href="/admin/products/edit?id=<?php echo $product['id']; ?>" class="btn btn-sm btn-outline-primary">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <a href="/admin/products/delete?id=<?php echo $product['id']; ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Bạn có chắc chắn muốn xóa sản phẩm này?')">
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
<?php
include_once "views/layouts/header.php";
$path = "/admin/products";
?>

<?php include_once "views/layouts/admin_sidebar.php"; ?>

    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">Thêm sản phẩm</h1>
    </div>

    <div class="row">
        <div class="col-md-8">
            <div class="card shadow-sm">
                <div class="card-body">
                    <?php if (isset($error)): ?>
                        <div class="alert alert-danger">
                            <?php echo $error; ?>
                        </div>
                    <?php endif; ?>

                    <form method="POST" action="/admin/products/create" enctype="multipart/form-data">
                        <div class="mb-3">
                            <label for="name" class="form-label">Tên sản phẩm</label>
                            <input type="text" class="form-control" id="name" name="name" required>
                        </div>
                        <div class="mb-3">
                            <label for="category_id" class="form-label">Danh mục</label>
                            <select class="form-select" id="category_id" name="category_id" required>
                                <option value="">Chọn danh mục</option>
                                <?php while ($category = $categories->fetch(PDO::FETCH_ASSOC)): ?>
                                    <option value="<?php echo $category['id']; ?>"><?php echo $category['name']; ?></option>
                                <?php endwhile; ?>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="description" class="form-label">Mô tả</label>
                            <textarea class="form-control" id="description" name="description" rows="5"></textarea>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-4">
                                <label for="price" class="form-label">Giá (VND)</label>
                                <input type="number" class="form-control" id="price" name="price" min="0" required
                                       value="<?php echo isset($product->price) ? $product->price : ''; ?>">
                            </div>
                            <div class="col-md-4">
                                <label for="discount" class="form-label">Giảm giá (%)</label>
                                <input type="number" class="form-control" id="discount" name="discount" min="0" max="100"
                                       value="<?php echo isset($product->discount) ? $product->discount : 0; ?>">
                            </div>
                            <div class="col-md-4">
                                <label for="stock" class="form-label">Số lượng tồn kho</label>
                                <input type="number" class="form-control" id="stock" name="stock" min="0" required
                                       value="<?php echo isset($product->stock) ? $product->stock : ''; ?>">
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="image" class="form-label">Hình ảnh</label>
                            <input class="form-control" type="file" id="image" name="image">
                        </div>
                        <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                            <a href="/admin/products" class="btn btn-outline-secondary me-md-2">Hủy</a>
                            <button type="submit" class="btn btn-primary">Lưu</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    </main>
    </div>
    </div>

<?php include_once "views/layouts/footer.php"; ?>
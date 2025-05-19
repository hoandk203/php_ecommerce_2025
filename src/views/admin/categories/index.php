<?php
include_once "views/layouts/header.php";
$path = "/admin/categories";
?>

<?php include_once "views/layouts/admin_sidebar.php"; ?>

    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">Quản lý danh mục</h1>
        <a href="/admin/categories/create" class="btn btn-sm btn-primary">
            <i class="fas fa-plus"></i> Thêm danh mục
        </a>
    </div>

    <div class="card shadow-sm">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead>
                    <tr>
                        <th width="50">ID</th>
                        <th>Tên danh mục</th>
                        <th>Mô tả</th>
                        <th width="150">Ngày tạo</th>
                        <th width="120">Thao tác</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php while ($category = $stmt->fetch(PDO::FETCH_ASSOC)): ?>
                        <tr>
                            <td><?php echo $category['id']; ?></td>
                            <td><?php echo $category['name']; ?></td>
                            <td><?php echo $category['description']; ?></td>
                            <td><?php echo date('d/m/Y', strtotime($category['created_at'])); ?></td>
                            <td>
                                <a href="/admin/categories/edit?id=<?php echo $category['id']; ?>" class="btn btn-sm btn-outline-primary">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <a href="/admin/categories/delete?id=<?php echo $category['id']; ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Bạn có chắc chắn muốn xóa danh mục này?')">
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
<?php
include_once "views/layouts/header.php";
$path = "/admin/users";
?>

<?php include_once "views/layouts/admin_sidebar.php"; ?>

    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">Quản lý người dùng</h1>
    </div>

    <div class="card shadow-sm">
    <div class="card-body">
    <div class="table-responsive">
    <table class="table table-striped table-hover">
    <thead>
    <tr>
        <th width="50">ID</th>
        <th>Họ tên</th>
        <th>Email</th>
        <th>Vai trò</th>
        <th>Ngày đăng ký</th>
        <th width="100">Thao tác</th>
    </tr>
    </thead>
    <tbody>
<?php while ($user = $stmt->fetch(PDO::FETCH_ASSOC)): ?>
    <tr>
        <td><?php echo $user['id']; ?></td>
        <td><?php echo $user['name']; ?></td>
        <td><?php echo $user['email']; ?></td>
        <td>
            <?php if ($user['role'] === 'admin'): ?>
                <span class="badge bg-danger">Admin</span>
            <?php else: ?>
                <span class="badge bg-info">Khách hàng</span>
            <?php endif; ?>
        </td>
        <td><?php echo date('d/m/Y', strtotime($user['created_at'])); ?></td>
        <td>
            <a href="/admin/users/edit?id=<?php echo $user['id']; ?>" class="btn btn-sm btn-outline-primary">
                <i class="fas fa-edit"></i>
            </a>
        </td>
    </tr>
<?php endwhile; ?>
    </tbody>
    </table>
    </div>
    </div>
    </div>

<?php include_once "views/layouts/footer.php"; ?>
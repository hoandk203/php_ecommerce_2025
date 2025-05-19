<?php
include_once "views/layouts/header.php";
$path = "/admin/users";
?>

<?php include_once "views/layouts/admin_sidebar.php"; ?>

<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Chỉnh sửa người dùng</h1>
</div>

<div class="card shadow-sm">
    <div class="card-header">
        <h5 class="card-title mb-0">Thông tin người dùng</h5>
    </div>
    <div class="card-body">
        <?php if (isset($error)): ?>
            <div class="alert alert-danger"><?php echo $error; ?></div>
        <?php endif; ?>
        
        <form method="POST" action="/admin/users/edit?id=<?php echo $user->id; ?>">
            <div class="mb-3">
                <label for="name" class="form-label">Họ tên</label>
                <input type="text" class="form-control" id="name" name="name" value="<?php echo $user->name; ?>" required>
            </div>
            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" class="form-control" id="email" value="<?php echo $user->email; ?>" disabled>
            </div>
            <div class="mb-3">
                <label for="role" class="form-label">Vai trò</label>
                <select class="form-select" id="role" name="role">
                    <option value="user" <?php echo $user->role === 'user' ? 'selected' : ''; ?>>Khách hàng</option>
                    <option value="admin" <?php echo $user->role === 'admin' ? 'selected' : ''; ?>>Admin</option>
                </select>
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Mật khẩu mới (để trống nếu không thay đổi)</label>
                <input type="password" class="form-control" id="password" name="password">
            </div>
            <div class="mb-3">
                <label for="created_at" class="form-label">Ngày đăng ký</label>
                <input type="text" class="form-control" id="created_at" value="<?php echo date('d/m/Y', strtotime($user->created_at)); ?>" disabled>
            </div>
            <div class="d-flex justify-content-between">
                <a href="/admin/users" class="btn btn-outline-secondary">Quay lại</a>
                <button type="submit" class="btn btn-primary">Cập nhật</button>
            </div>
        </form>
    </div>
</div>

<?php include_once "views/layouts/footer.php"; ?>

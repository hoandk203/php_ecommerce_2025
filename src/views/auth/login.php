<?php include_once "views/layouts/header.php"; ?>

    <div class="row justify-content-center mt-5">
        <div class="col-md-6">
            <div class="card shadow-sm">
                <div class="card-header bg-dark text-white">
                    <h4 class="mb-0">Đăng nhập</h4>
                </div>
                <div class="card-body">
                    <?php if (isset($error)): ?>
                        <div class="alert alert-danger">
                            <?php echo $error; ?>
                        </div>
                    <?php endif; ?>

                    <form method="POST" action="/login">
                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control" id="email" name="email" required>
                        </div>
                        <div class="mb-3">
                            <label for="password" class="form-label">Mật khẩu</label>
                            <input type="password" class="form-control" id="password" name="password" required>
                        </div>
                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-dark">Đăng nhập</button>
                        </div>
                    </form>

                    <hr>

                    <div class="text-center">
                        <p>Chưa có tài khoản? <a style="color: black; font-weight: bold" href="/register">Đăng ký ngay</a></p>
                    </div>
                </div>
            </div>
        </div>
    </div>

<?php include_once "views/layouts/footer.php"; ?>
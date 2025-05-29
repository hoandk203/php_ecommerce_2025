<?php require_once 'views/layouts/header.php'; ?>

<div class="container-fluid">
    <div class="row">
        <?php require_once 'views/layouts/admin_sidebar.php'; ?>
        
        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
            <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                <h1 class="h2">Chỉnh sửa câu trả lời</h1>
                <div class="btn-toolbar mb-2 mb-md-0">
                    <a href="/admin/chatbot" class="btn btn-sm btn-outline-secondary">
                        <i class="fas fa-arrow-left"></i> Quay lại
                    </a>
                </div>
            </div>

            <?php if (isset($_SESSION['error'])): ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <?= $_SESSION['error'] ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
                <?php unset($_SESSION['error']); ?>
            <?php endif; ?>

            <div class="card">
                <div class="card-body">
                    <form method="POST" action="/admin/chatbot/edit?id=<?= $chatbot->id ?>">
                        <div class="mb-3">
                            <label for="keywords" class="form-label">Từ khóa</label>
                            <input type="text" class="form-control" id="keywords" name="keywords" required
                                   value="<?= htmlspecialchars($chatbot->keywords) ?>"
                                   placeholder="Nhập các từ khóa, phân cách bằng dấu phẩy">
                            <div class="form-text">Ví dụ: xin chào, chào, hi, hello</div>
                        </div>

                        <div class="mb-3">
                            <label for="response" class="form-label">Câu trả lời</label>
                            <textarea class="form-control" id="response" name="response" rows="3" required
                                      placeholder="Nhập câu trả lời cho chatbot"><?= htmlspecialchars($chatbot->response) ?></textarea>
                        </div>

                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Lưu thay đổi
                        </button>
                    </form>
                </div>
            </div>
        </main>
    </div>
</div>

<?php require_once 'views/layouts/footer.php'; ?> 
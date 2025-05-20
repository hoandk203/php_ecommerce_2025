<?php
class CategoryController {
    private $db;

    public function __construct() {
        require_once 'config/Database.php';
        $database = new Database();
        $this->db = $database->getConnection();
    }

    // Lấy tất cả danh mục
    public function index() {
        require_once 'models/Category.php';
        $category = new Category($this->db);
        $categories = $category->getAll();

        require_once 'views/categories/index.php';
    }

    // Hiển thị form thêm danh mục
    public function create() {
        // Kiểm tra quyền admin
        if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
            $_SESSION['error'] = 'Bạn không có quyền truy cập trang này';
            header("Location: /");
            exit;
        }

        require_once 'views/admin/categories/create.php';
    }

    // Xử lý thêm danh mục
    public function store() {
        // Kiểm tra quyền admin
        if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
            $_SESSION['error'] = 'Bạn không có quyền thực hiện hành động này';
            header("Location: /");
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name = trim($_POST['name']);
            $description = trim($_POST['description']);

            // Kiểm tra dữ liệu
            if (empty($name)) {
                $error = "Tên danh mục không được để trống";
                require_once 'views/admin/categories/create.php';
                return;
            }

            require_once 'models/Category.php';
            $category = new Category($this->db);
            $category->name = $name;
            $category->description = $description;

            if ($category->create()) {
                $_SESSION['success'] = "Thêm danh mục thành công";
                header("Location: /admin/categories");
                exit;
            } else {
                $error = "Có lỗi xảy ra, vui lòng thử lại";
                require_once 'views/admin/categories/create.php';
                return;
            }
        }
    }

    // Hiển thị form chỉnh sửa danh mục
    public function edit($id) {
        // Kiểm tra quyền admin
        if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
            $_SESSION['error'] = 'Bạn không có quyền truy cập trang này';
            header("Location: /");
            exit;
        }

        require_once 'models/Category.php';
        $category = new Category($this->db);
        $category->id = $id;

        if (!$category->getById($id)) {
            $_SESSION['error'] = "Danh mục không tồn tại";
            header("Location: /admin/categories");
            exit;
        }

        require_once 'views/admin/categories/edit.php';
    }

    // Xử lý cập nhật danh mục
    public function update($id) {
        // Kiểm tra quyền admin
        if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
            $_SESSION['error'] = 'Bạn không có quyền thực hiện hành động này';
            header("Location: /");
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name = trim($_POST['name']);
            $description = trim($_POST['description']);

            // Kiểm tra dữ liệu
            if (empty($name)) {
                $error = "Tên danh mục không được để trống";
                require_once 'models/Category.php';
                $category = new Category($this->db);
                $category->getById($id);
                require_once 'views/admin/categories/edit.php';
                return;
            }

            require_once 'models/Category.php';
            $category = new Category($this->db);
            $category->id = $id;
            $category->name = $name;
            $category->description = $description;

            if ($category->update()) {
                $_SESSION['success'] = "Cập nhật danh mục thành công";
                header("Location: /admin/categories");
                exit;
            } else {
                $error = "Có lỗi xảy ra, vui lòng thử lại";
                $category->getById($id);
                require_once 'views/admin/categories/edit.php';
                return;
            }
        }
    }

    // Xử lý xóa danh mục
    public function delete($id) {
        // Kiểm tra quyền admin
        if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
            $_SESSION['error'] = 'Bạn không có quyền thực hiện hành động này';
            header("Location: /");
            exit;
        }

        require_once 'models/Category.php';
        $category = new Category($this->db);
        $category->id = $id;

        // Kiểm tra danh mục có sản phẩm không
        require_once 'models/Product.php';
        $product = new Product($this->db);
        $products = $product->getByCategory($id);

        if ($products->rowCount() > 0) {
            $_SESSION['error'] = "Không thể xóa danh mục đang chứa sản phẩm";
            header("Location: /admin/categories");
            exit;
        }

        if ($category->delete()) {
            $_SESSION['success'] = "Xóa danh mục thành công";
        } else {
            $_SESSION['error'] = "Có lỗi xảy ra, vui lòng thử lại";
        }

        header("Location: /admin/categories");
        exit;
    }
}
?>
<?php
class AdminController {
    private $db;

    public function __construct() {
        require_once 'config/Database.php';
        $database = new Database();
        $this->db = $database->getConnection();

        // Kiểm tra quyền admin
        if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
            header("Location: /login");
            exit;
        }
    }

    // Dashboard admin
    public function index() {
        // Thống kê tổng số người dùng
        require_once 'models/User.php';
        $user = new User($this->db);
        $users = $user->getAll();
        $userCount = $users->rowCount();

        // Thống kê tổng số sản phẩm
        require_once 'models/Product.php';
        $product = new Product($this->db);
        $products = $product->getAll();
        $productCount = $products->rowCount();

        // Thống kê tổng số danh mục
        require_once 'models/Category.php';
        $category = new Category($this->db);
        $categories = $category->getAll();
        $categoryCount = $categories->rowCount();

        // Thống kê tổng số đơn hàng
        require_once 'models/Order.php';
        $order = new Order($this->db);
        $orders = $order->getAll();
        $orderCount = $orders->rowCount();

        // Lấy các đơn hàng gần đây
        $query = "SELECT o.*, u.name as user_name 
                FROM orders o
                LEFT JOIN users u ON o.user_id = u.id
                ORDER BY o.created_at DESC
                LIMIT 5";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        $recentOrderStmt = $stmt;
        
        // Lấy dữ liệu doanh thu theo tháng
        $monthlyRevenueStmt = $order->getMonthlyRevenue();
        
        // Lấy dữ liệu doanh thu sản phẩm theo tháng
        $monthlyProductRevenueStmt = $order->getMonthlyProductRevenue();
        
        // Chuẩn bị dữ liệu cho biểu đồ
        $monthLabels = [];
        $revenueData = [];
        
        // Khởi tạo mảng với 12 tháng, giá trị mặc định là 0
        for ($i = 1; $i <= 12; $i++) {
            $monthLabels[] = 'Tháng ' . $i;
            $revenueData[$i] = 0;
        }
        
        // Điền dữ liệu thực tế
        while ($row = $monthlyRevenueStmt->fetch(PDO::FETCH_ASSOC)) {
            $month = (int)$row['month'];
            $revenueData[$month] = (float)$row['revenue'];
        }
        
        // Chuyển đổi mảng kết hợp thành mảng tuần tự cho JavaScript
        $revenueData = array_values($revenueData);
        
        // Chuẩn bị dữ liệu sản phẩm bán chạy theo tháng
        $productRevenueData = [];
        while ($row = $monthlyProductRevenueStmt->fetch(PDO::FETCH_ASSOC)) {
            $month = (int)$row['month'];
            $productName = $row['product_name'];
            $revenue = (float)$row['revenue'];
            
            if (!isset($productRevenueData[$month])) {
                $productRevenueData[$month] = [];
            }
            
            // Chỉ lấy 5 sản phẩm có doanh thu cao nhất mỗi tháng
            if (count($productRevenueData[$month]) < 5) {
                $productRevenueData[$month][] = [
                    'product_name' => $productName,
                    'revenue' => $revenue
                ];
            }
        }
        
        // Chuyển đổi dữ liệu sang định dạng JSON để sử dụng trong JavaScript
        $monthlyRevenueJson = json_encode($revenueData);
        $monthLabelsJson = json_encode($monthLabels);
        $productRevenueJson = json_encode($productRevenueData);

        require_once 'views/admin/dashboard.php';
    }

    // Quản lý người dùng
    public function users() {
        require_once 'models/User.php';
        $user = new User($this->db);
        $stmt = $user->getAll();

        require_once 'views/admin/users/index.php';
    }

    // Chỉnh sửa người dùng
    public function editUser($id) {
        require_once 'models/User.php';
        $user = new User($this->db);

        // Kiểm tra người dùng tồn tại
        if (!$user->getById($id)) {
            header("Location: /admin/users");
            exit;
        }

        // Xử lý cập nhật
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $user->name = $_POST['name'];
            $user->role = $_POST['role'];

            // Nếu có cập nhật mật khẩu
            if (!empty($_POST['password'])) {
                $user->password = $_POST['password'];
                $user->updatePassword();
            }

            if ($user->update()) {
                $_SESSION['success'] = "Cập nhật người dùng thành công!";
                header("Location: /admin/users");
                exit;
            } else {
                $error = "Đã xảy ra lỗi khi cập nhật người dùng!";
            }
        }

        require_once 'views/admin/users/edit.php';
    }

    // Quản lý danh mục
    public function categories() {
        require_once 'models/Category.php';
        $category = new Category($this->db);
        $stmt = $category->getAll();

        require_once 'views/admin/categories/index.php';
    }

    // Thêm danh mục
    public function createCategory() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            require_once 'models/Category.php';
            $category = new Category($this->db);

            $category->name = $_POST['name'];
            $category->description = $_POST['description'];

            if ($category->create()) {
                $_SESSION['success'] = "Thêm danh mục thành công!";
                header("Location: /admin/categories");
                exit;
            } else {
                $error = "Đã xảy ra lỗi khi thêm danh mục!";
            }
        }

        require_once 'views/admin/categories/create.php';
    }

    // Chỉnh sửa danh mục
    public function editCategory($id) {
        require_once 'models/Category.php';
        $category = new Category($this->db);

        // Kiểm tra danh mục tồn tại
        if (!$category->getById($id)) {
            header("Location: /admin/categories");
            exit;
        }

        // Xử lý cập nhật
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $category->name = $_POST['name'];
            $category->description = $_POST['description'];

            if ($category->update()) {
                $_SESSION['success'] = "Cập nhật danh mục thành công!";
                header("Location: /admin/categories");
                exit;
            } else {
                $error = "Đã xảy ra lỗi khi cập nhật danh mục!";
            }
        }

        require_once 'views/admin/categories/edit.php';
    }

    // Xóa danh mục
    public function deleteCategory($id) {
        require_once 'models/Category.php';
        $category = new Category($this->db);

        // Kiểm tra danh mục tồn tại
        if ($category->getById($id)) {
            // Kiểm tra danh mục đã có sản phẩm chưa
            $query = "SELECT COUNT(*) as count FROM products WHERE category_id = ?";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(1, $id);
            $stmt->execute();
            $row = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($row['count'] > 0) {
                $_SESSION['error'] = "Không thể xóa danh mục đã có sản phẩm!";
                header("Location: /admin/categories");
                exit;
            }

            if ($category->delete()) {
                $_SESSION['success'] = "Xóa danh mục thành công!";
            } else {
                $_SESSION['error'] = "Đã xảy ra lỗi khi xóa danh mục!";
            }
        }

        header("Location: /admin/categories");
        exit;
    }

    // Quản lý sản phẩm
    public function products() {
        // Thêm logic phân trang
        $items_per_page = 6; // Số sản phẩm mỗi trang
        $current_page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $offset = ($current_page - 1) * $items_per_page;

        require_once 'models/Product.php';
        $product = new Product($this->db);

        // Lấy tổng số sản phẩm để tính số trang
        $total_items = $product->getTotalFiltered();
        $total_pages = ceil($total_items / $items_per_page);
        
        // Đảm bảo current_page hợp lệ
        if ($current_page < 1) $current_page = 1;
        if ($current_page > $total_pages) $current_page = $total_pages;

        // Lấy danh sách sản phẩm có phân trang
        $products = $product->getFiltered(null, null, null, null, null, $items_per_page, $offset);

        require_once 'views/admin/products/index.php';
    }

    // Thêm sản phẩm
    public function createProduct() {
        // Lấy danh sách danh mục
        require_once 'models/Category.php';
        $category = new Category($this->db);
        $categories = $category->getAll();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            require_once 'models/Product.php';
            $product = new Product($this->db);

            $product->name = $_POST['name'];
            $product->category_id = $_POST['category_id'];
            $product->description = $_POST['description'];
            $product->price = $_POST['price'];
            $product->stock = $_POST['stock'];
            $product->discount = $_POST['discount'];

            // Xử lý upload ảnh
            if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
                $upload_dir = 'assets/uploads/';

                // Tạo thư mục nếu chưa tồn tại
                if (!is_dir($upload_dir)) {
                    mkdir($upload_dir, 0755, true);
                }

                $file_name = time() . '_' . $_FILES['image']['name'];
                $upload_path = $upload_dir . $file_name;

                if (move_uploaded_file($_FILES['image']['tmp_name'], $upload_path)) {
                    $product->image = '/' . $upload_path;
                }
            }

            if ($product->create()) {
                $_SESSION['success'] = "Thêm sản phẩm thành công!";
                header("Location: /admin/products");
                exit;
            } else {
                $error = "Đã xảy ra lỗi khi thêm sản phẩm!";
            }
        }

        require_once 'views/admin/products/create.php';
    }

    // Chỉnh sửa sản phẩm
    public function editProduct($id) {
        require_once 'models/Product.php';
        $product = new Product($this->db);

        // Kiểm tra sản phẩm tồn tại
        if (!$product->getById($id)) {
            header("Location: /admin/products");
            exit;
        }

        // Lấy danh sách danh mục
        require_once 'models/Category.php';
        $category = new Category($this->db);
        $categories = $category->getAll();

        // Xử lý cập nhật
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $product->name = $_POST['name'];
            $product->category_id = $_POST['category_id'];
            $product->description = $_POST['description'];
            $product->price = $_POST['price'];
            $product->stock = $_POST['stock'];
            $product->discount = $_POST['discount'];

            // Xử lý upload ảnh
            if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
                $upload_dir = 'assets/uploads/';

                // Tạo thư mục nếu chưa tồn tại
                if (!is_dir($upload_dir)) {
                    mkdir($upload_dir, 0755, true);
                }

                $file_name = time() . '_' . $_FILES['image']['name'];
                $upload_path = $upload_dir . $file_name;

                if (move_uploaded_file($_FILES['image']['tmp_name'], $upload_path)) {
                    $product->image = '/' . $upload_path;
                }
            }

            if ($product->update()) {
                $_SESSION['success'] = "Cập nhật sản phẩm thành công!";
                header("Location: /admin/products");
                exit;
            } else {
                $error = "Đã xảy ra lỗi khi cập nhật sản phẩm!";
            }
        }

        require_once 'views/admin/products/edit.php';
    }

    // Xóa sản phẩm
    public function deleteProduct($id) {
        require_once 'models/Product.php';
        $product = new Product($this->db);

        // Kiểm tra sản phẩm tồn tại
        if ($product->getById($id)) {
            if ($product->delete()) {
                $_SESSION['success'] = "Xóa sản phẩm thành công!";
            } else {
                $_SESSION['error'] = "Đã xảy ra lỗi khi xóa sản phẩm!";
            }
        }

        header("Location: /admin/products");
        exit;
    }

    // Quản lý đơn hàng
    public function orders() {
        // Thêm logic phân trang và lọc
        $items_per_page = 7; // Số đơn hàng mỗi trang
        $current_page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $status = isset($_GET['status']) ? $_GET['status'] : null;
        $offset = ($current_page - 1) * $items_per_page;

        require_once 'models/Order.php';
        $order = new Order($this->db);

        // Lấy tổng số đơn hàng để tính số trang
        $total_items = $order->getTotalFiltered($status);
        $total_pages = ceil($total_items / $items_per_page);
        
        // Đảm bảo current_page hợp lệ
        if ($current_page < 1) $current_page = 1;
        if ($current_page > $total_pages) $current_page = $total_pages;

        // Lấy danh sách đơn hàng có phân trang và lọc
        $orders = $order->getFiltered($status, $items_per_page, $offset);

        require_once 'views/admin/orders/index.php';
    }

    // Chi tiết đơn hàng
    public function viewOrder($id) {
        require_once 'models/Order.php';
        $order = new Order($this->db);

        // Kiểm tra đơn hàng tồn tại
        if (!$order->getById($id)) {
            header("Location: /admin/orders");
            exit;
        }

        // Lấy chi tiết đơn hàng
        require_once 'models/OrderItem.php';
        $order_item = new OrderItem($this->db);
        $order_items = $order_item->getByOrderId($order->id);

        require_once 'views/admin/orders/view.php';
    }

    // Cập nhật trạng thái đơn hàng
    public function updateOrderStatus() {
        $order_id = isset($_POST['order_id']) ? (int)$_POST['order_id'] : 0;
        $status = isset($_POST['status']) ? $_POST['status'] : '';

        require_once 'models/Order.php';
        $order = new Order($this->db);

        // Kiểm tra đơn hàng tồn tại
        if ($order->getById($order_id)) {
            $order->status = $status;

            if ($order->updateStatus()) {
                $_SESSION['success'] = "Cập nhật trạng thái đơn hàng thành công!";
            } else {
                $_SESSION['error'] = "Đã xảy ra lỗi khi cập nhật trạng thái đơn hàng!";
            }
        }

        header("Location: /admin/orders/view?id=$order_id");
        exit;
    }

    // Xóa đơn hàng
    public function deleteOrder($id) {
        require_once 'models/Order.php';
        $order = new Order($this->db);

        // Kiểm tra đơn hàng tồn tại
        if ($order->getById($id)) {
            if ($order->delete()) {
                $_SESSION['success'] = "Xóa đơn hàng thành công!";
            } else {
                $_SESSION['error'] = "Đã xảy ra lỗi khi xóa đơn hàng!";
            }
        }

        header("Location: /admin/orders");
        exit;
    }

}
?>
<?php
// Bắt đầu session
session_start();

// Định nghĩa URL cơ sở
define('BASE_URL', '/');

// Lấy đường dẫn URL hiện tại
$request_uri = $_SERVER['REQUEST_URI'];
$path = parse_url($request_uri, PHP_URL_PATH);

// Loại bỏ dấu "/" ở cuối nếu có
$path = rtrim($path, '/');
if (empty($path)) {
    $path = '/';
}

// Xử lý routing
switch ($path) {
    // Trang chủ
    case '/':
        require_once 'controllers/HomeController.php';
        $controller = new HomeController();
        $controller->index();
        break;

    // Xem sản phẩm
    case '/products':
        require_once 'controllers/ProductController.php';
        $controller1 = new ProductController();
        $controller1->index();
        break;

    // Chi tiết sản phẩm
    case '/products/detail':
        $id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
        require_once 'controllers/ProductController.php';
        $controller = new ProductController();
        $controller->detail($id);
        break;

    // Giỏ hàng
    case '/cart':
        require_once 'controllers/CartController.php';
        $controller = new CartController();
        $controller->index();
        break;

    // Thêm vào giỏ hàng
    case '/cart/add':
        require_once 'controllers/CartController.php';
        $controller = new CartController();
        $controller->add();
        break;

    // Cập nhật giỏ hàng
    case '/cart/update':
        require_once 'controllers/CartController.php';
        $controller = new CartController();
        $controller->update();
        break;

    // Xóa khỏi giỏ hàng
    case '/cart/remove':
        require_once 'controllers/CartController.php';
        $controller = new CartController();
        $controller->remove();
        break;

    // Xóa toàn bộ giỏ hàng
    case '/cart/clear':
        require_once 'controllers/CartController.php';
        $controller = new CartController();
        $controller->clear();
        break;

    // Thanh toán
    case '/checkout':
        require_once 'controllers/OrderController.php';
        $controller = new OrderController();
        $controller->checkout();
        break;
        
    // Xử lý kết quả thanh toán từ VNPAY
    case '/vnpay-return':
        require_once 'controllers/OrderController.php';
        $controller = new OrderController();
        $controller->vnpayReturn();
        break;

    // Danh sách đơn hàng của người dùng
    case '/orders':
        require_once 'controllers/OrderController.php';
        $controller = new OrderController();
        $controller->index();
        break;

    // Chi tiết đơn hàng
    case '/orders/view':
        $id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
        require_once 'controllers/OrderController.php';
        $controller = new OrderController();
        $controller->view($id);
        break;

    // Đăng ký
    case '/register':
        require_once 'controllers/UserController.php';
        $controller = new UserController();
        $controller->register();
        break;

    // Đăng nhập
    case '/login':
        require_once 'controllers/UserController.php';
        $controller = new UserController();
        $controller->login();
        break;

    // Đăng xuất
    case '/logout':
        require_once 'controllers/UserController.php';
        $controller = new UserController();
        $controller->logout();
        break;

    // Thông tin tài khoản
    case '/profile':
        require_once 'controllers/UserController.php';
        $controller = new UserController();
        $controller->profile();
        break;

    // ADMIN ROUTES

    // Dashboard admin
    case '/admin':
        require_once 'controllers/AdminController.php';
        $controller = new AdminController();
        $controller->index();
        break;

    // Quản lý người dùng
    case '/admin/users':
        require_once 'controllers/AdminController.php';
        $controller = new AdminController();
        $controller->users();
        break;

    // Chỉnh sửa người dùng
    case '/admin/users/edit':
        $id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
        require_once 'controllers/AdminController.php';
        $controller = new AdminController();
        $controller->editUser($id);
        break;

    // Quản lý danh mục
    case '/admin/categories':
        require_once 'controllers/AdminController.php';
        $controller = new AdminController();
        $controller->categories();
        break;

    // Thêm danh mục
    case '/admin/categories/create':
        require_once 'controllers/AdminController.php';
        $controller = new AdminController();
        $controller->createCategory();
        break;

    // Chỉnh sửa danh mục
    case '/admin/categories/edit':
        $id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
        require_once 'controllers/AdminController.php';
        $controller = new AdminController();
        $controller->editCategory($id);
        break;

    // Xóa danh mục
    case '/admin/categories/delete':
        $id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
        require_once 'controllers/AdminController.php';
        $controller = new AdminController();
        $controller->deleteCategory($id);
        break;

    // Quản lý sản phẩm
    case '/admin/products':
        require_once 'controllers/AdminController.php';
        $controller = new AdminController();
        $controller->products();
        break;

    // Thêm sản phẩm
    case '/admin/products/create':
        require_once 'controllers/AdminController.php';
        $controller = new AdminController();
        $controller->createProduct();
        break;

    // Chỉnh sửa sản phẩm
    case '/admin/products/edit':
        $id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
        require_once 'controllers/AdminController.php';
        $controller = new AdminController();
        $controller->editProduct($id);
        break;

    // Xóa sản phẩm
    case '/admin/products/delete':
        $id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
        require_once 'controllers/AdminController.php';
        $controller = new AdminController();
        $controller->deleteProduct($id);
        break;

    // Quản lý đơn hàng
    case '/admin/orders':
        require_once 'controllers/AdminController.php';
        $controller = new AdminController();
        $controller->orders();
        break;

    // Chi tiết đơn hàng
    case '/admin/orders/view':
        $id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
        require_once 'controllers/AdminController.php';
        $controller = new AdminController();
        $controller->viewOrder($id);
        break;

    // Cập nhật trạng thái đơn hàng
    case '/admin/orders/update-status':
        require_once 'controllers/AdminController.php';
        $controller = new AdminController();
        $controller->updateOrderStatus();
        break;

    case '/orders/cancel':
        require_once 'controllers/OrderController.php';
        $controller = new OrderController();
        $controller->cancelOrder();
        break;

    case '/admin/orders/delete':
        $id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
        require_once 'controllers/AdminController.php';
        $controller = new AdminController();
        $controller->deleteOrder($id);
        break;

    // Trang không tồn tại
    default:
        http_response_code(404);
        include_once 'views/404.php';
        break;
}
?>

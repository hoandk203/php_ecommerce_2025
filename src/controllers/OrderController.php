<?php
class OrderController {
    private $db;

    public function __construct() {
        require_once 'config/database.php';
        $database = new Database();
        $this->db = $database->getConnection();
    }

    // Trang thanh toán
    public function checkout() {
        // Kiểm tra đăng nhập
        if (!isset($_SESSION['user'])) {
            $_SESSION['error'] = "Vui lòng đăng nhập để thanh toán!";
            header("Location: /login");
            exit;
        }

        // Kiểm tra giỏ hàng trong database
        require_once 'models/Cart.php';
        $cart = new Cart($this->db);
        $cart_id = $cart->getOrCreateCart($_SESSION['user']['id']);
        $cart_items = $cart->getItems();

        if (empty($cart_items)) {
            $_SESSION['error'] = "Giỏ hàng trống!";
            header("Location: /cart");
            exit;
        }

        // Lấy thông tin người dùng
        require_once 'models/User.php';
        $user = new User($this->db);
        $user->getById($_SESSION['user']['id']);

        // Tính tổng tiền
        $total = $cart->getTotal();

        require_once 'models/Product.php';
        $product = new Product($this->db);


        // xử lý đặt hàng
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            require_once 'models/Order.php';
            $order = new Order($this->db);

            // Thông tin đơn hàng
            $order->user_id = $_SESSION['user']['id'];
            $order->total_amount = $total; // Using total from cart
            $order->name = $_POST['name'];
            $order->email = $_POST['email'];
            $order->phone = $_POST['phone'];
            $order->address = $_POST['address'];
            $order->payment_method = $_POST['payment_method'];

            // Tạo đơn hàng
            if ($order->create()) {
                require_once 'models/OrderItem.php';
                $order_item = new OrderItem($this->db);

                // Thêm chi tiết đơn hàng từ cart_items
                foreach ($cart_items as $item) {
                    $order_item->order_id = $order->id;
                    $order_item->product_id = $item['product_id'];
                    $order_item->quantity = $item['quantity'];
                    $order_item->price = $item['price'];
                    $order_item->create();

                    // Cập nhật số lượng tồn kho
                    $product->getById($item['product_id']);
                    $product->updateStock($item['quantity']);
                }

                // Xóa giỏ hàng sau khi đặt hàng thành công
                $cart->clear();

                $_SESSION['success'] = "Đặt hàng thành công!";
                header("Location: /orders");
                exit;
            } else {
                $error = "Đã xảy ra lỗi khi đặt hàng!";
            }
        }

        require_once 'views/checkout.php';
    }

    // Danh sách đơn hàng của người dùng
    public function index() {
        // Kiểm tra đăng nhập
        if (!isset($_SESSION['user'])) {
            header("Location: /login");
            exit;
        }

        require_once 'models/Order.php';
        $order = new Order($this->db);
        $orders = $order->getByUser($_SESSION['user']['id']);

        require_once 'views/orders/index.php';
    }

    // Chi tiết đơn hàng
    public function view($id) {
        // Kiểm tra đăng nhập
        if (!isset($_SESSION['user'])) {
            header("Location: /login");
            exit;
        }

        require_once 'models/Order.php';
        $order = new Order($this->db);

        // Kiểm tra đơn hàng tồn tại và thuộc về người dùng hiện tại
        if (!$order->getById($id) || ($order->user_id != $_SESSION['user']['id'] && $_SESSION['user']['role'] != 'admin')) {
            header("Location: /orders");
            exit;
        }

        // Lấy chi tiết đơn hàng
        require_once 'models/OrderItem.php';
        $order_item = new OrderItem($this->db);
        $order_items = $order_item->getByOrderId($order->id);

        require_once 'views/orders/view.php';
    }
}
?>
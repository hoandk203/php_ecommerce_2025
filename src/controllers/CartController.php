<?php
class CartController {
    private $db;
    private $cart;

    public function __construct() {
        require_once 'config/database.php';
        $database = new Database();
        $this->db = $database->getConnection();
        
        // Kiểm tra đăng nhập
        if (!isset($_SESSION['user']['id'])) {
            $_SESSION['error'] = "Vui lòng đăng nhập để sử dụng giỏ hàng";
            header("Location: /login");
            exit;
        }
        
        // Khởi tạo giỏ hàng
        require_once 'models/Cart.php';
        $this->cart = new Cart($this->db);
        $this->cart->getOrCreateCart($_SESSION['user']['id']);
    }

    // Hiển thị giỏ hàng
    public function index() {
        $cart_items = $this->cart->getItems();
        $total = $this->cart->getTotal();
        
        require_once 'views/cart.php';
    }

    // Thêm sản phẩm vào giỏ hàng
    public function add() {
        $product_id = isset($_POST['product_id']) ? (int)$_POST['product_id'] : 0;
        $quantity = isset($_POST['quantity']) ? (int)$_POST['quantity'] : 1;

        // Kiểm tra số lượng hợp lệ
        if ($quantity <= 0) {
            $quantity = 1;
        }

        // Kiểm tra sản phẩm tồn tại
        require_once 'models/Product.php';
        $product = new Product($this->db);

        if ($product->getById($product_id)) {
            // Kiểm tra số lượng tồn kho
            if ($product->stock < $quantity) {
                $_SESSION['error'] = "Số lượng sản phẩm trong kho không đủ!";
                header("Location: /products/detail?id=$product_id");
                exit;
            }

            // Thêm vào giỏ hàng
            if ($this->cart->addItem($product_id, $quantity)) {
                $_SESSION['success'] = "Đã thêm sản phẩm vào giỏ hàng!";
            } else {
                $_SESSION['error'] = "Không thể thêm sản phẩm vào giỏ hàng!";
            }
        }

        header("Location: /cart");
        exit;
    }


    // Cập nhật số lượng sản phẩm trong giỏ hàng
    public function update() {
        $product_id = isset($_POST['product_id']) ? (int)$_POST['product_id'] : 0;
        $quantity = isset($_POST['quantity']) ? (int)$_POST['quantity'] : 1;

        // Kiểm tra số lượng hợp lệ
        if ($quantity <= 0) {
            // Xóa sản phẩm khỏi giỏ hàng
            $this->cart->removeItem($product_id);
        } else {
            // Kiểm tra sản phẩm tồn tại
            require_once 'models/Product.php';
            $product = new Product($this->db);

            if ($product->getById($product_id)) {
                // Kiểm tra số lượng tồn kho
                if ($product->stock < $quantity) {
                    $_SESSION['error'] = "Số lượng sản phẩm trong kho không đủ!";
                } else {
                    // Cập nhật số lượng
                    $this->cart->updateItem($product_id, $quantity);
                }
            }
        }

        header("Location: /cart");
        exit;
    }

    // Xóa sản phẩm khỏi giỏ hàng
    public function remove() {
        $product_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
        $this->cart->removeItem($product_id);
        
        header("Location: /cart");
        exit;
    }

    // Xóa toàn bộ giỏ hàng
    public function clear() {
        $this->cart->clear();
        
        header("Location: /cart");
        exit;
    }
}
?>
<?php
class OrderController {
    private $db;

    public function __construct() {
        require_once 'config/Database.php';
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

            // Nếu thanh toán VNPAY, chuyển hướng đến cổng thanh toán
            if ($order->payment_method === 'vnpay') {
                // Lưu thông tin đơn hàng vào session để xử lý sau khi thanh toán
                $_SESSION['pending_order'] = [
                    'user_id' => $order->user_id,
                    'total_amount' => $order->total_amount,
                    'name' => $order->name,
                    'email' => $order->email,
                    'phone' => $order->phone,
                    'address' => $order->address,
                    'payment_method' => $order->payment_method,
                    'cart_items' => $cart_items
                ];
                
                // Chuyển hướng đến trang thanh toán VNPAY
                $this->redirectToVNPay($total, "Thanh toan don hang");
                exit;
            }

            // Tạo đơn hàng (chỉ cho COD)
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

    // Chuyển hướng đến cổng thanh toán VNPAY
    private function redirectToVNPay($amount, $orderInfo) {
        require_once 'config/VNPayConfig.php';
        
        $vnp_TxnRef = time() . "-" . rand(10000, 99999); // Mã đơn hàng
        $vnp_OrderInfo = $orderInfo;
        $vnp_OrderType = VNPayConfig::$vnp_OrderType;
        $vnp_Amount = $amount * 100; // Số tiền * 100 (VNPay yêu cầu)
        $vnp_Locale = VNPayConfig::$vnp_Locale;
        $vnp_BankCode = '';
        $vnp_IpAddr = $_SERVER['REMOTE_ADDR'];
        
        $inputData = array(
            "vnp_Version" => VNPayConfig::$vnp_Version,
            "vnp_TmnCode" => VNPayConfig::$vnp_TmnCode,
            "vnp_Amount" => $vnp_Amount,
            "vnp_Command" => VNPayConfig::$vnp_Command,
            "vnp_CreateDate" => date('YmdHis'),
            "vnp_CurrCode" => VNPayConfig::$vnp_CurrCode,
            "vnp_IpAddr" => $vnp_IpAddr,
            "vnp_Locale" => $vnp_Locale,
            "vnp_OrderInfo" => $vnp_OrderInfo,
            "vnp_OrderType" => $vnp_OrderType,
            "vnp_ReturnUrl" => (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://" . $_SERVER['HTTP_HOST'] . VNPayConfig::$vnp_ReturnUrl,
            "vnp_TxnRef" => $vnp_TxnRef
        );
        
        if (isset($vnp_BankCode) && $vnp_BankCode != "") {
            $inputData['vnp_BankCode'] = $vnp_BankCode;
        }
        
        ksort($inputData);
        $query = "";
        $i = 0;
        $hashdata = "";
        
        foreach ($inputData as $key => $value) {
            if ($i == 1) {
                $hashdata .= '&' . urlencode($key) . "=" . urlencode($value);
            } else {
                $hashdata .= urlencode($key) . "=" . urlencode($value);
                $i = 1;
            }
            $query .= urlencode($key) . "=" . urlencode($value) . '&';
        }
        
        $vnp_Url = VNPayConfig::$vnp_Url . "?" . $query;
        
        if (isset(VNPayConfig::$vnp_HashSecret)) {
            $vnpSecureHash = hash_hmac('sha512', $hashdata, VNPayConfig::$vnp_HashSecret);
            $vnp_Url .= 'vnp_SecureHash=' . $vnpSecureHash;
        }
        
        // Lưu thông tin giao dịch vào session
        $_SESSION['vnp_transaction'] = $vnp_TxnRef;
        
        header('Location: ' . $vnp_Url);
        exit;
    }
    
    // Xử lý kết quả thanh toán từ VNPAY
    public function vnpayReturn() {
        require_once 'config/VNPayConfig.php';
        
        $vnp_SecureHash = isset($_GET['vnp_SecureHash']) ? $_GET['vnp_SecureHash'] : '';
        $inputData = array();
        
        foreach ($_GET as $key => $value) {
            if (substr($key, 0, 4) == "vnp_") {
                $inputData[$key] = $value;
            }
        }
        
        unset($inputData['vnp_SecureHash']);
        ksort($inputData);
        $i = 0;
        $hashData = "";
        
        foreach ($inputData as $key => $value) {
            if ($i == 1) {
                $hashData .= '&' . urlencode($key) . "=" . urlencode($value);
            } else {
                $hashData .= urlencode($key) . "=" . urlencode($value);
                $i = 1;
            }
        }
        
        $secureHash = hash_hmac('sha512', $hashData, VNPayConfig::$vnp_HashSecret);
        $vnp_Amount = isset($_GET['vnp_Amount']) ? $_GET['vnp_Amount'] : 0;
        $vnp_ResponseCode = isset($_GET['vnp_ResponseCode']) ? $_GET['vnp_ResponseCode'] : '';
        $vnp_TransactionStatus = isset($_GET['vnp_TransactionStatus']) ? $_GET['vnp_TransactionStatus'] : '';
        $vnp_TxnRef = isset($_GET['vnp_TxnRef']) ? $_GET['vnp_TxnRef'] : '';
        
        // Kiểm tra chữ ký và mã giao dịch
        if ($secureHash == $vnp_SecureHash && $vnp_TransactionStatus == '00' && $vnp_ResponseCode == '00') {
            // Thanh toán thành công, tạo đơn hàng
            if (isset($_SESSION['pending_order']) && isset($_SESSION['vnp_transaction']) && $_SESSION['vnp_transaction'] == $vnp_TxnRef) {
                require_once 'models/Order.php';
                $order = new Order($this->db);
                
                // Lấy thông tin đơn hàng từ session
                $pendingOrder = $_SESSION['pending_order'];
                $order->user_id = $pendingOrder['user_id'];
                $order->total_amount = $pendingOrder['total_amount'];
                $order->name = $pendingOrder['name'];
                $order->email = $pendingOrder['email'];
                $order->phone = $pendingOrder['phone'];
                $order->address = $pendingOrder['address'];
                $order->payment_method = $pendingOrder['payment_method'];
                
                // Tạo đơn hàng
                if ($order->create()) {
                    require_once 'models/OrderItem.php';
                    $order_item = new OrderItem($this->db);
                    
                    require_once 'models/Product.php';
                    $product = new Product($this->db);
                    
                    // Thêm chi tiết đơn hàng
                    foreach ($pendingOrder['cart_items'] as $item) {
                        $order_item->order_id = $order->id;
                        $order_item->product_id = $item['product_id'];
                        $order_item->quantity = $item['quantity'];
                        $order_item->price = $item['price'];
                        $order_item->create();
                        
                        // Cập nhật số lượng tồn kho
                        $product->getById($item['product_id']);
                        $product->updateStock($item['quantity']);
                    }
                    
                    // Xóa giỏ hàng
                    require_once 'models/Cart.php';
                    $cart = new Cart($this->db);
                    $cart->clear();
                    
                    // Xóa thông tin đơn hàng tạm thời
                    unset($_SESSION['pending_order']);
                    unset($_SESSION['vnp_transaction']);
                    
                    $_SESSION['success'] = "Thanh toán thành công! Đơn hàng của bạn đã được tạo.";
                    header("Location: /orders");
                    exit;
                }
            }
        } else {
            // Thanh toán thất bại
            $_SESSION['error'] = "Thanh toán thất bại hoặc đã bị hủy.";
            header("Location: /checkout");
            exit;
        }
        
        // Xử lý lỗi không xác định
        $_SESSION['error'] = "Đã xảy ra lỗi trong quá trình xử lý thanh toán.";
        header("Location: /checkout");
        exit;
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

    public function cancelOrder() {
        // Kiểm tra đăng nhập
        if (!isset($_SESSION['user'])) {
            header("Location: /login");
            exit;
        }

        $order_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

        require_once 'models/Order.php';
        $order = new Order($this->db);

        // Kiểm tra đơn hàng tồn tại và thuộc về user hiện tại
        if ($order->getById($order_id) && $order->user_id === $_SESSION['user']['id']) {
            // Chỉ cho phép hủy đơn hàng ở trạng thái "pending"
            if ($order->status === 'pending') {
                $order->status = 'cancelled';
                if ($order->updateStatus()) {
                    $_SESSION['success'] = "Đơn hàng đã được hủy thành công!";
                } else {
                    $_SESSION['error'] = "Đã xảy ra lỗi khi hủy đơn hàng!";
                }
            } else {
                $_SESSION['error'] = "Không thể hủy đơn hàng ở trạng thái hiện tại!";
            }
        } else {
            $_SESSION['error'] = "Không tìm thấy đơn hàng!";
        }

        // Chuyển về trang chi tiết đơn hàng
        header("Location: /orders/view?id=$order_id");
        exit;
    }
}
?>
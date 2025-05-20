<?php
class UserController {
    private $db;

    public function __construct() {
        require_once 'config/Database.php';
        $database = new Database();
        $this->db = $database->getConnection();
    }

    // Trang đăng ký
    public function register() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            require_once 'models/User.php';
            $user = new User($this->db);

            // Kiểm tra xem email đã tồn tại chưa
            $email = $_POST['email'];
            if ($user->getByEmail($email)) {
                $error = "Email đã tồn tại!";
                require_once 'views/auth/register.php';
                return;
            }

            // Kiểm tra mật khẩu xác nhận
            if ($_POST['password'] !== $_POST['confirm_password']) {
                $error = "Mật khẩu xác nhận không khớp!";
                require_once 'views/auth/register.php';
                return;
            }

            // Tạo user mới
            $user->name = $_POST['name'];
            $user->email = $email;
            $user->password = $_POST['password'];

            if ($user->create()) {
                $_SESSION['success'] = "Đăng ký thành công! Vui lòng đăng nhập.";
                header("Location: /login");
                exit;
            } else {
                $error = "Đã xảy ra lỗi khi đăng ký!";
            }
        }

        require_once 'views/auth/register.php';
    }

    // Trang đăng nhập
    public function login() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            require_once 'models/User.php';
            $user = new User($this->db);

            $email = $_POST['email'];
            $password = $_POST['password'];

            // Kiểm tra email
            if ($user->getByEmail($email)) {
                // Kiểm tra mật khẩu
                if (password_verify($password, $user->password)) {
                    // Đăng nhập thành công, lưu thông tin vào session
                    $_SESSION['user'] = [
                        'id' => $user->id,
                        'name' => $user->name,
                        'email' => $user->email,
                        'role' => $user->role
                    ];

                    // Chuyển hướng dựa trên vai trò
                    if ($user->role === 'admin') {
                        header("Location: /admin");
                    } else {
                        header("Location: /");
                    }
                    exit;
                } else {
                    $error = "Mật khẩu không đúng!";
                }
            } else {
                $error = "Email không tồn tại!";
            }
        }

        require_once 'views/auth/login.php';
    }

    // Đăng xuất
    public function logout() {
        // Xóa thông tin người dùng khỏi session
        unset($_SESSION['user']);
        // Chuyển hướng về trang chủ
        header("Location: /");
        exit;
    }

    // Trang thông tin tài khoản
    public function profile() {
        // Kiểm tra đăng nhập
        if (!isset($_SESSION['user'])) {
            header("Location: /login");
            exit;
        }

        require_once 'models/User.php';
        $user = new User($this->db);
        $user->getById($_SESSION['user']['id']);

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Cập nhật thông tin
            $user->name = $_POST['name'];

            // Nếu muốn đổi mật khẩu
            if (!empty($_POST['password'])) {
                // Kiểm tra mật khẩu xác nhận
                if ($_POST['password'] !== $_POST['confirm_password']) {
                    $error = "Mật khẩu xác nhận không khớp!";
                    require_once 'views/profile.php';
                    return;
                }

                $user->password = $_POST['password'];
                if ($user->updatePassword()) {
                    $success = "Cập nhật mật khẩu thành công!";
                } else {
                    $error = "Đã xảy ra lỗi khi cập nhật mật khẩu!";
                }
            }

            if ($user->update()) {
                // Cập nhật session
                $_SESSION['user']['name'] = $user->name;
                $success = "Cập nhật thông tin thành công!";
            } else {
                $error = "Đã xảy ra lỗi khi cập nhật thông tin!";
            }
        }

        require_once 'views/profile.php';
    }
}
?>

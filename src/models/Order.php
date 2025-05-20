<?php
class Order {
    private $conn;
    private $table_name = "orders";

    public $id;
    public $user_id;
    public $total_amount;
    public $status;
    public $name;
    public $email;
    public $phone;
    public $address;
    public $payment_method;
    public $created_at;
    public $user_name;
    public $user_email;

    public function __construct($db) {
        $this->conn = $db;
    }

    // Lấy tất cả đơn hàng
    public function getAll() {
        $query = "SELECT o.*, u.name as user_name, u.email as user_email 
                FROM " . $this->table_name . " o
                LEFT JOIN users u ON o.user_id = u.id
                ORDER BY o.created_at DESC";

        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    // Lấy đơn hàng của người dùng
    public function getByUser($user_id) {
        $query = "SELECT o.*, u.name as user_name, u.email as user_email 
                FROM " . $this->table_name . " o
                LEFT JOIN users u ON o.user_id = u.id
                WHERE o.user_id = ?
                ORDER BY o.created_at DESC";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $user_id);
        $stmt->execute();
        return $stmt;
    }

    // Lấy đơn hàng theo ID
    public function getById($id) {
        $query = "SELECT o.*, u.name as user_name, u.email as user_email 
                FROM " . $this->table_name . " o
                LEFT JOIN users u ON o.user_id = u.id
                WHERE o.id = ?";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $id);
        $stmt->execute();

        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if($row) {
            $this->id = $row['id'];
            $this->user_id = $row['user_id'];
            $this->total_amount = $row['total_amount'];
            $this->status = $row['status'];
            $this->name = $row['name'];
            $this->email = $row['email'];
            $this->phone = $row['phone'];
            $this->address = $row['address'];
            $this->payment_method = $row['payment_method'];
            $this->created_at = $row['created_at'];
            $this->user_name = $row['user_name'];
            $this->user_email = $row['user_email'];
            return true;
        }
        return false;
    }

    // Tạo đơn hàng mới
    public function create() {
        $query = "INSERT INTO " . $this->table_name . " 
                (user_id, total_amount, name, email, phone, address, payment_method) 
                VALUES (?, ?, ?, ?, ?, ?, ?)";

        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(1, $this->user_id);
        $stmt->bindParam(2, $this->total_amount);
        $stmt->bindParam(3, $this->name);
        $stmt->bindParam(4, $this->email);
        $stmt->bindParam(5, $this->phone);
        $stmt->bindParam(6, $this->address);
        $stmt->bindParam(7, $this->payment_method);

        if($stmt->execute()) {
            // Lấy ID đơn hàng vừa tạo
            $this->id = $this->conn->lastInsertId();
            return true;
        }
        return false;
    }

    // Cập nhật trạng thái đơn hàng
    public function updateStatus() {
        $query = "UPDATE " . $this->table_name . " SET status = ? WHERE id = ?";
        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(1, $this->status);
        $stmt->bindParam(2, $this->id);

        if($stmt->execute()) {
            return true;
        }
        return false;
    }

    // Xóa đơn hàng
    public function delete() {
        // Xóa các order items trước
        $query = "DELETE FROM order_items WHERE order_id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $this->id);
        $stmt->execute();

        // Sau đó xóa order
        $query = "DELETE FROM " . $this->table_name . " WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $this->id);

        return $stmt->execute();
    }
}
?>
<?php
class OrderItem {
    private $conn;
    private $table_name = "order_items";

    public $id;
    public $order_id;
    public $product_id;
    public $quantity;
    public $price;
    public $product_name;
    public $product_image;

    public function __construct($db) {
        $this->conn = $db;
    }

    // Lấy chi tiết đơn hàng
    public function getByOrderId($order_id) {
        $query = "SELECT oi.*, p.name as product_name, p.image as product_image 
                FROM " . $this->table_name . " oi
                LEFT JOIN products p ON oi.product_id = p.id
                WHERE oi.order_id = ?";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $order_id);
        $stmt->execute();
        return $stmt;
    }

    // Tạo chi tiết đơn hàng
    public function create() {
        $query = "INSERT INTO " . $this->table_name . " 
                (order_id, product_id, quantity, price) 
                VALUES (?, ?, ?, ?)";

        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(1, $this->order_id);
        $stmt->bindParam(2, $this->product_id);
        $stmt->bindParam(3, $this->quantity);
        $stmt->bindParam(4, $this->price);

        if($stmt->execute()) {
            return true;
        }
        return false;
    }
}
?>
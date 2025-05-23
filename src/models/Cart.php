<?php
class Cart {
    private $conn;
    
    // Thuộc tính của giỏ hàng
    public $id;
    public $user_id;
    public $created_at;
    public $updated_at;
    
    public function __construct($db) {
        $this->conn = $db;
    }
    
    // Lấy hoặc tạo giỏ hàng cho user
    public function getOrCreateCart($user_id) {
        $query = "SELECT id FROM carts WHERE user_id = ? LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([$user_id]);
        
        if ($stmt->rowCount() > 0) {
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            $this->id = $row['id'];
        } else {
            // Tạo giỏ hàng mới
            $query = "INSERT INTO carts (user_id) VALUES (?) RETURNING id";
            $stmt = $this->conn->prepare($query);
            $stmt->execute([$user_id]);
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            $this->id = $row['id'];
        }
        
        $this->user_id = $user_id;
        return $this->id;
    }
    
    // Lấy tất cả sản phẩm trong giỏ hàng
    public function getItems() {
        $query = "SELECT ci.id, ci.product_id, ci.quantity, p.name, p.price, p.discount, 
             p.image, p.stock,
             ROUND(p.price * (1 - p.discount::float/100)) as discounted_price
             FROM cart_items ci
             JOIN products p ON ci.product_id = p.id
             WHERE ci.cart_id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([$this->id]);

        $items = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $row['subtotal'] = $row['discounted_price'] * $row['quantity'];
            $items[] = $row;
        }

        return $items;
    }
    
    // Thêm sản phẩm vào giỏ hàng
    public function addItem($product_id, $quantity) {
        // Kiểm tra sản phẩm đã có trong giỏ hàng chưa
        $query = "SELECT quantity FROM cart_items WHERE cart_id = ? AND product_id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([$this->id, $product_id]);
        
        if ($stmt->rowCount() > 0) {
            // Cập nhật số lượng
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            $new_quantity = $row['quantity'] + $quantity;
            
            $query = "UPDATE cart_items SET quantity = ?, updated_at = CURRENT_TIMESTAMP 
                     WHERE cart_id = ? AND product_id = ?";
            $stmt = $this->conn->prepare($query);
            return $stmt->execute([$new_quantity, $this->id, $product_id]);
        } else {
            // Thêm mới
            $query = "INSERT INTO cart_items (cart_id, product_id, quantity) VALUES (?, ?, ?)";
            $stmt = $this->conn->prepare($query);
            return $stmt->execute([$this->id, $product_id, $quantity]);
        }
    }
    
    // Cập nhật số lượng sản phẩm
    public function updateItem($product_id, $quantity) {
        $query = "UPDATE cart_items SET quantity = ?, updated_at = CURRENT_TIMESTAMP 
                 WHERE cart_id = ? AND product_id = ?";
        $stmt = $this->conn->prepare($query);
        return $stmt->execute([$quantity, $this->id, $product_id]);
    }
    
    // Xóa sản phẩm khỏi giỏ hàng
    public function removeItem($product_id) {
        $query = "DELETE FROM cart_items WHERE cart_id = ? AND product_id = ?";
        $stmt = $this->conn->prepare($query);
        return $stmt->execute([$this->id, $product_id]);
    }
    
    // Xóa toàn bộ giỏ hàng
    public function clear() {
        $query = "DELETE FROM cart_items WHERE cart_id = ?";
        $stmt = $this->conn->prepare($query);
        return $stmt->execute([$this->id]);
    }
    
    // Tính tổng giá trị giỏ hàng
    public function getTotal() {
        $query = "SELECT SUM(ci.quantity * ROUND(p.price * (1 - p.discount::float/100))) as total 
             FROM cart_items ci
             JOIN products p ON ci.product_id = p.id
             WHERE ci.cart_id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([$this->id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        return $row['total'] ? $row['total'] : 0;
    }
    
    // Đếm số lượng sản phẩm trong giỏ hàng
    public function countItems() {
        $query = "SELECT COUNT(*) as count FROM cart_items WHERE cart_id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([$this->id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        
        return $row['count'];
    }
}
?>
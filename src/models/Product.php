<?php
class Product {
    private $conn;
    private $table_name = "products";

    public $id;
    public $category_id;
    public $name;
    public $description;
    public $price;
    public $stock;
    public $image;
    public $created_at;
    public $category_name;

    public function __construct($db) {
        $this->conn = $db;
    }

    // Lấy tất cả sản phẩm
    public function getAll() {
        $query = "SELECT p.*, c.name as category_name 
                FROM " . $this->table_name . " p
                LEFT JOIN categories c ON p.category_id = c.id
                ORDER BY p.id DESC";

        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    // Lấy sản phẩm theo ID
    public function getById($id) {
        $query = "SELECT p.*, c.name as category_name 
                FROM " . $this->table_name . " p
                LEFT JOIN categories c ON p.category_id = c.id
                WHERE p.id = ?";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $id);
        $stmt->execute();

        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if($row) {
            $this->id = $row['id'];
            $this->category_id = $row['category_id'];
            $this->name = $row['name'];
            $this->description = $row['description'];
            $this->price = $row['price'];
            $this->stock = $row['stock'];
            $this->image = $row['image'];
            $this->created_at = $row['created_at'];
            $this->category_name = $row['category_name'];
            return true;
        }
        return false;
    }

    // Lấy sản phẩm theo danh mục
    public function getByCategory($category_id) {
        $query = "SELECT p.*, c.name as category_name 
                FROM " . $this->table_name . " p
                LEFT JOIN categories c ON p.category_id = c.id
                WHERE p.category_id = ?
                ORDER BY p.id DESC";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $category_id);
        $stmt->execute();
        return $stmt;
    }

    // Tìm kiếm sản phẩm
    public function search($keyword) {
        $query = "SELECT p.*, c.name as category_name 
                FROM " . $this->table_name . " p
                LEFT JOIN categories c ON p.category_id = c.id
                WHERE p.name ILIKE ? OR p.description ILIKE ?
                ORDER BY p.id DESC";

        $keyword = "%" . $keyword . "%";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $keyword);
        $stmt->bindParam(2, $keyword);
        $stmt->execute();
        return $stmt;
    }

    // Tạo sản phẩm mới
    public function create() {
        $query = "INSERT INTO " . $this->table_name . " 
                (category_id, name, description, price, stock, image) 
                VALUES (?, ?, ?, ?, ?, ?)";

        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(1, $this->category_id);
        $stmt->bindParam(2, $this->name);
        $stmt->bindParam(3, $this->description);
        $stmt->bindParam(4, $this->price);
        $stmt->bindParam(5, $this->stock);
        $stmt->bindParam(6, $this->image);

        if($stmt->execute()) {
            return true;
        }
        return false;
    }

    // Cập nhật sản phẩm
    public function update() {
        $query = "UPDATE " . $this->table_name . " 
                SET category_id = ?, name = ?, description = ?, price = ?, stock = ?";

        // Nếu có cập nhật hình ảnh
        if(!empty($this->image)) {
            $query .= ", image = ?";
        }

        $query .= " WHERE id = ?";

        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(1, $this->category_id);
        $stmt->bindParam(2, $this->name);
        $stmt->bindParam(3, $this->description);
        $stmt->bindParam(4, $this->price);
        $stmt->bindParam(5, $this->stock);

        // Nếu có cập nhật hình ảnh
        if(!empty($this->image)) {
            $stmt->bindParam(6, $this->image);
            $stmt->bindParam(7, $this->id);
        } else {
            $stmt->bindParam(6, $this->id);
        }

        if($stmt->execute()) {
            return true;
        }
        return false;
    }

    // Xoá sản phẩm
    public function delete() {
        $query = "DELETE FROM " . $this->table_name . " WHERE id = ?";
        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(1, $this->id);

        if($stmt->execute()) {
            return true;
        }
        return false;
    }

    // Cập nhật số lượng tồn kho
    public function updateStock($quantity) {
        $query = "UPDATE " . $this->table_name . " SET stock = stock - ? WHERE id = ?";
        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(1, $quantity);
        $stmt->bindParam(2, $this->id);

        if($stmt->execute()) {
            return true;
        }
        return false;
    }
}
?>
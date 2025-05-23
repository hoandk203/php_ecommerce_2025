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
    public $discount;
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
            $this->discount = $row['discount'];
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
                (category_id, name, description, price, stock,discount, image) 
                VALUES (?, ?, ?, ?, ?, ?, ?)";

        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(1, $this->category_id);
        $stmt->bindParam(2, $this->name);
        $stmt->bindParam(3, $this->description);
        $stmt->bindParam(4, $this->price);
        $stmt->bindParam(5, $this->stock);
        $stmt->bindParam(6, $this->discount);
        $stmt->bindParam(7, $this->image);


        if($stmt->execute()) {
            return true;
        }
        return false;
    }

    // Cập nhật sản phẩm
    public function update() {
        $query = "UPDATE " . $this->table_name . " 
                SET category_id = ?, name = ?, description = ?, price = ?, stock = ?, discount = ?";

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
        $stmt->bindParam(6, $this->discount);

        // Nếu có cập nhật hình ảnh
        if(!empty($this->image)) {
            $stmt->bindParam(7, $this->image);
            $stmt->bindParam(8, $this->id);
        } else {
            $stmt->bindParam(7, $this->id);
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

    public function getFiltered($category_id = null, $min_price = null, $max_price = null, $search = null, $sort = null) {
        $query = "SELECT p.*, c.name as category_name 
              FROM " . $this->table_name . " p
              LEFT JOIN categories c ON p.category_id = c.id
              WHERE 1=1";

        $params = array();

        // Lọc theo danh mục
        if ($category_id) {
            $query .= " AND p.category_id = ?";
            $params[] = $category_id;
        }

        // Lọc theo giá tối thiểu
        if ($min_price !== null) {
            $query .= " AND p.price >= ?";
            $params[] = $min_price;
        }

        // Lọc theo giá tối đa
        if ($max_price !== null) {
            $query .= " AND p.price <= ?";
            $params[] = $max_price;
        }

        // Tìm kiếm theo từ khóa
        if ($search) {
            $query .= " AND (p.name ILIKE ? OR p.description ILIKE ?)";
            $search = "%$search%";
            $params[] = $search;
            $params[] = $search;
        }

        // Sắp xếp theo giá
        if ($sort === 'asc') {
            $query .= " ORDER BY p.price ASC, p.id DESC";
        } elseif ($sort === 'desc') {
            $query .= " ORDER BY p.price DESC, p.id DESC";
        } else {
            $query .= " ORDER BY p.id DESC";
        }

        $stmt = $this->conn->prepare($query);

        // Bind các tham số
        foreach ($params as $key => $value) {
            $stmt->bindValue($key + 1, $value);
        }

        $stmt->execute();
        return $stmt;
    }
}
?>
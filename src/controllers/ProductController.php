<?php
class ProductController {
    private $db;

    public function __construct() {
        require_once 'config/database.php';
        $database = new Database();
        $this->db = $database->getConnection();
    }

    // Danh sách sản phẩm
    public function index() {
        // Xử lý tìm kiếm
        $search = isset($_GET['search']) ? $_GET['search'] : '';
        $category_id = isset($_GET['category']) ? $_GET['category'] : '';

        require_once 'models/Product.php';
        $product = new Product($this->db);

        // Lấy danh sách sản phẩm
        if (!empty($search)) {
            $products = $product->search($search);
        } elseif (!empty($category_id)) {
            $products = $product->getByCategory($category_id);
        } else {
            $products = $product->getAll();
        }

        // Lấy danh mục
        require_once 'models/Category.php';
        $category = new Category($this->db);
        $categories = $category->getAll();

        require_once 'views/products/index.php';
    }

    // Chi tiết sản phẩm
    public function detail($id) {
        require_once 'models/Product.php';
        $product = new Product($this->db);

        // Kiểm tra sản phẩm tồn tại
        if (!$product->getById($id)) {
            header("Location: /products");
            exit;
        }

        // Lấy sản phẩm liên quan (cùng danh mục)
        $related_products = $product->getByCategory($product->category_id);

        require_once 'views/products/detail.php';
    }

}
?>
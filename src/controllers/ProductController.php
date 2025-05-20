<?php
class ProductController {
    private $db;

    public function __construct() {
        require_once 'config/Database.php';
        $database = new Database();
        $this->db = $database->getConnection();
    }

    // Danh sách sản phẩm
    public function index() {
        $search = isset($_GET['search']) ? $_GET['search'] : '';
        $category_id = isset($_GET['category']) ? $_GET['category'] : null;
        $min_price = isset($_GET['min_price']) && $_GET['min_price'] !== '' ? (float)$_GET['min_price'] : null;
        $max_price = isset($_GET['max_price']) && $_GET['max_price'] !== '' ? (float)$_GET['max_price'] : null;
        $sort = isset($_GET['sort']) ? $_GET['sort'] : null;

        require_once 'models/Product.php';
        $product = new Product($this->db);

        // Lấy danh sách sản phẩm đã lọc
        $products = $product->getFiltered($category_id, $min_price, $max_price, $search, $sort);

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
<?php
class HomeController {
    private $db;

    public function __construct() {
        require_once 'config/Database.php';
        $database = new Database();
        $this->db = $database->getConnection();
    }

    public function index() {
        // Lấy danh mục sản phẩm
        require_once 'models/Category.php';
        $category = new Category($this->db);
        $categories = $category->getAll();

        // Lấy sản phẩm nổi bật
        require_once 'models/Product.php';
        $product = new Product($this->db);
        $featured_products = $product->getAll();


        require_once 'views/home.php';
    }
}
?>
<?php include_once "views/layouts/header.php"; ?>
<style>
    #sortAsc:checked + label{
        border-color: #fb5858 !important;
        color: #fb5858;
    }
    #sortDesc:checked + label{
        border-color: #fb5858 !important;
        color: #fb5858;
    }

    .active{
        background-color: #303030 !important;
        color: #fff !important;
    }

    .pagination .page-item.active .page-link {
        background-color: #fb5858;
        border-color: #fb5858;
        color: #fff;
    }
    
    .pagination .page-link {
        color: #fb5858;
        
    }
    
    .pagination .page-link:hover {
        color: #fff;
        background-color: #fb5858;
        border-color: #fb5858;
    }
</style>
    <div class="row">
        <div class="col-md-3">
            <div class="card mb-4">
                <div class="card-header">
                    <h5>Danh mục</h5>
                </div>
                <div class="card-body">
                    <div class="list-group">
                        <a href="/products" class="list-group-item item-active list-group-item-action <?php echo empty($_GET['category']) ? 'active' : ''; ?>">
                            Tất cả sản phẩm
                        </a>
                        <?php while ($category = $categories->fetch(PDO::FETCH_ASSOC)): ?>
                            <a href="/products?category=<?php echo $category['id']; ?>" class="list-group-item list-group-item-action <?php echo (isset($_GET['category']) && $_GET['category'] == $category['id']) ? 'active' : ''; ?>">
                                <?php echo $category['name']; ?>
                            </a>
                        <?php endwhile; ?>
                    </div>
                </div>
            </div>
            <div class="card mb-4">
                <div class="card-header">
                    <h5>Lọc sản phẩm</h5>
                </div>
                <div class="card-body">
                    <form action="/products" method="GET" id="filterForm">
                        <!-- Giữ lại category ID nếu đang lọc theo danh mục -->
                        <?php if (isset($_GET['category'])): ?>
                            <input type="hidden" name="category" value="<?php echo htmlspecialchars($_GET['category']); ?>">
                        <?php endif; ?>

                        <!-- Thêm lựa chọn sắp xếp -->
                        <div class="mb-3">
                            <label class="form-label">Sắp xếp theo giá</label>
                            <div class="mb-2">
                                        <div class="form-check mb-2 p-0">
                                            <input hidden="hidden" class="form-check-input" type="radio" name="sort" id="sortAsc" value="asc" <?php echo (isset($_GET['sort']) && $_GET['sort'] == 'asc') ? 'checked' : ''; ?>>
                                            <label class="form-check-label" for="sortAsc"  style="border: 1px solid #ccc; padding: 4px 10px; border-radius: 99px;">
                                                Giá thấp đến cao <i class="fa-solid fa-up-long"></i>
                                            </label>
                                        </div>
                                        <div class="form-check p-0">
                                            <input hidden="hidden" class="form-check-input" type="radio" name="sort" id="sortDesc" value="desc" <?php echo (isset($_GET['sort']) && $_GET['sort'] == 'desc') ? 'checked' : ''; ?>>
                                            <label class="form-check-label" for="sortDesc" style="border: 1px solid #ccc; padding: 4px 10px; border-radius: 99px;">
                                                Giá cao đến thấp <i class="fa-solid fa-down-long"></i>
                                            </label>
                                        </div>
                                    </div>
                        </div>
                        <div class="mb-3">
                                           <label class="form-label">Khoảng giá: <span id="priceRange">0đ - 200,000,000đ</span></label>
                                           <div class="range_container">
                                               <div class="sliders_control">
                                                   <input type="range" id="priceSlider" class="form-range"
                                                          style="background-color: #ccc; padding: 4px;padding-left: 8px;padding-right: 8px; border-radius: 999px"
                                                          min="0" max="200000000" step="500000"
                                                          value="<?php echo isset($_GET['max_price']) ? htmlspecialchars($_GET['max_price']) : '200000000'; ?>">
                                               </div>
                                               <input type="hidden" name="min_price" id="minPrice"
                                                      value="<?php echo isset($_GET['min_price']) ? htmlspecialchars($_GET['min_price']) : '0'; ?>">
                                               <input type="hidden" name="max_price" id="maxPrice"
                                                      value="<?php echo isset($_GET['max_price']) ? htmlspecialchars($_GET['max_price']) : '200000000'; ?>">
                                           </div>
                                       </div>
                                       <script>
                                       const priceSlider = document.getElementById('priceSlider');
                                       const minPrice = document.getElementById('minPrice');
                                       const maxPrice = document.getElementById('maxPrice');
                                       const priceRange = document.getElementById('priceRange');

                                       function formatPrice(price) {
                                           return new Intl.NumberFormat('vi-VN').format(price) + 'đ';
                                       }

                                       priceSlider.addEventListener('input', function() {
                                           maxPrice.value = this.value;
                                           minPrice.value = 0;
                                           priceRange.textContent = `${formatPrice(0)} - ${formatPrice(this.value)}`;
                                       });
                                       </script>
                        <button type="submit" class="btn btn-dark w-100">Lọc</button>
                        <?php if (isset($_GET['min_price']) || isset($_GET['max_price'])): ?>
                            <a href="<?php echo '/products' . (isset($_GET['category']) ? '?category=' . htmlspecialchars($_GET['category']) : ''); ?>"
                               class="btn btn-outline-secondary w-100 mt-2">Xóa bộ lọc</a>
                        <?php endif; ?>
                    </form>
                </div>
            </div>

        </div>

        <div class="col-md-9">
            <div class="card mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5>Danh sách sản phẩm</h5>
                    <?php if (!empty($_GET['search'])): ?>
                        <span>Kết quả tìm kiếm cho: "<?php echo htmlspecialchars($_GET['search']); ?>"</span>
                    <?php elseif (!empty($_GET['category'])): ?>
                        <?php
                        $current_category = "";
                        foreach ($categories as $cat) {
                            if ($cat['id'] == $_GET['category']) {
                                $current_category = $cat['name'];
                                break;
                            }
                        }
                        ?>
                        <span>Danh mục: <?php echo $current_category; ?></span>
                    <?php endif; ?>
                </div>
                <div class="card-body">
                    <?php if ($products->rowCount() > 0): ?>
                        <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
                            <?php while ($product = $products->fetch(PDO::FETCH_ASSOC)): ?>
                                <div class="col">
                                    <div class="card h-100 product-card shadow-sm">
                                        <img src="<?php echo !empty($product['image']) ? $product['image'] : 'https://via.placeholder.com/300x200?text=No+Image'; ?>" class="card-img-top" alt="<?php echo $product['name']; ?>">
                                        <div class="card-body">
                                            <h5 class="card-title" style="display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;"><?php echo $product['name']; ?></h5>
                                            <p class="card-text text-truncate"><?php echo $product['description']; ?></p>
                                            <p class="card-text text-danger fw-bold"><?php echo number_format($product['price'], 0, ',', '.'); ?> VND</p>
                                            <div class="d-flex justify-content-between">
                                                <a href="/products/detail?id=<?php echo $product['id']; ?>" class="btn text-white" style="background-color: #fb5858">Chi tiết</a>
                                                <form method="POST" action="/cart/add">
                                                    <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">
                                                    <input type="hidden" name="quantity" value="1">
                                                    <button type="submit" class="btn btn-outline-success">
                                                        <i class="fas fa-cart-plus"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php endwhile; ?>
                        </div>
                        
                        <!-- Phân trang -->
                        <?php if ($total_pages > 1): ?>
                        <div class="d-flex justify-content-center mt-4">
                            <nav aria-label="Page navigation">
                                <ul class="pagination">
                                    <?php if ($current_page > 1): ?>
                                        <li class="page-item">
                                            <a class="page-link" href="?<?php 
                                                $params = $_GET;
                                                $params['page'] = $current_page - 1;
                                                echo http_build_query($params);
                                            ?>" aria-label="Previous">
                                                <span aria-hidden="true">&laquo;</span>
                                            </a>
                                        </li>
                                    <?php endif; ?>

                                    <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                                        <li class="page-item <?php echo $i == $current_page ? 'active' : ''; ?>">
                                            <a class="page-link" href="?<?php 
                                                $params = $_GET;
                                                $params['page'] = $i;
                                                echo http_build_query($params);
                                            ?>"><?php echo $i; ?></a>
                                        </li>
                                    <?php endfor; ?>

                                    <?php if ($current_page < $total_pages): ?>
                                        <li class="page-item">
                                            <a class="page-link" href="?<?php 
                                                $params = $_GET;
                                                $params['page'] = $current_page + 1;
                                                echo http_build_query($params);
                                            ?>" aria-label="Next">
                                                <span aria-hidden="true">&raquo;</span>
                                            </a>
                                        </li>
                                    <?php endif; ?>
                                </ul>
                            </nav>
                        </div>
                        <?php endif; ?>
                    <?php else: ?>
                        <div class="alert alert-info">
                            Không tìm thấy sản phẩm nào.
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

<?php include_once "views/layouts/footer.php"; ?>
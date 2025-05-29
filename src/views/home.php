<?php include_once "layouts/header.php"; ?>
    <div class="row">
        <div class="col-md-12">
            <div class="">
                <div class="d-flex">
                    <div class="home-sidebar col-2 pe-3 pb-3 d-none d-lg-block">
                        <div class="home-sidebar-item mb-3 pt-3 pe-3" style="height: 100%; border-radius: 8px; box-shadow: 0 0 10px 0 rgba(0, 0, 0, 0.3);">
                            <ul class="list-group" style="list-style: none;">
                                <?php
                                $categoriesList = $categories->fetchAll(PDO::FETCH_ASSOC);
                                foreach ($categoriesList as $category): ?>
                                    <li class="d-flex justify-content-between mb-2"><a class="list-group-item list-group-item-action" style="border:none; background-color: transparent" href="/products?category=<?php echo $category['id']; ?>">
                                            <?php if($category['name']=="Điện thoại") echo "<i class='fas fa-mobile-alt'></i> ";
                                            elseif($category['name']=="Laptop") echo "<i class='fa-solid fa-laptop'></i> ";
                                            elseif($category['name']=="Máy tính bảng") echo "<i class='fa-solid fa-tablet-screen-button'></i> ";
                                            elseif($category['name']=="Phụ kiện") echo "<i class='fa-solid fa-diagram-successor'></i> ";
                                            elseif($category['name']=="Tivi") echo "<i class='fa-solid fa-tv'></i> ";
                                            elseif($category['name']=="Máy tính bàn") echo "<i class='fa-solid fa-desktop'></i> ";?>

                                            <?php echo $category['name']; ?></a><i class="fa-solid fa-chevron-right d-flex align-items-center"></i></li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    </div>
                    <div id="carouselHome" class="carousel slide mb-3 col-12 col-lg-10" style="height: 400px;" data-bs-ride="carousel">
                        <div class="carousel-indicators">
                            <button type="button" data-bs-target="#carouselHome" data-bs-slide-to="0" class="active"></button>
                            <button type="button" data-bs-target="#carouselHome" data-bs-slide-to="1"></button>
                            <button type="button" data-bs-target="#carouselHome" data-bs-slide-to="2"></button>
                        </div>
                        <div class="carousel-inner shadow" style="border-radius: 8px;">
                            <div class="carousel-item active">
                                <img style="height: 400px;" src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcTXpzjT8QkpSA9EOKEuQDcHR0q30aGofH-s2g&s" class="d-block w-100" alt="Khuyến mãi điện thoại">
                                <div class="carousel-caption d-none d-md-block">
                                    <h2>Khuyến mãi điện thoại</h2>
                                    <p>Giảm giá đến 20% cho tất cả các dòng điện thoại mới nhất</p>
                                </div>
                            </div>
                            <div class="carousel-item">
                                <img style="height: 400px;" src="https://img.pikbest.com/backgrounds/20210618/creative-technology-smart-style-mobile-promotion-banner-template_6021593.jpg!w700wp" class="d-block w-100" alt="Laptop giá tốt">
                                <div class="carousel-caption d-none d-md-block">
                                    <h2>Laptop giá tốt</h2>
                                    <p>Nhiều ưu đãi hấp dẫn khi mua laptop trong tháng này</p>
                                </div>
                            </div>
                            <div class="carousel-item">
                                <img style="height: 400px;" src="https://img.pikbest.com/backgrounds/20210618/creative-technology-smart-style-mobile-promotion-banner-template_6021593.jpg!w700wp" class="d-block w-100" alt="Phụ kiện chính hãng">
                                <div class="carousel-caption d-none d-md-block">
                                    <h2>Phụ kiện chính hãng</h2>
                                    <p>Mua 1 tặng 1 với nhiều phụ kiện công nghệ</p>
                                </div>
                            </div>
                        </div>
                        <button class="carousel-control-prev" type="button" data-bs-target="#carouselHome" data-bs-slide="prev">
                            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                            <span class="visually-hidden">Previous</span>
                        </button>
                        <button class="carousel-control-next" type="button" data-bs-target="#carouselHome" data-bs-slide="next">
                            <span class="carousel-control-next-icon" aria-hidden="true"></span>
                            <span class="visually-hidden">Next</span>
                        </button>
                    </div>
                </div>
            </div>
            <div class="mb-4 rounded shadow">
                <img style="width: 100%; height: 120px; border-radius: 8px;" src="https://cdn2.cellphones.com.vn/insecure/rs:fill:800:150/q:90/plain/https://dashboard.cellphones.com.vn/storage/ngay-hoi-dinh-gia-special-banner-mobile.gif" alt="">
            </div>
        </div>
    </div>

    <div class="row" style="margin-bottom: 80px;">
        <div class="col-md-12">
            <div class="row row-cols-1 row-cols-md-2 row-cols-lg-4 g-4">
                <?php

                foreach ($categoriesList as $category): ?>
                    <?php if($category['id'] <=4){ ?>
                        <div class="col">
                            <div class="card h-100 text-center shadow-sm" style=" border-radius: 8px;">
                                <div class="card-body" style="background-color: #fb5858;  border-radius: 8px;">
                                    <h5 class="card-title" style="color: white; font-weight: bold; font-size: 28px"><?php echo $category['name']; ?></h5>
                                    <p class="card-text" style="color:white"><?php echo $category['description']; ?></p>
                                    <a href="/products?category=<?php echo $category['id']; ?>" class="btn bg-white">Xem sản phẩm</a>
                                </div>
                            </div>
                        </div>
                    <?php } ?>
                <?php endforeach; ?>
            </div>
        </div>
    </div>

    <div class="row" style="margin-bottom: 80px;">
        <div class="col-md-12">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h2>Sản phẩm gần đây</h2>
                <a href="/products" class="btn btn-outline-primary">Xem tất cả</a>
            </div>
            <div class="row row-cols-1 row-cols-md-2 row-cols-lg-5 g-4">
                <?php
                $count = 0;
                while ($product = $featured_products->fetch(PDO::FETCH_ASSOC)):
                    $count++;
                    if ($count > 10) break;
                    ?>
                    <div class="col">
                        <div class="card h-100 product-card shadow-sm" style="border-radius: 16px;">
                            <img style="border-top-left-radius: 16px; border-top-right-radius: 16px;" src="<?php echo !empty($product['image']) ? $product['image'] : 'https://via.placeholder.com/300x200?text=No+Image'; ?>" class="card-img-top" alt="<?php echo $product['name']; ?>">
                            <div class="card-body position-relative shadow" style="padding-bottom: 54px; border-radius: 16px;">
                                <?php if (!empty($product['discount']) && $product['discount'] > 0): ?>
                                    <div class="position-absolute start-0 bg-danger text-white py-1 px-2" style="top: -150px;border-radius: 0 0 8px 0; transform: translateY(-100%);">
                                        -<?php echo $product['discount']; ?>%
                                    </div>
                                <?php endif; ?>
                                <h5 class="card-title" style="display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;"><?php echo $product['name']; ?></h5>
                                <p class="card-text text-truncate"><?php echo $product['description']; ?></p>
                                <p class="card-text text-danger fw-bold"><?php echo number_format($product['price'], 0, ',', '.'); ?> VND</p>
                                <div class="d-flex justify-content-between position-absolute bottom-0 start-0 end-0 p-3">
                                    <a href="/products/detail?id=<?php echo $product['id']; ?>" class="btn" style="background-color: #fb5858; color: white">Chi tiết</a>
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
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
                                       <div class="d-flex justify-content-between align-items-center mb-3">
                                           <h2>Sản phẩm nổi bật</h2>
                                           <a href="/products" class="btn btn-outline-primary">Xem tất cả</a>
                                       </div>
                                       <div class="row row-cols-1 row-cols-md-2 row-cols-lg-5 g-4">
                                           <?php
                                           $count = 0;
                                           while ($product = $featured_products->fetch(PDO::FETCH_ASSOC)):
                                               $count++;
                                               if ($count > 10) break;
                                               ?>
                                               <div class="col">
                                                   <div class="card h-100 product-card shadow-sm " style="border-radius: 16px;">
                                                       <img style="border-top-left-radius: 16px; border-top-right-radius: 16px;" src="<?php echo !empty($product['image']) ? $product['image'] : 'https://via.placeholder.com/300x200?text=No+Image'; ?>" class="card-img-top" alt="<?php echo $product['name']; ?>">
                                                       <div class="card-body position-relative shadow"  style="padding-bottom: 54px; border-radius: 16px;">
                                                           <?php if (!empty($product['discount']) && $product['discount'] > 0): ?>
                                                               <div class="position-absolute start-0 bg-danger text-white py-1 px-2" style="top: -150px;border-radius: 0 0 8px 0; transform: translateY(-100%);">
                                                                   -<?php echo $product['discount']; ?>%
                                                               </div>
                                                           <?php endif; ?>
                                                           <h5 class="card-title" style="display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;"><?php echo $product['name']; ?></h5>
                                                           <p class="card-text text-truncate"><?php echo $product['description']; ?></p>
                                                           <p class="card-text text-danger fw-bold"><?php echo number_format($product['price'], 0, ',', '.'); ?> VND</p>
                                                           <div class="d-flex justify-content-between position-absolute bottom-0 start-0 end-0 p-3">
                                                               <a href="/products/detail?id=<?php echo $product['id']; ?>" class="btn" style="background-color: #fb5858; color: white">Chi tiết</a>
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
                                   </div>


    </div>

<?php include_once "layouts/footer.php"; ?>
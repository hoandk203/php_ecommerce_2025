<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shop Điện Tử</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <!-- Custom CSS -->
    <link href="/assets/css/style.css" rel="stylesheet">
</head>
<body style="padding-top: 100px; min-height: 100vh">
<header>
    <nav class="navbar navbar-expand-lg navbar-dark position-fixed top-0 w-100 z-3" style="background-color: #bf1414;">
        <div class="container">
            <a class="navbar-brand" href="/"><img src="/assets/image/logo.png" alt="logo-hoan-shop" style="width: 100px"></a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link text-white" href="/">Trang chủ</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-white" href="/products">Sản phẩm</a>
                    </li>
                </ul>

                <form class="d-flex me-3" action="/products" method="GET">
                        <div class="position-relative">
                                    <button class="btn position-absolute top-50 start-0 translate-middle-y border-0 bg-transparent" type="submit">
                                        <i class="fas fa-search"></i>
                                    </button>
                                    <input class="form-control ps-5" type="search" name="search" placeholder="Tìm kiếm sản phẩm...">
                                </div>
                    </form>

                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link text-white" href="/cart">
                            <i class="fas fa-shopping-cart"></i> Giỏ hàng
                            <?php
                            if (isset($_SESSION['user']['id'])) {
                                require_once __DIR__ . '/../../config/Database.php';
                                require_once __DIR__ . '/../../models/Cart.php';

                                $database = new Database();
                                $db = $database->getConnection();

                                $cart = new Cart($db);
                                $cart->getOrCreateCart($_SESSION['user']['id']);
                                $count = $cart->countItems();

                                if ($count > 0):
                                    ?>
                                    <span class="badge" style="background-color: white; color: black" ><?php echo $count; ?></span>
                                <?php
                                endif;
                            }
                            ?>
                        </a>
                    </li>

                    <?php if (isset($_SESSION['user'])): ?>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle text-white" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown">
                                <i class="fas fa-user"></i> <?php echo $_SESSION['user']['name']; ?>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <?php if ($_SESSION['user']['role'] === 'admin'): ?>
                                    <li><a class="dropdown-item" href="/admin">Dashboard Admin</a></li>
                                    <li><hr class="dropdown-divider"></li>
                                <?php endif; ?>
                                <li><a class="dropdown-item" href="/profile">Tài khoản của tôi</a></li>
                                <li><a class="dropdown-item" href="/orders">Đơn hàng của tôi</a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li><a class="dropdown-item" href="/logout">Đăng xuất</a></li>
                            </ul>
                        </li>
                    <?php else: ?>
                        <li class="nav-item">
                            <a class="nav-link text-white" href="/login">Đăng nhập</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-white" href="/register">Đăng ký</a>
                        </li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </nav>
</header>

<main class="container" style="min-height: 55vh">
    <?php if (isset($_SESSION['success'])): ?>
        <div class="alert alert-success alert-dismissible fade show">
            <?php
            echo $_SESSION['success'];
            unset($_SESSION['success']);
            ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <?php if (isset($_SESSION['error'])): ?>
    <div class="alert alert-danger alert-dismissible fade show">
        <?php
        echo $_SESSION['error'];
        unset($_SESSION['error']);
        ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php endif; ?>
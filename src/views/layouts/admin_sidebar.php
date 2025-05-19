<div class="container-fluid">
    <div class="row">
        <nav id="sidebarMenu" class="col-md-3 col-lg-2 d-md-block bg-light sidebar collapse">
            <div class="position-sticky pt-3">
                <ul class="nav flex-column">
                    <li class="nav-item">
                        <a class="nav-link <?php echo $path === '/admin' ? 'active' : ''; ?>" href="/admin">
                            <i class="fas fa-tachometer-alt"></i> Dashboard
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?php echo strpos($path, '/admin/products') === 0 ? 'active' : ''; ?>" href="/admin/products">
                            <i class="fas fa-box"></i> Quản lý sản phẩm
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?php echo strpos($path, '/admin/categories') === 0 ? 'active' : ''; ?>" href="/admin/categories">
                            <i class="fas fa-list"></i> Quản lý danh mục
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?php echo strpos($path, '/admin/orders') === 0 ? 'active' : ''; ?>" href="/admin/orders">
                            <i class="fas fa-shopping-cart"></i> Quản lý đơn hàng
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?php echo strpos($path, '/admin/users') === 0 ? 'active' : ''; ?>" href="/admin/users">
                            <i class="fas fa-users"></i> Quản lý người dùng
                        </a>
                    </li>
                </ul>

                <hr>

                <ul class="nav flex-column">
                    <li class="nav-item">
                        <a class="nav-link" href="/">
                            <i class="fas fa-home"></i> Về trang chủ
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/logout">
                            <i class="fas fa-sign-out-alt"></i> Đăng xuất
                        </a>
                    </li>
                </ul>
            </div>
        </nav>

        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4" style="min-height: 60vh">
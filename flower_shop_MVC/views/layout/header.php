<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <title>Flower Cat Shop</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="/Project_Alpha_FlowerShop/flower_shop_MVC/assets/images/image_logos/logo2.png"
        type="image/icon">
    <link href="/Project_Alpha_FlowerShop/flower_shop_MVC/assets/css/bootstrap.min.css" rel="stylesheet">
    <link href="/Project_Alpha_FlowerShop/flower_shop_MVC/assets/css/style.css" rel="stylesheet">
</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-light bg-green-100 sticky-top" style="border-bottom:1px green solid;">
        <div class="container">
            <!-- Logo -->
            <a class="navbar-brand d-flex align-items-center" href="index.php">
                <img src="/Project_Alpha_FlowerShop/flower_shop_MVC/assets/images/image_logos/logo2.png"
                    class="logo me-2">
                <span class="brand-name">Flower Cat-Shop</span>
            </a>
            <!-- Mobile button -->
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mynavbar">
                <span class="navbar-toggler-icon"></span>
            </button>
            <!-- Menu -->
            <div class="collapse navbar-collapse" id="mynavbar">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link active" href="index.php">Trang chủ</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">Sản phẩm</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">Liên hệ</a>
                    </li>
                </ul>
                <!-- Search -->
                <form class="d-flex" method="GET" action="index.php">
                    <div class="search-box d-flex align-items-center me-2">
                        <button type="button" class="btn btn-search me-2" onclick="toggleSearch()">
                            <img src="assets/images/image_logos/search-interface-symbol.png" alt="logo">
                        </button>
                        <input type="text" id="searchInput" class="form-control" placeholder="Search flower..."
                            style="display:none;" name="keyword">
                        <input type="hidden" name="action" value="search">
                    </div>
                </form>
                <!-- Login / Register -->
                <div class="d-flex align-items-center justify-content-end">
                    <?php if (isset($_SESSION['user'])): ?>
                        <div class="dropdown me-3">
                            <a class="nav-link dropdown-toggle fw-bold text-dark p-0" href="#" role="button"
                                id="userMenuLink" data-bs-toggle="dropdown" aria-expanded="false">
                                Xin chào <?= $_SESSION['user']['username'] ?>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end shadow border-0" aria-labelledby="userMenuLink">
                                <?php if ($_SESSION['user']['role'] == 'admin'): ?>
                                    <li>
                                        <a class="dropdown-item py-2" href="index.php?action=home_admin">
                                            Quản lý sản phẩm
                                        </a>
                                    </li>
                                    <li>
                                        <hr class="dropdown-divider">
                                    </li>
                                <?php endif; ?>
                                <li>
                                    <a class="dropdown-item py-2 text-danger" href="index.php?action=logout">
                                        Đăng xuất
                                    </a>
                                </li>
                            </ul>
                        </div>
                    <?php else: ?>
                        <a href="index.php?action=login" class="btn btn-outline-warning me-2 ms-2">Đăng nhập</a>
                    <?php endif; ?>
                    <a href="index.php?action=cart" class="btn btn-outline-success btn-cart border-0">
                        <img src="assets/images/image_logos/shopping-bag.png" alt="Cart" style="width: 24px;">
                    </a>
                </div>
            </div>
        </div>
    </nav>
    <script src="assets/js/bootstrap.bundle.min.js"></script>
    <script src="assets/js/javascript.js"></script>
</body>

</html>
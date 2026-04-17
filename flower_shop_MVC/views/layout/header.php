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
    <link rel="icon" href="assets/images/image_logos/logo2.png" type="image/icon">
    <link href="/assets/css/bootstrap.min.css" rel="stylesheet">
    <link href="/assets/css/style.css" rel="stylesheet">
</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-light bg-green-100 sticky-top" style="border-bottom:1px green solid;">
        <div class="container">
            <!-- Logo -->
            <a class="navbar-brand d-flex align-items-center" href="index.php">
                <img src="assets/images/image_logos/logo2.png" class="logo me-2">
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
                        <a class="nav-link" href="index.php?action=product">Sản phẩm</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="index.php?action=contact">Liên hệ</a>
                    </li>
                </ul>
                <!-- Search -->
                <form class="d-flex" method="GET" action="index.php">
                    <div class="search-box d-flex align-items-center me-2">
                        <button type="button" class="btn btn-search me-2" onclick="toggleSearch();">
                            <img src="assets/images/image_logos/search-interface-symbol.png" alt="logo">
                        </button>
                        <input type="text" id="searchInput" class="form-control" placeholder="Search flower..."
                            style="display:none;" name="keyword">
                        <input type="hidden" name="action" value="search">
                    </div>
                </form>
                <!--Login / Register-->
                <div class="d-flex align-items-center justify-content-end flex-wrap gap-2">
                    <?php if (isset($_SESSION['user'])): ?>
                        <div class="dropdown me-3">
                            <a class="nav-link dropdown-toggle fw-bold text-dark p-0" href="#" role="button"
                                id="userMenuLink" data-bs-toggle="dropdown" aria-expanded="false">
                                Xin chào <?= $_SESSION['user']['username'] ?>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end shadow-sm border-0 rounded-3 overflow-hidden">
                                <?php if ($_SESSION['user']['role'] == 'admin'): ?>
                                    <li>
                                        <a class="dropdown-item py-2 d-flex align-items-center"
                                            href="index.php?action=product_management">
                                            <i class="bi bi-box-seam me-2 text-primary"></i>
                                            <span>Quản lý sản phẩm</span>
                                        </a>
                                    </li>
                                    <li>
                                        <a class="dropdown-item py-2 d-flex align-items-center"
                                            href="index.php?action=contact_list">
                                            <i class="bi bi-chat-left-text me-2 text-primary"></i>
                                            <span>Quản lý liên hệ</span>
                                        </a>
                                    </li>
                                    <li>
                                        <a class="dropdown-item py-2 d-flex align-items-center"
                                            href="index.php?action=order_lists">
                                            <i class="bi bi-cart-check me-2 text-primary"></i>
                                            <span>Quản lý đơn đặt hàng</span>
                                        </a>
                                    </li>
                                <?php endif; ?>
                                    <li>
                                        <a class="dropdown-item py-2 d-flex align-items-center"
                                            href="index.php?action=contact_detail">
                                            <i class="bi bi-clock-history me-2 text-success"></i>
                                            <span>Lịch sử liên hệ</span>
                                        </a>
                                    </li>
                                
                                <li>
                                    <a class="dropdown-item py-2 d-flex align-items-center"
                                        href="index.php?action=my_orders">
                                        <i class="bi bi-cart-check me-2 text-primary"></i>
                                        <span>Đơn đã đặt</span>
                                    </a>
                                </li>
                                <li>
                                    <hr class="dropdown-divider mx-2">
                                </li>
                                <li>
                                    <a class="dropdown-item py-2 d-flex align-items-center text-danger"
                                        href="index.php?action=logout">
                                        <i class="bi bi-box-arrow-right me-2"></i>
                                        <span>Đăng xuất</span>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    <?php else: ?>
                        <a href="index.php?action=login" class="btn btn-outline-success me-2 ms-2">
                            Đăng nhập
                        </a>
                    <?php endif; ?>
                    <!--Cart-->
                    <a href="index.php?action=cart" class="btn btn-outline-success btn-cart border-0 ms-2">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="#000000"
                            viewBox="0 0 256 256">
                            <path
                                d="M236.78,68.37A6,6,0,0,0,232,66H55.67L45.78,30.39A6,6,0,0,0,40,26H16a6,6,0,0,0,0,12H35.44L71,165.89A22.08,22.08,0,0,0,92.16,182H191a22.08,22.08,0,0,0,21.2-16.11l25.63-92.28A6,6,0,0,0,236.78,68.37Zm-36.2,94.31A10,10,0,0,1,191,170H92.16a10,10,0,0,1-9.63-7.32L59,78H224.11ZM102,216a14,14,0,1,1-14-14A14,14,0,0,1,102,216Zm104,0a14,14,0,1,1-14-14A14,14,0,0,1,206,216Z">
                            </path>
                        </svg>
                    </a>
                </div>
            </div>
        </div>
    </nav>
</body>

</html>
<!DOCTYPE html>
<html lang="en">

<head>
    <title>Flower Cat Shop</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" href="assets/images/logo2.png" type="image/icon">
    <link href="assets/css/bootstrap.min.css" rel="stylesheet">
    <link href="assets/css/style.css" rel="stylesheet">
</head>
<?php session_start(); ?>

<body>

    <nav class="navbar navbar-expand-lg navbar-light bg-green-100 sticky-top" style="border-bottom:1px green solid;">
        <div class="container">
            <!-- Logo -->
            <a class="navbar-brand d-flex align-items-center" href="index.php">
                <img src="assets/images/logo2.png" class="logo me-2">
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
                    <div class="search-box d-flex align-items-center">
                        <button type="button" class="btn btn-search me-2" onclick="toggleSearch()">
                            🔍
                        </button>
                        <input type="text" id="searchInput" class="form-control" placeholder="Search flower..."
                            style="display:none;" name="keyword">
                        <input type="hidden" name="action" value="search">
                    </div>
                </form>
                <!-- Login / Register -->
                <?php if (isset($_SESSION['user'])): ?>
                    <span class="me-2 ms-2 px-3 py-1 border rounded-pill bg-light">
                        Xin chào <?= $_SESSION['user']['username'] ?>
                    </span>
                    <?php if ($_SESSION['user']['role'] == 'admin'): ?>
                        <a href="index.php?action=admin" class="btn btn-warning me-2">Quản trị</a>
                    <?php endif; ?>
                    <a href="index.php?action=logout" class="btn btn-danger">Logout</a>
                <?php else: ?>
                    <a href="index.php?action=login" class="btn btn-outline-success me-2 ms-2">Đăng nhập</a>
                    <a href="index.php?action=register" class="btn btn-success">Đăng ký</a>
                <?php endif; ?>
            </div>
        </div>
    </nav>
    <script src="assets/js/bootstrap.bundle.min.js"></script>
    <script src="assets/js/javascript.js"></script>
</body>

</html>
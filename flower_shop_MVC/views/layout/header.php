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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-light bg-green-100 sticky-top" style="border-bottom:1px green solid;">
        <div class="container d-flex align-items-center">
            <!-- Logo và tên thương hiệu -->
            <a class="navbar-brand d-flex align-items-center me-lg-4" href="index.php">
                <img src="assets/images/image_logos/logo2.png" class="logo me-2" style="width: 35px;">
                <span class="brand-name fw-bold" style="color: #2e7d32;">Flower Cat-Shop</span>
            </a>
            <!-- Menu điều hướng và các liên kết -->
            <div class="d-flex align-items-center gap-3 ms-auto ms-lg-0 order-lg-last">
                <?php if (isset($_SESSION['user'])): ?>
                    <div class="dropdown">
                        <a class="nav-link p-0 d-flex align-items-center" href="#" id="userMenuLink"
                            data-bs-toggle="dropdown" aria-expanded="false">
                            <div class="profile-icon-container">
                                <i class="fa-solid fa-user text-success"></i>
                            </div>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end shadow border-0 mt-2 custom-dropdown">
                            <li><a class="dropdown-item py-2" href="index.php?action=profile">Thông tin cá nhân</a></li>
                            <li>
                                <hr class="dropdown-divider">
                            </li>
                            <!-- Các liên kết dành cho admin -->
                            <?php if ($_SESSION['user']['role'] == 'admin'): ?>
                                <li><a class="dropdown-item py-2" href="index.php?action=product_management">Quản lý sản
                                        phẩm</a></li>
                                <li><a class="dropdown-item py-2" href="index.php?action=order_lists">Quản lý đơn hàng</a></li>
                                <li><a class="dropdown-item py-2" href="index.php?action=contact_list">Quản lý liên hệ</a></li>
                            <?php endif; ?>
                            <!-- Các liên kết dành cho user -->
                            <li><a class="dropdown-item py-2" href="index.php?action=contact_detail">Lịch sử liên hệ</a>
                            </li>
                            <li><a class="dropdown-item py-2" href="index.php?action=my_orders">Đơn đã đặt</a></li>
                            <li><a class="dropdown-item py-2" href="index.php?action=my_wishlist">Danh sách yêu thích</a></li>
                            <li>
                                <hr class="dropdown-divider">
                            </li>
                            <li><a class="dropdown-item py-2 text-danger" href="index.php?action=logout">Đăng xuất</a></li>
                        </ul>
                    </div>
                <?php else: ?>
                    <a href="index.php?action=login" class="btn btn-sm btn-outline-success rounded-pill px-3">Login</a>
                <?php endif; ?>
                <!-- Biểu tượng giỏ hàng -->
                <a href="index.php?action=cart" class="text-dark me-2 me-lg-0">
                    <i class="fa-solid fa-cart-shopping fs-5"></i>
                </a>
            </div>
            <!-- Nút toggle cho menu trên thiết bị di động -->
            <button class="navbar-toggler border-0 p-0" type="button" data-bs-toggle="collapse"
                data-bs-target="#mynavbar">
                <span class="navbar-toggler-icon"></span>
            </button>
            <!-- Menu điều hướng sẽ được ẩn trên thiết bị di động và hiển thị khi nhấn nút toggle -->
            <div class="collapse navbar-collapse" id="mynavbar">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item"><a class="nav-link active" href="index.php">Trang chủ</a></li>
                    <li class="nav-item"><a class="nav-link" href="index.php?action=product">Sản phẩm</a></li>
                    <li class="nav-item"><a class="nav-link" href="index.php?action=contact">Liên hệ</a></li>
                </ul>
                <!-- Form tìm kiếm sản phẩm -->
                <form class="d-flex my-2 my-lg-0 px-3" method="GET" action="index.php">
                    <div class="input-group">
                        <input type="text" name="keyword"
                            class="form-control form-control-sm border-success shadow-none"
                            placeholder="Search flower..." style="border-radius: 20px 0 0 20px;">
                        <button class="btn btn-success btn-sm" type="submit" style="border-radius: 0 20px 20px 0;">
                            <i class="fa-solid fa-magnifying-glass"></i>
                        </button>
                    </div>
                    <input type="hidden" name="action" value="search">
                </form>
            </div>
        </div>
    </nav>
</body>

</html>
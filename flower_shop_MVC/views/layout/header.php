<!DOCTYPE html>
<html lang="en">

<head>
    <title>Bootstrap Example</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link href="assets/css/bootstrap.min.css" rel="stylesheet">
    <link href="assets/css/style.css" rel="stylesheet">
</head>

<body>

    <nav class="navbar navbar-expand-lg navbar-light bg-green-100 sticky-top" 
        style="border-bottom:1px green solid;">
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
                <form class="d-flex">
                    <div class="search-box d-flex align-items-center">
                        <button type="button" class="btn btn-search me-2" onclick="toggleSearch()">
                            🔍
                        </button>
                        <input type="text" id="searchInput" class="form-control" placeholder="Search flower..."
                            style="display:none;">
                    </div>
                </form>
            </div>
        </div>
    </nav>
    <script src="assets/js/bootstrap.bundle.min.js"></script>
    <script src="assets/js/javascript.js"></script>
</body>

</html>
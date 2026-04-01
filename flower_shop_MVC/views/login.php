<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <title>Đăng nhập - Flower Cat Shop</title>
    <link href="/Project_Alpha_FlowerShop/FLOWER_SHOP_MVC/assets/css/bootstrap.min.css" rel="stylesheet">
    <link href="/Project_Alpha_FlowerShop/FLOWER_SHOP_MVC/assets/css/style.css" rel="stylesheet">
</head>

<body class="bg-login">
    <div class="auth-container">
        <div class="auth-card">
            <h2 style="color:#3a7d7c;">Đăng nhập</h2>
            <form method="POST" action="index.php?action=login_submit">
                <div class="mb-3">
                    <input type="text" name="username" class="form-control" placeholder="Tên đăng nhập">
                </div>
                <div class="mb-3">
                    <input type="password" name="password" class="form-control" placeholder="Mật khẩu">
                </div>
                <!-- Remember me -->
                <div class="form-check mb-3 text-start">
                    <input class="form-check-input" type="checkbox" name="remember" id="remember">
                    <label class="form-check-label" for="remember">
                        Ghi nhớ đăng nhập
                    </label>
                </div>
                <button class="btn btn-login w-100 rounded-pill">Đăng nhập</button>
                <p class="text-center mt-3">
                    <a href="/Project_Alpha_FlowerShop/FLOWER_SHOP_MVC/index.php"
                        class="btn btn-secondary w-100 rounded-pill">
                        ← Quay lại trang chủ
                    </a>
                </p>
                <p class="text-center mt-3">
                    Chưa có tài khoản?
                    <a href="index.php?action=register" class="link-register">Đăng ký</a>
                </p>
            </form>
        </div>
    </div>
</body>

</html>
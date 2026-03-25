<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <title>Đăng nhập - Flower Cat Shop</title>
    <link href="/Project_Alpha_FlowerShop/FLOWER_SHOP_MVC/assets/css/bootstrap.min.css" rel="stylesheet">
    <link href="/Project_Alpha_FlowerShop/FLOWER_SHOP_MVC/assets/css/style.css" rel="stylesheet">
</head>

<body class="bg-register">
    <div class="auth-container">
        <div class="auth-card">
            <h2 style="color:#ff6b81;">Đăng ký</h2>
            <form method="POST" action="index.php?action=register_submit">
                <div class="mb-3">
                    <input type="text" name="username" class="form-control" placeholder="Tên đăng nhập">
                </div>
                <div class="mb-3">
                    <input type="email" name="email" class="form-control" placeholder="Email">
                </div>
                <div class="mb-3">
                    <input type="password" name="password" class="form-control" placeholder="Mật khẩu">
                </div>
                <div class="mb-3">
                    <input type="password" name="confirm_password" class="form-control" placeholder="Nhập lại mật khẩu">
                </div>
                <button class="btn btn-register w-100 rounded-pill">Đăng ký</button>
                <p class="text-center mt-3">
                    <a href="/Project_Alpha_FlowerShop/FLOWER_SHOP_MVC/index.php" class="btn btn-secondary w-100 rounded-pill">
                        ← Quay lại trang chủ
                    </a>
                </p>
                <p class="text-center mt-3">
                    Đã có tài khoản?
                    <a href="index.php?action=login" class="link-login">Đăng nhập</a>
                </p>
            </form>
        </div>
    </div>
</body>

</html>
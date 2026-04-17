<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <title>Đăng nhập - Flower Cat Shop</title>
    <link rel="icon" href="assets/images/image_logos/logo2.png" type="image/icon">
    <link href="assets/css/bootstrap.min.css" rel="stylesheet">
    <link href="assets/css/style.css" rel="stylesheet">
</head>

<body class="bg-login">
    <div class="auth-container">
        <div class="auth-card">
            <?php if (isset($_GET['error']) && $_GET['error'] == 'denied'): ?>
                <div class="alert alert-danger text-center shadow-sm">
                    <i class="bi bi-exclamation-triangle-fill"></i>
                    <strong>Truy cập bị từ chối!</strong> <br>
                    Vui lòng đăng nhập với tài khoản Admin để tiếp tục.
                </div>
            <?php endif; ?>
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
                    <a href="index.php" class="btn btn-secondary w-100 rounded-pill">
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
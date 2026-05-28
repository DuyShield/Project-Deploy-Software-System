<?php
require_once "model/M_Category.php";
require_once "model/M_User.php";
// Kiểm tra session tồn tại chưa
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
// Xác định action hiện tại để highlight menu
$currentAction = $_GET['action'] ?? 'home';

// Lấy userId nếu có, nếu chưa đăng nhập thì mặc định là null
$userId = isset($_SESSION['user']['id']) ? $_SESSION['user']['id'] : null;

// Tạo model
$catModel = new M_Category();
$userModel = new M_User();
$categories = $catModel->getAllCategories();
$currentCategory = isset($_GET['category_id']) ? (int)$_GET['category_id'] : null;
// Hàm để chuyển severity thành class Bootstrap tương ứng
function getNotificationSeverityClass($severity)
{
    return match ($severity) {
        'warning' => 'warning',
        'danger' => 'danger',
        'success' => 'success',
        default => 'info',
    };
}
// Hàm để trích xuất severity và content từ thông báo, hỗ trợ cả trường hợp severity nằm trong content
function parseNotificationSeverityAndContent(array $notif): array
{
    $severity = $notif['severity'] ?? null;
    $content = $notif['content'] ?? '';
    $productId = null; // Mặc định là không có link sản phẩm

    // Nếu severity không tồn tại, cố gắng trích xuất từ content nếu có định dạng [severity:level]
    if (empty($severity) && preg_match('/^\[severity:(info|warning|danger|success)\](.*)$/s', $content, $matches)) {
        $severity = $matches[1];
        $content = $matches[2];
    }

    // Kiểm tra nếu content có chứa tag product_id để trích xuất ID sản phẩm
    if (preg_match('/^\[product_id:(\d+)\](.*)$/s', $content, $matches)) {
        $productId = (int)$matches[1];
        $content = $matches[2]; // Trả lại nội dung văn bản sạch không chứa tag
    }

    return [
        'severity' => $severity ?: 'info',
        'content' => $content,
        'product_id' => $productId // Trả thêm thông tin ID sản phẩm ra ngoài
    ];
}

// Lấy thông báo cho user nếu đã đăng nhập
$notifications = [];
$globalNotifications = [];
$notificationCount = 0;
$unreadNotificationCount = 0;
$unreadGlobalCount = 0;
// Nếu đã đăng nhập, lấy số lượng thông báo chưa đọc và danh sách thông báo để hiển thị
if ($userId !== null) {
    $notificationCount = $userModel->getNotificationCount($userId);
    $unreadNotificationCount = $userModel->getUnreadNotificationCount($userId);

    // Lấy danh sách thông báo riêng hiển thị trong dropdown
    $notifications = $userModel->getNotificationsForUser($userId, 5);
}
// Nếu đang ở trang home, cũng lấy thông báo global để hiển thị ở phần chính của trang chủ
if ($currentAction === 'home') {
    // Truyền $userId (có thể là ID hoặc null) vào Model
    $globalNotifications = $userModel->getGlobalNotificationsForUser($userId, 5);

    // Đếm số thông báo global chưa đọc
    foreach ($globalNotifications as $g) {
        // Chỉ tăng biến đếm nếu người dùng đã đăng nhập và thông báo đó chưa đọc
        if ($userId !== null && isset($g['is_read']) && $g['is_read'] == 0) {
            $unreadGlobalCount++;
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <title>Flower Cat Shop</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="assets/images/image_logos/logo2.png" type="image/icon">
    <link href="assets/css/bootstrap.min.css" rel="stylesheet">
    <link href="assets/css/style.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-light bg-green-100" style="border-bottom:1px green solid;">
        <div class="container d-flex align-items-center">
            <!-- Logo và tên thương hiệu -->
            <a class="navbar-brand d-flex align-items-center me-lg-4" href="index.php">
                <img src="assets/images/image_logos/logo2.png" class="logo me-2" style="width: 35px;">
                <span class="brand-name disabled fw-bold" style="color: #2e7d32;">Flower Cat-Shop</span>
            </a>
            <!-- Menu điều hướng và các liên kết -->
            <div class="d-flex align-items-center gap-3 ms-auto ms-lg-0 order-lg-last">
                <?php if (isset($_SESSION['user']) && $notificationCount > 0): ?>
                    <div class="dropdown notification-dropdown me-3">
                        <!-- Biểu tượng thông báo với badge hiển thị số lượng thông báo chưa đọc (tổng) -->
                        <a class="nav-link position-relative p-0 text-dark" href="#" id="notificationMenuLink"
                            data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="fa-solid fa-bell fs-5"></i>
                            <?php if ($unreadNotificationCount > 0): ?>
                                <span class="badge bg-danger rounded-pill notification-badge"><?= $unreadNotificationCount ?></span>
                            <?php endif; ?>
                        </a>
                        <!-- Dropdown menu hiển thị danh sách thông báo mới nhất (toàn bộ loại) -->
                        <ul class="dropdown-menu dropdown-menu-end shadow border-0 mt-2 notification-menu" aria-labelledby="notificationMenuLink">
                            <?php if (!empty($notifications)): ?>
                                <?php foreach ($notifications as $notif): ?>
                                    <?php
                                    $parsedNotification = parseNotificationSeverityAndContent($notif);
                                    $severityClass = getNotificationSeverityClass($parsedNotification['severity']);
                                    $badgeTextClass = $severityClass === 'danger' ? 'text-white' : 'text-dark';

                                    // Nếu có product_id thì dẫn tới trang chi tiết, ngược lại dẫn tới trang danh sách thông báo
                                    $targetLink = !empty($parsedNotification['product_id'])
                                        ? "index.php?action=detail&id=" . $parsedNotification['product_id']
                                        : "index.php?action=notifications";
                                    ?>
                                    <li>
                                        <a class="dropdown-item notification-item" href="<?= $targetLink ?>">
                                            <span class="badge bg-<?= $severityClass ?> <?= $badgeTextClass ?> me-2"><?= strtoupper($severityClass) ?></span>
                                            <strong><?= $notif['title'] ?></strong>
                                            <div class="notification-text mb-1"><?= htmlspecialchars($parsedNotification['content']) ?></div>
                                            <small class="text-muted"><?= $notif['created_at'] ?></small>
                                        </a>
                                    </li>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <li>
                                    <div class="dropdown-item text-muted">Không có thông báo</div>
                                </li>
                            <?php endif; ?>
                            <li>
                                <hr class="dropdown-divider">
                            </li>
                            <li><a class="dropdown-item text-center" href="index.php?action=notifications">Xem tất cả thông báo</a></li>
                        </ul>
                    </div>
                <?php endif; ?>
                <?php if (isset($_SESSION['user'])): ?>
                    <div class="dropdown">
                        <a class="nav-link p-0 d-flex align-items-center" href="#" id="userMenuLink"
                            data-bs-toggle="dropdown" aria-expanded="false">
                            <img src="assets/images/<?= ($_SESSION['user']['avatar'] == 'default.jpg') ? 'image_avatar_default/' : 'image_avatar_users/' ?><?= $_SESSION['user']['avatar'] ?>"
                                alt="Avatar" class="rounded-circle me-2" style="width: 32px; height: 32px; object-fit: cover;">
                            <span class="d-none d-md-inline"><?= $_SESSION['user']['username'] ?></span>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end shadow border-0 mt-2 custom-dropdown">
                            <li><a class="dropdown-item py-2" href="index.php?action=profile">Thông tin cá nhân</a></li>
                            <li>
                                <hr class="dropdown-divider">
                            </li>
                            <!-- Các liên kết dành cho admin -->
                            <?php if ($_SESSION['user']['role'] == 'admin'): ?>
                                <li><a class="dropdown-item py-2" href="index.php?action=dashboard">Dashboard</a></li>
                                <li><a class="dropdown-item py-2" href="index.php?action=contact_list">Quản lý liên hệ</a></li>
                                <li><a class="dropdown-item py-2" href="index.php?action=admin_notification_form">Gửi thông báo</a></li>
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
                    <li class="nav-item"><a class="nav-link <?= $currentAction === 'home' ? 'active' : '' ?>" href="index.php">Trang chủ</a></li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle <?= in_array($currentAction, ['product', 'filter_by_category', 'search', 'detail']) ? 'active' : '' ?>" href="index.php?action=product" role="button" data-bs-toggle="dropdown">
                            Sản phẩm
                        </a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="index.php?action=product">Tất cả sản phẩm</a></li>
                            <li>
                                <hr class="dropdown-divider">
                            </li>
                            <?php if (!empty($categories)): ?>
                                <?php foreach ($categories as $cat): ?>
                                    <li><a class="dropdown-item <?= ($currentAction === 'filter_by_category' && $currentCategory === $cat['id_category']) ? 'active' : '' ?>" href="index.php?action=filter_by_category&category_id=<?= $cat['id_category'] ?>">
                                            <?= $cat['name_category'] ?>
                                        </a></li>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </ul>
                    </li>
                    <li class="nav-item"><a class="nav-link <?= $currentAction === 'contact' ? 'active' : '' ?>" href="index.php?action=contact">Liên hệ</a></li>
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
    <?php if ($currentAction === 'home'): ?>
        <div class="secondary-navbar bg-white shadow-sm border-top">
            <div class="container">
                <ul class="nav justify-content-center flex-wrap gap-2 secondary-navbar-list">
                    <li class="nav-item"><a class="nav-link secondary-nav-link <?= in_array($currentAction, ['product', 'search']) && empty($currentCategory) ? 'active' : '' ?>" href="index.php?action=product">Tất cả sản phẩm</a></li>
                    <?php if (!empty($categories)): ?>
                        <?php foreach ($categories as $cat): ?>
                            <li class="nav-item">
                                <a class="nav-link secondary-nav-link <?= ($currentAction === 'filter_by_category' && $currentCategory === $cat['id_category']) ? 'active' : '' ?>" href="index.php?action=filter_by_category&category_id=<?= $cat['id_category'] ?>">
                                    <?= $cat['name_category'] ?>
                                </a>
                            </li>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    <?php endif; ?>
</body>

</html>
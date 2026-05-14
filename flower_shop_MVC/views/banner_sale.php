<?php require "views/layout/header.php"; ?>
<!-- Thông báo thành công hoặc lỗi -->
<div id="notification-container">
    <?php if (isset($_SESSION['success'])): ?>
        <div class="alert-box success">
            <i class="fas fa-check-circle"></i>
            <span><?= $_SESSION['success'];
            unset($_SESSION['success']); ?></span>
        </div>
    <?php endif; ?>

    <?php if (isset($_SESSION['error'])): ?>
        <div class="alert-box error">
            <i class="fas fa-exclamation-circle"></i>
            <span><?= $_SESSION['error'];
            unset($_SESSION['error']); ?></span>
        </div>
    <?php endif; ?>
</div>
<div class="container mt-4">
    <div>
        <div class="d-flex justify-content-between align-items-center mb-4 flex-column flex-md-row gap-2">
            <h2 class="text-uppercase fw-bold mb-0">Quản lý Banner & Sale</h2>
            <!-- Thanh điều hướng -->
            <div class="d-flex gap-2 flex-wrap">
                <a href="index.php?action=dashboard" class="btn btn-outline-success">Dashboard</a>
                <a href="index.php?action=product_management" class="btn btn-outline-success">Sản phẩm</a>
                <a href="index.php?action=order_lists" class="btn btn-outline-success">Đơn hàng</a>
                <a href="index.php?action=account_management" class="btn btn-outline-success">Quản Lý Tài Khoản</a>
                <a href="index.php?action=banner_sale" class="btn btn-success">Banner & Sale</a>
            </div>
        </div>
    </div>
    <!-- Trang quản lý banner và sản phẩm sale trên trang chủ -->
    <div class="card mb-4 shadow-sm">
        <div class="card-header table-color fw-bold">
            <h5 class="mb-0 back-color">Quản lý Banner trang chủ & Sản phẩm sale</h5>
        </div>
        <div class="card-body">
            <form action="index.php?action=save_home_settings" method="POST" enctype="multipart/form-data">
                <div class="row g-3">
                    <div class="col-md-6">
                        <!-- Dropdown chọn sản phẩm sale -->
                        <label class="form-label">Sản phẩm sale hiển thị</label>
                        <select name="sale_product_id" class="form-select">
                            <option value="">-- Chọn sản phẩm sale --</option>
                            <?php foreach ($products as $prod): ?>
                                <option value="<?= $prod['id_product'] ?>" <?= (isset($homeSettings['sale_product_id']) && $homeSettings['sale_product_id'] == $prod['id_product']) ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($prod['name_product']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Ghi chú</label>
                        <textarea class="form-control" rows="2"
                            readonly>Banner và sản phẩm sale sẽ hiển thị trên trang chủ khi không tìm kiếm.</textarea>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Banner chính 1</label>
                        <input type="file" name="main_banner_1" class="form-control">
                        <?php if (!empty($homeSettings['banners'][0])): ?>
                            <img src="assets/images/image_banners/<?= $homeSettings['banners'][0] ?>"
                                class="img-fluid mt-2 rounded" alt="Banner 1">
                        <?php endif; ?>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Banner chính 2</label>
                        <input type="file" name="main_banner_2" class="form-control">
                        <?php if (!empty($homeSettings['banners'][1])): ?>
                            <img src="assets/images/image_banners/<?= $homeSettings['banners'][1] ?>"
                                class="img-fluid mt-2 rounded" alt="Banner 2">
                        <?php endif; ?>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Banner chính 3</label>
                        <input type="file" name="main_banner_3" class="form-control">
                        <?php if (!empty($homeSettings['banners'][2])): ?>
                            <img src="assets/images/image_banners/<?= $homeSettings['banners'][2] ?>"
                                class="img-fluid mt-2 rounded" alt="Banner 3">
                        <?php endif; ?>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Banner nhỏ 1</label>
                        <input type="file" name="side_banner_1" class="form-control">
                        <?php if (!empty($homeSettings['side_banners'][0])): ?>
                            <img src="assets/images/image_banners/<?= $homeSettings['side_banners'][0] ?>"
                                class="img-fluid mt-2 rounded" alt="Banner nhỏ 1">
                        <?php endif; ?>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Banner nhỏ 2</label>
                        <input type="file" name="side_banner_2" class="form-control">
                        <?php if (!empty($homeSettings['side_banners'][1])): ?>
                            <img src="assets/images/image_banners/<?= $homeSettings['side_banners'][1] ?>"
                                class="img-fluid mt-2 rounded" alt="Banner nhỏ 2">
                        <?php endif; ?>
                    </div>
                </div>
                <div class="mt-3 text-end">
                    <button type="submit" class="btn btn-primary">Lưu cài đặt trang chủ</button>
                </div>
            </form>
        </div>
    </div>
</div>
<?php require "views/layout/footer.php"; ?>
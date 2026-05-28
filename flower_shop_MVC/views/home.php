<?php require "views/layout/header.php"; ?>
<!-- Hiển thị thông báo -->
<div id="notification-container">
    <?php if (isset($_SESSION['success'])): ?>
        <div class="alert-box success">
            <i class="fas fa-check-circle"></i>
            <span><?= $_SESSION['success']; unset($_SESSION['success']); ?></span>
        </div>
    <?php endif; ?>

    <?php if (isset($_SESSION['error'])): ?>
        <div class="alert-box error">
            <i class="fas fa-exclamation-circle"></i>
            <span><?= $_SESSION['error']; unset($_SESSION['error']); ?></span>
        </div>
    <?php endif; ?>
</div>
<!-- Trang hiển thị danh sách sản phẩm trên trang chủ -->
<body>
    <?php if (!isset($isSearch) && empty($hideBanner)) { ?>
        <div class="container mt-4">
            <?php if (isset($globalNotifications) && !empty($globalNotifications)): ?>
                <div class="mb-4">
                    <!-- Hiển thị thông báo global ở phần chính của trang chủ -->
                    <h5 class="mb-3">Thông báo mới</h5>
                    <?php foreach ($globalNotifications as $notif): ?>
                        <?php
                        $severity = $notif['severity'] ?? 'info';
                        $content = $notif['content'];
                        if (empty($notif['severity']) && preg_match('/^\[severity:(info|warning|danger|success)\](.*)$/s', $content, $matches)) {
                            $severity = $matches[1];
                            $content = $matches[2];
                        }
                        ?>
                        <div class="alert alert-<?= $severity ?> rounded-3">
                            <div class="d-flex justify-content-between align-items-start">
                                <div>
                                    <strong><?= $notif['title'] ?></strong>
                                    <p class="mb-1 small text-secondary"><?= $content ?></p>
                                </div>
                                <small class="text-muted"><?= $notif['created_at'] ?></small>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
            <div class="row g-2 g-md-3">
                <!-- Main Banner -->
                <div class="col-md-6 col-12">
                    <div id="bannerSlide" class="carousel slide" data-bs-ride="carousel" data-bs-interval="2000">
                        <div class="carousel-inner">
                            <?php
                            $bannerList = $homeSettings['banners'] ?? ['main-banner.jpg', 'main-banner1.jpg', 'main-banner2.jpg'];
                            foreach ($bannerList as $index => $bannerImage):
                                $activeClass = $index === 0 ? ' active' : '';
                                $src = 'assets/images/image_banners/' . $bannerImage;
                            ?>
                                <div class="carousel-item<?= $activeClass ?>">
                                    <img src="<?= $src ?>" class="d-block w-100 rounded" alt="Banner <?= $index + 1 ?>">
                                </div>
                            <?php endforeach; ?>
                        </div>
                        <!--Button Move -->
                        <button class="carousel-control-prev" type="button" data-bs-target="#bannerSlide"
                            data-bs-slide="prev">
                            <span class="carousel-control-prev-icon"></span>
                        </button>
                        <button class="carousel-control-next" type="button" data-bs-target="#bannerSlide"
                            data-bs-slide="next">
                            <span class="carousel-control-next-icon"></span>
                        </button>
                    </div>
                </div>
                <!-- Right Banner -->
                <div class="col-md-3 col-6">
                    <!-- Banner 1 -->
                    <?php $sideBanners = $homeSettings['side_banners'] ?? ['banner1.jpg', 'banner2.jpg']; ?>
                    <?php foreach ($sideBanners as $bannerImage): ?>
                        <div class="small-banner mb-3">
                            <img src="assets/images/image_banners/<?= $bannerImage ?>" class="img-fluid" alt="Side Banner">
                        </div>
                    <?php endforeach; ?>
                </div>
                <div class="col-md-3 col-6">
                    <!-- Flash sale -->
                    <div class="flash-sale baner-sale">
                        <h5 style="color:red;">FLASH SALE
                            <span class="time" id="countdown">07 : 04 : 29</span>
                        </h5>
                        <?php if (!empty($saleProduct)): ?>
                            <div class="product">
                                <img src="assets/images/image_products/<?= $saleProduct['image'] ?>" class="img-fluid img-banner-sale" alt="<?= $saleProduct['name_product'] ?>">
                                <h6><?= $saleProduct['name_product'] ?></h6>
                                <div class="price">
                                    <span class="new"><?= number_format($saleProduct['price_product']) ?> đ</span>
                                    <?php if (isset($saleProduct['price_product']) && is_numeric($saleProduct['price_product'])): ?>
                                        <span class="old"><?= number_format($saleProduct['price_product'] * 2) ?> đ</span>
                                    <?php endif; ?>
                                </div>
                                <div class="rating">
                                    ★ <?= number_format($saleProductRating['avg_rating'], 1) ?>
                                    (<?= number_format($saleProductRating['total_reviews']) ?> đánh giá)
                                </div>
                            </div>
                        <?php else: ?>
                            <div class="product text-center text-white">
                                <p class="mb-0">Chưa có sản phẩm sale</p>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    <?php } ?>
    <div class="container mt-4">
        <h2 class="title-section fw-bold text-uppercase">
            <?php if (isset($isSearch)) {
                echo "Kết quả tìm kiếm cho: <b>$keyword</b>";
            } elseif (isset($isFilter) && isset($categoryName) && $categoryName) {
                echo "Danh mục: <b>$categoryName</b>";
            } elseif (isset($isFilter)) {
                echo "Kết quả lọc sản phẩm";
            } else {
                echo "Danh sách hoa";
            } ?>
        </h2>
        <!-- Bộ lọc danh mục -->
        <?php if (!empty($showFilters) && isset($categories) && !empty($categories)): ?>
            <div class="mb-4 card shadow-sm">
                <div class="card-body">
                    <form method="GET" action="index.php" class="row g-3">
                        <input type="hidden" name="action" value="filter_products">
                        
                        <!-- Lọc theo danh mục -->
                        <div class="col-md-3">
                            <label class="form-label fw-bold">Danh mục</label>
                            <select name="category" class="form-select">
                                <option value="">Tất cả danh mục</option>
                                <?php if (isset($categories) && !empty($categories)): ?>
                                    <?php foreach ($categories as $cat): ?>
                                        <option value="<?= $cat['id_category'] ?>" 
                                                <?= (isset($_GET['category']) && $_GET['category'] == $cat['id_category']) ? 'selected' : '' ?>>
                                            <?= $cat['name_category'] ?>
                                        </option>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </select>
                        </div>
                        
                        <!-- Lọc theo giá -->
                        <div class="col-md-3">
                            <label class="form-label fw-bold">Giá từ (VNĐ)</label>
                            <input type="number" name="price_min" class="form-control" placeholder="Giá tối thiểu"
                                   value="<?= isset($_GET['price_min']) ? htmlspecialchars($_GET['price_min']) : '' ?>" min="0">
                        </div>
                        
                        <div class="col-md-3">
                            <label class="form-label fw-bold">Giá đến (VNĐ)</label>
                            <input type="number" name="price_max" class="form-control" placeholder="Giá tối đa"
                                   value="<?= isset($_GET['price_max']) ? htmlspecialchars($_GET['price_max']) : '' ?>" min="0">
                        </div>
                        
                        <!-- Lọc theo trạng thái -->
                        <div class="col-md-2">
                            <label class="form-label fw-bold">Trạng thái</label>
                            <select name="stock_status" class="form-select">
                                <option value="">Tất cả</option>
                                <option value="in_stock" <?= (isset($_GET['stock_status']) && $_GET['stock_status'] == 'in_stock') ? 'selected' : '' ?>>Còn hàng</option>
                                <option value="out_of_stock" <?= (isset($_GET['stock_status']) && $_GET['stock_status'] == 'out_of_stock') ? 'selected' : '' ?>>Hết hàng</option>
                            </select>
                        </div>
                        
                        <!-- Nút lọc -->
                        <div class="col-md-1 d-flex align-items-end">
                            <button type="submit" class="btn btn-success w-100">
                                <i class="fas fa-filter"></i> Lọc
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        <?php endif; ?>

        <div class="row g-3">
            <!-- Danh sách sản phẩm sẽ được hiển thị ở đây -->
            <?php if (!empty($products))
                foreach ($products as $row) {
                    $stock = isset($row['stock']) ? (int) $row['stock'] : 0;
                    ?>
                    <div class="col-md-3 col-sm-6 col-12">
                        <div class="card mb-4 card-size-img h-100 <?= ($stock <= 0) ? 'opacity-50' : '' ?>"
                            style="<?= ($stock <= 0) ?: '' ?>">
                            <!--Logo card-->
                            <img src="assets/images/image_products/<?php echo $row['image']; ?>" class="card-img-top">
                            <!--Information product-->
                            <div class="card-body">
                                <h5><?php echo $row['name_product']; ?></h5>
                                <p class="text-danger">
                                    <?php echo number_format($row['price_product']); ?> VNĐ
                                </p>
                                <a href="index.php?action=detail&id=<?php echo $row['id_product']; ?>"
                                    class="btn btn-success w-100">
                                    Xem chi tiết
                                </a>
                            </div>
                        </div>
                    </div>
                <?php } else { ?>
                <div class="col-12">
                    <div class="text-center">
                        <h3 class="text-danger" style="border: 4px;">Không tìm thấy sản phẩm
                            <?php if (isset($isSearch))
                                echo "\"$keyword\""; ?></h3>
                    </div>
                </div>
            <?php } ?>
        </div>

        <?php if (isset($totalPages) && $totalPages > 1): ?>
            <nav aria-label="Trang sản phẩm">
                <ul class="pagination justify-content-center mt-4">
                    <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                        <li class="page-item <?= (isset($page) && $page == $i) ? 'active' : '' ?>">
                            <a class="page-link" href="index.php?action=home&page=<?= $i ?>"><?= $i ?></a>
                        </li>
                    <?php endfor; ?>
                </ul>
            </nav>
        <?php endif; ?>

        <?php ?>
    </div>
    </div>
</body>
<?php require "views/layout/footer.php"; ?>
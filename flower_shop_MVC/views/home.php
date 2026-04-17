<?php require "views/layout/header.php"; ?>

<body>
    <?php if (!isset($isSearch) && empty($hideBanner)) { ?>
        <div class="container mt-4">
            <div class="row g-2 g-md-3">
                <!-- Main Banner -->
                <div class="col-md-6 col-12">
                    <div id="bannerSlide" class="carousel slide" data-bs-ride="carousel" data-bs-interval="2000">
                        <div class="carousel-inner">
                            <div class="carousel-item active">
                                <img src="assets/images/image_banners/main-banner.jpg" class="d-block w-100 rounded">
                            </div>
                            <div class="carousel-item">
                                <img src="assets/images/image_banners/main-banner1.jpg" class="d-block w-100 rounded">
                            </div>
                            <div class="carousel-item">
                                <img src="assets/images/image_banners/main-banner2.jpg" class="d-block w-100 rounded">
                            </div>
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
                    <div class="small-banner mb-3">
                        <img src="assets/images/image_banners/banner1.jpg" class="img-fluid">
                    </div>
                    <!-- Banner 2 -->
                    <div class="small-banner mb-3">
                        <img src="assets/images/image_banners/banner2.jpg" class="img-fluid">
                    </div>
                </div>
                <div class="col-md-3 col-6">
                    <!-- Flash sale -->
                    <div class="flash-sale baner-sale">
                        <h5 style="color:red;">FLASH SALE
                            <span class="time" id="countdown">07 : 04 : 29</span>
                        </h5>
                        <div class="product">
                            <img src="assets/images/image_products/hoa_hong.jpg" class="img-fluid img-banner-sale">
                            <h6>Hoa Hồng</h6>
                            <div class="price">
                                <span class="new">120,000 đ</span>
                                <span class="old">240,000 đ</span>
                            </div>
                            <div class="rating">
                                ★ 5.0 (49)
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <?php } ?>
    <div class="container mt-4">
        <h2 class="title-section fw-bold text-uppercase">
            <?php if (isset($isSearch)) {
                echo "Kết quả tìm kiếm cho: <b>$keyword</b>";
            } else {
                echo "Danh sách hoa";
            } ?>
        </h2>
        <div class="row g-3">
            <?php if (!empty($products))
                foreach ($products as $row) { ?>
                    <div class="col-md-3 col-sm-6 col-12">
                        <div class="card mb-4 card-size-img h-100">
                            <!--Logo card-->
                            <img src="assets/images/image_products/<?php echo $row['image']; ?>" class="card-img-top">
                            <!--Information product-->
                            <div class="card-body">
                                <h5><?php echo $row['name_product']; ?></h5>
                                <p class="text-danger">
                                    <?php echo number_format($row['price_product']); ?> VNĐ
                                </p>
                                <a href="index.php?action=detail&id=<?php echo $row['id_product']; ?>" class="btn btn-success w-100">
                                    Xem chi tiết
                                </a>
                            </div>
                        </div>
                    </div>
                <?php } else { ?>
                <div class="col-12">
                    <div class="text-center">
                        <h3 class="text-danger" style="border: 4px;">Không tìm thấy sản phẩm "<?= $keyword ?>"</h3>
                    </div>
                </div>
            <?php } ?>
            <?php ?>
        </div>
    </div>
</body>
<?php require "views/layout/footer.php"; ?>
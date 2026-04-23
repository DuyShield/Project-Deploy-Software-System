<?php require "views/layout/header.php"; ?>
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
<div class="container mt-5">
    <div class="row g-4">
        <!--Main Picture-->
        <div class="col-md-5 col-12">
            <div class="product-img mb-4">
                <img src="assets/images/image_products/<?php echo $product['image']; ?>"
                    class="img-fluid rounded shadow-lg product-main-img" alt="Product Image">
            </div>
        </div>
        <!--Detail Product-->
        <div class="col-md-7 col-12">
            <h1 class="product-title fw-bold text-dark mb-3">
                <?php echo $product['name_product']; ?>
            </h1>
            <div class="product-price mb-4">
                <span class="price-current text-danger fw-bold fs-4">
                    <?php echo number_format($product['price_product']); ?> VNĐ
                </span>
            </div>
            <div class="product-description mb-4">
                <h5 class="fw-semibold mb-2">Mô tả sản phẩm</h5>
                <p class="text-muted lh-base">
                    <?php echo $product['description_product']; ?>
                </p>
            </div>
            <form action="index.php?action=add_to_cart" method="POST">
                <input type="hidden" name="id_product" value="<?php echo $product['id_product']; ?>">
                <input type="hidden" name="name_product" value="<?php echo $product['name_product']; ?>">
                <input type="hidden" name="price_product" value="<?php echo $product['price_product']; ?>">
                <input type="hidden" name="image" value="<?php echo $product['image']; ?>">
                <!--Quantity-->
                <div class="mb-4">
                    <label class="form-label fw-semibold">Số lượng:</label>
                    <div class="d-flex align-items-center gap-3">
                        <button type="button" class="btn btn-outline-secondary qty-btn"
                            onclick="changeQty(-1)">-</button>
                        <input type="number" name="quantity" value="1" min="1"
                            class="form-control text-center qty-input" style="width: 80px;">
                        <button type="button" class="btn btn-outline-secondary qty-btn"
                            onclick="changeQty(1)">+</button>
                    </div>
                </div>
                <!--Button-->
                <div class="d-flex gap-2 mb-4">
                    <div class="d-flex gap-3 mb-4">
                        <button class="btn btn-success btn-lg px-4 py-2 fw-semibold">
                            <i class="bi bi-cart-plus me-2"></i>Add to Cart
                        </button>
                        <button type="submit" formaction="index.php?action=add_wishlist"
                            class="btn btn-outline-danger btn-lg px-4 py-2 fw-semibold">
                            <i class="fa-solid fa-heart me-2"></i>Wishlist
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- Bình luận & Đánh giá -->
<div class="container mt-5 comment-section">
    <h3 class="fw-bold mb-4 comment-title">Bình luận & Đánh giá</h3>
    <!-- Thông báo khi chưa đăng nhập tk-->
    <?php if (!isset($_SESSION['user'])): ?>
        <div class="alert alert-light border shadow-sm text-center mt-4 py-3">
            <i class="bi bi-info-circle me-2"></i>
            Vui lòng <a href="index.php?action=login" class="text-success fw-bold text-decoration-none">Đăng nhập</a> để
            bình luận.
        </div>
    <?php endif; ?>
    <div class="mt-5">
        <!-- Danh sách bình luận -->
        <?php if (!empty($comments)): ?>
            <?php foreach ($comments as $comment): ?>
                <div class="comment-item d-flex">
                    <div class="flex-shrink-0">
                        <div class="rounded-circle bg-light d-flex align-items-center justify-content-center"
                            style="width: 50px; height: 50px; color: #a5d6a7;">
                            <i class="bi bi-person-fill fs-3"></i>
                        </div>
                    </div>
                    <div class="ms-3">
                        <h6 class="mb-0 fw-bold"><?php echo htmlspecialchars($comment['username']); ?></h6>
                        <small class="text-muted" style="font-size: 0.8rem;">
                            <?php echo date("d/m/Y", strtotime($comment['created_at'])); ?>
                            <span class="badge bg-light text-success border ms-2">Đã mua hàng</span>
                        </small>
                        <p class="mt-2 text-dark" style="line-height: 1.6;"><?php echo htmlspecialchars($comment['content']); ?>
                        </p>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="text-center py-5">
                <img src="assets/images/empty_comment.png" style="width: 80px; opacity: 0.3;">
                <p class="text-muted mt-3">Chưa có bình luận nào cho sản phẩm này.</p>
            </div>
        <?php endif; ?>
    </div>
</div>
<?php require "views/layout/footer.php"; ?>
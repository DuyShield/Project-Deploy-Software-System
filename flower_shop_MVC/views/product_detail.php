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
                        <?php $stock = isset($product['stock']) ? (int)$product['stock'] : 0; ?>
                        <?php if ($stock > 0): ?>
                        <button class="btn btn-success btn-lg px-4 py-2 fw-semibold">
                            <i class="bi bi-cart-plus me-2"></i>Add to Cart
                        </button>
                        <?php else: ?>
                        <button class="btn btn-danger btn-lg px-4 py-2 fw-semibold" disabled>
                            <i class="bi bi-exclamation-circle me-2"></i>Hết hàng
                        </button>
                        <?php endif; ?>
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
    <h3 class="fw-bold mb-4 comment-title">Đánh giá sản phẩm</h3>
    
    <!-- Summary Đánh giá -->
    <div class="row g-4 mb-5">
        <div class="col-md-3">
            <div class="card shadow-sm h-100 border-0">
                <div class="card-body text-center">
                    <h1 class="display-4 fw-bold text-danger"><?= $ratingInfo['avg_rating'] ?></h1>
                    <div class="mb-2">
                        <?php
                        $fullStars = floor($ratingInfo['avg_rating']);
                        $hasHalfStar = ($ratingInfo['avg_rating'] - $fullStars) >= 0.5;
                        for ($i = 0; $i < $fullStars; $i++) {
                            echo '<i class="fas fa-star text-warning"></i>';
                        }
                        if ($hasHalfStar) {
                            echo '<i class="fas fa-star-half-alt text-warning"></i>';
                        }
                        $emptyStars = 5 - $fullStars - ($hasHalfStar ? 1 : 0);
                        for ($i = 0; $i < $emptyStars; $i++) {
                            echo '<i class="far fa-star text-warning"></i>';
                        }
                        ?>
                    </div>
                    <small class="text-muted"><?= $totalReviews ?> đánh giá</small>
                </div>
            </div>
        </div>
        
        <div class="col-md-9">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-body">
                    <!-- Thống kê chi tiết -->
                    <?php
                    // Tính tổng số đánh giá để tính phần trăm
                    $totalStars = array_sum($starStats);
                    // Hiển thị thống kê phần trăm cho từng mức sao
                    for ($i = 5; $i >= 1; $i--) {
                        $count = $starStats[$i] ?? 0;
                        $percentage = $totalStars > 0 ? round(($count / $totalStars) * 100) : 0;
                    ?>
                    <div class="d-flex align-items-center mb-3">
                        <span class="text-muted" style="width: 40px;"><?= $i ?> ⭐</span>
                        <div class="progress flex-grow-1 mx-3" style="height: 20px;">
                            <div class="progress-bar bg-warning" role="progressbar" 
                                 style="width: <?= $percentage ?>%;" 
                                 aria-valuenow="<?= $percentage ?>" 
                                 aria-valuemin="0" aria-valuemax="100">
                                <?= $percentage ?>%
                            </div>
                        </div>
                        <span class="text-muted" style="width: 40px;"><?= $count ?></span>
                    </div>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Button Viết/Sửa Đánh Giá -->
    <?php if (!isset($_SESSION['user'])): ?>
        <div class="alert alert-light border shadow-sm text-center mt-4 py-3">
            <i class="bi bi-info-circle me-2"></i>
            Vui lòng <a href="index.php?action=login" class="text-success fw-bold text-decoration-none">Đăng nhập</a> để đánh giá sản phẩm.
        </div>
    <?php else: ?>
        <div class="mb-4">
            <?php if ($userReviewed && $userComment): ?>
                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#editReviewModal">
                    <i class="fa-solid fa-pen"></i>
                     Sửa đánh giá của bạn
                </button>
            <?php elseif ($hasPurchased): ?>
                <a href="index.php?action=write_review&id_product=<?= $id ?>&id_order=0" class="btn btn-success">
                    <i class="fa-regular fa-star"></i> Viết đánh giá
                </a>
            <?php else: ?>
                <div class="alert alert-warning">Chỉ khách hàng đã mua sản phẩm này mới có thể đánh giá.</div>
            <?php endif; ?>
        </div>
        
        <!-- Modal Sửa Đánh Giá -->
        <?php if ($userReviewed && $userComment): ?>
        <div class="modal fade" id="editReviewModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header bg-success text-white">
                        <h5 class="modal-title">Sửa đánh giá của bạn</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <form action="index.php?action=save_review" method="POST">
                        <div class="modal-body">
                            <input type="hidden" name="id_product" value="<?= $id ?>">
                            <input type="hidden" name="id_order" value="<?= $userComment['id_order'] ?>">
                            
                            <div class="mb-4">
                                <label class="form-label d-block fw-bold">Chất lượng sản phẩm:</label>
                                <div class="star-rating fs-2">
                                    <?php for ($i = 5; $i >= 1; $i--): ?>
                                        <input type="radio" name="rating" value="<?= $i ?>" id="editStar<?= $i ?>" 
                                               <?= ($userComment['rating'] == $i) ? 'checked' : '' ?>>
                                        <label for="editStar<?= $i ?>" title="<?= $i ?> sao"><i class="fas fa-star"></i></label>
                                    <?php endfor; ?>
                                </div>
                            </div>
                            
                            <div class="mb-4">
                                <label class="form-label fw-bold">Nội dung đánh giá:</label>
                                <textarea name="content" class="form-control" rows="5"><?= htmlspecialchars($userComment['content']) ?></textarea>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                            <button type="submit" class="btn btn-primary">Lưu thay đổi</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <?php endif; ?>
    <?php endif; ?>
    
    <!-- Danh sách bình luận -->
    <div class="mt-5">
        <h5 class="fw-bold mb-4">Các đánh giá khác</h5>
        <?php if (!empty($comments)): ?>
            <?php foreach ($comments as $comment): ?>
                <div class="comment-item d-flex mb-4 pb-4 border-bottom">
                    <div class="flex-shrink-0">
                        <div class="rounded-circle bg-light d-flex align-items-center justify-content-center"
                            style="width: 50px; height: 50px; color: #a5d6a7;">
                            <i class="bi bi-person-fill fs-3"></i>
                        </div>
                    </div>
                    <div class="ms-3">
                        <h6 class="mb-0 fw-bold"><?php echo $comment['username']; ?></h6>
                        <small class="text-muted" style="font-size: 0.8rem;">   
                            <?php echo date("d/m/Y", strtotime($comment['created_at'])); ?>
                            <span class="badge bg-light text-success border ms-2">Đã mua hàng</span>
                        </small>
                        <div class="my-2">
                            <?php
                            $fullStars = floor($comment['rating']);
                            for ($i = 0; $i < $fullStars; $i++) {
                                echo '<i class="fas fa-star text-warning"></i>';
                            }
                            for ($i = $fullStars; $i < 5; $i++) {
                                echo '<i class="far fa-star text-warning"></i>';
                            }
                            ?>
                        </div>
                        <p class="mt-2 text-dark" style="line-height: 1.6;"><?php echo $comment['content']; ?></p>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="text-center py-5">
                <p class="text-muted mt-3">Chưa có đánh giá nào cho sản phẩm này.</p>
            </div>
        <?php endif; ?>
    </div>
</div>
<?php require "views/layout/footer.php"; ?>
<?php require "views/layout/header.php"; ?>
<!-- Trang hiển thị form đánh giá sản phẩm sau khi khách hàng đã nhận hàng -->
<div class="container mt-5">
    <div class="card shadow-sm border-0 mx-auto" style="max-width: 600px;">
        <div class="card-body p-4 text-center">
            <h4 class="fw-bold mb-4" style="color: #2e7d32;">Đánh giá sản phẩm</h4>
            <!-- Hiển thị thông tin sản phẩm và đơn hàng để khách hàng biết đang đánh giá sản phẩm nào -->
            <div class="d-flex align-items-center mb-4 text-start p-2 bg-light rounded">
                <img src="assets/images/image_products/<?= $product['image'] ?>" width="80" class="rounded">
                <div class="ms-3">
                    <h6 class="mb-1 fw-bold"><?= $product['name_product'] ?></h6>
                    <small class="text-muted">Đơn hàng: #<?= $id_order ?></small>
                </div>
            </div>
            <!-- Form đánh giá sản phẩm -->
            <form action="index.php?action=save_review" method="POST">
                <!-- Các input ẩn để gửi thông tin sản phẩm và đơn hàng khi submit form -->
                <input type="hidden" name="id_product" value="<?= $product['id_product'] ?>">
                <input type="hidden" name="id_order" value="<?= $id_order ?>">
                <!-- Đánh giá sản phẩm -->
                <div class="mb-4">
                    <label class="form-label d-block fw-bold">Chất lượng sản phẩm:</label>
                    <div class="star-rating fs-2">
                        <?php for ($i = 5; $i >= 1; $i--): ?>
                            <input type="radio" name="rating" value="<?= $i ?>" id="star<?= $i ?>" <?= $i == 5 ? 'checked' : '' ?>>
                            <label for="star<?= $i ?>" title="<?= $i ?> sao"><i class="fas fa-star"></i></label>
                        <?php endfor; ?>
                    </div>
                </div>
                <!-- Textarea để khách hàng nhập nội dung đánh giá -->
                <div class="mb-4 text-start">
                    <label class="form-label fw-bold">Nội dung đánh giá:</label>
                    <textarea name="content" class="form-control" rows="5"
                        placeholder="Hãy chia sẻ cảm nhận của bạn về bó hoa này nhé..."></textarea>
                </div>
                <!-- Button gửi đánh giá -->
                <div class="d-grid gap-2">
                    <button class="btn btn-success btn-lg">Gửi đánh giá ngay</button>
                    <a href="index.php?action=my_orders" class="btn btn-link text-muted">Quay lại đơn hàng</a>
                </div>
            </form>
        </div>
    </div>
</div>
<?php require "views/layout/footer.php"; ?>
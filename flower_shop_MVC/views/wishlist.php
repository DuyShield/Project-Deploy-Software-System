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
<div class="container my-5">
    <h3 class="fw-bold mb-4" style="color: #2e7d32;">
        <i class="fa-solid fa-heart me-2"></i>Sản phẩm yêu thích của tôi
    </h3>
    <!-- Danh sách sản phẩm yêu thích -->
    <div class="card shadow-sm border-0 rounded-3">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th class="ps-4 py-3">Sản phẩm</th>
                            <th class="py-3">Giá tiền</th>
                            <th class="py-3 text-center">Trạng thái</th>
                            <th class="py-3 text-center">Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Sản phẩm yêu thích sẽ được hiển thị ở đây -->
                        <?php foreach ($items as $item): ?>
                            <tr>
                                <td class="ps-4 py-3">
                                    <div class="d-flex align-items-center">
                                        <img src="assets/images/image_products/<?= $item['image'] ?>" class="rounded"
                                            style="width: 70px; height: 70px; object-fit: cover;">
                                        <div class="ms-3">
                                            <h6 class="mb-0 fw-bold"><?= $item['name_product'] ?></h6>
                                            <small class="text-muted">Phân loại: Hoa tươi</small>
                                        </div>
                                    </div>
                                </td>
                                <td class="py-3">
                                    <span class="text-danger fw-bold"><?= number_format($item['price_product']) ?>đ</span>
                                </td>
                                <td class="py-3 text-center">
                                    <?php if ($item['stock'] > 0): ?>
                                        <span class="bg-success-subtle text-success px-3">Còn hàng</span>
                                    <?php else: ?>
                                        <span class="bg-danger-subtle text-danger px-3">Hết hàng</span>
                                    <?php endif; ?>
                                </td>
                                <td class="py-3 text-center">
                                    <div class="d-flex justify-content-center gap-2">
                                        <form action="index.php?action=add_to_cart" method="POST">
                                            <input type="hidden" name="id_product" value="<?= $item['id_product'] ?>">
                                            <input type="hidden" name="quantity" value="1">
                                            <button type="submit" class="btn btn-success btn-sm rounded-pill px-3">
                                                <i class="fa-solid fa-cart-plus me-1"></i>Thêm vào giỏ
                                            </button>
                                        </form>

                                        <button type="button"
                                            class="btn btn-outline-danger btn-sm rounded-circle btn-delete-wishlist"
                                            data-bs-toggle="modal" data-bs-target="#modalDelete"
                                            data-id="<?= $item['id_product'] ?>" data-name="<?= $item['name_product'] ?>">
                                            <i class="fa-solid fa-trash-can"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                        <!-- Nếu không có sản phẩm yêu thích nào -->
                        <?php if (empty($items)): ?>
                            <tr>
                                <td colspan="4" class="text-center py-5">
                                    <img src="assets/images/empty-wishlist.png" style="width: 150px;"
                                        class="mb-3 opacity-50">
                                    <p class="text-muted">Danh sách yêu thích của bạn còn trống.</p>
                                    <a href="index.php?action=product" class="btn btn-success rounded-pill px-4">Tiếp tục
                                        mua sắm</a>
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<!-- Modal xác nhận xóa khỏi yêu thích -->
<div class="modal fade" id="modalDelete" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title">Xác nhận xóa</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                    aria-label="Close"></button>
            </div>
            <form action="index.php?action=remove_wishlist" method="POST">
                <div class="modal-body">
                    <p>Bạn có chắc muốn xóa sản phẩm: <strong id="deleteProductName"></strong> khỏi danh sách yêu thích?
                    </p>
                    <input type="hidden" name="id_product" id="deleteProductId">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                    <button type="submit" class="btn btn-danger">Xác nhận xóa</button>
                </div>
            </form>
        </div>
    </div>
</div>
<?php require "views/layout/footer.php"; ?>
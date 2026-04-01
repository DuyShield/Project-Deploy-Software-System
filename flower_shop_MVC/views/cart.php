<?php require "views/layout/header.php";
?>

<div class="container my-5">
    <h2 class="mb-4 fw-bold text-uppercase">Giỏ hàng</h2>
    
    <?php if (empty($cartItems)): ?>
        <div class="text-center py-5 border rounded bg-light">
            <p class="mb-3 text-secondary">Giỏ hàng của bạn hiện đang trống.</p>
            <a href="index.php" class="btn btn-outline-dark rounded-0 px-4 fw-bold">QUAY LẠI CỬA HÀNG</a>
        </div>
    <?php else: ?>
        <div class="row g-5">
            <div class="col-lg-8">
                <div class="table-responsive px-4 py-2">
                    <table class="table align-middle">
                        <thead>
                            <tr class="text-uppercase small text-muted">
                                <th class="border-0 ps-0">Sản phẩm</th>
                                <th class="border-0 text-center">Số lượng</th>
                                <th class="border-0 text-end pe-0">Thành tiền</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($cartItems as $id => $item): 
                                //Xác định ID sản phẩm
                                $productId = $item['id_product'] ?? $id; 
                            ?>
                                <tr>
                                    <td class="ps-1 py-4">
                                        <div class="d-flex align-items-center">
                                            <img src="assets/images/image_products/<?php echo $item['image'] ?? 'none.jpg'; ?>"
                                                 class="border me-3 img-cart" style="width: 70px; height: 80px; object-fit: cover;">
                                            <div>
                                                <div class="fw-bold text-dark"><?php echo $item['name_product']; ?></div>
                                                <div class="text-muted small">Đơn giá: <?php echo number_format($item['price_product']); ?> đ</div>
                                                
                                                <a href="index.php?action=remove_from_cart&id=<?php echo $productId; ?>"
                                                   class="text-secondary small text-decoration-underline"
                                                   onclick="return confirm('Xóa sản phẩm này?')">
                                                   Xóa sản phẩm
                                                </a>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="text-center">
                                        <div class="d-flex justify-content-center">
                                            <div class="d-inline-flex border align-items-center bg-white" style="height: 35px;">
                                                <a href="index.php?action=update_cart&id=<?php echo $productId; ?>&op=dec"
                                                   class="btn btn-sm px-3 border-0">-</a>
                                                
                                                <input type="text" value="<?php echo $item['quantity']; ?>"
                                                       class="form-control form-control-sm text-center border-0 bg-transparent fw-bold"
                                                       style="width: 40px; padding: 0;" readonly>
                                                
                                                <a href="index.php?action=update_cart&id=<?php echo $productId; ?>&op=inc"
                                                   class="btn btn-sm px-3 border-0">+</a>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="text-end pe-0 fw-bold">
                                        <?php echo number_format($item['price_product'] * $item['quantity']); ?> đ
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
                <div class="mt-3">
                    <a href="index.php" class="text-dark text-decoration-none small fw-bold">← TIẾP TỤC MUA SẮM</a>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="p-4 bg-light border" style="top: 90px;">
                    <h5 class="fw-bold mb-4 text-uppercase">Thông tin đơn hàng</h5>
                    <div class="d-flex justify-content-between mb-2">
                        <span>Tạm tính</span>
                        <span><?php echo number_format($total); ?> đ</span>
                    </div>
                    <div class="d-flex justify-content-between mb-4">
                        <span>Giao hàng</span>
                        <span class="small text-muted fst-italic">Miễn phí</span>
                    </div>
                    <hr>
                    <div class="d-flex justify-content-between mb-4">
                        <span class="fw-bold fs-5">TỔNG CỘNG</span>
                        <span class="fw-bold fs-5 text-danger"><?php echo number_format($total); ?> đ</span>
                    </div>
                    <a href="index.php?action=checkout" class="btn btn-dark w-100 rounded-0 py-3 fw-bold">
                        THANH TOÁN NGAY
                    </a>
                </div>
            </div>
        </div>
    <?php endif; ?>
</div>
<?php require "views/layout/footer.php"; ?>
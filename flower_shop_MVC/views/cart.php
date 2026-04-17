<?php require "views/layout/header.php";
?>

<body class="site-container">
    <main class="main-content">
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
                        <form action="index.php?action=checkout" method="POST" id="cartForm">
                            <div class="table-responsive px-4 py-2">
                                <table class="table align-middle">
                                    <thead>
                                        <tr class="text-uppercase small text-muted align-middle">
                                            <th class="border-0 ps-0">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" id="selectAllCheckbox"
                                                        checked>
                                                    <label class="form-check-label small fw-bold"
                                                        for="selectAllCheckbox">Chọn</label>
                                                </div>
                                            </th>
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
                                            <tr id="cart-row-<?php echo $productId; ?>"
                                                data-product-id="<?php echo $productId; ?>"
                                                data-unit-price="<?php echo $item['price_product']; ?>">
                                                <td class="ps-1 py-4">
                                                    <div class="form-check">
                                                        <input class="form-check-input selected-item-checkbox" type="checkbox"
                                                            name="selected_items[]" value="<?php echo $productId; ?>" checked
                                                            data-item-total="<?php echo $item['price_product'] * $item['quantity']; ?>">
                                                    </div>
                                                </td>
                                                <td class="ps-1 py-4">
                                                    <div class="d-flex align-items-center">
                                                        <img src="assets/images/image_products/<?php echo $item['image'] ?? 'none.jpg'; ?>"
                                                            class="border me-3 img-cart"
                                                            style="width: 70px; height: 80px; object-fit: cover;">
                                                        <div>
                                                            <div class="fw-bold text-dark">
                                                                <?php echo $item['name_product']; ?>
                                                            </div>
                                                            <div class="text-muted small">Đơn giá:
                                                                <?php echo number_format($item['price_product']); ?> đ
                                                            </div>

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
                                                        <div class="d-inline-flex border align-items-center bg-white"
                                                            style="height: 35px;">
                                                            <a href="index.php?action=update_cart&id=<?php echo $productId; ?>&op=dec"
                                                                class="btn btn-sm px-3 border-0 cart-qty-btn"
                                                                data-id="<?php echo $productId; ?>" data-op="dec">-</a>

                                                            <input type="text" value="<?php echo $item['quantity']; ?>"
                                                                class="form-control form-control-sm text-center border-0 bg-transparent fw-bold cart-qty-value"
                                                                style="width: 40px; padding: 0;" readonly>

                                                            <a href="index.php?action=update_cart&id=<?php echo $productId; ?>&op=inc"
                                                                class="btn btn-sm px-3 border-0 cart-qty-btn"
                                                                data-id="<?php echo $productId; ?>" data-op="inc">+</a>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td class="text-end pe-0 fw-bold">
                                                    <span class="item-total">
                                                        <?php echo number_format($item['price_product'] * $item['quantity']); ?>
                                                    </span>
                                                    đ
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                            <div class="d-flex justify-content-between align-items-center mt-3">
                                <a href="index.php" class="text-dark text-decoration-none small fw-bold">← TIẾP TỤC MUA
                                    SẮM</a>
                                <button type="button"
                                    class="btn btn-link text-danger text-decoration-none small fw-bold p-0"
                                    data-bs-toggle="modal" data-bs-target="#modalClearCart">Xóa tất cả giỏ hàng</button>
                            </div>
                    </div>

                    <div class="col-lg-4">
                        <div class="p-4 bg-light border" style="top: 90px;">
                            <h5 class="fw-bold mb-4 text-uppercase">Thông tin đơn hàng</h5>
                            <div class="d-flex justify-content-between mb-2">
                                <span>Tạm tính</span>
                                <span id="subtotalAmount">
                                    <?php echo number_format($total); ?> đ
                                </span>
                            </div>
                            <div class="d-flex justify-content-between mb-4">
                                <span>Giao hàng</span>
                                <span class="small text-muted fst-italic">Miễn phí</span>
                            </div>
                            <hr>
                            <div class="d-flex justify-content-between mb-4">
                                <span class="fw-bold fs-5">TỔNG CỘNG</span>
                                <span id="grandTotalAmount" class="fw-bold fs-5 text-danger">
                                    <?php echo number_format($total); ?>
                                    đ
                                </span>
                            </div>
                            <input type="hidden" name="total_price" value="<?php echo $total; ?>"
                                data-full-total="<?php echo $total; ?>">
                            <button type="submit" class="btn btn-dark w-100 rounded-0 py-3 fw-bold">
                                THANH TOÁN
                            </button>
                        </div>
                    </div>
                </div>
                </form>
                <!--Modal xóa tất cả giỏ hàng-->
                <div class="modal fade" id="modalClearCart" tabindex="-1" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header bg-danger text-white">
                                <h5 class="modal-title">Xác nhận xóa</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <form action="index.php?action=clear_cart" method="POST">
                                <div class="modal-body">
                                    <p>Bạn có chắc muốn xóa tất cả sản phẩm trong giỏ hàng?</p>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                                    <button type="submit" class="btn btn-danger">Xóa tất cả</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </main>
</body>

<?php require "views/layout/footer.php"; ?>
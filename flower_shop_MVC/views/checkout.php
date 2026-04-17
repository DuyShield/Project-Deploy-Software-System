<?php require "views/layout/header.php"; ?>

<body class="site-container">
    <main class="main-content">
        <form action="index.php?action=process_checkout" method="POST" onsubmit="return validateCheckout()">
            <div class="checkout-wrapper">
                <div class="checkout-form">
                    <h2 class="section-title">THÔNG TIN GIAO HÀNG</h2>
                    <?php if (!empty($_SESSION['checkout_error'])): ?>
                        <div class="form-error" style="color: #d9534f; margin-bottom: 1rem;">
                            <?php echo $_SESSION['checkout_error'];
                            unset($_SESSION['checkout_error']); ?>
                        </div>
                    <?php endif; ?>
                    <div class="form-group">
                        <input type="text" name="name" placeholder="Họ và tên người nhận" class="input-control"
                            required>
                    </div>
                    <div class="form-row">
                        <input type="tel" name="phone" placeholder="Số điện thoại" class="input-control"
                            inputmode="numeric" pattern="[0-9]{10}" title="Số điện thoại phải gồm đúng 10 chữ số"
                            required>
                        <input type="email" name="email" placeholder="Email" class="input-control" required>
                    </div>
                    <div class="form-group">
                        <select class="input-control" id="province" name="province_id">
                            <option value="">Chọn Tỉnh / Thành phố</option>
                        </select>
                    </div>

                    <div class="form-group group-hidden" id="district-group">
                        <select class="input-control" id="district" name="district_id">
                            <option value="">Chọn Quận / Huyện</option>
                        </select>
                    </div>

                    <div class="form-group group-hidden" id="ward-group">
                        <select class="input-control" id="ward" name="ward_id">
                            <option value="">Chọn Phường / Xã</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <input type="text" name="address" placeholder="Địa chỉ chi tiết (Số nhà, tên đường...)"
                            class="input-control" required>
                    </div>
                    <div class="form-group">
                        <textarea name="note" placeholder="Ghi chú đơn hàng (ví dụ: Giao giờ hành chính)"
                            class="input-control" rows="3"></textarea>
                    </div>
                    <h2 class="section-title" style="margin-top: 40px;">PHƯƠNG THỨC THANH TOÁN</h2>
                    <div class="payment-options">
                        <label class="payment-card active js-payment-card" onclick="changeActivePayment(this)">
                            <input type="radio" name="payment" value="cod" checked style="display: none;">
                            <span class="payment-name">Thanh toán khi nhận hàng (COD)</span>
                        </label>

                        <label class="payment-card js-payment-card" onclick="changeActivePayment(this)">
                            <input type="radio" name="payment" value="bank_transfer" style="display: none;">
                            <span class="payment-name">Chuyển khoản Ngân hàng (QR Code)</span>
                        </label>
                    </div>

                    <!-- QR Code cho chuyển khoản -->
                    <div id="qr-code-section" class="qr-code-card" style="display: none;">
                        <div class="qr-card-header">
                            <div>
                                <h3>Chuyển khoản ngân hàng</h3>
                                <p>Quét mã QR hoặc chuyển khoản theo thông tin bên dưới.</p>
                            </div>
                        </div>
                        <div class="qr-card-body">
                            <div class="qr-image-wrapper">
                                <img src="/assets/images/image_qr/QR_code.png" alt="QR Code chuyển khoản">
                            </div>
                            <div class="qr-bank-details">
                                <div class="detail-row"><span>Ngân hàng:</span><strong>TPBank</strong></div>
                                <div class="detail-row"><span>Số tài khoản:</span><strong>00002491268</strong></div>
                                <div class="detail-row"><span>Chủ tài khoản:</span><strong>Flower Shop</strong></div>
                                <div class="detail-row"><span>Số tiền:</span><strong
                                        id="qr-amount"><?php echo number_format($total); ?> đ</strong></div>
                                <div class="detail-row"><span>Nội dung:</span><strong>Thanh toan don hang</strong></div>
                            </div>
                        </div>
                        <div class="qr-card-footer">
                            <p>Vui lòng chụp ảnh biên nhận sau khi chuyển khoản và gửi lại cho chúng tôi để xác nhận đơn
                                hàng.</p>
                        </div>
                    </div>
                </div>

                <div class="order-sidebar">
                    <div class="summary-box">
                        <h3 class="summary-title">ĐƠN HÀNG CỦA BẠN</h3>
                        <div class="product-mini">
                            <?php foreach ($cartItems as $item): ?>
                                <div class="item d-flex justify-content-between mb-2">
                                    <span>
                                        <?php echo $item['name_product']; ?>
                                        (x<?php echo $item['quantity']; ?>)
                                    </span>
                                    <span class="price">
                                        <?php echo number_format($item['price_product'] * $item['quantity']); ?> đ
                                    </span>
                                </div>
                            <?php endforeach; ?>
                        </div>

                        <div class="divider"></div>
                        <div class="summary-row d-flex justify-content-between">
                            <span>Tạm tính</span>
                            <span>
                                <?php echo number_format($total); ?> đ
                            </span>
                        </div>
                        <div class="total-row d-flex justify-content-between fw-bold">
                            <span>TỔNG CỘNG</span>
                            <span class="grand-total text-danger">
                                <?php echo number_format($total); ?> đ
                            </span>
                        </div>
                        <input type="hidden" name="total_price" value="<?php echo $total; ?>"
                            data-full-total="<?php echo $total; ?>">
                        <?php if (!empty($selectedItems)): ?>
                            <?php foreach ($selectedItems as $selectedItem): ?>
                                <input type="hidden" name="selected_items[]" value="<?php echo $selectedItem; ?>">
                            <?php endforeach; ?>
                        <?php endif; ?>
                        <button class="btn-complete">HOÀN TỐT ĐẶT HÀNG</button>
                        <a href="index.php?action=cart" class="back-to-cart">← Quay lại giỏ hàng</a>
                    </div>
                </div>
            </div>
        </form>
    </main>
</body>
<?php require "views/layout/footer.php"; ?>
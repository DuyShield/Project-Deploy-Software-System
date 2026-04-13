<?php require "views/layout/header.php"; ?>
<form action="index.php?action=process_checkout" method="POST">
    <div class="checkout-wrapper">
        <div class="checkout-form">
            <h2 class="section-title">THÔNG TIN GIAO HÀNG</h2>
            <div class="form-group">
                <input type="text" name="name" placeholder="Họ và tên người nhận" class="input-control">
            </div>
            <div class="form-row">
                <input type="text" name="phone" placeholder="Số điện thoại" class="input-control">
                <input type="email" name="email" placeholder="Email" class="input-control">
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
                    class="input-control">
            </div>
            <div class="form-group">
                <textarea name="note" placeholder="Ghi chú đơn hàng (ví dụ: Giao giờ hành chính)" class="input-control"
                    rows="3"></textarea>
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
                <input type="hidden" name="total_price" value="<?php echo $total; ?>">
                <button class="btn-complete">HOÀN TẤT ĐẶT HÀNG</button>
                <a href="index.php?action=cart" class="back-to-cart">← Quay lại giỏ hàng</a>
            </div>
        </div>
    </div>
</form>
<?php require "views/layout/footer.php"; ?>
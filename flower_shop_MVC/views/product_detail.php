<?php require "views/layout/header.php"; ?>
<div class="container mt-5">
    <div class="row g-4">
        <!--Main Picture-->
        <div class="col-md-5 col-12">
            <div class="product-img mb-4">
                <img src="/Project_Alpha_FlowerShop/flower_shop_MVC/assets/images/image_products/<?php echo $product['image']; ?>"
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
                        <button type="button" class="btn btn-outline-secondary qty-btn" onclick="changeQty(-1)">-</button>
                        <input type="number" name="quantity" value="1" min="1" class="form-control text-center qty-input"
                            style="width: 80px;">
                        <button type="button" class="btn btn-outline-secondary qty-btn" onclick="changeQty(1)">+</button>
                    </div>
                </div>
                <!--Button-->
                <div class="d-flex gap-2 mb-4">
                    <div class="d-flex gap-3 mb-4">
                        <button class="btn btn-success btn-lg px-4 py-2 fw-semibold">
                            <i class="bi bi-cart-plus me-2"></i>Add to Cart
                        </button>
                        <button class="btn btn-outline-danger btn-lg px-4 py-2 fw-semibold">
                            <i class="bi bi-heart me-2"></i>Wishlist
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
<?php require "views/layout/footer.php"; ?>
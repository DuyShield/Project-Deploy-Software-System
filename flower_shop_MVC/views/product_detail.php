<?php require "views/layout/header.php"; ?>
<body>
    <div class="container mt-5">
    <div class="row">

        <!--Main Picture-->
        <div class="col-md-5">
            <div class="product-img">
                <img src="assets/images/<?php echo $product['image']; ?>" 
                     class="img-fluid rounded shadow">
            </div>
        </div>
        <!--Detail Product-->
        <div class="col-md-7">
            <h2 class="product-title">
                <?php echo $product['name_product']; ?>
            </h2>
            <h3 class="text-danger mb-3">
                <?php echo number_format($product['price_product']); ?> VNĐ
            </h3>
            <p class="text-muted">
                <?php echo $product['description_product']; ?>
            </p>
            <!--Quantity-->
            <div class="mb-3">
                <label>Số lượng:</label>
                <input type="number" value="1" min="1" class="form-control w-25">
            </div>
            <!--Button-->
            <div class="d-flex gap-3">
                <button class="btn btn-success btn-lg">
                    Add to cart
                </button>
                <button class="btn btn-outline-danger btn-lg">
                    Wishlist
                </button>
            </div>
        </div>
    </div>
</div>
</body>
<?php require "views/layout/footer.php"; ?>
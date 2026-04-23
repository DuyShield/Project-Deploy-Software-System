<?php require "views/layout/header.php"; ?>
<!--Trang hiển thị khi đặt hàng thành công-->
<div class="container text-center" style="padding: 100px 0;">
    <div class="success-icon" style="font-size: 80px; color: #28a745;">
        <i class="fas fa-check-circle"></i> </div>
    <h1 class="mt-4 fw-bold">ĐẶT HÀNG THÀNH CÔNG!</h1>
    <p class="text-secondary fs-5">Cảm ơn bạn đã tin tưởng Flower Shop. <br> 
    Chúng tôi sẽ sớm liên hệ với bạn qua số điện thoại để xác nhận đơn hàng.</p>
    
    <div class="mt-5">
        <a href="index.php" class="btn btn-dark px-5 py-3 fw-bold">TIẾP TỤC MUA SẮM</a>
        <a href="index.php?action=my_orders" class="btn btn-outline-dark px-5 py-3 fw-bold ms-3">XEM ĐƠN HÀNG</a>
    </div>
</div>

<?php require "views/layout/footer.php"; ?>
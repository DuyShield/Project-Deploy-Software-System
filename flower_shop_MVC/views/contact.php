<?php require "views/layout/header.php";
if (isset($_SESSION['success'])): ?>
    <div class="message_success">
        <?= $_SESSION['success'];
        unset($_SESSION['success']); ?>
    </div>
<?php endif; ?>

<?php if (isset($_SESSION['error'])): ?>
    <div class="message_failed">
        <?= $_SESSION['error'];
        unset($_SESSION['error']); ?>
    </div>
<?php endif;
?>

<div class="container mt-5 mb-5">
    <div class="row g-4">
        <!--Thông tin liên hệ-->
        <div class="col-md-5">
            <div class="p-4 shadow rounded bg-light h-100">
                <h4 class="fw-bold mb-3 text-success">Thông tin liên hệ</h4>

                <p><strong><svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="#000000"
                            viewBox="0 0 256 256">
                            <path
                                d="M26.22,94.41A6,6,0,0,0,26,96v16A38,38,0,0,0,42,143V216a6,6,0,0,0,6,6H208a6,6,0,0,0,6-6V143A38,38,0,0,0,230,112V96a5.91,5.91,0,0,0-.23-1.64L215.43,44.15A14.07,14.07,0,0,0,202,34H54A14.07,14.07,0,0,0,40.57,44.15Zm25.89-47A2,2,0,0,1,54,46H202a2,2,0,0,1,1.92,1.45L216.05,90H40ZM102,102h52v10a26,26,0,0,1-52,0Zm-64,0H90v10a26,26,0,0,1-38.18,23,6,6,0,0,0-1.65-1A26,26,0,0,1,38,112ZM202,210H54V148.66a38,38,0,0,0,42-16.21,37.95,37.95,0,0,0,64,0,38,38,0,0,0,42,16.21Zm3.83-76a6,6,0,0,0-1.65,1A26,26,0,0,1,166,112V102h52v10A26,26,0,0,1,205.83,134Z">
                            </path>
                        </svg> Flower Cat Shop</strong></p>
                <p><svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="#000000" viewBox="0 0 256 256">
                        <path
                            d="M128,66a38,38,0,1,0,38,38A38,38,0,0,0,128,66Zm0,64a26,26,0,1,1,26-26A26,26,0,0,1,128,130Zm0-112a86.1,86.1,0,0,0-86,86c0,30.91,14.34,63.74,41.47,94.94a252.32,252.32,0,0,0,41.09,38,6,6,0,0,0,6.88,0,252.32,252.32,0,0,0,41.09-38c27.13-31.2,41.47-64,41.47-94.94A86.1,86.1,0,0,0,128,18Zm0,206.51C113,212.93,54,163.62,54,104a74,74,0,0,1,148,0C202,163.62,143,212.93,128,224.51Z">
                        </path>
                    </svg> Địa chỉ: TP. Hồ Chí Minh</p>
                <p><svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="#000000" viewBox="0 0 256 256">
                        <path
                            d="M221.59,160.3l-47.24-21.17a14,14,0,0,0-13.28,1.22,4.81,4.81,0,0,0-.56.42l-24.69,21a1.88,1.88,0,0,1-1.68.06c-15.87-7.66-32.31-24-40-39.65a1.91,1.91,0,0,1,0-1.68l21.07-25a6.13,6.13,0,0,0,.42-.58,14,14,0,0,0,1.12-13.27L95.73,34.49a14,14,0,0,0-14.56-8.38A54.24,54.24,0,0,0,34,80c0,78.3,63.7,142,142,142a54.25,54.25,0,0,0,53.89-47.17A14,14,0,0,0,221.59,160.3ZM176,210C104.32,210,46,151.68,46,80A42.23,42.23,0,0,1,82.67,38h.23a2,2,0,0,1,1.84,1.31l21.1,47.11a2,2,0,0,1,0,1.67L84.73,113.15a4.73,4.73,0,0,0-.43.57,14,14,0,0,0-.91,13.73c8.87,18.16,27.17,36.32,45.53,45.19a14,14,0,0,0,13.77-1c.19-.13.38-.27.56-.42l24.68-21a1.92,1.92,0,0,1,1.6-.1l47.25,21.17a2,2,0,0,1,1.21,2A42.24,42.24,0,0,1,176,210Z">
                        </path>
                    </svg> SĐT: 0123 456 789</p>
                <p><svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="#000000" viewBox="0 0 256 256">
                        <path
                            d="M224,50H32a6,6,0,0,0-6,6V192a14,14,0,0,0,14,14H216a14,14,0,0,0,14-14V56A6,6,0,0,0,224,50Zm-96,85.86L47.42,62H208.58ZM101.67,128,38,186.36V69.64Zm8.88,8.14L124,148.42a6,6,0,0,0,8.1,0l13.4-12.28L208.58,194H47.43ZM154.33,128,218,69.64V186.36Z">
                        </path>
                    </svg> phuocduy565@gmail.com</p>

                <hr>

                <p class="mb-2"><svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="#000000"
                        viewBox="0 0 256 256">
                        <path
                            d="M128,26A102,102,0,1,0,230,128,102.12,102.12,0,0,0,128,26Zm0,192a90,90,0,1,1,90-90A90.1,90.1,0,0,1,128,218Zm62-90a6,6,0,0,1-6,6H128a6,6,0,0,1-6-6V72a6,6,0,0,1,12,0v50h50A6,6,0,0,1,190,128Z">
                        </path>
                    </svg> Giờ mở cửa:</p>
                <p>09:00 - 20:00 (T2 - T7)</p>
            </div>
        </div>
        <!--Form liên hệ-->
        <div class="col-md-7">
            <div class="p-4 shadow rounded bg-white">
                <h4 class="fw-bold mb-3 text-success">Gửi liên hệ cho chúng tôi</h4>

                <form method="POST" action="index.php?action=contact_submit">
                    <div class="mb-3">
                        <label class="form-label">Họ tên</label>
                        <input name="name" type="text" class="form-control" placeholder="Nhập họ tên..." required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Email</label>
                        <input name="email" type="email" class="form-control" placeholder="Nhập email..." required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Nội dung</label>
                        <textarea name="message" class="form-control" rows="4" placeholder="Nhập nội dung..."
                            required></textarea>
                    </div>

                    <button class="btn btn-success w-100">
                        Gửi liên hệ
                    </button>
                </form>
            </div>
        </div>

    </div>
    <!--Google Map-->
    <div class="mt-5 shadow rounded overflow-hidden">
        <iframe src="https://www.google.com/maps?q=Ho+Chi+Minh+City&output=embed" width="100%" height="350"
            style="border:0;" allowfullscreen="" loading="lazy">
        </iframe>
    </div>
</div>

<?php require "views/layout/footer.php"; ?>
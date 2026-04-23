<?php require "views/layout/header.php"; ?>
<!--Trang hiển thị chi tiết đơn hàng dành cho khách hàng-->
<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold text-dark text-uppercase">CHI TIẾT ĐƠN HÀNG #<?= $order['id_order'] ?></h2>
            <p class="text-muted mb-0">
                <i class="far fa-calendar-alt"></i> Ngày đặt: <?= date("d/m/Y H:i", strtotime($order['created_at'])) ?>
            </p>
        </div>
        <a href="index.php?action=my_orders" class="btn btn-outline-secondary fw-bold shadow-sm">
            <i class="fas fa-chevron-left"></i> QUAY LẠI
        </a>
    </div>
    <!--Thông tin đơn hàng và sản phẩm trong đơn sẽ được hiển thị ở đây-->
    <div class="row">
        <div class="col-md-4">
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-light fw-bold py-3">
                    <i class="fas fa-shipping-fast me-2"></i> Thông tin nhận hàng
                </div>
                <div class="card-body">
                    <p class="mb-2"><strong>Người nhận:</strong> <?= $order['fullname'] ?></p>
                    <p class="mb-2"><strong>Số điện thoại:</strong> <?= $order['phone'] ?></p>
                    <p class="mb-0"><strong>Địa chỉ:</strong> <?= $order['address'] ?></p>
                </div>
            </div>

            <div class="card shadow-sm border-0">
                <div class="card-body">
                    <strong>Trạng thái: </strong>
                    <?php
                    $status_map = [
                        0 => ['text' => 'Chờ xác nhận', 'class' => 'status-pending'],
                        1 => ['text' => 'Đang giao', 'class' => 'status-shipping'],
                        2 => ['text' => 'Đã giao', 'class' => 'status-success'],
                        3 => ['text' => 'Đã hủy', 'class' => 'status-cancel']
                    ];
                    $st = $status_map[$order['status']] ?? $status_map[0];
                    ?>
                    <span class="badge-status <?= $st['class'] ?>"><?= $st['text'] ?></span>
                </div>
            </div>
        </div>
        <!--Bảng hiển thị các sản phẩm trong đơn hàng-->
        <div class="col-md-8">
            <div class="table-responsive shadow-sm rounded">
                <table class="table align-middle bg-white mb-0">
                    <thead class="table-color text-white">
                        <tr>
                            <th class="py-3">Sản phẩm</th>
                            <th class="text-center py-3">Giá</th>
                            <th class="text-center py-3">Số lượng</th>
                            <th class="text-end py-3">Thành tiền</th>
                            <?php if ($isDelivered): ?>
                                <th class="text-center py-3">Hành động</th>
                            <?php endif; ?>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Sản phẩm trong đơn sẽ được hiển thị ở đây -->
                        <?php foreach ($items as $item): ?>
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <img src="assets/images/image_products/<?= $item['image'] ?>" width="60"
                                            class="rounded shadow-sm me-3 border">
                                        <span class="fw-bold text-dark"><?= $item['name_product'] ?></span>
                                    </div>
                                </td>
                                <td class="text-center"><?= number_format($item['price']) ?>đ</td>
                                <td class="text-center"><?= $item['quantity'] ?></td>
                                <td class="text-end fw-bold"><?= number_format($item['price'] * $item['quantity']) ?>đ</td>

                                <?php if ($isDelivered): ?>
                                    <td class="text-center">
                                        <a href="index.php?action=write_review&id_product=<?= $item['id_product'] ?>&id_order=<?= $order['id_order'] ?>"
                                            class="btn btn-sm btn-success rounded-pill px-3 shadow-sm">
                                            Đánh giá
                                        </a>
                                    </td>
                                <?php endif; ?>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                    <!-- Hiển thị tổng tiền ở cuối bảng -->
                    <tfoot class="bg-light">
                        <tr>
                            <td colspan="<?= $totalColumns - 1 ?>" class="text-end fw-bold py-3">
                                <?php
                                echo ($order['status'] == 2) ? "Tổng tiền:" : "Tổng tiền thanh toán:";
                                ?>
                            </td>
                            <td class="text-end text-danger fs-5 fw-bold py-3">
                                <?= number_format($order['total_price']) ?>đ
                            </td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
</div>
<?php require "views/layout/footer.php"; ?>
<?php require "views/layout/header.php"; ?>
<div id="notification-container">
    <?php if (isset($_SESSION['success'])): ?>
        <div class="alert-box success">
            <i class="fas fa-check-circle"></i>
            <span><?= $_SESSION['success'];
            unset($_SESSION['success']); ?></span>
        </div>
    <?php endif; ?>

    <?php if (isset($_SESSION['error'])): ?>
        <div class="alert-box error">
            <i class="fas fa-exclamation-circle"></i>
            <span><?= $_SESSION['error'];
            unset($_SESSION['error']); ?></span>
        </div>
    <?php endif; ?>
</div>

<body class="site-container">
    <main class="main-content">
        <div class="container mt-4">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2 class="fw-bold text-dark text-uppercase">QUẢN LÝ ĐẶT HÀNG</h2>
            </div>
            <!-- Bảng danh sách đơn hàng -->
            <div class="table-responsive shadow-sm rounded">
                <table class="table table-hover align-middle bg-white mb-2">
                    <thead class="table-color">
                        <tr>
                            <th class="text-center" style="width: 5%">ID</th>
                            <th style="width: 15%">Khách hàng</th>
                            <th style="width: 30%">Thông tin liên hệ</th>
                            <th style="width: 12%">Tổng tiền</th>
                            <th style="width: 15%">Trạng thái</th>
                            <th class="text-center" style="width: 15%">Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($orders)): ?>
                            <?php foreach ($orders as $row): ?>
                                <tr>
                                    <td class="text-center"><?= $row['id_order'] ?></td>
                                    <td><strong><?= $row['fullname'] ?></strong></td>
                                    <td>
                                        <div class="small">SĐT: <?= $row['phone'] ?></div>
                                        <div class="small text-muted">ĐC: <?= $row['address'] ?></div>
                                    </td>
                                    <td class="text-danger fw-bold"><?= number_format($row['total_price']) ?>đ</td>
                                    <td>
                                        <?php
                                        $status_map = [
                                            0 => ['text' => 'Chờ xác nhận', 'class' => 'status-pending'],
                                            1 => ['text' => 'Đang giao', 'class' => 'status-shipping'],
                                            2 => ['text' => 'Đã giao', 'class' => 'status-success'],
                                            3 => ['text' => 'Đã hủy', 'class' => 'status-cancel']
                                        ];
                                        $st = $status_map[$row['status']] ?? $status_map[0];
                                        ?>
                                        <span class="badge-status <?= $st['class'] ?>"><?= $st['text'] ?></span>
                                    </td>
                                    <td class="text-center">
                                        <div class="d-flex justify-content-center gap-2">
                                            <div class="dropdown">
                                                <button class="btn btn-sm btn-warning fw-bold text-white px-3"
                                                    data-bs-toggle="dropdown">
                                                    Sửa
                                                </button>
                                                <ul class="dropdown-menu shadow-sm border-0">
                                                    <li>
                                                        <form action="index.php?action=update_status" method="POST">
                                                            <input type="hidden" name="id_order"
                                                                value="<?= $row['id_order'] ?>">
                                                            <input type="hidden" name="status" value="1">
                                                            <button type="submit" class="dropdown-item small">Đang giao</button>
                                                        </form>
                                                    </li>
                                                    <li>
                                                        <form action="index.php?action=update_status" method="POST">
                                                            <input type="hidden" name="id_order"
                                                                value="<?= $row['id_order'] ?>">
                                                            <input type="hidden" name="status" value="2">
                                                            <button type="submit" class="dropdown-item small text-success">Đã
                                                                giao</button>
                                                        </form>
                                                    </li>
                                                    <li>
                                                        <form action="index.php?action=update_status" method="POST">
                                                            <input type="hidden" name="id_order"
                                                                value="<?= $row['id_order'] ?>">
                                                            <input type="hidden" name="status" value="3">
                                                            <button type="submit" class="dropdown-item small text-danger">Hủy
                                                                đơn</button>
                                                        </form>
                                                    </li>
                                                </ul>
                                            </div>

                                            <button class="btn btn-sm btn-danger fw-bold px-3"
                                                onclick="openDeleteOrderModal(<?= $row['id_order'] ?>)">
                                                Xóa
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="6" class="text-center p-5 text-muted">Chưa có đơn hàng nào.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
        <!--Modal xóa đơn hàng-->
        <div class="modal fade" id="modalDeleteOrder" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header bg-danger text-white">
                        <h5 class="modal-title">Xác nhận xóa</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form action="index.php?action=delete_order" method="POST">
                        <div class="modal-body">
                            <p>Bạn có chắc muốn xóa đơn hàng: <strong id="deleteOrderId"></strong>?</p>
                            <input type="hidden" name="id" id="deleteOrderIdInput">
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                            <button type="submit" class="btn btn-danger">Xóa</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </main>
</body>
<?php require "views/layout/footer.php"; ?>
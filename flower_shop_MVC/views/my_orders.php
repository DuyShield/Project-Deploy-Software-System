<?php require "views/layout/header.php"; ?>
<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold text-dark text-uppercase">ĐƠN HÀNG CỦA TÔI</h2>
    </div>

    <div class="table-responsive shadow-sm rounded">
        <table class="table table-hover align-middle bg-white mb-2">
            <thead class="table-color">
                <tr>
                    <th class="text-center" style="width: 10%">Mã đơn</th>
                    <th style="width: 20%">Ngày đặt</th>
                    <th style="width: 25%">Địa chỉ giao hàng</th>
                    <th style="width: 15%">Tổng tiền</th>
                    <th style="width: 15%">Trạng thái</th>
                    <th class="text-center" style="width: 15%">Thao tác</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($orders)): ?>
                    <?php foreach ($orders as $row): ?>
                        <tr>
                            <td class="text-center fw-bold"><?= $row['id_order'] ?></td>
                            <td><?= date("d/m/Y H:i", strtotime($row['created_at'])) ?></td>
                            <td>
                                <div class="small text-muted text-truncate" style="max-width: 200px;">
                                    <?= $row['address'] ?>
                                </div>
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
                                    <a href="index.php?action=order_detail&id=<?= $row['id_order'] ?>" 
                                       class="btn btn-sm btn-info fw-bold text-white px-3">
                                        Xem
                                    </a>

                                    <?php if ($row['status'] == 0): ?>
                                        <form action="index.php?action=update_status" method="POST" 
                                              onsubmit="return confirm('Bạn có chắc chắn muốn hủy đơn hàng này?')">
                                            <input type="hidden" name="id_order" value="<?= $row['id_order'] ?>">
                                            <input type="hidden" name="status" value="3">
                                            <button type="submit" class="btn btn-sm btn-outline-danger fw-bold px-3">
                                                Hủy
                                            </button>
                                        </form>
                                    <?php endif; ?>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="6" class="text-center p-5 text-muted">
                            Bạn chưa có đơn hàng nào. <br>
                            <a href="index.php?action=product" class="btn btn-primary mt-3">Tiếp tục mua sắm</a>
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
<?php require "views/layout/footer.php"; ?>
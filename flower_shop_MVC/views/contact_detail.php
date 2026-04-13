<?php require "views/layout/header.php"; ?>

<div class="container my-5">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="d-flex align-items-center justify-content-between mb-4">
                <h3 class="fw-bold text-success">
                    <i class="bi bi-clock-history"></i> Lịch sử liên hệ của bạn
                </h3>
                <a href="index.php?action=contact" class="btn btn-outline-success rounded-pill">
                    + Gửi liên hệ mới
                </a>
            </div>
            <?php if (empty($contacts)): ?>
                <div class="card shadow-sm border-0 text-center py-5">
                    <div class="card-body">
                        <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" fill="#000000" viewBox="0 0 256 256">
                            <path
                                d="M102,152a6,6,0,0,1-6,6H56a6,6,0,0,1,0-12H96A6,6,0,0,1,102,152Zm136-36v60a14,14,0,0,1-14,14H134v34a6,6,0,0,1-12,0V190H32a14,14,0,0,1-14-14V116A58.07,58.07,0,0,1,76,58h78V24a6,6,0,0,1,6-6h32a6,6,0,0,1,0,12H166V58h14A58.07,58.07,0,0,1,238,116ZM122,178V116a46,46,0,0,0-92,0v60a2,2,0,0,0,2,2Zm104-62a46.06,46.06,0,0,0-46-46H166v74a6,6,0,0,1-12,0V70H111.29A57.93,57.93,0,0,1,134,116v62h90a2,2,0,0,0,2-2Z">
                            </path>
                        </svg>
                        <p class="mt-3 text-muted">Bạn chưa có yêu cầu liên hệ nào.</p>
                    </div>
                </div>
            <?php else: ?>
                <div class="table-responsive shadow-sm rounded-4 bg-white p-3">
                    <table class="table table-hover align-middle">
                        <thead class="table-light">
                            <tr>
                                <th scope="col" class="ps-3">Ngày gửi</th>
                                <th scope="col">Nội dung đã gửi</th>
                                <th scope="col">Phản hồi từ Shop</th>
                                <th scope="col" class="text-center">Trạng thái</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($contacts as $item): ?>
                                <tr>
                                    <td class="ps-3 text-muted" style="width: 150px;">
                                        <?= date('d/m/Y', strtotime($item['created_at'])) ?>
                                        <br><small><?= date('H:i', strtotime($item['created_at'])) ?></small>
                                    </td>
                                    <td>
                                        <div class="text-truncate" style="max-width: 250px;" title="<?= $item['message'] ?>">
                                            <?= $item['message'] ?>
                                        </div>
                                    </td>
                                    <td>
                                        <?php if (!empty($item['reply'])): ?>
                                            <div
                                                class="p-2 bg-light rounded text-success small border-start border-success border-3">
                                                <strong>Shop:</strong> <?= $item['reply'] ?>
                                            </div>
                                        <?php else: ?>
                                            <span class="text-muted small"><em>Đang chờ phản hồi...</em></span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="text-center">
                                        <?php if (!empty($item['reply'])): ?>
                                            <span class="badge rounded-pill bg-success-subtle text-success px-3">Hoàn thành</span>
                                        <?php else: ?>
                                            <span class="badge rounded-pill bg-warning-subtle text-warning px-3">Đang xử lý</span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>
<?php require "views/layout/footer.php"; ?>
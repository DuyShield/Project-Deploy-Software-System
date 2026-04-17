<?php require "views/layout/header.php"; ?>
<div id="notification-container">
    <?php if (isset($_SESSION['success'])): ?>
        <div class="alert-box success">
            <i class="fas fa-check-circle"></i>
            <span><?= $_SESSION['success']; unset($_SESSION['success']); ?></span>
        </div>
    <?php endif; ?>

    <?php if (isset($_SESSION['error'])): ?>
        <div class="alert-box error">
            <i class="fas fa-exclamation-circle"></i>
            <span><?= $_SESSION['error']; unset($_SESSION['error']); ?></span>
        </div>
    <?php endif; ?>
</div>
<div class="container my-5">
    <h3 class="fw-bold mb-4">HÒM THƯ KHÁCH HÀNG</h3>
    <?php if (!empty($contacts)): ?>
        <?php foreach ($contacts as $c): ?>      
            <div class="card mb-3 border-0 shadow-sm" style="background-color: #f9fdfa; border-radius: 12px;">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-md-3 border-end">
                            <h6 class="fw-bold mb-1 text-dark"><?= $c['name'] ?></h6>
                            <p class="small text-muted mb-2"><?= $c['email'] ?></p>
                            <span class="badge <?= empty($c['reply']) ? 'bg-warning' : 'bg-success' ?> rounded-pill">
                                <?= empty($c['reply']) ? 'Chưa trả lời' : 'Đã trả lời' ?>
                            </span>
                        </div>

                        <div class="col-md-7 px-4">
                            <p class="mb-2"><strong>Khách viết:</strong> <?= nl2br($c['message']) ?></p>                           
                            <?php if (!empty($c['reply'])): ?>
                                <div class="mt-2 p-2 rounded-3" style="background-color: #eef7ee;">
                                    <p class="text-success mb-0 small">
                                        <strong>Shop trả lời:</strong> <?= nl2br($c['reply']) ?>
                                    </p>
                                </div>
                            <?php endif; ?>
                        </div>

                        <div class="col-md-2 d-flex flex-column gap-2">
                            <button type="button" class="btn btn-success fw-bold text-white border-0 py-2 w-100"
                                    data-bs-toggle="modal" data-bs-target="#replyModal<?= $c['id'] ?>"
                                    style="border-radius: 10px; font-size: 14px;">
                                Trả lời
                            </button>

                            <button type="button" class="btn btn-danger fw-bold text-white border-0 py-2 w-100"
                                    data-bs-toggle="modal" data-bs-target="#delModal<?= $c['id'] ?>"
                                    style="border-radius: 10px; font-size: 14px;">
                                Xóa
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            <!--Modal phản hồi-->
            <div class="modal fade" id="replyModal<?= $c['id'] ?>" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content border-0 shadow-lg" style="border-radius: 15px;">
                        <form action="index.php?action=send_reply" method="POST">
                            <div class="modal-header border-0 pb-0">
                                <h5 class="fw-bold text-success">Phản hồi khách hàng</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                            </div>
                            <div class="modal-body">
                                <input type="hidden" name="id" value="<?= $c['id'] ?>">
                                <div class="mb-3">
                                    <label class="fw-bold mb-2 small text-uppercase text-muted">Nội dung phản hồi:</label>
                                    <textarea name="reply" class="form-control border-0 bg-light" rows="4" 
                                              placeholder="Nhập nội dung cho khách..." 
                                              style="border-radius: 10px;"><?=$c['reply'] ?? '' ?></textarea>
                                </div>
                            </div>
                            <div class="modal-footer border-0 pt-0">
                                <button type="button" class="btn btn-light fw-bold" data-bs-dismiss="modal" style="border-radius: 8px;">Hủy</button>
                                <button type="submit" class="btn btn-warning fw-bold text-dark border-0 px-4" style="border-radius: 8px;">Lưu</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <!--Modal xóa liên hệ-->
            <div class="modal fade" id="delModal<?= $c['id'] ?>" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header bg-danger text-white">
                            <h5 class="modal-title">Xác nhận xóa</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <p>Bạn có chắc muốn xóa tin nhắn của <strong><?= $c['name'] ?></strong>?</p>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                            <a href="index.php?action=delete_contact&id=<?= $c['id'] ?>" class="btn btn-danger">Xóa</a>
                        </div>
                    </div>
                </div>
            </div>

        <?php endforeach; ?>
        <?php else: ?>
        <p class="text-center text-muted py-5">Hòm thư hiện đang trống.</p>
    <?php endif; ?>
</div>

<?php require "views/layout/footer.php"; ?>
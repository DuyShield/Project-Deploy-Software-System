<?php require "views/layout/header.php"; ?>

<div class="container mt-4 mb-5">
    <div class="card shadow-sm">
        <div class="card-body">
            <h3 class="card-title mb-3">THÔNG BÁO CỦA BẠN</h3>
            <?php if (!empty($notifications)): ?>
                <div class="notification-list">
                    <?php foreach ($notifications as $notif): ?>
                        <?php
                        $severity = $notif['severity'] ?? 'info';
                        $content = $notif['content'];
                        if (empty($notif['severity']) && preg_match('/^\[severity:(info|warning|danger|success)\](.*)$/s', $content, $matches)) {
                            $severity = $matches[1];
                            $content = $matches[2];
                        }
                        ?>
                        <!-- Hiển thị thông báo với định dạng dựa trên mức độ nghiêm trọng -->
                        <div class="alert alert-<?= $severity ?> mb-3 rounded-3">
                            <div class="d-flex justify-content-between align-items-start">
                                <div>
                                    <h6 class="mb-1"><?= $notif['title'] ?></h6>
                                    <p class="mb-1 text-secondary small"><?= $content ?></p>
                                </div>
                                <small class="text-muted"><?= $notif['created_at'] ?></small>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <div class="alert alert-info mb-0">Bạn hiện chưa có thông báo mới.</div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php require "views/layout/footer.php"; ?>
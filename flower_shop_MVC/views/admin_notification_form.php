<?php require "views/layout/header.php"; ?>
<!-- Hiển thị thông báo thành công hoặc lỗi -->
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

<div class="container mt-4 mb-5">
    <div class="card shadow-sm">
        <div class="card-body">
            <form method="POST" action="index.php?action=send_notification" class="row g-3" id="adminNotificationForm">
                <div class="col-md-3">
                    <label class="form-label fw-bold">Loại thông báo</label>
                    <select name="notification_type" id="notification_type" class="form-select">
                        <option value="global">Toàn bộ người dùng</option>
                        <option value="personal">Tới người dùng cụ thể</option>
                    </select>
                </div>
                <div class="col-md-3" id="targetUserGroup" style="display:none;">
                    <label class="form-label fw-bold">Người nhận</label>
                    <select name="user_id" class="form-select">
                        <option value="">Chọn người dùng</option>
                        <?php if (!empty($users)): ?>
                            <?php foreach ($users as $userOption): ?>
                                <option value="<?= $userOption['id'] ?>"><?= htmlspecialchars($userOption['username']) ?>
                                </option>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label fw-bold">Mức cảnh báo</label>
                    <select name="severity" class="form-select">
                        <option value="info">Thông tin</option>
                        <option value="warning">Cảnh báo</option>
                        <option value="danger">Nguy hiểm</option>
                        <option value="success">Thành công</option>
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-bold">Tiêu đề thông báo</label>
                    <input type="text" name="title" class="form-control" placeholder="Tiêu đề" required>
                </div>
                <div class="col-md-8">
                    <label class="form-label fw-bold">Nội dung thông báo</label>
                    <input type="text" name="message" class="form-control" placeholder="Nội dung" required>
                </div>
                <div class="col-md-2 d-grid align-self-end">
                    <button type="submit" class="btn btn-success">Gửi thông báo</button>
                </div>
            </form>
            <script>
                document.getElementById('notification_type').addEventListener('change', function () {
                    var targetUserGroup = document.getElementById('targetUserGroup');
                    targetUserGroup.style.display = this.value === 'personal' ? 'block' : 'none';
                });
            </script>
            <div class="mt-4">
                <a href="index.php?action=home" class="btn btn-outline-secondary btn-sm">Quay lại Home</a>
            </div>
        </div>
    </div>
</div>

<?php require "views/layout/footer.php"; ?>
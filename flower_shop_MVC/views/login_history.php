<?php require "views/layout/header.php"; ?>

<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4 flex-column flex-md-row gap-2">
        <h2 class="text-uppercase fw-bold mb-0">
            Lịch sử đăng nhập - <?php echo htmlspecialchars($user['username']); ?>
        </h2>
        <div class="d-flex gap-2 flex-wrap">
            <a href="index.php?action=account_management" class="btn btn-outline-success">← Quay lại</a>
            <a href="index.php?action=dashboard" class="btn btn-outline-success">Dashboard</a>
        </div>
    </div>

    <div class="card shadow">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-color">
                        <tr>
                            <th>Thời gian</th>
                            <th>IP Address</th>
                            <th>User Agent</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($history)): ?>
                            <?php foreach ($history as $record): ?>
                                <tr>
                                    <td><?php echo date('d/m/Y H:i:s', strtotime($record['login_at'])); ?></td>
                                    <td><?php echo htmlspecialchars($record['ip_address'] ?? 'N/A'); ?></td>
                                    <td>
                                        <small class="text-muted">
                                            <?php echo htmlspecialchars(substr($record['user_agent'] ?? '', 0, 100)); ?>
                                            <?php if (strlen($record['user_agent'] ?? '') > 100): ?>...<?php endif; ?>
                                        </small>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="3" class="text-center">Không có lịch sử đăng nhập.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?php require "views/layout/footer.php"; ?>
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

<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4 flex-column flex-md-row gap-2">
        <h2 class="text-uppercase fw-bold mb-0">Quản lý tài khoản</h2>
        <div class="d-flex gap-2 flex-wrap">
            <a href="index.php?action=dashboard" class="btn btn-outline-success">Dashboard</a>
            <a href="index.php?action=product_management" class="btn btn-outline-success">Sản phẩm</a>
            <a href="index.php?action=order_lists" class="btn btn-outline-success">Đơn hàng</a>
            <a href="index.php?action=account_management" class="btn btn-success">Quản Lý Tài Khoản</a>
            <a href="index.php?action=banner_sale" class="btn btn-outline-success">Banner & Sale</a>
        </div>
    </div>

    <div class="table-responsive shadow-sm rounded">
        <table class="table table-hover align-middle bg-white">
            <thead class="table-color">
                <tr>
                    <th>ID</th>
                    <th>Tên đăng nhập</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th>Đăng nhập cuối</th>
                    <th class="text-center">Thao tác</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($users)): ?>
                    <?php foreach ($users as $user): ?>
                        <tr>
                            <td><?php echo $user['id']; ?></td>
                            <td><?php echo $user['username']; ?></td>
                            <td><?php echo $user['email']; ?></td>
                            <td>
                                <span class="badge bg-<?php echo $user['role'] == 'admin' ? 'danger' : 'primary'; ?>">
                                    <?php echo ucfirst($user['role']); ?>
                                </span>
                            </td>
                            <td><?php echo $user['last_login_at'] ? date('d/m/Y H:i', strtotime($user['last_login_at'])) : 'Chưa đăng nhập'; ?></td>
                            <td class="text-center">
                                <div class="d-flex justify-content-center gap-2">
                                    <!-- Nút thay đổi role -->
                                    <button class="btn btn-sm btn-warning" data-bs-toggle="modal"
                                            data-bs-target="#roleModal"
                                            onclick="setRoleData(<?= $user['id'] ?>, '<?= $user['role'] ?>', '<?= $user['username'] ?>')">
                                        <i class="fas fa-user-shield"></i> Role
                                    </button>
                                    <!-- Nút đổi mật khẩu -->
                                    <button class="btn btn-sm btn-info" data-bs-toggle="modal"
                                            data-bs-target="#passwordModal"
                                            onclick="setPasswordData(<?= $user['id'] ?>, '<?= $user['username'] ?>')">
                                        <i class="fas fa-key"></i> Mật khẩu
                                    </button>
                                    <!-- Nút xem lịch sử -->
                                    <a href="index.php?action=view_login_history&user_id=<?= $user['id'] ?>" class="btn btn-sm btn-secondary">
                                        <i class="fas fa-history"></i> Lịch sử
                                    </a>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="6" class="text-center">Không có tài khoản nào.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Modal thay đổi role -->
<div class="modal fade" id="roleModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Thay đổi Role</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="index.php?action=change_user_role" method="POST">
                <div class="modal-body">
                    <p>Thay đổi role cho user: <strong id="roleUsername"></strong></p>
                    <input type="hidden" name="user_id" id="roleUserId">
                    <div class="mb-3">
                        <label class="form-label">Role mới</label>
                        <select name="role" id="roleSelect" class="form-select" required>
                            <option value="user">User</option>
                            <option value="admin">Admin</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                    <button type="submit" class="btn btn-warning">Cập nhật Role</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal đổi mật khẩu -->
<div class="modal fade" id="passwordModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Đổi mật khẩu</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="index.php?action=change_user_password" method="POST">
                <div class="modal-body">
                    <p>Đổi mật khẩu cho user: <strong id="passwordUsername"></strong></p>
                    <input type="hidden" name="user_id" id="passwordUserId">
                    <div class="mb-3">
                        <label class="form-label">Mật khẩu mới</label>
                        <input type="password" name="new_password" class="form-control" required
                               placeholder="Ít nhất 8 ký tự, có chữ và số">
                        <small class="form-text text-muted">Mật khẩu phải có ít nhất 8 ký tự và bao gồm cả chữ lẫn số.</small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                    <button type="submit" class="btn btn-info">Đổi mật khẩu</button>
                </div>
            </form>
        </div>
    </div>
</div>
<?php require "views/layout/footer.php"; ?>
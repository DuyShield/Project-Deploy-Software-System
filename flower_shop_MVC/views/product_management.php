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
        <h2 class="text-uppercase fw-bold mb-0">
            <?php if (isset($isSearch)) {
                echo "Kết quả tìm kiếm cho: <b>$keyword</b>";
            } else {
                echo "Quản lý sản phẩm";
            } ?>
        </h2>
        <!-- Thanh điều hướng -->
        <div class="d-flex gap-2 flex-wrap">
            <a href="index.php?action=dashboard" class="btn btn-outline-success">Dashboard</a>
            <a href="index.php?action=product_management" class="btn btn-success">Sản phẩm</a>
            <a href="index.php?action=order_lists" class="btn btn-outline-success">Đơn hàng</a>
            <a href="index.php?action=account_management" class="btn btn-outline-success">Quản Lý Tài Khoản</a>
            <a href="index.php?action=banner_sale" class="btn btn-outline-success">Banner & Sale</a>
        </div>
    </div>
    <!--Thanh tìm kiếm sản phẩm -->
    <div class="card mb-4 shadow-sm">
        <div class="card-body">
            <form class="row g-2 g-sm-3" method="GET" action="index.php">
                <input type="hidden" name="action" value="search_product_management">
                <div class="col-12 col-md-6 col-lg-3">
                    <label class="form-label">Từ khóa</label>
                    <input type="text" name="keyword" class="form-control" placeholder="Tên sản phẩm..."
                        value="<?= isset($keyword) ? $keyword : '' ?>">
                </div>
                <!--Bộ lọc nâng cao-->
                <div class="col-12 col-md-6 col-lg-3">
                    <label class="form-label">Danh mục</label>
                    <select name="category" class="form-select">
                        <option value="">Tất cả danh mục</option>
                        <?php if (!empty($categories))
                            foreach ($categories as $cate): ?>
                                <option value="<?php echo $cate['id_category']; ?>" <?= (isset($category) && $category == $cate['id_category']) ? 'selected' : '' ?>>
                                    <?php echo $cate['name_category']; ?>
                                </option>
                            <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-6 col-md-3 col-lg-2">
                    <label class="form-label">Giá từ</label>
                    <input type="number" name="price_min" class="form-control" min="0"
                        value="<?= isset($price_min) ? $price_min : '' ?>">
                </div>
                <div class="col-6 col-md-3 col-lg-2">
                    <label class="form-label">Giá đến</label>
                    <input type="number" name="price_max" class="form-control" min="0"
                        value="<?= isset($price_max) ? $price_max : '' ?>">
                </div>
                <div class="col-12 col-md-6 col-lg-2">
                    <label class="form-label">Tình trạng</label>
                    <select name="stock_status" class="form-select">
                        <option value="">Tất cả kho</option>
                        <option value="in_stock" <?= (isset($stock_status) && $stock_status === 'in_stock') ? 'selected' : '' ?>>Còn hàng</option>
                        <option value="out_of_stock" <?= (isset($stock_status) && $stock_status === 'out_of_stock') ? 'selected' : '' ?>>Hết hàng</option>
                    </select>
                </div>
                <div class="col-12 col-md-6 col-lg-2 d-flex align-items-end">
                    <button type="submit" class="btn btn-secondary w-100">Lọc sản phẩm</button>
                </div>
            </form>
        </div>
    </div>

    <div class="mb-3 d-flex justify-content-between align-items-center">
        <h4 class="mb-0">Danh sách sản phẩm</h4>
        <button class="btn btn-success shadow-sm d-flex align-items-center gap-2" data-bs-toggle="modal"
            data-bs-target="#modalProduct">
            <i class="bi bi-plus-circle"></i> Thêm sản phẩm mới
        </button>
    </div>

    <div class="table-responsive shadow-sm rounded">
        <table class="table table-hover align-middle bg-white">
            <thead class="table-color"> 
                <tr>
                    <th>ID</th>
                    <th>Hình ảnh</th>
                    <th>Tên sản phẩm</th>
                    <th>Danh mục</th>
                    <th>Giá tiền</th>
                    <th>Kho</th>
                    <th>Mô tả sản phẩm</th>
                    <th class="text-center">Thao tác</th>
                </tr>
            </thead>
            <!-- Dữ liệu sản phẩm hiển thị ở đây-->
            <tbody>
                <?php
                if (!empty($products))
                    foreach ($products as $row) { ?>
                        <tr>
                            <td><?php echo $row['id_product'] ?></td>
                            <td><img src="assets/images/image_products/<?php echo $row['image'] ?>" class="rounded image_icon"
                                    alt="product" style="width: 50px; height: 50px; object-fit: cover;"></td>
                            <td><strong><?php echo $row['name_product'] ?></strong></td>
                            <td><?php echo $row['name_category'] ?? $row['id_category'] ?></td>
                            <td class="text-danger fw-bold"><?php echo number_format($row['price_product']) ?>đ</td>
                            <td>
                                <?php $stock = isset($row['stock']) ? (int) $row['stock'] : 0; ?>
                                <span
                                    class="badge <?= $stock <= 0 ? 'bg-danger' : ($stock <= 5 ? 'bg-warning' : 'bg-success') ?>">
                                    <?= $stock ?> cái
                                </span>
                            </td>
                            <td><small
                                    class="text-muted"><?php echo mb_strimwidth($row['description_product'], 0, 50, "...") ?></small>
                            </td>
                            <td class="text-center">
                                <div class="d-flex justify-content-center gap-2">
                                    <button class="btn btn-sm btn-warning" data-bs-toggle="modal"
                                        data-bs-target="#modalModify"
                                        onclick="openEditModal(<?= $row['id_product'] ?>, '<?= addslashes($row['name_product']) ?>', <?= $row['id_category'] ?>, <?= $row['price_product'] ?>, <?= $row['stock'] ?? 0 ?>, '<?= addslashes($row['description_product']) ?>', '<?= $row['image'] ?>')">
                                        <i class="bi bi-pencil"></i> Sửa
                                    </button>
                                    <button type="button" class="btn btn-sm btn-danger"
                                        onclick="openDeleteModal(<?php echo $row['id_product']; ?>, '<?php echo addslashes($row['name_product']); ?>')">
                                        <i class="bi bi-trash"></i> Xóa
                                    </button>
                                </div>
                            </td>
                        </tr>
                    <?php } ?>
            </tbody>
        </table>
    </div>
</div>
<!--Modal thêm sản phẩm-->
<div class="modal fade" id="modalProduct" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <!--Header-->
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title">Thông tin sản phẩm</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <!--Form-->
            <form action="index.php?action=save_product" method="POST" enctype="multipart/form-data">
                <div class="modal-body">
                    <div class="row">
                        <!--Tên-->
                        <div class="col-md-12">
                            <label class="form-label">Tên sản phẩm</label>
                            <input type="text" name="name" class="form-control" required>
                        </div>
                        <!--Danh mục-->
                        <div class="col-md-12">
                            <label class="form-label">Danh mục</label>
                            <select name="category" class="form-select">
                                <?php if (!empty($categories))
                                    foreach ($categories as $cate): ?>
                                        <option value="<?php echo $cate['id_category']; ?>">
                                            <?php echo $cate['name_category']; ?>
                                        </option>
                                    <?php endforeach; ?>
                            </select>
                        </div>
                        <!--Giá-->
                        <div class="col-md-12">
                            <label class="form-label">Giá (VNĐ)</label>
                            <input type="number" name="price" class="form-control" required>
                        </div>
                        <!--Kho-->
                        <div class="col-md-12">
                            <label class="form-label">Số lượng tồn kho</label>
                            <input type="number" name="stock" class="form-control" value="0" min="0" required>
                        </div>
                        <!--Mô tả-->
                        <div class="col-md-12">
                            <label class="form-label">Mô tả sản phẩm</label>
                            <textarea name="description" class="form-control" rows="4"
                                placeholder="Nhập mô tả chi tiết sản phẩm..." required></textarea>
                        </div>
                        <!--Ảnh-->
                        <div class="col-md-12">
                            <label class="form-label">Hình ảnh</label>
                            <input type="file" name="image" class="form-control">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                    <button type="submit" class="btn btn-primary">Lưu dữ liệu</button>
                </div>
            </form>
        </div>
    </div>
</div>
<!--Modal xóa sản phẩm-->
<div class="modal fade" id="modalDelete" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title">Xác nhận xóa</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="index.php?action=del_product" method="POST">
                <div class="modal-body">
                    <p>Bạn có chắc muốn xóa sản phẩm: <strong id="deleteProductName"></strong>?</p>
                    <input type="hidden" name="id_product" id="deleteProductId">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                    <button type="submit" class="btn btn-danger">Xóa</button>
                </div>
            </form>
        </div>
    </div>
</div>
<!--Modal sửa sản phẩm-->
<div class="modal fade" id="modalModify" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <!--Header-->
            <div class="modal-header bg-warning text-white">
                <h5 class="modal-title">Chỉnh sửa sản phẩm</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <!--Form-->
            <form action="index.php?action=up_product" method="POST" enctype="multipart/form-data">
                <div class="modal-body">
                    <div class="row g-3">
                        <!--Id ẩn-->
                        <input type="hidden" name="id_product" id="edit_id">
                        <!--Tên-->
                        <div class="col-md-12">
                            <label class="form-label">Tên sản phẩm</label>
                            <input type="text" name="name" id="edit_name" class="form-control" required>
                        </div>
                        <!--Danh mục-->
                        <div class="col-md-12">
                            <label class="form-label">Danh mục</label>
                            <select name="category" id="edit_category" class="form-select">
                                <?php foreach ($categories as $cat): ?>
                                    <option value="<?= $cat['id_category'] ?>">
                                        <?= $cat['name_category'] ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <!--Giá-->
                        <div class="col-md-12">
                            <label class="form-label">Giá</label>
                            <input type="number" name="price" id="edit_price" class="form-control" required>
                        </div>
                        <!--Kho-->
                        <div class="col-md-12">
                            <label class="form-label">Số lượng tồn kho</label>
                            <input type="number" name="stock" id="edit_stock" class="form-control" min="0" required>
                        </div>
                        <!--Mô tả-->
                        <div class="col-md-12">
                            <label class="form-label">Mô tả</label>
                            <textarea name="description" id="edit_description" class="form-control" rows="4"></textarea>
                        </div>
                        <!--Ảnh-->
                        <div class="col-md-12">
                            <label class="form-label">Hình ảnh</label>
                            <input type="file" name="image" class="form-control" onchange="previewImage(event)">
                            <!--Ảnh cũ-->
                            <div class="mt-2">
                                <small>Ảnh hiện tại:</small><br>
                                <img id="old_image" src="" width="200" class="rounded border">
                            </div>
                            <!--Preview ảnh mới-->
                            <div class="mt-2">
                                <small>Ảnh mới:</small><br>
                                <img id="preview_image" width="200" class="rounded border d-none">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                    <button type="submit" class="btn btn-primary">Lưu thay đổi</button>
                </div>
            </form>
        </div>
    </div>
</div>
</body>
<?php require "views/layout/footer.php"; ?>
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
<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4 flex-column flex-md-row gap-2">

        <h2 class="text-uppercase fw-bold mb-0">
            <?php if (isset($isSearch)) {
                echo "Kết quả tìm kiếm cho: <b>$keyword</b>";
            } else {
                echo "Quản lý sản phẩm";
            } ?>
        </h2>
        <button class="btn btn-success btn-mobile-full w-md-auto" data-bs-toggle="modal" data-bs-target="#modalProduct">
            Thêm sản phẩm mới
        </button>
    </div>
    <!--Thanh tìm kiếm sản phẩm -->
    <div class="card mb-4 shadow-sm">
        <div class="card-body">
            <form class="row g-2 g-sm-3" method="GET" action="index.php">
                <div class="col-12 col-sm-10">
                    <input type="hidden" name="action" value="search_product_management">
                    <input type="text" name="keyword" class="form-control" placeholder="Nhập tên sản phẩm cần tìm...">
                </div>
                <div class="col-12 col-sm-2">
                    <button type="submit" class="btn btn-secondary w-100">Tìm kiếm</button>
                </div>
            </form>
        </div>
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
                    <th>Mô tả sản phẩm</th>
                    <th class="text-center">Thao tác</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if (!empty($products))
                    foreach ($products as $row) { ?>
                        <tr>
                            <td><?php echo $row['id_product'] ?></td>
                            <td><img src="assets/images/image_products/<?php echo $row['image'] ?>" class="rounded image_icon" alt="product">
                            </td>
                            <td><strong><?php echo $row['name_product'] ?></strong></td>
                            <td><?php echo $row['name_category'] ?? $row['id_category'] ?></td>
                            <td class="text-danger fw-bold"><?php echo number_format($row['price_product']) ?>đ</td>
                            <td><?php echo $row['description_product'] ?></td>
                            <td class="text-center">
                                <div class="d-flex justify-content-center gap-2">
                                    <button class="btn btn-sm btn-warning" data-bs-toggle="modal" data-bs-target="#modalModify"
                                        onclick="openEditModal(
                                        <?= $row['id_product'] ?>,
                                        '<?= addslashes($row['name_product']) ?>',
                                        <?= $row['id_category'] ?>,
                                        <?= $row['price_product'] ?>,
                                        '<?= addslashes($row['description_product']) ?>',
                                        '<?= $row['image'] ?>'
                                         )">
                                        Sửa
                                    </button>
                                    <button type="button" class="btn btn-sm btn-danger w-100"
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
                                        <option value="<?php echo $cate['id_category']; ?>"><?php echo $cate['name_category']; ?>
                                        </option>
                                    <?php endforeach; ?>
                            </select>
                        </div>
                        <!--Giá-->
                        <div class="col-md-12">
                            <label class="form-label">Giá (VNĐ)</label>
                            <input type="number" name="price" class="form-control" required>
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
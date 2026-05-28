<?php
require "views/layout/header.php";
?>
<div class="container py-5">
    <div class="d-flex justify-content-between align-items-start mb-4 flex-column flex-md-row gap-3">
        <div>
            <h1 class="h2 fw-bold">DASHBOARD QUẢN TRỊ</h1>
            <p class="text-muted mb-0">Tổng quan quản lý sản phẩm, giao hàng và kho hàng.</p>
        </div>
        <div class="d-flex gap-2 flex-wrap">
            <a href="index.php?action=dashboard" class="btn btn-success">Dashboard</a>
            <a href="index.php?action=product_management" class="btn btn-outline-success">Sản phẩm</a>
            <a href="index.php?action=order_lists" class="btn btn-outline-success">Đơn hàng</a>
            <a href="index.php?action=account_management" class="btn btn-outline-success">Quản Lý Tài Khoản</a>
            <a href="index.php?action=banner_sale" class="btn btn-outline-success">Banner & Sale</a>
        </div>
    </div>

    <div class="row g-4 mb-4">
        <div class="col-md-3">
            <div class="card shadow-sm h-100">
                <div class="card-body">
                    <h5 class="card-title">Sản phẩm</h5>
                    <p class="display-6 fw-bold mb-1"><?= number_format($totalProducts) ?></p>
                    <p class="text-muted mb-0">Tổng số sản phẩm trong hệ thống.</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card shadow-sm h-100">
                <div class="card-body">
                    <h5 class="card-title">Đơn hàng</h5>
                    <p class="display-6 fw-bold mb-1"><?= number_format($totalOrders) ?></p>
                    <p class="text-muted mb-0">Đơn đã ghi nhận.</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card shadow-sm h-100">
                <div class="card-body">
                    <h5 class="card-title">Tồn kho</h5>
                    <p class="display-6 fw-bold mb-1"><?= number_format($totalStock) ?></p>
                    <p class="text-muted mb-0">Tổng số đơn vị sản phẩm trong kho.</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card shadow-sm h-100">
                <div class="card-body">
                    <h5 class="card-title">Danh mục</h5>
                    <p class="display-6 fw-bold mb-1"><?= count($categoryProductCounts) ?></p>
                    <p class="text-muted mb-0">Số danh mục sản phẩm đã phân loại.</p>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4 mb-4">
        <div class="col-lg-4">
            <div class="list-group shadow-sm">
                <a href="index.php?action=product_management" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                    Quản lý sản phẩm
                    <span class="badge bg-success rounded-pill">Sản phẩm</span>
                </a>
                <a href="index.php?action=order_lists" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                    Quản lý giao hàng
                    <span class="badge bg-success rounded-pill">Giao hàng</span>
                </a>
                <a href="index.php?action=product_management" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                    Quản lý kho
                    <span class="badge bg-success rounded-pill">Kho</span>
                </a>
                <a href="index.php?action=contact_list" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                    Quản lý liên hệ
                    <span class="badge bg-success rounded-pill">Liên hệ</span>
                </a>
            </div>
        </div>

        <div class="col-lg-8">
            <div class="row g-4">
                <div class="col-md-6">
                    <div class="card shadow-sm h-100">
                        <div class="card-body">
                            <h5 class="card-title">Kho: phân bổ theo danh mục</h5>
                            <canvas id="categoryChart" height="250"></canvas>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card shadow-sm h-100">
                        <div class="card-body">
                            <h5 class="card-title">Giao hàng: trạng thái đơn</h5>
                            <canvas id="statusChart" height="250"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const categoryLabels = <?= json_encode(array_keys($categoryStockCounts)) ?>;
    const categoryData = <?= json_encode(array_values($categoryStockCounts)) ?>;
    const statusLabels = <?= json_encode(array_keys($statusCounts)) ?>;
    const statusData = <?= json_encode(array_values($statusCounts)) ?>;
    // Biểu đồ số lượng sản phẩm theo danh mục
    const categoryCtx = document.getElementById('categoryChart');
    if (categoryCtx) {
        new Chart(categoryCtx, {
            type: 'doughnut',
            data: {
                labels: categoryLabels,
                datasets: [{
                    data: categoryData,
                    backgroundColor: ['#198754', '#20c997', '#0dcaf0', '#ffc107', '#0d6efd', '#6f42c1'],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'bottom'
                    }
                }
            }
        });
    }
    // Biểu đồ trạng thái đơn hàng
    const statusCtx = document.getElementById('statusChart');
    if (statusCtx) {
        new Chart(statusCtx, {
            type: 'bar',
            data: {
                labels: statusLabels,
                datasets: [{
                    label: 'Số đơn hàng',
                    data: statusData,
                    backgroundColor: ['#0d6efd', '#198754', '#ffc107', '#6c757d']
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            precision: 0
                        }
                    }
                },
                plugins: {
                    legend: {
                        display: false
                    }
                }
            }
        });
    }
</script>

<?php require "views/layout/footer.php"; ?>

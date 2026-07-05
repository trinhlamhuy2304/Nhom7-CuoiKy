<?php
/**
 * DASHBOARD QUẢN TRỊ
 * TODO cho thành viên: bổ sung biểu đồ thống kê (Chart.js) - điểm khuyến khích
 */
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/includes/auth.php';

$page_title = 'Dashboard';

$tong_khoa_hoc  = $pdo->query("SELECT COUNT(*) FROM khoa_hoc")->fetchColumn();
$tong_danh_muc  = $pdo->query("SELECT COUNT(*) FROM danh_muc")->fetchColumn();
$tong_dang_ky   = $pdo->query("SELECT COUNT(*) FROM hoc_vien_dang_ky")->fetchColumn();

require_once __DIR__ . '/includes/admin_header.php';
?>

<div class="row g-4">
    <div class="col-md-4">
        <div class="card text-white bg-primary shadow-sm">
            <div class="card-body d-flex justify-content-between align-items-center">
                <div>
                    <h2 class="fw-bold mb-0"><?= (int) $tong_khoa_hoc ?></h2>
                    <p class="mb-0">Khóa học</p>
                </div>
                <i class="bi bi-journal-bookmark fs-1"></i>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card text-white bg-success shadow-sm">
            <div class="card-body d-flex justify-content-between align-items-center">
                <div>
                    <h2 class="fw-bold mb-0"><?= (int) $tong_danh_muc ?></h2>
                    <p class="mb-0">Danh mục</p>
                </div>
                <i class="bi bi-tags fs-1"></i>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card text-white bg-warning shadow-sm">
            <div class="card-body d-flex justify-content-between align-items-center">
                <div>
                    <h2 class="fw-bold mb-0"><?= (int) $tong_dang_ky ?></h2>
                    <p class="mb-0">Lượt đăng ký</p>
                </div>
                <i class="bi bi-person-check fs-1"></i>
            </div>
        </div>
    </div>
</div>

<div class="alert alert-info mt-4">
    <i class="bi bi-info-circle"></i>
    Đây là bộ khung (skeleton) quản trị cơ bản. Các thành viên có thể bổ sung: biểu đồ thống kê,
    phân trang, lọc dữ liệu, upload ảnh, xuất Excel/PDF... để cộng điểm khuyến khích.
</div>

<?php require_once __DIR__ . '/includes/admin_footer.php'; ?>

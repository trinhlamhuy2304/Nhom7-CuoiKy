<?php
/**
 * DASHBOARD QUẢN TRỊ
 * Đã bổ sung biểu đồ thống kê bằng Chart.js (điểm khuyến khích)
 */
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/includes/auth.php';

$page_title = 'Dashboard';

$tong_khoa_hoc  = $pdo->query("SELECT COUNT(*) FROM khoa_hoc")->fetchColumn();
$tong_danh_muc  = $pdo->query("SELECT COUNT(*) FROM danh_muc")->fetchColumn();
$tong_dang_ky   = $pdo->query("SELECT COUNT(*) FROM hoc_vien_dang_ky")->fetchColumn();

// ---- Dữ liệu cho biểu đồ 1: Số lượng khóa học theo danh mục ----
$khoa_hoc_theo_danh_muc = $pdo->query(
    "SELECT dm.ten_danh_muc, COUNT(kh.id) AS so_luong
     FROM danh_muc dm
     LEFT JOIN khoa_hoc kh ON kh.danh_muc_id = dm.id
     GROUP BY dm.id, dm.ten_danh_muc
     ORDER BY dm.ten_danh_muc ASC"
)->fetchAll();

// ---- Dữ liệu cho biểu đồ 2: Lượt đăng ký theo 7 ngày gần nhất ----
$dang_ky_theo_ngay = $pdo->query(
    "SELECT DATE(ngay_dang_ky) AS ngay, COUNT(*) AS so_luong
     FROM hoc_vien_dang_ky
     WHERE ngay_dang_ky >= DATE_SUB(CURDATE(), INTERVAL 6 DAY)
     GROUP BY DATE(ngay_dang_ky)
     ORDER BY ngay ASC"
)->fetchAll();

// Lấp đầy đủ 7 ngày (kể cả ngày không có đăng ký) để biểu đồ không bị thiếu trục
$nhan_ngay = [];
$so_luong_ngay = [];
$map_dang_ky = [];
foreach ($dang_ky_theo_ngay as $row) {
    $map_dang_ky[$row['ngay']] = (int) $row['so_luong'];
}
for ($i = 6; $i >= 0; $i--) {
    $ngay = date('Y-m-d', strtotime("-$i day"));
    $nhan_ngay[] = date('d/m', strtotime($ngay));
    $so_luong_ngay[] = $map_dang_ky[$ngay] ?? 0;
}

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

<div class="row g-4 mt-1">
    <div class="col-lg-6">
        <div class="card shadow-sm h-100">
            <div class="card-header bg-white">
                <i class="bi bi-bar-chart-line"></i> Khóa học theo danh mục
            </div>
            <div class="card-body">
                <canvas id="chartDanhMuc" height="220"></canvas>
            </div>
        </div>
    </div>
    <div class="col-lg-6">
        <div class="card shadow-sm h-100">
            <div class="card-header bg-white">
                <i class="bi bi-graph-up"></i> Lượt đăng ký 7 ngày gần nhất
            </div>
            <div class="card-body">
                <canvas id="chartDangKy" height="220"></canvas>
            </div>
        </div>
    </div>
</div>

<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
new Chart(document.getElementById('chartDanhMuc'), {
    type: 'bar',
    data: {
        labels: <?= json_encode(array_column($khoa_hoc_theo_danh_muc, 'ten_danh_muc'), JSON_UNESCAPED_UNICODE) ?>,
        datasets: [{
            label: 'Số khóa học',
            data: <?= json_encode(array_map('intval', array_column($khoa_hoc_theo_danh_muc, 'so_luong'))) ?>,
            backgroundColor: '#0d6efd'
        }]
    },
    options: {
        responsive: true,
        plugins: { legend: { display: false } },
        scales: { y: { beginAtZero: true, ticks: { stepSize: 1 } } }
    }
});

new Chart(document.getElementById('chartDangKy'), {
    type: 'line',
    data: {
        labels: <?= json_encode($nhan_ngay, JSON_UNESCAPED_UNICODE) ?>,
        datasets: [{
            label: 'Lượt đăng ký',
            data: <?= json_encode($so_luong_ngay) ?>,
            borderColor: '#198754',
            backgroundColor: 'rgba(25,135,84,0.15)',
            tension: 0.3,
            fill: true
        }]
    },
    options: {
        responsive: true,
        plugins: { legend: { display: false } },
        scales: { y: { beginAtZero: true, ticks: { stepSize: 1 } } }
    }
});
</script>

<?php require_once __DIR__ . '/includes/admin_footer.php'; ?>

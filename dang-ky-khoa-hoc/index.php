<?php
/**
 * TRANG CHỦ
 * Yêu cầu: Trang chủ, Danh sách dữ liệu, Tìm kiếm
 * TODO cho thành viên: thêm phân trang, lọc theo danh mục, sắp xếp giá... (điểm khuyến khích)
 */
require_once __DIR__ . '/config/database.php';

$page_title = 'Trang chủ';

// ---- Tìm kiếm khóa học theo tên (yêu cầu 4.1) ----
$keyword     = isset($_GET['q']) ? trim($_GET['q']) : '';
$danh_muc_id = isset($_GET['danh_muc_id']) ? (int) $_GET['danh_muc_id'] : 0;

// Lấy danh sách danh mục để hiển thị bộ lọc
$danh_muc_list = $pdo->query("SELECT * FROM danh_muc ORDER BY ten_danh_muc ASC")->fetchAll();

$sql = "SELECT kh.*, dm.ten_danh_muc
        FROM khoa_hoc kh
        JOIN danh_muc dm ON kh.danh_muc_id = dm.id
        WHERE kh.trang_thai = 1";

$params = [];
if ($keyword !== '') {
    $sql .= " AND kh.ten_khoa_hoc LIKE :keyword";
    $params[':keyword'] = '%' . $keyword . '%';
}
if ($danh_muc_id > 0) {
    $sql .= " AND kh.danh_muc_id = :danh_muc_id";
    $params[':danh_muc_id'] = $danh_muc_id;
}

// ---- Sắp xếp theo giá (điểm khuyến khích) ----
$sort = isset($_GET['sort']) ? $_GET['sort'] : '';
switch ($sort) {
    case 'gia_tang':
        $sql .= " ORDER BY kh.hoc_phi ASC";
        break;
    case 'gia_giam':
        $sql .= " ORDER BY kh.hoc_phi DESC";
        break;
    default:
        $sql .= " ORDER BY kh.created_at DESC";
        break;
}
$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$danh_sach_khoa_hoc = $stmt->fetchAll();

require_once __DIR__ . '/includes/header.php';
?>

<!-- ================= BANNER ================= -->
<section class="banner text-white text-center d-flex align-items-center">
    <div class="container">
        <h1 class="fw-bold display-5">Đăng Ký Khóa Học Trực Tuyến</h1>
        <p class="lead">Khám phá hàng loạt khóa học chất lượng và đăng ký chỉ trong vài bước đơn giản.</p>
        <a href="#danh-sach-khoa-hoc" class="btn btn-warning btn-lg mt-2">Xem khóa học ngay</a>
    </div>
</section>

<!-- ================= DANH SÁCH KHÓA HỌC ================= -->
<section class="container my-5" id="danh-sach-khoa-hoc">
    <h2 class="text-center mb-4 fw-bold">Danh Sách Khóa Học</h2>

    <?php if ($keyword !== ''): ?>
        <p class="text-muted">Kết quả tìm kiếm cho: "<strong><?= htmlspecialchars($keyword) ?></strong>"</p>
    <?php endif; ?>

    <!-- Bộ lọc theo danh mục -->
    <div class="mb-4 text-center">
        <a href="<?= BASE_URL ?>/index.php?q=<?= urlencode($keyword) ?>"
           class="btn btn-sm <?= $danh_muc_id === 0 ? 'btn-primary' : 'btn-outline-primary' ?> me-2 mb-2">
            Tất cả
        </a>
        <?php foreach ($danh_muc_list as $dm): ?>
            <a href="<?= BASE_URL ?>/index.php?q=<?= urlencode($keyword) ?>&danh_muc_id=<?= $dm['id'] ?>"
               class="btn btn-sm <?= $danh_muc_id === (int)$dm['id'] ? 'btn-primary' : 'btn-outline-primary' ?> me-2 mb-2">
                <?= htmlspecialchars($dm['ten_danh_muc']) ?>
            </a>
        <?php endforeach; ?>
    </div>

    <div class="row g-4">
        <?php if (count($danh_sach_khoa_hoc) === 0): ?>
            <div class="col-12">
                <div class="alert alert-warning text-center">Không tìm thấy khóa học nào phù hợp.</div>
            </div>
        <?php endif; ?>

        <?php foreach ($danh_sach_khoa_hoc as $kh): ?>
            <div class="col-md-6 col-lg-4">
                <div class="card h-100 shadow-sm course-card">
                    <img src="<?= BASE_URL ?>/assets/img/<?= htmlspecialchars($kh['hinh_anh'] ?: 'no-image.jpg') ?>"
                         class="card-img-top" alt="<?= htmlspecialchars($kh['ten_khoa_hoc']) ?>"
                         onerror="this.src='<?= BASE_URL ?>/assets/img/no-image.jpg'">
                    <div class="card-body d-flex flex-column">
                        <span class="badge bg-info text-dark mb-2 align-self-start">
                            <?= htmlspecialchars($kh['ten_danh_muc']) ?>
                        </span>
                        <h5 class="card-title"><?= htmlspecialchars($kh['ten_khoa_hoc']) ?></h5>
                        <p class="card-text text-muted small flex-grow-1">
                            <?= htmlspecialchars(mb_strimwidth($kh['mo_ta'] ?? '', 0, 100, '...')) ?>
                        </p>
                        <p class="mb-1"><i class="bi bi-person-badge"></i> GV: <?= htmlspecialchars($kh['giang_vien']) ?></p>
                        <p class="mb-2 fw-bold text-danger">
                            <?= number_format($kh['hoc_phi'], 0, ',', '.') ?> đ
                        </p>
                        <a href="<?= BASE_URL ?>/khoa-hoc-chi-tiet.php?id=<?= $kh['id'] ?>" class="btn btn-primary mt-auto">
                            Xem chi tiết & Đăng ký
                        </a>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>

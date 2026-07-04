<?php
/**
 * TRANG CHI TIẾT KHÓA HỌC + FORM ĐĂNG KÝ
 * Yêu cầu: Trang chi tiết
 */
require_once __DIR__ . '/config/database.php';

$id = isset($_GET['id']) ? (int) $_GET['id'] : 0;

$stmt = $pdo->prepare("SELECT kh.*, dm.ten_danh_muc
                        FROM khoa_hoc kh
                        JOIN danh_muc dm ON kh.danh_muc_id = dm.id
                        WHERE kh.id = :id");
$stmt->execute([':id' => $id]);
$khoa_hoc = $stmt->fetch();

if (!$khoa_hoc) {
    header('Location: ' . BASE_URL . '/index.php');
    exit;
}

$page_title = $khoa_hoc['ten_khoa_hoc'];
require_once __DIR__ . '/includes/header.php';
?>

<div class="container my-5">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="<?= BASE_URL ?>/index.php">Trang chủ</a></li>
            <li class="breadcrumb-item active"><?= htmlspecialchars($khoa_hoc['ten_khoa_hoc']) ?></li>
        </ol>
    </nav>

    <div class="row g-4">
        <!-- Thông tin khóa học -->
        <div class="col-lg-7">
            <img src="<?= BASE_URL ?>/assets/img/<?= htmlspecialchars($khoa_hoc['hinh_anh'] ?: 'no-image.jpg') ?>"
                 class="img-fluid rounded shadow-sm mb-3" alt="<?= htmlspecialchars($khoa_hoc['ten_khoa_hoc']) ?>"
                 onerror="this.src='<?= BASE_URL ?>/assets/img/no-image.jpg'">

            <span class="badge bg-info text-dark mb-2"><?= htmlspecialchars($khoa_hoc['ten_danh_muc']) ?></span>
            <h2 class="fw-bold"><?= htmlspecialchars($khoa_hoc['ten_khoa_hoc']) ?></h2>

            <ul class="list-group list-group-flush mb-3">
                <li class="list-group-item"><i class="bi bi-person-badge"></i> Giảng viên: <strong><?= htmlspecialchars($khoa_hoc['giang_vien']) ?></strong></li>
                <li class="list-group-item"><i class="bi bi-clock"></i> Thời lượng: <strong><?= htmlspecialchars($khoa_hoc['thoi_luong']) ?></strong></li>
                <li class="list-group-item"><i class="bi bi-calendar-event"></i> Khai giảng: <strong><?= htmlspecialchars($khoa_hoc['ngay_khai_giang']) ?></strong></li>
                <li class="list-group-item"><i class="bi bi-people"></i> Số lượng tối đa: <strong><?= (int) $khoa_hoc['so_luong_toi_da'] ?> học viên</strong></li>
                <li class="list-group-item"><i class="bi bi-cash-coin"></i> Học phí: <strong class="text-danger"><?= number_format($khoa_hoc['hoc_phi'], 0, ',', '.') ?> đ</strong></li>
            </ul>

            <h5>Mô tả khóa học</h5>
            <p><?= nl2br(htmlspecialchars($khoa_hoc['mo_ta'])) ?></p>
        </div>

        <!-- Form đăng ký -->
        <div class="col-lg-5">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <i class="bi bi-pencil-square"></i> Đăng ký khóa học này
                </div>
                <div class="card-body">
                    <form action="<?= BASE_URL ?>/dang-ky-xu-ly.php" method="POST" novalidate id="formDangKy">
                        <input type="hidden" name="khoa_hoc_id" value="<?= $khoa_hoc['id'] ?>">

                        <div class="mb-3">
                            <label class="form-label">Họ và tên <span class="text-danger">*</span></label>
                            <input type="text" name="ho_ten" class="form-control" required minlength="2">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Email <span class="text-danger">*</span></label>
                            <input type="email" name="email" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Số điện thoại <span class="text-danger">*</span></label>
                            <input type="tel" name="so_dien_thoai" class="form-control" required pattern="[0-9]{9,11}">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Ghi chú</label>
                            <textarea name="ghi_chu" class="form-control" rows="3"></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="bi bi-send-check"></i> Xác nhận đăng ký
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>

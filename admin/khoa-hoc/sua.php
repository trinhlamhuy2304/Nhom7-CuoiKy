<?php
/**
 * QUẢN LÝ KHÓA HỌC - CẬP NHẬT
 */
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../includes/auth.php';

if (session_status() === PHP_SESSION_NONE) session_start();

$id = isset($_GET['id']) ? (int) $_GET['id'] : 0;

$stmt = $pdo->prepare("SELECT * FROM khoa_hoc WHERE id = :id");
$stmt->execute([':id' => $id]);
$khoa_hoc = $stmt->fetch();

if (!$khoa_hoc) {
    $_SESSION['flash_message'] = 'Không tìm thấy khóa học.';
    $_SESSION['flash_type'] = 'danger';
    header('Location: index.php');
    exit;
}

$page_title = 'Sửa khóa học';
$errors = [];
$danh_muc_list = $pdo->query("SELECT id, ten_danh_muc FROM danh_muc ORDER BY ten_danh_muc")->fetchAll();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $ten_khoa_hoc     = trim($_POST['ten_khoa_hoc'] ?? '');
    $danh_muc_id      = (int) ($_POST['danh_muc_id'] ?? 0);
    $giang_vien       = trim($_POST['giang_vien'] ?? '');
    $thoi_luong       = trim($_POST['thoi_luong'] ?? '');
    $ngay_khai_giang  = trim($_POST['ngay_khai_giang'] ?? '');
    $so_luong_toi_da  = (int) ($_POST['so_luong_toi_da'] ?? 0);
    $hoc_phi          = (float) ($_POST['hoc_phi'] ?? 0);
    $mo_ta            = trim($_POST['mo_ta'] ?? '');
    $hinh_anh         = trim($_POST['hinh_anh'] ?? '');
    $trang_thai       = isset($_POST['trang_thai']) ? 1 : 0;

    if (mb_strlen($ten_khoa_hoc) < 3) {
        $errors[] = 'Tên khóa học phải có ít nhất 3 ký tự.';
    }
    if ($danh_muc_id <= 0) {
        $errors[] = 'Vui lòng chọn danh mục.';
    }
    if ($hoc_phi < 0) {
        $errors[] = 'Học phí không hợp lệ.';
    }
    if ($so_luong_toi_da <= 0) {
        $errors[] = 'Số lượng tối đa phải lớn hơn 0.';
    }

    if (empty($errors)) {
        $stmt = $pdo->prepare("UPDATE khoa_hoc SET
            ten_khoa_hoc = :ten_khoa_hoc,
            danh_muc_id = :danh_muc_id,
            giang_vien = :giang_vien,
            thoi_luong = :thoi_luong,
            ngay_khai_giang = :ngay_khai_giang,
            so_luong_toi_da = :so_luong_toi_da,
            hoc_phi = :hoc_phi,
            mo_ta = :mo_ta,
            hinh_anh = :hinh_anh,
            trang_thai = :trang_thai
            WHERE id = :id");
        $stmt->execute([
            ':ten_khoa_hoc'    => $ten_khoa_hoc,
            ':danh_muc_id'     => $danh_muc_id,
            ':giang_vien'      => $giang_vien,
            ':thoi_luong'      => $thoi_luong,
            ':ngay_khai_giang' => $ngay_khai_giang,
            ':so_luong_toi_da' => $so_luong_toi_da,
            ':hoc_phi'         => $hoc_phi,
            ':mo_ta'           => $mo_ta,
            ':hinh_anh'        => $hinh_anh,
            ':trang_thai'      => $trang_thai,
            ':id'              => $id,
        ]);

        $_SESSION['flash_message'] = 'Cập nhật khóa học thành công!';
        $_SESSION['flash_type'] = 'success';
        header('Location: index.php');
        exit;
    }
    // Nếu có lỗi, ghi đè dữ liệu hiển thị lại bằng dữ liệu vừa nhập
    $khoa_hoc = array_merge($khoa_hoc, $_POST);
}

require_once __DIR__ . '/../includes/admin_header.php';
?>

<div class="container my-4">
    <h3 class="fw-bold mb-3"><i class="bi bi-pencil-square"></i> Sửa khóa học</h3>

    <?php if (!empty($errors)): ?>
        <div class="alert alert-danger">
            <ul class="mb-0">
                <?php foreach ($errors as $e): ?>
                    <li><?= htmlspecialchars($e) ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <form method="POST" class="row g-3">
        <div class="col-md-8">
            <label class="form-label">Tên khóa học <span class="text-danger">*</span></label>
            <input type="text" name="ten_khoa_hoc" class="form-control" required
                   value="<?= htmlspecialchars($khoa_hoc['ten_khoa_hoc']) ?>">
        </div>
        <div class="col-md-4">
            <label class="form-label">Danh mục <span class="text-danger">*</span></label>
            <select name="danh_muc_id" class="form-select" required>
                <option value="">-- Chọn danh mục --</option>
                <?php foreach ($danh_muc_list as $dm): ?>
                    <option value="<?= $dm['id'] ?>"
                        <?= ($khoa_hoc['danh_muc_id'] == $dm['id']) ? 'selected' : '' ?>>
                        <?= htmlspecialchars($dm['ten_danh_muc']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="col-md-4">
            <label class="form-label">Giảng viên</label>
            <input type="text" name="giang_vien" class="form-control"
                   value="<?= htmlspecialchars($khoa_hoc['giang_vien']) ?>">
        </div>
        <div class="col-md-4">
            <label class="form-label">Thời lượng</label>
            <input type="text" name="thoi_luong" class="form-control"
                   value="<?= htmlspecialchars($khoa_hoc['thoi_luong']) ?>">
        </div>
        <div class="col-md-4">
            <label class="form-label">Ngày khai giảng</label>
            <input type="date" name="ngay_khai_giang" class="form-control"
                   value="<?= htmlspecialchars($khoa_hoc['ngay_khai_giang']) ?>">
        </div>

        <div class="col-md-4">
            <label class="form-label">Số lượng tối đa</label>
            <input type="number" name="so_luong_toi_da" class="form-control" min="1"
                   value="<?= htmlspecialchars($khoa_hoc['so_luong_toi_da']) ?>">
        </div>
        <div class="col-md-4">
            <label class="form-label">Học phí (đ)</label>
            <input type="number" step="1000" name="hoc_phi" class="form-control" min="0"
                   value="<?= htmlspecialchars($khoa_hoc['hoc_phi']) ?>">
        </div>
        <div class="col-md-4">
            <label class="form-label">Tên file hình ảnh</label>
            <input type="text" name="hinh_anh" class="form-control"
                   value="<?= htmlspecialchars($khoa_hoc['hinh_anh']) ?>">
        </div>

        <div class="col-12">
            <label class="form-label">Mô tả</label>
            <textarea name="mo_ta" class="form-control" rows="4"><?= htmlspecialchars($khoa_hoc['mo_ta']) ?></textarea>
        </div>

        <div class="col-12 form-check ms-2">
            <input type="checkbox" name="trang_thai" class="form-check-input" id="trangThai"
                   <?= ($khoa_hoc['trang_thai'] == 1) ? 'checked' : '' ?>>
            <label class="form-check-label" for="trangThai">Mở đăng ký</label>
        </div>

        <div class="col-12">
            <button type="submit" class="btn btn-primary"><i class="bi bi-check-lg"></i> Cập nhật</button>
            <a href="index.php" class="btn btn-secondary">Hủy</a>
        </div>
    </form>
</div>

<?php require_once __DIR__ . '/../includes/admin_footer.php'; ?>
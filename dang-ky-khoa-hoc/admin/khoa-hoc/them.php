<?php
/**
 * THÊM KHÓA HỌC MỚI
 */
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../includes/auth.php';

$page_title = 'Thêm khóa học';
$errors = [];

$danh_muc_list = $pdo->query("SELECT * FROM danh_muc ORDER BY ten_danh_muc")->fetchAll();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $danh_muc_id     = (int) ($_POST['danh_muc_id'] ?? 0);
    $ten_khoa_hoc    = trim($_POST['ten_khoa_hoc'] ?? '');
    $mo_ta           = trim($_POST['mo_ta'] ?? '');
    $giang_vien      = trim($_POST['giang_vien'] ?? '');
    $hoc_phi         = (float) ($_POST['hoc_phi'] ?? 0);
    $thoi_luong      = trim($_POST['thoi_luong'] ?? '');
    $hinh_anh        = trim($_POST['hinh_anh'] ?? '');
    $so_luong_toi_da = (int) ($_POST['so_luong_toi_da'] ?? 30);
    $ngay_khai_giang = trim($_POST['ngay_khai_giang'] ?? '');
    $trang_thai      = isset($_POST['trang_thai']) ? 1 : 0;

    // ---- Validation phía Server ----
    if ($danh_muc_id <= 0) $errors[] = 'Vui lòng chọn danh mục.';
    if (mb_strlen($ten_khoa_hoc) < 3) $errors[] = 'Tên khóa học phải có ít nhất 3 ký tự.';
    if ($hoc_phi < 0) $errors[] = 'Học phí không hợp lệ.';

    if (empty($errors)) {
        $stmt = $pdo->prepare("INSERT INTO khoa_hoc
            (danh_muc_id, ten_khoa_hoc, mo_ta, giang_vien, hoc_phi, thoi_luong, hinh_anh, so_luong_toi_da, ngay_khai_giang, trang_thai)
            VALUES (:danh_muc_id, :ten, :mo_ta, :gv, :hoc_phi, :tl, :ha, :sl, :ngay, :tt)");
        $stmt->execute([
            ':danh_muc_id' => $danh_muc_id,
            ':ten'         => $ten_khoa_hoc,
            ':mo_ta'       => $mo_ta,
            ':gv'          => $giang_vien,
            ':hoc_phi'     => $hoc_phi,
            ':tl'          => $thoi_luong,
            ':ha'          => $hinh_anh,
            ':sl'          => $so_luong_toi_da,
            ':ngay'        => $ngay_khai_giang ?: null,
            ':tt'          => $trang_thai,
        ]);

        $_SESSION['flash_message'] = 'Thêm khóa học thành công.';
        $_SESSION['flash_type'] = 'success';
        header('Location: ' . BASE_URL . '/admin/khoa-hoc/index.php');
        exit;
    }
}

require_once __DIR__ . '/../includes/admin_header.php';
?>

<div class="card shadow-sm">
    <div class="card-body">
        <?php if (!empty($errors)): ?>
            <div class="alert alert-danger">
                <ul class="mb-0">
                    <?php foreach ($errors as $err): ?><li><?= htmlspecialchars($err) ?></li><?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <form method="POST">
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">Tên khóa học <span class="text-danger">*</span></label>
                    <input type="text" name="ten_khoa_hoc" class="form-control" required
                           value="<?= htmlspecialchars($_POST['ten_khoa_hoc'] ?? '') ?>">
                </div>
                <div class="col-md-6">
                    <label class="form-label">Danh mục <span class="text-danger">*</span></label>
                    <select name="danh_muc_id" class="form-select" required>
                        <option value="">-- Chọn danh mục --</option>
                        <?php foreach ($danh_muc_list as $dm): ?>
                            <option value="<?= $dm['id'] ?>"><?= htmlspecialchars($dm['ten_danh_muc']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Giảng viên</label>
                    <input type="text" name="giang_vien" class="form-control">
                </div>
                <div class="col-md-3">
                    <label class="form-label">Học phí (đ)</label>
                    <input type="number" step="1000" min="0" name="hoc_phi" class="form-control" value="0">
                </div>
                <div class="col-md-3">
                    <label class="form-label">Thời lượng</label>
                    <input type="text" name="thoi_luong" class="form-control" placeholder="vd: 8 tuần">
                </div>
                <div class="col-md-4">
                    <label class="form-label">Tên file hình ảnh</label>
                    <input type="text" name="hinh_anh" class="form-control" placeholder="vd: khoa-hoc-1.jpg">
                    <small class="text-muted">Đặt ảnh trong thư mục /assets/img/. (Có thể nâng cấp thành upload file)</small>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Số lượng tối đa</label>
                    <input type="number" min="1" name="so_luong_toi_da" class="form-control" value="30">
                </div>
                <div class="col-md-4">
                    <label class="form-label">Ngày khai giảng</label>
                    <input type="date" name="ngay_khai_giang" class="form-control">
                </div>
                <div class="col-12">
                    <label class="form-label">Mô tả</label>
                    <textarea name="mo_ta" class="form-control" rows="4"><?= htmlspecialchars($_POST['mo_ta'] ?? '') ?></textarea>
                </div>
                <div class="col-12">
                    <div class="form-check">
                        <input type="checkbox" name="trang_thai" class="form-check-input" id="trangThai" checked>
                        <label class="form-check-label" for="trangThai">Mở đăng ký ngay</label>
                    </div>
                </div>
            </div>

            <div class="mt-4">
                <button type="submit" class="btn btn-primary"><i class="bi bi-save"></i> Lưu khóa học</button>
                <a href="index.php" class="btn btn-secondary">Hủy</a>
            </div>
        </form>
    </div>
</div>

<?php require_once __DIR__ . '/../includes/admin_footer.php'; ?>

<?php
/**
 * QUẢN LÝ KHÓA HỌC - DANH SÁCH
 * Yêu cầu: Hiển thị danh sách, tìm kiếm theo tên khóa học, link Thêm/Sửa/Xóa
 */
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../includes/auth.php'; // TODO: đổi tên nếu bạn Duy đặt tên file khác

$page_title = 'Quản lý khóa học';

// ---- Tìm kiếm theo tên khóa học ----
$keyword = isset($_GET['q']) ? trim($_GET['q']) : '';

$sql = "SELECT kh.*, dm.ten_danh_muc
        FROM khoa_hoc kh
        JOIN danh_muc dm ON kh.danh_muc_id = dm.id
        WHERE 1=1";
$params = [];

if ($keyword !== '') {
    $sql .= " AND kh.ten_khoa_hoc LIKE :keyword";
    $params[':keyword'] = '%' . $keyword . '%';
}
$sql .= " ORDER BY kh.created_at DESC";

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$danh_sach = $stmt->fetchAll();

require_once __DIR__ . '/../includes/admin_header.php';
?>

<div class="container-fluid my-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3 class="fw-bold mb-0"><i class="bi bi-mortarboard"></i> Quản lý khóa học</h3>
        <a href="them.php" class="btn btn-success">
            <i class="bi bi-plus-circle"></i> Thêm khóa học
        </a>
    </div>

    <?php if (!empty($_SESSION['flash_message'])): ?>
        <div class="alert alert-<?= htmlspecialchars($_SESSION['flash_type'] ?? 'info') ?>">
            <?= htmlspecialchars($_SESSION['flash_message']) ?>
        </div>
        <?php unset($_SESSION['flash_message'], $_SESSION['flash_type']); ?>
    <?php endif; ?>

    <!-- Form tìm kiếm -->
    <form method="GET" class="row g-2 mb-3">
        <div class="col-md-4">
            <input type="text" name="q" class="form-control" placeholder="Tìm theo tên khóa học..."
                   value="<?= htmlspecialchars($keyword) ?>">
        </div>
        <div class="col-auto">
            <button type="submit" class="btn btn-primary"><i class="bi bi-search"></i> Tìm</button>
        </div>
    </form>

    <div class="table-responsive">
        <table class="table table-bordered table-hover align-middle">
            <thead class="table-light">
                <tr>
                    <th>#</th>
                    <th>Tên khóa học</th>
                    <th>Danh mục</th>
                    <th>Giảng viên</th>
                    <th>Học phí</th>
                    <th>Trạng thái</th>
                    <th style="width: 140px;">Hành động</th>
                </tr>
            </thead>
            <tbody>
                <?php if (count($danh_sach) === 0): ?>
                    <tr><td colspan="7" class="text-center text-muted">Không có khóa học nào.</td></tr>
                <?php endif; ?>
                <?php foreach ($danh_sach as $i => $kh): ?>
                    <tr>
                        <td><?= $i + 1 ?></td>
                        <td><?= htmlspecialchars($kh['ten_khoa_hoc']) ?></td>
                        <td><?= htmlspecialchars($kh['ten_danh_muc']) ?></td>
                        <td><?= htmlspecialchars($kh['giang_vien']) ?></td>
                        <td><?= number_format($kh['hoc_phi'], 0, ',', '.') ?> đ</td>
                        <td>
                            <?php if ($kh['trang_thai'] == 1): ?>
                                <span class="badge bg-success">Đang mở</span>
                            <?php else: ?>
                                <span class="badge bg-secondary">Đã đóng</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <a href="sua.php?id=<?= $kh['id'] ?>" class="btn btn-sm btn-warning">
                                <i class="bi bi-pencil-square"></i>
                            </a>
                            <a href="xoa.php?id=<?= $kh['id'] ?>" class="btn btn-sm btn-danger"
                               onclick="return confirm('Bạn có chắc muốn xóa khóa học này?');">
                                <i class="bi bi-trash"></i>
                            </a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<?php require_once __DIR__ . '/../includes/admin_footer.php'; ?>
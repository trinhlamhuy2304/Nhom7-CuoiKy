<?php
/**
 * QUẢN LÝ KHÓA HỌC - Danh sách + Tìm kiếm + Xóa
 */
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../includes/auth.php';

$page_title = 'Quản lý khóa học';

// ---- Xử lý xóa ----
if (isset($_GET['delete'])) {
    $id = (int) $_GET['delete'];
    $stmt = $pdo->prepare("DELETE FROM khoa_hoc WHERE id = :id");
    $stmt->execute([':id' => $id]);
    $_SESSION['flash_message'] = 'Đã xóa khóa học thành công.';
    $_SESSION['flash_type'] = 'success';
    header('Location: ' . BASE_URL . '/admin/khoa-hoc/index.php');
    exit;
}

// ---- Tìm kiếm ----
$keyword = trim($_GET['q'] ?? '');
$sql = "SELECT kh.*, dm.ten_danh_muc FROM khoa_hoc kh
        JOIN danh_muc dm ON kh.danh_muc_id = dm.id";
$params = [];
if ($keyword !== '') {
    $sql .= " WHERE kh.ten_khoa_hoc LIKE :kw";
    $params[':kw'] = '%' . $keyword . '%';
}
$sql .= " ORDER BY kh.id DESC";
$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$danh_sach = $stmt->fetchAll();

require_once __DIR__ . '/../includes/admin_header.php';
?>

<div class="d-flex justify-content-between align-items-center mb-3">
    <form class="d-flex" method="GET">
        <input type="search" name="q" class="form-control me-2" placeholder="Tìm khóa học..."
               value="<?= htmlspecialchars($keyword) ?>">
        <button class="btn btn-outline-primary"><i class="bi bi-search"></i></button>
    </form>
    <a href="them.php" class="btn btn-success">
        <i class="bi bi-plus-circle"></i> Thêm khóa học
    </a>
</div>

<div class="card shadow-sm">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th>#</th>
                    <th>Tên khóa học</th>
                    <th>Danh mục</th>
                    <th>Giảng viên</th>
                    <th>Học phí</th>
                    <th>Trạng thái</th>
                    <th class="text-center">Hành động</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($danh_sach)): ?>
                    <tr><td colspan="7" class="text-center text-muted py-4">Chưa có khóa học nào.</td></tr>
                <?php endif; ?>
                <?php foreach ($danh_sach as $i => $kh): ?>
                    <tr>
                        <td><?= $i + 1 ?></td>
                        <td><?= htmlspecialchars($kh['ten_khoa_hoc']) ?></td>
                        <td><?= htmlspecialchars($kh['ten_danh_muc']) ?></td>
                        <td><?= htmlspecialchars($kh['giang_vien']) ?></td>
                        <td><?= number_format($kh['hoc_phi'], 0, ',', '.') ?> đ</td>
                        <td>
                            <?php if ($kh['trang_thai']): ?>
                                <span class="badge bg-success">Đang mở</span>
                            <?php else: ?>
                                <span class="badge bg-secondary">Đã đóng</span>
                            <?php endif; ?>
                        </td>
                        <td class="text-center">
                            <a href="sua.php?id=<?= $kh['id'] ?>" class="btn btn-sm btn-warning">
                                <i class="bi bi-pencil"></i>
                            </a>
                            <a href="index.php?delete=<?= $kh['id'] ?>" class="btn btn-sm btn-danger"
                               onclick="return confirm('Bạn có chắc muốn xóa khóa học này?')">
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

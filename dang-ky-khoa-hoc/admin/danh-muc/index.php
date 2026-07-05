<?php
/**
 * QUẢN LÝ DANH MỤC - DANH SÁCH
 */
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../includes/auth.php';

$page_title = 'Quản lý danh mục';

$keyword = isset($_GET['q']) ? trim($_GET['q']) : '';

$sql = "SELECT dm.*, 
        (SELECT COUNT(*) FROM khoa_hoc kh WHERE kh.danh_muc_id = dm.id) AS so_khoa_hoc
        FROM danh_muc dm WHERE 1=1";
$params = [];

if ($keyword !== '') {
    $sql .= " AND dm.ten_danh_muc LIKE :keyword";
    $params[':keyword'] = '%' . $keyword . '%';
}
$sql .= " ORDER BY dm.ten_danh_muc ASC";

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$danh_sach = $stmt->fetchAll();

require_once __DIR__ . '/../includes/admin_header.php';
?>

<div class="container-fluid my-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3 class="fw-bold mb-0"><i class="bi bi-tags"></i> Quản lý danh mục</h3>
        <a href="them.php" class="btn btn-success">
            <i class="bi bi-plus-circle"></i> Thêm danh mục
        </a>
    </div>

    <?php if (!empty($_SESSION['flash_message'])): ?>
        <div class="alert alert-<?= htmlspecialchars($_SESSION['flash_type'] ?? 'info') ?>">
            <?= htmlspecialchars($_SESSION['flash_message']) ?>
        </div>
        <?php unset($_SESSION['flash_message'], $_SESSION['flash_type']); ?>
    <?php endif; ?>

    <form method="GET" class="row g-2 mb-3">
        <div class="col-md-4">
            <input type="text" name="q" class="form-control" placeholder="Tìm theo tên danh mục..."
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
                    <th>Tên danh mục</th>
                    <th>Số khóa học thuộc danh mục</th>
                    <th style="width: 140px;">Hành động</th>
                </tr>
            </thead>
            <tbody>
                <?php if (count($danh_sach) === 0): ?>
                    <tr><td colspan="4" class="text-center text-muted">Không có danh mục nào.</td></tr>
                <?php endif; ?>
                <?php foreach ($danh_sach as $i => $dm): ?>
                    <tr>
                        <td><?= $i + 1 ?></td>
                        <td><?= htmlspecialchars($dm['ten_danh_muc']) ?></td>
                        <td><span class="badge bg-info text-dark"><?= (int) $dm['so_khoa_hoc'] ?></span></td>
                        <td>
                            <a href="sua.php?id=<?= $dm['id'] ?>" class="btn btn-sm btn-warning">
                                <i class="bi bi-pencil-square"></i>
                            </a>
                            <a href="xoa.php?id=<?= $dm['id'] ?>" class="btn btn-sm btn-danger"
                               onclick="return confirm('Bạn có chắc muốn xóa danh mục này?');">
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
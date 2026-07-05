<?php
/**
 * QUẢN LÝ DANH MỤC - CRUD gọn trong 1 trang (list + thêm + xóa)
 * Sửa danh mục nằm ở file sua.php riêng
 */
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../includes/auth.php';

$page_title = 'Quản lý danh mục';
$errors = [];

// ---- Thêm danh mục mới ----
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $ten_danh_muc = trim($_POST['ten_danh_muc'] ?? '');
    $mo_ta = trim($_POST['mo_ta'] ?? '');

    if (mb_strlen($ten_danh_muc) < 2) {
        $errors[] = 'Tên danh mục phải có ít nhất 2 ký tự.';
    } else {
        $stmt = $pdo->prepare("INSERT INTO danh_muc (ten_danh_muc, mo_ta) VALUES (:ten, :mo_ta)");
        $stmt->execute([':ten' => $ten_danh_muc, ':mo_ta' => $mo_ta]);
        $_SESSION['flash_message'] = 'Thêm danh mục thành công.';
        $_SESSION['flash_type'] = 'success';
        header('Location: ' . BASE_URL . '/admin/danh-muc/index.php');
        exit;
    }
}

// ---- Xóa danh mục ----
if (isset($_GET['delete'])) {
    $id = (int) $_GET['delete'];
    try {
        $stmt = $pdo->prepare("DELETE FROM danh_muc WHERE id = :id");
        $stmt->execute([':id' => $id]);
        $_SESSION['flash_message'] = 'Đã xóa danh mục.';
        $_SESSION['flash_type'] = 'success';
    } catch (PDOException $e) {
        $_SESSION['flash_message'] = 'Không thể xóa: danh mục đang có khóa học liên kết.';
        $_SESSION['flash_type'] = 'danger';
    }
    header('Location: ' . BASE_URL . '/admin/danh-muc/index.php');
    exit;
}

$danh_sach = $pdo->query("SELECT dm.*, (SELECT COUNT(*) FROM khoa_hoc kh WHERE kh.danh_muc_id = dm.id) AS so_khoa_hoc
                           FROM danh_muc dm ORDER BY dm.id DESC")->fetchAll();

require_once __DIR__ . '/../includes/admin_header.php';
?>

<div class="row g-4">
    <!-- Form thêm danh mục -->
    <div class="col-lg-4">
        <div class="card shadow-sm">
            <div class="card-header bg-primary text-white"><i class="bi bi-plus-circle"></i> Thêm danh mục</div>
            <div class="card-body">
                <?php if (!empty($errors)): ?>
                    <div class="alert alert-danger"><?= implode('<br>', array_map('htmlspecialchars', $errors)) ?></div>
                <?php endif; ?>
                <form method="POST">
                    <div class="mb-3">
                        <label class="form-label">Tên danh mục</label>
                        <input type="text" name="ten_danh_muc" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Mô tả</label>
                        <textarea name="mo_ta" class="form-control" rows="3"></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary w-100">Lưu danh mục</button>
                </form>
            </div>
        </div>
    </div>

    <!-- Danh sách danh mục -->
    <div class="col-lg-8">
        <div class="card shadow-sm">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>Tên danh mục</th>
                            <th>Số khóa học</th>
                            <th class="text-center">Hành động</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($danh_sach)): ?>
                            <tr><td colspan="4" class="text-center text-muted py-4">Chưa có danh mục nào.</td></tr>
                        <?php endif; ?>
                        <?php foreach ($danh_sach as $i => $dm): ?>
                            <tr>
                                <td><?= $i + 1 ?></td>
                                <td><?= htmlspecialchars($dm['ten_danh_muc']) ?></td>
                                <td><span class="badge bg-info text-dark"><?= (int) $dm['so_khoa_hoc'] ?></span></td>
                                <td class="text-center">
                                    <a href="sua.php?id=<?= $dm['id'] ?>" class="btn btn-sm btn-warning"><i class="bi bi-pencil"></i></a>
                                    <a href="index.php?delete=<?= $dm['id'] ?>" class="btn btn-sm btn-danger"
                                       onclick="return confirm('Xóa danh mục này?')"><i class="bi bi-trash"></i></a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../includes/admin_footer.php'; ?>

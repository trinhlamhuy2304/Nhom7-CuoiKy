<?php
/**
 * CẬP NHẬT DANH MỤC
 */
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../includes/auth.php';

$page_title = 'Cập nhật danh mục';
$id = (int) ($_GET['id'] ?? 0);

$stmt = $pdo->prepare("SELECT * FROM danh_muc WHERE id = :id");
$stmt->execute([':id' => $id]);
$dm = $stmt->fetch();

if (!$dm) {
    header('Location: ' . BASE_URL . '/admin/danh-muc/index.php');
    exit;
}

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $ten_danh_muc = trim($_POST['ten_danh_muc'] ?? '');
    $mo_ta = trim($_POST['mo_ta'] ?? '');

    if (mb_strlen($ten_danh_muc) < 2) {
        $errors[] = 'Tên danh mục phải có ít nhất 2 ký tự.';
    } else {
        $stmt = $pdo->prepare("UPDATE danh_muc SET ten_danh_muc = :ten, mo_ta = :mo_ta WHERE id = :id");
        $stmt->execute([':ten' => $ten_danh_muc, ':mo_ta' => $mo_ta, ':id' => $id]);
        $_SESSION['flash_message'] = 'Cập nhật danh mục thành công.';
        $_SESSION['flash_type'] = 'success';
        header('Location: ' . BASE_URL . '/admin/danh-muc/index.php');
        exit;
    }
    $dm = array_merge($dm, $_POST);
}

require_once __DIR__ . '/../includes/admin_header.php';
?>

<div class="card shadow-sm" style="max-width: 600px;">
    <div class="card-body">
        <?php if (!empty($errors)): ?>
            <div class="alert alert-danger"><?= implode('<br>', array_map('htmlspecialchars', $errors)) ?></div>
        <?php endif; ?>
        <form method="POST">
            <div class="mb-3">
                <label class="form-label">Tên danh mục</label>
                <input type="text" name="ten_danh_muc" class="form-control" value="<?= htmlspecialchars($dm['ten_danh_muc']) ?>" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Mô tả</label>
                <textarea name="mo_ta" class="form-control" rows="3"><?= htmlspecialchars($dm['mo_ta']) ?></textarea>
            </div>
            <button type="submit" class="btn btn-primary"><i class="bi bi-save"></i> Cập nhật</button>
            <a href="index.php" class="btn btn-secondary">Hủy</a>
        </form>
    </div>
</div>

<?php require_once __DIR__ . '/../includes/admin_footer.php'; ?>

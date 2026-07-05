<?php
/**
 * QUẢN LÝ DANH MỤC - THÊM MỚI
 */
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../includes/auth.php';

if (session_status() === PHP_SESSION_NONE) session_start();

$page_title = 'Thêm danh mục';
$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $ten_danh_muc = trim($_POST['ten_danh_muc'] ?? '');

    if (mb_strlen($ten_danh_muc) < 2) {
        $errors[] = 'Tên danh mục phải có ít nhất 2 ký tự.';
    }

    // Kiểm tra trùng tên danh mục
    if (empty($errors)) {
        $check = $pdo->prepare("SELECT id FROM danh_muc WHERE ten_danh_muc = :ten");
        $check->execute([':ten' => $ten_danh_muc]);
        if ($check->fetch()) {
            $errors[] = 'Tên danh mục này đã tồn tại.';
        }
    }

    if (empty($errors)) {
        $stmt = $pdo->prepare("INSERT INTO danh_muc (ten_danh_muc) VALUES (:ten)");
        $stmt->execute([':ten' => $ten_danh_muc]);

        $_SESSION['flash_message'] = 'Thêm danh mục thành công!';
        $_SESSION['flash_type'] = 'success';
        header('Location: index.php');
        exit;
    }
}

require_once __DIR__ . '/../includes/admin_header.php';
?>

<div class="container my-4">
    <h3 class="fw-bold mb-3"><i class="bi bi-plus-circle"></i> Thêm danh mục mới</h3>

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
        <div class="col-md-6">
            <label class="form-label">Tên danh mục <span class="text-danger">*</span></label>
            <input type="text" name="ten_danh_muc" class="form-control" required minlength="2"
                   value="<?= htmlspecialchars($_POST['ten_danh_muc'] ?? '') ?>">
        </div>
        <div class="col-12">
            <button type="submit" class="btn btn-primary"><i class="bi bi-check-lg"></i> Lưu danh mục</button>
            <a href="index.php" class="btn btn-secondary">Hủy</a>
        </div>
    </form>
</div>

<?php require_once __DIR__ . '/../includes/admin_footer.php'; ?>
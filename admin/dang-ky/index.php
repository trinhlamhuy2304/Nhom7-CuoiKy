<?php
/**
 * QUẢN LÝ DANH SÁCH ĐĂNG KÝ KHÓA HỌC
 */
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../includes/auth.php';

$page_title = 'Danh sách đăng ký';

// ---- Xóa đăng ký ----
if (isset($_GET['delete'])) {
    $id = (int) $_GET['delete'];
    $stmt = $pdo->prepare("DELETE FROM hoc_vien_dang_ky WHERE id = :id");
    $stmt->execute([':id' => $id]);
    $_SESSION['flash_message'] = 'Đã xóa lượt đăng ký.';
    $_SESSION['flash_type'] = 'success';
    header('Location: ' . BASE_URL . '/admin/dang-ky/index.php');
    exit;
}

// ---- Cập nhật trạng thái duyệt ----
if (isset($_POST['update_status'])) {
    $id = (int) $_POST['id'];
    $trang_thai = $_POST['trang_thai'];
    $stmt = $pdo->prepare("UPDATE hoc_vien_dang_ky SET trang_thai = :tt WHERE id = :id");
    $stmt->execute([':tt' => $trang_thai, ':id' => $id]);
    $_SESSION['flash_message'] = 'Đã cập nhật trạng thái.';
    $_SESSION['flash_type'] = 'success';
    header('Location: ' . BASE_URL . '/admin/dang-ky/index.php');
    exit;
}

// ---- Tìm kiếm ----
$keyword = trim($_GET['q'] ?? '');
$sql = "SELECT dk.*, kh.ten_khoa_hoc FROM hoc_vien_dang_ky dk
        JOIN khoa_hoc kh ON dk.khoa_hoc_id = kh.id";
$params = [];
if ($keyword !== '') {
    $sql .= " WHERE dk.ho_ten LIKE :kw OR dk.email LIKE :kw OR dk.so_dien_thoai LIKE :kw";
    $params[':kw'] = '%' . $keyword . '%';
}
$sql .= " ORDER BY dk.ngay_dang_ky DESC";
$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$danh_sach = $stmt->fetchAll();

require_once __DIR__ . '/../includes/admin_header.php';
?>

<form class="d-flex mb-3" method="GET">
    <input type="search" name="q" class="form-control me-2" placeholder="Tìm theo tên, email, SĐT..."
           value="<?= htmlspecialchars($keyword) ?>">
    <button class="btn btn-outline-primary"><i class="bi bi-search"></i></button>
</form>

<div class="card shadow-sm">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th>#</th>
                    <th>Học viên</th>
                    <th>Email</th>
                    <th>SĐT</th>
                    <th>Khóa học</th>
                    <th>Ngày đăng ký</th>
                    <th>Trạng thái</th>
                    <th class="text-center">Hành động</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($danh_sach)): ?>
                    <tr><td colspan="8" class="text-center text-muted py-4">Chưa có lượt đăng ký nào.</td></tr>
                <?php endif; ?>
                <?php foreach ($danh_sach as $i => $dk): ?>
                    <tr>
                        <td><?= $i + 1 ?></td>
                        <td><?= htmlspecialchars($dk['ho_ten']) ?></td>
                        <td><?= htmlspecialchars($dk['email']) ?></td>
                        <td><?= htmlspecialchars($dk['so_dien_thoai']) ?></td>
                        <td><?= htmlspecialchars($dk['ten_khoa_hoc']) ?></td>
                        <td><?= htmlspecialchars($dk['ngay_dang_ky']) ?></td>
                        <td>
                            <form method="POST" class="d-flex gap-1">
                                <input type="hidden" name="id" value="<?= $dk['id'] ?>">
                                <select name="trang_thai" class="form-select form-select-sm" onchange="this.form.submit()">
                                    <option value="cho_xu_ly" <?= $dk['trang_thai'] === 'cho_xu_ly' ? 'selected' : '' ?>>Chờ xử lý</option>
                                    <option value="da_duyet" <?= $dk['trang_thai'] === 'da_duyet' ? 'selected' : '' ?>>Đã duyệt</option>
                                    <option value="da_huy" <?= $dk['trang_thai'] === 'da_huy' ? 'selected' : '' ?>>Đã hủy</option>
                                </select>
                                <input type="hidden" name="update_status" value="1">
                            </form>
                        </td>
                        <td class="text-center">
                            <a href="index.php?delete=<?= $dk['id'] ?>" class="btn btn-sm btn-danger"
                               onclick="return confirm('Xóa lượt đăng ký này?')">
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

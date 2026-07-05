<?php
/**
 * QUẢN LÝ DANH MỤC - XÓA
 */
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../includes/auth.php';

if (session_status() === PHP_SESSION_NONE) session_start();

$id = isset($_GET['id']) ? (int) $_GET['id'] : 0;

if ($id <= 0) {
    header('Location: index.php');
    exit;
}

// Kiểm tra xem còn khóa học nào thuộc danh mục này không
$check = $pdo->prepare("SELECT COUNT(*) FROM khoa_hoc WHERE danh_muc_id = :id");
$check->execute([':id' => $id]);
$so_khoa_hoc = (int) $check->fetchColumn();

if ($so_khoa_hoc > 0) {
    $_SESSION['flash_message'] = 'Không thể xóa vì vẫn còn khóa học thuộc danh mục này. Vui lòng chuyển hoặc xóa khóa học trước.';
    $_SESSION['flash_type'] = 'warning';
} else {
    try {
        $stmt = $pdo->prepare("DELETE FROM danh_muc WHERE id = :id");
        $stmt->execute([':id' => $id]);

        $_SESSION['flash_message'] = 'Xóa danh mục thành công!';
        $_SESSION['flash_type'] = 'success';
    } catch (PDOException $e) {
        $_SESSION['flash_message'] = 'Có lỗi xảy ra khi xóa danh mục.';
        $_SESSION['flash_type'] = 'danger';
    }
}

header('Location: index.php');
exit;
<?php
/**
 * QUẢN LÝ KHÓA HỌC - XÓA
 */
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../includes/auth.php';

if (session_status() === PHP_SESSION_NONE) session_start();

$id = isset($_GET['id']) ? (int) $_GET['id'] : 0;

if ($id <= 0) {
    header('Location: index.php');
    exit;
}

try {
    // Kiểm tra xem khóa học đã có học viên đăng ký chưa (nếu có, không nên xóa cứng)
    $check = $pdo->prepare("SELECT COUNT(*) FROM hoc_vien_dang_ky WHERE khoa_hoc_id = :id");
    $check->execute([':id' => $id]);
    $so_luong_dang_ky = (int) $check->fetchColumn();

    if ($so_luong_dang_ky > 0) {
        // Đã có học viên đăng ký -> chỉ đóng khóa học thay vì xóa hẳn (tránh mất dữ liệu liên quan)
        $stmt = $pdo->prepare("UPDATE khoa_hoc SET trang_thai = 0 WHERE id = :id");
        $stmt->execute([':id' => $id]);

        $_SESSION['flash_message'] = 'Khóa học đã có học viên đăng ký nên không thể xóa, hệ thống đã tự động đóng khóa học này.';
        $_SESSION['flash_type'] = 'warning';
    } else {
        $stmt = $pdo->prepare("DELETE FROM khoa_hoc WHERE id = :id");
        $stmt->execute([':id' => $id]);

        $_SESSION['flash_message'] = 'Xóa khóa học thành công!';
        $_SESSION['flash_type'] = 'success';
    }
} catch (PDOException $e) {
    $_SESSION['flash_message'] = 'Có lỗi xảy ra khi xóa khóa học.';
    $_SESSION['flash_type'] = 'danger';
}

header('Location: index.php');
exit;
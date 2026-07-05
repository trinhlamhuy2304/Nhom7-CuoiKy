<?php
/**
 * XỬ LÝ ĐĂNG KÝ KHÓA HỌC
 * Yêu cầu: kiểm tra dữ liệu đầu vào (validation), thông báo thành công/thất bại
 */
require_once __DIR__ . '/config/database.php';

if (session_status() === PHP_SESSION_NONE) session_start();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ' . BASE_URL . '/index.php');
    exit;
}

$khoa_hoc_id   = isset($_POST['khoa_hoc_id']) ? (int) $_POST['khoa_hoc_id'] : 0;
$ho_ten        = trim($_POST['ho_ten'] ?? '');
$email         = trim($_POST['email'] ?? '');
$so_dien_thoai = trim($_POST['so_dien_thoai'] ?? '');
$ghi_chu       = trim($_POST['ghi_chu'] ?? '');

$errors = [];

// ---- Kiểm tra dữ liệu đầu vào (validation phía Server) ----
if ($khoa_hoc_id <= 0) {
    $errors[] = 'Khóa học không hợp lệ.';
}
if (mb_strlen($ho_ten) < 2) {
    $errors[] = 'Họ tên phải có ít nhất 2 ký tự.';
}
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errors[] = 'Email không hợp lệ.';
}
if (!preg_match('/^[0-9]{9,11}$/', $so_dien_thoai)) {
    $errors[] = 'Số điện thoại không hợp lệ (chỉ gồm 9-11 chữ số).';
}

// Kiểm tra khóa học có tồn tại không
if ($khoa_hoc_id > 0) {
    $check = $pdo->prepare("SELECT id FROM khoa_hoc WHERE id = :id AND trang_thai = 1");
    $check->execute([':id' => $khoa_hoc_id]);
    if (!$check->fetch()) {
        $errors[] = 'Khóa học không tồn tại hoặc đã đóng đăng ký.';
    }
}

if (!empty($errors)) {
    $_SESSION['flash_message'] = implode(' ', $errors);
    $_SESSION['flash_type'] = 'danger';
    header('Location: ' . BASE_URL . '/khoa-hoc-chi-tiet.php?id=' . $khoa_hoc_id);
    exit;
}

// ---- Thêm dữ liệu vào bảng hoc_vien_dang_ky ----
try {
    $stmt = $pdo->prepare("INSERT INTO hoc_vien_dang_ky (khoa_hoc_id, ho_ten, email, so_dien_thoai, ghi_chu)
                            VALUES (:khoa_hoc_id, :ho_ten, :email, :so_dien_thoai, :ghi_chu)");
    $stmt->execute([
        ':khoa_hoc_id'   => $khoa_hoc_id,
        ':ho_ten'        => $ho_ten,
        ':email'         => $email,
        ':so_dien_thoai' => $so_dien_thoai,
        ':ghi_chu'       => $ghi_chu,
    ]);

    header('Location: ' . BASE_URL . '/dang-ky-thanh-cong.php');
    exit;
} catch (PDOException $e) {
    $_SESSION['flash_message'] = 'Có lỗi xảy ra, vui lòng thử lại sau.';
    $_SESSION['flash_type'] = 'danger';
    header('Location: ' . BASE_URL . '/khoa-hoc-chi-tiet.php?id=' . $khoa_hoc_id);
    exit;
}

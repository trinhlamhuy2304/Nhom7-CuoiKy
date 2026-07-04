<?php
// File header.php - dùng chung cho toàn bộ trang người dùng (navbar + mở thẻ html/body)
if (!isset($pdo)) {
    require_once __DIR__ . '/../config/database.php';
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= isset($page_title) ? htmlspecialchars($page_title) . ' - ' : '' ?>Đăng Ký Khóa Học</title>

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
    <!-- CSS riêng của dự án -->
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/style.css">
</head>
<body>

<!-- ================= NAVBAR ================= -->
<nav class="navbar navbar-expand-lg navbar-dark bg-primary sticky-top shadow-sm">
    <div class="container">
        <a class="navbar-brand fw-bold" href="<?= BASE_URL ?>/index.php">
            <i class="bi bi-mortarboard-fill"></i> EduRegister
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNavbar">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="mainNavbar">
            <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
                <li class="nav-item">
                    <a class="nav-link" href="<?= BASE_URL ?>/index.php">Trang chủ</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="<?= BASE_URL ?>/index.php#danh-sach-khoa-hoc">Khóa học</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="<?= BASE_URL ?>/admin/login.php">
                        <i class="bi bi-person-lock"></i> Quản trị
                    </a>
                </li>
            </ul>

            <!-- Form tìm kiếm khóa học (yêu cầu 4.1: Tìm kiếm) -->
            <form class="d-flex ms-lg-3" role="search" method="GET" action="<?= BASE_URL ?>/index.php">
                <input class="form-control me-2" type="search" name="q" placeholder="Tìm khóa học..."
                       value="<?= isset($_GET['q']) ? htmlspecialchars($_GET['q']) : '' ?>">
                <button class="btn btn-light" type="submit"><i class="bi bi-search"></i></button>
            </form>
        </div>
    </div>
</nav>

<!-- Thông báo (session flash message) -->
<?php if (session_status() === PHP_SESSION_NONE) session_start();
if (!empty($_SESSION['flash_message'])): ?>
    <div class="container mt-3">
        <div class="alert alert-<?= $_SESSION['flash_type'] ?? 'info' ?> alert-dismissible fade show" role="alert">
            <?= htmlspecialchars($_SESSION['flash_message']) ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    </div>
    <?php unset($_SESSION['flash_message'], $_SESSION['flash_type']); ?>
<?php endif; ?>

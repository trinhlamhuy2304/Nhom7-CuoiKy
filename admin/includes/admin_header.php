<?php
// admin_header.php - Sidebar + mở thẻ html/body cho trang quản trị
if (!isset($pdo)) {
    require_once __DIR__ . '/../../config/database.php';
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= isset($page_title) ? htmlspecialchars($page_title) . ' - ' : '' ?>Trang quản trị</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/style.css">
</head>
<body>
<div class="d-flex">
    <!-- SIDEBAR -->
    <div class="admin-sidebar" style="width: 240px;">
        <div class="text-center text-white py-4 border-bottom border-secondary">
            <i class="bi bi-mortarboard-fill fs-3"></i>
            <h5 class="mt-2 mb-0">Admin Panel</h5>
        </div>
        <a href="<?= BASE_URL ?>/admin/index.php" class="<?= basename($_SERVER['PHP_SELF']) === 'index.php' && strpos($_SERVER['PHP_SELF'], '/admin/') !== false && strpos($_SERVER['PHP_SELF'], 'khoa-hoc') === false && strpos($_SERVER['PHP_SELF'], 'danh-muc') === false ? 'active' : '' ?>">
            <i class="bi bi-speedometer2"></i> Dashboard
        </a>
        <a href="<?= BASE_URL ?>/admin/khoa-hoc/index.php" class="<?= strpos($_SERVER['PHP_SELF'], '/khoa-hoc/') !== false ? 'active' : '' ?>">
            <i class="bi bi-journal-bookmark"></i> Quản lý khóa học
        </a>
        <a href="<?= BASE_URL ?>/admin/danh-muc/index.php" class="<?= strpos($_SERVER['PHP_SELF'], '/danh-muc/') !== false ? 'active' : '' ?>">
            <i class="bi bi-tags"></i> Quản lý danh mục
        </a>
        <a href="<?= BASE_URL ?>/admin/dang-ky/index.php" class="<?= strpos($_SERVER['PHP_SELF'], '/dang-ky/') !== false ? 'active' : '' ?>">
            <i class="bi bi-person-check"></i> Danh sách đăng ký
        </a>
        <a href="<?= BASE_URL ?>/index.php" target="_blank">
            <i class="bi bi-box-arrow-up-right"></i> Xem website
        </a>
        <a href="<?= BASE_URL ?>/admin/logout.php" class="text-danger">
            <i class="bi bi-box-arrow-left"></i> Đăng xuất
        </a>
    </div>

    <!-- NỘI DUNG -->
    <div class="flex-grow-1 p-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h3 class="fw-bold mb-0"><?= isset($page_title) ? htmlspecialchars($page_title) : 'Trang quản trị' ?></h3>
            <span class="text-muted">
                <i class="bi bi-person-circle"></i> Xin chào, <strong><?= htmlspecialchars($_SESSION['admin_name'] ?? '') ?></strong>
            </span>
        </div>

        <?php if (!empty($_SESSION['flash_message'])): ?>
            <div class="alert alert-<?= $_SESSION['flash_type'] ?? 'info' ?> alert-dismissible fade show" role="alert">
                <?= htmlspecialchars($_SESSION['flash_message']) ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            <?php unset($_SESSION['flash_message'], $_SESSION['flash_type']); ?>
        <?php endif; ?>

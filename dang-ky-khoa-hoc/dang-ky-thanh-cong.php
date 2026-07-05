<?php
require_once __DIR__ . '/config/database.php';
$page_title = 'Đăng ký thành công';
require_once __DIR__ . '/includes/header.php';
?>

<div class="container my-5 text-center">
    <i class="bi bi-check-circle-fill text-success" style="font-size: 5rem;"></i>
    <h2 class="mt-3 fw-bold">Đăng ký khóa học thành công!</h2>
    <p class="text-muted">Cảm ơn bạn đã đăng ký. Chúng tôi sẽ liên hệ với bạn qua email/số điện thoại sớm nhất.</p>
    <a href="<?= BASE_URL ?>/index.php" class="btn btn-primary mt-2">
        <i class="bi bi-house-door"></i> Quay về trang chủ
    </a>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>

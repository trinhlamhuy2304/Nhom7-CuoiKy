<?php

// ==== CẤU HÌNH KẾT NỐI - CHỈNH LẠI CHO PHÙ HỢP VỚI MÁY CỦA BẠN ====
define('DB_HOST', 'localhost');
define('DB_NAME', 'dangkykhoahoc');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_CHARSET', 'utf8mb4');

// Đường dẫn gốc của website (dùng cho link, ảnh...). Sửa lại theo tên thư mục project của bạn.
define('BASE_URL', '/dang-ky-khoa-hoc');

try {
    $dsn = 'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=' . DB_CHARSET;
    $options = [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES   => false,
    ];
    $pdo = new PDO($dsn, DB_USER, DB_PASS, $options);
} catch (PDOException $e) {
    die('Lỗi kết nối cơ sở dữ liệu: ' . $e->getMessage());
}

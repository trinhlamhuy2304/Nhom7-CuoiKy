<?php
/**
 * Kiểm tra đăng nhập - include file này ở đầu MỌI trang admin (trừ login.php)
 */
if (session_status() === PHP_SESSION_NONE) session_start();

if (empty($_SESSION['admin_id'])) {
    header('Location: ' . BASE_URL . '/admin/login.php');
    exit;
}

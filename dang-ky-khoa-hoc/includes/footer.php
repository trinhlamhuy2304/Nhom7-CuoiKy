<!-- ================= FOOTER ================= -->
<footer class="bg-dark text-light pt-5 pb-3 mt-5">
    <div class="container">
        <div class="row">
            <div class="col-md-4 mb-3">
                <h5><i class="bi bi-mortarboard-fill"></i> EduRegister</h5>
                <p class="small text-secondary">
                    Website đăng ký khóa học trực tuyến - Đồ án cuối kỳ môn Lập trình Web.
                </p>
            </div>
            <div class="col-md-4 mb-3">
                <h5>Liên kết</h5>
                <ul class="list-unstyled">
                    <li><a href="<?= BASE_URL ?>/index.php" class="text-light text-decoration-none">Trang chủ</a></li>
                    <li><a href="<?= BASE_URL ?>/admin/login.php" class="text-light text-decoration-none">Quản trị</a></li>
                </ul>
            </div>
            <div class="col-md-4 mb-3">
                <h5>Liên hệ</h5>
                <p class="small mb-1"><i class="bi bi-envelope"></i> lienhe@eduregister.local</p>
                <p class="small mb-1"><i class="bi bi-telephone"></i> 0123 456 789</p>
            </div>
        </div>
        <hr class="border-secondary">
        <p class="text-center small text-secondary mb-0">
            &copy; <?= date('Y') ?> EduRegister - Đồ án cuối kỳ nhóm dịch vụ: Đăng ký khóa học.
        </p>
    </div>
</footer>

<!-- Bootstrap JS Bundle -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<!-- JS riêng của dự án -->
<script src="<?= BASE_URL ?>/assets/js/script.js"></script>
</body>
</html>

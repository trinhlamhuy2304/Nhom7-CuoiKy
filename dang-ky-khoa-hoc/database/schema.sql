-- =========================================================
-- CSDL: Website Đăng ký khóa học
-- Đồ án cuối kỳ - Lập trình Web
-- =========================================================

CREATE DATABASE IF NOT EXISTS dangkykhoahoc CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE dangkykhoahoc;

-- ---------------------------------------------------------
-- Bảng 1: admin - Tài khoản quản trị
-- ---------------------------------------------------------
CREATE TABLE admin (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL, -- lưu bằng password_hash()
    full_name VARCHAR(100) NOT NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- Tài khoản mặc định: username = admin , password = admin123
-- Mật khẩu đã được hash bằng password_hash('admin123', PASSWORD_DEFAULT)
INSERT INTO admin (username, password, full_name) VALUES
('admin', '$2y$10$92I2sEwG6Y1e1yA0F0Zsf.Bq0zW1nCM8P0F1qF7XyO2t1qk0m6C1a', 'Quản trị viên');

-- ---------------------------------------------------------
-- Bảng 2: danh_muc - Danh mục khóa học
-- ---------------------------------------------------------
CREATE TABLE danh_muc (
    id INT AUTO_INCREMENT PRIMARY KEY,
    ten_danh_muc VARCHAR(100) NOT NULL,
    mo_ta VARCHAR(255) DEFAULT NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

INSERT INTO danh_muc (ten_danh_muc, mo_ta) VALUES
('Lập trình', 'Các khóa học về lập trình phần mềm, web, mobile'),
('Ngoại ngữ', 'Các khóa học tiếng Anh, tiếng Nhật, tiếng Hàn...'),
('Kỹ năng mềm', 'Giao tiếp, thuyết trình, quản lý thời gian'),
('Thiết kế đồ họa', 'Photoshop, Illustrator, UI/UX');

-- ---------------------------------------------------------
-- Bảng 3: khoa_hoc - Danh sách khóa học
-- Quan hệ: khoa_hoc.danh_muc_id -> danh_muc.id (N-1)
-- ---------------------------------------------------------
CREATE TABLE khoa_hoc (
    id INT AUTO_INCREMENT PRIMARY KEY,
    danh_muc_id INT NOT NULL,
    ten_khoa_hoc VARCHAR(150) NOT NULL,
    mo_ta TEXT DEFAULT NULL,
    giang_vien VARCHAR(100) DEFAULT NULL,
    hoc_phi DECIMAL(10,2) NOT NULL DEFAULT 0,
    thoi_luong VARCHAR(50) DEFAULT NULL, -- vd: "8 tuần"
    hinh_anh VARCHAR(255) DEFAULT NULL,
    so_luong_toi_da INT DEFAULT 30,
    ngay_khai_giang DATE DEFAULT NULL,
    trang_thai TINYINT(1) DEFAULT 1, -- 1: đang mở, 0: đã đóng
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_khoahoc_danhmuc FOREIGN KEY (danh_muc_id)
        REFERENCES danh_muc(id) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB;

INSERT INTO khoa_hoc (danh_muc_id, ten_khoa_hoc, mo_ta, giang_vien, hoc_phi, thoi_luong, hinh_anh, so_luong_toi_da, ngay_khai_giang) VALUES
(1, 'Lập trình Web PHP & MySQL cơ bản', 'Khóa học giúp bạn xây dựng website từ con số 0 với PHP thuần và MySQL.', 'Nguyễn Văn A', 1200000, '8 tuần', 'khoa-hoc-mau-1.jpg', 30, '2026-08-01'),
(1, 'Lập trình JavaScript nâng cao', 'Tìm hiểu sâu về JavaScript, ES6+, DOM và xử lý bất đồng bộ.', 'Trần Thị B', 1500000, '6 tuần', 'khoa-hoc-mau-2.jpg', 25, '2026-08-10'),
(2, 'Tiếng Anh giao tiếp căn bản', 'Phát triển kỹ năng nghe nói tiếng Anh trong 3 tháng.', 'Lê Văn C', 2000000, '12 tuần', 'khoa-hoc-mau-3.jpg', 20, '2026-08-05'),
(3, 'Kỹ năng thuyết trình chuyên nghiệp', 'Rèn luyện sự tự tin và kỹ năng thuyết trình trước đám đông.', 'Phạm Thị D', 900000, '4 tuần', 'khoa-hoc-mau-4.jpg', 40, '2026-08-15');

-- ---------------------------------------------------------
-- Bảng 4: hoc_vien_dang_ky - Thông tin đăng ký khóa học
-- Quan hệ: hoc_vien_dang_ky.khoa_hoc_id -> khoa_hoc.id (N-1)
-- ---------------------------------------------------------
CREATE TABLE hoc_vien_dang_ky (
    id INT AUTO_INCREMENT PRIMARY KEY,
    khoa_hoc_id INT NOT NULL,
    ho_ten VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL,
    so_dien_thoai VARCHAR(20) NOT NULL,
    ghi_chu VARCHAR(255) DEFAULT NULL,
    trang_thai VARCHAR(20) DEFAULT 'cho_xu_ly', -- cho_xu_ly, da_duyet, da_huy
    ngay_dang_ky DATETIME DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_dangky_khoahoc FOREIGN KEY (khoa_hoc_id)
        REFERENCES khoa_hoc(id) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB;

-- Dữ liệu mẫu (có thể xóa)
INSERT INTO hoc_vien_dang_ky (khoa_hoc_id, ho_ten, email, so_dien_thoai, ghi_chu) VALUES
(1, 'Nguyễn Văn Học', 'hocvien1@gmail.com', '0901234567', 'Đăng ký mẫu để test');

// =========================================================
// File JavaScript dùng chung - Website Đăng ký khóa học
// Thành viên có thể bổ sung thêm validate, hiệu ứng tại đây
// =========================================================

document.addEventListener('DOMContentLoaded', function () {

    // Validate phía Client cho form đăng ký khóa học (nếu tồn tại trên trang)
    const formDangKy = document.getElementById('formDangKy');
    if (formDangKy) {
        formDangKy.addEventListener('submit', function (e) {
            const hoTen = formDangKy.querySelector('[name="ho_ten"]').value.trim();
            const sdt = formDangKy.querySelector('[name="so_dien_thoai"]').value.trim();

            if (hoTen.length < 2) {
                alert('Vui lòng nhập họ tên hợp lệ (ít nhất 2 ký tự).');
                e.preventDefault();
                return;
            }

            if (!/^[0-9]{9,11}$/.test(sdt)) {
                alert('Số điện thoại không hợp lệ (chỉ gồm 9-11 chữ số).');
                e.preventDefault();
                return;
            }
        });
    }

    // Tự động đóng thông báo (alert) sau 4 giây
    const alerts = document.querySelectorAll('.alert');
    alerts.forEach(function (alertEl) {
        setTimeout(function () {
            const bsAlert = bootstrap.Alert.getOrCreateInstance(alertEl);
            bsAlert.close();
        }, 4000);
    });

});

// TODO cho thành viên: thêm hiệu ứng lọc/sắp xếp danh sách khóa học bằng JS (điểm khuyến khích)

# 🐾 PETSHOP - Hệ Thống Quản Lý & Chăm Sóc Thú Cưng Toàn Diện

PETSHOP là một ứng dụng web toàn diện được thiết kế để quản lý cửa hàng thú cưng, đặt lịch dịch vụ chăm sóc (Spa, Khách sạn thú cưng) kết hợp với Trợ lý ảo AI thông minh và hệ thống tích điểm thăng hạng hội viên độc đáo. Dự án được phát triển dựa trên mô hình kiến trúc **MVC (Model-View-Controller)** bằng ngôn ngữ **PHP** thuần túy, mang lại hiệu năng cao và cấu trúc sạch sẽ.

---

## 🌟 Các Tính Năng Nổi Bật

### 1. 🛒 Cửa hàng trực tuyến & Giỏ hàng AJAX

- **Duyệt & Tìm kiếm sản phẩm:** Giao diện mua sắm trực quan, lọc sản phẩm theo danh mục.
- **Yêu thích (Wishlist):** Thả tim sản phẩm yêu thích và đồng bộ trực tiếp mà không cần tải lại trang (AJAX).
- **Giỏ hàng mượt mà:** Thêm sản phẩm vào giỏ hàng ngay lập tức từ trang danh sách hoặc trang chi tiết thông qua AJAX, cập nhật badge giỏ hàng trên Header kèm hiệu ứng hoạt họa và Toast thông báo hiển thị thời gian thực.

### 2. 🏥 Đặt lịch Dịch vụ & Khách sạn Thú cưng

- **Đặt lịch Spa:** Chọn dịch vụ tắm, cắt tỉa lông, làm móng cho thú cưng một cách nhanh chóng.
- **Khách sạn Thú cưng (Boarding):** Đặt chỗ lưu trú, chăm sóc thú cưng khi Sen đi vắng.
- **Quản lý lịch hẹn:** Theo dõi trạng thái lịch hẹn trực quan từ trang lịch sử cá nhân.

### 3. 🤖 Trợ lý ảo AI thông minh (Pawsy)

- Tích hợp **OpenRouter API** (sử dụng mô hình ngôn ngữ Llama 3.1) để trò chuyện với khách hàng.
- Tư vấn sức khỏe, dinh dưỡng, triệu chứng bệnh thường gặp ở thú cưng 24/7.
- Gợi ý trực tiếp các dịch vụ phù hợp tại cửa hàng dựa trên nhu cầu của khách hàng.

### 4. 🎖️ Hệ thống Hạng Hội viên (Membership Tier)

- Tự động tích lũy chi tiêu thường niên để thăng hạng: **Đồng ➔ Bạc ➔ Vàng ➔ Bạch Kim ➔ VIP**.
- Cập nhật thanh tiến trình thăng hạng và số tiền cần chi tiêu thêm trực quan tại menu cá nhân.
- Áp dụng tự động các đặc quyền ưu đãi giảm giá hóa đơn tương ứng với từng cấp độ hội viên khi thanh toán đơn hàng.

### 5. 🔔 Hệ thống Thông báo nội bộ (Notifications)

- Thông báo tự động cho người dùng khi: xác nhận đơn hàng, lịch hẹn dịch vụ được phê duyệt, hoặc được nâng hạng thành viên mới.
- Giao diện xem nhanh thông báo tích hợp trực tiếp trên Header với cơ chế tự động làm mới (polling).

### 6. 📊 Bảng điều khiển Quản trị (Admin Panel)

- **Tổng quan (Dashboard):** Biểu đồ doanh thu, số liệu khách hàng, thống kê số lượng đơn hàng.
- **Quản lý kho hàng & sản phẩm:** Thêm, sửa, xóa, kiểm soát số lượng tồn kho của từng mặt hàng.
- **Quản lý đơn hàng & dịch vụ:** Phê duyệt đơn hàng, xác nhận lịch hẹn chăm sóc thú cưng.
- **Chấm công nhân viên (Attendance):** Quản lý chuyên cần của nhân viên hàng ngày tự động theo múi giờ Việt Nam (`Asia/Ho_Chi_Minh`).
- **Quản lý lương (Payroll):** Tính toán bảng lương tự động hàng tháng dựa trên số ngày công và chức vụ.
- **Lịch sử hoạt động (Activity Logs):** Ghi nhận nhật ký thao tác quan trọng trên hệ thống để dễ dàng kiểm soát.

---

## 🛠️ Công Nghệ Sử Dụng

- **Backend:** PHP 8.x (Kiến trúc MVC tự xây dựng, kết nối PDO MySQL an toàn).
- **Frontend:** Tailwind CSS (qua CDN), Alpine.js (quản lý trạng thái giao diện), Font Awesome 6.
- **Database:** MySQL.
- **Gửi Email:** SMTP Gmail và Resend API (Gửi email xác nhận qua giao thức HTTPS bảo mật).
- **AI Engine:** OpenRouter API Client.

---

## 📂 Cấu Trúc Thư Mục Dự Án

```text
PETSHOP/
├── app/                      # Mã nguồn ứng dụng chính (PHP MVC)
│   ├── config/               # Chứa các file cấu hình hệ thống
│   │   ├── config.php        # Cấu hình chính (chỉ nạp secrets.php nếu có)
│   │   └── secrets.php       # Lưu trữ API keys bí mật (Không commit lên Git)
│   ├── controllers/          # Bộ điều hướng (Controllers)
│   ├── core/                 # Core Framework (App, Controller, Database)
│   ├── helpers/              # Các hàm bổ trợ (Session, Mailer, Flash)
│   ├── models/               # Xử lý logic dữ liệu (Models)
│   └── views/                # Giao diện hiển thị (Views)
├── public/                   # Thư mục công khai truy cập từ trình duyệt
│   ├── css/                  # Custom CSS styles
│   ├── js/                   # Custom JavaScript (AJAX & Toasts)
│   ├── images/               # Hình ảnh sản phẩm
│   └── index.php             # File khởi chạy ứng dụng chính (Front Controller)
├── .gitignore                # Khai báo các file Git cần bỏ qua
├── database.sql              # Cấu trúc cơ sở dữ liệu ban đầu
└── README.md                 # Tài liệu hướng dẫn dự án
```

---

## 🚀 Hướng Dẫn Cài Đặt & Cấu Hình Local

### 1. Yêu cầu hệ thống

- Đã cài đặt phần mềm **XAMPP** (hỗ trợ PHP 8.0 trở lên và MySQL).
- Git (nếu muốn quản lý mã nguồn).

### 2. Các bước cài đặt

1. Tải dự án và đặt vào thư mục `htdocs` của XAMPP:
   ```bash
   cd C:/xampp/htdocs
   git clone https://github.com/tuan1503vp/PETSHOP.git
   ```
2. Khởi động **Apache** và **MySQL** trên XAMPP Control Panel.
3. Tạo cơ sở dữ liệu MySQL:
   - Truy cập `http://localhost/phpmyadmin/`.
   - Tạo cơ sở dữ liệu mới tên là `petshop_db`.
   - Import file `database.sql` và `advanced_features.sql` vào cơ sở dữ liệu vừa tạo.

### 3. Trải nghiệm ứng dụng

Mở trình duyệt web và truy cập địa chỉ:
https://pet.kesug.com/

# Hệ Thống Quản Lý Nhân Viên (Employee Management System)

## 📋 Mô Tả

Ứng dụng web CRUD (Create, Read, Update, Delete) được xây dựng bằng PHP và MySQL, cho phép quản lý thông tin nhân viên công ty. Ứng dụng có giao diện thân thiện với Bootstrap 5 và hỗ trợ tìm kiếm theo tên hoặc email, quản lý trạng thái làm việc và mức lương.

## ✨ Các Tính Năng

- ✅ **Xem danh sách nhân viên** - Hiển thị tất cả nhân viên dưới dạng bảng
- ✅ **Tìm kiếm** - Tìm kiếm nhân viên theo tên hoặc email
- ✅ **Thêm nhân viên mới** - Form thêm mới với xác thực dữ liệu
- ✅ **Sửa thông tin nhân viên** - Cập nhật thông tin có sẵn
- ✅ **Xóa nhân viên** - Xóa khỏi hệ thống với xác nhận
- ✅ **Quản lý trạng thái** - Đang làm việc / Đã nghỉ
- ✅ **Quản lý lương** - Hiển thị lương theo định dạng tiền tệ
- ✅ **Flash Messages** - Hiển thị thông báo thành công/lỗi
- ✅ **Xác thực dữ liệu** - Kiểm tra tính hợp lệ trước khi lưu
- ✅ **Giao diện Responsive** - Tương thích với tất cả thiết bị
- ✅ **Hỗ trợ Unicode** - Hiển thị chính xác tiếng Việt

## 📁 Cấu Trúc Thư Mục

```
lab011_Trần Mạnh Quân_Topic2/
├── db.php              # Kết nối cơ sở dữ liệu (PDO)
├── index.php           # Danh sách + Tìm kiếm + Flash message
├── create.php          # Form thêm mới + Xử lý thêm + Validate
├── edit.php            # Form sửa + Xử lý cập nhật + Validate
├── delete.php          # Xử lý xóa
├── database.sql        # Tạo bảng employees và dữ liệu mẫu
└── README.md           # Tệp này
```

## 🗄️ Cấu Trúc Cơ Sở Dữ Liệu

### Database: `lab11_employees`
**Character Set**: `utf8mb4_unicode_ci` (Hỗ trợ tiếng Việt)

### Bảng: `employees`

| Cột | Kiểu Dữ Liệu | Ràng Buộc | Mô Tả |
|-----|--------------|----------|-------|
| `id` | INT | PRIMARY KEY, AUTO_INCREMENT | ID nhân viên |
| `full_name` | VARCHAR(120) | NOT NULL | Họ tên nhân viên (bắt buộc) |
| `email` | VARCHAR(120) | NOT NULL, UNIQUE | Email (bắt buộc, không trùng lặp) |
| `phone` | VARCHAR(20) | NULL | Số điện thoại |
| `position` | VARCHAR(80) | NOT NULL | Vị trí công việc (bắt buộc) |
| `salary` | INT | NULL | Lương (VNĐ) |
| `status` | TINYINT(1) | DEFAULT 1 | Trạng thái (1: Đang làm, 0: Đã nghỉ) |
| `created_at` | DATETIME | DEFAULT CURRENT_TIMESTAMP | Thời gian tạo |
| `updated_at` | DATETIME | DEFAULT CURRENT_TIMESTAMP ON UPDATE | Thời gian cập nhật |

## 🚀 Hướng Dẫn Cài Đặt

### Yêu Cầu
- PHP 7.2+
- MySQL 5.7+
- XAMPP hoặc máy chủ web tương tự
- Trình duyệt web hiện đại

### Các Bước Cài Đặt

#### 1. Tạo Cơ Sở Dữ Liệu

**Cách 1: Sử dụng phpMyAdmin**
- Mở phpMyAdmin: `http://localhost/phpmyadmin`
- Click **"Mới"** để tạo database mới
- Nhập tên: `lab11_employees`
- Chọn Charset: `utf8mb4_unicode_ci`
- Click **"Tạo"**
- Chọn database vừa tạo, click tab **"SQL"**
- Copy nội dung từ `database.sql` vào khung soạn thảo
- Click **"Thực thi"**

**Cách 2: Sử dụng phpMyAdmin Import**
- Mở phpMyAdmin: `http://localhost/phpmyadmin`
- Tạo database `lab11_employees` (như trên)
- Chọn database, click tab **"Nhập"**
- Click **"Chọn tệp"**, chọn `database.sql`
- Click **"Thực thi"**

**Cách 3: Sử dụng Command Line**
```bash
mysql -u root -p < database.sql
```

#### 2. Xác Minh Kết Nối

File `db.php` đã được cấu hình sẵn:
```php
$host = 'localhost';
$dbname = 'lab11_employees';
$username = 'root';
$password = ''; // Mặc định rỗng
```

Nếu cần thay đổi, hãy sửa các giá trị trên trong file `db.php`

#### 3. Truy Cập Ứng Dụng

Mở trình duyệt và đi đến:
```
http://localhost/lab011_Trần%20Mạnh%20Quân_20230496/lab011_Trần%20Mạnh%20Quân_Topic2/
```

hoặc

```
http://localhost/lab011_Trần%20Mạnh%20Quân_20230496/lab011_Trần%20Mạnh%20Quân_Topic2/index.php
```

## 📖 Hướng Dẫn Sử Dụng

### 🏠 Trang Chủ (index.php)
- Hiển thị danh sách tất cả nhân viên
- Nhập từ khóa để tìm kiếm (theo tên hoặc email)
- Click **"Reset"** để xóa bộ lọc và xem tất cả
- Click **"Sửa"** để cập nhật thông tin nhân viên
- Click **"Xóa"** để xóa nhân viên (có xác nhận)
- Xem trạng thái làm việc qua badge màu:
  - 🟢 **Đang làm việc** (Status = 1)
  - ⚫ **Đã nghỉ** (Status = 0)

### ➕ Thêm Nhân Viên (create.php)
1. Click **"+ Thêm Nhân viên"** từ trang chủ
2. Điền các thông tin:
   - **Họ Tên**: Bắt buộc, không để trống
   - **Email**: Bắt buộc, phải là email hợp lệ, không được trùng lặp
   - **Điện Thoại**: Tùy chọn
   - **Vị Trí**: Bắt buộc (VD: Developer, Manager, Tester, HR...)
   - **Lương**: Tùy chọn, nhập số tiền (VNĐ)
   - **Trạng Thái**: Chọn "Đang làm việc" hoặc "Đã nghỉ"
3. Click **"Thêm"** để lưu

### ✏️ Sửa Nhân Viên (edit.php)
1. Click nút **"Sửa"** trên hàng nhân viên cần chỉnh sửa
2. Cập nhật thông tin cần thiết
3. Lưu ý: Email phải duy nhất (trừ email hiện tại của nhân viên)
4. Click **"Cập nhật"**

### ❌ Xóa Nhân Viên (delete.php)
1. Click nút **"Xóa"** trên hàng nhân viên
2. Xác nhận xóa trong hộp thoại popup (hiển thị tên nhân viên)
3. Nhân viên sẽ bị xóa khỏi hệ thống

### 🔍 Tìm Kiếm
- Nhập từ khóa trong ô tìm kiếm
- Click **"Tìm kiếm"** hoặc nhấn Enter
- Kết quả sẽ lọc theo Tên hoặc Email
- Click **"Reset"** để xem lại tất cả nhân viên

## ✔️ Xác Thực Dữ Liệu

Ứng dụng kiểm tra:

| Trường | Kiểm Tra |
|--------|----------|
| **Họ Tên** | Không để trống |
| **Email** | Email hợp lệ, không trùng lặp |
| **Vị Trí** | Không để trống |
| **Lương** | Phải là số không âm (nếu nhập) |
| **Trạng Thái** | Đang làm (1) / Đã nghỉ (0) |

## 🔒 Bảo Mật

- ✅ Sử dụng PDO Prepared Statements để phòng chống SQL Injection
- ✅ Sử dụng `htmlspecialchars()` để phòng chống XSS
- ✅ Session management để quản lý trạng thái người dùng
- ✅ Xác nhận xóa để tránh xóa nhầm
- ✅ Email unique constraint để tránh trùng lặp

## 🎨 Công Nghệ Sử Dụng

- **Backend**: PHP 7.2+
- **Database**: MySQL 5.7+
- **Frontend**: HTML5, CSS3
- **Framework CSS**: Bootstrap 5 (CDN)
- **Database Connection**: PDO (PHP Data Objects)
- **Character Encoding**: UTF-8MB4 (Hỗ trợ Tiếng Việt)

## 📝 Dữ Liệu Mẫu

Cơ sở dữ liệu được cài đặt sẵn 5 nhân viên:

| ID | Tên | Email | Vị Trí | Lương | Trạng Thái |
|----|-----|-------|--------|-------|-----------|
| 1 | Nguyễn Văn A | vana@example.com | Developer | 15,000,000₫ | Đang làm |
| 2 | Trần Thị B | btran@example.com | Tester | 12,000,000₫ | Đang làm |
| 3 | Lê Văn C | cle@example.com | Manager | 25,000,000₫ | Đang làm |
| 4 | Phạm Thị D | dpham@example.com | HR | 10,000,000₫ | Đã nghỉ |
| 5 | Hoàng Văn E | ehoang@example.com | Intern | 5,000,000₫ | Đang làm |

## 🐛 Xử Lý Sự Cố

### Lỗi: "Connection failed"
- Kiểm tra MySQL đã chạy chưa
- Kiểm tra tên người dùng, mật khẩu trong `db.php`
- Kiểm tra database `lab11_employees` đã tạo chưa

### Lỗi: "Table 'lab11_employees.employees' doesn't exist"
- Nhập lại database.sql qua phpMyAdmin
- Hoặc chạy lệnh SQL từ command line

### Lỗi: "Duplicate entry for key 'email'"
- Email đã tồn tại trong hệ thống
- Sử dụng email khác hoặc sửa email cũ

### Hiển thị ký tự lạ tiếng Việt
- Kiểm tra xem database đã set charset `utf8mb4_unicode_ci` chưa
- Kiểm tra trong phpMyAdmin: tab "Thao tác" → "Sắp xếp" → chọn `utf8mb4_unicode_ci`

### Lỗi 404
- Kiểm tra đường dẫn URL
- Đảm bảo tệp nằm trong `xampp/htdocs`

## 📚 Tài Liệu Tham Khảo

- [PHP Documentation](https://www.php.net/docs.php)
- [MySQL Documentation](https://dev.mysql.com/doc/)
- [Bootstrap 5](https://getbootstrap.com/docs/5.0/)
- [PDO Tutorial](https://www.php.net/manual/en/book.pdo.php)

## 🗑️ Xóa Database

Nếu cần xóa database:

**Cách 1: Sử dụng phpMyAdmin**
- Mở phpMyAdmin
- Chọn database `lab11_employees`
- Click tab **"Thao tác"**
- Click **"Xóa cơ sở dữ liệu"**
- Xác nhận xóa

**Cách 2: Sử dụng Command Line**
```bash
mysql -u root -e "DROP DATABASE IF EXISTS lab11_employees;"
```

## 👨‍💻 Tác Giả

**Trần Mạnh Quân** - Lab 011 Topic 2

## 📅 Ngày Tạo

Tháng 1, 2026


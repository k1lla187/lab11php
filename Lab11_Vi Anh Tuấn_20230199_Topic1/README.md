# Hệ Thống Quản Lý Sinh Viên (Student Management System)

## 📋 Mô Tả

Ứng dụng web CRUD (Create, Read, Update, Delete) được xây dựng bằng PHP và MySQL, cho phép quản lý thông tin sinh viên. Ứng dụng có giao diện thân thiện với Bootstrap 5 và hỗ trợ tìm kiếm, thêm, sửa, xóa sinh viên.

## ✨ Các Tính Năng

- ✅ **Xem danh sách sinh viên** - Hiển thị tất cả sinh viên dưới dạng bảng
- ✅ **Tìm kiếm** - Tìm kiếm sinh viên theo tên hoặc email
- ✅ **Thêm sinh viên mới** - Form thêm mới với xác thực dữ liệu
- ✅ **Sửa thông tin sinh viên** - Cập nhật thông tin sinh viên có sẵn
- ✅ **Xóa sinh viên** - Xóa sinh viên khỏi hệ thống
- ✅ **Flash Messages** - Hiển thị thông báo thành công/lỗi
- ✅ **Xác thực dữ liệu** - Kiểm tra tính hợp lệ trước khi lưu
- ✅ **Giao diện Responsive** - Tương thích với tất cả thiết bị

## 📁 Cấu Trúc Thư Mục

```
lab011_Trần Mạnh Quân_Topic1/
├── db.php              # Kết nối cơ sở dữ liệu
├── index.php           # Danh sách + Tìm kiếm + Hiển thị Flash message
├── create.php          # Form thêm mới + Xử lý thêm + Validate
├── edit.php            # Form sửa + Xử lý cập nhật + Validate
├── delete.php          # Xử lý xóa
├── database.sql        # Tạo bảng và dữ liệu mẫu
├── style.css           # CSS tùy chỉnh (kết hợp Bootstrap CDN)
└── README.md           # Tệp này
```

## 🗄️ Cấu Trúc Cơ Sở Dữ Liệu

### Bảng: `students`

| Cột | Kiểu Dữ Liệu | Ràng Buộc | Mô Tả |
|-----|--------------|----------|-------|
| `id` | INT | PRIMARY KEY, AUTO_INCREMENT | ID sinh viên |
| `name` | VARCHAR(100) | NOT NULL | Tên sinh viên |
| `email` | VARCHAR(100) | NOT NULL, UNIQUE | Email sinh viên |
| `phone` | VARCHAR(20) | - | Số điện thoại |
| `date_of_birth` | DATE | - | Ngày sinh |
| `gpa` | FLOAT | - | Điểm GPA |
| `created_at` | TIMESTAMP | DEFAULT CURRENT_TIMESTAMP | Thời gian tạo |
| `updated_at` | TIMESTAMP | DEFAULT CURRENT_TIMESTAMP ON UPDATE | Thời gian cập nhật |

## 🚀 Hướng Dẫn Cài Đặt

### Yêu Cầu
- PHP 7.0+
- MySQL 5.7+
- XAMPP hoặc máy chủ web tương tự
- Trình duyệt web hiện đại

### Các Bước Cài Đặt

#### 1. Tạo Cơ Sở Dữ Liệu

**Cách 1: Sử dụng phpMyAdmin**
- Mở phpMyAdmin: `http://localhost/phpmyadmin`
- Tạo cơ sở dữ liệu mới tên là `student_management`
- Nhập nội dung từ tệp `database.sql` vào tab SQL
- Click "Thực thi"

**Cách 2: Sử dụng Command Line**
```bash
mysql -u root -p < database.sql
```

#### 2. Cấu Hình Kết Nối (Nếu cần)

Mở tệp `db.php` và kiểm tra:
```php
define('DB_HOST', 'localhost');      // Địa chỉ máy chủ
define('DB_USER', 'root');           // Tên người dùng MySQL
define('DB_PASS', '');               // Mật khẩu MySQL
define('DB_NAME', 'lab11_categories'); // Tên CSDL
```

#### 3. Truy Cập Ứng Dụng

Mở trình duyệt và đi đến:
```
http://localhost/lab011_Trần%20Mạnh%20Quân_20230496/lab011_Trần%20Mạnh%20Quân_Topic1/
```

hoặc

```
http://localhost/lab011_Trần%20Mạnh%20Quân_20230496/lab011_Trần%20Mạnh%20Quân_Topic1/index.php
```

## 📖 Hướng Dẫn Sử Dụng

### 🏠 Trang Chủ (index.php)
- Hiển thị danh sách tất cả sinh viên
- Nhập từ khóa trong ô tìm kiếm để tìm theo tên hoặc email
- Click "Thêm Sinh Viên Mới" để thêm sinh viên
- Click "Sửa" để cập nhật thông tin
- Click "Xóa" để xóa sinh viên (có xác nhận)

### ➕ Thêm Sinh Viên (create.php)
1. Click "Thêm Sinh Viên Mới"
2. Điền các thông tin:
   - **Tên**: Tối thiểu 3 ký tự (bắt buộc)
   - **Email**: Email hợp lệ, không trùng với email có sẵn (bắt buộc)
   - **Điện thoại**: Không bắt buộc
   - **Ngày sinh**: Định dạng YYYY-MM-DD, không bắt buộc
   - **GPA**: Số từ 0 đến 4, không bắt buộc
3. Click "Thêm Sinh Viên"

### ✏️ Sửa Sinh Viên (edit.php)
1. Click nút "Sửa" trên hàng sinh viên
2. Cập nhật thông tin cần thiết
3. Click "Cập Nhật"

### ❌ Xóa Sinh Viên (delete.php)
1. Click nút "Xóa" trên hàng sinh viên
2. Xác nhận xóa trong hộp thoại
3. Sinh viên sẽ bị xóa khỏi hệ thống

## ✔️ Xác Thực Dữ Liệu

Ứng dụng kiểm tra:

| Trường | Kiểm Tra |
|--------|----------|
| **Tên** | Không để trống, ≥ 3 ký tự |
| **Email** | Không để trống, định dạng email hợp lệ, không trùng lặp |
| **GPA** | Phải là số, nằm trong khoảng 0-4 |
| **Ngày sinh** | Định dạng ngày hợp lệ |

## 🔒 Bảo Mật

- ✅ Sử dụng `htmlspecialchars()` để phòng chống XSS
- ✅ Sử dụng `real_escape_string()` để phòng chống SQL Injection
- ✅ Session management để quản lý trạng thái người dùng
- ✅ Xác nhận xóa để tránh xóa nhầm

## 🎨 Công Nghệ Sử Dụng

- **Backend**: PHP 7.0+
- **Database**: MySQL
- **Frontend**: HTML5, CSS3
- **Framework CSS**: Bootstrap 5 (CDN)
- **Session Management**: PHP Session

## 📝 Dữ Liệu Mẫu

Cơ sở dữ liệu được cài đặt sẵn 4 sinh viên:

1. Nguyễn Văn A - GPA: 3.5
2. Trần Thị B - GPA: 3.8
3. Lê Minh C - GPA: 3.2
4. Phạm Thị D - GPA: 3.9

## 🐛 Xử Lý Sự Cố

### Lỗi: "Connection failed"
- Kiểm tra MySQL đã chạy chưa
- Kiểm tra tên người dùng, mật khẩu trong `db.php`
- Kiểm tra cơ sở dữ liệu `lab11_categories` đã tạo chưa

### Lỗi: "Email này đã tồn tại"
- Email phải là duy nhất
- Kiểm tra lại email đã nhập

### Lỗi 404
- Kiểm tra đường dẫn URL
- Đảm bảo tệp nằm trong `xampp/htdocs`

## 📚 Tài Liệu Tham Khảo

- [PHP Documentation](https://www.php.net/docs.php)
- [MySQL Documentation](https://dev.mysql.com/doc/)
- [Bootstrap 5](https://getbootstrap.com/docs/5.0/)

## 👨‍💻 Tác Giả

**Trần Mạnh Quân** - Lab 011 Topic 1

## 📅 Ngày Tạo

Tháng 1, 2026



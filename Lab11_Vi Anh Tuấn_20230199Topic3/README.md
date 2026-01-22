# Hệ Thống Quản Lý Nhà Cung Cấp (Suppliers Management System)

## 📋 Mô Tả

Ứng dụng web CRUD (Create, Read, Update, Delete) được xây dựng bằng PHP và MySQL, cho phép quản lý thông tin nhà cung cấp (Suppliers). Ứng dụng có giao diện thân thiện với Bootstrap 5 và hỗ trợ tìm kiếm theo 3 trường, quản lý trạng thái hợp tác.

## ✨ Các Tính Năng

- ✅ **Xem danh sách nhà cung cấp** - Hiển thị tất cả nhà cung cấp dưới dạng bảng
- ✅ **Tìm kiếm trên 3 trường** - Tìm kiếm theo tên, mã số thuế hoặc điện thoại
- ✅ **Thêm nhà cung cấp mới** - Form thêm mới với xác thực dữ liệu
- ✅ **Sửa thông tin nhà cung cấp** - Cập nhật thông tin có sẵn
- ✅ **Xóa nhà cung cấp** - Xóa khỏi hệ thống với xác nhận
- ✅ **Quản lý trạng thái** - Hợp tác (Active) / Ngừng hợp tác (Inactive)
- ✅ **Flash Messages** - Hiển thị thông báo thành công/lỗi
- ✅ **Xác thực dữ liệu** - Kiểm tra tính hợp lệ trước khi lưu
- ✅ **Giao diện Responsive** - Tương thích với tất cả thiết bị
- ✅ **Hỗ trợ Unicode** - Hiển thị chính xác tiếng Việt

## 📁 Cấu Trúc Thư Mục

```
lab011_Trần Mạnh Quân_Topic3/
├── db.php              # Kết nối cơ sở dữ liệu (PDO)
├── index.php           # Danh sách + Tìm kiếm 3 trường + Flash message
├── create.php          # Form thêm mới + Xử lý thêm + Validate
├── edit.php            # Form sửa + Xử lý cập nhật + Validate unique
├── delete.php          # Xử lý xóa
├── database.sql        # Tạo bảng suppliers và dữ liệu mẫu
└── README.md           # Tệp này
```

## 🗄️ Cấu Trúc Cơ Sở Dữ Liệu

### Database: `lab11_suppliers`
**Character Set**: `utf8mb4_unicode_ci` (Hỗ trợ tiếng Việt)

### Bảng: `suppliers`

| Cột | Kiểu Dữ Liệu | Ràng Buộc | Mô Tả |
|-----|--------------|----------|-------|
| `id` | INT | PRIMARY KEY, AUTO_INCREMENT | ID nhà cung cấp |
| `supplier_name` | VARCHAR(120) | NOT NULL | Tên nhà cung cấp (bắt buộc) |
| `tax_code` | VARCHAR(30) | UNIQUE, NULL | Mã số thuế (duy nhất, cho phép NULL) |
| `contact_name` | VARCHAR(120) | NULL | Tên người liên hệ |
| `phone` | VARCHAR(20) | NULL | Điện thoại |
| `address` | VARCHAR(255) | NULL | Địa chỉ |
| `status` | TINYINT(1) | DEFAULT 1 | Trạng thái (1: Hợp tác, 0: Ngừng) |
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
- Nhập tên: `lab11_suppliers`
- Chọn Charset: `utf8mb4_unicode_ci`
- Click **"Tạo"**
- Chọn database vừa tạo, click tab **"SQL"**
- Copy nội dung từ `database.sql` vào khung soạn thảo
- Click **"Thực thi"**

**Cách 2: Sử dụng phpMyAdmin Import**
- Mở phpMyAdmin: `http://localhost/phpmyadmin`
- Tạo database `lab11_suppliers` (như trên)
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
$dbname = 'lab11_suppliers';
$username = 'root';
$password = ''; // Mặc định rỗng
```

Nếu cần thay đổi, hãy sửa các giá trị trên trong file `db.php`

#### 3. Truy Cập Ứng Dụng

Mở trình duyệt và đi đến:
```
http://localhost/lab011_Trần%20Mạnh%20Quân_20230496/lab011_Trần%20Mạnh%20Quân_Topic3/
```

hoặc

```
http://localhost/lab011_Trần%20Mạnh%20Quân_20230496/lab011_Trần%20Mạnh%20Quân_Topic3/index.php
```

## 📖 Hướng Dẫn Sử Dụng

### 🏠 Trang Chủ (index.php)
- Hiển thị danh sách tất cả nhà cung cấp
- Nhập từ khóa để tìm kiếm (theo tên, MST hoặc SĐT)
- Click **"Reset"** để xóa bộ lọc và xem tất cả
- Click **"Sửa"** để cập nhật thông tin
- Click **"Xóa"** để xóa nhà cung cấp (có xác nhận)
- Xem trạng thái hợp tác qua badge màu

### ➕ Thêm Nhà Cung Cấp (create.php)
1. Click **"+ Thêm Nhà cung cấp"** từ trang chủ
2. Điền các thông tin:
   - **Tên Nhà Cung Cấp**: Bắt buộc, không để trống
   - **Mã Số Thuế**: Tùy chọn, nhưng nếu nhập phải duy nhất
   - **Tên Người Liên Hệ**: Tùy chọn
   - **Điện Thoại**: Tùy chọn
   - **Địa Chỉ**: Tùy chọn
   - **Trạng Thái**: Chọn "Hợp tác" hoặc "Ngừng hợp tác"
3. Click **"Thêm"** để lưu

### ✏️ Sửa Nhà Cung Cấp (edit.php)
1. Click nút **"Sửa"** trên hàng nhà cung cấp cần chỉnh sửa
2. Cập nhật thông tin cần thiết
3. Click **"Cập nhật"**

### ❌ Xóa Nhà Cung Cấp (delete.php)
1. Click nút **"Xóa"** trên hàng nhà cung cấp
2. Xác nhận xóa trong hộp thoại popup
3. Nhà cung cấp sẽ bị xóa khỏi hệ thống

### 🔍 Tìm Kiếm
- Nhập từ khóa trong ô tìm kiếm
- Click **"Tìm kiếm"** hoặc nhấn Enter
- Kết quả sẽ lọc theo 3 trường: Tên, MST, hoặc SĐT
- Click **"Reset"** để xem lại tất cả

## ✔️ Xác Thực Dữ Liệu

Ứng dụng kiểm tra:

| Trường | Kiểm Tra |
|--------|----------|
| **Tên NCC** | Không để trống |
| **Mã Số Thuế** | Duy nhất (nếu có dữ liệu) |
| **Phone** | Định dạng hợp lệ |
| **Status** | Hợp tác (1) / Ngừng (0) |

## 🔒 Bảo Mật

- ✅ Sử dụng PDO Prepared Statements để phòng chống SQL Injection
- ✅ Sử dụng `htmlspecialchars()` để phòng chống XSS
- ✅ Session management để quản lý trạng thái người dùng
- ✅ Xác nhận xóa để tránh xóa nhầm

## 🎨 Công Nghệ Sử Dụng

- **Backend**: PHP 7.2+
- **Database**: MySQL 5.7+
- **Frontend**: HTML5, CSS3
- **Framework CSS**: Bootstrap 5 (CDN)
- **Database Connection**: PDO (PHP Data Objects)
- **Character Encoding**: UTF-8MB4 (Hỗ trợ Tiếng Việt)

## 📝 Dữ Liệu Mẫu

Cơ sở dữ liệu được cài đặt sẵn 5 nhà cung cấp:

| ID | Tên Nhà Cung Cấp | MST | Trạng Thái |
|----|------------------|-----|-----------|
| 1 | Công ty TNHH Samsung Vina | 0300123456 | Hợp tác |
| 2 | Công ty Cổ phần Vinamilk | 0300999888 | Hợp tác |
| 3 | Tập đoàn Hòa Phát | 0900111222 | Hợp tác |
| 4 | Nhà cung cấp Giấy Bãi Bằng | --- | Hợp tác |
| 5 | Công ty Máy tính ABC | 0101234999 | Ngừng |

## 🐛 Xử Lý Sự Cố

### Lỗi: "Connection failed"
- Kiểm tra MySQL đã chạy chưa
- Kiểm tra tên người dùng, mật khẩu trong `db.php`
- Kiểm tra database `lab11_suppliers` đã tạo chưa

### Lỗi: "Table 'lab11_suppliers.suppliers' doesn't exist"
- Nhập lại database.sql qua phpMyAdmin
- Hoặc chạy lệnh SQL từ command line

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
- Chọn database `lab11_suppliers`
- Click tab **"Thao tác"**
- Click **"Xóa cơ sở dữ liệu"**
- Xác nhận xóa

**Cách 2: Sử dụng Command Line**
```bash
mysql -u root -e "DROP DATABASE IF EXISTS lab11_suppliers;"
```

## 👨‍💻 Tác Giả

**Trần Mạnh Quân** - Lab 011 Topic 3

## 📅 Ngày Tạo

Tháng 1, 2026


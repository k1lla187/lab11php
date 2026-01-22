CREATE DATABASE IF NOT EXISTS lab11_suppliers CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE lab11_suppliers;

CREATE TABLE suppliers (
    id INT AUTO_INCREMENT PRIMARY KEY,
    supplier_name VARCHAR(120) NOT NULL,
    tax_code VARCHAR(30) NULL UNIQUE, -- Cho phép NULL, nhưng nếu có dữ liệu thì không được trùng
    contact_name VARCHAR(120) NULL,
    phone VARCHAR(20) NULL,
    address VARCHAR(255) NULL,
    status TINYINT(1) DEFAULT 1, -- 1: Hợp tác, 0: Ngừng hợp tác
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- Dữ liệu mẫu
INSERT INTO suppliers (supplier_name, tax_code, contact_name, phone, address, status) VALUES 
('Công ty TNHH Samsung Vina', '0300123456', 'Nguyễn Văn A', '0987654321', 'Khu công nghệ cao, TP.HCM', 1),
('Công ty Cổ phần Vinamilk', '0300999888', 'Trần Thị B', '02854155555', 'Quận 7, TP.HCM', 1),
('Tập đoàn Hòa Phát', '0900111222', 'Lê Văn C', '02436686688', 'Hai Bà Trưng, Hà Nội', 1),
('Nhà cung cấp Giấy Bãi Bằng', NULL, 'Phạm Văn D', '0912345678', 'Phú Thọ', 1),
('Công ty Máy tính ABC', '0101234999', 'Hoàng Thị E', '0999888777', 'Đống Đa, Hà Nội', 0);
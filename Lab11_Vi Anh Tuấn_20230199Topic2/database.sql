CREATE DATABASE IF NOT EXISTS lab11_employees CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE lab11_employees;

CREATE TABLE employees (
    id INT AUTO_INCREMENT PRIMARY KEY,
    full_name VARCHAR(120) NOT NULL,
    email VARCHAR(120) NOT NULL UNIQUE,
    phone VARCHAR(20) NULL,
    position VARCHAR(80) NOT NULL,
    salary INT NULL,
    status TINYINT(1) DEFAULT 1, -- 1: Đang làm việc, 0: Đã nghỉ
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Dữ liệu mẫu (5 bản ghi)
INSERT INTO employees (full_name, email, phone, position, salary, status) VALUES 
('Nguyễn Văn A', 'vana@example.com', '0901234567', 'Developer', 15000000, 1),
('Trần Thị B', 'btran@example.com', '0912345678', 'Tester', 12000000, 1),
('Lê Văn C', 'cle@example.com', '0987654321', 'Manager', 25000000, 1),
('Phạm Thị D', 'dpham@example.com', '', 'HR', 10000000, 0),
('Hoàng Văn E', 'ehoang@example.com', '0999888777', 'Intern', 5000000, 1);
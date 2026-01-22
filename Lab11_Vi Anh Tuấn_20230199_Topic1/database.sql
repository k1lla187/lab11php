CREATE DATABASE IF NOT EXISTS lab11_categories CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE lab11_categories;

CREATE TABLE categories (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    slug VARCHAR(120) NOT NULL UNIQUE,
    description TEXT NULL,
    status TINYINT(1) DEFAULT 1, -- 1: Active, 0: Inactive
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Dữ liệu mẫu
INSERT INTO categories (name, slug, description, status) VALUES 
('Tin tức công nghệ', 'tin-tuc-cong-nghe', 'Chuyên mục tin tức IT', 1),
('Lập trình Web', 'lap-trinh-web', 'HTML, CSS, PHP, JS', 1),
('Cơ sở dữ liệu', 'co-so-du-lieu', 'MySQL, SQL Server', 1),
('Mạng máy tính', 'mang-may-tinh', 'Network basic', 0),
('Tin tuyển dụng', 'tin-tuyen-dung', 'Việc làm IT mới nhất', 1);
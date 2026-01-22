<?php
$host = 'localhost';
$dbname = 'lab11_employees';
$username = 'root';
$password = ''; // Mặc định XAMPP là rỗng

try {
    $conn = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    // Bật chế độ báo lỗi Exception để dễ debug
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Lỗi kết nối CSDL: " . $e->getMessage());
}
?>
<?php
header('Content-Type: text/html; charset=utf-8');

$host = 'localhost';
$dbname = 'lab11_suppliers';
$username = 'root';
$password = ''; // Mặc định là rỗng

try {
    $conn = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Lỗi kết nối: " . $e->getMessage());
}
?>  
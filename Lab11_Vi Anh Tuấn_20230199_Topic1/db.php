<?php
// db.php
$host = 'localhost';
$dbname = 'lab11_categories';
$username = 'root';
$password = ''; // XAMPP mặc định pass là rỗng, Laragon cũng vậy (hoặc 'root')

try {
    $conn = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    // Thiết lập chế độ lỗi để bắn ra Exception
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Lỗi kết nối CSDL: " . $e->getMessage());
}
?>
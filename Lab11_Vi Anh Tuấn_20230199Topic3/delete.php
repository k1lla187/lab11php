<?php
session_start();
header('Content-Type: text/html; charset=utf-8');
require_once 'db.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    try {
        $stmt = $conn->prepare("DELETE FROM suppliers WHERE id = :id");
        $stmt->execute([':id' => $id]);
        
        $_SESSION['message'] = 'Đã xóa nhà cung cấp!';
        $_SESSION['msg_type'] = 'success';
    } catch (PDOException $e) {
        $_SESSION['message'] = 'Không thể xóa (có thể đang có dữ liệu liên quan).';
        $_SESSION['msg_type'] = 'danger';
    }
}
header('Location: index.php');
exit;
?>
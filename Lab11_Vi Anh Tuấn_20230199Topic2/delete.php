<?php
session_start();
require_once 'db.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    try {
        $stmt = $conn->prepare("DELETE FROM employees WHERE id = :id");
        $stmt->execute([':id' => $id]);
        
        $_SESSION['message'] = 'Xóa nhân viên thành công!';
        $_SESSION['msg_type'] = 'success';
    } catch (PDOException $e) {
        $_SESSION['message'] = 'Lỗi không thể xóa: ' . $e->getMessage();
        $_SESSION['msg_type'] = 'danger';
    }
}

header('Location: index.php');
exit;
?>
<?php
session_start();
require_once 'db.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    
    try {
        // Xóa bản ghi
        $stmt = $conn->prepare("DELETE FROM categories WHERE id = :id");
        $stmt->execute([':id' => $id]);
        
        $_SESSION['message'] = 'Đã xóa danh mục thành công!';
        $_SESSION['msg_type'] = 'success'; // Màu xanh
    } catch (PDOException $e) {
        $_SESSION['message'] = 'Không thể xóa: ' . $e->getMessage();
        $_SESSION['msg_type'] = 'danger'; // Màu đỏ
    }
}

header('Location: index.php');
exit;
?>
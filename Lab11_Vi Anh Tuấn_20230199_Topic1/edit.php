<?php
session_start();
require_once 'db.php';

// Kiểm tra ID
if (!isset($_GET['id'])) {
    header('Location: index.php');
    exit;
}
$id = $_GET['id'];

// Lấy dữ liệu hiện tại
$stmt = $conn->prepare("SELECT * FROM categories WHERE id = :id");
$stmt->execute([':id' => $id]);
$category = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$category) {
    $_SESSION['message'] = 'Không tìm thấy danh mục!';
    $_SESSION['msg_type'] = 'danger';
    header('Location: index.php');
    exit;
}

$errors = [];
// Nếu chưa submit thì lấy data từ DB, nếu submit rồi (mà lỗi) thì lấy từ $_POST
$data = $category; 

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Cập nhật biến data từ input
    $data['name'] = trim($_POST['name']);
    $data['slug'] = trim($_POST['slug']);
    $data['description'] = trim($_POST['description']);
    $data['status'] = isset($_POST['status']) ? (int)$_POST['status'] : 0;

    // --- VALIDATE (Giống Create) ---
    if (empty($data['name'])) {
        $errors['name'] = 'Tên bắt buộc.';
    } elseif (mb_strlen($data['name']) < 3 || mb_strlen($data['name']) > 100) {
        $errors['name'] = 'Tên từ 3-100 ký tự.';
    }

    if (empty($data['slug'])) {
        $errors['slug'] = 'Slug bắt buộc.';
    } elseif (!preg_match('/^[a-z0-9-]+$/', $data['slug'])) {
        $errors['slug'] = 'Slug sai định dạng.';
    } else {
        // Check Unique Slug NGOẠI TRỪ chính ID này
        $stmtCheck = $conn->prepare("SELECT COUNT(*) FROM categories WHERE slug = :slug AND id != :id");
        $stmtCheck->execute([':slug' => $data['slug'], ':id' => $id]);
        if ($stmtCheck->fetchColumn() > 0) {
            $errors['slug'] = 'Slug đã tồn tại.';
        }
    }

    // --- UPDATE ---
    if (empty($errors)) {
        try {
            $sql = "UPDATE categories SET name = :name, slug = :slug, description = :desc, status = :status WHERE id = :id";
            $stmt = $conn->prepare($sql);
            $stmt->execute([
                ':name' => $data['name'],
                ':slug' => $data['slug'],
                ':desc' => $data['description'],
                ':status' => $data['status'],
                ':id' => $id
            ]);

            $_SESSION['message'] = 'Cập nhật thành công!';
            $_SESSION['msg_type'] = 'success';
            header('Location: index.php');
            exit;
        } catch (PDOException $e) {
            $errors['db'] = 'Lỗi: ' . $e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Sửa Danh mục</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="container mt-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header bg-warning text-dark">Chỉnh sửa Danh mục: <?= htmlspecialchars($category['name']) ?></div>
                <div class="card-body">
                    <form action="" method="POST">
                        <div class="mb-3">
                            <label class="form-label">Tên danh mục</label>
                            <input type="text" name="name" class="form-control <?= isset($errors['name']) ? 'is-invalid' : '' ?>" 
                                   value="<?= htmlspecialchars($data['name']) ?>">
                            <div class="invalid-feedback"><?= $errors['name'] ?? '' ?></div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Slug</label>
                            <input type="text" name="slug" class="form-control <?= isset($errors['slug']) ? 'is-invalid' : '' ?>" 
                                   value="<?= htmlspecialchars($data['slug']) ?>">
                            <div class="invalid-feedback"><?= $errors['slug'] ?? '' ?></div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Mô tả</label>
                            <textarea name="description" class="form-control" rows="3"><?= htmlspecialchars($data['description']) ?></textarea>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Trạng thái</label>
                            <select name="status" class="form-select">
                                <option value="1" <?= $data['status'] == 1 ? 'selected' : '' ?>>Hiển thị</option>
                                <option value="0" <?= $data['status'] == 0 ? 'selected' : '' ?>>Ẩn</option>
                            </select>
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="index.php" class="btn btn-secondary">Quay lại</a>
                            <button type="submit" class="btn btn-warning">Cập nhật</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
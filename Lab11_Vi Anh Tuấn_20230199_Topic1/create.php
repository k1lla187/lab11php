<?php
session_start();
require_once 'db.php';

$errors = [];
$data = ['name' => '', 'slug' => '', 'description' => '', 'status' => 1];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Lấy và làm sạch dữ liệu
    $data['name'] = trim($_POST['name']);
    $data['slug'] = trim($_POST['slug']);
    $data['description'] = trim($_POST['description']);
    $data['status'] = isset($_POST['status']) ? (int)$_POST['status'] : 0;

    // --- VALIDATE ---
    // 1. Name: Bắt buộc, 3-100 ký tự
    if (empty($data['name'])) {
        $errors['name'] = 'Tên danh mục không được để trống.';
    } elseif (mb_strlen($data['name']) < 3 || mb_strlen($data['name']) > 100) {
        $errors['name'] = 'Tên danh mục phải từ 3 đến 100 ký tự.';
    }

    // 2. Slug: Bắt buộc, Regex (a-z, 0-9, -), Unique
    if (empty($data['slug'])) {
        $errors['slug'] = 'Slug không được để trống.';
    } elseif (!preg_match('/^[a-z0-9-]+$/', $data['slug'])) {
        $errors['slug'] = 'Slug chỉ được chứa chữ thường (a-z), số (0-9) và dấu gạch ngang (-).';
    } else {
        // Check Unique Slug
        $stmt = $conn->prepare("SELECT COUNT(*) FROM categories WHERE slug = :slug");
        $stmt->execute([':slug' => $data['slug']]);
        if ($stmt->fetchColumn() > 0) {
            $errors['slug'] = 'Slug này đã tồn tại, vui lòng chọn slug khác.';
        }
    }

    // --- INSERT ---
    if (empty($errors)) {
        try {
            $sql = "INSERT INTO categories (name, slug, description, status) VALUES (:name, :slug, :desc, :status)";
            $stmt = $conn->prepare($sql);
            $stmt->execute([
                ':name' => $data['name'],
                ':slug' => $data['slug'],
                ':desc' => $data['description'],
                ':status' => $data['status']
            ]);

            // Flash Message
            $_SESSION['message'] = 'Thêm mới thành công!';
            $_SESSION['msg_type'] = 'success';
            header('Location: index.php');
            exit;
        } catch (PDOException $e) {
            $errors['db'] = 'Lỗi lưu dữ liệu: ' . $e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Thêm Danh mục</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="container mt-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header bg-primary text-white">Thêm Danh mục mới</div>
                <div class="card-body">
                    <?php if (isset($errors['db'])): ?>
                        <div class="alert alert-danger"><?= $errors['db'] ?></div>
                    <?php endif; ?>

                    <form action="" method="POST">
                        <div class="mb-3">
                            <label class="form-label">Tên danh mục <span class="text-danger">*</span></label>
                            <input type="text" name="name" class="form-control <?= isset($errors['name']) ? 'is-invalid' : '' ?>" 
                                   value="<?= htmlspecialchars($data['name']) ?>">
                            <div class="invalid-feedback"><?= $errors['name'] ?? '' ?></div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Slug (URL) <span class="text-danger">*</span></label>
                            <input type="text" name="slug" class="form-control <?= isset($errors['slug']) ? 'is-invalid' : '' ?>" 
                                   value="<?= htmlspecialchars($data['slug']) ?>"
                                   placeholder="vi-du-nhu-the-nay">
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
                            <button type="submit" class="btn btn-primary">Lưu lại</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
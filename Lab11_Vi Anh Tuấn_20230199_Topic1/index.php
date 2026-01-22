<?php
session_start();
require_once 'db.php';

// Xử lý tìm kiếm
$keyword = '';
if (isset($_GET['keyword'])) {
    $keyword = trim($_GET['keyword']);
}

// Query dữ liệu (Prepared Statement cho Search)
$sql = "SELECT * FROM categories WHERE name LIKE :keyword OR slug LIKE :keyword ORDER BY id DESC";
$stmt = $conn->prepare($sql);
$stmt->execute([':keyword' => "%$keyword%"]);
$categories = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Quản lý Danh mục - Lab 11</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="container mt-4">
    <h2 class="mb-4 text-center">Quản lý Danh mục (Categories)</h2>

    <?php if (isset($_SESSION['message'])): ?>
        <div class="alert alert-<?= $_SESSION['msg_type']; ?> alert-dismissible fade show">
            <?= $_SESSION['message']; ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        <?php 
        unset($_SESSION['message']); 
        unset($_SESSION['msg_type']);
        ?>
    <?php endif; ?>

    <div class="row mb-3">
        <div class="col-md-6">
            <a href="create.php" class="btn btn-primary">
                + Thêm mới
            </a>
        </div>
        <div class="col-md-6">
            <form action="" method="GET" class="d-flex">
                <input type="text" name="keyword" class="form-control me-2" 
                       placeholder="Tìm theo tên hoặc slug..." 
                       value="<?= htmlspecialchars($keyword) ?>">
                <button type="submit" class="btn btn-success">Tìm kiếm</button>
                <?php if($keyword): ?>
                    <a href="index.php" class="btn btn-secondary ms-2">Reset</a>
                <?php endif; ?>
            </form>
        </div>
    </div>

    <table class="table table-bordered table-striped table-hover">
        <thead class="table-dark">
            <tr>
                <th>ID</th>
                <th>Tên danh mục</th>
                <th>Slug</th>
                <th>Trạng thái</th>
                <th>Ngày tạo</th>
                <th style="width: 150px;">Hành động</th>
            </tr>
        </thead>
        <tbody>
            <?php if (count($categories) > 0): ?>
                <?php foreach ($categories as $cat): ?>
                    <tr>
                        <td><?= htmlspecialchars($cat['id']) ?></td>
                        <td><?= htmlspecialchars($cat['name']) ?></td>
                        <td><?= htmlspecialchars($cat['slug']) ?></td>
                        <td>
                            <?php if ($cat['status'] == 1): ?>
                                <span class="badge bg-success">Hiển thị</span>
                            <?php else: ?>
                                <span class="badge bg-secondary">Ẩn</span>
                            <?php endif; ?>
                        </td>
                        <td><?= date('d/m/Y', strtotime($cat['created_at'])) ?></td>
                        <td>
                            <a href="edit.php?id=<?= $cat['id'] ?>" class="btn btn-sm btn-warning">Edit</a>
                            <a href="delete.php?id=<?= $cat['id'] ?>" 
                               class="btn btn-sm btn-danger"
                               onclick="return confirm('Bạn có chắc muốn xóa danh mục: <?= htmlspecialchars($cat['name']) ?>?')">
                               Delete
                            </a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="6" class="text-center">Không tìm thấy dữ liệu.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
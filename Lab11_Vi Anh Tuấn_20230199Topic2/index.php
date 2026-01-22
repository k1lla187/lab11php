<?php
session_start();
require_once 'db.php';

// Xử lý tìm kiếm
$keyword = '';
if (isset($_GET['keyword'])) {
    $keyword = trim($_GET['keyword']);
}

// Query dữ liệu (Tìm theo Tên hoặc Email)
$sql = "SELECT * FROM employees WHERE full_name LIKE :keyword OR email LIKE :keyword ORDER BY id DESC";
$stmt = $conn->prepare($sql);
$stmt->execute([':keyword' => "%$keyword%"]);
$employees = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Quản lý Nhân viên</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="container mt-4">
    <h2 class="text-center mb-4">Quản lý Nhân viên (Employees)</h2>

    <?php if (isset($_SESSION['message'])): ?>
        <div class="alert alert-<?= $_SESSION['msg_type']; ?> alert-dismissible fade show">
            <?= $_SESSION['message']; ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        <?php unset($_SESSION['message'], $_SESSION['msg_type']); ?>
    <?php endif; ?>

    <div class="row mb-3">
        <div class="col-md-6">
            <a href="create.php" class="btn btn-primary">+ Thêm Nhân viên</a>
        </div>
        <div class="col-md-6">
            <form action="" method="GET" class="d-flex">
                <input type="text" name="keyword" class="form-control me-2" 
                       placeholder="Nhập tên hoặc email..." 
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
                <th>Họ tên</th>
                <th>Email</th>
                <th>SĐT</th>
                <th>Vị trí</th>
                <th>Lương</th>
                <th>Trạng thái</th>
                <th style="width: 150px;">Hành động</th>
            </tr>
        </thead>
        <tbody>
            <?php if (count($employees) > 0): ?>
                <?php foreach ($employees as $emp): ?>
                    <tr>
                        <td><?= htmlspecialchars($emp['id']) ?></td>
                        <td><?= htmlspecialchars($emp['full_name']) ?></td>
                        <td><?= htmlspecialchars($emp['email']) ?></td>
                        <td><?= htmlspecialchars($emp['phone']) ?></td>
                        <td><?= htmlspecialchars($emp['position']) ?></td>
                        <td><?= number_format($emp['salary']) ?> đ</td>
                        <td>
                            <?php if ($emp['status'] == 1): ?>
                                <span class="badge bg-success">Đang làm việc</span>
                            <?php else: ?>
                                <span class="badge bg-secondary">Đã nghỉ</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <a href="edit.php?id=<?= $emp['id'] ?>" class="btn btn-sm btn-warning">Sửa</a>
                            <a href="delete.php?id=<?= $emp['id'] ?>" 
                               class="btn btn-sm btn-danger"
                               onclick="return confirm('Bạn chắc chắn muốn xóa nhân viên: <?= htmlspecialchars($emp['full_name']) ?>?')">
                               Xóa
                            </a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="8" class="text-center">Không tìm thấy dữ liệu.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
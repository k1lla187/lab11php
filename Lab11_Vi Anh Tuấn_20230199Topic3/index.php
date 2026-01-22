<?php
session_start();
header('Content-Type: text/html; charset=utf-8');
require_once 'db.php';

// Xử lý tìm kiếm
$keyword = '';
if (isset($_GET['keyword'])) {
    $keyword = trim($_GET['keyword']);
}

// Query tìm kiếm trên 3 trường
$sql = "SELECT * FROM suppliers 
        WHERE supplier_name LIKE :kw 
        OR tax_code LIKE :kw 
        OR phone LIKE :kw 
        ORDER BY id DESC";

$stmt = $conn->prepare($sql);
$stmt->execute([':kw' => "%$keyword%"]);
$suppliers = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Quản lý Nhà cung cấp</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="container mt-4">
    <h2 class="text-center mb-4">Quản lý Nhà cung cấp (Suppliers)</h2>

    <?php if (isset($_SESSION['message'])): ?>
        <div class="alert alert-<?= $_SESSION['msg_type']; ?> alert-dismissible fade show">
            <?= $_SESSION['message']; ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        <?php unset($_SESSION['message'], $_SESSION['msg_type']); ?>
    <?php endif; ?>

    <div class="row mb-3">
        <div class="col-md-5">
            <a href="create.php" class="btn btn-primary">+ Thêm Nhà cung cấp</a>
        </div>
        <div class="col-md-7">
            <form action="" method="GET" class="d-flex">
                <input type="text" name="keyword" class="form-control me-2" 
                       placeholder="Tên, MST hoặc SĐT..." 
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
                <th>Tên NCC</th>
                <th>Mã số thuế</th>
                <th>Người liên hệ</th>
                <th>SĐT</th>
                <th>Trạng thái</th>
                <th style="width: 150px;">Hành động</th>
            </tr>
        </thead>
        <tbody>
            <?php if (count($suppliers) > 0): ?>
                <?php foreach ($suppliers as $s): ?>
                    <tr>
                        <td><?= htmlspecialchars($s['id']) ?></td>
                        <td>
                            <strong><?= htmlspecialchars($s['supplier_name']) ?></strong><br>
                            <small class="text-muted"><?= htmlspecialchars($s['address']) ?></small>
                        </td>
                        <td><?= htmlspecialchars($s['tax_code'] ?? '---') ?></td>
                        <td><?= htmlspecialchars($s['contact_name']) ?></td>
                        <td><?= htmlspecialchars($s['phone']) ?></td>
                        <td>
                            <?php if ($s['status'] == 1): ?>
                                <span class="badge bg-success">Hợp tác</span>
                            <?php else: ?>
                                <span class="badge bg-secondary">Ngừng</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <a href="edit.php?id=<?= $s['id'] ?>" class="btn btn-sm btn-warning">Sửa</a>
                            <a href="delete.php?id=<?= $s['id'] ?>" 
                               class="btn btn-sm btn-danger"
                               onclick="return confirm('Xóa nhà cung cấp: <?= htmlspecialchars($s['supplier_name']) ?>?')">
                               Xóa
                            </a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr><td colspan="7" class="text-center">Chưa có dữ liệu</td></tr>
            <?php endif; ?>
        </tbody>
    </table>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
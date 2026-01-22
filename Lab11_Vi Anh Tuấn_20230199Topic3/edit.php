<?php
session_start();
header('Content-Type: text/html; charset=utf-8');
require_once 'db.php';

if (!isset($_GET['id'])) {
    header('Location: index.php'); exit;
}
$id = $_GET['id'];

// Lấy dữ liệu cũ
$stmt = $conn->prepare("SELECT * FROM suppliers WHERE id = :id");
$stmt->execute([':id' => $id]);
$supplier = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$supplier) {
    $_SESSION['message'] = 'Không tìm thấy dữ liệu!';
    $_SESSION['msg_type'] = 'danger';
    header('Location: index.php'); exit;
}

$errors = [];
$data = $supplier; 

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data['supplier_name'] = trim($_POST['supplier_name']);
    $data['tax_code'] = trim($_POST['tax_code']);
    $data['contact_name'] = trim($_POST['contact_name']);
    $data['phone'] = trim($_POST['phone']);
    $data['address'] = trim($_POST['address']);
    $data['status'] = isset($_POST['status']) ? (int)$_POST['status'] : 0;

    // --- VALIDATE ---
    if (empty($data['supplier_name'])) {
        $errors['supplier_name'] = 'Tên bắt buộc.';
    } elseif (mb_strlen($data['supplier_name']) < 3 || mb_strlen($data['supplier_name']) > 120) {
        $errors['supplier_name'] = 'Tên từ 3-120 ký tự.';
    }

    if (!empty($data['tax_code'])) {
        // Check Unique TRỪ ID ĐANG SỬA
        $stmtCheck = $conn->prepare("SELECT COUNT(*) FROM suppliers WHERE tax_code = :tax AND id != :id");
        $stmtCheck->execute([':tax' => $data['tax_code'], ':id' => $id]);
        if ($stmtCheck->fetchColumn() > 0) {
            $errors['tax_code'] = 'Mã số thuế đã được sử dụng bởi NCC khác.';
        }
    }

    if (!empty($data['phone'])) {
        if (!preg_match('/^[0-9]{9,12}$/', $data['phone'])) {
            $errors['phone'] = 'SĐT sai định dạng (9-12 số).';
        }
    }

    // --- UPDATE ---
    if (empty($errors)) {
        try {
            $taxCodeToSave = empty($data['tax_code']) ? null : $data['tax_code'];

            $sql = "UPDATE suppliers SET supplier_name=:name, tax_code=:tax, contact_name=:contact, 
                    phone=:phone, address=:addr, status=:status WHERE id=:id";
            $stmt = $conn->prepare($sql);
            $stmt->execute([
                ':name' => $data['supplier_name'],
                ':tax' => $taxCodeToSave,
                ':contact' => $data['contact_name'],
                ':phone' => $data['phone'],
                ':addr' => $data['address'],
                ':status' => $data['status'],
                ':id' => $id
            ]);

            $_SESSION['message'] = 'Cập nhật thành công!';
            $_SESSION['msg_type'] = 'success';
            header('Location: index.php'); exit;
        } catch (PDOException $e) {
            $errors['db'] = 'Lỗi cập nhật: ' . $e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Sửa Nhà cung cấp</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="container mt-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header bg-warning text-dark">Sửa Nhà cung cấp: <?= htmlspecialchars($supplier['supplier_name']) ?></div>
                <div class="card-body">
                    <form action="" method="POST">
                        <div class="mb-3">
                            <label class="form-label">Tên Nhà cung cấp</label>
                            <input type="text" name="supplier_name" class="form-control <?= isset($errors['supplier_name']) ? 'is-invalid' : '' ?>" 
                                   value="<?= htmlspecialchars($data['supplier_name']) ?>">
                            <div class="invalid-feedback"><?= $errors['supplier_name'] ?? '' ?></div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Mã số thuế</label>
                                <input type="text" name="tax_code" class="form-control <?= isset($errors['tax_code']) ? 'is-invalid' : '' ?>" 
                                       value="<?= htmlspecialchars($data['tax_code']) ?>">
                                <div class="invalid-feedback"><?= $errors['tax_code'] ?? '' ?></div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Số điện thoại</label>
                                <input type="text" name="phone" class="form-control <?= isset($errors['phone']) ? 'is-invalid' : '' ?>" 
                                       value="<?= htmlspecialchars($data['phone']) ?>">
                                <div class="invalid-feedback"><?= $errors['phone'] ?? '' ?></div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Người liên hệ</label>
                                <input type="text" name="contact_name" class="form-control" value="<?= htmlspecialchars($data['contact_name']) ?>">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Trạng thái</label>
                                <select name="status" class="form-select">
                                    <option value="1" <?= $data['status'] == 1 ? 'selected' : '' ?>>Hợp tác</option>
                                    <option value="0" <?= $data['status'] == 0 ? 'selected' : '' ?>>Ngừng hợp tác</option>
                                </select>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Địa chỉ</label>
                            <textarea name="address" class="form-control" rows="2"><?= htmlspecialchars($data['address']) ?></textarea>
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
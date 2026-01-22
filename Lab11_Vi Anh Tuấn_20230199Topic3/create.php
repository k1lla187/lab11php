<?php
session_start();
header('Content-Type: text/html; charset=utf-8');
require_once 'db.php';

$errors = [];
$data = ['supplier_name' => '', 'tax_code' => '', 'contact_name' => '', 'phone' => '', 'address' => '', 'status' => 1];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data['supplier_name'] = trim($_POST['supplier_name']);
    $data['tax_code'] = trim($_POST['tax_code']);
    $data['contact_name'] = trim($_POST['contact_name']);
    $data['phone'] = trim($_POST['phone']);
    $data['address'] = trim($_POST['address']);
    $data['status'] = isset($_POST['status']) ? (int)$_POST['status'] : 0;

    // --- VALIDATE ---
    
    // 1. Tên NCC: Bắt buộc, 3-120 ký tự
    if (empty($data['supplier_name'])) {
        $errors['supplier_name'] = 'Tên nhà cung cấp bắt buộc.';
    } elseif (mb_strlen($data['supplier_name']) < 3 || mb_strlen($data['supplier_name']) > 120) {
        $errors['supplier_name'] = 'Tên phải từ 3 đến 120 ký tự.';
    }

    // 2. Tax Code: Nếu nhập -> Check Unique
    if (!empty($data['tax_code'])) {
        $stmt = $conn->prepare("SELECT COUNT(*) FROM suppliers WHERE tax_code = :tax");
        $stmt->execute([':tax' => $data['tax_code']]);
        if ($stmt->fetchColumn() > 0) {
            $errors['tax_code'] = 'Mã số thuế này đã tồn tại.';
        }
    }

    // 3. Phone: Nếu nhập -> Phải là số, dài 9-12
    if (!empty($data['phone'])) {
        // Regex: Chỉ chứa số (0-9), độ dài 9-12
        if (!preg_match('/^[0-9]{9,12}$/', $data['phone'])) {
            $errors['phone'] = 'SĐT chỉ chứa số và dài từ 9-12 ký tự.';
        }
    }

    // --- INSERT ---
    if (empty($errors)) {
        try {
            // Nếu tax_code rỗng thì lưu là NULL (để tránh lỗi Unique chuỗi rỗng trong một số version DB cũ, hoặc chuẩn hóa dữ liệu)
            $taxCodeToSave = empty($data['tax_code']) ? null : $data['tax_code'];

            $sql = "INSERT INTO suppliers (supplier_name, tax_code, contact_name, phone, address, status) 
                    VALUES (:name, :tax, :contact, :phone, :addr, :status)";
            $stmt = $conn->prepare($sql);
            $stmt->execute([
                ':name' => $data['supplier_name'],
                ':tax' => $taxCodeToSave,
                ':contact' => $data['contact_name'],
                ':phone' => $data['phone'],
                ':addr' => $data['address'],
                ':status' => $data['status']
            ]);

            $_SESSION['message'] = 'Thêm mới thành công!';
            $_SESSION['msg_type'] = 'success';
            header('Location: index.php');
            exit;
        } catch (PDOException $e) {
            $errors['db'] = 'Lỗi DB: ' . $e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Thêm Nhà cung cấp</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="container mt-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header bg-primary text-white">Thêm Nhà cung cấp</div>
                <div class="card-body">
                    <?php if (isset($errors['db'])): ?>
                        <div class="alert alert-danger"><?= $errors['db'] ?></div>
                    <?php endif; ?>

                    <form action="" method="POST">
                        <div class="mb-3">
                            <label class="form-label">Tên Nhà cung cấp <span class="text-danger">*</span></label>
                            <input type="text" name="supplier_name" class="form-control <?= isset($errors['supplier_name']) ? 'is-invalid' : '' ?>" 
                                   value="<?= htmlspecialchars($data['supplier_name']) ?>">
                            <div class="invalid-feedback"><?= $errors['supplier_name'] ?? '' ?></div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Mã số thuế (nếu có)</label>
                                <input type="text" name="tax_code" class="form-control <?= isset($errors['tax_code']) ? 'is-invalid' : '' ?>" 
                                       value="<?= htmlspecialchars($data['tax_code']) ?>">
                                <div class="invalid-feedback"><?= $errors['tax_code'] ?? '' ?></div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Số điện thoại</label>
                                <input type="text" name="phone" class="form-control <?= isset($errors['phone']) ? 'is-invalid' : '' ?>" 
                                       value="<?= htmlspecialchars($data['phone']) ?>" placeholder="Chỉ nhập số">
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
                            <button type="submit" class="btn btn-primary">Lưu lại</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
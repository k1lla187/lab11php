<?php
session_start();
require_once 'db.php';

if (!isset($_GET['id'])) {
    header('Location: index.php');
    exit;
}
$id = $_GET['id'];

// Lấy dữ liệu cũ
$stmt = $conn->prepare("SELECT * FROM employees WHERE id = :id");
$stmt->execute([':id' => $id]);
$employee = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$employee) {
    $_SESSION['message'] = 'Không tìm thấy nhân viên!';
    $_SESSION['msg_type'] = 'danger';
    header('Location: index.php');
    exit;
}

$errors = [];
$data = $employee; // Gán dữ liệu cũ vào form

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data['full_name'] = trim($_POST['full_name']);
    $data['email'] = trim($_POST['email']);
    $data['phone'] = trim($_POST['phone']);
    $data['position'] = trim($_POST['position']);
    $data['salary'] = trim($_POST['salary']);
    $data['status'] = isset($_POST['status']) ? (int)$_POST['status'] : 0;

    // --- VALIDATE (Tương tự Create) ---
    if (empty($data['full_name'])) {
        $errors['full_name'] = 'Họ tên là bắt buộc.';
    } elseif (mb_strlen($data['full_name']) < 3 || mb_strlen($data['full_name']) > 120) {
        $errors['full_name'] = 'Họ tên 3-120 ký tự.';
    }

    if (empty($data['email'])) {
        $errors['email'] = 'Email bắt buộc.';
    } elseif (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = 'Email sai định dạng.';
    } else {
        // Check unique NGOẠI TRỪ chính ID này
        $stmtCheck = $conn->prepare("SELECT COUNT(*) FROM employees WHERE email = :email AND id != :id");
        $stmtCheck->execute([':email' => $data['email'], ':id' => $id]);
        if ($stmtCheck->fetchColumn() > 0) {
            $errors['email'] = 'Email này đã thuộc về nhân viên khác.';
        }
    }

    if (empty($data['position'])) {
        $errors['position'] = 'Vị trí bắt buộc.';
    }

    if ($data['salary'] !== '') {
        if (!is_numeric($data['salary']) || $data['salary'] < 0) {
            $errors['salary'] = 'Lương phải >= 0.';
        }
    } else {
        $data['salary'] = null;
    }

    // --- UPDATE ---
    if (empty($errors)) {
        try {
            $sql = "UPDATE employees SET full_name=:name, email=:email, phone=:phone, 
                    position=:pos, salary=:salary, status=:status WHERE id=:id";
            $stmt = $conn->prepare($sql);
            $stmt->execute([
                ':name' => $data['full_name'],
                ':email' => $data['email'],
                ':phone' => $data['phone'],
                ':pos' => $data['position'],
                ':salary' => $data['salary'],
                ':status' => $data['status'],
                ':id' => $id
            ]);

            $_SESSION['message'] = 'Cập nhật thành công!';
            $_SESSION['msg_type'] = 'success';
            header('Location: index.php');
            exit;
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
    <title>Sửa Nhân viên</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="container mt-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header bg-warning text-dark">Sửa thông tin: <?= htmlspecialchars($employee['full_name']) ?></div>
                <div class="card-body">
                    <form action="" method="POST">
                        <div class="mb-3">
                            <label class="form-label">Họ và tên</label>
                            <input type="text" name="full_name" class="form-control <?= isset($errors['full_name']) ? 'is-invalid' : '' ?>" 
                                   value="<?= htmlspecialchars($data['full_name']) ?>">
                            <div class="invalid-feedback"><?= $errors['full_name'] ?? '' ?></div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Email</label>
                            <input type="email" name="email" class="form-control <?= isset($errors['email']) ? 'is-invalid' : '' ?>" 
                                   value="<?= htmlspecialchars($data['email']) ?>">
                            <div class="invalid-feedback"><?= $errors['email'] ?? '' ?></div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Số điện thoại</label>
                                <input type="text" name="phone" class="form-control" value="<?= htmlspecialchars($data['phone']) ?>">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Vị trí</label>
                                <input type="text" name="position" class="form-control <?= isset($errors['position']) ? 'is-invalid' : '' ?>" 
                                       value="<?= htmlspecialchars($data['position']) ?>">
                                <div class="invalid-feedback"><?= $errors['position'] ?? '' ?></div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Lương</label>
                                <input type="number" name="salary" class="form-control <?= isset($errors['salary']) ? 'is-invalid' : '' ?>" 
                                       value="<?= htmlspecialchars($data['salary']) ?>">
                                <div class="invalid-feedback"><?= $errors['salary'] ?? '' ?></div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Trạng thái</label>
                                <select name="status" class="form-select">
                                    <option value="1" <?= $data['status'] == 1 ? 'selected' : '' ?>>Đang làm việc</option>
                                    <option value="0" <?= $data['status'] == 0 ? 'selected' : '' ?>>Đã nghỉ</option>
                                </select>
                            </div>
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
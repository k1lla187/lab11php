<?php
session_start();
require_once 'db.php';

$errors = [];
// Khởi tạo giá trị mặc định để fill lại form khi lỗi
$data = ['full_name' => '', 'email' => '', 'phone' => '', 'position' => '', 'salary' => '', 'status' => 1];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data['full_name'] = trim($_POST['full_name']);
    $data['email'] = trim($_POST['email']);
    $data['phone'] = trim($_POST['phone']);
    $data['position'] = trim($_POST['position']);
    $data['salary'] = trim($_POST['salary']);
    $data['status'] = isset($_POST['status']) ? (int)$_POST['status'] : 0;

    // --- VALIDATE SERVER-SIDE ---
    
    // 1. Full Name: Bắt buộc, 3-120 ký tự
    if (empty($data['full_name'])) {
        $errors['full_name'] = 'Họ tên là bắt buộc.';
    } elseif (mb_strlen($data['full_name']) < 3 || mb_strlen($data['full_name']) > 120) {
        $errors['full_name'] = 'Họ tên phải từ 3 đến 120 ký tự.';
    }

    // 2. Email: Bắt buộc, đúng định dạng, Unique
    if (empty($data['email'])) {
        $errors['email'] = 'Email là bắt buộc.';
    } elseif (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = 'Email không đúng định dạng.';
    } else {
        // Check trùng email
        $stmt = $conn->prepare("SELECT COUNT(*) FROM employees WHERE email = :email");
        $stmt->execute([':email' => $data['email']]);
        if ($stmt->fetchColumn() > 0) {
            $errors['email'] = 'Email này đã tồn tại trên hệ thống.';
        }
    }

    // 3. Position: Bắt buộc
    if (empty($data['position'])) {
        $errors['position'] = 'Vị trí công việc là bắt buộc.';
    }

    // 4. Salary: Nếu nhập thì phải là số >= 0
    if ($data['salary'] !== '') {
        if (!is_numeric($data['salary']) || $data['salary'] < 0) {
            $errors['salary'] = 'Lương phải là số và lớn hơn hoặc bằng 0.';
        }
    } else {
        $data['salary'] = null; // Chuyển về null nếu rỗng
    }

    // --- INSERT DỮ LIỆU ---
    if (empty($errors)) {
        try {
            $sql = "INSERT INTO employees (full_name, email, phone, position, salary, status) 
                    VALUES (:name, :email, :phone, :pos, :salary, :status)";
            $stmt = $conn->prepare($sql);
            $stmt->execute([
                ':name' => $data['full_name'],
                ':email' => $data['email'],
                ':phone' => $data['phone'],
                ':pos' => $data['position'],
                ':salary' => $data['salary'],
                ':status' => $data['status']
            ]);

            $_SESSION['message'] = 'Thêm nhân viên thành công!';
            $_SESSION['msg_type'] = 'success';
            header('Location: index.php');
            exit;
        } catch (PDOException $e) {
            $errors['db'] = 'Lỗi hệ thống: ' . $e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Thêm Nhân viên</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="container mt-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header bg-primary text-white">Thêm Nhân viên mới</div>
                <div class="card-body">
                    <?php if (isset($errors['db'])): ?>
                        <div class="alert alert-danger"><?= $errors['db'] ?></div>
                    <?php endif; ?>

                    <form action="" method="POST">
                        <div class="mb-3">
                            <label class="form-label">Họ và tên <span class="text-danger">*</span></label>
                            <input type="text" name="full_name" class="form-control <?= isset($errors['full_name']) ? 'is-invalid' : '' ?>" 
                                   value="<?= htmlspecialchars($data['full_name']) ?>">
                            <div class="invalid-feedback"><?= $errors['full_name'] ?? '' ?></div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Email <span class="text-danger">*</span></label>
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
                                <label class="form-label">Vị trí <span class="text-danger">*</span></label>
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
                            <button type="submit" class="btn btn-primary">Lưu lại</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
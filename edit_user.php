<?php
include 'db_connect.php';
session_start();

// ตรวจสอบสิทธิ์ admin
if ($_SESSION['role'] !== 'admin') {
    echo "<script>alert('คุณไม่มีสิทธิ์เข้าถึงหน้านี้!'); window.location.href='index.php';</script>";
    exit;
}

// ตรวจสอบว่ามีการส่ง ID มาหรือไม่
if (isset($_GET['id'])) {
    $user_id = $_GET['id'];
    $sql = "SELECT * FROM users WHERE user_id = $user_id";
    $result = mysqli_query($conn, $sql);
    $user = mysqli_fetch_assoc($result);

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $username = $_POST['username'];
        $email = $_POST['email'];
        $status = $_POST['status'];

        $sql = "UPDATE users SET username='$username', email='$email', status='$status' WHERE user_id=$user_id";
        if ($conn->query($sql) === TRUE) {
            echo "<script>alert('แก้ไขข้อมูลผู้ใช้สำเร็จ!'); window.location.href='admin_users.php';</script>";
        } else {
            echo "Error: " . $conn->error;
        }
    }
} else {
    echo "<script>alert('ไม่พบข้อมูลผู้ใช้!'); window.location.href='admin_users.php';</script>";
}
?>

<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>แก้ไขข้อมูลผู้ใช้</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<div class="container mt-5">
    <h2>แก้ไขข้อมูลผู้ใช้</h2>
    <form method="post">
        <div class="mb-3">
            <label for="username" class="form-label">ชื่อผู้ใช้</label>
            <input type="text" class="form-control" name="username" id="username" value="<?php echo $user['username']; ?>" required>
        </div>
        <div class="mb-3">
            <label for="email" class="form-label">อีเมล</label>
            <input type="email" class="form-control" name="email" id="email" value="<?php echo $user['email']; ?>" required>
        </div>
        <div class="mb-3">
            <label for="status" class="form-label">สถานะ</label>
            <select class="form-control" name="status" id="status">
                <option value="active" <?php if ($user['status'] == 'active') echo 'selected'; ?>>ปกติ</option>
                <option value="blocked" <?php if ($user['status'] == 'blocked') echo 'selected'; ?>>บล็อค</option>
            </select>
        </div>
        <button type="submit" class="btn btn-primary">อัปเดตข้อมูล</button>
    </form>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

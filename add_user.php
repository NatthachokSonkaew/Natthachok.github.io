<?php
include 'db_connect.php';
session_start();

// ตรวจสอบสิทธิ์ admin
if ($_SESSION['role'] !== 'admin') {
    echo "<script>alert('คุณไม่มีสิทธิ์เข้าถึงหน้านี้!'); window.location.href='index.php';</script>";
    exit;
}

// ตรวจสอบการส่งฟอร์ม
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    // ตรวจสอบว่าอีเมลมีการใช้งานแล้วหรือไม่
    $sql = "SELECT * FROM users WHERE email='$email'";
    $result = mysqli_query($conn, $sql);
    
    if ($result->num_rows > 0) {
        echo "<script>alert('อีเมลนี้ถูกใช้แล้ว');</script>";
    } else {
        // ลบการอ้างอิงถึง status
        $sql = "INSERT INTO users (username, email, password) VALUES ('$username', '$email', '$password')";
        if ($conn->query($sql) === TRUE) {
            echo "<script>alert('เพิ่มผู้ใช้สำเร็จ!'); window.location.href='admin_users.php';</script>";
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>เพิ่มผู้ใช้ใหม่</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f0f8ff;
            font-family: 'Arial', sans-serif;
        }
        .container {
            margin-top: 50px;
            padding: 30px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }
        h2 {
            color: #e91e63;
            text-align: center;
            font-weight: bold;
        }
        .form-label {
            font-weight: bold;
        }
        .btn-primary {
            background-color: #ff4081;
            border-color: #ff4081;
        }
        .btn-primary:hover {
            background-color: #e91e63;
            border-color: #e91e63;
        }
        .btn-secondary {
            background-color: #00bcd4;
            border-color: #00bcd4;
        }
        .btn-secondary:hover {
            background-color: #0097a7;
            border-color: #0097a7;
        }
        .btn {
            padding: 10px 20px;
            font-size: 16px;
        }
        .form-control {
            border-radius: 4px;
            box-shadow: none;
            border: 1px solid #ddd;
        }
        .form-control:focus {
            border-color: #ff4081;
            box-shadow: 0 0 5px rgba(255, 64, 129, 0.5);
        }
    </style>
</head>
<body>

<div class="container">
    <h2>เพิ่มผู้ใช้ใหม่</h2>
    <form method="post">
        <div class="mb-3">
            <label for="username" class="form-label">ชื่อผู้ใช้</label>
            <input type="text" class="form-control" name="username" id="username" required>
        </div>
        <div class="mb-3">
            <label for="email" class="form-label">อีเมล</label>
            <input type="email" class="form-control" name="email" id="email" required>
        </div>
        <div class="mb-3">
            <label for="password" class="form-label">รหัสผ่าน</label>
            <input type="password" class="form-control" name="password" id="password" required>
        </div>
        <button type="submit" class="btn btn-primary">เพิ่มผู้ใช้</button>
    </form>

    <!-- ปุ่มกลับหน้าหลัก -->
    <a href="index.php" class="btn btn-secondary mt-3">กลับหน้าหลัก</a>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

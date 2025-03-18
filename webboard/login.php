<?php
include 'db_connect.php';
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];
    
    // ตรวจสอบข้อมูลผู้ใช้จากฐานข้อมูล
    $sql = "SELECT * FROM users WHERE username='$username'";
    $result = $conn->query($sql);
    
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        
        // ตรวจสอบรหัสผ่าน
        if (password_verify($password, $row['password'])) {
            // ตั้งค่า session สำหรับข้อมูลผู้ใช้
            $_SESSION['user_id'] = $row['user_id'];
            $_SESSION['username'] = $row['username'];
            $_SESSION['role'] = $row['role']; // เพิ่มการตั้งค่า role ที่ได้จากฐานข้อมูล
            
            // ส่งผู้ใช้ไปยังหน้าหลักหรือหน้าที่เหมาะสม
            echo "<script>alert('เข้าสู่ระบบสำเร็จ!'); window.location.href='index.php';</script>";
        } else {
            echo "<script>alert('รหัสผ่านไม่ถูกต้อง!');</script>";
        }
    } else {
        echo "<script>alert('ไม่พบบัญชีผู้ใช้!');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>เข้าสู่ระบบ</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f4f7f9;
            font-family: 'Arial', sans-serif;
        }

        .login-container {
            background-color: white;
            padding: 40px;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            max-width: 400px;
            margin: 50px auto;
        }

        .btn-custom {
            background-color: #ff8ac5;
            color: white;
        }

        .btn-custom:hover {
            background-color: #f06292;
        }

        .form-label {
            font-weight: bold;
        }

        .register-link {
            text-align: center;
            margin-top: 20px;
        }

        .register-link a {
            color: #ff8ac5;
            font-weight: bold;
        }
    </style>
</head>
<body>

    <div class="login-container">
        <h2 class="text-center">เข้าสู่ระบบ</h2>
        
        <form method="post">
            <div class="mb-3">
                <label for="username" class="form-label">Username</label>
                <input type="text" name="username" class="form-control" id="username" required>
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <input type="password" name="password" class="form-control" id="password" required>
            </div>
            <button type="submit" class="btn btn-custom w-100">เข้าสู่ระบบ</button>
        </form>

        <div class="register-link">
            <p>ยังไม่เป็นสมาชิก? <a href="register.php">สมัครสมาชิกที่นี่</a></p>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

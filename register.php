<?php
include 'db_connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);
    $email = $_POST['email'];
    
    // อัปโหลดรูปโปรไฟล์
    $profile_image = 'default.png';
    if (!empty($_FILES['profile_image']['name'])) {
        $target_dir = "uploads/";
        $profile_image = basename($_FILES['profile_image']['name']);
        $target_file = $target_dir . $profile_image;
        move_uploaded_file($_FILES['profile_image']['tmp_name'], $target_file);
    }
    
    $sql = "INSERT INTO users (username, password, email, profile_image) VALUES ('$username', '$password', '$email', '$profile_image')";
    if ($conn->query($sql) === TRUE) {
        echo "<script>alert('สมัครสมาชิกสำเร็จ!'); window.location.href='login.php';</script>";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>สมัครสมาชิก</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f4f7f9;
            font-family: 'Arial', sans-serif;
        }

        .register-container {
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

        .login-link {
            text-align: center;
            margin-top: 20px;
        }

        .login-link a {
            color: #ff8ac5;
            font-weight: bold;
        }
    </style>
</head>
<body>

    <div class="register-container">
        <h2 class="text-center">สมัครสมาชิก</h2>
        
        <form method="post" enctype="multipart/form-data">
            <div class="mb-3">
                <label for="username" class="form-label">Username</label>
                <input type="text" name="username" class="form-control" id="username" required>
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <input type="password" name="password" class="form-control" id="password" required>
            </div>
            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" name="email" class="form-control" id="email" required>
            </div>
            <div class="mb-3">
                <label for="profile_image" class="form-label">Profile Image</label>
                <input type="file" name="profile_image" class="form-control" id="profile_image">
            </div>
            <button type="submit" class="btn btn-custom w-100">สมัครสมาชิก</button>
        </form>

        <div class="login-link">
            <p>มีบัญชีอยู่แล้ว? <a href="login.php">เข้าสู่ระบบ</a></p>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

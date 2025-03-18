<?php
session_start();
include('db_connect.php'); // รวมไฟล์ db_connect.php เพื่อเชื่อมต่อฐานข้อมูล

// ตรวจสอบว่า user ได้ล็อกอินหรือไม่
$is_logged_in = isset($_SESSION['username']);
$username = $is_logged_in ? $_SESSION['username'] : '';

// ตรวจสอบว่าผู้ใช้มีการตั้งค่า profile_image หรือไม่
$profile_image = isset($_SESSION['profile_image']) ? $_SESSION['profile_image'] : 'default.png'; // หากไม่มีให้ใช้ default.png

// หากผู้ใช้ไม่ได้ล็อกอิน ให้ redirect ไปหน้า login
if (!$is_logged_in) {
    header('Location: login.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $question_title = $_POST['question_title'];
    $question_details = $_POST['question_details'];

    // แทรกคำถามใหม่ลงในฐานข้อมูล
    $sql = "INSERT INTO webboard (username, question, details, status, create_date) 
            VALUES ('$username', '$question_title', '$question_details', 'active', NOW())";
    if (mysqli_query($conn, $sql)) {
        echo "<script>alert('Question created successfully!'); window.location.href = 'index.php';</script>";
    } else {
        echo "<script>alert('Error creating question.');</script>";
    }
}
// ถ้าผู้ใช้ล็อกอินแล้ว ให้ดึงข้อมูล role และ profile_image
$role = '';
$profile_image = 'default.png'; // ค่าเริ่มต้นของภาพโปรไฟล์
if ($is_logged_in) {
    // ดึงข้อมูล role ของผู้ใช้จากฐานข้อมูล
    $sql = "SELECT role, profile_image FROM users WHERE username = '$username' LIMIT 1";
    $result = mysqli_query($conn, $sql);
    if ($result) {
        $user_data = mysqli_fetch_assoc($result);
        $role = $user_data['role']; // ดึงค่า role จากฐานข้อมูล
        $profile_image = $user_data['profile_image'] ?: 'default.png'; // ถ้าไม่มีภาพโปรไฟล์ให้ใช้ default.png
    }
}

// ตรวจสอบว่าภาพโปรไฟล์มีอยู่ในโฟลเดอร์ uploads หรือไม่
if (!file_exists('uploads/' . $profile_image)) {
    $profile_image = 'default.png'; // ใช้ default.png หากไม่พบไฟล์
}
?>

<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Question</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
            font-family: 'Arial', sans-serif;
        }
        .container {
            margin-top: 50px;
            background-color: #ffffff;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }
        .form-label {
            font-weight: bold;
        }
        .btn-primary {
            background-color: #ff69b4;
            border: none;
        }
        .btn-primary:hover {
            background-color: #e05bbf;
        }
        .navbar {
            background-color: rgb(253, 151, 199);
        }
        .navbar-brand {
            color: #fff;
            font-weight: bold;
        }
        .navbar-nav .nav-link {
            color: #fff !important;
        }
        .navbar-nav .nav-link:hover {
            color: #ddd !important;
        }
        .navbar-nav .dropdown-menu {
            position: absolute;
            top: 100%; /* ทำให้เมนู dropdown อยู่ใต้เมนูหลัก */
            right: 0; /* จัดให้เมนูอยู่ทางขวา */
            left: auto;
            z-index: 1000; /* ป้องกันไม่ให้เมนูซ้อนกับองค์ประกอบอื่น */
        }
        .profile-dropdown {
            display: flex;
            align-items: center;
            cursor: pointer;
        }
        .profile-img {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            margin-right: 10px;
        }
        .alert {
            font-size: 1.1rem;
            margin-top: 20px;
        }
    </style>
</head>
<body>

<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-light">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">Webboard</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <?php if ($is_logged_in): ?>
                        <li class="nav-item dropdown">
                            <span class="nav-link profile-dropdown" id="navbarDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                                <img src="uploads/<?php echo $profile_image; ?>" alt="Profile Image" class="profile-img">
                                <?php echo $username; ?>
                            </span>
                            <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                                <li><span class="dropdown-item">Role: <?php echo htmlspecialchars($role); ?></span></li>
                                <li><a class="dropdown-item" href="profile.php">Profile</a></li>
                                <li><a class="dropdown-item" href="logout.php">Logout</a></li>
                                
                                <!-- เพิ่มเมนูสำหรับ Admin -->
                                <?php if ($role == 'admin'): ?>
                                    <li><a class="dropdown-item" href="admin_users.php">Manage Users</a></li>
                                <?php endif; ?>
                            </ul>
                        </li>
                    <?php else: ?>
                        <li class="nav-item">
                            <a class="nav-link" href="login.php">Login</a>
                        </li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </nav>

<div class="container">
    <h1 class="text-center mb-4">Create a New Question</h1>

    <form action="create_question.php" method="POST">
        <div class="mb-3">
            <label for="question_title" class="form-label">Question Title</label>
            <input type="text" class="form-control" id="question_title" name="question_title" required>
        </div>
        <div class="mb-3">
            <label for="question_details" class="form-label">Details</label>
            <textarea class="form-control" id="question_details" name="question_details" rows="4" required></textarea>
        </div>
        <div class="text-center">
            <button type="submit" class="btn btn-primary">Submit Question</button>
        </div>
    </form>

    <!-- ปุ่มกลับไปหน้าหลัก -->
    <div class="text-center mt-3">
        <a href="index.php" class="btn btn-secondary">Back to Home</a>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

<?php
include('db_connect.php');
session_start();

// ตรวจสอบว่าเป็นสมาชิกที่ล็อกอินหรือไม่
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$user_id = $_SESSION['user_id'];

// ดึงข้อมูลผู้ใช้จากฐานข้อมูล
$sql = "SELECT * FROM users WHERE user_id = '$user_id'";
$result = mysqli_query($conn, $sql);
$user = mysqli_fetch_assoc($result);

// ดึงข้อมูลประวัติการตั้งคำถามและแสดงความคิดเห็นของผู้ใช้
$sql_questions = "SELECT * FROM questions WHERE user_id = '$user_id'";
$result_questions = mysqli_query($conn, $sql_questions);

// ดึงข้อมูลคอมเมนต์ของผู้ใช้
$sql_comments = "SELECT comments.*, questions.title AS question_title FROM comments JOIN questions ON comments.question_id = questions.question_id WHERE comments.user_id = '$user_id'";
$result_comments = mysqli_query($conn, $sql_comments);

// อัพเดตข้อมูลผู้ใช้เมื่อมีการส่งฟอร์ม
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $new_password = $_POST['new_password'];
    $profile_image = $_FILES['profile_image']['name'];

    // เปลี่ยนชื่อผู้ใช้
    if (!empty($username)) {
        $sql_update = "UPDATE users SET username = '$username' WHERE user_id = '$user_id'";
        mysqli_query($conn, $sql_update);
    }

    // เปลี่ยนรหัสผ่าน
    if (!empty($new_password)) {
        if (password_verify($password, $user['password'])) {
            $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
            $sql_update_password = "UPDATE users SET password = '$hashed_password' WHERE user_id = '$user_id'";
            mysqli_query($conn, $sql_update_password);
        } else {
            echo "Current password is incorrect.";
        }
    }

    // อัพโหลดรูปโปรไฟล์
    if (!empty($profile_image)) {
        $target_dir = "uploads/";
        $target_file = $target_dir . basename($profile_image);
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
        $allowed_types = ['jpg', 'jpeg', 'png', 'gif'];
        $max_file_size = 5000000; // 5 MB

        if ($_FILES["profile_image"]["size"] > $max_file_size) {
            echo "Sorry, your file is too large.";
        } elseif (!in_array($imageFileType, $allowed_types)) {
            echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
        } else {
            if (move_uploaded_file($_FILES["profile_image"]["tmp_name"], $target_file)) {
                $sql_update_image = "UPDATE users SET profile_image = '$target_file' WHERE user_id = '$user_id'";
                mysqli_query($conn, $sql_update_image);
            } else {
                echo "Sorry, there was an error uploading your file.";
            }
        }
    }

    // Redirect ไปยังหน้าโปรไฟล์
    header('Location: profile.php');
    exit;
}

// ตรวจสอบว่าได้อัพโหลดภาพโปรไฟล์หรือไม่
if (isset($_FILES['profile_image']) && $_FILES['profile_image']['error'] == 0) {
    $profile_image = $_FILES['profile_image']['name'];
} else {
    // ถ้าไม่มีการอัพโหลดใหม่, ให้ใช้ค่าภาพเดิม
    $profile_image = $user['profile_image'];
}

?>

<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f4f7fc;
            font-family: 'Arial', sans-serif;
            color: #333;
        }

        .container {
            margin-top: 50px;
        }

        .profile-image {
            width: 150px;
            height: 150px;
            object-fit: cover;
            border-radius: 50%;
            border: 5px solid #4CAF50;
        }

        .btn-back {
            background-color: #4caf50;
            border-color: #4caf50;
            color: white;
        }

        .btn-back:hover {
            background-color: #45a049;
            border-color: #45a049;
        }

        .card {
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }

        .table th, .table td {
            vertical-align: middle;
        }
    </style>
</head>
<body>

<div class="container">
    <div class="card p-4">
        <h2 class="text-center mb-4">Profile of <?php echo htmlspecialchars($user['username']); ?></h2>

        <!-- แสดงภาพโปรไฟล์ -->
        <div class="text-center mb-4">
            <img src="<?php echo !empty($user['profile_image']) && file_exists($user['profile_image']) ? $user['profile_image'] : 'uploads/default-avatar.jpg'; ?>" class="profile-image" alt="Profile Image">
        </div>

        <form method="POST" enctype="multipart/form-data">
            <div class="mb-3">
                <label for="username" class="form-label">Username</label>
                <input type="text" class="form-control" name="username" value="<?php echo htmlspecialchars($user['username']); ?>" required>
            </div>

            <div class="mb-3">
                <label for="password" class="form-label">Current Password</label>
                <input type="password" class="form-control" name="password" placeholder="Enter your current password" required>
            </div>

            <div class="mb-3">
                <label for="new_password" class="form-label">New Password</label>
                <input type="password" class="form-control" name="new_password" placeholder="Enter your new password">
            </div>

            <div class="mb-3">
                <label for="profile_image" class="form-label">Profile Image</label>
                <input type="file" class="form-control" name="profile_image">
            </div>

            <button type="submit" class="btn btn-primary w-100">Update Profile</button>
        </form>

        <br><br>

        <h3>Questions Created</h3>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Question Title</th>
                    <th>Created At</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($question = mysqli_fetch_assoc($result_questions)): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($question['title']); ?></td>
                        <td><?php echo $question['created_at']; ?></td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>

        <h3>Comments Made</h3>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Comment</th>
                    <th>On Question</th>
                    <th>Created At</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($comment = mysqli_fetch_assoc($result_comments)): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($comment['content']); ?></td>
                        <td><?php echo htmlspecialchars($comment['question_title']); ?></td>
                        <td><?php echo $comment['created_at']; ?></td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>

        <div class="text-center">
            <a href="index.php" class="btn btn-back btn-sm">Back to Home</a>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

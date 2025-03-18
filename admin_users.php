<?php
include('db_connect.php');
session_start();

// ตรวจสอบว่าเป็น admin หรือไม่
if ($_SESSION['role'] != 'admin') {
    header('Location: index.php'); // ถ้าไม่ใช่ admin ให้กลับไปหน้าแรก
    exit;
}

// ดึงข้อมูลผู้ใช้ทั้งหมด รวมถึง id
$sql = "SELECT user_id, email, username, role FROM users";
$result = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Users</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        /* ตกแต่งเพิ่มเติม */
        body {
            background-color: #f0f8ff; /* สีพื้นหลังฟ้าสด */
            font-family: 'Arial', sans-serif;
            color: #333;
        }

        .navbar {
            background-color: #ff69b4; /* สีชมพูอ่อน */
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

        .container {
            margin-top: 30px;
        }

        h1 {
            color: #ff69b4; /* สีชมพูอ่อนสำหรับหัวข้อ */
            text-align: center;
            margin-bottom: 30px;
        }

        table {
            width: 100%;
            margin-top: 20px;
            border-collapse: collapse;
        }

        table, th, td {
            border: 1px solid #ddd;
        }

        th, td {
            padding: 12px 15px;
            text-align: center;
        }

        th {
            background-color: #ffebf0; /* สีชมพูอ่อนสำหรับหัวตาราง */
            color: #ff69b4;
        }

        .btn {
            margin: 5px;
        }

        .btn-warning {
            background-color: #f7b7c0; /* สีชมพูอ่อน */
            border-color: #f7b7c0;
        }

        .btn-danger {
            background-color: #ff4c7d; /* สีชมพูเข้ม */
            border-color: #ff4c7d;
        }

        .btn-secondary {
            background-color: #00bcd4; /* สีฟ้าสด */
            border-color: #00bcd4;
        }

        .btn:hover {
            opacity: 0.8;
        }

        .btn-back {
            background-color: #4caf50; /* สีเขียว */
            border-color: #4caf50;
            color: white;
        }

        .btn-back:hover {
            background-color: #45a049;
            border-color: #45a049;
        }
        .btn-custom {
            background-color: #007bff;
            color: white;
            border-radius: 5px;
        }

        .btn-custom:hover {
            background-color: #0056b3;
        }

        .page-header {
            text-align: center;
            color: #333;
            margin-bottom: 20px;
            font-size: 28px;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Manage Users</h1>

        <a href="add_user.php" class="btn btn-success btn-sm">Add New User</a>
        
        <!-- ปุ่มกลับหน้าหลัก -->
        <a href="index.php" class="btn btn-back btn-sm">กลับหน้าหลัก</a>

        <table>
            <tr>
                <th>Email</th>
                <th>Username</th>
                <th>Role</th>
                <th>Actions</th>
            </tr>
            <?php while ($user = mysqli_fetch_assoc($result)): ?>
            <tr>
                <td><?php echo htmlspecialchars($user['email']); ?></td>
                <td><?php echo htmlspecialchars($user['username']); ?></td>
                <td><?php echo htmlspecialchars($user['role']); ?></td>
                <td>
                    <!-- ปุ่มการแก้ไข -->
                    <a href="edit_user.php?id=<?php echo $user['user_id']; ?>" class="btn btn-warning btn-sm">Edit</a>

                    <!-- ปุ่มการลบ -->
                    <a href="delete_user.php?id=<?php echo $user['user_id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this user?')">Delete</a>

                    <!-- ปุ่มการบล็อก -->
                    <a href="block_user.php?id=<?php echo $user['user_id']; ?>" class="btn btn-secondary btn-sm" onclick="return confirm('Are you sure you want to block this user?')">Block</a>
                </td>
            </tr>
            <?php endwhile; ?>
        </table>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

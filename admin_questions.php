<?php
include 'db_connect.php';
session_start();

// ตรวจสอบสิทธิ์ admin
if ($_SESSION['role'] !== 'admin') {
    echo "<script>alert('คุณไม่มีสิทธิ์เข้าถึงหน้านี้!'); window.location.href='index.php';</script>";
    exit;
}

// ดึงข้อมูลกระทู้ทั้งหมดจากฐานข้อมูล
$sql = "SELECT * FROM webboard";
$result = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>จัดการคำถาม</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f4f7f9;
            font-family: 'Arial', sans-serif;
        }
        .container {
            background-color: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        .table th, .table td {
            vertical-align: middle;
        }
        .btn-custom {
            background-color: #ff8ac5;
            color: white;
        }
        .btn-custom:hover {
            background-color: #f06292;
        }
        .header-title {
            font-size: 2rem;
            font-weight: bold;
            color: #333;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>

<div class="container mt-5">
    <h2 class="header-title">จัดการคำถาม</h2>
    
    <!-- ค้นหากระทู้ -->
    <div class="mb-3">
        <form method="GET" action="">
            <div class="input-group">
                <input type="text" class="form-control" placeholder="ค้นหาคำถาม..." name="search" value="<?php echo isset($_GET['search']) ? $_GET['search'] : ''; ?>">
                <button class="btn btn-outline-secondary" type="submit">ค้นหา</button>
            </div>
        </form>
    </div>

    <!-- ตารางแสดงคำถาม -->
    <table class="table table-striped table-bordered">
        <thead>
            <tr>
                <th>#</th>
                <th>คำถาม</th>
                <th>ผู้ตั้งกระทู้</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($question = mysqli_fetch_assoc($result)): ?>
                <tr>
                    <td><?php echo $question['question_id']; ?></td>
                    <td><?php echo htmlspecialchars($question['question']); ?></td>
                    <td><?php echo $question['username']; ?></td>
                    <td>
                        <a href="edit_question.php?id=<?php echo $question['question_id']; ?>" class="btn btn-warning btn-sm">แก้ไข</a>
                        <a href="delete_question.php?id=<?php echo $question['question_id']; ?>" class="btn btn-danger btn-sm">ลบ</a>
                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
    <!-- ปุ่มกลับหน้าหลัก -->
    <a href="index.php" class="btn btn-back btn-sm">กลับหน้าหลัก</a>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

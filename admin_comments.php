<?php
include 'db_connect.php';
session_start();

// ตรวจสอบสิทธิ์ admin
if ($_SESSION['role'] !== 'admin') {
    echo "<script>alert('คุณไม่มีสิทธิ์เข้าถึงหน้านี้!'); window.location.href='index.php';</script>";
    exit;
}

// ดึงข้อมูลความคิดเห็นทั้งหมดจากฐานข้อมูล
$sql = "SELECT * FROM comments ORDER BY create_date DESC";
$result = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>จัดการความคิดเห็น</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f7f7f7;
            font-family: 'Arial', sans-serif;
        }

        .container {
            background-color: white;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            padding: 30px;
            margin-top: 50px;
        }

        .badge-warning {
            background-color: #ffb300;
        }

        .btn-warning, .btn-danger {
            border-radius: 5px;
        }

        .table th, .table td {
            vertical-align: middle;
        }

        .table-striped tbody tr:nth-child(odd) {
            background-color: #f9f9f9;
        }

        .table-striped tbody tr:nth-child(even) {
            background-color: #ffffff;
        }

        .table th {
            background-color: #007bff;
            color: white;
        }

        .table td {
            font-size: 14px;
            color: #333;
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
    <h2 class="page-header">จัดการความคิดเห็น</h2>
    <table class="table table-striped">
        <thead>
            <tr>
                <th>#</th>
                <th>ความคิดเห็น</th>
                <th>ผู้แสดงความคิดเห็น</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($comment = mysqli_fetch_assoc($result)): ?>
                <tr>
                    <td><?php echo $comment['comment_id']; ?></td>
                    <td><?php echo htmlspecialchars($comment['comment']); ?></td>
                    <td><?php echo $comment['username']; ?></td>
                    <td>
                        <?php if ($comment['status'] == 'hidden'): ?>
                            <span class="badge bg-warning">ซ่อน</span>
                        <?php else: ?>
                            <a href="hide_comment.php?id=<?php echo $comment['comment_id']; ?>" class="btn btn-warning btn-sm">ซ่อน</a>
                        <?php endif; ?>
                        <a href="delete_comment.php?id=<?php echo $comment['comment_id']; ?>" class="btn btn-danger btn-sm">ลบ</a>
                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

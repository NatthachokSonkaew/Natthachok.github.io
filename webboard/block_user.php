<?php
include 'db_connect.php';
session_start();

// ตรวจสอบสิทธิ์ admin
if ($_SESSION['role'] !== 'admin') {
    echo "<script>alert('คุณไม่มีสิทธิ์เข้าถึงหน้านี้!'); window.location.href='index.php';</script>";
    exit;
}

if (isset($_GET['id'])) {
    $user_id = $_GET['id'];

    // อัปเดตสถานะผู้ใช้ให้เป็นบล็อค
    $sql = "UPDATE users SET status='blocked' WHERE user_id = $user_id";
    if ($conn->query($sql) === TRUE) {
        echo "<script>alert('บล็อคผู้ใช้สำเร็จ!'); window.location.href='admin_users.php';</script>";
    } else {
        echo "Error: " . $conn->error;
    }
}
?>

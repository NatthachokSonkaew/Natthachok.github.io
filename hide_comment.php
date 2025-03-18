<?php
include 'db_connect.php';
session_start();

// ตรวจสอบสิทธิ์ admin
if ($_SESSION['role'] !== 'admin') {
    echo "<script>alert('คุณไม่มีสิทธิ์เข้าถึงหน้านี้!'); window.location.href='index.php';</script>";
    exit;
}

if (isset($_GET['id'])) {
    $comment_id = (int)$_GET['id'];
    
    // เปลี่ยนสถานะของความคิดเห็นเป็น "hidden"
    $sql = "UPDATE comments SET status = 'hidden' WHERE comment_id = $comment_id";
    if ($conn->query($sql) === TRUE) {
        echo "<script>alert('ซ่อนความคิดเห็นสำเร็จ!'); window.location.href='admin_comments.php';</script>";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}
?>

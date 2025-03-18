<?php
include 'db_connect.php';
session_start();

// ตรวจสอบสิทธิ์ admin
if ($_SESSION['role'] !== 'admin') {
    echo "<script>alert('คุณไม่มีสิทธิ์เข้าถึงหน้านี้!'); window.location.href='index.php';</script>";
    exit;
}

if (isset($_GET['id'])) {
    $question_id = (int)$_GET['id'];

    // อัปเดตสถานะคำถามเป็น "ซ่อน"
    $sql = "UPDATE webboard SET status = 'hidden' WHERE question_id = $question_id";
    
    if ($conn->query($sql) === TRUE) {
        echo "<script>alert('ซ่อนคำถามสำเร็จ!'); window.location.href='admin_questions.php';</script>";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}
?>

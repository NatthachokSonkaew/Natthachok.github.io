<?php
include 'db_connect.php';
session_start();

// ตรวจสอบสิทธิ์ admin
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    echo "<script>alert('คุณไม่มีสิทธิ์เข้าถึงหน้านี้!'); window.location.href='index.php';</script>";
    exit;
}

if (isset($_GET['id'])) {
    $question_id = (int)$_GET['id']; // รับค่าจาก URL และแปลงเป็นจำนวนเต็ม

    // เตรียมคำสั่ง SQL เพื่อลบคำถาม
    $sql = "DELETE FROM webboard WHERE question_id = ?";
    
    // ใช้ prepared statement เพื่อป้องกัน SQL Injection
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("i", $question_id);  // ผูกค่าตัวแปร $question_id กับคำสั่ง SQL
        if ($stmt->execute()) {
            echo "<script>alert('ลบคำถามสำเร็จ!'); window.location.href='admin_questions.php';</script>";
        } else {
            echo "<script>alert('เกิดข้อผิดพลาดในการลบคำถาม!'); window.location.href='admin_questions.php';</script>";
        }
        $stmt->close();
    } else {
        echo "Error: " . $conn->error;
    }
} else {
    echo "<script>alert('คำถามไม่ถูกต้อง'); window.location.href='admin_questions.php';</script>";
}

$conn->close();
?>

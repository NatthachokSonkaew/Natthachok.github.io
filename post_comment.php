<?php
session_start();
include('db_connect.php');

if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit();
}

$question_id = isset($_POST['question_id']) ? (int)$_POST['question_id'] : 0;
$comment = isset($_POST['comment']) ? $_POST['comment'] : '';

// ตรวจสอบว่า comment ไม่ว่าง
if (empty($comment)) {
    echo "Please enter a comment.";
    exit();
}

// เก็บความคิดเห็นในฐานข้อมูล
$username = $_SESSION['username'];
$sql = "INSERT INTO comments (question_id, username, comment) VALUES ($question_id, '$username', '$comment')";
if (mysqli_query($conn, $sql)) {
    header("Location: view_question.php?id=$question_id");
} else {
    echo "Error: " . mysqli_error($conn);
}
?>

<?php
session_start();
session_unset(); // ลบข้อมูลใน session
session_destroy(); // ทำลาย session
header('Location: index.php'); // ส่งผู้ใช้กลับไปหน้า index
exit;
?>

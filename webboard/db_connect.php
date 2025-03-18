<?php
$servername = "sql109.infinityfree.com";
$username = "if0_38115422";
$password = "kHvaMHsxkDWQ";
$dbname = "if0_38115422_webboard";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>

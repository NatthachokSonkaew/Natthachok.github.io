<?php
include 'db_connect.php';

$username = $_POST['username'];
$email = $_POST['email'];
$password = password_hash($_POST['password'], PASSWORD_DEFAULT);
$profile_pic = "default.jpg";

if (!empty($_FILES["profile_pic"]["name"])) {
    $target_dir = "uploads/";
    $profile_pic = $target_dir . basename($_FILES["profile_pic"]["name"]);
    move_uploaded_file($_FILES["profile_pic"]["tmp_name"], $profile_pic);
}

$sql = "INSERT INTO users (Username, Email, Password, ProfilePic) VALUES ('$username', '$email', '$password', '$profile_pic')";
$conn->query($sql);
header("Location: login.php");
?>

<?php
session_start();
include('db_connect.php'); // เชื่อมต่อฐานข้อมูล

// ตรวจสอบว่ามีการส่งข้อมูลจากฟอร์มหรือไม่
if (isset($_POST['email'], $_POST['password'], $_POST['username'])) {
    // รับข้อมูลจากฟอร์ม
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);
    $name = mysqli_real_escape_string($conn, $_POST['username']);

    // ตรวจสอบอีเมลซ้ำในฐานข้อมูล
    $sql_check_email = "SELECT * FROM users WHERE email = '$email'";
    $result_check_email = mysqli_query($conn, $sql_check_email);

    if (mysqli_num_rows($result_check_email) > 0) {
        echo "อีเมลนี้มีผู้ใช้งานแล้ว, กรุณาใช้เมลอื่น.";
    } else {
        // ถ้าอีเมลยังไม่เคยลงทะเบียนในฐานข้อมูล
        $hashed_password = password_hash($password, PASSWORD_DEFAULT); // แฮชพาสเวิร์ด

        // คำสั่ง SQL ในการเพิ่มข้อมูล
        $sql = "INSERT INTO users (email, password, name, created_at) 
                VALUES ('$email', '$hashed_password', '$name', NOW())";

        if (mysqli_query($conn, $sql)) {
            echo "สมัครสมาชิกสำเร็จ!";
        } else {
            echo "เกิดข้อผิดพลาด: " . mysqli_error($conn);
        }
    }
}

// ตรวจสอบว่า user ได้ล็อกอินหรือไม่
$is_logged_in = isset($_SESSION['username']);
$username = $is_logged_in ? $_SESSION['username'] : '';

// ถ้าผู้ใช้ล็อกอินแล้ว ให้ดึงข้อมูล role และ profile_image
$role = '';
$profile_image = 'default.png'; // ค่าเริ่มต้นของภาพโปรไฟล์
if ($is_logged_in) {
    // ดึงข้อมูล role ของผู้ใช้จากฐานข้อมูล
    $sql = "SELECT role, profile_image FROM users WHERE username = '$username' LIMIT 1";
    $result = mysqli_query($conn, $sql);
    if ($result) {
        $user_data = mysqli_fetch_assoc($result);
        $role = $user_data['role']; // ดึงค่า role จากฐานข้อมูล
        $profile_image = $user_data['profile_image'] ?: 'default.png'; // ถ้าไม่มีภาพโปรไฟล์ให้ใช้ default.png
    }
}

// ตรวจสอบว่าภาพโปรไฟล์มีอยู่ในโฟลเดอร์ uploads หรือไม่
if (!file_exists('uploads/' . $profile_image)) {
    $profile_image = 'default.png'; // ใช้ default.png หากไม่พบไฟล์
}
?>

<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Webboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f4f7f9;
            font-family: 'Arial', sans-serif;
        }

        .navbar {
            background-color: rgb(253, 151, 199);
        }

        .navbar-brand {
            color: #fff;
            font-weight: bold;
        }

        .navbar-nav .nav-link {
            color: #fff !important;
        }

        .navbar-nav .nav-link:hover {
            color: #ddd !important;
        }
        .navbar-nav .dropdown-menu {
            position: absolute;
            top: 100%; /* ทำให้เมนู dropdown อยู่ใต้เมนูหลัก */
            right: 0; /* จัดให้เมนูอยู่ทางขวา */
            left: auto;
            z-index: 1000; /* ป้องกันไม่ให้เมนูซ้อนกับองค์ประกอบอื่น */
        }
        .profile-dropdown {
            display: flex;
            align-items: center;
            cursor: pointer;
        }
        .profile-img {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            margin-right: 10px;
        }

        .container {
            margin-top: 30px;
        }

        .question-list {
            margin-top: 30px;
        }

        .question-list li {
            padding: 15px;
            background-color: #ffffff;
            margin-bottom: 15px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            transition: background-color 0.3s ease;
        }

        .question-list li:hover {
            background-color:rgb(255, 105, 188);
        }

        .question-list a {
            text-decoration: none;
            color: rgb(243, 75, 117);
            font-weight: bold;
        }

        .question-list a:hover {
            color: rgb(212, 143, 230);
        }

        .alert {
            font-size: 1.1rem;
            margin-top: 20px;
        }

        .admin-btn {
            background-color: #FF6347; /* สีพื้นหลังของปุ่ม */
            color: white; /* สีตัวอักษร */
            border: none; /* ไม่ให้มีกรอบ */
            padding: 10px 15px; /* ระยะห่างภายในปุ่ม */
            border-radius: 5px; /* มุมโค้งของปุ่ม */
            font-size: 14px; /* ขนาดตัวอักษร */
            cursor: pointer; /* เปลี่ยนเคอร์เซอร์เมื่อชี้ไปที่ปุ่ม */
            transition: background-color 0.3s ease, transform 0.2s ease; /* เพิ่มการเปลี่ยนสีและการย่อขยายเมื่อเลื่อนเมาส์ */
            display: inline-block;
            margin-right: 10px; /* เพิ่มระยะห่างระหว่างปุ่ม */
        }

        .admin-btn:hover {
            background-color: #FF4500; /* สีพื้นหลังเมื่อเลื่อนเมาส์ไปที่ปุ่ม */
            transform: scale(1.05); /* ทำให้ปุ่มขยายเล็กน้อยเมื่อเลื่อนเมาส์ */
        }

        .admin-btn:active {
            background-color: #FF0000; /* สีพื้นหลังเมื่อคลิกปุ่ม */
            transform: scale(0.95); /* ลดขนาดปุ่มเล็กน้อยเมื่อคลิก */
        }

        .admin-btn + .admin-btn {
            margin-left: 10px; /* ระยะห่างระหว่างปุ่ม Delete และ Hide */
        }
    </style>
</head>
<body>

    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-light">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">Webboard</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <?php if ($is_logged_in): ?>
                        <li class="nav-item dropdown">
                            <span class="nav-link profile-dropdown" id="navbarDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                                <img src="uploads/<?php echo $profile_image; ?>" alt="Profile Image" class="profile-img">
                                <?php echo $username; ?>
                            </span>
                            <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                                <li><span class="dropdown-item">Role: <?php echo htmlspecialchars($role); ?></span></li>
                                <li><a class="dropdown-item" href="profile.php">Profile</a></li>
                                <li><a class="dropdown-item" href="logout.php">Logout</a></li>
                            </ul>
                        </li>
                    <?php else: ?>
                        <li class="nav-item">
                            <a class="nav-link" href="login.php">Login</a>
                        </li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container">
        <h1 class="text-center mb-4">Welcome to the Webboard!</h1>

        <!-- ส่วนแสดงปุ่มสร้างคำถาม -->
        <?php if ($is_logged_in): ?>
            <div class="text-center">
                <a href="create_question.php" class="btn btn-primary btn-lg">Create a Question</a>
            </div>
        <?php else: ?>
            <div class="alert alert-warning" role="alert">
                Please <a href="login.php" class="alert-link">login</a> to ask a question.
            </div>
        <?php endif; ?>

        <!-- แสดงคำถามที่มีอยู่ -->
        <div class="question-list">
            <h2>Recent Questions</h2>
            <ul>
                <?php
                $sql = "SELECT * FROM webboard WHERE status = 'active' ORDER BY create_date DESC";
                $result = mysqli_query($conn, $sql);
                if (mysqli_num_rows($result) > 0) {
                    while ($row = mysqli_fetch_assoc($result)) {
                        $create_date = date("d-m-Y H:i", strtotime($row['create_date']));
                        echo '<li>';
                        echo '<h4><a href="view_question.php?id=' . $row['question_id'] . '">' . htmlspecialchars($row['question']) . '</a></h4>';
                        echo '<p><strong>' . htmlspecialchars($row['username']) . '</strong> asked on ' . $create_date . '</p>';

                        // เฉพาะผู้ใช้ที่เป็น admin เท่านั้นที่จะเห็นปุ่มลบและซ่อนคำถาม
                        if ($role == 'admin') {
                            echo '<div class="mt-2">';
                            echo '<a href="delete_question.php?id=' . $row['question_id'] . '" class="admin-btn" onclick="return confirmDelete()">Delete</a>';
                            echo ' <a href="hide_question.php?id=' . $row['question_id'] . '" class="admin-btn" onclick="return confirmHide()">Hide</a>';
                            echo '</div>';
                        }

                        echo '</li>';
                    }
                } else {
                    echo '<li>No questions available.</li>';
                }
                ?>
                <script>
                    // ฟังก์ชันยืนยันการลบคำถาม
                    function confirmDelete() {
                        return confirm('Are you sure you want to delete this question?');
                    }

                    // ฟังก์ชันยืนยันการซ่อนคำถาม
                    function confirmHide() {
                        return confirm('Are you sure you want to hide this question?');
                    }
                </script>

            </ul>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

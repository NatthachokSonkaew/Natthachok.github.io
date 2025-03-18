<?php
session_start();
include('db_connect.php'); // รวมไฟล์ db_connect.php เพื่อเชื่อมต่อฐานข้อมูล

// ตรวจสอบว่า user ได้ล็อกอินหรือไม่
$is_logged_in = isset($_SESSION['username']);
$username = $is_logged_in ? $_SESSION['username'] : '';

// ตรวจสอบว่าผู้ใช้มีการตั้งค่า profile_image หรือไม่
$profile_image = isset($_SESSION['profile_image']) ? $_SESSION['profile_image'] : 'default.png'; // หากไม่มีให้ใช้ default.png

// ตรวจสอบว่า user มีบทบาทเป็น admin หรือไม่
$is_admin = isset($_SESSION['role']) && $_SESSION['role'] === 'admin';

// ตรวจสอบ ID ของคำถาม
$question_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

// ดึงข้อมูลคำถามจากฐานข้อมูล
$sql = "SELECT * FROM webboard WHERE question_id = $question_id";
$result = mysqli_query($conn, $sql);
$question = mysqli_fetch_assoc($result);

// ถ้าไม่พบคำถาม
if (!$question) {
    echo "Question not found.";
    exit;
}
// ดึงความคิดเห็นที่เกี่ยวข้องกับคำถามนี้
$comments_sql = "SELECT * FROM comments WHERE question_id = $question_id AND status != 'deleted' ORDER BY create_date DESC";
$comments_result = mysqli_query($conn, $comments_sql);

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
    <title>View Question</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f4f7f9;
            font-family: 'Arial', sans-serif;
        }

        .navbar {
            background-color:rgb(253, 151, 199);
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

        .container {
            margin-top: 30px;
        }

        .question-box {
            background-color: #ffffff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .comment-box {
            margin-top: 20px;
        }

        .comment-box .comment {
            background-color: #ffffff;
            padding: 15px;
            margin-bottom: 10px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .comment-box .comment p {
            margin-bottom: 0;
        }

        .comment-box .comment span {
            font-size: 0.9rem;
            color: #888;
        }

        .alert {
            font-size: 1.1rem;
            margin-top: 20px;
        }

        /* รูปโปรไฟล์ */
        .profile-img {
            width: 30px;
            height: 30px;
            border-radius: 50%;
            margin-right: 10px;
        }

        /* ปรับเมนู dropdown */
        .navbar-nav .dropdown-menu {
            position: absolute;
            top: 100%; /* ทำให้เมนู dropdown อยู่ใต้เมนูหลัก */
            right: 0; /* จัดให้เมนูอยู่ทางขวา */
            left: auto;
            z-index: 1000; /* ป้องกันไม่ให้เมนูซ้อนกับองค์ประกอบอื่น */
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
        <!-- แสดงคำถาม -->
        <div class="question-box">
            <h2><?php echo htmlspecialchars($question['question']); ?></h2>
            <p><?php echo nl2br(htmlspecialchars($question['details'])); ?></p>
        </div>

        <!-- แสดงความคิดเห็น -->
        <div class="comment-box">
            <h3>Comments</h3>
            <?php if (mysqli_num_rows($comments_result) > 0): ?>
                <?php while ($comment = mysqli_fetch_assoc($comments_result)): ?>
                    <?php if ($comment['status'] == 'hidden'): ?>
                        <div class="comment">
                            <p>This comment is hidden by the admin.</p>
                        </div>
                    <?php else: ?>
                        <div class="comment">
                            <p><strong><?php echo htmlspecialchars($comment['username']); ?></strong> said:</p>
                            <p><?php echo nl2br(htmlspecialchars($comment['comment'])); ?></p>
                            <span><?php echo date("d-m-Y H:i", strtotime($comment['create_date'])); ?></span>
                            
                            <!-- สำหรับแอดมินจะเห็นปุ่มลบหรือซ่อนความคิดเห็น -->
                            <?php if ($is_admin): ?>
                                <div class="comment-actions">
                                    <!-- ปุ่มลบความคิดเห็น -->
                                    <a href="delete_comment.php?id=<?php echo $comment['comment_id']; ?>&question_id=<?php echo $question_id; ?>" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this comment?');">Delete</a>

                                    <!-- ปุ่มซ่อนความคิดเห็น -->
                                    <a href="hide_comment.php?id=<?php echo $comment['comment_id']; ?>&question_id=<?php echo $question_id; ?>" class="btn btn-warning" onclick="return confirm('Are you sure you want to hide this comment?');">Hide</a>
                                </div>
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>
                <?php endwhile; ?>
            <?php else: ?>
                <p>No comments yet.</p>
            <?php endif; ?>
        </div>

        <!-- เพิ่มความคิดเห็น -->
        <?php if ($is_logged_in): ?>
            <div class="comment-box">
                <h3>Post a Comment</h3>
                <form action="post_comment.php" method="POST">
                    <input type="hidden" name="question_id" value="<?php echo $question_id; ?>">
                    <div class="mb-3">
                        <textarea class="form-control" name="comment" rows="3" required></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary">Post Comment</button>
                </form>
            </div>
        <?php else: ?>
            <p>Please <a href="login.php">login</a> to post a comment.</p>
        <?php endif; ?>
        
        <!-- ปุ่มกลับหน้าหลัก -->
        <div class="mb-3 d-flex justify-content-end">
            <a href="index.php" class="btn btn-primary">Back to Home</a>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

<?php
session_start();

include 'connect.php';

// Logout logic
if (isset($_GET['action']) && $_GET['action'] === 'logout') {
    session_destroy();
    header("Location: admin_login.php");
    exit();
}

// Check if the admin is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: admin_login.php");
    exit();
}

// Fetch admin details based on the user_id stored in the session
$adminStmt = $pdo->prepare("SELECT * FROM users WHERE user_id = :user_id AND is_admin = 1");
$adminStmt->bindParam(':user_id', $_SESSION['user_id']);
$adminStmt->execute();
$admin = $adminStmt->fetch(PDO::FETCH_ASSOC);

// Check if admin details are retrieved
if (!$admin) {
    // Redirect to login if admin details are not found
    header("Location: admin_login.php");
    exit();
}

// Update admin details if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $newEmail = $_POST['email'] ?? '';
    $newPassword = $_POST['password'] ?? '';

    // Validate input as needed

    // Update email and password in the database
    $updateStmt = $pdo->prepare("UPDATE users SET email = :email, password = :password WHERE user_id = :user_id");
    $updateStmt->bindParam(':email', $newEmail);
    $updateStmt->bindParam(':password', password_hash($newPassword, PASSWORD_DEFAULT));
    $updateStmt->bindParam(':user_id', $_SESSION['user_id']);
    $updateStmt->execute();

    // Redirect to the same page to prevent form resubmission
    header("Location: admin_profile.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Profile</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }
        #sidebar {
            width: 250px;
            height: 100%;
            background: #2c3e50;
            position: fixed;
            left: 0;
            overflow-x: hidden;
            padding-top: 20px;
            text-align: center;
            color: #ecf0f1;
        }

        #profile-pic {
            border: 3px solid #fff;
            border-radius: 50%;
            margin-bottom: 10px;
            width: 100px;
            height: 100px;
            object-fit: cover;
        }

        #sidebar a {
            padding: 15px 10px;
            text-decoration: none;
            font-size: 18px;
            color: #ecf0f1;
            display: block;
            transition: 0.3s;
        }

        #sidebar a:hover {
            background-color: #34495e;
        }

        #sidebar .sub-menu {
            display: none;
            padding-left: 20px;
        }

        #sidebar .parent:hover .sub-menu {
            display: block;
        }

        #content {
            margin-left: 250px;
            padding: 15px;
            background-color: #fff;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .parent {
            color: white;
        }

        #content h2 {
            padding-top: 50px;
            font-size: 50px;
            color: #333;
        }

        form {
            margin: 50px 50px 230px 50px;

        }

        label {
            display: block;
            margin-bottom: 8px;
            color: #333;
        }

        input {
            width: 100%;
            padding: 10px;
            margin-bottom: 16px;
            box-sizing: border-box;
        }

        button {
            background-color: #3498db;
            color: white;
            padding: 12px 20px;
            border: none;
            cursor: pointer;
            font-size: 16px;
        }

        button:hover {
            background-color: #2980b9;
        }

        .profile-header {
            display: flex;
            align-items: center;
            background-color: #2c3e50;
            padding: 10px;
            color: white;
        }

        .profile-header img {
            border: 2px solid white;
            border-radius: 50%;
            margin-right: 10px;
        }

        .profile-name {
            font-size: 20px;
            font-weight: bold;
        }

        .username {
            font-size: 16px;
        }
    </style>
</head>
<body>
    <div id="sidebar">
        <img id="profile-pic" src="img/default_profile_image.jpg" alt="Admin Profile Picture">
        <a href="admin_profile.php">Admin Profile</a>
        <a href="admin_event.php">Event</a>
        <div class="parent" onclick="toggleSubMenu('sub-menu-students')">
            <p>Student by Year</p>
            <div class="sub-menu" id="sub-menu-students">
                <a href="admin_student1.php">Year 1</a>
                <a href="admin_student2.php">Year 2</a>
                <a href="admin_student3.php">Year 3</a>
                <a href="admin_student4.php">Year 4</a>
            </div>
        </div>
        <a href="admin_comment.php">Comments and Post</a>
        <a href="admin_resources.php">Uploaded Resources</a>
        <a href="admin_resources.php?action=logout">Logout</a>
    </div>

    <div id="content">
        <h2>Welcome, <?= htmlspecialchars($admin['username']) ?>!</h2>
        
        <form method="post">
            <label for="email">Email:</label>
            <input type="email" name="email" value="<?= htmlspecialchars($admin['email']) ?>" required>

            <label for="password">Password:</label>
            <input type="password" name="password" required>

            <button type="submit">Update Profile</button>
        </form>
    </div>

    <div class="profile-header">
        <img src="<?= htmlspecialchars($admin['profile_picture']) ?>" alt="User Profile" class="profile-image" style="width: 50px; height: 50px;">
        <div>
            <div class="profile-name" id="profileName"><?= htmlspecialchars($admin['username']) ?></div>
            <div class="username" id="profileUsername"><?= htmlspecialchars($admin['email']) ?></div>
        </div>
    </div>
</body>
</html>

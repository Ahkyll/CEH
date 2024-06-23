<?php
include 'connect.php';

// Fetch users for Year 1
$year1UsersStmt = $pdo->prepare("SELECT * FROM users WHERE year = '2nd'");
$year1UsersStmt->execute();
$year1Users = $year1UsersStmt->fetchAll(PDO::FETCH_ASSOC);

// Logout logic
if (isset($_GET['action']) && $_GET['action'] === 'logout') {
    session_destroy();
    header("Location: admin_login.php"); // Redirect to your login page after logout
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Year 1 Students</title>
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


        .year-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        .year-table th, .year-table td {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: left;
        }

        .year-table th {
            background-color: #4b5d67;
            color: #ffffff;
        }

        .action-btn {
            background-color: #4CAF50;
            color: #ffffff;
            padding: 8px 12px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            margin-right: 5px;
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
        <h2>Year 2 Students</h2>
        <table class="year-table">
            <thead>
                <tr>
                    <th>User_Id</th>
                    <th>Username</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Section</th>
                    <th>Profile Picture</th>
                    <th>Date Created</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($year1Users as $user): ?>
                    <tr>
                        <td><?= $user['user_id']; ?></td>
                        <td><?= $user['username']; ?></td>
                        <td><?= $user['name']; ?></td>
                        <td><?= $user['email']; ?></td>
                        <td><?= $user['section']; ?></td>
                        <td><?= $user['profile_picture']; ?></td>
                        <td><?= $user['created_at']; ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <script>
        // Replace the following functions with your actual admin/user role assignment logic
        function makeAdmin(userId, year) {
            alert("Make user with ID " + userId + " in Year " + year + " an admin");
            // Add logic to make the user an admin
        }

        function makeUser(userId, year) {
            alert("Make admin with ID " + userId + " in Year " + year + " a user");
            // Add logic to make the admin a user
        }
    </script>
</body>
</html>

<?php
session_start();
include 'connect.php';

// Fetch forums
$forumStmt = $pdo->prepare("SELECT * FROM tb_forum");
$forumStmt->execute();
$forums = $forumStmt->fetchAll(PDO::FETCH_ASSOC);

// Fetch comments
$commentStmt = $pdo->prepare("SELECT * FROM tb_comments");
$commentStmt->execute();
$comments = $commentStmt->fetchAll(PDO::FETCH_ASSOC);

if (isset($_GET['action'])) {
    $action = $_GET['action'];
    
    if ($action === 'delete_forum' && isset($_GET['forum_id'])) {
        $forumId = $_GET['forum_id'];
        
        // Delete the forum from the database
        $deleteForumSql = "DELETE FROM tb_forum WHERE post_id = ?";
        $deleteForumStmt = $pdo->prepare($deleteForumSql);
        $deleteForumStmt->execute([$forumId]);

        // Redirect back to the admin_comment.php page
        header("Location: admin_comment.php");
        exit();
    } elseif ($action === 'delete_comment' && isset($_GET['comment_id'])) {
        $commentId = $_GET['comment_id'];

        // Delete the comment from the database
        $deleteCommentSql = "DELETE FROM tb_comments WHERE comment_id = ?";
        $deleteCommentStmt = $pdo->prepare($deleteCommentSql);
        $deleteCommentStmt->execute([$commentId]);

        // Redirect back to the admin_comment.php page
        header("Location: admin_comment.php");
        exit();
    }
}
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
    <title>Admin - Comments Management</title>
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

        #content {
            margin-left: 250px;
            padding: 15px;
        }


        .post-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        .post-table th, .post-table td {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: left;
        }

        .post-table th {
            background-color: #4b5d67;
            color: #ffffff;
        }

        .comment-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        .comment-table th, .comment-table td {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: left;
        }

        .comment-table th {
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
    <h2>Comments and Forums Management</h2>

    <!-- Forum Table -->
    <h3>Forums</h3>
    <table class="post-table">
        <thead>
        <tr>
            <th>Uploader User Id</th>
            <th>Post ID</th>
            <th>Post Content</th>
            <th>Post Date</th>
            <th>Actions</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($forums as $forum): ?>
            <tr>
                <td><?php echo $forum['user_id']; ?></td>
                <td><?php echo $forum['post_id']; ?></td>
                <td><?php echo $forum['post_content']; ?></td>
                <td><?php echo $forum['post_date']; ?></td>
                <td>
                <button class="action-btn" onclick="deleteForum(<?php echo $forum['post_id']; ?>)">Delete Forum</button>

                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>

    <!-- Comment Table -->
    <h3>Comments</h3>
    <table class="comment-table">
        <thead>
        <tr>
            <th>Replied User Id</th>
            <th>Post ID</th>
            <th>Comment Content</th>
            <th>Comment Date</th>
            <th>Actions</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($comments as $comment): ?>
            <tr>
                <td><?php echo $comment['user_id']; ?></td>
                <td><?php echo $comment['post_id']; ?></td>
                <td><?php echo $comment['comment_content']; ?></td>
                <td><?php echo $comment['comment_date']; ?></td>
                <td>
                <button class="action-btn" onclick="deleteComment(<?php echo $comment['comment_id']; ?>)">Delete Comment</button>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</div>

<script>
     function deleteForum(forumId) {
        if (confirm("Are you sure you want to delete this forum?")) {
            window.location.href = "admin_comment.php?action=delete_forum&forum_id=" + forumId;
        }
    }

    function deleteComment(commentId) {
        if (confirm("Are you sure you want to delete this comment?")) {
            window.location.href = "admin_comment.php?action=delete_comment&comment_id=" + commentId;
        }
    }
</script>

</body>
</html>

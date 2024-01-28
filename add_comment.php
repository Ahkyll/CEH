<?php
session_start();
include 'connect.php';

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Handle the creation of a new comment
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit-comment'])) {
    $postId = $_POST['post_id'];
    $commentContent = htmlspecialchars($_POST['comment-content']);

    // Validate input if needed

    // Check if the provided post_id exists in tb_forum
    $checkPostSql = "SELECT COUNT(*) FROM tb_forum WHERE post_id = ?";
    $checkPostStmt = $pdo->prepare($checkPostSql);

    if (!$checkPostStmt->execute([$postId])) {
        // Handle database error
        die("Database error while checking post_id.");
    }

    if ($checkPostStmt->fetchColumn() > 0) {
        // Insert the new comment into the database
        $userId = $_SESSION['user_id'];

        
        $insertCommentSql = "INSERT INTO tb_comments (post_id, user_id, comment_content, comment_date, comment_time) VALUES (?, ?, ?, NOW(), NOW())";        
        $insertCommentStmt = $pdo->prepare($insertCommentSql);

        if (!$insertCommentStmt->execute([$postId, $userId, $commentContent])) {
            // Handle database error
            die("Database error while inserting comment.");
        }

        // Redirect after successful form submission
        header("Location: forum_display.php");
        exit();
    } else {
        // Handle the case where the provided post_id doesn't exist
        echo "Error: Invalid post_id for comment.";
    }
}
?>

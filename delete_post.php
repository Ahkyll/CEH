<?php
session_start();
include 'connect.php';

if (isset($_GET['post_id'])) {
    $postId = $_GET['post_id'];

    // Delete associated comments first
    $deleteCommentsSql = "DELETE FROM tb_comments WHERE post_id = ?";
    $deleteCommentsStmt = $pdo->prepare($deleteCommentsSql);
    $deleteCommentsStmt->execute([$postId]);

    // Now delete the post
    $deletePostSql = "DELETE FROM tb_forum WHERE post_id = ?";
    $deletePostStmt = $pdo->prepare($deletePostSql);
    $deletePostStmt->execute([$postId]);

    // Redirect after successful deletion
    header("Location: forum_display.php");
    exit();
} else {
    // Redirect if post_id is not provided
    header("Location: forum_display.php");
    exit();
}
?>

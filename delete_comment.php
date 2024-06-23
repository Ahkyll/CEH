<?php
session_start();
include 'connect.php';

if (isset($_GET['comment_id'])) {
    $commentId = $_GET['comment_id'];

    // Fetch comment details
    $fetchCommentSql = "SELECT * FROM tb_comments WHERE comment_id = ?";
    $fetchCommentStmt = $pdo->prepare($fetchCommentSql);
    $fetchCommentStmt->execute([$commentId]);
    $comment = $fetchCommentStmt->fetch(PDO::FETCH_ASSOC);

    // Check if the logged-in user is the owner of the comment
    if ($comment && $comment['user_id'] == $_SESSION['user_id']) {
        // Delete the comment from the database
        $deleteCommentSql = "DELETE FROM tb_comments WHERE comment_id = ?";
        $deleteCommentStmt = $pdo->prepare($deleteCommentSql);
        $deleteCommentStmt->execute([$commentId]);

        // Redirect back to the post or specific page
        header("Location: forum_display.php"); // You can modify this URL as needed
        exit();
    }
}
?>

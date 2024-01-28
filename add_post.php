<?php
session_start();
include 'connect.php';

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php"); // Redirect to the login page if not logged in
    exit();
}

// Handle the creation of a new post
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit-post'])) {
    $postContent = htmlspecialchars($_POST['post-content']);

    // You may want to perform further validation here

    // Insert the new post into the database
    $userId = $_SESSION['user_id'];

    $insertPostSql = "INSERT INTO tb_forum (user_id, post_content, post_date, post_time) VALUES (?, ?, NOW(), NOW())";
    $insertPostStmt = $pdo->prepare($insertPostSql);
    $insertPostStmt->execute([$userId, $postContent]);

    // Redirect after successful form submission
    header("Location: forum_display.php");
    exit(); // Ensure script execution stops after the header redirect
}
?>

<?php
session_start();
include 'connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Check if the user is still an admin
    if ($_SESSION['user_type'] !== 'user') {
        header('location: login.php');
        exit();
    }

    // Process and update profile information
    $newUsername = $_POST['new_username'];
    $newName = $_POST['new_name'];
    $userId = $_SESSION['user_id'];

    // Check if the new username is already taken
    $checkUsernameStmt = $pdo->prepare("SELECT user_id FROM users WHERE username = :username AND user_id != :user_id");
    $checkUsernameStmt->bindParam(':username', $newUsername);
    $checkUsernameStmt->bindParam(':user_id', $userId);
    $checkUsernameStmt->execute();

    if ($checkUsernameStmt->rowCount() > 0) {
        // Username is already taken, handle this situation (e.g., show an error message)
        echo "Error: The chosen username is already taken.";
    } else {
        // Update profile information in the database
        $updateStmt = $pdo->prepare("UPDATE users SET username = :username, name = :name WHERE user_id = :user_id");
        $updateStmt->bindParam(':username', $newUsername);
        $updateStmt->bindParam(':name', $newName);
        $updateStmt->bindParam(':user_id', $userId);
        $updateStmt->execute();

        // Update session variables with the new data
        $_SESSION['username'] = $newUsername;
        $_SESSION['user_name'] = $newName;

        header('location: user_home.php');
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <!-- Add your head content as needed -->
</head>

<body>

    <h1>Edit Profile</h1>
    <form method="post" action="edit_profile.php">
        <label for="new_username">New Username:</label>
        <input type="text" name="new_username" required>
        <br>
        <label for="new_name">New Name:</label>
        <input type="text" name="new_name" required>
        <br>
        <button type="submit">Save Changes</button>
    </form>

</body>

</html>

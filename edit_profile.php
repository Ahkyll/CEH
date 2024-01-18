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

    // File upload handling
    $uploadDirectory = 'assets/img'; // Directory where uploaded files will be stored
    $targetFile = $uploadDirectory . basename($_FILES['profile_picture']['name']);
    $uploadOk = 1;
    $imageFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));

    // Check if the image file is a actual image or fake image
    if (isset($_POST['submit'])) {
        $check = getimagesize($_FILES['profile_picture']['tmp_name']);
        if ($check !== false) {
            $uploadOk = 1;
        } else {
            echo "Error: File is not an image.";
            $uploadOk = 0;
        }
    }


    // Check file size (adjust as needed)
    if ($_FILES['profile_picture']['size'] > 50000000) {
        echo "Error: Sorry, your file is too large.";
        $uploadOk = 0;
    }

    // Allow certain file formats
    $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif'];
    if (!in_array($imageFileType, $allowedExtensions)) {
        echo "Error: Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
        $uploadOk = 0;
    }

    if ($uploadOk == 0) {
        echo "Error: Your file was not uploaded.";
    } else {
        if (move_uploaded_file($_FILES['profile_picture']['tmp_name'], $targetFile)) {
            // Update profile information in the database with the new file path
            $updateStmt = $pdo->prepare("UPDATE users SET username = :username, name = :name, profile_picture = :profile_picture WHERE user_id = :user_id");
            $updateStmt->bindParam(':username', $newUsername);
            $updateStmt->bindParam(':name', $newName);
            $updateStmt->bindParam(':profile_picture', $targetFile);
            $updateStmt->bindParam(':user_id', $userId);
            $updateStmt->execute();

            // Update session variables with the new data
            $_SESSION['username'] = $newUsername;
            $_SESSION['user_name'] = $newName;
            $_SESSION['profile_picture'] = $targetFile;

            header('location: user_home.php');
            exit();
        } else {
            echo "Error: There was an error uploading your file.";
        }
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
    <form method="post" action="edit_profile.php" enctype="multipart/form-data">
        <label for="new_username">New Username:</label>
        <input type="text" name="new_username" required>
        <br>
        <label for="new_name">New Name:</label>
        <input type="text" name="new_name" required>
        <br>
        <label for="profile_picture">Profile Picture:</label>
        <input type="file" name="profile_picture" accept="image/*" required>
        <br>
        <button type="submit">Save Changes</button>
    </form>

</body>

</html>

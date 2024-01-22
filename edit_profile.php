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
    $newCourse = $_POST['new_course']; // Added
    $newYear = $_POST['new_year']; // Added
    $userId = $_SESSION['user_id'];

    // File upload handling
    $uploadDirectory = 'assets/img'; // Directory where uploaded files will be stored
    $targetFile = $uploadDirectory . basename($_FILES['profile_picture']['name']);
    $uploadOk = 1;
    $imageFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));

    // Check if the image file is a real image or fake image
    if (isset($_POST['save_changes'])) { // Changed
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
            // Update profile information in the database with the new file path, course, and year
            $updateStmt = $pdo->prepare("UPDATE users SET username = :username, name = :name, course = :course, year = :year, profile_picture = :profile_picture WHERE user_id = :user_id");
            $updateStmt->bindParam(':username', $newUsername);
            $updateStmt->bindParam(':name', $newName);
            $updateStmt->bindParam(':course', $newCourse);
            $updateStmt->bindParam(':year', $newYear);
            $updateStmt->bindParam(':profile_picture', $targetFile);
            $updateStmt->bindParam(':user_id', $userId);
            $updateStmt->execute();

            // Update session variables with the new data
            $_SESSION['username'] = $newUsername;
            $_SESSION['user_name'] = $newName;
            $_SESSION['course'] = $newCourse;
            $_SESSION['year'] = $newYear;
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
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Profile</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        form {
            max-width: 400px;
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        h1 {
            text-align: center;
            color: #333;
        }

        label {
            display: block;
            margin: 10px 0;
            font-weight: bold;
        }

        input {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            box-sizing: border-box;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        button {
            background-color: #333;
            color: #fff;
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        button:hover {
            background-color: #555;
        }
    </style>
</head>

<body>

    <form method="post" action="edit_profile.php" enctype="multipart/form-data">
        <h1>Edit Profile</h1>
        <label for="new_username">New Username:</label>
        <input type="text" name="new_username" required>

        <label for="new_name">New Name:</label>
        <input type="text" name="new_name" required>

        <label for="new_course">New Course:</label>
        <input type="text" name="new_course" required>

        <label for="new_year">New Year:</label>
        <input type="text" name="new_year" required>

        <label for="profile_picture">Profile Picture:</label>
        <input type="file" name="profile_picture" accept="image/*" required>

        <button type="submit" name="save_changes">Save Changes</button> <!-- Changed -->
    </form>

</body>

</html>

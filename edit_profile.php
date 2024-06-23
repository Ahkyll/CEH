<?php
session_start();
include 'connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Process and update profile information
    $newUsername = $_POST['new_username'];
    $newName = $_POST['new_name'];
    $newSection = $_POST['new_section'];
    $newYear = $_POST['new_year'];
    $userId = $_SESSION['user_id'];

    // File upload handling
    $uploadDirectory = 'assets/img/profile';
    $uploadOk = 1;
    $targetFile = '';

    if (isset($_FILES['profile_picture']) && $_FILES['profile_picture']['error'] === 0) {
        $check = getimagesize($_FILES['profile_picture']['tmp_name']);

        if ($check !== false) {
            // File is an image, you can proceed with the upload
            $target_dir = 'assets/img/profile';
            $targetFile = $target_dir . basename($_FILES["profile_picture"]["name"]);
            move_uploaded_file($_FILES["profile_picture"]["tmp_name"], $targetFile);
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
    if (!empty($targetFile) && !in_array(strtolower(pathinfo($targetFile, PATHINFO_EXTENSION)), $allowedExtensions)) {
        echo "Error: Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
        $uploadOk = 0;
    }

    if ($uploadOk == 0) {
        echo "Error: Your file was not uploaded.";
    } else {
        // Update profile information in the database with the new file path, course, and year
        $updateStmt = $pdo->prepare("UPDATE users SET 
            username = COALESCE(NULLIF(:username, ''), username),
            name = COALESCE(NULLIF(:name, ''), name),
            section = COALESCE(NULLIF(:section, ''), section),
            year = COALESCE(NULLIF(:year, ''), year)" . (!empty($targetFile) ? ', profile_picture = :profile_picture' : '') . "
            WHERE user_id = :user_id");

        $updateStmt->bindParam(':username', $newUsername);
        $updateStmt->bindParam(':name', $newName);
        $updateStmt->bindParam(':section', $newSection);
        $updateStmt->bindParam(':year', $newYear);
        if (!empty($targetFile)) {
            $updateStmt->bindParam(':profile_picture', $targetFile);
        }
        $updateStmt->bindParam(':user_id', $userId);
        $updateStmt->execute();

        // Update session variables with the new data
        $_SESSION['username'] = $newUsername;
        $_SESSION['user_name'] = $newName;
        $_SESSION['section'] = $newSection;
        $_SESSION['year'] = $newYear;
        $_SESSION['profile_picture'] = $targetFile;

        header('location: user_home.php');
        exit();
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
            background: url("img/cpsubg.jpeg") no-repeat center center fixed;
            background-size: cover;
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
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            background: linear-gradient(308deg, rgb(2, 0, 36) 0%, rgba(9, 9, 121, 0.845) 35%, rgb(0, 213, 255) 100%);
        }

        h1 {
            text-align: center;
            color:black;
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
            background-color: #0f96fe;
            color: #fff;
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        button:hover {
            background-color:cornflowerblue;
        }
    </style>
</head>

<body>

    <form method="post" action="edit_profile.php" enctype="multipart/form-data">
        <h1>Edit Profile</h1>
        <label for="new_username">New Username:</label>
        <input type="text" name="new_username" >

        <label for="new_name">New Name:</label>
        <input type="text" name="new_name" >

        <label for="new_section">New Section:</label>
        <input type="text" name="new_section" >

        <label for="new_year">New Year:</label>
        <input type="text" name="new_year" >

        <label for="profile_picture">Profile Picture:</label>
        <input type="file" name="profile_picture" accept="image/*" >

        <button type="submit" name="save_changes">Save Changes</button> <!-- Changed -->
    </form>

</body>

</html>

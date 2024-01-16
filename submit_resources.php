<?php
// resource_submission.php
session_start();

// Prevent caching
header("Cache-Control: no-cache, must-revalidate"); // HTTP/1.1
header("Pragma: no-cache"); // HTTP/1.0
header("Expires: Sat, 26 Jul 2025 05:00:00 GMT"); // Date in the past

// Include the database connection script
include 'connect.php';

// Handle form submissions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Check if the user is logged in
    if (isset($_SESSION['user_id'])) {
        $loggedInUserId = $_SESSION['user_id'];

        try {
            // Query to get user_id from users table
            $getUserStmt = $pdo->prepare("SELECT user_id FROM users WHERE user_id = :user_id");
            $getUserStmt->bindParam(':user_id', $loggedInUserId);
            $getUserStmt->execute();

            // Fetch the user's information
            $userRow = $getUserStmt->fetch(PDO::FETCH_ASSOC);

            // Check if the user exists
            if ($userRow) {
                // Process form data
                $resourceTitle = $_POST['resourceTitle'];
                $resourceCategory = $_POST['category'];
                $resourceLink = $_POST['resourceLink'];

                // Get the user ID from the database
                $userId = $userRow['user_id'];

                // Prepare the resource insertion query
                $stmt = $pdo->prepare("INSERT INTO resources (user_id, resource_title, resource_category, resource_link) VALUES (:user_id, :title, :category, :link)");
                $stmt->bindParam(':user_id', $userId);
                $stmt->bindParam(':title', $resourceTitle);
                $stmt->bindParam(':category', $resourceCategory);
                $stmt->bindParam(':link', $resourceLink);

                // Execute the query
                $stmt->execute();

                // Redirect to resources.php on success
                header("Location: resources.php");
                exit();
            } else {
                // User not found in the database
                echo '<script>alert("Error: User not found in the database.");</script>';
            }
        } catch (PDOException $e) {
            // Handle database error
            echo '<script>alert("Error: Failed to fetch user information.");</script>';
            // You might want to log the error for debugging purposes
            error_log('Error: ' . $e->getMessage());
        }
    } else {
        // User is not logged in
        echo '<script>alert("Error: User not logged in.");</script>';
    }
}
?>







<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Resource Submission Form</title>
    <link rel="stylesheet" href="css/resources.css"> <!-- Include your existing CSS file or add styles here -->
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
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            width: 300px;
        }

        h2 {
            text-align: center;
            color: #333;
        }

        label {
            display: block;
            margin: 10px 0 5px;
            color: #555;
        }

        input, select {
            width: 100%;
            padding: 8px;
            margin-bottom: 10px;
            box-sizing: border-box;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        button {
            background-color: #4caf50;
            color: #fff;
            padding: 10px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            width: 100%;
        }

        button:hover {
            background-color: #45a049;
        }
    </style>
</head>

<body>
    <form method="post" action="" enctype="multipart/form-data">
        <h2>Submit a Resource</h2>
        <label for="resourceTitle">Resource Title:</label>
        <input type="text" id="resourceTitle" name="resourceTitle" required>

        <label for="category">Category:</label>
        <select id="category" name="category" required>
            <option value="research_papers">Research Papers</option>
            <option value="study_guides">Study Guides</option>
            <option value="templates">Templates</option>
        </select>

        <label for="resourceLink">Resource Link:</label>
        <input type="url" id="resourceLink" name="resourceLink" placeholder="https://example.com" required>

        <!-- Uncomment the following section if you want to include file uploads -->
        <!--
        <label for="resourceFile">Upload File:</label>
        <input type="file" id="resourceFile" name="resourceFile" accept=".pdf, .doc, .docx">
        -->

        <button type="submit" name="submit">Submit Resource</button>
        
    </form>

    <!-- Your existing HTML and JavaScript code here -->
</body>

</html>

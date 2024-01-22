<?php
session_start();
include 'connect.php';

// Handle resource submission form submissions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
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

                // Handle file upload
                $uploadDir = 'assets/';
                $uploadedFile = $uploadDir . basename($_FILES['resourceFile']['name']);

                if (move_uploaded_file($_FILES['resourceFile']['tmp_name'], $uploadedFile)) {
                    // Prepare the resource insertion query with file path
                    $stmt = $pdo->prepare("INSERT INTO resources (user_id, resource_title, resource_category, resource_link, resource_file) VALUES (:user_id, :title, :category, :link, :file_path)");
                    $stmt->bindParam(':user_id', $userId);
                    $stmt->bindParam(':title', $resourceTitle);
                    $stmt->bindParam(':category', $resourceCategory);
                    $stmt->bindParam(':link', $resourceLink);
                    $stmt->bindParam(':file_path', $uploadedFile);

                    // Execute the query
                    $stmt->execute();

                    // Redirect to resources.php on success
                    header("Location: resources.php");
                    exit();
                } else {
                    // File upload failed
                    echo '<script>alert("Error: Failed to upload file.");</script>';
                }
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

    // Redirect to resources.php on successful resource submission
    header("Location: resources.php");
    exit();
}

// Fetch resources with user information from the database
$query = "SELECT r.*, u.username AS uploader_name, u.username AS uploader_username, u.year, u.course
          FROM resources r
          JOIN users u ON r.user_id = u.user_id";


// Check if a search query is provided
if (isset($_GET['search'])) {
    $search = $_GET['search'];
    $query .= " WHERE r.resource_title LIKE '%$search%' OR r.resource_category LIKE '%$search%'";
}

$stmt = $pdo->query($query);

// Fetch data as an associative array
$resources = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>





<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Website</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>

<style>
    * {
        
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }

    body {
        font-family: "Arial", sans-serif;
        background-color: #f0f5f9; /* Light blue-gray background */
    }

    header {
        background-color: #333; /* Dark blue header */
        color: #ecf0f1; /* White text */
        padding: 10px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        border-bottom: 3px solid #34495e; /* Slightly darker border */
    }

    .menu-icon {
        cursor: pointer;
        font-size: 25px;
        margin: 0 10px;
        color: #ecf0f1;
    }

    /* Dropdown styles */
    .dropdown-content {
        display: none;
        position: absolute;
        background-color: #ecf0f1; /* Light gray dropdown background */
        min-width: 200px;
        box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
        z-index: 1;
        padding: 10px;
        text-align: center;
        color: #333; /* Dark text */
    }

    #close-btn {
        cursor: pointer;
        font-size: 18px;
        position: absolute;
        top: 5px;
        right: 10px;
    }

    .menu-icon.dropdown.active .dropdown-content {
        display: block;
    }

    .profile-container {
        max-width: 800px;
        margin: 20px auto;
        padding: 20px;
        background-color: #ecf0f1;
        border-radius: 8px;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        border: 2px solid #34495e; /* Slightly darker border */
    }

    .profile-image {
        width: 100px;
        height: 100px;
        border-radius: 50%;
        object-fit: cover;
    }

    .profile-name {
        margin-top: 10px;
        font-size: 30px;
        color: #333;
    }

    .username {
        font-size: 20px;
        color: #333;
    }

    nav {
        padding: 20px;
        text-align: center;
    }

    nav a {
        display: inline-block;
        margin: 0 10px;
        text-decoration: none;
        font-size: 15px;
        font-weight: bold;
    }

    nav a h1 {
        color: white;
    }

    nav h1:hover {
        color: #0f96fe;
    }

    .head h1 {
        font-size: 50px;
        margin: 15px;
        text-align: center;
    }

    #resourceForm {
        position: fixed;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        background-color: #fff;
        padding: 20px;
        border-radius: 8px;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        z-index: 999; /* Set a high z-index to make it appear above other content */
        display: none;
    }

    #resourceForm h2 {
        text-align: center;
        color: #333;
    }

    #resourceForm label {
        display: block;
        margin: 10px 0 5px;
        color: #555;
    }

    #resourceForm input,
    #resourceForm select {
        width: 100%;
        padding: 8px;
        margin-bottom: 10px;
        box-sizing: border-box;
        border: 1px solid #ccc;
        border-radius: 4px;
    }

    #resourceForm button {
        background-color: #e74c3c; /* Red color for Submit Resources button */
        color: #fff;
        padding: 10px;
        border: none;
        border-radius: 4px;
        cursor: pointer;
        width: 100%;
    }

    #resourceForm button:hover {
        background-color: #c0392b; /* Darker red color on hover */
    }

    .resources {
        margin: 20px;
        display: flex;
        flex-wrap: wrap;
        justify-content: space-between;
    }

    .resource-details {
        width: calc(30% - 20px);
        background-color: #3498db;
        padding: 20px;
        margin-bottom: 20px;
        border-radius: 8px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        transition: background-color 0.3s ease;
    }

    .resource-details h3 {
        font-size: 25px;
        color: black;
    }

    .resource-details p {
        color: #ecf0f1;
    }

    .resource-details:hover {
        background-color: #2980b9;
    }

    .resource-details:hover h3 {
        color: #ecf0f1;
    }

    .resource-details:hover p {
        color: #fff;
    }

    .resource-details p a {
        color: #ecf0f1;
        text-decoration: none;
    }

    .resource-details p a:hover {
        text-decoration: underline;
    }

    .resource-details .download-link {
        display: inline-block;
        background-color: #27ae60;
        color: #fff;
        padding: 8px 12px;
        border-radius: 5px;
        margin-top: 10px;
        text-decoration: none;
        transition: background-color 0.3s ease;
    }

    .resource-details .download-link i {
        margin-right: 5px;
    }

    .resource-details .download-link:hover {
        background-color: #219d54;
    }
    .delete-btn {
        margin-top: 20px;
        margin-left: 220px;
    background-color: #e74c3c;
    color: #fff;
    border: none;
    padding: 8px 12px;
    border-radius: 5px;
    cursor: pointer;
    transition: background-color 0.3s ease;
}

.delete-btn:hover {
    background-color: #c0392b;
}

    /* Media query for mobile responsiveness */
    @media screen and (max-width: 768px) {
        header {
            flex-direction: column;
            align-items: flex-start;
        }

        nav {
            margin-top: 10px;
        }

        .menu-icon {
            margin-top: 10px;
        }

        .resource-details {
            width: calc(100% - 20px);
        }
    }

    .btn {
        background-color: #333;
        color: #fff;
        padding: 10px 20px;
        text-decoration: none;
        border-radius: 5px;
        display: inline-block;
        margin-top: 20px;
    }

    .menu-icon {
        cursor: pointer;
    }

    .active {
        display: block;
    }

    .submit-button {
        position: fixed;
        bottom: 20px;
        right: 50px;
        background-color: #e74c3c; /* Red color for Submit Resources button */
        color: #fff;
        border: none;
        border-radius: 50%;
        padding: 15px;
        font-size: 20px;
        cursor: pointer;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }

    .submit-button:hover::before {
        content: "Submit Resources";
        position: fixed;
        right: 20px; /* Adjust the distance from the button */
        background-color: white;
        color: black;
        padding: 5px;
        border-radius: 5px;
        font-size: 20px;
        opacity: 0;
        transition: opacity 0.3s ease;
    }

    .submit-button:hover::before {
        opacity: 1;
    }

    form {
        margin-top: 30px;
        margin-bottom: 40px;
        text-align: center; /* Center the search bar */
    }

    label {
        margin-right: 10px;
    }

    #search {
        padding: 12px;
        width: 60%;
        border: 2px solid #3498db;
        border-radius: 4px;
        outline: none;
        font-size: 16px;
        margin-right: 10px;
    }

    button {
        padding: 12px 20px;
        background-color: #3498db;
        color: #fff;
        border: none;
        border-radius: 4px;
        cursor: pointer;
        font-size: 16px;
    }

    button:hover {
        background-color: #2980b9;
    }

    /* Media query for mobile responsiveness */
    @media screen and (max-width: 768px) {
        header {
            flex-direction: column;
            align-items: flex-start;
        }

        nav {
            margin-top: 10px;
        }

        .menu-icon {
            margin-top: 10px;
        }

        .resource-details {
            width: calc(100% - 20px);
        }
    }
</style>

<body>
    <header>
        <div class="menu-icon dropdown">&#9776;
            <div class="dropdown-content">
                <span id="close-btn" onclick="toggleDropdown()">&#10006;</span>
                <div class="profile-container">
    <div class="profile-header">
        <?php
        if (isset($_SESSION['profile_picture']) && !empty($_SESSION['profile_picture'])) {
            echo '<img src="' . htmlspecialchars($_SESSION['profile_picture']) . '" alt="User Profile" class="profile-image">';
        } else {
            echo '<img src="default_profile_image.png" alt="Default Profile Image" class="profile-image">';
        }
        ?>
        <div class="profile-name" id="profileName"><?= htmlspecialchars($_SESSION['user_name']) ?></div>
        <div class="username" id="profileUsername"><?= htmlspecialchars($_SESSION['username']) ?></div>

        <?php if (isset($_SESSION['year']) && isset($_SESSION['course'])) : ?>
            <div class="year-course">
                Year: <?= htmlspecialchars($_SESSION['year']) ?> | Course: <?= htmlspecialchars($_SESSION['course']) ?>
            </div>
        <?php endif; ?>
    </div>
    <br>
    <a href="logout.php" class="btn">Logout</a>
    <a href="edit_profile.php" class="btn">Edit Profile</a>
</div>

            </div>
        </div>

        <nav>
            <a href="user_home.php">
                <h1>Home</h1>
            </a>
            <a href="resources.php">
                <h1><span style="color: #0f96fe;">Resources</span></h1>
            </a>
            <a href="forum.php">
                <h1>Forum</h1>
            </a>
            <a href="about.php">
                <h1>About us</h1>
            </a>
        </nav>
    </header>
    <div class="head">
        <h1>Resources Library</h1>
    </div>

    <!-- Submit Resources Button -->
    <a href="#" class="submit-button" title="Submit Resources" onclick="toggleForm()">
        <i class="fas fa-plus"></i>
    </a>

   <!-- Resource Submission Form -->
<form id="resourceForm" method="post" action="" enctype="multipart/form-data">
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
    <input type="url" id="resourceLink" name="resourceLink" placeholder="https://example.com" >

    <!-- Add a file input for uploading resources -->
    <label for="resourceFile">Upload File:</label>
    <input type="file" id="resourceFile" name="resourceFile">

    <button type="submit" name="submit">Submit Resource</button>
</form>


    <!-- Search Bar -->
    <form method="get" action="resources.php">
        <label for="search">Search:</label>
        <input type="text" name="search" id="search" placeholder="Enter search term">
        <button type="submit">Search</button>
    </form>
    <div class="resources">
    <?php foreach ($resources as $resource) : ?>
        <div class="resource-details">
            <h3><?= htmlspecialchars($resource['resource_title']) ?></h3>
            <p>Category: <?= htmlspecialchars($resource['resource_category']) ?></p>
            <p>Link: <a href="<?= htmlspecialchars($resource['resource_link']) ?>" target="_blank"><?= htmlspecialchars($resource['resource_link']) ?></a></p>
            <p>Uploader: <?= htmlspecialchars($resource['uploader_username']) ?></p>

            <?php if (!empty($resource['resource_file'])) : ?>
                <p>
                    File: 
                    <a href="<?= htmlspecialchars($resource['resource_file']) ?>" target="_blank">
                        <i class="fas fa-download"></i> Download File
                    </a>
                </p>
            <?php endif; ?>

            <?php if ($resource['user_id'] == $_SESSION['user_id']) : ?>
                <!-- Add delete button with trash icon if the resource belongs to the logged-in user -->
                <button onclick="confirmDelete(<?= $resource['resource_id'] ?>)" class="delete-btn">
                    <i class="fas fa-trash-alt"></i> Delete Resource
                </button>
            <?php endif; ?>
        </div>
    <?php endforeach; ?>
</div>





    <script>
        document.addEventListener("DOMContentLoaded", function () {
            var menuIcon = document.querySelector(".menu-icon.dropdown");
            menuIcon.addEventListener("click", function () {
                toggleDropdown();
            });

            var closeBtn = document.getElementById("close-btn");
            closeBtn.addEventListener("click", function () {
                toggleDropdown();
            });
        });

        function toggleDropdown() {
            var menuIcon = document.querySelector(".menu-icon.dropdown");
            menuIcon.classList.toggle("active");
        }

        function toggleForm() {
            var form = document.getElementById('resourceForm');
            form.style.display = form.style.display === 'none' ? 'block' : 'none';
        }
        
        function confirmDelete(resourceId) {
    var confirmDelete = confirm("Are you sure you want to delete this resource?");
    if (confirmDelete) {
        var xhr = new XMLHttpRequest();
        xhr.onreadystatechange = function () {
            if (xhr.readyState == 4) {
                try {
                    var response = JSON.parse(xhr.responseText);

                    if (response.success) {
                        // Reload the page after successful deletion
                        window.location.reload();
                    } else {
                        // Display an error message
                        alert("Error: " + response.error);
                    }
                } catch (error) {
                    console.error("Error parsing JSON response: " + error);
                }
            }
        };

        // Send a POST request to your server-side script (delete_resource.php)
        xhr.open("POST", "delete_resources.php", true);
        xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
        xhr.send("resource_id=" + resourceId);
    }
}




    </script>

</body>

</html>

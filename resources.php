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

            // Check if the user exists
            if ($getUserStmt->rowCount() > 0) {
                // Fetch the user's information
                $userRow = $getUserStmt->fetch(PDO::FETCH_ASSOC);

                // Process form data
                $resourceTitle = $_POST['resourceTitle'];
                $resourceCategory = $_POST['category'];
                $resourceLink = $_POST['resourceLink'];
                $resourceDesc = $_POST['resourceDesc'];

                // Handle file upload
                $uploadDir = 'assets/img/resources';
                $uploadedFile = $uploadDir . basename($_FILES['resourceFile']['name']);
                // Handle picture upload
                $uploadDirPicture = 'assets/img/resources';
                $uploadedPicture = $uploadDirPicture . basename($_FILES['resourcePicture']['name']);

                if (move_uploaded_file($_FILES['resourcePicture']['tmp_name'], $uploadedPicture)) {
                    // Picture uploaded successfully
                } else {
                    // Picture upload failed
                    echo '<script>alert("Error: Failed to upload picture.");</script>';
                }

                if (move_uploaded_file($_FILES['resourceFile']['tmp_name'], $uploadedFile)) {
                    // Prepare the resource insertion query with file path
                    $stmt = $pdo->prepare("INSERT INTO resources (user_id, resource_title, resource_category, resource_link, resource_file, resource_picture, resource_desc) VALUES (:user_id, :title, :category, :link, :file_path, :picture, :desc)");

                    $stmt->bindParam(':user_id', $loggedInUserId); // Use the logged-in user's ID
                    $stmt->bindParam(':title', $resourceTitle);
                    $stmt->bindParam(':category', $resourceCategory);
                    $stmt->bindParam(':link', $resourceLink);
                    $stmt->bindParam(':file_path', $uploadedFile);
                    $stmt->bindParam(':picture', $uploadedPicture);
                    $stmt->bindParam(':desc', $resourceDesc);

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
}

// Fetch resources with user information from the database
$query = "SELECT r.*, u.username AS uploader_name, u.username AS uploader_username, u.year
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
        text-decoration: none;
    }

    body {
        font-family: "Arial", sans-serif;
        background-color: #f0f5f9;
        /* Light blue-gray background */
    }

    header {
        background-color: #333;
        /* Dark blue header */
        color: #ecf0f1;
        /* White text */
        padding: 10px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        border-bottom: 3px solid #34495e;
        /* Slightly darker border */
    }


    /* Dropdown styles */
    .dropdown-content {
        display: none;
        position: absolute;
        background-color: #ecf0f1;
        /* Light gray dropdown background */
        min-width: 200px;
        box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
        z-index: 1;
        padding: 10px;
        text-align: center;
        color: #333;
        /* Dark text */
    }


    .profile-icon.dropdown.active .dropdown-content {
        display: block;
    }

    .profile-container {
        max-width: 800px;
        margin: 20px auto;
        padding: 20px;
        background-color: #ecf0f1;
        border-radius: 8px;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        border: 2px solid #34495e;
        /* Slightly darker border */
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
        z-index: 999;
        /* Set a high z-index to make it appear above other content */
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
        background-color: #e74c3c;
        /* Red color for Submit Resources button */
        color: #fff;
        padding: 10px;
        border: none;
        border-radius: 4px;
        cursor: pointer;
        width: 100%;
    }

    #resourceForm button:hover {
        background-color: #c0392b;
        /* Darker red color on hover */
    }

    .resources {
        margin: 50px;
        display: flex;
        flex-wrap: wrap;
        justify-content: space-between;
        text-align: center;
        
    }

    .resource-details {
    position: relative;
}

    .resource-details h3 {
        font-size: 25px;
        color: black;
    }

    .resource-details p {
        color: #ecf0f1;

    }


    .resource-details p a {
        color: #ecf0f1;
        text-decoration: none;
    }



    .resource-image {
        object-fit: cover;
        
    }
    .bot-details {
        margin-top: 15px;
        text-align: center; /* Center the text in the container */
    }

    .bot-details h3 {
        font-size: 25px;
        color: black;
    }

    .bot-details p {
        color: black;
        max-width: 300px;
        max-height: 600px; /* Limit the height of the description */
        overflow: hidden;
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
    .download-btn {
        text-decoration: none;
        background-color: #e74c3c;
        color: #fff;
        border: none;
        padding: 20px;
        border-radius: 5px;
        cursor: pointer;
        margin-top: 20px;
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

   

    .active {
        display: block;
    }

    .submit-button {
        position: fixed;
        bottom: 20px;
        right: 50px;
        background-color: #e74c3c;
        color: #fff;
        border: none;
        border-radius: 50%;
        padding: 15px;
        font-size: 30px;
        cursor: pointer;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        z-index: 999;
    }



    form {
        margin-top: 30px;
        margin-bottom: 40px;
        text-align: center;
        /* Center the search bar */
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


    
    .container {
        margin-top: 40px;
    position: relative;
    width: 300px; /* Set a fixed width for the container */
    height: 300px; /* Set a fixed height for the container */
    overflow: hidden; /* Hide overflow content outside the container */
}

.overlay {
    position: absolute;
    bottom: 0;
    left: 0;
    right: 0;
    background-color: #008CBA;
    overflow: hidden;
    width: 0;
    height: 100%;
    transition: .5s ease;
}

.container:hover .overlay {
    width: 100%;
}

.text {
    white-space: nowrap;
    color: white;
    font-size: 20px;
    position: absolute;
    overflow: hidden;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    -ms-transform: translate(-50%, -50%);
}

</style>

<body>
    <header>
        <div class="profile-icon dropdown">
            <?php
if (isset($_SESSION['profile_picture']) && !empty($_SESSION['profile_picture'])) {
    echo '<img src="' . htmlspecialchars($_SESSION['profile_picture']) . '" alt="User Profile" class="profile-image" style="width: 50px; height: 50px;">';
} else {
    echo '<img src="img\default_profile_image.jpg" alt="Default Profile Image" class="profile-image" style="width: 50px; height: 50px;">';
}
?>

            <div class="dropdown-content">
                <span id="close-btn" onclick="toggleDropdown()"></span>

                <div class="profile-container">
                    <div class="profile-header">
                        <?php
        if (isset($_SESSION['profile_picture']) && !empty($_SESSION['profile_picture'])) {
            echo '<img src="' . htmlspecialchars($_SESSION['profile_picture']) . '" alt="User Profile" class="profile-image">';
        } else {
            echo '<img src="img\default_profile_image.jpg" alt="Default Profile Image" class="profile-image">';
        }
        ?>
                        <div class="profile-name" id="profileName">
                            <?= htmlspecialchars($_SESSION['user_name']) ?>
                        </div>
                        <div class="username" id="profileUsername">
                            <?= htmlspecialchars($_SESSION['username']) ?>
                        </div>

                        <?php if (isset($_SESSION['year']) && isset($_SESSION['section'])) : ?>
                        <div class="year-course">
                            Year:
                            <?= htmlspecialchars($_SESSION['year']) ?> | Section:
                            <?= htmlspecialchars($_SESSION['section']) ?>
                        </div>
                        <?php endif; ?>
                    </div>
                    <br>
                    <a href="logout.php" class="btn"><i class="fas fa-sign-out-alt"></i> Logout</a>
                    <a href="edit_profile.php" class="btn"><i class="fas fa-user-edit"></i> Edit Profile</a>
                    <a href="edit_profile.php" class="btn"><i class="fas fa-user"></i> Profile dri ka edit</a>


                </div>

            </div>
        </div>

        <nav>
            <a href="user_home.php">
                <h1>Home</h1>
            </a>

            <a href="resources.php">
                <h1><span style="color: #0f96fe;">Resourc Library</span></h1>
            </a>
            <a href="forum_display.php">
                <h1>Discussion Forum</h1>
            </a>
            <a href="about.php">
                <h1>About</h1>
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

        <label for="resourceDesc">Resource Description:</label>
        <input type="text" id="resourceDesc" name="resourceDesc" >

        <label for="category">Category:</label>
        <select id="category" name="category" required>
            <option value="research_papers">Research Papers</option>
            <option value="study_guides">Study Guides</option>
            <option value="templates">Templates</option>
        </select>
                        
        <label for="resourceLink">Resource Link:</label>
        <input type="url" id="resourceLink" name="resourceLink" placeholder="https://example.com">

        <!-- Add a file input for uploading resources -->
        <label for="resourceFile">Upload File:</label>
        <input type="file" id="resourceFile" name="resourceFile">

        <label for="resourcePicture">Upload Picture:</label>
        <input type="file" id="resourcePicture" name="resourcePicture">


        <button type="submit" name="submit">Submit Resource</button>
        <button type="button" onclick="closeForm()">Close</button>
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
            <div class="container">
                <?php
                if (!empty($resource['resource_picture'])) {
                    echo '<img src="' . htmlspecialchars($resource['resource_picture']) . '" alt="Resource Image" class="resource-image" style="width: 300px; height: 300px;">';
                } else {
                    echo '<img src="default_image.jpg" alt="Default Resource Image" class="resource-image" style="width: 200px; height: 250px;">';
                }
                ?>
                <div class="overlay">
                    <div class="text">
                        
                        <p>Category: <?= htmlspecialchars($resource['resource_category']) ?></p>
                        <p>Link: <a href="<?= htmlspecialchars($resource['resource_link']) ?>" target="_blank">
                                <?= htmlspecialchars($resource['resource_link']) ?>
                            </a>
                        </p>
                        <p>Uploader: <?= htmlspecialchars($resource['uploader_username']) ?></p>
                        <?php if ($resource['user_id'] == $_SESSION['user_id']) : ?>
                            <!-- Add delete button with trash icon if the resource belongs to the logged-in user -->
                            <button onclick="confirmDelete(<?= $resource['resource_id'] ?>)" class="delete-btn">
                                <i class="fas fa-trash-alt"></i>
                            </button>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            <div class="bot-details">
            <h3><?= htmlspecialchars($resource['resource_title']) ?></h3>
            <br>
            <p><?= htmlspecialchars($resource['resource_desc']) ?></p>
            </div>
            
            <div class="download-btn">
            <?php if (!empty($resource['resource_file'])) : ?>
                <p>
                    <a href="<?= htmlspecialchars($resource['resource_file']) ?>" target="_blank">
                        View Resource
                    </a>
                </p>
            <?php endif; ?>
        </div>
        </div>
    <?php endforeach; ?>
</div>
            </div>





<script>
    document.addEventListener("DOMContentLoaded", function () {
        var menuIcon = document.querySelector(".profile-icon.dropdown");
        menuIcon.addEventListener("click", function () {
            toggleDropdown();
        });

        var submitButton = document.querySelector(".submit-button");
        submitButton.addEventListener("click", function (event) {
            toggleForm(event);
        });
    });

    function toggleDropdown() {
        var menuIcon = document.querySelector(".profile-icon.dropdown");
        menuIcon.classList.toggle("active");
    }

    function toggleForm(event) {
        var form = document.getElementById('resourceForm');
        form.style.display = form.style.display = 'block';

        // Prevent the default behavior of the click event
        if (event) {
            event.preventDefault();
        }
    }

    function closeForm() {
        var form = document.getElementById('resourceForm');
        form.style.display = 'none';
    }

    function confirmDelete(resourceId) {
        var confirmDelete = confirm("Are you sure you want to delete this resource?");

        if (confirmDelete) {
            var xhr = new XMLHttpRequest();
            xhr.onreadystatechange = function () {
                if (xhr.readyState == 4) {
                    if (xhr.status == 200) {
                        // Reload the page after successful deletion
                        window.location.reload();
                    } else {
                        // Display an error message
                        alert("Error: Unable to delete the resource.");
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
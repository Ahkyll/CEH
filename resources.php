<?php
session_start();
include 'connect.php';

// Fetch resources from the database (replace with your actual database query)
$query = "SELECT * FROM resources";
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
    background-color: #2c3e50; /* Dark blue header */
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
    color: #ecf0f1;
    transition: color 0.3s ease;
}

nav a:hover {
    color: #3498db; /* Highlight color on hover */
}

.resources {
    margin: 20px;
}

.resource-details {
    background-color: #fff;
    padding: 20px;
    margin-bottom: 20px;
    border-radius: 8px;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}

.resource-details h3 {
    color: #333;
}

.resource-details p {
    color: #666;
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
    background-color: #3498db;
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
    color:black;
    padding: 5px;
    border-radius: 5px;
    font-size: 20px;
    opacity: 0;
    transition: opacity 0.3s ease;
}

.submit-button:hover::before {
    opacity: 1;
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
}

</style>

<body>
    <header>
        <div class="menu-icon dropdown">&#9776;
            <div class="dropdown-content">
                <span id="close-btn" onclick="toggleDropdown()">&#10006;</span>

                <div class="profile-container">
    <div class="profile-header">
        <img src="img/simpson v.png" alt="User Profile" class="profile-image">
        <div class="profile-name" id="profileName"><?= htmlspecialchars($_SESSION['user_name']) ?></div>
        <div class="username" id="profileUsername"><?= htmlspecialchars($_SESSION['username']) ?></div>
    </div>
    <br>
    <a href="logout.php" class="btn">Logout</a>
    <a href="edit_profile.php" class="btn">Edit Profile</a> <!-- Add this line -->
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
            <a href="#">
                <h1>Forum</h1>
            </a>
            <a href="about.php">
                <h1>About us</h1>
            </a>
        </nav>
    </header>

    <div class="resources">
        <h1>Resources Library</h1>
        <?php foreach ($resources as $resource) : ?>
            <div class="resource-details">
                <h3><?= htmlspecialchars($resource['resource_title']) ?></h3>
                <p>Category: <?= htmlspecialchars($resource['resource_category']) ?></p>
                <p>Link: <a href="<?= htmlspecialchars($resource['resource_link']) ?>" target="_blank"><?= htmlspecialchars($resource['resource_link']) ?></a></p>
            </div>
        <?php endforeach; ?>
    </div>

    <a href="submit_resources.php" class="submit-button" title="Submit Resources">
    <i class="fas fa-plus"></i>
</a>

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
    </script>

</body>

</html>

<?php
session_start();
include 'connect.php';

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About Us</title>
    <style>
        * {
            color: black;
            margin: 0;
            padding: 0;
            text-decoration: none;
        }

        /* Add your styles here */
        body {
            font-family: "Arial", sans-serif;
            background-color: rgb(26, 25, 25);
        }

        header {
            background-color: #333;
            color: #fff;
            padding: 10px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            border: 3px solid black;
        }

        .menu-icon {
            cursor: pointer;
            font-size: 25px;
            margin: 0 10px;
            color: white;
        }

        /* Dropdown styles */
        .dropdown-content {
            display: none;
            position: absolute;
            background-color: #f9f9f9;
            min-width: 200px;
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
            z-index: 1;
            padding: 10px;
            text-align: center;
        }

        .dropdown-content img {
            max-width: 100px;
            max-height: 100px;
            margin-bottom: 10px;
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
            background-color: #ebe2e2;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            border: 2px solid black;
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
            color: black;
        }

        .username {
            font-size: 20px;
        }

        button {
            background-color: #0f96fe;
            font-size: 20px;
            padding: 10px;
            width: 150px;
            height: 50px;
            margin: 30px;
            border: #ccc;
            border: 3px solid black;
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

        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }

        header {
            background-color: #333;
            color: #fff;
            padding: 10px;
            text-align: center;
        }

        section {
            max-width: 800px;
            margin: 20px auto;
            padding: 20px;
            background-color: #fff;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        form {
            max-width: 600px;
            margin: 20px auto;
            padding: 20px;
            background-color: #fff;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        label {
            display: block;
            margin-bottom: 8px;
        }

        input,
        textarea {
            width: 100%;
            padding: 10px;
            margin-bottom: 16px;
            box-sizing: border-box;
        }

        button {
            background-color: #333;
            color: #fff;
            padding: 10px 20px;
            border: none;
            cursor: pointer;
        }

        button:hover {
            background-color: #555;
        }

        footer {
            background-color: #333;
            color: #fff;
            text-align: center;
            padding: 10px;
            position: fixed;
            bottom: 0;
            width: 100%;
        }
    </style>
</head>

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
                <h1>Resources</h1>
            </a>
            <a href="#">
                <h1>Forum</h1>
            </a>
            <a href="about.php">
                <h1><span style="color: #0f96fe;">About Us</span></h1>
            </a>
        </nav>
    </header>

    <section>
        <h2>Our Story</h2>
        <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed euismod neque nec diam feugiat, ac gravida nisl
            ultricies.</p>
        <p>Nulla facilisi. Duis sed aliquam justo. Etiam eget velit nec eros ullamcorper sagittis. In hac habitasse
            platea dictumst.</p>
    </section>

    <form method="post" action="contact_process.php">
    <!-- Your form fields remain unchanged -->
    <h2>Contact Us</h2>
    <label for="name">Name:</label>
    <input type="text" id="name" name="name" required>

    <label for="email">Email:</label>
    <input type="email" id="email" name="email" required>

    <label for="message">Message:</label>
    <textarea id="message" name="message" rows="4" required></textarea>

    <button type="submit">Submit</button>
</form>

    <footer>
        &copy; 2024 Your Company Name. All rights reserved.
    </footer>

</body>
<script src="js/home.js"></script>

</html>
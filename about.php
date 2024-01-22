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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <style>
        /* Reset styles */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        /* Global styles */
        body {
            font-family: "Arial", sans-serif;
            background-color: #f4f4f4;
            margin: 0;
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

        h2 {
            color: #333;
        }

        p {
            margin-bottom: 15px;
            line-height: 1.6;
        }

        /* Contact Form Styles */
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
            font-weight: bold;
        }

        input,
        textarea {
            width: 100%;
            padding: 10px;
            margin-bottom: 16px;
            box-sizing: border-box;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        button {
            background-color: #333;
            color: #fff;
            padding: 10px 20px;
            border: none;
            cursor: pointer;
            border-radius: 4px;
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

        /* Header styles */
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

        button.btn {
            background-color: #0f96fe;
            font-size: 20px;
            padding: 10px;
            width: 150px;
            height: 50px;
            margin: 30px;
            border: #ccc;
            border: 3px solid black;
            text-decoration: none;
            color: white;
            display: inline-block;
            text-align: center;
        }

        /* Navigation styles */
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
            color: white;
        }

        nav a h1:hover {
            color: #0f96fe;
        }
        /* CTA Section Styles */
        .cta-section {
            background-color: #0f96fe;
            color: #fff;
            text-align: center;
            padding: 50px 0;
        }

        .cta-heading {
            font-size: 28px;
            margin-bottom: 20px;
        }

        .cta-button {
            background-color: #fff;
            color: #0f96fe;
            padding: 15px 30px;
            font-size: 18px;
            text-decoration: none;
            border-radius: 5px;
            transition: background-color 0.3s;
            cursor: pointer;
        }

        .cta-button:hover {
            background-color: #ddd;
        }

        /* Form Section Styles */
        .form-container {
            display: none;
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background-color: #fff;
            padding: 20px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            z-index: 
            2;
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
    </style>
</head>

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
                <h1>Resources</h1>
            </a>
            <a href="forum.php">
                <h1>Forum</h1>
            </a>
            <a href="about.php">
                <h1><span style="color: #0f96fe;">About Us</span></h1>
            </a>
        </nav>
    </header>

    <section>
        <h2>About Collaborate Ed Hub</h2>
        <p>Welcome to Collaborate Ed Hub, your one-stop destination for collaborative learning and resource sharing. We
            believe in the power of education and collaboration to shape a better future.</p>
        <p>Collaborate Ed Hub provides a platform for educators, students, and enthusiasts to share knowledge,
            resources, and ideas. Whether you're a teacher looking for teaching materials or a student seeking
            supplementary resources, Collaborate Ed Hub is here to support your educational journey.</p>
    </section>

    <section class="cta-section">
        <h2 class="cta-heading">Ready to Collaborate for a Better Education?</h2>
        <a href="javascript:void(0);" onclick="toggleForm()" class="cta-button">Contact Us</a>
    </section>

    <div class="form-container" id="contactForm">
        <form method="post" action="contact_process.php">
            <h2>Contact Us</h2>
            <label for="name">Name:</label>
            <input type="text" id="name" name="name" required>

            <label for="email">Email:</label>
            <input type="email" id="email" name="email" required>

            <label for="message">Message:</label>
            <textarea id="message" name="message" rows="4" required></textarea>

            <button type="submit">Submit</button>
            <a href="javascript:void(0);" onclick="toggleForm()">Close</a>
        </form>
    </div>



    
    <footer>
        &copy; 2024 Collaborate Ed Hub. All rights reserved.
    </footer>

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
        var formContainer = document.getElementById('contactForm');
        formContainer.style.display = (formContainer.style.display === 'none' || formContainer.style.display === '') ? 'block' : 'none';
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

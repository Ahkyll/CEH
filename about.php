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
        .form-container {
    display: none;
    position: fixed;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    background-color: #fff;
    padding: 20px;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    z-index: 2;
}

.form-container h2 {
    font-size: 24px;
    margin-bottom: 20px;
    color: #333;
}

.form-container label {
    display: block;
    margin-bottom: 8px;
    font-weight: bold;
}

.form-container input,
.form-container textarea {
    width: 100%;
    padding: 10px;
    margin-bottom: 16px;
    box-sizing: border-box;
    border: 1px solid #ccc;
    border-radius: 4px;
}

.form-container button {
    background-color: #333;
    color: #fff;
    padding: 10px 20px;
    border: none;
    cursor: pointer;
    border-radius: 4px;
}

.form-container button:hover {
    background-color: #555;
}

.form-container a {
    color: #0f96fe;
    cursor: pointer;
    text-decoration: none;
}

.form-container a:hover {
    text-decoration: underline;
}
        .btn {
        background-color: #0f96fe;
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


                </div>

            </div>
        </div>

        <nav>
            <a href="user_home.php">
                <h1>Home</h1>
            </a>

            <a href="resources.php">
            <h1>Resource Library</h1>
                
            </a>
            <a href="forum_display.php">
                <h1>Discussion Forum</h1>
            </a>
            <a href="about.php">
            <h1><span style="color: #0f96fe;">About </span></h1>
            </a>
        </nav>
    </header>

    <section>
        <h2>About Collaborate Ed Hub</h2>
        <p>Welcome to Collaborate Ed Hub, your one-stop destination for collaborative learning and resource sharing. We
            believe in the power of education and collaboration to shape a better future.</p>
        <p>Collaborate Ed Hub provides a platform for educators, students, and enthusiasts to share knowledge,
            resources, and ideas. Sudent seeking supplementary resources, Collaborate Ed Hub is here to support your educational journey.</p>
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
function toggleForm() {
    var formContainer = document.querySelector(".form-container");
    formContainer.style.display = (formContainer.style.display === 'none' || formContainer.style.display === '') ? 'block' : 'none';
}



    </script>
</body>

</html>

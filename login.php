<?php
include 'connect.php';
session_start();

if (isset($_POST['submit'])) {
    $email = $_POST['email'];
    $password = $_POST['password'];
    $userType = $_POST['user_type'];

    $select = "SELECT * FROM users WHERE email = :email AND user_type = :user_type";
    $stmt = $pdo->prepare($select);
    $stmt->bindParam(':email', $email, PDO::PARAM_STR);
    $stmt->bindParam(':user_type', $userType, PDO::PARAM_STR);
    $stmt->execute();

    if ($stmt->rowCount() > 0) {
        $row = $stmt->fetch();

        // Verify the entered password against the hashed password from the database
        if (password_verify($password, $row['password'])) {
            $_SESSION['user_type'] = $userType;

            if ($userType == 'admin' || $userType == 'user') {
                // Fetch additional user details
                $userId = $row['user_id'];
                $username = $row['username'];
                $user_name = $row['name'];

                // Set the user details in the session
                $_SESSION['user_id'] = $userId;
                $_SESSION['username'] = $username;
                $_SESSION['profile_picture'] = $row['profile_picture']; // Corrected this line
                $_SESSION['user_name'] = $user_name;
                $_SESSION['event_image'] = $eventImage;
                $_SESSION['faculty_image'] = $facultyImage;

                if ($userType == 'admin') {
                    header('location: admin_page.php');
                    session_regenerate_id(true); // Regenerate session ID
                    exit();
                } elseif ($userType == 'user') {
                    header('location: user_home.php');
                    session_regenerate_id(true); // Regenerate session ID
                    exit();
                }
            }
        } else {
            $error[] = 'Incorrect password!';
        }
    } else {
        $error[] = 'Incorrect email or user type!';
    }
}

// Display errors
if (!empty($error)) {
    foreach ($error as $err) {
        echo '<p>' . $err . '</p>';
    }
}
?>





<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="css/register.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">

</head>

<body style="height: 100%;
  width: 100%;
  min-height: 100vh;
  background-size: cover;
  background-repeat: no-repeat;
  background-image: url(img/cpsubg.jpeg);">
   <div class="title">
        <div class="logo"> <a href="index.html"><img src="img/collaborate_logo.png" alt="" width="200px"
                    height="200px"></a></div>
        <div class="text">
            <h1>Welcome to <br> Collaborate Ed Hub</h1>
            <h6>A CENTRAL PHILIPPINE STATE UNIVERSITY STUDENT COLLABORATION SPACE</h6>
        </div>
    </div>

    <div class="box">
        <div class="signin-text">
            <h1>Sign in</h1>
        </div>

        <?php
                if (!empty($error)) {
                    foreach ($error as $error_message) {
                        echo '<span class="error-msg">' . $error_message . '</span>';
                    }
                }
                ?>
        <div class="container">
            <form action="" method="POST">
            <select name="user_type" class="user_type">
                        <option value="user">user</option>
                        <option value="admin">admin</option>
                    </select>

                
                <input type="text" id="email" placeholder="Email" name="email" required> <br>
                <div class="password-container">
                    <label for="password" class="password-label">
                        <input type="password" id="password" name="password" required placeholder="Password">
                        <i class="fas fa-eye-slash" id="togglePassword" onclick="togglePasswordVisibility()"></i>
                    </label>
                   
                </div>
                <a href="#" class="fpass">
                    Forgot Password?
                </a>
                <br>
                <input type="submit" name="submit" value="login now" class="form-btn">
            </form>

            <div class="create-account">
                Don't have an account? <a href="signup.php">Create Account</a>
            </div>
        </div>
    </div>

    <script>
        function togglePasswordVisibility() {
            var passwordInput = document.getElementById("password");
            var toggleIcon = document.getElementById("togglePassword");

            if (passwordInput.type === "password") {
                passwordInput.type = "text";
                toggleIcon.classList.remove("fa-eye-slash");
                toggleIcon.classList.add("fa-eye");
            } else {
                passwordInput.type = "password";
                toggleIcon.classList.remove("fa-eye");
                toggleIcon.classList.add("fa-eye-slash");
            }
        }
    </script>
</body>

</html>

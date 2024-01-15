<?php
include 'server/connect.php';

if (isset($_POST['submit'])) {
    $email = $_POST['email'];
    $pass = md5($_POST['password']);
    $cpass = md5($_POST['cpassword']);
    $user_type = $_POST['user_type'];

    // Using prepared statements to prevent SQL injection
    $select = "SELECT * FROM signup WHERE email = :email";
    $stmt = $pdo->prepare($select);
    $stmt->bindParam(':email', $email);
    $stmt->execute();

    if ($stmt->rowCount() > 0) {
        $error[] = 'User already exists!';
    } else {
        if ($pass != $cpass) {
            $error[] = 'Passwords do not match!';
        } else {
            // Using prepared statements to prevent SQL injection
            $insert = "INSERT INTO signup (email, password, user_type) VALUES (:email, :password, :user_type)";
            $stmt = $pdo->prepare($insert);
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':password', $pass);
            $stmt->bindParam(':user_type', $user_type);
            $stmt->execute();

            header('location: login.php');
            exit();
        }
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
        <div class="logo"><a href="index.html"> <img src="img/collaborate_logo.png" alt="" width="200px" height="200px"></a></div>
        <div class="text">
            <h1>Welcome to <br> Collaborate Ed Hub</h1>
            <h6>A CENTRAL PHILIPPINE STATE UNIVERSITY STUDENT COLLABORATION SPACE</h6>
        </div>
    </div>

    <div class="box">
        <div class="signin-text">
            <h1>Sign up</h1>
        </div>
        <?php
                if (isset($error)) {
                    foreach ($error as $error) {
                        echo '<span class="error-msg">' . $error . '</span>';
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
                        <i class="fas fa-eye-slash toggle-password" onclick="togglePasswordVisibility('password')"></i>
                    </label>
                </div>

                <div class="password-container">
                    <label for="cpassword" class="password-label">
                        <input type="password" id="cpassword" name="cpassword" required placeholder="Confirm your password">
                        <i class="fas fa-eye-slash toggle-password" onclick="togglePasswordVisibility('cpassword')"></i>
                    </label>
                </div>
                
                <input type="submit" name="submit" value="Register Now" class="form-btn">
            </form>

            <div class="create-account">
                Already have an account? <a href="login.php">Sign in</a>
            </div>
        </div>
    </div>

</body>
<script>
        function togglePasswordVisibility(fieldId) {
            var passwordInput = document.getElementById(fieldId);
            var toggleIcon = passwordInput.nextElementSibling;

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

</html>

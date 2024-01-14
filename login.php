<?php
session_start();
include 'server/connect.php';

if (isset($_POST['submit'])) {
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = md5($_POST['password']); // This should be improved to use bcrypt or another secure hashing algorithm
    $userType = mysqli_real_escape_string($conn, $_POST['user_type']); // Get the selected user type from the form

    $select = "SELECT * FROM signup WHERE email = '$email' AND user_type = '$userType'";
    $result = mysqli_query($conn, $select);

    if (mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_array($result);

        if ($row['password'] == $password) {
            if ($row['user_type'] == 'admin') {
                $_SESSION['admin_name'] = $row['name'];
                header('location: admin_page.php');
                exit();
            } elseif ($row['user_type'] == 'user') {
                $_SESSION['user_name'] = $row['name'];
                header('location: user_home.php');
                exit();
            }
        } else {
            $error[] = 'Incorrect password!';
        }
    } else {
        $error[] = 'Incorrect email or user type!';
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

<body>
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

        <div class="container">
            <form action="" method="POST">

                <?php
                if (!empty($error)) {
                    foreach ($error as $error_message) {
                        echo '<span class="error-msg">' . $error_message . '</span>';
                    }
                }
                ?>
                <input type="text" id="email" placeholder="Email" name="email" required> <br>
                <div class="password-container">
                    <label for="password" class="password-label">
                        <input type="password" id="password" name="password" required placeholder="Password">
                        <i class="fas fa-eye-slash" id="togglePassword" onclick="togglePasswordVisibility()"></i>
                    </label>
                    <select name="user_type">
                        <option value="user">user</option>
                        <option value="admin">admin</option>
                    </select>
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

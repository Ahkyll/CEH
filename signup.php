<?php
include 'connect.php';

$errors = array();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
    $password = $_POST['password'];
    $cpassword = $_POST['cpassword'];
    $username = $_POST['username'];

    if (!$email) {
        $errors[] = 'Invalid email address!';
    } else {
        $select = "SELECT * FROM users WHERE email = :email";
        $stmt = $pdo->prepare($select);
        $stmt->bindParam(':email', $email, PDO::PARAM_STR);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            $errors[] = 'Email already exists!';
        } else {
            if ($password != $cpassword) {
                $errors[] = 'Passwords do not match!';
            } else {
                $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

                $insert = "INSERT INTO users (email, password, username) VALUES (:email, :password, :username)";
                $stmt = $pdo->prepare($insert);
                $stmt->bindParam(':email', $email, PDO::PARAM_STR);
                $stmt->bindParam(':password', $hashedPassword, PDO::PARAM_STR);
                $stmt->bindParam(':username', $username, PDO::PARAM_STR);
                $stmt->execute();

                if ($stmt->rowCount() > 0) {
                    session_start();
                    $userId = $pdo->lastInsertId();
                    $_SESSION['user_id'] = $userId;
                    $_SESSION['user_email'] = $email;

                    header('location: login.php');
                    exit();
                } else {
                    $errors[] = 'Registration failed. Please try again.';
                }
            }
        }
    }
}

?>





<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registration Page</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            text-decoration: none;
            font-family: "Arial", sans-serif;
        }

        body {
            height: 100%;
            width: 100%;
            min-height: 100vh;
            background-size: cover;
            background-repeat: no-repeat;
            background-image: url("img/cpsubg.jpeg");
            display: flex;
            justify-content: center;
            align-items: center;
            margin: 0;
        }

        .box {
            position: relative;
            background: linear-gradient(308deg, rgb(2, 0, 36) 0%, rgba(9, 9, 121, 0.845) 35%, rgb(0, 213, 255) 100%);
            border-radius: 15px;
            padding: 20px;
            text-align: center;
            color: white;
        }

        .signin-text h1 {
            margin: 20px 0;
            font-size: 2em;
        }

        .user_type {
            background-color: #0f96fe;
            font-size: 15px;
            width: 130px;
            height: 40px;
            margin: 20px;
            text-align: center;
        }

        .error-msg {
            color: #d9534f;
            background-color: #f2dede;
            border: 1px solid #d9534f;
            padding: 10px;
            margin-bottom: 10px;
            display: block;
            border-radius: 5px;
        }

        .password-container {
            position: relative;
            margin-bottom: 20px;
        }

        .password-label {
            position: relative;
        }

        .password-label i {
            position: absolute;
            right: 20px;
            top: 50%;
            transform: translateY(-50%);
            cursor: pointer;
            color: black;
        }

        #togglePassword {
            position: absolute;
            right: 20px;
            top: 50%;
            transform: translateY(-50%);
            cursor: pointer;
        }

        input[type="text"],
        input[type="password"] {
            font-size: 15px;
            width: 70%;
            height: 40px;
            padding: 10px;
            margin-bottom: 10px;
            border-radius: 15px;
            box-sizing: border-box;
        }

        .fpass {
            color: white;
            font-size: 15px;
            position: absolute;
            left: 65%;
        }

        .form-btn {
            background-color: #0f96fe;
            font-size: 20px;
            padding: 10px;
            width: 150px;
            height: 40px;
            margin: 20px;
            border: none;
            cursor: pointer;
        }

        .create-account {
            margin: 20px;
            color: white;
            font-size: 20px;
        }

        .create-account a {
            color: white;
            font-size: 20px;
        }
    </style>
</head>

<body>
    <div class="box">
        <div class="signin-text">
            <h1>Sign up</h1>
        </div>

        <div class="container">
            <form action="" method="POST">
                <?php
                if (!empty($errors)) {
                    foreach ($errors as $error) {
                        echo '<span class="error-msg">' . $error . '</span>';
                    }
                }
                ?>

                <input type="text" id="email" placeholder="Email" name="email" required> <br>
                <input type="text" id="username" placeholder="Username" name="username" required> <br>

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
</body>

</html>

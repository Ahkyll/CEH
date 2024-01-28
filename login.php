<?php
include 'connect.php';
session_start();

if (isset($_POST['submit'])) {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $select = "SELECT * FROM users WHERE email = :email";
    $stmt = $pdo->prepare($select);
    $stmt->bindParam(':email', $email, PDO::PARAM_STR);
    $stmt->execute();

    if ($stmt->rowCount() > 0) {
        $row = $stmt->fetch();

        // Verify the entered password against the hashed password from the database
        if (password_verify($password, $row['password'])) {

            // Set the common user details in the session
            $_SESSION['user_id'] = $row['user_id'];
            $_SESSION['username'] = $row['username'];
            $_SESSION['profile_picture'] = $row['profile_picture'];
            $_SESSION['user_name'] = $row['name'];
            $_SESSION['event_image'] = $row['event_image']; // assuming these fields exist in your users table
            $_SESSION['faculty_image'] = $row['faculty_image'];
            

            // Check if the user is an admin based on a specific field in the database
            if ($row['is_admin'] == 1) {
                header('location: admin_page.php');
            } else {
                header('location: user_home.php');
            }

            session_regenerate_id(true); // Regenerate session ID
            exit();
        } else {
            $error[] = 'Incorrect password!';
        }
    } else {
        $error[] = 'Incorrect email!';
    }
    // Log errors here
}
?>








<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
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
            padding: 70px;
            text-align: center;
            color: white;
        }

        .signin-text h1 {
            margin: 0px 0px 50px 0px;
            font-size: 3rem;
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
            width: 100%;
            height: 50px;
            padding: 10px;
            margin-bottom: 20px;
            border-radius: 15px;
            box-sizing: border-box;
        }

        .fpass {
            color: white;
            font-size: 17px;
            position: absolute;
            left: 60%;
        }

        .form-btn {
            background-color: #0f96fe;
            font-size: 20px;
            width: 150px;
            height: 50px;
            margin: 40px 0px 30px 0px;
            border: none;
            cursor: pointer;
            color: white;
        }
        .form-btn:hover {
            background-color: #0F4C75;
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
        .create-account span{
            color:#0f96fe;
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
</style>

</head>


<body>
  
  

    <div class="box">
        <div class="signin-text">
            <h1>Sign in</h1>
        </div>

      
        <div class="container">
            <form action="" method="POST">
            
                    <?php
             if (!empty($error)) {
            echo '<div class="error-container">';
            foreach ($error as $error_message) {
        echo '<span class="error-msg">' . $error_message . '</span>';
    }
    echo '</div>';
}
?>
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


                <input type="submit" name="submit" value="Login now" class="form-btn">
            </form>



            <div class="create-account">
                Don't have an account? <a href="signup.php"><span>Create Account</span></a>
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

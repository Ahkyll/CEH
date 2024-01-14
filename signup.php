<?php
include 'server/connect.php';

if (isset($_POST['submit'])) {
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $pass = md5($_POST['password']);
    $cpass = md5($_POST['cpassword']);
    $user_type = $_POST['user_type'];

    $select = "SELECT * FROM signup WHERE email = '$email'";
    $result = mysqli_query($conn, $select);

    if (mysqli_num_rows($result) > 0) {
        $error[] = 'User already exists!';
    } else {
        if ($pass != $cpass) {
            $error[] = 'Passwords do not match!';
        } else {
            $insert = "INSERT INTO signup (email, password, user_type) VALUES ('$email','$pass','$user_type')";
            mysqli_query($conn, $insert);
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
</head>

<body>
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

        <div class="container">
            <form action="" method="POST">
                <?php
                if (isset($error)) {
                    foreach ($error as $error) {
                        echo '<span class="error-msg">' . $error . '</span>';
                    }
                }
                ?>
                <input type="text" id="email" placeholder="Email" name="email" required> <br>
                <input type="password" id="password" placeholder="Password" name="password" required><br>
                <input type="password" name="cpassword" required placeholder="Confirm your password">
                <select name="user_type">
                    <option value="user">user</option>
                    <option value="admin">admin</option>
                </select>
                <input type="submit" name="submit" value="Register Now" class="form-btn">
            </form>

            <div class="create-account">
                Already have an account? <a href="login.php">Sign in</a>
            </div>
        </div>
    </div>

</body>

</html>

<?php 
include 'connect.php';
session_start();

$email = $_SESSION['email']; // Retrieve email before destroying the session

session_destroy();

echo '<script type="text/javascript">window.alert("'.$email.' has been Logout")</script>';
echo '<script type="text/javascript">window.location="index.html"</script>';
?>

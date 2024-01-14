<?php
session_start();
include 'server/connect.php';

// Handle form submissions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Check if the user is still an admin
    if ($_SESSION['user_type'] !== 'admin') {
        header('location:login.php');
        exit();
    }

    // Process form data
    $eventName = $_POST['event_name'];
    $eventDate = $_POST['event_date'];
    $eventDetails = $_POST['event_details'];
    $courseCategory = $_POST['course_category']; // New field for course category

    // Perform database update based on the submitted data
    $stmt = $pdo->prepare("INSERT INTO events (event_name, event_date, event_details, course_category) VALUES (:name, :date, :details, :category)");
    $stmt->bindParam(':name', $eventName);
    $stmt->bindParam(':date', $eventDate);
    $stmt->bindParam(':details', $eventDetails);
    $stmt->bindParam(':category', $courseCategory);
    $stmt->execute();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel</title>
</head>

<body>
<form method="post" action="" enctype="multipart/form-data">
        <label for="event_name">Event Name:</label>
        <input type="text" name="event_name" required>

        <label for="event_date">Event Date:</label>
        <input type="date" name="event_date" required>

        <label for="event_details">Event Details:</label>
        <textarea name="event_details" required></textarea>

        <label for="course_category">Course Category:</label>
        <select name="course_category">
        <option value="GENERAL">GENERAL</option>
            <option value="BSIT">BSIT</option>
            <option value="BEED">BEED</option>
            <option value="BSCRIM">BSCRIM</option>
            <option value="BSHM">BSHM</option>
            <option value="BSAB">BSAB</option>
        </select>

        <!-- Other form elements -->

        <button type="submit" name="submit">Submit</button>
    </form>

    <a href="logout.php">Logout</a>

</body>

</html>

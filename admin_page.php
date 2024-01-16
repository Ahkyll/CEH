<?php
session_start();
include 'connect.php';

// Check if the user is an admin
if (!isset($_SESSION['user_type']) || $_SESSION['user_type'] !== 'admin') {
    header('location: login.php');
    exit();
}

$notification = ''; // Initialize notification variable

// Handle event form submissions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Process event form data
    if (isset($_POST['event_submit'])) {
        $eventName = $_POST['event_name'];
        $eventDate = $_POST['event_date'];
        $eventDetails = $_POST['event_details'];
        $courseCategory = $_POST['course_category'];

        // Get the user_id from the session
        $userId = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;

        // Check if user_id is set before inserting
        if ($userId !== null) {
            // Perform database update based on the submitted data
            $stmt = $pdo->prepare("INSERT INTO events (event_name, event_date, event_details, course_category, user_id) VALUES (:name, :date, :details, :category, :user_id)");
            $stmt->bindParam(':name', $eventName);
            $stmt->bindParam(':date', $eventDate);
            $stmt->bindParam(':details', $eventDetails);
            $stmt->bindParam(':category', $courseCategory);
            $stmt->bindParam(':user_id', $userId);

            if ($stmt->execute()) {
                $notification = 'Event uploaded successfully!';
            } else {
                $notification = 'Error uploading event. Please try again.';
            }
        } else {
            // Handle the case where user_id is not set (e.g., invalid session state)
            $notification = 'Error: User ID is not set.';
        }
    } elseif (isset($_POST['faculty_submit'])) {
        // Process faculty form data
        $facultyName = $_POST['faculty_name'];
        $facultyPosition = $_POST['faculty_position'];
        $facultyDepartment = $_POST['faculty_department'];

        // Get the user_id from the session
        $userId = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;

        // Check if user_id is set before inserting
        if ($userId !== null) {
            // Perform database update based on the submitted data
            $stmt = $pdo->prepare("INSERT INTO faculty (faculty_name, faculty_position, faculty_department, user_id) VALUES (:name, :position, :department, :user_id)");
            $stmt->bindParam(':name', $facultyName);
            $stmt->bindParam(':position', $facultyPosition);
            $stmt->bindParam(':department', $facultyDepartment);
            $stmt->bindParam(':user_id', $userId);

            if ($stmt->execute()) {
                $notification = 'Faculty member uploaded successfully!';
            } else {
                $notification = 'Error uploading faculty member. Please try again.';
            }
        } else {
            // Handle the case where user_id is not set (e.g., invalid session state)
            $notification = 'Error: User ID is not set.';
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel</title>
</head>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            height: 100vh;
        }

        h2 {
            color: #333;
        }

        form {
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
            max-width: 400px;
            width: 100%;
        }

        label {
            display: block;
            margin-bottom: 8px;
            color: #555;
        }

        input,
        textarea,
        select {
            width: 100%;
            padding: 8px;
            margin-bottom: 12px;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
        }

        button {
            background-color: #007bff;
            color: #fff;
            padding: 10px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        button:hover {
            background-color: #0056b3;
        }

        a {
            color: #007bff;
            text-decoration: none;
            margin-top: 10px;
            display: inline-block;
        }

        a:hover {
            text-decoration: underline;
        }

        .notification {
            color: #155724;
            background-color: #d4edda;
            border: 1px solid #c3e6cb;
            border-radius: 4px;
            padding: 10px;
            margin-top: 10px;
        }

        .error-notification {
            color: #721c24;
            background-color: #f8d7da;
            border: 1px solid #f5c6cb;
            border-radius: 4px;
            padding: 10px;
            margin-top: 10px;
        }

        /* Style for side-by-side forms */
        .form-container {
            display: flex;
            justify-content: space-between;
            gap: 20px;
            max-width: 800px;
            width: 100%;
        }
    </style>
<body>

<h2>Welcome, <?php echo $_SESSION['admin_name']; ?>!</h2>

    <div class="form-container">
        <!-- Event Form -->
        <form method="post" action="" enctype="multipart/form-data">
            <!-- Event form elements -->
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

            <button type="submit" name="submit">Submit Event</button>
        </form>

        <!-- Faculty Form -->
        <form method="post" action="" enctype="multipart/form-data">
            <!-- Faculty form elements -->
            <label for="faculty_name">Faculty Name:</label>
            <input type="text" name="faculty_name" required>

            <label for="faculty_position">Faculty Position:</label>
            <input type="text" name="faculty_position" required>

            <label for="faculty_department">Faculty Department:</label>
            <select name="faculty_department">
                <option value="GENERAL">GENERAL</option>
                <option value="BSIT">BSIT</option>
                <option value="BEED">BEED</option>
                <option value="BSCRIM">BSCRIM</option>
                <option value="BSHM">BSHM</option>
                <option value="BSAB">BSAB</option>
            </select>

            <button type="submit" name="faculty_submit">Submit Faculty</button>
        </form>
    </div>

    <a href="logout.php">Logout</a>

    <!-- Notification -->
    <?php
    if (!empty($notification)) {
        echo '<div class="notification">' . htmlspecialchars($notification) . '</div>';
    }
    ?>

</body>

</html>

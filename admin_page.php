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
            // File upload handling
            $uploadDirectory = 'assets/img'; // Specify the directory for event files
            $targetFile = $uploadDirectory . basename($_FILES['event_image']['name']);
            $uploadOk = 1;
            $imageFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));

            // Check if the image file is a actual image or fake image
            $check = getimagesize($_FILES['event_image']['tmp_name']);
            if ($check !== false) {
                $uploadOk = 1;
            } else {
                $notification = "Error: File is not an image.";
                $uploadOk = 0;
            }

            // Check file size (adjust as needed)
            if ($_FILES['event_image']['size'] > 50000000) {
                $notification = "Error: Sorry, your file is too large.";
                $uploadOk = 0;
            }

            // Allow certain file formats
            $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif'];
            if (!in_array($imageFileType, $allowedExtensions)) {
                $notification = "Error: Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
                $uploadOk = 0;
            }

            if ($uploadOk == 0) {
                $notification = "Error: Your file was not uploaded.";
            } else {
                if (move_uploaded_file($_FILES['event_image']['tmp_name'], $targetFile)) {
                    // Perform database update based on the submitted data
                    $stmt = $pdo->prepare("INSERT INTO events (event_name, event_date, event_details, course_category, user_id, event_image) VALUES (:name, :date, :details, :category, :user_id, :event_image)");
                    $stmt->bindParam(':name', $eventName);
                    $stmt->bindParam(':date', $eventDate);
                    $stmt->bindParam(':details', $eventDetails);
                    $stmt->bindParam(':category', $courseCategory);
                    $stmt->bindParam(':user_id', $userId);
                    $stmt->bindParam(':event_image', $targetFile);

                    if ($stmt->execute()) {
                        $notification = 'Event uploaded successfully!';
                    } else {
                        $notification = 'Error uploading event. Please try again.';
                    }
                } else {
                    $notification = "Error: There was an error uploading your file.";
                }
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
            // File upload handling
            $uploadDirectory = 'assets/img'; // Specify the directory for faculty files
            $targetFile = $uploadDirectory . basename($_FILES['faculty_image']['name']);
            $uploadOk = 1;
            $imageFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));

            // Check if the image file is a actual image or fake image
            $check = getimagesize($_FILES['faculty_image']['tmp_name']);
            if ($check !== false) {
                $uploadOk = 1;
            } else {
                $notification = "Error: File is not an image.";
                $uploadOk = 0;
            }

            // Check file size (adjust as needed)
            if ($_FILES['faculty_image']['size'] > 50000000) {
                $notification = "Error: Sorry, your file is too large.";
                $uploadOk = 0;
            }

            // Allow certain file formats
            $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif'];
            if (!in_array($imageFileType, $allowedExtensions)) {
                $notification = "Error: Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
                $uploadOk = 0;
            }

            if ($uploadOk == 0) {
                $notification = "Error: Your file was not uploaded.";
            } else {
                if (move_uploaded_file($_FILES['faculty_image']['tmp_name'], $targetFile)) {
                    // Perform database update based on the submitted data
                    $stmt = $pdo->prepare("INSERT INTO faculty (faculty_name, faculty_position, faculty_department, user_id, faculty_image) VALUES (:name, :position, :department, :user_id, :faculty_image)");
                    $stmt->bindParam(':name', $facultyName);
                    $stmt->bindParam(':position', $facultyPosition);
                    $stmt->bindParam(':department', $facultyDepartment);
                    $stmt->bindParam(':user_id', $userId);
                    $stmt->bindParam(':faculty_image', $targetFile);

                    if ($stmt->execute()) {
                        $notification = 'Faculty member uploaded successfully!';
                    } else {
                        $notification = 'Error uploading faculty member. Please try again.';
                    }
                } else {
                    $notification = "Error: There was an error uploading your file.";
                }
            }
        } else {
            // Handle the case where user_id is not set (e.g., invalid session state)
            $notification = 'Error: User ID is not set.';
        }
    }
}

// Handle username edit form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['edit_username_submit'])) {
    $newUsername = $_POST['new_username'];

    // Get the user_id from the session
    $userId = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;

    // Check if user_id is set before updating
    if ($userId !== null) {
        // Perform database update based on the submitted data
        $updateStmt = $pdo->prepare("UPDATE users SET username = :username WHERE user_id = :user_id");
        $updateStmt->bindParam(':username', $newUsername);
        $updateStmt->bindParam(':user_id', $userId);

        if ($updateStmt->execute()) {
            $_SESSION['username'] = $newUsername; // Update session variable with the new username
            $notification = 'Username updated successfully!';
        } else {
            $notification = 'Error updating username. Please try again.';
        }
    } else {
        // Handle the case where user_id is not set (e.g., invalid session state)
        $notification = 'Error: User ID is not set.';
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
            min-height: 100vh;
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
            margin-right: 10px; /* Add margin between buttons */
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
            margin-bottom: 20px;
        }

        .hidden-form {
            display: none;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th,
        td {
            padding: 12px;
            text-align: left;
            border: 1px solid #ddd;
        }

        th {
            background-color: #007bff;
            color: #fff;
        }

        tr:nth-child(even) {
            background-color: #f2f2f2;
        }

        img {
            max-width: 100px;
            max-height: 100px;
        }

        /* Responsive table styles */
        @media (max-width: 600px) {
            table {
                font-size: 14px;
            }
        }

        /* Add these new styles for side-by-side tables */
        .table-container {
            display: flex;
            justify-content: space-between;
            gap: 20px;
            width: 100%;
            max-width: 1200px; /* Adjust as needed */
        }

        .table-container table {
            width: 48%; /* Adjust as needed to leave some gap between tables */
        }
    </style>
</head>

<body>

    <h2>Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?>!</h2>

    <button onclick="toggleFormVisibility('editUsernameForm')">Show/Hide Edit Username Form</button>
    <button onclick="toggleFormVisibility('eventForm')">Show/Hide Event Form</button>
    <button onclick="toggleFormVisibility('facultyForm')">Show/Hide Faculty Form</button>

    <form method="post" action="" class="hidden-form" id="editUsernameForm">
        <h3>Edit Username</h3>
        <label for="new_username">New Username:</label>
        <input type="text" name="new_username" required>
        <button type="submit" name="edit_username_submit">Update Username</button>
    </form>

    <div class="form-container hidden-form" id="eventForm">
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

            <label for="event_image">Event Image:</label>
            <input type="file" name="event_image" accept="image/*">

            <!-- Other form elements -->

            <button type="submit" name="event_submit">Submit Event</button>
        </form>
    </div>

    <div class="form-container hidden-form" id="facultyForm">
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
            <label for="faculty_image">Faculty Image:</label>
            <input type="file" name="faculty_image" accept="image/*" required>

            <button type="submit" name="faculty_submit">Submit Faculty</button>
        </form>
    </div>

    <div class="table-container">
    <div>
        <h3>Faculty List</h3>
        <table border="1">
            <tr>
                <th>Name</th>
                <th>Position</th>
                <th>Department</th>
                <th>Image</th>
                <th>Action</th>
            </tr>
            <?php
            // Fetch faculty data from the database
            $facultyStmt = $pdo->query("SELECT * FROM faculty");
            while ($facultyRow = $facultyStmt->fetch(PDO::FETCH_ASSOC)) {
                echo "<tr>";
                echo "<td>{$facultyRow['faculty_name']}</td>";
                echo "<td>{$facultyRow['faculty_position']}</td>";
                echo "<td>{$facultyRow['faculty_department']}</td>";
                echo "<td><img src='{$facultyRow['faculty_image']}' alt='Faculty Image' style='max-width: 100px;'></td>";
                echo "<td><a href='edit.php?type=faculty&id={$facultyRow['faculty_id']}'>Edit</a></td>";
                echo "</tr>";
            }
            ?>
        </table>
    </div>

    <!-- Event Table Section -->
    <div>
        <h3>Event List</h3>
        <table border="1">
            <tr>
                <th>Name</th>
                <th>Date</th>
                <th>Details</th>
                <th>Category</th>
                <th>Image</th>
                <th>Action</th>
            </tr>
            <?php
            // Fetch event data from the database
            $eventStmt = $pdo->query("SELECT * FROM events");
            while ($eventRow = $eventStmt->fetch(PDO::FETCH_ASSOC)) {
                echo "<tr>";
                echo "<td>{$eventRow['event_name']}</td>";
                echo "<td>{$eventRow['event_date']}</td>";
                echo "<td>{$eventRow['event_details']}</td>";
                echo "<td>{$eventRow['course_category']}</td>";
                echo "<td><img src='{$eventRow['event_image']}' alt='Event Image' style='max-width: 100px;'></td>";
                echo "<td><a href='edit.php?type=event&id={$eventRow['event_id']}'>Edit</a></td>";
                echo "</tr>";
            }
            ?>
        </table>
    </div>
</div>

        </div>
    </div>
    <a href="logout.php">Logout</a>
    <!-- Notification -->
    <?php
    if (!empty($notification)) {
        echo '<div class="notification">' . htmlspecialchars($notification) . '</div>';
    }
    ?>
    <script>
        function toggleFormVisibility(formId) {
            var form = document.getElementById(formId);

            // Toggle visibility of the specified form
            form.style.display = (form.style.display === "none") ? "block" : "none";
        }
    </script>
</body>

</html>
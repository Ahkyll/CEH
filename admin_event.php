<?php
session_start();
include 'connect.php';

$notification = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Process event form data
    if (isset($_POST['event_submit'])) {
        $eventName = htmlspecialchars($_POST['event_name']);
        $eventDate = $_POST['event_date'];
        $eventDetails = htmlspecialchars($_POST['event_details']);

        // Get the user_id from the session
        $userId = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;

        if ($userId !== null) {
            // File upload handling
            $uploadDirectory = 'assets/img';
            $uploadOk = 1;
        
            // Check if a file is uploaded
            if (!empty($_FILES['event_image']['tmp_name']) && is_uploaded_file($_FILES['event_image']['tmp_name'])) {
                $targetFile = $uploadDirectory . uniqid() . '_' . basename($_FILES['event_image']['name']);
                $check = getimagesize($_FILES['event_image']['tmp_name']);
        
                if ($check !== false) {
                    $uploadOk = 1;
                } else {
                    $notification = "Error: File is not an image.";
                    $uploadOk = 0;
                }
        
                if ($uploadOk && $_FILES['event_image']['size'] > 50000000) {
                    $notification = "Error: Sorry, your file is too large.";
                    $uploadOk = 0;
                }
        
                // Allow certain file formats
                $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif'];
                if ($uploadOk && !in_array(strtolower(pathinfo($targetFile, PATHINFO_EXTENSION)), $allowedExtensions)) {
                    $notification = "Error: Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
                    $uploadOk = 0;
                }
        
                if ($uploadOk == 0) {
                    $notification = "Error: Your file was not uploaded.";
                } else {
                    if (move_uploaded_file($_FILES['event_image']['tmp_name'], $targetFile)) {
                        $stmt = $pdo->prepare("INSERT INTO events (event_name, event_date, event_details,  user_id, event_image) VALUES (:name, :date, :details, :user_id, :event_image)");
                        $stmt->bindParam(':name', $eventName);
                        $stmt->bindParam(':date', $eventDate);
                        $stmt->bindParam(':details', $eventDetails);
                        $stmt->bindParam(':user_id', $userId);
                        $stmt->bindParam(':event_image', $targetFile);
        
                        if ($stmt->execute()) {
                            $notification = 'Event uploaded successfully!';
                            header("Location: admin_event.php");
                            exit(); 
                        } else {
                            $notification = 'Error uploading event. Please try again.';
                        }
                    } else {
                        $notification = "Error: There was an error uploading your file.";
                    }
                }
            } else {
                // If no image is uploaded, proceed without image-related checks
                $stmt = $pdo->prepare("INSERT INTO events (event_name, event_date, event_details,  user_id) VALUES (:name, :date, :details, :user_id)");
                $stmt->bindParam(':name', $eventName);
                $stmt->bindParam(':date', $eventDate);
                $stmt->bindParam(':details', $eventDetails);
                $stmt->bindParam(':user_id', $userId);
        
                if ($stmt->execute()) {
                    $notification = 'Event uploaded successfully!';

                    header("Location: admin_event.php");
                    exit(); 
                } else {
                    $notification = 'Error uploading event. Please try again.';
                }
            }
        } else {
            // Handle the case where user_id is not set (e.g., invalid session state)
            $notification = 'Error: User ID is not set.';
        }
    }        
}
// Handle event deletion
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['action']) && $_GET['action'] === 'delete' && isset($_GET['event_id'])) {
    $eventToDelete = $_GET['event_id'];

    // Perform database deletion based on the submitted data
    $deleteStmt = $pdo->prepare("DELETE FROM events WHERE event_id = :event_id");
    $deleteStmt->bindParam(':event_id', $eventToDelete);

    if ($deleteStmt->execute()) {
        // Respond with event details (JSON format for simplicity)
        echo json_encode(['success' => true]);
        exit(); // Stop further script execution after handling the AJAX request
    } else {
        // Respond with an error message in JSON format
        echo json_encode(['error' => 'Error deleting event. Please try again.']);
        exit();
    }
}




// Handle event edit
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['edit_event_submit'])) {
    $eventId = $_POST['edit_event_id'];
    $eventName = htmlspecialchars($_POST['edit_event_name']);
    $eventDate = $_POST['edit_event_date'];
    $eventDetails = htmlspecialchars($_POST['edit_event_details']);

    // File upload handling for the edited image
    $uploadDirectory = 'assets/img';
    $uploadOk = 1;

    if (!empty($_FILES['edit_event_image']['tmp_name']) && is_uploaded_file($_FILES['edit_event_image']['tmp_name'])) {
        $targetFile = $uploadDirectory . uniqid() . '_' . basename($_FILES['edit_event_image']['name']);
        $check = getimagesize($_FILES['edit_event_image']['tmp_name']);

        if ($check !== false) {
            $uploadOk = 1;
        } else {
            $notification = "Error: File is not an image.";
            $uploadOk = 0;
        }

        if ($uploadOk && $_FILES['edit_event_image']['size'] > 50000000) {
            $notification = "Error: Sorry, your file is too large.";
            $uploadOk = 0;
        }

        $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif'];
        if ($uploadOk && !in_array(strtolower(pathinfo($targetFile, PATHINFO_EXTENSION)), $allowedExtensions)) {
            $notification = "Error: Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
            $uploadOk = 0;
        }

        if ($uploadOk == 0) {
            $notification = "Error: Your file was not uploaded.";
        } else {
            if (move_uploaded_file($_FILES['edit_event_image']['tmp_name'], $targetFile)) {
                // Update database with the new image path
                $stmt = $pdo->prepare("UPDATE events SET event_name = :event_name, event_date = :event_date, event_details = :event_details, event_image = :event_image WHERE event_id = :event_id");
                $stmt->bindParam(':event_name', $eventName);
                $stmt->bindParam(':event_date', $eventDate);
                $stmt->bindParam(':event_details', $eventDetails);
                $stmt->bindParam(':event_image', $targetFile);
                $stmt->bindParam(':event_id', $eventId);

                if ($stmt->execute()) {
                    $notification = 'Event updated successfully!';
                } else {
                    $notification = 'Error updating event. Please try again.';
                }
            } else {
                $notification = "Error: There was an error uploading your file.";
            }
        }
    } else {
        // If no image is uploaded, proceed without image-related checks
        $stmt = $pdo->prepare("UPDATE events SET event_name = :event_name, event_date = :event_date, event_details = :event_details WHERE event_id = :event_id");
        $stmt->bindParam(':event_name', $eventName);
        $stmt->bindParam(':event_date', $eventDate);
        $stmt->bindParam(':event_details', $eventDetails);
        $stmt->bindParam(':event_id', $eventId);

        if ($stmt->execute()) {
            $notification = 'Event updated successfully!';
        } else {
            $notification = 'Error updating event. Please try again.';
        }
    }
}

// Logout logic
if (isset($_GET['action']) && $_GET['action'] === 'logout') {
    session_destroy();
    header("Location: admin_login.php"); // Redirect to your login page after logout
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Event Page</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }
        #sidebar {
            width: 250px;
            height: 100%;
            background: #2c3e50;
            position: fixed;
            left: 0;
            overflow-x: hidden;
            padding-top: 20px;
            text-align: center;
        }

        #profile-pic {
            border: 3px solid #fff;
            border-radius: 50%;
            margin-bottom: 10px;
            width: 100px;
            height: 100px;
            object-fit: cover;
        }

        #sidebar a {
            padding: 15px 10px;
            text-decoration: none;
            font-size: 18px;
            color: #ecf0f1;
            display: block;
            transition: 0.3s;
        }

        #sidebar a:hover {
            background-color: #34495e;
        }

        #sidebar .sub-menu {
            display: none;
            padding-left: 20px;
        }

        #sidebar .parent:hover .sub-menu {
            display: block;
        }

        #content {
            margin-left: 250px;
            padding: 15px;
            background-color: #fff;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        .parent {
            color: white;
        }
        #content {
            margin-left: 250px; /* Adjusted margin to accommodate the sidebar */
            padding: 15px;
        }

        .event-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        .event-table th, .event-table td {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: left;
        }

        .event-table th {
            background-color: #4b5d67;
            color: #ffffff;
        }

        .event-table td.editable[data-field="event_details"] {
            max-width: 200px; /* Set a specific width for event details */
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .edit-btn, .delete-btn, .history-btn {
            background-color: #4CAF50;
            color: #ffffff;
            padding: 8px 12px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            margin-right: 5px;
        }
        form {
    display: none;
    max-width: 400px;
    margin: 20px auto; 
    padding: 20px;
    background-color: #fff;
    border: 1px solid #ddd;
    border-radius: 8px;
    position: relative; 
    top: 50%;
    transform: translateY(-50%);
}


form button {
    display: block;
    width: 100%;
    padding: 10px;
    background-color: #4CAF50;
    color: #ffffff;
    border: none;
    border-radius: 4px;
    cursor: pointer;
}

        form label {
            display: block;
            margin-bottom: 8px;
            font-weight: bold;
        }

        form input,
        form textarea {
            width: 100%;
            padding: 8px;
            margin-bottom: 12px;
            box-sizing: border-box;
        }

        form button {
            display: block;
            width: 100%;
            padding: 10px;
            background-color: #4CAF50;
            color: #ffffff;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        /* Toggle button style */
        #toggleForm {
            display: block;
            margin: 20px auto;
            padding: 10px;
            background-color: #4CAF50;
            color: #ffffff;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            position: fixed;
            top: 20px;
            right: 20px;
        }
    </style>

    </style>
</head>
<body>
<div id="sidebar">
    <img id="profile-pic" src="img/default_profile_image.jpg" alt="Admin Profile Picture">
    <a href="admin_profile.php">Admin Profile</a>
    <a href="admin_event.php">Event</a>
    <div class="parent" onclick="toggleSubMenu('sub-menu-students')">
        <p>Student by Year</p>
        <div class="sub-menu" id="sub-menu-students">
            <a href="admin_student1.php">Year 1</a>
            <a href="admin_student2.php">Year 2</a>
            <a href="admin_student3.php">Year 3</a>
            <a href="admin_student4.php">Year 4</a>
        </div>
    </div>
    <a href="admin_comment.php">Comments and Post</a>
    <a href="admin_resources.php">Uploaded Resources</a>
    <a href="admin_resources.php?action=logout">Logout</a>
</div>
<div id="content">
    <h2>Event Page</h2>

    <table class="event-table">
        <thead>
        <tr>
            <th>Name</th>
            <th>Date</th>
            <th>Details</th>
            <th>Image</th>
            <th>Action</th>
        </tr>
        </thead>
        <tbody>
        <?php
        $eventStmt = $pdo->query("SELECT * FROM events");
        while ($eventRow = $eventStmt->fetch(PDO::FETCH_ASSOC)) {
            echo "<tr data-event-id='{$eventRow['event_id']}'>";
            echo "<td class='editable' data-field='event_name' data-event-id='{$eventRow['event_id']}'>{$eventRow['event_name']}</td>";
            echo "<td class='editable' data-field='event_date' data-event-id='{$eventRow['event_id']}'>{$eventRow['event_date']}</td>";
            echo "<td class='editable' data-field='event_details' data-event-id='{$eventRow['event_id']}'>{$eventRow['event_details']}</td>";
            echo "<td><img src='{$eventRow['event_image']}' alt='Event Image' style='max-width: 100px;'></td>";
            echo "<td>";
            echo "<button class='edit-btn' onclick='editEvent({$eventRow['event_id']})'>Edit</button>";
            echo "<button class='delete-btn' onclick='deleteEvent({$eventRow['event_id']})'>Delete</button>";
            echo "</td>";
            echo "</tr>";
        }
        ?>
        </tbody>
    </table>
    <!-- Toggle button -->
<button id="toggleForm">Add Event</button>

<!-- Add Event Form -->
<form id="eventForm" action="admin_event.php" method="post" enctype="multipart/form-data">
    <label for="event_name">Event Name:</label>
    <input type="text" id="event_name" name="event_name" required>

    <label for="event_date">Event Date:</label>
    <input type="date" id="event_date" name="event_date" required>

    <label for="event_details">Event Details:</label>
    <textarea id="event_details" name="event_details" required></textarea>

    <label for="event_image">Event Image:</label>
    <input type="file" id="event_image" name="event_image" accept="image/*" >

    <button type="submit" name="event_submit">Add Event</button>
</form>
</div>

<!-- Edit Event Form -->
<form id="editEventForm" action="admin_event.php" method="post" enctype="multipart/form-data" style="display: none;">
    <input type="hidden" id="edit_event_id" name="edit_event_id" value="">
    <label for="edit_event_name">Event Name:</label>
    <input type="text" id="edit_event_name" name="edit_event_name" required>

    <label for="edit_event_date">Event Date:</label>
    <input type="date" id="edit_event_date" name="edit_event_date" required>

    <label for="edit_event_details">Event Details:</label>
    <textarea id="edit_event_details" name="edit_event_details" required></textarea>

    <label for="edit_event_image">Event Image:</label>
    <input type="file" id="edit_event_image" name="edit_event_image" accept="image/*" >

    <button type="submit" name="edit_event_submit">Save Changes</button>
</form>

<script>
function toggleSubMenu(subMenuId) {
        var subMenu = document.getElementById(subMenuId);
        subMenu.style.display = subMenu.style.display === 'block' ? 'none' : 'block';
    }

// Update the JavaScript code handling event deletion
function deleteEvent(eventId) {
    if (confirm('Are you sure you want to delete this event?')) {
        fetch('admin_event.php?action=delete&event_id=' + eventId)
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Reload the page or update the event list in the UI
                    location.reload(); // This will reload the entire page, you can update the UI as needed
                } else {
                    alert('Error deleting event. Please try again.');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('An unexpected error occurred. Please try again.');
            });
    }
}

    // Toggle form visibility
    document.getElementById('toggleForm').addEventListener('click', function () {
        var form = document.getElementById('eventForm');
        form.style.display = form.style.display === 'none' ? 'block' : 'none';
    });

    function editEvent(eventId) {
    // Set the values in the edit form based on the clicked event
    var eventRow = document.querySelector(`tr[data-event-id="${eventId}"]`);
    document.getElementById('edit_event_id').value = eventId;
    document.getElementById('edit_event_name').value = eventRow.querySelector('td[data-field="event_name"]').innerText;
    document.getElementById('edit_event_date').value = eventRow.querySelector('td[data-field="event_date"]').innerText;
    document.getElementById('edit_event_details').value = eventRow.querySelector('td[data-field="event_details"]').innerText;

    // Toggle visibility of edit form
    var editForm = document.getElementById('editEventForm');
    editForm.style.display = editForm.style.display === 'none' ? 'block' : 'none';
}
</script>



</body>
</html>


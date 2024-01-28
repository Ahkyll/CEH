<?php
session_start();
include 'connect.php';


$type = isset($_GET['type']) ? $_GET['type'] : '';
$id = isset($_GET['id']) ? $_GET['id'] : '';

if ($type === 'faculty') {
    // Fetch faculty data for editing
    $stmt = $pdo->prepare("SELECT * FROM faculty WHERE faculty_id = :id");
    $stmt->bindParam(':id', $id);
    $stmt->execute();
    $record = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$record) {
        // Redirect if the faculty record is not found
        header('location: admin_page.php');
        exit();
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['edit_faculty_submit'])) {
        // Process the form data for editing faculty
        $newName = $_POST['new_name'];
        $newPosition = $_POST['new_position'];
        $newDepartment = $_POST['new_department'];

        // Check if a new image file is uploaded
        if ($_FILES['new_image']['size'] > 0) {
            $uploadDir = 'assets/img';
            $uploadFile = $uploadDir . basename($_FILES['new_image']['name']);

            if (move_uploaded_file($_FILES['new_image']['tmp_name'], $uploadFile)) {
                // Update the image path in the database
                $imagePath = $uploadFile;
            } else {
                $notification = 'Error uploading image. Please try again.';
                // You may want to handle this error more gracefully
            }
        } else {
            // Keep the existing image path if no new image is uploaded
            $imagePath = $record['faculty_image'];
        }

        // Perform database update based on the submitted data
        $updateStmt = $pdo->prepare("UPDATE faculty SET faculty_name = :name, faculty_position = :position, faculty_department = :department, faculty_image = :image WHERE faculty_id = :id");
        $updateStmt->bindParam(':name', $newName);
        $updateStmt->bindParam(':position', $newPosition);
        $updateStmt->bindParam(':department', $newDepartment);
        $updateStmt->bindParam(':image', $imagePath);
        $updateStmt->bindParam(':id', $id);

        if ($updateStmt->execute()) {
            $notification = 'Faculty record updated successfully!';
            header('location: admin_page.php');
            exit();
        } else {
            $notification = 'Error updating faculty record. Please try again.';
        }
    }
} elseif ($type === 'event') {
    // Fetch event data for editing
    $stmt = $pdo->prepare("SELECT * FROM events WHERE event_id = :id");
    $stmt->bindParam(':id', $id);
    $stmt->execute();
    $record = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$record) {
        // Redirect if the event record is not found
        header('location: admin_page.php');
        exit();
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['edit_event_submit'])) {
        // Process the form data for editing events
        $newName = $_POST['new_name'];
        $newDate = $_POST['new_date'];
        $newDetails = $_POST['new_details'];

        // Check if a new image file is uploaded
        if ($_FILES['new_image']['size'] > 0) {
            $uploadDir = 'assets/img';
            $uploadFile = $uploadDir . basename($_FILES['new_image']['name']);

            if (move_uploaded_file($_FILES['new_image']['tmp_name'], $uploadFile)) {
                // Update the image path in the database
                $imagePath = $uploadFile;
            } else {
                $notification = 'Error uploading image. Please try again.';
                // You may want to handle this error more gracefully
            }
        } else {
            // Keep the existing image path if no new image is uploaded
            $imagePath = $record['event_image'];
        }

        // Perform database update based on the submitted data
        $updateStmt = $pdo->prepare("UPDATE events SET event_name = :name, event_date = :date, event_details = :details, event_image = :image WHERE event_id = :id");
        $updateStmt->bindParam(':name', $newName);
        $updateStmt->bindParam(':date', $newDate);
        $updateStmt->bindParam(':details', $newDetails);
        $updateStmt->bindParam(':image', $imagePath);
        $updateStmt->bindParam(':id', $id);

        if ($updateStmt->execute()) {
            $notification = 'Event record updated successfully!';
            header('location: admin_page.php');
            exit();
        } else {
            $notification = 'Error updating event record. Please try again.';
        }
    }
} else {
    // Redirect if type is not valid
    header('location: admin_page.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Record</title>
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
        margin-right: 10px;
    }

    button:hover {
        background-color: #0056b3;
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
</style>

<body>

    <h2>Edit Record</h2>

    <form method="post" action="" enctype="multipart/form-data">
        <?php if ($type === 'faculty') : ?>
            <!-- Faculty edit form -->
            <label for="new_name">New Name:</label>
            <input type="text" name="new_name" value="<?php echo htmlspecialchars($record['faculty_name']); ?>" required>

            <label for="new_position">New Position:</label>
            <input type="text" name="new_position" value="<?php echo htmlspecialchars($record['faculty_position']); ?>" required>

            <label for="new_image">New Image:</label>
            <input type="file" name="new_image">

            <button type="submit" name="edit_faculty_submit">Update Faculty</button>
        <?php elseif ($type === 'event') : ?>
            <!-- Event edit form -->
            <label for="new_name">New Name:</label>
            <input type="text" name="new_name" value="<?php echo htmlspecialchars($record['event_name']); ?>" required>

            <label for="new_date">New Date:</label>
            <input type="date" name="new_date" value="<?php echo htmlspecialchars($record['event_date']); ?>" required>

            <label for="new_details">New Details:</label>
            <textarea name="new_details" required><?php echo htmlspecialchars($record['event_details']); ?></textarea>

            <label for="new_image">New Image:</label>
            <input type="file" name="new_image">

            <button type="submit" name="edit_event_submit">Update Event</button>
        <?php endif; ?>
    </form>

    <!-- Notification -->
    <?php
    if (!empty($notification)) {
        echo '<div class="notification">' . htmlspecialchars($notification) . '</div>';
    }
    ?>

</body>

</html>

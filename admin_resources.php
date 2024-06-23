<?php
session_start();
include 'connect.php';

$notification = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {


    // Handle resource deletion
    if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['action']) && $_GET['action'] === 'delete' && isset($_GET['resource_id'])) {
        $resourceToDelete = $_GET['resource_id'];

        // Fetch resource details before deletion
        $resourceStmt = $pdo->prepare("SELECT resource_name FROM resources WHERE resource_id = :resource_id");
        $resourceStmt->bindParam(':resource_id', $resourceToDelete);
        $resourceStmt->execute();
        $resourceDetails = $resourceStmt->fetch(PDO::FETCH_ASSOC);

        // Perform database deletion based on the submitted data
        $deleteStmt = $pdo->prepare("DELETE FROM resources WHERE resource_id = :resource_id");
        $deleteStmt->bindParam(':resource_id', $resourceToDelete);

        if ($deleteStmt->execute()) {
            // Respond with resource details (JSON format for simplicity)
            echo json_encode($resourceDetails);
            exit(); // Stop further script execution after handling the AJAX request
        } else {
            echo 'Error deleting resource. Please try again.';
            exit();
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
    <title>Resource Page</title>
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
            margin-left: 250px;
            /* Adjusted margin to accommodate the sidebar */
            padding: 15px;
        }

        .resource-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        .resource-table th,
        .resource-table td {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: left;
        }

        .resource-table th {
            background-color: #4b5d67;
            color: #ffffff;
        }

        .edit-btn,
        .delete-btn,
        .history-btn {
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
        }

        #history-section {
            margin-top: 20px;
            font-size: 16px;
        }
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
        <h2>Resource Page</h2>

        <table class="resource-table">
            <thead>
                <tr>
                    <th>Uploader</th>
                    <th>Title</th>
                    <th>Category</th>
                    <th>File</th>
                    <th>Link</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // Fetch resource data from the database
                $resourceStmt = $pdo->query("SELECT * FROM resources");
                while ($resourceRow = $resourceStmt->fetch(PDO::FETCH_ASSOC)) {
                    echo "<tr>";
                    echo "<td>{$resourceRow['user_id']}</td>";
                    echo "<td>{$resourceRow['resource_title']}</td>";
                    echo "<td>{$resourceRow['resource_category']}</td>";
                    echo "<td><a href='{$resourceRow['resource_file']}' target='_blank'>Download</a></td>";
                    echo "<td>{$resourceRow['resource_link']}</td>";
                    echo "<td>";
                    echo "<button class='delete-btn' onclick='deleteResource({$resourceRow['resource_id']})'>Delete</button>";
                    echo "</td>";
                    echo "</tr>";
                }
                ?>
            </tbody>
        </table>

    </div>

<!-- Add the following JavaScript function to your existing script -->
<script>
    // Function to update the resource history
    function updateHistory(message) {
        // Assuming you have a div with id 'history-section' to display messages
        var historySection = document.getElementById("history-section");
        var historyEntry = document.createElement("p");
        historyEntry.textContent = message;
        historySection.appendChild(historyEntry);
    }

    // Function to delete a resource
    function deleteResource(resourceId) {
        // Confirm deletion
        var confirmDelete = confirm("Are you sure you want to delete this resource?");

        if (confirmDelete) {
            // Create a new XMLHttpRequest object
            var xhr = new XMLHttpRequest();

            // Configure it: GET-request for the delete action
            xhr.open('GET', 'admin_resources.php?action=delete&resource_id=' + resourceId, true);

            // Set up a callback function to handle the response
            xhr.onreadystatechange = function () {
                if (xhr.readyState === XMLHttpRequest.DONE) {
                    if (xhr.status === 200) {
                        // Parse the JSON response
                        var deletedResource = JSON.parse(xhr.responseText);

                        // Display a message or update the UI as needed
                        updateHistory("Resource deleted successfully: " + deletedResource.resource_name);
                        
                        // Optionally, you can remove the deleted resource row from the table
                        var deletedRow = document.getElementById("resource_row_" + resourceId);
                        if (deletedRow) {
                            deletedRow.parentNode.removeChild(deletedRow);
                        }
                    } else {
                        // Handle the error case
                        alert('Error deleting resource. Please try again.');
                    }
                }
            };

            // Send the request
            xhr.send();
        }
    }

    // Toggle form display
    document.getElementById('toggleForm').addEventListener('click', function () {
        var form = document.getElementById('resourceForm');
        form.style.display = form.style.display === 'none' ? 'block' : 'none';
    });
</script>

</body>

</html>

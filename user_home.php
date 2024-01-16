<?php
session_start();
include 'connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Check if the user is still an admin
    if ($_SESSION['user_type'] !== 'user') {
        header('location: login.php');
        exit();
    }
}

// Fetch events from the database based on course category
$courseCategory = isset($_POST['course_category']) ? $_POST['course_category'] : 'ALL_CATEGORIES';

// Fetch events based on the selected course category
$eventsStmt = $pdo->prepare("SELECT * FROM events" . ($courseCategory !== 'ALL_CATEGORIES' ? " WHERE course_category = :category" : ""));
if ($courseCategory !== 'ALL_CATEGORIES') {
    $eventsStmt->bindParam(':category', $courseCategory);
}
$eventsStmt->execute();
$events = $eventsStmt->fetchAll(PDO::FETCH_ASSOC);

// Fetch faculty members from the database based on the department
$facultyDepartment = isset($_POST['faculty_department']) ? $_POST['faculty_department'] : 'ALL_DEPARTMENTS';

// Fetch faculty members based on the selected department
$facultyStmt = $pdo->prepare("SELECT * FROM faculty" . ($facultyDepartment !== 'ALL_DEPARTMENTS' ? " WHERE faculty_department = :department" : ""));
if ($facultyDepartment !== 'ALL_DEPARTMENTS') {
    $facultyStmt->bindParam(':department', $facultyDepartment);
}
$facultyStmt->execute();
$faculty = $facultyStmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Website</title>
    <link rel="stylesheet" type="text/css" href="css/home.css">
</head>

<body>

    <header>
        <div class="menu-icon dropdown">&#9776;
            <div class="dropdown-content">
                <span id="close-btn" onclick="toggleDropdown()">&#10006;</span>

                <div class="profile-container">
    <div class="profile-header">
        <img src="img/simpson v.png" alt="User Profile" class="profile-image">
        <div class="profile-name" id="profileName"><?= htmlspecialchars($_SESSION['user_name']) ?></div>
        <div class="username" id="profileUsername"><?= htmlspecialchars($_SESSION['username']) ?></div>
    </div>
    <br>
    <a href="logout.php" class="btn">Logout</a>
    <a href="edit_profile.php" class="btn">Edit Profile</a> <!-- Add this line -->
</div>
            </div>
        </div>

        <nav>
            <a href="user_home.php">
                <h1><span style="color: #0f96fe;">Home</span></h1>
            </a>
            <a href="resources.php">
                <h1>Resources</h1>
            </a>
            <a href="#">
                <h1>Forum</h1>
            </a>
            <a href="about.php">
                <h1>About us</h1>
            </a>
        </nav>
    </header>


   <!-- Events Section -->
<div class="events">
    <h1>Events</h1>
    <form method="post">
        <label for="course_category">Filter by Department:</label>
        <select name="course_category" onchange="this.form.submit()">
            <option value="ALL_CATEGORIES">ALL CATEGORIES</option>
            <option value="GENERAL">GENERAL</option>
            <option value="BSIT">BSIT</option>
            <option value="BEED">BEED</option>
            <option value="BSCRIM">BSCRIM</option>
            <option value="BSHM">BSHM</option>
            <option value="BSAB">BSAB</option>
            <!-- Add more categories as needed -->
        </select>
    </form>
    <ul>
        <?php foreach ($events as $event) : ?>
            <li class="event-item" data-course="<?= htmlspecialchars($event['course_category']) ?>">
                <img class="event-image" src="img/simpson-v.png" alt="<?= htmlspecialchars($event['event_name']) ?>">
                <div class="event-details">
                    <h3><?= htmlspecialchars($event['event_name']) ?></h3>
                    <p>Date: <?= htmlspecialchars($event['event_date']) ?></p>
                    <p>Details: <?= htmlspecialchars($event['event_details']) ?></p>
                    <p>Category: <?= htmlspecialchars($event['course_category']) ?></p>
                </div>
            </li>
        <?php endforeach; ?>
    </ul>
</div>

<!-- Faculty Section -->
<div class="faculty">
    <h1>Faculty Members</h1>
    <form method="post">
        <label for="faculty_department">Filter by Department:</label>
        <select name="faculty_department" onchange="this.form.submit()">
            <option value="ALL_DEPARTMENTS">ALL DEPARTMENTS</option>
            <option value="GENERAL">GENERAL</option>
            <option value="BSIT">BSIT</option>
            <option value="BEED">BEED</option>
            <option value="BSCRIM">BSCRIM</option>
            <option value="BSHM">BSHM</option>
            <option value="BSAB">BSAB</option>
            <!-- Add more departments as needed -->
        </select>
    </form>
    <ul>
        <?php foreach ($faculty as $facultyMember) : ?>
            <li class="faculty-member" data-department="<?= htmlspecialchars($facultyMember['faculty_department']) ?>">
                <img class="faculty-image" src="" alt="<?= htmlspecialchars($facultyMember['faculty_name']) ?>">
                <div class="faculty-details">
                    <h3><?= htmlspecialchars($facultyMember['faculty_name']) ?></h3>
                    <p>Position: <?= htmlspecialchars($facultyMember['faculty_position']) ?></p>
                    <p>Department: <?= htmlspecialchars($facultyMember['faculty_department']) ?></p>
                </div>
            </li>
        <?php endforeach; ?>
    </ul>
</div>

    <script src="js/home.js"></script>

</body>

</html>

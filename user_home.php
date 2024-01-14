<?php
session_start();
include 'server/connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Check if the user is still an admin
    if ($_SESSION['user_type'] !== 'user') {
        header('location:login.php');
        exit();
    }
}

// Fetch events from the database based on course category
$courseCategory = isset($_POST['course_category']) ? $_POST['course_category'] : 'all';

// If the category is not set or is 'all', fetch all events
if ($courseCategory === 'all') {
    $stmt = $pdo->query("SELECT * FROM events");
} else {
    // Fetch events based on the selected course category
    $stmt = $pdo->prepare("SELECT * FROM events WHERE course_category = :category");
    $stmt->bindParam(':category', $courseCategory);
    $stmt->execute();
}

$events = $stmt->fetchAll(PDO::FETCH_ASSOC);
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
                        <div class="profile-name">Lykah Gomo</div>
                        <div class="username">@ahkyl</div>
                    </div>
                    <br>
                    <a href="logout.php" class="btn">Logout</a>
                </div>
            </div>
        </div>

        <nav>
            <a href="user_home.php">
            <h1><span style="color: #0f96fe;">Home</span></h1>
            </a>
            <a href="projects.php">
                <h1>Projects</h1>
            </a>
            <a href="resources.php">
                <h1>Resources</h1>
            </a>
            <a href="#">
                <h1>Forum</h1>
            </a>
            <a href="#">
                <h1>Contact us</h1>
            </a>


        </nav>
    </header>

<button onclick="showAll('GENERAL')">GENERAL</button>
<button onclick="showOtherCourses('BSIT')" data-course="BSIT">BSIT</button>
<button onclick="showOtherCourses('BEED')" data-course="BEED">BEED</button>
<button onclick="showOtherCourses('BSCRIM')" data-course="BSCRIM">BSCRIM</button>
<button onclick="showOtherCourses('BSHM')" data-course="BSHM">BSHM</button>
<button onclick="showOtherCourses('BSAB')" data-course="BSAB">BSAB</button>


<div class="events">
    <h1>Events</h1>
    <p>Welcome to our events section! Here, you can find information about upcoming events, conferences, and activities happening in our community.</p>
    <?php foreach ($events as $event) : ?>
        <li data-course="<?= htmlspecialchars($event['course_category']) ?>">
            <img class="event-image" src="img/simpson v.png" alt="<?= htmlspecialchars($event['event_name']) ?>">
            <div class="event-details">
                <h3><?= htmlspecialchars($event['event_name']) ?></h3>
                <p>Date: <?= htmlspecialchars($event['event_date']) ?></p>
                <p>Details: <?= htmlspecialchars($event['event_details']) ?></p>
                <p>Category: <?= htmlspecialchars($event['course_category']) ?></p>
            </div>
        </li>
    <?php endforeach; ?>
</div>

    <div class="faculty">
        <h1>Faculty Members</h1>
        <p>Meet our dedicated faculty members who contribute to the success of our institution.</p>
        <ul>
        <li data-course="school">
    <img src="img/simpson v.png" alt="Faculty 1">
    <div>
        <strong>James Reid</strong><br>
        Department Head
    </div>
</li>


<li data-course="BSIT">
        <!-- Event details for BSIT -->
        <img src="img/simpson v.png" alt="Faculty 1">
    <div>
        <strong>BSIT</strong><br>
        Department Head
    </div>
    </li>

    <li data-course="BEED">
    <img src="img/simpson v.png" alt="Faculty 1">
    <div>
        <strong>BEED</strong><br>
        Department Head
    </div>
    </li>
    <li data-course="BSCRIM">
    <img src="img/simpson v.png" alt="Faculty 1">
    <div>
        <strong>BSCRIM</strong><br>
        Department Head
    </div>
    </li>
    <li data-course="BSHM">
    <img src="img/simpson v.png" alt="Faculty 1">
    <div>
        <strong>BSHM</strong><br>
        Department Head
    </div>
    </li>
    <li data-course="BSAB">
    <img src="img/simpson v.png" alt="Faculty 1">
    <div>
        <strong>BSAB</strong><br>
        Department Head
    </div>
    </li>









        </ul>
    </div>

    <script src="js/home.js"></script>

</body>

</html>
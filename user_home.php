<?php

include 'server/connect.php';

session_start();

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
                    <button onclick="logout()">Logout</button>
                </div>
            </div>
        </div>

        <nav>
            <a href="home.html">
                <h1><span style="color: #0f96fe;">Home</span></h1>
            </a>
            <a href="projects.html">
                <h1>Projects</h1>
            </a>
            <a href="resources.html">
                <h1>Resources</h1>
            </a>

        </nav>
    </header>

    <button onclick="showAll()">Show All</button>
<button onclick="showOtherCourses('BSIT')">BSIT</button>
<button onclick="showOtherCourses('BEED')">BEED</button>
<button onclick="showOtherCourses('BSCRIM')">BSCRIM</button>
<button onclick="showOtherCourses('BSHM')">BSHM</button>
<button onclick="showOtherCourses('BSAB')">BSAB</button>

<div class="events">
    <h1>Events</h1>
        <p>Welcome to our events section! Here, you can find information about upcoming events, conferences, and
            activities happening in our community.</p>
            <ul>
            <li data-course="school">
    <img class="event-image" src="img/simpson v.png" alt="Overall School Event">
    <div class="event-details">
        <h3>Overall School Event</h3>
        <p>Date: January 24, 2023</p>
        <p>Details: A special event for the entire school community.</p>
    </div>
</li>
    <!-- Add events for other courses -->
    <li data-course="BSIT">
        <!-- Event details for BSIT -->
        <img class="event-image" src="img/simpson v.png" alt="Event 1">
        <div class="event-details">
            <h3>Event 1: BSIT</h3>
            <p>Date: January 24, 2023</p>
            <p>Details: Final exam for the first semester.</p>
        </div>
    </li>
    <li data-course="BEED">
    <img class="event-image" src="img/simpson v.png" alt="Event 1">
        <div class="event-details">
            <h3>Event 1: BEED</h3>
            <p>Date: January 24, 2023</p>
            <p>Details: Final exam for the first semester.</p>
        </div>
    </li>
    <li data-course="BSCRIM">
    <img class="event-image" src="img/simpson v.png" alt="Event 1">
        <div class="event-details">
            <h3>Event 1: BSCRIM</h3>
            <p>Date: January 24, 2023</p>
            <p>Details: Final exam for the first semester.</p>
        </div>
    </li>
    <li data-course="BSHM">
    <img class="event-image" src="img/simpson v.png" alt="Event 1">
        <div class="event-details">
            <h3>Event 1: BSHM</h3>
            <p>Date: January 24, 2023</p>
            <p>Details: Final exam for the first semester.</p>
        </div>
    </li>
    <li data-course="BSAB">
    <img class="event-image" src="img/simpson v.png" alt="Event 1">
        <div class="event-details">
            <h3>Event 1: BSAB</h3>
            <p>Date: January 24, 2023</p>
            <p>Details: Final exam for the first semester.</p>
        </div>
    </li>
</ul>
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
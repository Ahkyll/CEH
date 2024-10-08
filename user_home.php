<?php
session_start();
include 'connect.php';

$eventsStmt = $pdo->prepare("SELECT * FROM events ORDER BY event_date");
$eventsStmt->execute();
$events = $eventsStmt->fetchAll(PDO::FETCH_ASSOC);
?>




<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Website</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

    <style>
         * {

margin: 0;
padding: 0;
box-sizing: border-box;
text-decoration: none;
}

body {
font-family: "Arial", sans-serif;
background-color:#34495e;
}

header {
background-color: #333;
color: #ecf0f1;
padding: 10px;
display: flex;
justify-content: space-between;
align-items: center;
border-bottom: 3px solid #34495e;
}


.dropdown-content {
display: none;
position: absolute;
background-color: #ecf0f1;
min-width: 200px;
box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
z-index: 1;
padding: 10px;
text-align: center;
color: #333;
}


.profile-icon.dropdown.active .dropdown-content {
display: block;
}

.profile-container {
max-width: 800px;
margin: 20px auto;
padding: 20px;
background-color: #ecf0f1;
border-radius: 8px;
box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
border: 2px solid #34495e;
}

.profile-image {
width: 100px;
height: 100px;
border-radius: 50%;
object-fit: cover;
}

.profile-name {
margin-top: 10px;
font-size: 30px;
color: #333;
}

.username {
font-size: 20px;
color: #333;
}

nav {
padding: 20px;
text-align: center;
}

nav a {
display: inline-block;
margin: 0 10px;
text-decoration: none;
font-size: 15px;
font-weight: bold;
}

nav a h1 {
color: white;
}

nav h1:hover {
color: #0f96fe;
}

        .slideshow-container {
            max-width: 900px;
            position: relative;
            margin: auto;
            margin-top: 30px;
        }

        .mySlides {
            display: none;
            width: 100%;
        }

        img.slide-image {
            width: 100%;
            height: auto;
        }

        .prev,
        .next {
            position: absolute;
            top: 50%;
            width: auto;
            padding: 16px;
            margin-top: -22px;
            color: white;
            font-weight: bold;
            font-size: 18px;
            transition: 0.6s ease;
            border-radius: 0 3px 3px 0;
            user-select: none;
        }

        .prev {
            left: 0;
            border-radius: 3px 0 0 3px;
        }

        .next {
            right: 0;
            border-radius: 0 3px 3px 0;
        }

        .prev:hover,
        .next:hover {
            background-color: rgba(0, 0, 0, 0.8);
        }

        .dot {
            height: 15px;
            width: 15px;
            margin: 0 2px;
            background-color: #bbb;
            border-radius: 50%;
            display: inline-block;
            transition: background-color 0.6s ease;
        }

        .active {
            background-color: #717171;
        }

        .events {
            margin: 20px auto;
            padding: 20px;
            background-color: #ebe2e2;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            text-align: center;
            border: 3px solid black;
        }

        .events h1 {
            color: #000000;
            font-size: 30px;
            margin-bottom: 20px;
        }

        .event-list {
            list-style-type: none;
            padding: 0;
        }

        .event-row {
            display: flex;
            flex-wrap: wrap;
            justify-content: space-around;
            margin-bottom: 20px;
        }

        .event-item {
            width: 300px;
            margin: 10px;
            padding: 10px;
            background-color: #0F4C75;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            text-align: center;
            border: 3px solid black;
        }

        .event-image {
            width: 100%;
            height: auto;
            border: 3px solid black;
            width: 280px;
            height: 300px;
            object-fit: cover;
        }

        .event-details {
            margin-top: 10px;
            color: white;
        }



        .enlarged-image-container {
            display: none;
            justify-content: center;
            align-items: center;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.8);
            z-index: 2;
        }

        .enlarged-image {
            max-width: 80%;
            max-height: 80%;
        }

        .btn {
           background-color: #0f96fe; color: #fff;
            padding: 10px 20px;
            text-decoration: none;
            border-radius: 5px;
            display: inline-block;
            margin-top: 20px;
        }
    </style>
</head>

<body>

<header>
        <div class="profile-icon dropdown">
            <?php
if (isset($_SESSION['profile_picture']) && !empty($_SESSION['profile_picture'])) {
    echo '<img src="' . htmlspecialchars($_SESSION['profile_picture']) . '" alt="User Profile" class="profile-image" style="width: 50px; height: 50px;">';
} else {
    echo '<img src="img\default_profile_image.jpg" alt="Default Profile Image" class="profile-image" style="width: 50px; height: 50px;">';
}
?>

            <div class="dropdown-content">
                <span id="close-btn" onclick="toggleDropdown()"></span>

                <div class="profile-container">
                    <div class="profile-header">
                        <?php
        if (isset($_SESSION['profile_picture']) && !empty($_SESSION['profile_picture'])) {
            echo '<img src="' . htmlspecialchars($_SESSION['profile_picture']) . '" alt="User Profile" class="profile-image">';
        } else {
            echo '<img src="img\default_profile_image.jpg" alt="Default Profile Image" class="profile-image">';
        }
        ?>
                        <div class="profile-name" id="profileName">
                            <?= htmlspecialchars($_SESSION['user_name']) ?>
                        </div>
                        <div class="username" id="profileUsername">
                            <?= htmlspecialchars($_SESSION['username']) ?>
                        </div>

                        <?php if (isset($_SESSION['year']) && isset($_SESSION['section'])) : ?>
                        <div class="year-course">
                            Year:
                            <?= htmlspecialchars($_SESSION['year']) ?> | Section:
                            <?= htmlspecialchars($_SESSION['section']) ?>
                        </div>
                        <?php endif; ?>
                    </div>
                    <br>
                    <a href="logout.php" class="btn"><i class="fas fa-sign-out-alt"></i> Logout</a>
                    <a href="edit_profile.php" class="btn"><i class="fas fa-user-edit"></i> Edit Profile</a>


                </div>

            </div>
        </div>

        <nav>
            <a href="user_home.php">
            <h1><span style="color: #0f96fe;">Home</span></h1>
            </a>

            <a href="resources.php">
                <h1>Resource Library</h1>
            </a>
            <a href="forum_display.php">
                <h1>Discussion Forum</h1>
            </a>
            <a href="about.php">
                <h1>About</h1>
            </a>
        </nav>
    </header>

    <div class="slideshow-container">
        <div class="mySlides">
            <img class="slide-image" src="img/cpsubg.jpeg" alt="Slideshow Image 1">
        </div>
        <div class="mySlides">
            <img class="slide-image" src="img/cpsubg.jpeg" alt="Slideshow Image 2">
        </div>
        <div class="mySlides">
            <img class="slide-image" src="img/collaborate_logo.png" alt="Slideshow Image 3">
        </div>

        <!-- Navigation arrows for the slideshow -->
        <a class="prev" onclick="plusSlides(-1)">&#10094;</a>
        <a class="next" onclick="plusSlides(1)">&#10095;</a>

        <!-- Navigation dots for the slideshow -->
        <div style="text-align: center; margin-top: 10px;">
            <span class="dot" onclick="currentSlide(1)"></span>
            <span class="dot" onclick="currentSlide(2)"></span>
            <span class="dot" onclick="currentSlide(3)"></span>
            <!-- Add more dots for additional slides -->
        </div>
    </div>


    
    <!-- Events Section -->
    <div class="events">
        <h1>Events Calendar</h1>

        <ul>
            <div class="event-list">
                <?php
                    $eventsInRow = 4; // Set the number of events in each row
                    $eventCount = 0;
                    foreach ($events as $event) :
                        if ($eventCount % $eventsInRow === 0) {
                        echo '<div class="event-row">';
                         }
                ?>
                
                <div class="event-item">
                    <?php
            $eventImage = isset($event['event_image']) ? htmlspecialchars($event['event_image']) : 'default_event_image.png';
            $imagePath = "" . $eventImage;
            ?>
                    <img class="event-image" src="<?= $imagePath ?>"
                        alt="<?= htmlspecialchars($event['event_name']) ?>">
                    <div class="event-details">
                        <h3>
                            <?= htmlspecialchars($event['event_name']) ?>
                        </h3>
                        <p>Date:
                            <?= htmlspecialchars($event['event_date']) ?>
                        </p>
                        <p>Details:
                            <?= htmlspecialchars($event['event_details']) ?>
                        </p>
                    </div>
                </div>
                <?php
        $eventCount++;
        if ($eventCount % $eventsInRow === 0) {
            echo '</div>';
        }
    endforeach;
    ?>
            </div>
        </ul>
    </div>


    <script>
    document.addEventListener("DOMContentLoaded", function () {
        var profileIcon = document.querySelector(".profile-icon.dropdown");
        profileIcon.addEventListener("click", function () {
            toggleDropdown();
        });

        function toggleDropdown() {
            var profileIcon = document.querySelector(".profile-icon.dropdown");
            profileIcon.classList.toggle("active");
        }

        // JavaScript for the slideshow
        var slideIndex = 1;
        showSlides(slideIndex);

        function plusSlides(n) {
            showSlides(slideIndex += n);
        }

        function currentSlide(n) {
            showSlides(slideIndex = n);
        }

        function showSlides(n) {
            var i;
            var slides = document.getElementsByClassName("mySlides");
            var dots = document.getElementsByClassName("dot");

            if (n > slides.length) {
                slideIndex = 1;
            }
            if (n < 1) {
                slideIndex = slides.length;
            }

            for (i = 0; i < slides.length; i++) {
                slides[i].style.display = "none";
            }

            for (i = 0; i < dots.length; i++) {
                dots[i].className = dots[i].className.replace(" active", "");
            }

            slides[slideIndex - 1].style.display = "block";
            dots[slideIndex - 1].className += " active";
        }

        var eventImages = document.querySelectorAll(".event-image");
        var enlargedImageContainer = document.createElement("div");
        enlargedImageContainer.className = "enlarged-image-container";
        enlargedImageContainer.innerHTML =
            '<img class="enlarged-image" id="enlarged-image" alt="Enlarged Image">';
        document.body.appendChild(enlargedImageContainer);

        eventImages.forEach(function (image) {
            image.addEventListener("click", function () {
                var enlargedImage = document.getElementById("enlarged-image");
                enlargedImage.src = this.src;
                enlargedImageContainer.style.display = "flex";
            });
        });

        enlargedImageContainer.addEventListener("click", function (event) {
            // Close the enlarged image only if the click is outside the image
            if (event.target.id === "enlarged-image") {
                return;
            }
            this.style.display = "none";
        });

        // Navigation arrows event listeners
        var prevArrow = document.querySelector(".prev");
        var nextArrow = document.querySelector(".next");

        prevArrow.addEventListener("click", function () {
            plusSlides(-1);
        });

        nextArrow.addEventListener("click", function () {
            plusSlides(1);
        });
    });

</script>


</body>

</html>
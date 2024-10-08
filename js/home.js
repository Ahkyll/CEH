document.addEventListener("DOMContentLoaded", function () {
  var menuIcon = document.querySelector(".menu-icon.dropdown");
  menuIcon.addEventListener("click", function () {
    toggleDropdown();
  });

  var closeBtn = document.getElementById("close-btn");
  closeBtn.addEventListener("click", function () {
    toggleDropdown();
  });

  var courseButtons = document.querySelectorAll("button[data-course]");
  courseButtons.forEach(function (button) {
    button.addEventListener("click", function () {
      var courseCategory = this.getAttribute("data-course");
      showOtherCourses(courseCategory);
    });
  });

  function toggleDropdown() {
    var menuIcon = document.querySelector(".menu-icon.dropdown");
    menuIcon.classList.toggle("active");
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

  enlargedImageContainer.addEventListener("click", function () {
    this.style.display = "none";
  });
});

var slideIndex = 0;
showSlides();

function showSlides() {
  var i;
  var slides = document.getElementsByClassName("mySlides");
  for (i = 0; i < slides.length; i++) {
    slides[i].style.display = "none";
  }
  slideIndex++;
  if (slideIndex > slides.length) {
    slideIndex = 1;
  }
  slides[slideIndex - 1].style.display = "block";
  setTimeout(showSlides, 2000); // Change slide every 2 seconds
}

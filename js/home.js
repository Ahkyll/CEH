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

function showAll(general) {
  toggleCourseVisibility(general);
}

function showOtherCourses(course) {
  toggleCourseVisibility(course);
}

function toggleCourseVisibility(course) {
  var eventItems = document.querySelectorAll(".events li");
  eventItems.forEach((item) => {
    if (item.getAttribute("data-course") === course || course === "general") {
      item.style.display = "block";
    } else {
      item.style.display = "none";
    }
  });
}

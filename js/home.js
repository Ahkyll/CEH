document.addEventListener("DOMContentLoaded", function () {
  var menuIcon = document.querySelector(".menu-icon.dropdown");
  menuIcon.addEventListener("click", function () {
    toggleDropdown();
  });

  var closeBtn = document.getElementById("close-btn");
  closeBtn.addEventListener("click", function () {
    toggleDropdown();
  });
});

function toggleDropdown() {
  var menuIcon = document.querySelector(".menu-icon.dropdown");
  menuIcon.classList.toggle("active");
}

function logout() {
  // Implement your logout logic here
  window.location.href = "index.html";

  return false;
  // Replace this with actual logout logic
}
document.addEventListener("DOMContentLoaded", function () {
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

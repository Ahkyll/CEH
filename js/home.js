document.addEventListener("DOMContentLoaded", function () {
    var menuIcon = document.querySelector('.menu-icon.dropdown');
    menuIcon.addEventListener('click', function () {
        toggleDropdown();
    });

    var closeBtn = document.getElementById('close-btn');
    closeBtn.addEventListener('click', function () {
        toggleDropdown();
    });
});

function toggleDropdown() {
    var menuIcon = document.querySelector('.menu-icon.dropdown');
    menuIcon.classList.toggle('active');
}

function logout() {
    // Add your logout logic here
    alert('Logout clicked');
}

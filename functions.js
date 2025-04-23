// variables for dark mode button
const toggle = document.getElementById("darkmode-toggle");
const body = document.body;

// Check if dark mode was previously enabled
// localStorage is a built-in JavaScript feature that allows you to store data persistently in a user's browser. 
// This means the data remains even after the user refreshes the page or closes the browser.
if (localStorage.getItem("darkMode") === "enabled") {
    body.classList.add("dark-mode");
    toggle.checked = true;
}

// dark mode button toggle script
toggle.addEventListener("change", function () {
    body.classList.toggle("dark-mode");

    // Save the user's preference in localStorage
    if (body.classList.contains("dark-mode")) {
        localStorage.setItem("darkMode", "enabled");
    } else {
        localStorage.setItem("darkMode", "disabled");
    }
});

// Sidebar Toggle Script
// function toggleSidebar() {
//     let sidebar = document.getElementById("sidebar");
//     // let content = document.querySelector(".content");
//     let toggleBtn = document.querySelector(".toggle-btn");

//     sidebar.classList.toggle("collapsed");
//     // content.classList.toggle("collapsed");
//     toggleBtn.classList.toggle("collapsed");

//     // Adjust toggle button position dynamically
//     if (sidebar.classList.contains("collapsed")) {
//         toggleBtn.style.left = "80px";
//     } else {
//         toggleBtn.style.left = "260px";
//     }
// }
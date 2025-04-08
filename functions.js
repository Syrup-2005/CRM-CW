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
function toggleSidebar() {
    let sidebar = document.getElementById("sidebar");
    // let content = document.querySelector(".content");
    let toggleBtn = document.querySelector(".toggle-btn");

    sidebar.classList.toggle("collapsed");
    // content.classList.toggle("collapsed");
    toggleBtn.classList.toggle("collapsed");

    // Adjust toggle button position dynamically
    if (sidebar.classList.contains("collapsed")) {
        toggleBtn.style.left = "80px";
    } else {
        toggleBtn.style.left = "260px";
    }
}

document.addEventListener("DOMContentLoaded", function() {
    fetch("get_user_data.php")
    .then(response => response.json())
    .then(data => {
        if (data.error) {
            document.getElementById("user-info").innerHTML = "<p style='color: red;'>" + data.error + "</p>";
        } else {
            document.getElementById("user-name").innerText = data.name;
            document.getElementById("user-email").innerText = data.email;
        }
    })
    .catch(error => console.error("Error fetching user data:", error));
});

document.addEventListener("DOMContentLoaded", function() {
    fetch("get_user_data.php") //Linked to get user data.php, need to update the db 
    .then(response => response.json())
    .then(data => {
        if (data.error) {
            document.getElementById("user-name").innerText = "Error loading data";
        } else {
            document.getElementById("user-name").innerText = data.name;
            document.getElementById("user-company").innerText = data.company;
            document.getElementById("user-email").innerText = data.email;
            document.getElementById("user-phone").innerText = data.phoneNumber;
            document.getElementById("user-address").innerText = data.address;
            document.getElementById("profile-pic").src = data.profile_picture || "default.jpg";
        }
    })
    .catch(error => console.error("Error fetching user data:", error));
});

// creating dynamic buttons
// document.addEventListener("DOMContentLoaded", function() {
//     // Button data (text + link)
//     let buttons = [
//         { text: "Schedule a Call", link: "calls.html" },
//         { text: "Manage Clients", link: "managecustomers.html" },
//         { text: "View Reports", link: "reports.html" }, 
//         { text: "Send an Email", link: "emails.html" },
//         { text: "Customise Profile", link: "profile.html" }
//     ];

//     // Shuffle function (Fisher-Yates Algorithm)
//     function shuffle(array) {
//         for (let i = array.length - 1; i > 0; i--) {
//             let j = Math.floor(Math.random() * (i + 1));
//             [array[i], array[j]] = [array[j], array[i]]; // Swap elements
//         }
//     }

//     // Shuffle buttons
//     shuffle(buttons);

//     // Generate buttons dynamically
//     let buttonContainer = document.getElementById("button-container");
//     buttonContainer.innerHTML = ""; // Clear existing buttons

//     buttons.forEach(button => {
//         let a = document.createElement("a");
//         a.href = button.link;
//         a.className = "btn btn-primary w-50";
//         a.innerText = button.text;

//         // console.log("Creating button:", a.innerText, "with link:", a.href); // Debugging

//         buttonContainer.appendChild(a);
//         buttonContainer.appendChild(document.createElement("br")); // Line break
//     });
// });
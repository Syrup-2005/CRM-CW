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

// ****************************************************************************************
// addcustomer.html scripts
// ****************************************************************************************
// <!-- Javascript to fetch admin data -->
document.addEventListener("DOMContentLoaded", function() {
    // Fetch admins when the page loads
    fetch('get_admins.php')
        .then(response => response.json())
        .then(data => {
            const adminSelect = document.getElementById("admin_id");
            if (data.error) {
                adminSelect.innerHTML = `<option>${data.error}</option>`;
            } else {
                let options = "";
                data.forEach(admin => {
                    options += `<option value="${admin.user_id}">${admin.Name}</option>`;
                });
                adminSelect.innerHTML = options; // Populate select dropdown
            }
        })
        .catch(error => console.error("Error fetching admin data:", error));
});

// <!-- JavaScript for fetching user data -->
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

// <!-- Javascript for fetching customer information -->
fetch('get_customer_data.php')
    .then(res => res.json())
    .then(data => {
        const tbody = document.querySelector("table tbody");
        if (data.length === 0) {
            tbody.innerHTML = "<tr><td colspan='4'>No customers found.</td></tr>";
        } else {
            data.forEach(customer => {
                const row = document.createElement("tr");
                row.innerHTML = `
                    <td>${customer.customer_name}</td>
                    <td>${customer.email ?? ''}</td>
                    <td>${customer.phone_no ?? ''}</td>
                    <td>${customer.company ?? ''}</td>
                    <td>${customer.address ?? ''}</td>
                `;
                tbody.appendChild(row);
            });
        }
    })
    .catch(error => {
        console.error('Error fetching customer data:', error);
    });


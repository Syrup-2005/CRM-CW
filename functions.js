// Variables for dark mode button
const toggle = document.getElementById("darkmode-toggle");
const body = document.body;
let bg_color = "#ffe1f2";
let finisher; // store instance globally if needed

// Function to initialize or re-initialize FinisherHeader
function initFinisherHeader() {
    // Clear the old canvas if it exists
    const oldCanvas = document.querySelector(".finisher-canvas");
    if (oldCanvas) oldCanvas.remove();

    new FinisherHeader({
        count: 20,
        size: {
            min: 12,
            max: 254,
            pulse: 0.2
        },
        speed: {
            x: { min: 0, max: 0.2 },
            y: { min: 0, max: 0.4 }
        },
        colors: {
            background: bg_color,
            particles: [
                "#ffe960",
                "#87ddfe",
                "#8481ff",
                "#fc7bfc",
                "#e2ffa5"
            ]
        },
        blending: "overlay",
        opacity: {
            center: 0,
            edge: 0.7
        },
        skew: 0,
        shapes: ["c"]
    });
}

// On DOM load
document.addEventListener("DOMContentLoaded", function () {
    if (localStorage.getItem("darkMode") === "enabled") {
        body.classList.add("dark-mode");
        bg_color = "#202225";
        toggle.checked = true;
    }
    initFinisherHeader();
});

// Dark mode toggle event
toggle.addEventListener("change", function () {
    const darkEnabled = body.classList.toggle("dark-mode");

    // Update background color
    bg_color = darkEnabled ? "#202225" : "#ffe1f2";
    localStorage.setItem("darkMode", darkEnabled ? "enabled" : "disabled");

    // Re-initialize the FinisherHeader with new color
    initFinisherHeader();
});

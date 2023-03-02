// Font Awesome
import "@fortawesome/fontawesome-free/js/fontawesome";
import "@fortawesome/fontawesome-free/js/solid";
FontAwesome.config.mutateApproach = "sync";

// Css
import "./styles/app.scss";

// Libs
import "bootstrap/dist/js/bootstrap.min";

// Start stimulus
import "./stimulus";

if (navigator && navigator.serviceWorker) {
    navigator.serviceWorker.register("sw.js");
}

//cibler le logo sur chaque page
let logo = document.querySelectorAll("img#logo");
let logoFooter = document.querySelector("img#footer");
let html = document.querySelector("html");
let sun = document.querySelector("#sun");
let moon = document.querySelector("#moon");

document.addEventListener("DOMContentLoaded", () => {
    const isDarkMode = localStorage.getItem("darkmode");
    if (isDarkMode == "true") {
        html.setAttribute("data-bs-theme", "dark");
        logo.forEach((element) => element.setAttribute("src", "/app_icon/logowhite.png"));
        // logo.setAttribute("src", "/app_icon/logowhite.png");
        logoFooter.setAttribute("src", "/app_icon/logowhite.png");
        checkbox.setAttribute("checked", "");
        sun.classList.remove("hidden");
    } else {
        // sun.classList.remove("hidden");
        moon.classList.remove("hidden");
    }
});

// logo.setAttribute("src", "/app_icon/logowhite.png");
function darkmode() {
    let isDarkMode = localStorage.getItem("darkmode");
    if (isDarkMode == "false" || !isDarkMode) {
        html.setAttribute("data-bs-theme", "dark");
        localStorage.setItem("darkmode", "true");
        logoFooter.setAttribute("src", "/app_icon/logowhite.png");
        logo.forEach((element) => element.setAttribute("src", "/app_icon/logowhite.png"));
        sun.classList.remove("hidden");
        moon.classList.add("hidden");
        // logo.setAttribute("src", "/app_icon/logowhite.png");
    } else {
        html.setAttribute("data-bs-theme", "");
        localStorage.setItem("darkmode", "false");
        checkbox.setAttribute("checked", "");
        logo.forEach((element) => element.setAttribute("src", "/app_icon/logo.png"));
        // logo.setAttribute("src", "/app_icon/logo.png");
        logoFooter.setAttribute("src", "/app_icon/logoblancnoir.png");
        sun.classList.add("hidden");
        moon.classList.remove("hidden");
    }
    console.log(sun, moon);
}

let checkbox = document.querySelector("#demo5");
checkbox.addEventListener("click", darkmode);

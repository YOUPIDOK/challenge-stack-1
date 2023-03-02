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
let logo = document.querySelector("img#logo");
let logoFooter = document.querySelector("img#footer");
let html = document.querySelector("html");

// logo.setAttribute("src", "/app_icon/logowhite.png");
function darkmode() {
    let isDarkMode = localStorage.getItem("darkmode");
    if (isDarkMode == "false" || !isDarkMode) {
        html.setAttribute("data-bs-theme", "dark");
        localStorage.setItem("darkmode", "true");
        logoFooter.setAttribute("src", "/app_icon/logowhite.png");
        logo.setAttribute("src", "/app_icon/logowhite.png");
    } else {
        html.setAttribute("data-bs-theme", "");
        localStorage.setItem("darkmode", "false");
        checkbox.setAttribute("checked", "");
        logo.setAttribute("src", "/app_icon/logo.png");
        logoFooter.setAttribute("src", "/app_icon/logoblancnoir.png");
    }
}
document.addEventListener("DOMContentLoaded", () => {
    const isDarkMode = localStorage.getItem("darkmode");
    if (isDarkMode == "true") {
        html.setAttribute("data-bs-theme", "dark");
        logo.forEach((element) => element.setAttribute("src", "/app_icon/logowhite.png"));
        logoFooter.setAttribute("src", "/app_icon/logowhite.png");
        checkbox.setAttribute("checked", "");
    }
});

let checkbox = document.querySelector("#demo5");
checkbox.addEventListener("click", darkmode);

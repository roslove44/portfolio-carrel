// Couleurs de Base
const root = document.documentElement;
const computedStyle = getComputedStyle(root);
const colorPrincipal = computedStyle.getPropertyValue("--color-principal");
const colorSecondary = computedStyle.getPropertyValue("--color-secondary");

const dark_mode_svg = document.querySelector("#wave_img");
let bgDark = document.querySelectorAll("[data-turn-bg-dark]");
let bgSecondary = document.querySelectorAll("[data-turn-bg-secondary]");
let bgWhite = document.querySelectorAll("[data-turn-bg-white]");
let textTurnPrincipal = document.querySelectorAll("[data-turn-text-principal]");
let textTurnWhite = document.querySelectorAll("[data-turn-text-white]");

// Ajoutez un écouteur d'événements "change" à la case à cocher
function turnToDarkMode() {
  if (dark_mode_svg) {
    dark_mode_svg.style.fill = "black";
  }
  for (let text of textTurnPrincipal) {
    text.style.color = colorPrincipal;
  }
  for (let text of textTurnWhite) {
    text.classList.remove("text-muted");
    text.style.color = "white";
  }
  for (let bg of bgDark) {
    bg.style.backgroundColor = "black";
    bg.classList.remove("bg-light");
  }
  for (let bg of bgSecondary) {
    bg.style.backgroundColor = colorSecondary;
    bg.classList.remove("bg-light");
  }
  for (let bg of bgWhite) {
    bg.style.backgroundColor = "white";
    bg.classList.remove("bg-light");
  }
}
function turnToLightMode() {
  if (dark_mode_svg) {
    dark_mode_svg.style.fill = "";
  }
  for (let text of textTurnPrincipal) {
    text.style.color = "";
  }
  for (let text of textTurnWhite) {
    text.style.color = "";
  }
  for (let bg of bgDark) {
    bg.style.backgroundColor = "";
  }
  for (let bg of bgSecondary) {
    bg.style.backgroundColor = "";
  }
  for (let bg of bgWhite) {
    bg.style.backgroundColor = "";
  }
}

// Initialisez le mode en fonction de la valeur stockée dans localStorage
const isDarkMode = localStorage.getItem("darkMode") === "true";
checkbox.checked = isDarkMode;

// Appliquez le mode sombre ou clair initial
if (isDarkMode) {
  turnToDarkMode();
} else {
  turnToLightMode();
}

checkbox.addEventListener("change", function () {
  if (checkbox.checked) {
    turnToDarkMode();
    localStorage.setItem("darkMode", true);
  } else {
    turnToLightMode();
    localStorage.setItem("darkMode", false);
  }
});

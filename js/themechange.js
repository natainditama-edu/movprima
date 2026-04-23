/* LIGHT/DARK THEME */
const btn = document.querySelector(".theme-toggle");
const checkbox = document.getElementById("darkmode");
const checkboxText = document.getElementById("checkboxText");

btn.addEventListener("click", function () {
  if (prefersDarkScheme.matches) {
    document.body.classList.toggle("light-theme");
    var theme = document.body.classList.contains("light-theme")
      ? "light"
      : "dark";
    var txt = document.body.classList.contains("light-theme")
      ? "Light Mode"
      : "Dark Mode";
    localStorage.setItem("currentThemeText", txt);
    document.getElementById("checkboxText").textContent =
      localStorage.getItem("currentThemeText");
  } else {
    document.body.classList.toggle("dark-theme");
    var theme = document.body.classList.contains("dark-theme")
      ? "dark"
      : "light";
    var txt = document.body.classList.contains("dark-theme")
      ? "Dark Mode"
      : "Light Mode";
    localStorage.setItem("currentThemeText", txt);
    document.getElementById("checkboxText").textContent =
      localStorage.getItem("currentThemeText");
  }

  localStorage.setItem("theme", theme);
  localStorage.setItem("darkmode", checkbox.checked);
});

function load() {
  var checked = JSON.parse(localStorage.getItem("darkmode"));
  document.getElementById("darkmode").checked = checked;
  if (localStorage.getItem("currentThemeText") == null) {
    document.getElementById("checkboxText").textContent = "Choose your theme.";
  } else {
    document.getElementById("checkboxText").textContent =
      localStorage.getItem("currentThemeText");
  }
}

load();

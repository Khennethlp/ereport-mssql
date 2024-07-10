// document.addEventListener("DOMContentLoaded", function () {
//   const darkBtn = document.getElementById("dark-btn");
//   const lightBtn = document.getElementById("light-btn");
//   // const active = document.getElementById("a-active");
//   const buttons = document.querySelectorAll('.btn-theme');
//   const body = document.body;

//   // Function to apply the theme
//   function applyTheme(theme) {
//     if (theme === "dark") {
//       body.classList.add("dark-mode");
//       body.classList.remove("light-mode");
//      darkBtn.classList.add("theme-active");
//     lightBtn.classList.remove("theme-active");
//     darkBtn.style.color = "white";
//     lightBtn.style.color = "white";
//     } else {
//       body.classList.add("light-mode");
//       body.classList.remove("dark-mode");
//       lightBtn.classList.add("theme-active");
//       darkBtn.classList.remove("theme-active");
//       darkBtn.style.color = "black";
//       lightBtn.style.color = "white";
//     }
//   }

//   function applyDarkTheme() {
//     body.classList.add("dark-mode");
//     body.classList.remove("light-mode");
//     darkBtn.style.color = "white";
//     lightBtn.style.color = "white";
//     sessionStorage.setItem("theme", "dark"); // Save the theme preference in sessionStorage
//   }

//   function applyLightTheme() {
//     body.classList.add("light-mode");
//     body.classList.remove("dark-mode");
//     darkBtn.style.color = "black";
//     lightBtn.style.color = "black";
//     sessionStorage.setItem("theme", "light"); // Save the theme preference in sessionStorage
//   }

//   // Check session storage for theme preference
//   const currentTheme = sessionStorage.getItem("theme") || "light";
//   applyTheme(currentTheme);

//   // Event listener for dark mode button
//   darkBtn.addEventListener("click", function () {
//     applyDarkTheme();
//     darkBtn.classList.add("theme-active");
//     lightBtn.classList.remove("theme-active");
//   });

//   lightBtn.addEventListener("click", function () {
//     applyLightTheme();
//     lightBtn.classList.add("theme-active");
//     darkBtn.classList.remove("theme-active");
//   });

//   // if (theme === "light") {
//   //   darkBtn.style.color = "white";
//   //   lightBtn.style.color = "white";
//   //   lightBtn.classList.add("theme-active");
//   //   darkBtn.classList.remove("theme-active");
//   // } else {
//   //   darkBtn.style.color = "white";
//   //   lightBtn.style.color = "white";
//   //   darkBtn.classList.add("theme-active");
//   //   lightBtn.classList.remove("theme-active");
//   // }

//   buttons.forEach(button => {
//     button.addEventListener('click', function () {
//         // Remove 'active' class from all buttons
//         buttons.forEach(btn => btn.classList.remove('theme-active'));

//         // Add 'active' class to the clicked button
//         this.classList.add('theme-active');
//         this.style.color = "white";
//     });
// });
// });

document.addEventListener("DOMContentLoaded", function () {
  const body = document.body;
  const sidebar = document.getElementById("sidebar");
  const navbar = document.getElementById("navbar");

  // Function to detect system theme
  function detectSystemTheme() {
    const systemTheme = window.matchMedia("(prefers-color-scheme: dark)")
      .matches
      ? "dark"
      : "light";
    console.log("Detected system theme:", systemTheme);
    return systemTheme;
  }

  // Function to apply the theme
  function applyTheme(theme) {
    if (theme === "dark") {
      body.classList.add("dark-mode");
      body.classList.remove("light-mode");

      sidebar.classList.add("sidebar-dark-primary");
      sidebar.classList.remove("sidebar-light-primary");

      navbar.classList.add("navbar-dark");
      navbar.classList.remove("navbar-light");
    } else {
      body.classList.remove("dark-mode");
      body.classList.add("light-mode");

      sidebar.classList.remove("sidebar-dark-primary");
      sidebar.classList.add("sidebar-light-primary");

      navbar.classList.remove("navbar-dark");
      navbar.classList.add("navbar-light");
    }
  }

  // Automatically apply the system theme and update on change
  function applySystemTheme() {
    const systemTheme = detectSystemTheme();
    applyTheme(systemTheme);
  }

  // Event listener for system theme changes
  window
    .matchMedia("(prefers-color-scheme: dark)")
    .addEventListener("change", function (e) {
      console.log("System theme changed to:", e.matches ? "dark" : "light");
      applySystemTheme();
    });

  // Apply the initial system theme
  applySystemTheme();
});



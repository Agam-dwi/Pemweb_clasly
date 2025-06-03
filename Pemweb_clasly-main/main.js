console.log("Classly homepage loaded.");

// Ambil nama user dari localStorage
const userName = localStorage.getItem("classlyUserName");

// Cek apakah ada nama user
if (userName) {
  const welcomeText = document.getElementById("welcome-text");
  welcomeText.textContent = `Welcome back, ${userName}!`;
}
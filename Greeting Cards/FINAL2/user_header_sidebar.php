<?php
// Start session safely
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Redirect if not logged in
if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit();
}
?>

<style>
/* ===== General Styles ===== */
* {
  box-sizing: border-box;
  margin: 0;
  padding: 0;
}
body {
  overflow-x: hidden; /* Prevent horizontal scroll */
  font-family: Comic Sans MS, sans-serif;
  background: linear-gradient(to right, #ffe4e1, #ffb6c1);
  min-height: 100vh;
}

/* ===== Header ===== */
header {
  background: linear-gradient(90deg, #FF69B4, #F8BBD0);
  color: white;
  display: flex;
  align-items: center;
  justify-content: center;
  padding: 30px 50px;
  position: fixed;
  top: 0;
  left: 0;
  right: 0;
  height: 90px;
  z-index: 1001;
}
.menu-icon {
  font-size: 26px;
  cursor: pointer;
  left: 20px;
  top: 25px;
  position: absolute;
  color: white;
  z-index: 1002;
}
header img.logo {
  height: 110px;
  margin-left: 20px;
  margin-right: 15px;
  object-fit: contain;
}
header h1 {
  font-size: 22px;
  text-align: center;
  margin: 0 auto;
}
.search-form {
  display: flex;
  gap: 10px;
  align-items: center;
}
.search-form input {
  padding: 8px 15px;
  font-size: 14px;
  border: 1px solid #ccc;
  border-radius: 20px;
  width: 200px;
}
.search-form button {
  padding: 8px 14px;
  background-color: #fff;
  color: #FF69B4;
  border: none;
  border-radius: 20px;
  cursor: pointer;
  font-size: 14px;
}

/* Responsive header */
@media (max-width: 600px) {
  .search-form input { width: 140px; }
  header { flex-direction: column; align-items: flex-start; }
  header h1 { font-size: 18px; }
  .menu-icon { font-size: 22px; top: 10px; }
}

/* ===== Sidebar ===== */
nav {
  position: fixed;
  top: 90px; /* match header height */
  left: 0;
  width: 200px;
  height: 100%;
  background-color: #fff;
  box-shadow: 2px 0 6px rgba(0,0,0,0.1);
  padding-top: 20px;
  transform: translateX(-200px);
  transition: transform 0.3s ease;
  z-index: 1000;
}
nav.show { transform: translateX(0); }
nav a {
  display: block;
  padding: 12px 20px;
  color: #333;
  text-decoration: none;
  font-size: 16px;
}
nav a:hover { background-color: #f0f0f0; }
.logout { color: red; font-weight: bold; }

/* ===== Dropdown ===== */
.dropdown { position: relative; }
.dropdown-btn {
  background: none;
  border: none;
  font-size: 16px;
  color: #333;
  padding: 12px 20px;
  text-align: left;
  width: 100%;
  cursor: pointer;
}
.dropdown-content {
  display: none;
  position: absolute;
  top: 100%;
  left: 0;
  flex-direction: column;
  background-color: #f9f9f9;
  box-shadow: inset 0 1px 2px rgba(0,0,0,0.1);
  z-index: 999;
  width: 180px;
}
.dropdown-content a {
  padding: 10px 30px;
  color: #333;
  text-decoration: none;
}
.dropdown-content a:hover { background-color: #f0f0f0; }
.dropdown.active .dropdown-content { display: flex; flex-direction: column; }
</style>

<header>
  <div class="menu-icon" id="menuToggle" onclick="toggleNav()" aria-expanded="false">☰</div>
  <img src="image/logo11.png" class="logo" alt="Greeting Cards Logo" />
  <h1></h1>
  <form action="user-dashboard.php" method="GET" class="search-form" id="dashSearchForm">
    <input type="text" name="query" id="dashSearchInput" placeholder="Search cards..." 
      value="<?php echo isset($_GET['query']) ? htmlspecialchars($_GET['query']) : ''; ?>" required />
    <button type="submit">🔍</button>
    <button type="button" id="dashSearchClear" title="Clear search">✖</button>
  </form>
</header>

<nav id="sidebar">
  <a href="user-dashboard.php">🏠 Home</a>

  <div class="dropdown">
    <button class="dropdown-btn" type="button">🗂️ Cards</button>
    <div class="dropdown-content">
      <a href="Birthday.php">🎂 Birthday</a>
      <a href="Anniversary.php">💑 Anniversary</a>
      <a href="ThankYou.php">🙏 Thank You</a>
      <a href="Congratulation.php">🎉 Congrats</a>
      <a href="Sorry.php">😔 Sorry</a>
      <a href="Welcome.php">👋 Welcome</a>
    </div>
  </div>

  <a href="cart.php">🛒 Add To Cart</a>
  <a href="contact.php">📞 Contact Us</a>
  <a href="aboutus.php">👤 About Us</a>
  <a href="logout.php" class="logout">🚪 Logout</a>
</nav>

<script>
// Sidebar toggle
function toggleNav() {
  const sidebar = document.getElementById("sidebar");
  const main = document.querySelector("main");
  const footer = document.querySelector("footer");
  const menuToggle = document.getElementById("menuToggle");
  const cardsContainer = document.querySelector(".cards-container");

  const isOpen = sidebar.classList.toggle("show");
  if(main) main.classList.toggle("shifted", isOpen);
  if(footer) footer.classList.toggle("shifted", isOpen);
  if(cardsContainer) cardsContainer.classList.toggle("shifted", isOpen);

  menuToggle.setAttribute("aria-expanded", isOpen);
}

// Dropdown toggle
document.addEventListener("DOMContentLoaded", function () {
  const dropdownBtn = document.querySelector(".dropdown-btn");
  const dropdown = dropdownBtn?.parentElement;
  if (dropdownBtn && dropdown) {
    dropdownBtn.addEventListener("click", function () {
      dropdown.classList.toggle("active");
    });
  }
});
</script>

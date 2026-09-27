<?php
session_start();
if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>User Dashboard | heycardy</title>
  <style>
    * {
      box-sizing: border-box;
      margin: 0;
      padding: 0;
    }body {
 overflow-x: hidden; /* Prevent horizontal scroll */
  font-family: Comic Sans MS;
  background: linear-gradient(to right, #ffe4e1, #ffb6c1);
  margin: 0;
  padding: 0;
  min-height: 100vh;
}

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
      overflow: hidden;
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
      text-align: center;
    }
    @media (max-width: 600px) {
      .search-form input {
        width: 140px;
      }
      header {
        flex-direction: column;
        align-items: flex-start;
      }
      header h1 {
        font-size: 18px;
      }
      .menu-icon {
        font-size: 22px;
        top: 10px;
      }
    }
    nav {
      position: fixed;
      top: 90px; /* match header height */
      left: 0;
      width: 200px;
      height: 100%;
      background-color: #fff;
      box-shadow: 2px 0 6px rgba(0, 0, 0, 0.1);
      padding-top: 20px;
      transform: translateX(-200px);
      transition: transform 0.3s ease;
      z-index: 1000;
    }

    nav.show {
      transform: translateX(0);
    }
main {
  background: transparent;
  margin-left: 100; /* ← Remove default offset */
  margin-top: 130px;
  padding: 70px;
  min-height: 400px;
  transition: margin-left 0.3s ease;
}

    main.shifted {
      margin-left: 200px; /* Same as sidebar width */
    }

    footer {
      margin-top: 40px;
      text-align: center;
      color: #666;
      padding: 15px;
      font-size: 14px;
      transition: margin-left 0.3s ease;
    }

    footer.shifted {
      margin-left: 200px;
    }

    nav a {
      display: block;
      padding: 12px 20px;
      color: #333;
      text-decoration: none;
      font-size: 16px;
    }
    nav a:hover {
      background-color: #f0f0f0;
    }
    /* Dropdown styles */
    .dropdown {
      position: relative;
    }
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
    .dropdown-content a:hover {
      background-color: #f0f0f0;
    }
    .dropdown.active .dropdown-content {
      display: flex;
      flex-direction: column;
    }
 .cards-container {
  display: flex;
  flex-wrap: wrap;
  justify-content: flex-start;
  gap: 25px;
  padding: 0 0px; /* Small left/right padding */
  box-sizing: border-box;
  transition: padding-left 0.3s ease;
}

.cards-container.shifted {
  padding-left: 100px; /* match sidebar + small margin */
}

.card {
  flex: 0 0 250px; /* fixed size for card */
  background: #fff;
  border-radius: 12px;
  box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
  overflow: hidden;
  transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.card img {
  width: 100%;
  height: 180px;
  object-fit: cover;
  transition: filter 0.3s ease;
}
  /* Highlighted search results */
    .card.highlight {
      outline: 3px solid #ff69b4;
      box-shadow: 0 0 0 4px rgba(255,105,180,0.2), 0 8px 20px rgba(0,0,0,0.25);
      transform: translateY(-6px);
    }
    .card.highlight img { filter: brightness(1.12) saturate(1.05); }
    .card:hover {
      transform: translateY(-5px);
      box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
    }
    .card:hover img {
      filter: brightness(1.1);
    }
    .card-content {
      padding: 15px;
      text-align: center;
    }
    .card.dim { opacity: 0.45; filter: grayscale(10%); }
    .card-content h3 {
      font-size: 18px;
      font-family: Arial, sans-serif;
      margin-bottom: 8px;
      color: #333;
    }
    .card-content p {
      font-size: 14px;
      color: #666;
      margin: 4px 0;
    }
    .card-content form button {
      background-color: #FF69B4;
      color: white;
      border: none;
      padding: 8px 16px;
      border-radius: 20px;
      cursor: pointer;
      font-size: 14px;
      transition: background-color 0.3s ease;
    }
    .card-content form button:hover {
      background-color: #e0559f;
    }
    .logout {
      color: red;
      font-weight: bold;
    }
  </style>
</head>
<body>

<header>
  <div class="menu-icon" id="menuToggle" onclick="toggleNav()" aria-expanded="false">☰</div>
  <img src="image/logo11.png" class="logo" alt="Greeting Cards Logo" />
  <h1></h1>
  <form action="user-dashboard.php" method="GET" class="search-form" id="dashSearchForm">
    <input type="text" name="query" id="dashSearchInput" placeholder="Search cards..." value="<?php echo isset($_GET['query']) ? htmlspecialchars($_GET['query']) : ''; ?>" required />
    <button type="submit">🔍</button>
    <button type="button" id="dashSearchClear" title="Clear search">✖</button>
  </form>
</header>

<nav id="sidebar">
  <a href="#">🏠 Home</a>
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
  <a href="cart.php" onclick="loadContent('cart.php'); return false;">🛒 Add To Cart</a>
  <a href="#" onclick="loadContent('contact.php'); return false;">📞 Contact Us</a>
  <a href="#" onclick="loadContent('aboutus.php'); return false;">👤 About Us</a>
  <a href="logout.php" class="logout">🚪 Logout</a>
</nav>

<main>
  <div id="dynamic-content">
    <div class="cards-container">
      <?php
      $conn = new mysqli("localhost", "root", "", "heycardy");
      if ($conn->connect_error) {
          die("Connection failed: " . $conn->connect_error);
      }

      $query = isset($_GET['query']) ? trim($_GET['query']) : '';
      $queryLower = strtolower($query);

      // Prepare SQL with LIKE clauses if search query exists
      if ($query !== '') {
          $likeQuery = "%" . $conn->real_escape_string($queryLower) . "%";
          $stmt = $conn->prepare("SELECT * FROM cardss WHERE LOWER(name) LIKE ? OR LOWER(category) LIKE ? OR LOWER(subcategory) LIKE ? ORDER BY id DESC");
          $stmt->bind_param("sss", $likeQuery, $likeQuery, $likeQuery);
          $stmt->execute();
          $result = $stmt->get_result();
      } else {
          $result = $conn->query("SELECT * FROM cardss ORDER BY id DESC");
      }

      if ($result && $result->num_rows > 0) {
          while ($row = $result->fetch_assoc()) {
              $nameLc = strtolower($row['name'] ?? '');
              $catLc = strtolower($row['category'] ?? '');
              $subLc = strtolower($row['subcategory'] ?? '');
              $isMatch = false;
              if ($query !== '') {
                  $isMatch = (strpos($nameLc, $queryLower) !== false) || (strpos($catLc, $queryLower) !== false) || (strpos($subLc, $queryLower) !== false);
              }
              if ($query !== '') {
                  $cardClass = $isMatch ? "card highlight" : "card dim";
              } else {
                  $cardClass = "card";
              }
              echo "<div class='" . $cardClass . "'>";
              echo "<img src='image/" . htmlspecialchars($row['image']) . "' alt='" . htmlspecialchars($row['name']) . "' />";
              echo "<div class='card-content'>";
              echo "<h3>" . htmlspecialchars($row['name']) . "</h3>";
              echo "<p>₹" . number_format(floatval($row['price']), 2) . "</p>";
              echo "<p>Category: " . htmlspecialchars($row['category']) . "</p>";
              echo "<p>Subcategory: " . htmlspecialchars($row['subcategory']) . "</p>";
              echo "<form method='POST' action='add_to_cart.php'>";
              echo "<input type='hidden' name='card_id' value='" . intval($row['id']) . "' />";
              echo "<div style='margin:8px 0'>Quantity: <input type='number' name='quantity' min='1' value='1' style='width:60px;padding:4px;border:1px solid #ccc;border-radius:6px;' /></div>";
              echo "<button type='submit'>Add to Cart</button>";
              echo "</form>";
              echo "</div>";
              echo "</div>";
          }
          if (isset($stmt)) $stmt->close();
      } else {
          echo "<p>No cards available yet.</p>";
      }
      $conn->close();
      ?>
    </div>
  </div>

  <footer>
    &copy; <?= date('Y') ?> Greeting Cards Inc. | Enjoy sharing love!
  </footer>
</main>

<script>
  // Toggle sidebar navigation and adjust main/footer margin accordingly
function toggleNav() {
  const sidebar = document.getElementById("sidebar");
  const main = document.querySelector("main");
  const footer = document.querySelector("footer");
  const menuToggle = document.getElementById("menuToggle");
  const cardsContainer = document.querySelector(".cards-container");

  const isOpen = sidebar.classList.toggle("show");
  main.classList.toggle("shifted", isOpen);
  footer.classList.toggle("shifted", isOpen);

  if (cardsContainer) {
    cardsContainer.classList.toggle("shifted", isOpen);
  }

  menuToggle.setAttribute("aria-expanded", isOpen);
}



  // Dropdown toggle on click
  document.addEventListener("DOMContentLoaded", function () {
    const dropdownBtn = document.querySelector(".dropdown-btn");
    const dropdown = dropdownBtn?.parentElement;

    if (dropdownBtn && dropdown) {
      dropdownBtn.addEventListener("click", function () {
        dropdown.classList.toggle("active");
      });
    }
  });

  // Load content dynamically (AJAX)
  function loadContent(page) {
    const main = document.querySelector("main");
    fetch(page)
      .then(response => {
        if (!response.ok) throw new Error("Network response was not OK");
        return response.text();
      })
      .then(data => {
        main.innerHTML = data;
      })
      .catch(error => {
        main.innerHTML = "<p>Error loading content. Please try again later.</p>";
        console.error("Error:", error);
      });
  }

  // Scroll to first highlighted card if search query exists
  (function() {
    const params = new URLSearchParams(window.location.search);
    const q = params.get('query');
    if (q && q.trim().length > 0) {
      const first = document.querySelector('.card.highlight');
      if (first) {
        setTimeout(() => first.scrollIntoView({ behavior: 'smooth', block: 'center' }), 100);
      }
    }
  })();

  // Clear search: remove query param and reset highlights
  (function() {
    const clearBtn = document.getElementById('dashSearchClear');
    const input = document.getElementById('dashSearchInput');
    const form = document.getElementById('dashSearchForm');
    if (clearBtn && input && form) {
      clearBtn.addEventListener('click', function() {
        window.location.href = 'user-dashboard.php';
      });
      form.addEventListener('submit', function(e) {
        if (!input.value || input.value.trim() === '') {
          e.preventDefault();
          window.location.href = 'user-dashboard.php';
        }
      });
      input.addEventListener('keydown', function(ev) {
        if ((ev.key === 'Enter' || ev.keyCode === 13) && input.value.trim() === '') {
          ev.preventDefault();
          window.location.href = 'user-dashboard.php';
        }
      });
    }
  })();
</script>

<?php include 'footer1.php'; ?>
</body>
</html>

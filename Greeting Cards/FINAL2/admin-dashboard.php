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
  <meta charset="UTF-8">
  <title>Admin Dashboard | heycardy1</title>
  <style>



.dropdown {
  position: relative;
}

.dropdown-content {
  display: none;
  flex-direction: column;
  position: relative;
  left: 10px;
  background-color: #fff;
  padding-left: 10px;
}

.dropdown-content a {
  padding: 8px 20px;
  font-size: 15px;
  color: #444;
  text-decoration: none;
}

.dropdown-content a:hover {
  background-color: #f0f0f0;
}

.dropdown.show .dropdown-content {
  display: flex;
}




    * {
      box-sizing: border-box;
      margin: 0;
      padding: 0;
    }

    body {
      font-family: Comic Sans MS;
      background-color: white;
      transition: margin-left 0.3s ease;
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

    nav {
      position: fixed;
      top: 90px;
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

    main {
      margin-top: 130px;
      padding: 20px;
      min-height: 400px;
      transition: margin-left 0.3s ease;
    }

    main.shifted {
      margin-left: 200px;
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
  margin-left: 200px; /* This matches sidebar width */
}


    .logout {
      color: red;
      font-weight: bold;
    }

    @media (max-width: 600px) {
      header h1 {
        font-size: 18px;
      }

      .menu-icon {
        font-size: 22px;
        top: 10px;
      }

      .search-form input {
        width: 140px;
      }

      header {
        flex-direction: column;
        align-items: flex-start;
      }
    }
  </style>
</head>
<body>

<header>
  <div class="menu-icon" id="menuToggle" onclick="toggleNav()" aria-expanded="false">☰</div>
  <img src="image/logo11.png" class="logo" alt="Greeting Cards Logo">
  <h1>Admin Dashboard</h1>
  </header>

<nav id="sidebar">
  <a href="#" onclick="loadPage('admin-dashboard.php'); return false;">🏠 Dashboard</a>
  



  <a href="add_card.php" class="add-card-btn">➕ Add New Card</a>

<a href="view_card.php" onclick="loadPage('view_card.php'); return false;">👀 View Cards</a>


  <a href="manage-user.php" onclick="loadPage('manage-user.php'); return false;">👤 Manage Users</a>
  <a href="manage-cart.php" onclick="loadPage('manage-cart.php'); return false;">🛒 Manage Cart</a>
  <a href="manage-orders.php" onclick="loadPage('manage-orders.php'); return false;">📦 Manage Orders</a>

 <a href="manage-payment.php" onclick="loadPage('manage-payment.php'); return false;">💲Manage payment</a>


  <a href="logout.php" class="logout">🚪 Logout</a>
</nav>

<main id="mainContent">
  <h2>Welcome, Admin!</h2>
  <p>Select an action from the sidebar to get started.</p>
</main>



<?php if (isset($_GET['deleted'])): ?>
    <p style="color: red;">Card deleted successfully!</p>
<?php elseif (isset($_GET['updated'])): ?>
    <p style="color: green;">Card updated successfully!</p>
<?php endif; ?>


<script>
  function toggleNav() {
    const sidebar = document.getElementById("sidebar");
    const main = document.getElementById("mainContent");
    const footer = document.getElementById("footer");

    const isOpen = sidebar.classList.toggle("show");
    main.classList.toggle("shifted", isOpen);
    footer.classList.toggle("shifted", isOpen);

    document.getElementById("menuToggle").setAttribute("aria-expanded", isOpen);
  }

  function loadPage(page) {
    fetch(page)
      .then(res => res.text())
      .then(html => {
        document.getElementById("mainContent").innerHTML = html;
      })
      .catch(err => {
        document.getElementById("mainContent").innerHTML = "<p>Error loading content.</p>";
        console.error(err);
      });
  }





function toggleDropdown() {
  const dropdown = document.querySelector('.dropdown');
  dropdown.classList.toggle('show');
}

function toggleDropdown(event) {
  event.preventDefault();
  const dropdown = document.querySelector('.dropdown');
  dropdown.classList.toggle('show');
}





</script>

</body>
</html>

<?php include 'footer1.php'; ?>





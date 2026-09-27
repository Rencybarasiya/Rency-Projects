<?php
$con=mysqli_connect("localhost","root","","flowercrafts");
if (mysqli_connect_errno()) 
{
    echo "Failed to connect...".mysqli_error();
    exit();
}
?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Floreva</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <style>
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
    }
    body {
      font-family: 'Segoe UI', sans-serif;
      background: url("flbg.jpg") no-repeat center center/cover;
      color: #4a2c35;
      min-height: 100vh;
      display: flex;
      flex-direction: column;
    }

    /* HEADER */
    header {
      background: rgba(255, 255, 255, 0.3); /* slight transparent overlay */
      backdrop-filter: blur(6px);
      position: sticky;
      top: 0;
      z-index: 1000;
    }
    nav {
      display: flex;
      justify-content: space-between;
      align-items: center;
      padding: 12px 50px;
    }
    nav img { height: 55px; }
    nav ul {
      list-style: none;
      display: flex;
      align-items: center;
      gap: 25px;
    }
    nav ul li {
      position: relative;
      font-weight: 600;
      color: #5a1a35;
      cursor: pointer;
      transition: color 0.3s;
    }
    nav ul li:hover { color: #d63384; }

    /* Floral Boutique dropdown */
    nav ul li ul {
      position: absolute;
      top: 40px;
      left: 0;
      background: #fff0f5;
      border-radius: 12px;
      padding: 10px 0;
      display: block;
      opacity: 0;
      visibility: hidden;
      transform: translateY(10px);
      transition: all 0.3s ease;
      min-width: 240px;
      box-shadow: 0px 6px 15px rgba(0, 0, 0, 0.12);
    }
    nav ul li:hover > ul {
      opacity: 1;
      visibility: visible;
      transform: translateY(0);
    }

    /* Dropdown items */
    nav ul li ul li {
      padding: 10px 15px;
      color: #663147;
      font-size: 0.95rem;
      transition: all 0.3s ease;
      border-radius: 6px;
      white-space: nowrap;
    }
    nav ul li ul li:hover {
      background: #ffd6e0;
      color: #b03060;
    }

    /* Sub Dropdown (Saplings → Indoor/Outdoor) */
    nav ul li ul li ul {
      position: absolute;
      top: 0;
      left: 100%;
      background: #ffe6f0;
      border-radius: 10px;
      padding: 10px 0;
      display: block;
      opacity: 0;
      visibility: hidden;
      transform: translateX(10px);
      transition: all 0.3s ease;
      min-width: 180px;
      box-shadow: 0px 6px 15px rgba(0, 0, 0, 0.1);
    }
    nav ul li ul li:hover > ul {
      opacity: 1;
      visibility: visible;
      transform: translateX(0);
    }

    /* Sub-dropdown items */
    nav ul li ul li ul li {
      padding: 8px 12px;
      font-size: 0.9rem;
      color: #5a1a35;
      border-radius: 6px;
      transition: all 0.3s ease;
    }
    nav ul li ul li ul li:hover {
      background: #ffd6e0;
      color: #d63384;
    }

    /* Nav Links */
    nav ul li a {
      text-decoration: none;
      color: #5a1a35;
      transition: color 0.3s ease;
    }
    nav ul li a:hover { color: #d63384; }

    /* Icons */
    .icons {
      display: flex;
      gap: 20px;
      margin-left: 30px;
    }
    .icons a {
      text-decoration: none;
      color: #5a1a35;
      font-weight: 500;
      transition: all 0.3s ease;
    }
    .icons a:hover {
      color: #d63384;
      transform: scale(1.05);
    }
    .icons {
      display: flex;
      gap: 20px;
      margin-left: 30px;
    }
    .icons a {
      text-decoration: none;
      color: #5a1a35;
      font-weight: 500;
    }
    .icons a:hover {
      color: #d63384;
      transform: scale(1.05);
    }

    /* ABOUT SECTION */
.about {
  padding: 80px 20px;
  background: rgba(255, 255, 255, 0.6); /* soft overlay on bg */
  backdrop-filter: blur(6px);
  text-align: center;
}

.about-container {
  max-width: 1200px;
  margin: auto;
}

.about-intro h1 {
  font-size: 2.8rem;
  color: #b03060;
  margin-bottom: 15px;
  text-shadow: 1px 1px 4px rgba(255,255,255,0.7);
}

.about-intro p {
  font-size: 1.2rem;
  color: #4a2c35;
  max-width: 800px;
  margin: auto;
  margin-bottom: 40px;
  line-height: 1.8;
}

/* About Cards */
.about-cards {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
  gap: 25px;
}

.about-cards .card {
  background: #fff0f5;
  padding: 25px;
  border-radius: 18px;
  box-shadow: 0px 6px 15px rgba(0,0,0,0.1);
  transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.about-cards .card:hover {
  transform: translateY(-6px);
  box-shadow: 0px 12px 20px rgba(0,0,0,0.15);
}

.about-cards h2 {
  color: #d63384;
  font-size: 1.5rem;
  margin-bottom: 12px;
}

.about-cards p, 
.about-cards ul {
  font-size: 1rem;
  color: #4a2c35;
  line-height: 1.6;
}

.about-cards ul {
  list-style: none;
  padding: 0;
}

.about-cards ul li {
  margin-bottom: 8px;
}


    /* FOOTER */
    .footer {
      background: rgba(255, 255, 255, 0.3);
      backdrop-filter: blur(6px);
      padding: 30px 50px;
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
      gap: 30px;
      text-align: left;
    }
    .footer h3 {
      color: #d63384;
      margin-bottom: 10px;
    }
    .footer a {
      text-decoration: none;
      color: #4a2c35;
      transition: all 0.3s;
    }
    .footer a:hover {
      color: #d63384;
    }
    .footer i {
      margin-right: 8px;
      color: #d63384;
    }
  </style>
</head>
<body>
  <?php include 'header.php'; ?>
  <!-- ABOUT FLORANEST SECTION -->
    <section class="about">
      <div class="about-container">
        <div class="about-intro">
          <h1>🌸 About Floreva 🌸</h1>
          <p>"Floreva is where nature blossoms into art – bringing you blooms, gifts, and greenery to make every moment unforgettable."</p>
        </div>

        <div class="about-cards">
          <div class="card">
            <h2>Our Mission</h2>
            <p> "Floreva is where nature blossoms into art – bringing you blooms, gifts, and greenery to make every moment unforgettable."</p>
          </div>
          <div class="card">
            <h2>Our Vision</h2>
            <p>"To be the go-to floral destination that turns emotions into timeless expressions."</p>
          </div>
          <div class="card">
            <h2>Why Choose Floreva?</h2>
            <p>"Unique designs, fresh blooms, thoughtful gifting, and a personal touch – all delivered with love."</p>
          </div>
          <div class="card">
            <h2>Services</h2>
            <ul>
              <li>Free Shipping in city</li>
              <li>Rs. 50 Shipping charges in all over india</li>
            </ul>
          </div>
        </div>
      </div>
    </section>
    <?php include 'footer.php'; ?>
</body>
</html>

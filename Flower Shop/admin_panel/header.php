<!-- header.php -->
<header class="header">
    <div class="logo-container">
        <img src="Floreva.png" alt="Floreva Logo" class="logo">
        <h1>Floreva Admin Panel</h1>
    </div>
    <nav class="nav-links">
        <a href="dashboard.php">Dashboard</a>
        <a href="products.php">Products</a>
        <a href="order_admin.php">Orders</a>
        <a href="user.php">Users</a>
        <a href="feedback_admin.php">Feedback</a>
        <a href="logout.php" class="logout">Logout</a>
    </nav>
</header>

<style>
    .header {
        background: #ffe4ec;
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 10px 30px;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        position: sticky;
        top: 0;
        z-index: 10;
    }
    .logo-container {
        display: flex;
        align-items: center;
        gap: 10px;
    }
    .logo {
        height: 50px;
        width: 50px;
        border-radius: 50%;
        object-fit: contain;
    }
    .header h1 {
        color: #d63384;
        font-size: 22px;
        margin: 0;
    }
    .nav-links a {
        margin: 0 10px;
        text-decoration: none;
        font-weight: bold;
        color: #5a1a35;
        transition: color 0.3s, background 0.3s;
        padding: 6px 10px;
        border-radius: 6px;
    }
    .nav-links a:hover {
        background: #ffb6c1;
        color: white;
    }
    .logout {
        background: #ff4d6d;
        color: white !important;
    }
    .logout:hover {
        background: #d63384;
    }
</style>

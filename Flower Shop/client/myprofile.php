<?php
session_start();
$con = mysqli_connect("localhost", "root", "", "flowercrafts");

// Check database connection
if (mysqli_connect_errno()) {
    echo "Failed to connect to MySQL: " . mysqli_connect_error();
    exit();
}

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login_user.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Fetch user details (TABLE NAME: user)
$query = "SELECT * FROM users WHERE id = '$user_id' LIMIT 1";
$result = mysqli_query($con, $query);

if (mysqli_num_rows($result) > 0) {
    $user = mysqli_fetch_assoc($result);
} else {
    echo "User not found!";
    exit();
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>My Profile | Floreva</title>
    <style>
        body {
            margin: 0;
            font-family: 'Segoe UI', sans-serif;
            background-color: #fff0f5;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }
        .profile-container {
            flex: 1;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 20px;
        }
        .profile-card {
            background: #fff;
            padding: 25px;
            border-radius: 15px;
            box-shadow: 0 4px 10px rgba(0,0,0,0.1);
            text-align: center;
            max-width: 400px;
            width: 100%;
        }
        .profile-card img {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            object-fit: cover;
            margin-bottom: 15px;
            border: 3px solid #d63384;
        }
        .profile-card h2 {
            margin: 0 0 10px;
            color: #d63384;
        }
        .profile-card p {
            margin: 5px 0;
            font-size: 16px;
        }
        .logout-btn {
            display: inline-block;
            margin-top: 15px;
            padding: 10px 20px;
            background-color: #d63384;
            color: white;
            text-decoration: none;
            border-radius: 8px;
            transition: 0.3s;
        }
        .logout-btn:hover {
            background-color: #b52d6f;
        }
    </style>
</head>
<body>
    <?php include 'header.php'; ?>

    <div class="profile-container">
        <div class="profile-card">
            <?php if (!empty($user['image'])): ?>
                <img src="photos/<?php echo htmlspecialchars($user['image']); ?>" alt="Profile Image">
            <?php else: ?>
                <img src="photos/default.png" alt="Default Profile">
            <?php endif; ?>

            <h2><?php echo htmlspecialchars($user['name']); ?></h2>
            <p><strong>Email:</strong> <?php echo htmlspecialchars($user['email']); ?></p>
            <p><strong>Mobile:</strong> <?php echo htmlspecialchars($user['mobile_number']); ?></p>
            <a href="logout.php" class="logout-btn">Logout</a>
        </div>
    </div>

    <?php include 'footer.php'; ?>
</body>
</html>

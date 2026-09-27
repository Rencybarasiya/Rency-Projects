<?php
session_start();

/* ------------------------ DB CONNECTION ------------------------ */
$conn = mysqli_connect("localhost", "root", "", "heycardy1");
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

/* ------------------------ HANDLER ------------------------ */
$error   = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Grab & sanitize
    $first   = trim($_POST['first']    ?? '');
    $last    = trim($_POST['last']     ?? '');
    $email   = trim($_POST['email']    ?? '');
    $user    = trim($_POST['username'] ?? '');
    $pass    = $_POST['password']      ?? '';
    $confirm = $_POST['confirm']       ?? '';

    // Basic validation
    if (!$first || !$email || !$user || !$pass) {
        $error = 'Please fill in all fields.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Invalid email format.';
    } elseif ($pass !== $confirm) {
        $error = 'Passwords do not match.';
    } else {
        // Check if username is taken
        $stmt = mysqli_prepare($conn, "SELECT id FROM user WHERE username = ?");
        mysqli_stmt_bind_param($stmt, 's', $user);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_store_result($stmt);

        if (mysqli_stmt_num_rows($stmt) > 0) {
            $error = 'That username is already taken.';
        } else {
            // Hash password & insert into DB
            $hash = password_hash($pass, PASSWORD_DEFAULT);
            $stmt = mysqli_prepare($conn, 
                "INSERT INTO user (first_name, last_name, email, username, password_hash)
                 VALUES (?, ?, ?, ?, ?)"
            );
            mysqli_stmt_bind_param($stmt, 'sssss', $first, $last, $email, $user, $hash);

            if (mysqli_stmt_execute($stmt)) {
                header("Location: login.php");
                exit();
            } else {
                $error = 'Something went wrong. Please try again.';
            }
        }
        mysqli_stmt_close($stmt);
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Register | Greeting Cards</title>
    <style>
        body, html {
            margin: 0;
            padding: 0;
            height: 100%;
            font-family: Comic Sans MS;
            overflow: hidden;
        }

        .bg {
            position: absolute;
            top: 0; left: 0;
            width: 100%; height: 100%;
            background: url('image/greeting-cards.jpg') no-repeat center/cover;
            filter: blur(3px);
            z-index: -2;
        }

        .overlay {
            position: absolute;
            top: 0; left: 0;
            width: 100%; height: 100%;
            backdrop-filter: blur(1px) brightness(1.1);
            background: rgba(255, 255, 255, 0.2);
            z-index: -1;
        }

        .card-container {
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .card {
            background: rgba(255, 255, 255, 0.35);
            backdrop-filter: blur(2px);
            border-radius: 18px;
            padding: 45px 35px;
            width: 400px;
            text-align: center;
            box-shadow: 0 10px 24px rgba(0, 0, 0, 0.22);
        }

        .card h2 {
            margin: 0 0 6px;
            color: #333;
        }

        .card p {
            margin: 0 0 22px;
            color: #333;
        }

        .card input {
            width: 90%;
            padding: 12px;
            margin: 9px 0;
            border: 1px solid #ccc;
            border-radius: 9px;
            font-size: 15px;
        }

        .card button {
            padding: 12px 0;
            width: 100%;
            border: none;
            border-radius: 10px;
            background: #a6c1ee;
            color: #fff;
            font-size: 17px;
            cursor: pointer;
            transition: 0.3s;
        }

        .card button:hover {
            background: #849be4;
        }

        .msg-error {
            color: #df2424;
            margin-top: 10px;
        }

        .msg-success {
            color: #0a8c3a;
            margin-top: 10px;
        }

        .greeting-icon {
            font-size: 42px;
            margin-bottom: 8px;
        }

        .small-link {
            display: block;
            margin-top: 18px;
            font-size: 14px;
            color: #333;
            text-decoration: none;
        }

        .small-link:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>

<div class="bg"></div>
<div class="overlay"></div>

<div class="card-container">
    <div class="card">
        <div class="greeting-icon">🎉</div>
        <h2>Create Your Account</h2>
        <p>Join our Greeting Cards community</p>
        
        <form method="POST">
            <input type="text" name="first" placeholder="First Name" 
                   value="<?= htmlspecialchars($_POST['first'] ?? '') ?>" required>
            <input type="text" name="last" placeholder="Last Name" 
                   value="<?= htmlspecialchars($_POST['last'] ?? '') ?>" required>
            <input type="email" name="email" placeholder="Email" 
                   value="<?= htmlspecialchars($_POST['email'] ?? '') ?>" required>
            <input type="text" name="username" placeholder="Username" 
                   value="<?= htmlspecialchars($_POST['username'] ?? '') ?>" required>
            <input type="password" name="password" placeholder="Password" required>
            <input type="password" name="confirm" placeholder="Confirm Password" required>
            <button type="submit">Register</button>
        </form>

        <?php if ($error): ?>
            <div class="msg-error"><?= $error ?></div>
        <?php elseif ($success): ?>
            <div class="msg-success"><?= $success ?></div>
        <?php endif; ?>

        <a class="small-link" href="login.php">← Already have an account? Login</a>
    </div>
</div>

</body>
</html>

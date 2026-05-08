<?php
session_start();

$errors = [];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username         = trim($_POST['username'] ?? '');
    $class            = trim($_POST['class'] ?? '');
    $password         = $_POST['password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';

    // 🛡️ Basic Validation
    if (empty($username)) {
        $errors[] = "Username is required.";
    }
    if (empty($class)) {
        $errors[] = "Class is required.";
    }
    if (empty($password)) {
        $errors[] = "Password is required.";
    }
    if ($password !== $confirm_password) {
        $errors[] = "Passwords do not match.";
    }

    if (empty($errors)) {
        $conn = new mysqli("localhost", "root", "", "fees");
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        $tableName = "student" . intval($class); // Dynamically select table (student1, student2...)

        // 1️⃣ CHECK if username exists in that class table as a real student
        $check_student = $conn->prepare("SELECT id FROM `$tableName` WHERE name = ?");
        if (!$check_student) {
            die("Prepare failed: " . $conn->error);
        }
        $check_student->bind_param("s", $username);
        $check_student->execute();
        $check_student->store_result();

        if ($check_student->num_rows === 0) {
            $errors[] = "No student with this name found in Class $class!";
        } else {
            // 2️⃣ CHECK if this username already registered in login table
            $check = $conn->prepare("SELECT id FROM students WHERE username = ? AND class = ?");
            $check->bind_param("ss", $username, $class);
            $check->execute();
            $check->store_result();

            if ($check->num_rows > 0) {
                $errors[] = "This Username is already registered for Class $class!";
            } else {
                // 3️⃣ INSERT into `students` login table
                $hashed_pass = password_hash($password, PASSWORD_BCRYPT);
                $stmt = $conn->prepare("INSERT INTO students (username, class, password) VALUES (?, ?, ?)");
                if (!$stmt) {
                    die("Prepare failed: " . $conn->error);
                }
                $stmt->bind_param("sss", $username, $class, $hashed_pass);
                if ($stmt->execute()) {
                    header("Location: login.php?registered=1");
                    exit();
                } else {
                    $errors[] = "Error: " . $stmt->error;
                }
                $stmt->close();
            }
            $check->close();
        }

        $check_student->close();
        $conn->close();
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Register</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
        *, *:before, *:after {padding:0; margin:0; box-sizing:border-box;}
        body {background-color: #151632;}
        .background {
            width: 430px; height: 620px;
            position: absolute;
            transform: translate(-50%, -50%);
            left: 50%; top: 50%;
        }
        .background .shape {
            height: 210px; width: 210px;
            position: absolute;
            border-radius: 50%;
        }
        .shape:first-child {background: linear-gradient(#264db7, #1b36b7); left:-95px; top:-75px;}
        .shape:last-child {background: linear-gradient(to left, red, #f09819); right:-95px; bottom:-70px;}
        form {
            height: 550px; width: 400px;
            background-color: rgba(255,255,255,0.13);
            position: absolute;
            transform: translate(-50%, -50%);
            top: 50%; left: 50%;
            border-radius: 30px;
            backdrop-filter: blur(10px);
            border: 2px solid rgba(255,255,255,0.1);
            box-shadow: 0 0 50px rgba(8,7,16,0.6);
            padding: 50px 35px;
        }
        form * {font-family: Comic Sans MS; color:#ffffff; outline:none; border:none;}
        form h1 {font-size: 36px; font-weight: 200; line-height: 80px; text-align: center;}
        .input-container {
            position: relative;
            margin-top: 8px;
            padding: 1px;
            top: 5px;
        }
        .input-container i {
            position: absolute;
            left: 15px;
            top: 58%;
            transform: translateY(-50%);
            color: #2a2f32;
        }
        .input-container input,
        .input-container select {
            font-family: Comic Sans MS;
            width: 100%;
            padding: 10px 10px 10px 50px;
            height: 45px;
            background-color: rgba(255,255,255,0.07);
            border-radius: 8px;
            margin-top: 10px;
            font-size: 18px;
            font-weight: 200;
            color: #fff;
        }
        .input-container select option {background-color: #2c2f48; color: #fff;}
        ::placeholder {color: #d5dee5;}
        button {
            margin-top: 20px;
            width: 120px;
            left: 110px;
            position: relative;
            text-align: center;
            height: 45px;
            background-color: #ffffff;
            color: #211c10;
            padding: 4px;
            font-size: 22px;
            font-weight: 600;
            border-radius: 6px;
            cursor: pointer;
        }
        .already-account {
            display: block;
            text-align: center;
            margin-top: 15px;
            font-size: 16px;
            color: rgba(255, 255, 255, 0.5); /* blurred */
            text-decoration: none;
            transition: color 0.3s ease;
        }
        .already-account:hover {
            color: white; /* turn white on hover */
        }
        .error {margin-top: 10px; text-align: center; color: red;}
    </style>
</head>
<body>
    <div class="background">
        <div class="shape"></div>
        <div class="shape"></div>
    </div>

    <form method="POST" action="">
        <h1>Register</h1>

        <?php if (!empty($errors)): ?>
            <div class="error"><?= implode('<br>', array_map('htmlspecialchars', $errors)); ?></div>
        <?php endif; ?>

        <!-- USERNAME -->
        <div class="input-container">
            <i class="fas fa-user"></i>
            <input type="text" name="username" placeholder="Username" required>
        </div>

        <!-- CLASS -->
        <div class="input-container">
            <i class="fas fa-school"></i>
            <select name="class" required>
                <option value="">Select Class</option>
                <?php for ($i = 1; $i <= 12; $i++): ?>
                    <option value="<?= $i ?>">Class <?= $i ?></option>
                <?php endfor; ?>
            </select>
        </div>

        <!-- PASSWORD -->
        <div class="input-container">
            <i class="fas fa-lock"></i>
            <input type="password" name="password" placeholder="Password" required>
        </div>

        <!-- CONFIRM PASSWORD -->
        <div class="input-container">
            <i class="fas fa-lock"></i>
            <input type="password" name="confirm_password" placeholder="Confirm Password" required>
        </div>

        <button type="submit">Register</button>

        <!-- Already have account link -->
        <a href="login.php" class="already-account">Already have an account? Login</a>

    </form>
</body>
</html>

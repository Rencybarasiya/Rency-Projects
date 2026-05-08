<?php
session_start();

if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit();
}

$conn = mysqli_connect("localhost", "root", "", "fees");
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Save selected class to session
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['selected_class'])) {
    $_SESSION['selected_class'] = $_POST['selected_class'];
}

// Fetch all classes from class_list
$classes = mysqli_query($conn, "SELECT * FROM class_list");

// Get selected class
$selected_class = $_SESSION['selected_class'] ?? null;

// Access control logic using switch
$access_allowed = false;
switch ($selected_class) {
    case "Class 1":
    case "Class 2":
    case "Class 3":
    case "Class 4":
    case "Class 5":
    case "Class 6":
    case "Class 7":
    case "Class 8":
    case "Class 9":
    case "Class 10":
    case "Class 11":
    case "Class 12":
        $access_allowed = true;
        break;
    default:
        $access_allowed = false;
        break;
}

// Determine student page based on class
function getStudentPage($className) {
    $map = [
        "Class 1" => "student1.php",
        "Class 2" => "student2.php",
        "Class 3" => "student3.php",
        "Class 4" => "student4.php",
        "Class 5" => "student5.php",
        "Class 6" => "student6.php",
        "Class 7" => "student7.php",
        "Class 8" => "student8.php",
        "Class 9" => "student9.php",
        "Class 10" => "student10.php",
        "Class 11" => "student11.php",
        "Class 12" => "student12.php",
    ];

    return $map[$className] ?? "students.php"; 
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Welcome - Fees Management System</title>
    <style>
        label {
    font-size: 20px;
    /*font-weight: bold;
   display: inline-block; */
    margin-bottom: 8px;
}


        body {
            font-family: Comic Sans MS;
            background-color: #e3eef1ff;
            margin: 0;
            padding: 0;
        }

        .container {
            text-align: center;
            padding: 50px;
        }

        h1 {
            margin-top: 10px;
            font-size: 45px;
            color: #202460;
        }

        form {
            margin-bottom: 30px;
        }

        select {
            padding: 10px 20px;
            font-size: 16px;
        }

        .nav-buttons {
            margin-top: 40px;
        }

        .nav-buttons a {
            display: inline-block;
            margin: 10px;
            padding: 12px 25px;
            background-color: #2d89ef;
            color: white;
            text-decoration: none;
            border-radius: 6px;
            font-size: 20px;
        }

        .nav-buttons a:hover {
            background-color: #1b5dbf;
        }

        .logout {
            background-color: #c0392b !important;
        }

        .top-right-image {
            position: absolute;
            top: 10px;
            right: 10px;
            z-index: 1000;
        }

        .top-right-image img {
            height: 50px;
            width: auto;
            border-radius: 5px;
        }


.top-right-image {
  position: absolute;
  top: 10px;
  right: 10px;
  z-index: 1000;
}

.top-right-image img {
  height: 60px; /* Adjust size as needed */
  width: auto;
  border-radius: 5px; /* optional for rounded corners */
}


    </style>

    <script>
        // Auto-submit the form when class is selected
        function autoSubmitForm() {
            document.getElementById("classForm").submit();
        }
    </script>
</head>
<body>

<div class="top-right-image">
    <img src="evil.jpg" alt="evil-eye Image">
</div>

<div class="container">
    <h1>Welcome, <?= htmlspecialchars($_SESSION['user']); ?> 👋</h1>

    <form method="POST" id="classForm" action="">
        <label for="selected_class"><strong>Select a Class:</strong></label><br><br>
        <select name="selected_class" onchange="autoSubmitForm()" required>
            <option value="">-- Choose Class --</option>
            <?php while ($row = mysqli_fetch_assoc($classes)) { ?>
                <option value="<?= htmlspecialchars($row['class_name']) ?>"
                    <?= ($selected_class === $row['class_name']) ? 'selected' : '' ?>>
                    <?= htmlspecialchars($row['class_name']) ?>
                </option>
            <?php } ?>
        </select>
        <!-- Submit button removed -->
    </form>

    <?php if ($selected_class): ?>
        <h3>📘 Class Selected: <?= htmlspecialchars($selected_class); ?></h3>
        <div class="nav-buttons">
            <a href="<?= getStudentPage($selected_class); ?>">Manage Students</a>
            <a href="student_by_class.php">View/Add Fees</a>
       
            <a class="logout" href="logout.php">Logout</a>
        </div>
    <?php endif; ?>
</div>

</body>
</html>

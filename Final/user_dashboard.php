<?php
session_start();

// 1️⃣ Check login session
if (!isset($_SESSION['username']) || !isset($_SESSION['class'])) {
    header("Location: login.php");
    exit();
}

// 2️⃣ Fetch session values
$username = $_SESSION['username'];
$class = $_SESSION['class'];
$table = "student" . intval($class);

// 3️⃣ Connect DB
$conn = mysqli_connect("localhost", "root", "", "fees");
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// 4️⃣ Fetch student data
$sql = "SELECT id, name, class FROM `$table` WHERE name = ?";
$stmt = mysqli_prepare($conn, $sql);
if (!$stmt) {
    die("Prepare failed: " . mysqli_error($conn));
}
mysqli_stmt_bind_param($stmt, "s", $username);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

if (mysqli_num_rows($result) === 0) {
    die("Student not found.");
}

$student = mysqli_fetch_assoc($result);
$student_id = $student['id'];
mysqli_stmt_close($stmt);

// 5️⃣ Fetch payment data
$sqlP = "SELECT * FROM payments WHERE student_id = ? ORDER BY paid_on ASC";
$stmtP = mysqli_prepare($conn, $sqlP);
mysqli_stmt_bind_param($stmtP, "i", $student_id);
mysqli_stmt_execute($stmtP);
$payments = mysqli_stmt_get_result($stmtP);

$totalPaid = 0;
$totalInstallments = mysqli_num_rows($payments);
$paymentRows = [];
while ($p = mysqli_fetch_assoc($payments)) {
    $totalPaid += $p['paid_amount'];
    $paymentRows[] = $p;
}

mysqli_stmt_close($stmtP);
?>
<!DOCTYPE html>
<html>
<head>
    <title>User Dashboard</title>
    <style>
         h1 {
            font-size: 45px;
            color: #202460;
        }
        body {
            font-family: "Comic Sans MS", cursive, sans-serif;
            background-color: #e3eef1ff;
            margin-top: 10;
            padding: 0;
        }

        /* 🔹 Top Bar - Only Welcome Text */
        .top-bar {
            text-align: center;
            padding: 15px 30px;
            background: transparent;
        }

        .top-bar h1 {
            font-size: 2rem;
            color: #003366;
            margin: 0;
        }

        .btn {
            display: inline-block;
            padding: 8px 18px;
            font-weight: bold;
            border-radius: 8px;
            text-decoration: none;
            color: white;
        }

.logout-container {
    text-align: center;
    margin: 25px 0;
}

.btn-red {
    background-color: #dc3545;
    color: white;
font-size: 20px;
    font-weight: bold;
    text-decoration: none;
    padding: 10px 20px;
    border-radius: 8px;
    display: inline-block;
    transition: background 0.3s ease-in-out;
}

.btn-red:hover {
    background-color: #a71d2a;
}
 
        .btn-red { background-color: #dc3545; }
        .btn-red:hover { background-color: #a71d2a; }

        /* 🔹 Summary center ma perfect */
        .summary {
            background: #f0f8ff;
            padding: 15px;
            border-radius: 10px;
            display: inline-block;
            font-size: 1.1rem;
            margin: 20px auto;
            box-shadow: 0px 3px 8px rgba(0,0,0,0.1);
            text-align: center;
        }

        /* 🔹 Table Styling */
        table {
            width: 90%;
            margin: 0 auto;
            border-collapse: collapse;
            font-size: 1rem;
            background: white;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0px 3px 8px rgba(0,0,0,0.1);
        }

        table th, table td {
            border: 1px solid #ccc;
            padding: 10px;
            text-align: center;
        }

        table th {
            background: #ddeeff;
            color: #003366;
        }

        .btn-download {
            display:inline-block;
            background:#198754;
            color:white;
            padding:5px 12px;
            border-radius:5px;
            text-decoration:none;
        }
        .btn-download:hover { background:#145c32; }

        /* 🔹 Centered Logout */
        .logout-container {
            text-align: center;
            margin: 25px 0;
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
</head>
<body>

<div class="top-right-image">
    <img src="evil.jpg" alt="evil-eye Image">
</div>


    <!-- 🔹 Top Bar with Only Welcome -->
    <div class="top-bar">
        <h1>Welcome, <?= htmlspecialchars($student['name']) ?>! 👋</h1>
    </div>

    <!-- 🔹 Centered Class Info -->
    <div style="text-align:center;">
        <div class="summary">
            <strong>Class:</strong> <?= htmlspecialchars($class) ?><br>
            <strong>Total Installments Paid:</strong> <?= $totalInstallments ?><br>
            <strong>Total Fees Paid:</strong> ₹ <?= number_format($totalPaid, 2) ?>
        </div>
    </div>

    <h2 style="text-align:center;">Your Payment Details</h2>
    <table>
        <tr>
            <th>Installment No</th>
            <th>Paid On</th>
            <th>Mode</th>
            <th>Amount</th>
            <th>Receipt</th>
        </tr>
        <?php if (!empty($paymentRows)): ?>
            <?php foreach ($paymentRows as $p): ?>
                <tr>
                    <td><?= htmlspecialchars($p['installment_no']) ?></td>
                    <td><?= date("d-m-Y", strtotime($p['paid_on'])) ?></td>
                    <td><?= htmlspecialchars($p['mode']) ?></td>
                    <td>₹ <?= number_format($p['paid_amount'], 2) ?></td>
                    <td>
                        <a class="btn-download"
                           href="download_receipt.php?class=<?= urlencode($class) ?>&student_id=<?= $student_id ?>&installment=<?= $p['installment_no'] ?>">
                           Download
                        </a>
                    </td>
                </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr><td colspan="5">No payments found</td></tr>
        <?php endif; ?>
    </table>

    <!-- 🔹 Logout Button Center -->
    <div class="logout-container">
        <a href="logout.php" class="btn btn-red">Logout</a>
    </div>

</body>
</html>
<?php mysqli_close($conn); ?>

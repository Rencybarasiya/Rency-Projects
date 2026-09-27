
<?php include 'header.php'; ?>



<?php
// manage-user.php

include 'db1.php'; // Make sure this file connects to your database

// Fetch users
$sql = "SELECT * FROM user";
$result = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<title>Manage Users</title>
<style>
    body {
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        background: linear-gradient(135deg, #ffe0e9, #fff0f5);
        padding: 40px;
        color: #444;
    }

    h2 {
        font-family: 'Pacifico', cursive;
        color: #cc3366;
        font-size: 36px;
        text-align: center;
        margin-bottom: 30px;
    }

    table {
        width: 100%;
        max-width: 900px;
        margin: 0 auto;
        border-collapse: separate;
        border-spacing: 0 12px;
        background: white;
        border-radius: 15px;
        box-shadow: 0 6px 20px rgba(204, 51, 102, 0.2);
        overflow: hidden;
    }

    th, td {
        padding: 15px 20px;
        text-align: left;
    }

    thead tr {
        background: #ffb3cc;
        color: white;
        font-weight: bold;
        font-size: 18px;
        border-radius: 15px 15px 0 0;
    }

    tbody tr {
        background: #fff0f5;
        border-radius: 10px;
        transition: background-color 0.3s ease;
        box-shadow: 0 2px 6px rgba(204, 51, 102, 0.1);
    }

    tbody tr:hover {
        background-color: #ffd1e1;
        box-shadow: 0 4px 12px rgba(204, 51, 102, 0.3);
    }

    tbody tr td:first-child {
        font-weight: 600;
        color: #cc3366;
        width: 50px;
    }

    a {
        text-decoration: none;
        padding: 6px 14px;
        border-radius: 8px;
        font-weight: 600;
        transition: background-color 0.3s ease;
        font-size: 14px;
    }

    a[href*="edit-user.php"] {
        background-color: #ff6699;
        color: white;
        margin-right: 8px;
    }

    a[href*="edit-user.php"]:hover {
        background-color: #e05585;
    }

    a[href*="delete-user.php"] {
        background-color: #cc3366;
        color: white;
    }

    a[href*="delete-user.php"]:hover {
        background-color: #a52a56;
    }

    /* Responsive */
    @media (max-width: 600px) {
        table, thead, tbody, th, td, tr {
            display: block;
        }
        thead tr {
            display: none;
        }
        tbody tr {
            margin-bottom: 15px;
            box-shadow: none;
            background: #fff0f5;
            border-radius: 15px;
            padding: 15px;
        }
        tbody tr td {
            text-align: right;
            padding-left: 50%;
            position: relative;
        }
        tbody tr td::before {
            content: attr(data-label);
            position: absolute;
            left: 15px;
            width: 45%;
            padding-left: 15px;
            font-weight: 700;
            text-align: left;
            color: #cc3366;
        }
        tbody tr td:first-child {
            text-align: left;
            font-weight: 700;
            color: #cc3366;
        }
    }
</style>
</head>
<body>

<h2>Manage Users</h2>

<table>
    <thead>
        <tr>
            <th>ID</th>
            <th>First Name</th>
            <th>Last Name</th>
            <th>Email</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
    <?php while ($row = mysqli_fetch_assoc($result)) { ?>
        <tr>
            <td data-label="ID"><?= $row['id'] ?></td>
            <td data-label="First Name"><?= htmlspecialchars($row['first_name']) ?></td>
            <td data-label="Last Name"><?= htmlspecialchars($row['last_name']) ?></td>
            <td data-label="Email"><?= htmlspecialchars($row['email']) ?></td>
            <td data-label="Action">
                
                <a href="delete-user.php?id=<?= $row['id'] ?>" onclick="return confirm('Are you sure to delete this user?');">❌ Delete</a>
            </td>
        </tr>
    <?php } ?>
    </tbody>
</table>

</body>
</html>


<?php include 'footer1.php'; ?>
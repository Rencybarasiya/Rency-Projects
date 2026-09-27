<?php
session_start();
$con = mysqli_connect("localhost", "root", "", "flowercrafts");

// Check connection
if (!$con) {
    die("DB Connection Failed: " . mysqli_connect_error());
}

// Admin session check
/*sif (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit();
}*/

// Fetch users
$query = "SELECT * FROM users";
$result = mysqli_query($con, $query);
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Admin - Users | Floreva</title>
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            margin: 0;
            background: #fff5f8;
        }
        h1 {
            text-align: center;
            margin: 20px 0;
            color: #d6336c;
        }
        .container {
            width: 90%;
            margin: auto;
            background: white;
            border-radius: 12px;
            padding: 20px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }
        th, td {
            text-align: center;
            padding: 10px;
            border-bottom: 1px solid #ddd;
        }
        th {
            background: #ffe6ef;
            color: #d6336c;
        }
        tr:hover {
            background: #fff0f5;
        }
        img {
            width: 60px;
            height: 60px;
            object-fit: cover;
            border-radius: 50%;
            border: 2px solid #ffd6e0;
        }
        .btn {
            text-decoration: none;
            padding: 6px 12px;
            border-radius: 6px;
            font-size: 14px;
            margin: 2px;
        }
        .btn-edit {
            background: #ff85a2;
            color: white;
        }
        .btn-edit:hover {
            background: #e66b89;
        }
        .btn-delete {
            background: #ff4d6d;
            color: white;
        }
        .btn-delete:hover {
            background: #cc2f4d;
        }
    </style>
</head>
<body>
    <?php include 'header.php'; ?>
    <h1>Manage Users</h1>
    <div class="container">
        <table>
            <tr>
                <th>ID</th>
                <th>Profile</th>
                <th>Name</th>
                <th>Mobile</th>
                <th>Email</th>
                <th>Actions</th>
            </tr>
            <?php
            if (mysqli_num_rows($result) > 0) {
                while ($row = mysqli_fetch_assoc($result)) {
                    echo "<tr>
                        <td>{$row['id']}</td>
                        <td><img src='photos/{$row['image']}' alt='User'></td>
                        <td>{$row['name']}</td>
                        <td>{$row['mobile_number']}</td>
                        <td>{$row['email']}</td>
                        <td>
                            <a href='delete_user.php?id={$row['id']}' class='btn btn-delete' onclick=\"return confirm('Are you sure you want to delete this user?');\">Delete</a>
                        </td>
                    </tr>";
                }
            } else {
                echo "<tr><td colspan='6'>No users found</td></tr>";
            }
            ?>
        </table>
    </div>
<?php include 'footer.php'; ?>
</body>
</html>

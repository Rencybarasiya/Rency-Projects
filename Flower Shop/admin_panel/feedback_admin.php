<?php
session_start();
$con = mysqli_connect("localhost", "root", "", "flowercrafts");

if (mysqli_connect_errno()) {
    echo "Failed to connect..." . mysqli_error();
    exit();
}

// Handle delete request
if (isset($_GET['delete_id'])) {
    $delete_id = intval($_GET['delete_id']);
    $del_query = "DELETE FROM message WHERE id='$delete_id'";
    mysqli_query($con, $del_query);
    header("Location: feedback_admin.php"); // refresh page after deletion
    exit();
}

?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin Feedback - Floreva</title>
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background-color: #fff0f5;
            padding: 30px;
        }
        h2 {
            text-align: center;
            color: #d14781;
        }
        table {
            width: 90%;
            margin: 20px auto;
            border-collapse: collapse;
            box-shadow: 0 4px 15px rgba(255, 182, 193, 0.4);
            border-radius: 15px;
            overflow: hidden;
        }
        th, td {
            padding: 12px 20px;
            text-align: left;
        }
        th {
            background-color: #ffb6c1;
            color: white;
        }
        tr:nth-child(even) {
            background-color: #fff8fa;
        }
        tr:hover {
            background-color: #ffe0f0;
        }
        a.delete-btn {
            background-color: #ff4c6d;
            color: white;
            padding: 6px 12px;
            text-decoration: none;
            border-radius: 8px;
            transition: 0.3s;
        }
        a.delete-btn:hover {
            background-color: #e03a57;
        }
    </style>
</head>
<body>
<?php include 'header.php'; ?>
        <h2>All User Feedback</h2>

        <table>
            <tr>
                <th>ID</th>
                <th>User Name</th>
                <th>Email</th>
                <th>Subject</th>
                <th>Message</th>
                <th>Rating</th>
                <th>Action</th>
            </tr>

            <?php
            $query = "SELECT * FROM message ORDER BY id DESC";
            $result = mysqli_query($con, $query);
            while ($row = mysqli_fetch_assoc($result)) {
                echo "<tr>";
                echo "<td>".$row['id']."</td>";
                echo "<td>".$row['name']."</td>";
                echo "<td>".$row['email']."</td>";
                echo "<td>".$row['subject']."</td>";
                echo "<td>".$row['message']."</td>";
                echo "<td>".$row['rating']."</td>";
                echo "<td><a class='delete-btn' href='feedback_admin.php?delete_id=".$row['id']."' onclick='return confirm(\"Are you sure to delete this feedback?\");'>Delete</a></td>";
                echo "</tr>";
            }
            ?>
        </table>
<?php include 'footer.php'; ?>
</body>
</html>

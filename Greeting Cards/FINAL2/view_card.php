<?php 
include 'header.php'; 

// Enable error reporting
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$conn = new mysqli("localhost", "root", "", "heycardy");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT * FROM cardss";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>View Cards - heycardy1</title>
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Dancing+Script&family=Poppins:wght@300;500&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(to right, #ffe4e1, #fff0f5);
            padding: 40px;
        }

        h2 {
            color: #d6336c;
            text-align: center;
            margin: 50px 0;
            font-size: 28px;
        }

        .add-card-btn {
            display: block;
            margin: 0 auto 30px;
            padding: 10px 20px;
            background-color: #ff69b4;
            color: white;
            border: none;
            border-radius: 20px;
            font-size: 16px;
            cursor: pointer;
            text-decoration: none;
            transition: 0.3s ease;
        }

        .add-card-btn:hover {
            background-color: #e055a2;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            background-color: white;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        }

        th, td {
            padding: 14px;
            text-align: center;
            border-bottom: 1px solid #f2f2f2;
        }

        th {
            background-color: #ffb6c1;
            color: white;
            font-weight: 500;
        }

        tr:hover {
            background-color: #fff5f8;
            transform: scale(1.01);
            transition: 0.3s ease;
        }

        img {
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.05);
            transition: transform 0.3s ease;
        }

        img:hover {
            transform: scale(1.05);
        }

        .action-btn {
            padding: 8px 14px;
            border: none;
            border-radius: 20px;
            font-size: 14px;
            cursor: pointer;
            margin: 0 5px;
            transition: all 0.2s ease-in-out;
        }

        .edit-btn {
            background-color: #4CAF50;
            color: white;
        }

        .delete-btn {
            background-color: #f44336;
            color: white;
        }

        .action-btn:hover {
            opacity: 0.85;
        }

        .action-form {
            display: inline-block;
        }
    </style>
</head>
<body>
    <h2>All Greeting Cards</h2>

    <a href="add_card.php" class="add-card-btn">➕ Add New Card</a>

    <table>
        <tr>
            <th>Name</th>
            <th>Image</th>
            <th>Price</th>
            <th>Category</th>
            <th>Subcategory</th>
            <th>Action</th>
        </tr>
        <?php if($result && $result->num_rows > 0): ?>
            <?php while($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?php echo htmlspecialchars($row['name']); ?></td>
                    <td><img src="image/<?php echo htmlspecialchars($row['image']); ?>" width="100" alt="Card Image"></td>
                    <td>₹<?php echo htmlspecialchars($row['price']); ?></td>
                    <td><?php echo htmlspecialchars($row['category']); ?></td>
                    <td><?php echo htmlspecialchars($row['subcategory']); ?></td>
                    <td>
                        <!-- Edit Button -->
                        <form method="GET" action="edit_card.php" class="action-form">
                            <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
                            <button type="submit" class="action-btn edit-btn">Edit</button>
                        </form>

                        <!-- Delete Button -->
                        <form method="POST" action="delete_card.php" class="action-form" 
                              onsubmit="return confirm('Are you sure you want to delete this card?');">
                            <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
                            <button type="submit" class="action-btn delete-btn">Delete</button>
                        </form>
                    </td>
                </tr>
            <?php endwhile; ?>
        <?php else: ?>
            <tr><td colspan="6">No cards found.</td></tr>
        <?php endif; ?>
    </table>
</body>
</html>

<?php include 'footer1.php'; ?>

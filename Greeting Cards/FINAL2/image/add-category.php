<?php
session_start();

// Check if admin is logged in
if (!isset($_SESSION['admin'])) {
    header("Location: admin-login.php");
    exit();
}

$conn = mysqli_connect("localhost", "root", "", "heycardy");
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

$error = '';
$success = '';

// Handle Add Category Form
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_category'])) {
    $name = trim($_POST['name'] ?? '');
    $desc = trim($_POST['description'] ?? '');

    if (!$name) {
        $error = "Category name is required.";
    } else {
        $stmt = mysqli_prepare($conn, "INSERT INTO categories (name, description) VALUES (?, ?)");
        mysqli_stmt_bind_param($stmt, 'ss', $name, $desc);

        if (mysqli_stmt_execute($stmt)) {
            $success = "Category added successfully!";
        } else {
            $error = "Something went wrong while saving.";
        }
        mysqli_stmt_close($stmt);
    }
}

// Handle Delete Category
if (isset($_GET['delete'])) {
    $id = (int) $_GET['delete'];
    mysqli_query($conn, "DELETE FROM categories WHERE id = $id");
    header("Location: add-category.php");
    exit();
}

// Fetch categories
$categories = mysqli_query($conn, "SELECT * FROM categories ORDER BY id DESC");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Add Category | Admin Panel</title>
    <style>
        body {
            font-family: Comic Sans MS;
            background: #f0f0f0;
            padding: 30px;
        }

        .form-container, .table-container {
            background: white;
            padding: 30px;
            max-width: 700px;
            margin: auto;
            border-radius: 12px;
            box-shadow: 0 0 20px rgba(0,0,0,0.1);
            margin-bottom: 30px;
        }

        h2 {
            text-align: center;
            color: #333;
        }

        label {
            display: block;
            margin-top: 15px;
            font-weight: bold;
        }

        input[type="text"],
        textarea {
            width: 100%;
            padding: 10px;
            margin-top: 5px;
            border-radius: 8px;
            border: 1px solid #ccc;
        }

        button {
            margin-top: 20px;
            width: 100%;
            padding: 12px;
            background-color: #6fa1f2;
            border: none;
            color: white;
            font-size: 16px;
            border-radius: 8px;
            cursor: pointer;
        }

        button:hover {
            background-color: #4d7de0;
        }

        .msg-success {
            color: green;
            margin-top: 10px;
            text-align: center;
        }

        .msg-error {
            color: red;
            margin-top: 10px;
            text-align: center;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        table th, table td {
            padding: 12px;
            border: 1px solid #ccc;
            text-align: left;
        }

        table th {
            background-color: #f4f4f4;
        }

        .actions button {
            padding: 5px 10px;
            margin-right: 5px;
            font-size: 14px;
            border-radius: 5px;
            cursor: pointer;
        }

        .update-btn {
            background-color: #4caf50;
            color: white;
            border: none;
        }

        .delete-btn {
            background-color: #f44336;
            color: white;
            border: none;
        }

        .update-btn:hover {
            background-color: #45a049;
        }

        .delete-btn:hover {
            background-color: #d32f2f;
        }
    </style>
</head>
<body>

<div class="form-container">
    <h2>Add Greeting Card Category</h2>

    <form method="POST">
        <label for="name">Category Name:</label>
        <input type="text" name="name" id="name" required>

        <label for="description">Description:</label>
        <textarea name="description" id="description" rows="4" placeholder="Optional"></textarea>

        <button type="submit" name="add_category">Add Category</button>
    </form>

    <?php if ($error): ?>
        <div class="msg-error"><?= $error ?></div>
    <?php elseif ($success): ?>
        <div class="msg-success"><?= $success ?></div>
    <?php endif; ?>
</div>

<div class="table-container">
    <h2>Existing Categories</h2>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Description</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = mysqli_fetch_assoc($categories)): ?>
                <tr>
                    <td><?= htmlspecialchars($row['id']) ?></td>
                    <td><?= htmlspecialchars($row['name']) ?></td>
                    <td><?= htmlspecialchars($row['description']) ?></td>
                    <td class="actions">
                        <form method="GET" style="display:inline;">
                            <input type="hidden" name="delete" value="<?= $row['id'] ?>">
                            <button type="submit" class="delete-btn" onclick="return confirm('Delete this category?')">Delete</button>
                        </form>
                        <form method="GET" action="edit-category.php" style="display:inline;">
                            <input type="hidden" name="id" value="<?= $row['id'] ?>">
                            <button type="submit" class="update-btn">Update</button>
                        </form>
                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>

</body>
</html>

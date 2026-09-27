<?php
session_start();
$con = mysqli_connect("localhost", "root", "", "flowercrafts");
if (!$con) die("DB Connection Failed: " . mysqli_connect_error());

// Only allow admin access (adjust session check for your system)
/*if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit();
}*/

// Handle image upload
if (isset($_POST['upload'])) {
    $title = $_POST['title'];
    $image = $_FILES['image']['name'];
    $target = "pictures/" . basename($image);

    if (move_uploaded_file($_FILES['image']['tmp_name'], $target)) 
    {
        $q = "INSERT INTO gallery (title, image) VALUES ('$title', '$image')";
        mysqli_query($con, $q);
        $msg = "Image uploaded successfully!";
    } 
    else 
    {
        $msg = "Failed to upload image.";
    }
}

// Handle delete
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    $res = mysqli_query($con, "SELECT image FROM gallery WHERE id=$id");
    $row = mysqli_fetch_assoc($res);
    unlink("pictures/" . $row['image']);
    mysqli_query($con, "DELETE FROM gallery_images WHERE id=$id");
    header("Location: gallery_admin.php");
    exit();
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Admin Gallery | Floreva</title>
    <style>
        body {font-family: 'Segoe UI', sans-serif; background:#fff0f5; margin:0; padding:0;}
        h2 {text-align:center; color:#d6336c;}
        form {
            display:flex; flex-direction:column; align-items:center;
            background:white; padding:20px; border-radius:15px;
            max-width:400px; margin:20px auto; box-shadow:0 4px 10px rgba(0,0,0,0.1);
        }
        input[type=text], input[type=file] {
            padding:10px; margin:10px 0; width:100%;
            border:1px solid #ddd; border-radius:10px;
        }
        button {
            background:#ff69b4; color:white; border:none;
            padding:10px 20px; border-radius:10px; cursor:pointer;
            font-size:16px;
        }
        table {
            width:90%; margin:20px auto; border-collapse:collapse;
            background:white; border-radius:15px; overflow:hidden;
        }
        th, td {
            border:1px solid #eee; padding:10px; text-align:center;
        }
        th {background:#ffe4ec; color:#d6336c;}
        img {width:100px; height:70px; object-fit:cover; border-radius:10px;}
        a.delete-btn {
            background:#ff4d6d; color:white; padding:5px 10px;
            text-decoration:none; border-radius:8px;
        }
    </style>
</head>
<body>
<?php include 'header.php'; ?>
<h2>Floreva Admin |  Gallery</h2>
<form method="POST" enctype="multipart/form-data">
    <input type="text" name="title" placeholder="Enter Image Title" required>
    <input type="file" name="image" required>
    <button type="submit" name="upload">Upload</button>
</form>

<table>
    <tr>
        <th>ID</th>
        <th>Image</th>
        <th>Title</th>
        <th>Status</th>
        <th>Action</th>
    </tr>
    <?php
    $res = mysqli_query($con, "SELECT * FROM gallery ORDER BY created_at DESC");
    while ($row = mysqli_fetch_assoc($res)) {
        echo "<tr>
            <td>{$row['id']}</td>
            <td><img src='pictures/{$row['image']}'></td>
            <td>{$row['title']}</td>
            <td>{$row['status']}</td>
            <td><a class='delete-btn' href='gallery_admin.php?delete={$row['id']}'>Delete</a></td>
        </tr>";
    }
    ?>
</table>
<?php include 'footer.php'; ?>
</body>
</html>

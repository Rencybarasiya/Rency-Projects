<?php
session_start();
if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit();
}

$selected_class = "Class 7";

// DB Connection
$conn = mysqli_connect("localhost", "root", "", "fees");
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

$error_message = "";
$success_message = "";

// Add Student Logic
if ($_SERVER['REQUEST_METHOD'] === "POST" && isset($_POST['add_student'])) {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];

    if (!preg_match('/^[a-zA-Z\s]+$/', $name)) {
        $error_message = "Name must contain only alphabets and spaces.";
    } elseif (!preg_match('/^[0-9]{10}$/', $phone)) {
        $error_message = "Phone must be exactly 10 digits.";
    } elseif (!preg_match('/^[a-zA-Z0-9._%+-]+@gmail\.com$/', $email)) {
        $error_message = "Email must end with @gmail.com.";
    } else {
        $sql = "INSERT INTO student7 (name, email, phone, class)
                VALUES ('$name', '$email', '$phone', '$selected_class')";
        if (mysqli_query($conn, $sql)) {
            $success_message = "Student added successfully.";
        } else {
            $error_message = "Error: " . mysqli_error($conn);
        }
    }
}

// Update Student Logic
if ($_SERVER['REQUEST_METHOD'] === "POST" && isset($_POST['update_student'])) {
    $id = $_POST['id']; // coming from hidden field
    $name = $_POST['name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];

    if (!preg_match('/^[a-zA-Z\s]+$/', $name)) {
        $error_message = "Name must contain only alphabets and spaces.";
    } elseif (!preg_match('/^[0-9]{10}$/', $phone)) {
        $error_message = "Phone must be exactly 10 digits.";
    } elseif (!preg_match('/^[a-zA-Z0-9._%+-]+@gmail\.com$/', $email)) {
        $error_message = "Email must end with @gmail.com.";
    } else {
        $sql = "UPDATE student7 SET name='$name', email='$email', phone='$phone'
                WHERE id='$id' AND class='$selected_class'";
        if (mysqli_query($conn, $sql)) {
            $success_message = "Student updated successfully.";
            header("Location: student7.php");
            exit();
        } else {
            $error_message = "Error: " . mysqli_error($conn);
        }
    }
}

// Delete Student
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    mysqli_query($conn, "DELETE FROM student7 WHERE id='$id' AND class='$selected_class'");
}

// Fetch and Sort Students
$result = mysqli_query($conn, "SELECT * FROM student7 WHERE class = '$selected_class'");
$students = [];
while ($row = mysqli_fetch_assoc($result)) {
    $students[] = $row;
}

// Sort by first word
usort($students, function ($a, $b) {
    $a_first = explode(" ", trim($a['name']))[0] ?? '';
    $b_first = explode(" ", trim($b['name']))[0] ?? '';
    return $a_first === $b_first
        ? strcmp($a['name'], $b['name'])
        : strcmp($a_first, $b_first);
});

// Edit Mode
$edit_mode = false;
$edit_data = ['id' => '', 'name' => '', 'email' => '', 'phone' => ''];
if (isset($_GET['edit'])) {
    $edit_id = $_GET['edit'];
    $edit_query = mysqli_query($conn, "SELECT * FROM student7 WHERE id='$edit_id' AND class='$selected_class'");
    if (mysqli_num_rows($edit_query) == 1) {
        $edit_data = mysqli_fetch_assoc($edit_query);
        $edit_mode = true;
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Manage Students - <?= htmlspecialchars($selected_class) ?></title>
    <style>
        body {
            font-family: 'Comic Sans MS';
            background: #e3eef1ff;
            padding: 20px;
        }
        h1 { color: #1d2e65; }

 h2 { color: #192256; }
        table {
            width: 100%;
            border-collapse: collapse;
            background: white;
            margin-top: 20px;
        }
        th, td {
            padding: 8px;
            border: 1px solid #ddd;
            text-align: center;
            font-size: 15px;
        }
        th {
            background: #2c3e50;
            color: white;
        }
        tr:hover { background: #e3eef1ff; }
        form {
            background: white;
            padding: 20px;
            border-radius: 5px;
            margin-bottom: 20px;
        }
        input {
            padding: 10px;
            margin: 6px;
            width: 220px;
        }
        button {
            padding: 7px 15px;
            background: #192256;
	width: 180px;
	font-size: 20px;
	font-family: 'Comic Sans MS';
            color: white;
            border: none;
            cursor: pointer;
        }
        .delete-btn {
            background: #c0392b;
            padding: 5px 10px;
            color: white;
            text-decoration: none;
            border-radius: 3px;
        }
        .edit-btn {
            background: #2980b9;
            padding: 5px 10px;
            color: white;
            text-decoration: none;
            border-radius: 3px;
        }

.top-right-image {
  position: absolute;
  top: 10px;
  right: 10px;
  z-index: 1000;
}

.top-right-image img {
  height: 50px; /* Adjust size as needed */
  width: auto;
  border-radius: 5px; /* optional for rounded corners */
}


    </style>
</head>
<body>

<div class="top-right-image">
  <img src="evil.jpg" alt="evil-eye Image">
</div>


<?php if ($error_message): ?>
    <div id="errorMsg" style="background:#f8d7da;color:#721c24;padding:10px;border-radius:5px;">
        <?= $error_message ?>
    </div>
<?php endif; ?>

<?php if ($success_message): ?>
    <div id="successMsg" style="background:#d4edda;color:#155724;padding:10px;border-radius:5px;">
        <?= $success_message ?>
    </div>
<?php endif; ?>

<h1>Manage Students of <?= htmlspecialchars($selected_class) ?></h1>

<form method="POST" onsubmit="return validateForm()">
    <h2><?= $edit_mode ? "Update Student" : "Add New Student" ?></h2>

    <?php if ($edit_mode): ?>
        <input type="hidden" name="id" value="<?= htmlspecialchars($edit_data['id']) ?>">
    <?php endif; ?>

    <input type="text" name="name" id="name" placeholder="Name" value="<?= htmlspecialchars($edit_data['name']) ?>" required>
    <input type="email" name="email" id="email" placeholder="Email" value="<?= htmlspecialchars($edit_data['email']) ?>" required>
    <input type="text" name="phone" id="phone" placeholder="Phone" value="<?= htmlspecialchars($edit_data['phone']) ?>" maxlength="10" required>

    <button type="submit" name="<?= $edit_mode ? 'update_student' : 'add_student' ?>">
        <?= $edit_mode ? 'Update Student' : 'Add Student' ?>
    </button>
</form>
<table>
    <thead>
        <tr>
            <th>ID</th>
           
            <th>Name</th>
            <th>Email</th>
            <th>Phone</th>
            <th>Class</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody> 
        <?php $serial = 1; foreach ($students as $row): ?>
            <tr>
                <td><?= $serial++ ?></td>
               
                <td><?= htmlspecialchars($row['name']) ?></td>
                <td><?= htmlspecialchars($row['email']) ?></td>
                <td><?= htmlspecialchars($row['phone']) ?></td>
                <td><?= htmlspecialchars($row['class']) ?></td>
                <td>
                    <a class="delete-btn" href="?delete=<?= $row['id'] ?>" onclick="return confirm('Are you sure?')">Delete</a>
                    <a class="edit-btn" href="?edit=<?= $row['id'] ?>">Update</a>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<p><a href="welcome.php">Back to Dashboard</a></p>

<script>
function validateForm() {
    const name = document.getElementById('name').value.trim();
    const email = document.getElementById('email').value.trim();
    const phone = document.getElementById('phone').value.trim();

    if (!/^[a-zA-Z\s]+$/.test(name)) {
        alert("Name must contain only alphabets and spaces.");
        return false;
    }
    if (!/^[0-9]{10}$/.test(phone)) {
        alert("Phone must be exactly 10 digits.");
        return false;
    }
    if (!email.endsWith("@gmail.com")) {
        alert("Email must end with @gmail.com.");
        return false;
    }
    return true;
}

document.getElementById('phone').addEventListener('input', function () {
    this.value = this.value.replace(/\D/g, '').slice(0, 10);
});

document.querySelectorAll('input').forEach(input => {
    input.addEventListener('focus', () => {
        const successMsg = document.getElementById('successMsg');
        const errorMsg = document.getElementById('errorMsg');
        if (successMsg) successMsg.style.display = 'none';
        if (errorMsg) errorMsg.style.display = 'none';
    });
});
</script>

</body>
</html>

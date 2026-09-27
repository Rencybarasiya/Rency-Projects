<?php
session_start();
$con=mysqli_connect("localhost","root","","flowercrafts");
if (mysqli_connect_errno()) 
{
  echo "Failed to connect...".mysqli_error();
  exit();
}

if (!isset($_SESSION['user_id'])) {
    header("Location: login_user.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$msg = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name =  $_POST['name'];
    $email = $_POST['email'];
    $subject = $_POST['subject'];
    $message = $_POST['message'];
    $rating = $_POST['rating'];

    $q = "INSERT INTO message (user_id, name, email, subject, message, rating)
          VALUES ('$user_id', '$name', '$email', '$subject', '$message', '$rating')";

    if (mysqli_query($con, $q)) {
        $msg = "Feedback submitted successfully! 🌸";
    } else {
        $msg = "Error: " . mysqli_error($con);
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Feedback - Floreva</title>
    <style>
        body {
            background: #fff0f5; /* pastel pink background */
            font-family: 'Segoe UI', sans-serif;
            padding: 30px;
        }
        form {
            background: #fff;
            padding: 60px;
            border-radius: 25px;
            width: 500px;
            margin: auto;
            box-shadow: 0 6px 20px rgba(255, 182, 193, 0.5);
            border: 2px solid #ffb6c1;
        }
        h2, h3 {
            color: #d14781;
            text-align: center;
            margin-bottom: 20px;
        }
        input, textarea {
            width: 100%;
            padding: 14px;
            margin-top: 12px;
            border: 1px solid #f4a6b9;
            border-radius: 12px;
            background-color: #fff8fa;
            transition: 0.3s;
            font-size: 15px;
        }
        input:focus, textarea:focus {
            border-color: #ff80ab;
            outline: none;
            box-shadow: 0 0 10px rgba(255, 128, 171, 0.4);
        }
        button {
            background-color: #ff80ab;
            color: white;
            border: none;
            padding: 14px 20px;
            margin-top: 20px;
            border-radius: 12px;
            cursor: pointer;
            font-size: 16px;
            display: block;
            width: 100%;
            transition: background 0.3s, transform 0.2s;
        }
        button:hover {
            background-color: #e0679d;
            transform: scale(1.05);
        }
        .top-msg {
    background: #ffe4ec;
    color: #d14781;
    font-weight: bold;
    text-align: center;
    padding: 12px;
    margin: 15px auto;
    border: 2px solid #ffb6c1;
    border-radius: 12px;
    width: 500px;
    box-shadow: 0 3px 10px rgba(255, 182, 193, 0.4);
}

        .msg {
            color: #d14781;
            text-align: center;
            margin-top: 15px;
            font-weight: bold;
        }
        /* Flower Rating Styling */
        .rating {
            display: flex;
            justify-content: center;
            margin-top: 20px;
            gap: 18px;
        }
        .rating input {
            display: none;
        }
        .rating label {
            font-size: 2.5em;
            cursor: pointer;
            transition: transform 0.2s;
            color: #ccc;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 6px;
        }
        .rating label span {
            font-size: 14px;
            color: #a64c74;
        }
        .rating input:checked ~ label,
        .rating label:hover {
            transform: scale(1.2);
            color: #ff69b4;
        }
    </style>
</head>
<body>
    <?php include 'header.php'; ?>
    <?php if ($msg) echo "<p class='msg'>$msg</p>"; ?>
    <form method="POST">
        <h2>Leave Your Feedback</h2>
        <input type="text" name="name" placeholder="Your Name" required />
        <input type="email" name="email" placeholder="Your Email" required />
        <input type="text" name="subject" placeholder="Subject" required />
        <textarea name="message" rows="5" placeholder="Your message..." required></textarea>

        <h3>Rate Your Experience</h3>
        <div class="rating">

            <input type="radio" id="rate1" name="rating" value="1">
            <label for="rate1">🌸 <span>1.Dry</span></label>

            <input type="radio" id="rate2" name="rating" value="2">
            <label for="rate2">🌸 <span>2.Wilt</span></label>

            <input type="radio" id="rate3" name="rating" value="3">
            <label for="rate3">🌸 <span>3.Bud</span></label>

            <input type="radio" id="rate4" name="rating" value="4">
            <label for="rate4">🌸 <span>4.Bloom</span></label>

            <input type="radio" id="rate5" name="rating" value="5">
            <label for="rate5">🌸 <span>5.Blossom</span></label>

        </div>

        <button type="submit">Submit</button>
    </form>
    <?php include 'footer.php'; ?>
</body>
</html>

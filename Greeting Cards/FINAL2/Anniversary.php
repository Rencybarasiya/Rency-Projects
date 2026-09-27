<?php include 'user_header_sidebar.php'; ?>

<?php
include("db1.php");

$result = mysqli_query($conn, 
   "SELECT * FROM cardss WHERE category='cards' AND subcategory='Anniversary'");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Anniversary Cards</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;500&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Poppins', sans-serif; background:#fff; margin-top:90; }
        main { padding: 30px; background: linear-gradient(to right, #fff0f5, #ffe4e1); min-height: 100vh; transition: margin-left 0.3s ease; }
        h1 { text-align:center; color:#e91e63; margin: 0 0 20px; }
        .card-container { display:grid; grid-template-columns: repeat(4, 1fr); gap:25px; justify-items:center; }
        .card-box { background:#fff; border-radius:12px; box-shadow:0 4px 15px rgba(0,0,0,0.1); width:220px; padding:15px; text-align:center; transition: transform .3s; }
        .card-box:hover { transform: scale(1.05); }
        .card-box img { width:100%; height:180px; object-fit:cover; border-radius:8px; margin-bottom:10px; }
        .card-box h3 { color:#d6336c; margin-bottom:10px; font-size:18px; }
        .card-box p { color:#555; font-size:16px; margin:0; }
        .card-box form button { 
            background-color: #e91e63; color: white; border: none; padding: 8px 15px; border-radius: 5px; cursor: pointer; 
            margin-top: 5px;
        }
    </style>
</head>
<body>

<main>
    <div style="height:20px;"></div>

    <h1>💑 Anniversary Greeting Cards</h1>

    <div class="card-container">
        <?php while ($row = mysqli_fetch_assoc($result)) { ?>
            <div class="card-box">
                <img src="image/<?php echo htmlspecialchars($row['image']); ?>" alt="Anniversary Card">
                <h3><?php echo htmlspecialchars($row['name']); ?></h3>
                <p>₹<?php echo htmlspecialchars($row['price']); ?></p>

                <form method="post" action="add_to_cart.php">
                    <input type="hidden" name="product_id" value="<?php echo $row['id']; ?>">
                    <label>Quantity:</label>
                    <input type="number" name="quantity" value="1" min="1" style="width: 60px; padding: 5px; margin: 5px 0;">
                    <br>
                    <button type="submit">Add to Cart</button>
                </form>
            </div>
        <?php } ?>
    </div>

    <div style="height:20px;"></div>
    <?php include 'footer1.php'; ?>
</main>

<script>
    // Adjust main margin if sidebar is open
    const sidebar = document.getElementById('sidebar');
    const main = document.querySelector('main');
    if (sidebar && main) {
        const observer = new MutationObserver(() => {
            if (sidebar.classList.contains('show')) main.style.marginLeft = '200px';
            else main.style.marginLeft = '0';
        });
        observer.observe(sidebar, { attributes: true, attributeFilter: ['class'] });
    }
</script>

</body>
</html>

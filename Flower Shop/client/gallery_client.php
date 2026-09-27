<?php
$con = mysqli_connect("localhost", "root", "", "flowercrafts");
if (mysqli_connect_errno()) {
    echo "Failed to connect..." . mysqli_error($con);
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gallery | Floreva</title>
    <style>
        body {
            margin: 0;
            font-family: 'Segoe UI', sans-serif;
            background: #fff0f5;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }

        .page-container {
            flex: 1;
            display: flex;
            flex-direction: column;
            padding: 30px 10px;
            max-width: 100%;
            margin: 0 auto;
        }

        .page-title {
            font-size: 32px;
            font-weight: bold;
            color: #d6336c;
            text-align: center;
            margin-bottom: 20px;
        }

        /* Horizontal Scrollable Gallery */
        .gallery-scroll {
            display: flex;
            gap: 20px;
            overflow-x: auto;
            scroll-behavior: smooth;
            padding: 10px 15px;
        }

        /* Hide scrollbar on Webkit browsers */
        .gallery-scroll::-webkit-scrollbar {
            display: none;
        }

        /* Hide scrollbar on Firefox */
        .gallery-scroll {
            scrollbar-width: none;
        }

        .gallery-card {
            flex: 0 0 auto;
            width: 300px;
            border-radius: 15px;
            overflow: hidden;
            background: white;
            box-shadow: 0 4px 10px rgba(0,0,0,0.1);
            transition: transform 0.3s ease-in-out, box-shadow 0.3s ease-in-out;
        }

        .gallery-card img {
            width: 100%;
            height: 200px;
            object-fit: cover;
            display: block;
            cursor: pointer;
            transition: transform 0.3s ease-in-out;
        }

        .gallery-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 6px 15px rgba(0,0,0,0.15);
        }

        .gallery-card:hover img {
            transform: scale(1.05);
        }

        /* Lightbox */
        .lightbox {
            display: none;
            position: fixed;
            top: 0; left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.8);
            justify-content: center;
            align-items: center;
            z-index: 1000;
        }

        .lightbox img {
            max-width: 90%;
            max-height: 80%;
            border-radius: 15px;
            box-shadow: 0 0 20px rgba(255, 182, 193, 0.8);
        }

        .close-btn {
            position: absolute;
            top: 20px;
            right: 30px;
            font-size: 32px;
            color: white;
            cursor: pointer;
            font-weight: bold;
        }

        .no-images {
            text-align: center;
            font-size: 18px;
            color: #888;
            width: 100%;
            margin-top: 20px;
        }
    </style>
</head>
<body>

<?php include 'header.php'; ?>

<div class="page-container">
    <h1 class="page-title">Our Gallery</h1>

    <div class="gallery-scroll">
        <?php
        $q = "SELECT * FROM gallery WHERE status='active' ORDER BY created_at DESC";
        $res = mysqli_query($con, $q);
        if (mysqli_num_rows($res) > 0) {
            while ($row = mysqli_fetch_assoc($res)) {
                echo "<div class='gallery-card'>
                        <img src='../admin_panel/pictures/{$row['image']}' alt='{$row['title']}' onclick='openLightbox(this.src)'>
                      </div>";
            }
        } else {
            echo "<p class='no-images'>No images found.</p>";
        }
        ?>
    </div>
</div>

<div class="lightbox" id="lightbox">
    <span class="close-btn" onclick="closeLightbox()">&times;</span>
    <img id="lightbox-img">
</div>

<?php include 'footer.php'; ?>

<script>
function openLightbox(src) {
    document.getElementById("lightbox").style.display = "flex";
    document.getElementById("lightbox-img").src = src;
}
function closeLightbox() {
    document.getElementById("lightbox").style.display = "none";
}
</script>

</body>
</html>

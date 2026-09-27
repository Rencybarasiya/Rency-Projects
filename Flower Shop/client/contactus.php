<?php
// contact.php
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Contact Us | Floreva</title>
    <style>
        body {
            margin: 0;
            font-family: 'Segoe UI', sans-serif;
            background: url('flbg.jpg') no-repeat center center/cover;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: flex-start;
            min-height: 100vh;
            padding: 40px 20px;
        }
        h1 {
            color: #c2185b;
            font-size: 2.5rem;
            margin-bottom: 30px;
            text-shadow: 1px 1px 3px rgba(255, 192, 203, 0.7);
        }
        .contact-container {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 30px;
            max-width: 1000px;
            width: 100%;
        }
        .card {
            background: rgba(255, 255, 255, 0.85);
            backdrop-filter: blur(8px);
            border-radius: 25px;
            box-shadow: 0 6px 18px rgba(0, 0, 0, 0.15);
            padding: 25px;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);
        }
        form {
            display: flex;
            flex-direction: column;
            gap: 15px;
        }
        input, textarea {
            padding: 12px;
            border: 1px solid #f8bbd0;
            border-radius: 12px;
            font-size: 15px;
            background: #fff8fa;
            transition: 0.3s ease;
        }
        input:focus, textarea:focus {
            outline: none;
            border-color: #c2185b;
            box-shadow: 0 0 5px rgba(194, 24, 91, 0.4);
        }
        button {
            background: #c2185b;
            color: white;
            border: none;
            padding: 14px;
            font-size: 16px;
            font-weight: bold;
            border-radius: 12px;
            cursor: pointer;
            transition: 0.3s ease;
            box-shadow: 0 4px 10px rgba(194, 24, 91, 0.3);
        }
        button:hover {
            background: #ad1457;
            transform: scale(1.03);
        }
        .info h2 {
            margin-top: 0;
            color: #ad1457;
            font-size: 1.6rem;
            margin-bottom: 15px;
            text-align: center;
        }
        .info p {
            font-size: 16px;
            color: #880e4f;
            margin: 8px 0;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .icon {
            font-size: 18px;
        }
        @media (max-width: 768px) {
            .contact-container {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <h1>Contact Us 🌸</h1>
    <div class="contact-container">

        <!-- Contact Form Card -->
        <div class="card">
            <form method="POST" action="save_contact.php">
                <input type="text" name="name" placeholder="Your Name" required>
                <input type="email" name="email" placeholder="Your Email" required>
                <input type="text" name="subject" placeholder="Subject" required>
                <textarea name="message" rows="5" placeholder="Your Message..." required></textarea>
                <button type="submit">Send Message</button>
            </form>
        </div>

        <!-- Store Info Card -->
        <div class="card info">
            <h2>Our Store</h2>
            <p>📍 Address: 123 Bloom Street, City</p>
            <p>📞 Phone: +91 9876543210</p>
            <p>📧 Email: ask@floreva.com</p>
        </div>

    </div>
</body>
</html>

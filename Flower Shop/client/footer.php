<style>

.footer {
    background: rgba(255, 255, 255, 0.3);
    backdrop-filter: blur(6px);
    padding: 30px 5vw; /* ✅ relative padding */
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 30px;
    text-align: left;
    max-width: 100%; /* ✅ no overflow */
}
.footer h3 { color: #d63384; margin-bottom: 10px; }
.footer a { text-decoration: none; color: #4a2c35; transition: all 0.3s; }
.footer a:hover { color: #d63384; }
.footer i { margin-right: 8px; color: #d63384; }
</style>

<footer class="footer">
    <div>
        <h3>Floreva</h3>
        <p>Where every bloom tells a story 🌸</p>
    </div>
    <div>
        <h3>Quick Links</h3>
        <a href="home.php">Home</a><br>
        <a href="about.php">About Floreva</a>
    </div>
    <div>
        <h3>Contact Us</h3>
        <p><i class="fas fa-envelope"></i> ask@floreva.com</p>
        <p><i class="fas fa-phone"></i> +91 9876543210</p>
        <p><i class="fas fa-map-marker-alt"></i> 123 Bloom Street, Rajkot, Gujarat, India</p>
    </div>
    <div>
        <h3>Connect with Us</h3>
        <p><i class="fab fa-facebook"></i> <a href="#">Facebook</a></p>
        <p><i class="fab fa-instagram"></i> <a href="#">Instagram</a></p>
    </div>
</footer>
</body>
</html>

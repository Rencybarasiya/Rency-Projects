<footer style="
  background: linear-gradient(135deg, #FFE4EC, #FFDDEE);
  padding: 40px 0;
  font-family: 'Comic Sans MS', cursive, sans-serif;
  color: #333;
  box-shadow: 0 0 20px rgba(255, 105, 180, 0.2);
  border-top: 4px solid #FF69B4;
">
  <div style="
    max-width: 1200px;
    margin: auto;
    display: flex;
    flex-wrap: wrap;
    justify-content: space-between;
    align-items: center;
    padding: 0 20px;
  ">

    <!-- Left Column -->
    <div style="flex: 1 1 250px; text-align: center; padding: 20px;">
      <img src="image/logo1.jpg" alt="heycardy Logo"
        style="width: 100px; height: auto; border-radius: 50%;
        background: #fff; box-shadow: 0 0 10px rgba(0,0,0,0.1);">
      <p style="margin-top: 10px; color: #666; font-size: 14px;">Spreading smiles</p>
    </div>

    <!-- Center Column -->
    <div style="flex: 2 1 300px; text-align: center; padding: 20px;">
      <p style="margin: 5px 0;"><strong>📞</strong> +91 9510999850</p>
      <p style="margin: 5px 0;"><strong>📧</strong> heycardy@gmail.com</p>
      <p style="margin: 5px 0;"><strong>🌐</strong> www.heycardy.com</p>
      <p style="margin: 5px 0;"><strong>📍</strong> New Delhi, India</p>
    </div>

    <!-- Right Column -->
    <div style="flex: 1 1 250px; text-align: center; padding: 20px;">
      <h2 style="margin: 10px 0; color: #FF69B4; font-weight: bold;">heycardy</h2>
      <p style="color: #777; font-size: 14px;">For every feeling, a card 💌</p>
      <div style="margin-top: 15px;">
        <a href="#" title="Instagram" style="margin: 0 5px; font-size: 20px; color: #FF69B4;">📸</a>
        <a href="#" title="Facebook" style="margin: 0 5px; font-size: 20px; color: #3b5998;">📘</a>
        <a href="#" title="Twitter" style="margin: 0 5px; font-size: 20px; color: #1DA1F2;">🐦</a>
        <a href="#" title="Pinterest" style="margin: 0 5px; font-size: 20px; color: #E60023;">📌</a>
      </div>
    </div>
  </div>

  <div style="
    text-align: center;
    margin-top: 20px;
    color: #888;
    font-size: 14px;
  ">
    &copy; <?= date('Y'); ?> <strong>heycardy</strong> | Made with ❤️ for every occasion.
  </div>

  <style>
    /* Make footer responsive */
    @media (max-width: 768px) {
      footer div[style*='display: flex'] {
        flex-direction: column !important;
        text-align: center !important;
      }
    }
  </style>
</footer>

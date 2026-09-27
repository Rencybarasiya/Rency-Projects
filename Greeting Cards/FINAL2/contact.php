<!-- contact.php -->
<style>
  .contact-container {
    max-width: 800px;
    margin: auto;
    padding: 30px 20px;
    background-color: #fce4ec;
    border-radius: 10px;
    box-shadow: 0 4px 10px rgba(0,0,0,0.1);
    animation: fadeIn 0.9s ease-in-out;
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    color: #444;
  }

  .contact-container h2 {
    text-align: center;
    font-size: 28px;
    color: #d63384;
    margin-bottom: 20px;
  }

  .contact-container p {
    font-size: 18px;
    margin-bottom: 15px;
    text-align: center;
  }

  .contact-container ul {
    list-style: none;
    padding: 0;
    font-size: 17px;
    line-height: 1.8;
  }

  .contact-container li {
    padding-left: 30px;
    position: relative;
    margin-bottom: 10px;
  }

  .contact-container li::before {
    content: "📌";
    position: absolute;
    left: 0;
  }

  @keyframes fadeIn {
    from {
      opacity: 0;
      transform: translateY(15px);
    }
    to {
      opacity: 1;
      transform: translateY(0);
    }
  }
</style>

<div class="contact-container">
  <h2>Contact Us</h2>
  <p>If you have any questions or feedback, we’d love to hear from you!</p>
  <ul>
    <li><strong>Email:</strong> support@heycardy1.com</li>
    <li><strong>Phone:</strong> +91 98765 43210</li>
    <li><strong>Address:</strong> 123, Greeting Lane, Card City, India</li>
  </ul>
</div>

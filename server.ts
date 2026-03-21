// ================= server.ts (ALL-IN-ONE) =================
import express from "express";

const app = express();
const PORT = 3000;

app.get("/", (req, res) => {
  res.send(`
  <!DOCTYPE html>
  <html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>MyBot</title>

    <style>
      body {
        margin: 0;
        font-family: Arial, Helvetica, sans-serif;
        background-color: #030303;
        color: #ffffff;
      }

      .navbar {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 18px 50px;
        background: rgba(10, 10, 10, 0.8);
        backdrop-filter: blur(12px);
        border-bottom: 1px solid rgba(255, 255, 255, 0.04);
      }

      .logo {
        font-size: 22px;
        font-weight: bold;
        color: #f91f2f;
      }

      .nav-right {
        display: flex;
        gap: 28px;
        align-items: center;
      }

      .nav-link {
        color: #888;
        text-decoration: none;
      }

      .nav-link:hover {
        color: white;
      }

      .login-btn {
        background: #f91f2f;
        padding: 10px 20px;
        border-radius: 10px;
        color: white;
        text-decoration: none;
        box-shadow: 0 0 20px rgba(249,31,47,0.4);
      }

      .header {
        text-align: center;
        padding: 140px 20px;
      }

      .header h1 {
        font-size: 56px;
      }

      .cta-btn {
        background: #f91f2f;
        padding: 14px 30px;
        border-radius: 12px;
        color: white;
        text-decoration: none;
      }
    </style>
  </head>

  <body>

    <nav class="navbar">
      <div class="logo">MyBot</div>

      <div class="nav-right">
        <a href="#home" class="nav-link">Home</a>
        <a href="#features" class="nav-link">Features</a>
        <a href="#testimonials" class="nav-link">Testimonials</a>

        <a href="https://discord.com/api/oauth2/authorize" class="login-btn">
          Login with Discord
        </a>
      </div>
    </nav>

    <header class="header">
      <h1>Powerful Discord Bot</h1>
      <p>Automate your server and grow your community.</p>

      <a href="https://discord.com/api/oauth2/authorize" class="cta-btn">
        Get Started
      </a>
    </header>

  </body>
  </html>
  `);
});

app.listen(PORT, () => {
  console.log(`Server running on http://localhost:${PORT}`);
});

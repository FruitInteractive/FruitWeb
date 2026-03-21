import React from "react";
import "./styles.css";

function Navbar() {
  return (
    <nav className="navbar">
      <div className="nav-left">
        <div className="logo">MyBot</div>
      </div>

      <div className="nav-right">
        <a href="#home" className="nav-link">Home</a>
        <a href="#features" className="nav-link">Features</a>
        <a href="#testimonials" className="nav-link">Testimonials</a>

        <a
          href="https://discord.com/api/oauth2/authorize"
          className="login-btn"
        >
          Login with Discord
        </a>
      </div>
    </nav>
  );
}

function Header() {
  return (
    <header className="header">
      <h1>Powerful Discord Bot</h1>
      <p>
        Automate your server, engage your community, and level up your Discord
        experience.
      </p>

      <a
        href="https://discord.com/api/oauth2/authorize"
        className="cta-btn"
      >
        Get Started
      </a>
    </header>
  );
}

export default function App() {
  return (
    <div className="app">
      <Navbar />
      <Header />
    </div>
  );
}

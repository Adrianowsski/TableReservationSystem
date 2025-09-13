<?php
// index.php
$pageTitle = "Welcome to Our Restaurant!";
$customCss = "main.css";
require_once 'includes/header.php';
?>
<style>
    body {
        margin: 0;
        background: url('image/mainpage.webp') no-repeat center center fixed;
        background-size: cover;
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        color: #fff;
    }
</style>
<div class="full-page">
    <div class="hero-section section">
        <h1>Welcome to Our Restaurant!</h1>
        <p class="lead">Make table reservations online</p>
        <div class="mt-3">
            <a href="login.php" class="btn btn-outline-light"><i class="bi bi-box-arrow-in-right"></i> Log In</a>
            <a href="register.php" class="btn btn-outline-light"><i class="bi bi-person-plus"></i> Sign Up</a>
        </div>
    </div>
    <div class="about-section section">
        <h2><i class="bi bi-building"></i> Italian Restaurant</h2>
        <p>Discover the true taste of Italy in the heart of the city. Our restaurant offers authentic recipes prepared with passion and fresh ingredients.</p>
    </div>
    <div class="menu-section section">
        <h2><i class="bi bi-card-list"></i> Our Menu</h2>
        <p class="lead">Explore the unique flavors we have prepared for you</p>
        <a href="menu.php" class="btn btn-light btn-lg"><i class="bi bi-menu-up"></i> View Menu</a>
    </div>
</div>
<?php require_once 'includes/footer.php'; ?>

<?php
// menu.php

$pageTitle = "Our Menu";
$customCss = "menu.css";
require_once 'includes/header.php';
?>

<div class="menu-container">
    <h1 class="menu-heading">Our Menu</h1>

    <!-- Starters -->
    <section class="menu-category">
        <h2><i class="bi bi-egg-fried"></i> Starters</h2>
        <div class="row">
            <!-- Item 1: Bruschetta -->
            <div class="col-md-4 mb-4">
                <div class="card h-100">
                    <img src="image/bruschetta.webp" class="card-img-top" alt="Bruschetta">
                    <div class="card-body d-flex flex-column">
                        <h5 class="card-title">Bruschetta</h5>
                        <p class="card-text">Crispy baguette with tomatoes, basil, and olive oil.</p>
                        <p class="price">12 zł</p>
                    </div>
                </div>
            </div>
            <!-- Item 2: Caprese Salad -->
            <div class="col-md-4 mb-4">
                <div class="card h-100">
                    <img src="image/SalataCaprese.webp" class="card-img-top" alt="Caprese Salad">
                    <div class="card-body d-flex flex-column">
                        <h5 class="card-title">Caprese Salad</h5>
                        <p class="card-text">Fresh mozzarella, tomatoes, and basil, drizzled with olive oil.</p>
                        <p class="price">15 zł</p>
                    </div>
                </div>
            </div>
            <!-- Item 3: Tempura Shrimp -->
            <div class="col-md-4 mb-4">
                <div class="card h-100">
                    <img src="image/KrewetkiWTempurze.webp" class="card-img-top" alt="Tempura Shrimp">
                    <div class="card-body d-flex flex-column">
                        <h5 class="card-title">Tempura Shrimp</h5>
                        <p class="card-text">Lightly crispy shrimp in batter, served with chili sauce.</p>
                        <p class="price">18 zł</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Main Courses -->
    <section class="menu-category">
        <h2><i class="bi bi-cup-straw"></i> Main Courses</h2>
        <div class="row">
            <!-- Item 1: Beef Steak -->
            <div class="col-md-4 mb-4">
                <div class="card h-100">
                    <img src="image/StekWolowy.webp" class="card-img-top" alt="Beef Steak">
                    <div class="card-body d-flex flex-column">
                        <h5 class="card-title">Beef Steak</h5>
                        <p class="card-text">Juicy tenderloin steak, served with potatoes and vegetables.</p>
                        <p class="price">45 zł</p>
                    </div>
                </div>
            </div>
            <!-- Item 2: Spaghetti Carbonara -->
            <div class="col-md-4 mb-4">
                <div class="card h-100">
                    <img src="image/Carbonara.webp" class="card-img-top" alt="Spaghetti Carbonara">
                    <div class="card-body d-flex flex-column">
                        <h5 class="card-title">Spaghetti Carbonara</h5>
                        <p class="card-text">Classic Italian dish with pasta, pancetta, and creamy sauce.</p>
                        <p class="price">35 zł</p>
                    </div>
                </div>
            </div>
            <!-- Item 3: Mushroom Risotto -->
            <div class="col-md-4 mb-4">
                <div class="card h-100">
                    <img src="image/Risotto.webp" class="card-img-top" alt="Mushroom Risotto">
                    <div class="card-body d-flex flex-column">
                        <h5 class="card-title">Mushroom Risotto</h5>
                        <p class="card-text">Creamy risotto with porcini and other forest mushrooms.</p>
                        <p class="price">38 zł</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Desserts -->
    <section class="menu-category">
        <h2><i class="bi bi-egg-fried"></i> Desserts</h2>
        <div class="row">
            <!-- Item 1: Tiramisu -->
            <div class="col-md-4 mb-4">
                <div class="card h-100">
                    <img src="image/Tiramisu.webp" class="card-img-top" alt="Tiramisu">
                    <div class="card-body d-flex flex-column">
                        <h5 class="card-title">Tiramisu</h5>
                        <p class="card-text">Classic Italian dessert with mascarpone, coffee, and cocoa.</p>
                        <p class="price">20 zł</p>
                    </div>
                </div>
            </div>
            <!-- Item 2: Vanilla Ice Cream -->
            <div class="col-md-4 mb-4">
                <div class="card h-100">
                    <img src="image/LodyWaniliowe.webp" class="card-img-top" alt="Vanilla Ice Cream">
                    <div class="card-body d-flex flex-column">
                        <h5 class="card-title">Vanilla Ice Cream</h5>
                        <p class="card-text">Homemade vanilla ice cream with fresh fruit.</p>
                        <p class="price">15 zł</p>
                    </div>
                </div>
            </div>
            <!-- Item 3: Cheesecake -->
            <div class="col-md-4 mb-4">
                <div class="card h-100">
                    <img src="image/Sernik.webp" class="card-img-top" alt="Cheesecake">
                    <div class="card-body d-flex flex-column">
                        <h5 class="card-title">Cheesecake</h5>
                        <p class="card-text">Creamy cheesecake on a crispy crust with fruit mousse.</p>
                        <p class="price">22 zł</p>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<?php
require_once 'includes/footer.php';
?>

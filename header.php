<?php
if (isset($_SESSION['user_id'])) {
    $username = $_SESSION['username'];
} else {
    $username = "Guest";
}
?>
<!DOCTYPE html>
<html lang="en">
    <head>
<link rel="stylesheet" href="style.css">
</style>

    </head>
    <body>
<header>
    <nav>
        <div class="logo">
            <a href="index.php"><h1>HRM</h1></a>
        </div>
        <ul>
            <li><a href="index.php">Home</a></li>
            <li><a href="shop.php">Shop</a></li>
            <li><a href="about.php">About</a></li>
            <li><a href="contact.php">Contact</a></li>
            <?php if (isset($_SESSION['user_id'])) { ?>
                <li><a href="cart.php">Cart</a></li>
                <li><a href="orders.php">Orders</a></li>
                <li><a href="wishlist.php">Wishlist</a></li>
                <li><a href="logout.php">Logout</a></li>
                <li><a href="profile.php">Profile</a></li>
            <?php } else { ?>
                <li><a href="register.php">Register</a></li>
                <li><a href="login.php">Login</a></li>
            <?php } ?>
        </ul>
    </nav>
</header>
</body>
</html>
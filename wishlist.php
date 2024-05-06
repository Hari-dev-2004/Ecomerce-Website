<?php
session_start();
include 'db_connect.php';

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

// Get wishlist items from the database
$user_id = $_SESSION['user_id'];
$sql = "SELECT p.id, p.name, p.price, p.image
        FROM wishlist w
        JOIN products p ON w.product_id = p.id
        WHERE w.user_id = $user_id";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HRM | Wishlist</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <?php include 'header.php'; ?>

    <main>
        <section class="wishlist">
            <h2>Your Wishlist</h2>
            <?php
            if ($result->num_rows > 0) {
                echo "<div class='product-grid'>";
                while($row = $result->fetch_assoc()) {
                    echo "<div class='product'>";
                    echo "<img src='" . $row['image'] . "' alt='" . $row['name'] . "'>";
                    echo "<h3>" . $row['name'] . "</h3>";
                    echo "<p>$" . $row['price'] . "</p>";
                    echo "<a href='add_to_cart.php?id=" . $row['id'] . "' class='btn'>Add to Cart</a>";
                    echo "<a href='remove_from_wishlist.php?id=" . $row['id'] . "' class='btn'>Remove</a>";
                    echo "</div>";
                }
                echo "</div>";
            } else {
                echo "Your wishlist is empty.";
            }
            ?>
        </section>
    </main>

    <?php include 'footer.php'; ?>
</body>
</html>
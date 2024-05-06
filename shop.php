<?php
session_start();
include 'db_connect.php';

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shop Page</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
        }

        .product-container {
            display: flex;
            flex-wrap: wrap;
            justify-content: space-around; /* Align items in the center */
            padding: 20px; /* Add some spacing around the products */
        }

        .product {
            width: 32%; /* Two products per row */
            margin-bottom: 80px; /* Add spacing between products */
            border-radius: 5px;
            padding: 10px;
             /* Add a subtle shadow effect */
        }

        .product img {
            width: 100%; /* Make images fill the container */
            border-radius: 5px;
        }

        .product h3 {
            margin-top: 10px;
            font-size: 1.2em;
        }

        .product p {
            margin-top: 5px;
            font-size: 1.1em;
        }
        .product a{
            text-decoration: none;
            color: black;
        }

        .product .btn{
            text-decoration: none;
            color: white;
        }

        .product-options {
            display: flex;
            justify-content: space-between;
            margin-top: 10px;
        }

        .btn {
            padding: 10px 20px;
            background-color: #333;
            color: white;
            text-decoration: none;
            border: none;
            border-radius: 3px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .btn:hover {
            background-color: #767576;
        }
    </style>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<?php include 'header.php'; ?>
<div class="product-container">
    <?php
    include 'db_connect.php';

    $sql = "SELECT * FROM products ORDER BY id DESC LIMIT 6"; // Limit to 6 products
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            echo "<div class='product'>";
        // Change both the image and name to links
        echo "<a href='" . $row['name'] . ".php'>";
        echo "<img src='products/" . $row['image'] . "' alt='" . $row['name'] . "'>";
        echo "<h3>" . $row['name'] . "</h3>";
        echo "</a>"; // Close the link for product name and image
        echo "<p>₹" . $row['price'] . "</p>";
        echo "<div class='product-options'>";
        echo "<a href='buy1.php?id=" . $row['id'] . "' class='btn'>Buy Now</a>";
        echo "<a href='add_to_cart.php?id=" . $row['id'] . "' class='btn'>Add to Cart</a>";
        echo "<a href='add_to_wishlist.php?id=" . $row['id'] . "' class='btn'>Add to Wishlist</a>";
        echo "</div>"; // Close product-options
        echo "</div>";
        }
    } else {
        echo "No products found.";
    }

    $conn->close();
    ?>
</div>

</body>
</html>

<?php
session_start();
include 'db_connect.php';

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

// Get cart items from the database
$user_id = $_SESSION['user_id'];
$sql = "SELECT p.id, p.name, p.price, p.image, c.quantity
        FROM cart c
        JOIN products p ON c.product_id = p.id
        WHERE c.user_id = $user_id";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HRM | Cart</title>
    <style>
         body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f7f7f7;
        }

        .cart {
            max-width: 800px;
            margin: 50px auto;
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
            padding: 20px;
        }

        h2 {
            text-align: center;
            margin-bottom: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th, td {
            padding: 15px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        th {
            background-color: #f2f2f2;
        }

        img {
            width: 100px;
            height: auto;
            vertical-align: middle;
            margin-right: 10px;
            border-radius: 5px;
        }

        .total {
            font-weight: bold;
        }

        .btn {
            display: inline-block;
            padding: 10px 20px;
            background-color: #333;
            color: #fff;
            text-decoration: none;
            border-radius: 5px;
            transition: background-color 0.3s ease;
        }

        .btn:hover {
            background-color: #b4afb4;
        }

        .empty-cart-msg {
            text-align: center;
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <?php include 'header.php'; ?>

    <main>
        <section class="cart">
            <h2>Your Cart</h2>
            <?php
            if ($result->num_rows > 0) {
                echo "<table>";
                echo "<tr><th>Product</th><th>Price</th><th>Quantity</th><th>Total</th><th></th></tr>";
                $total = 0;
                while($row = $result->fetch_assoc()) {
                    $item_total = $row['price'] * $row['quantity'];
                    $total += $item_total;
                    echo "<tr>";
                    echo "<td><img src='products/" . $row['image'] . "' alt='" . $row['name'] . "'>" . $row['name'] . "</td>";
                    echo "<td>₹" . $row['price'] . "</td>";
                    echo "<td>" . $row['quantity'] . "</td>";
                    echo "<td>₹" . $item_total . "</td>";
                    echo "<td><a href='remove_from_cart.php?id=" . $row['id'] . "' class='btn'>Remove</a></td>";
                    echo "</tr>";
                }
                echo "<tr><td colspan='3' class='total'>Total:</td><td class='total'>$" . $total . "</td><td></td></tr>";
                echo "</table>";
                echo "<a href='checkout.php' class='btn'>Checkout</a>";
            } else {
                echo "Your cart is empty.";
            }
            ?>
        </section>
    </main>

    <?php include 'footer.php'; ?>
</body>
</html>
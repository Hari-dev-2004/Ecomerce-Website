<?php
session_start();
include 'db_connect.php';

// Check if the user is logged in and has items in the cart
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$user_id = $_SESSION['user_id'];
$sql = "SELECT p.id, p.name, p.price, c.quantity
        FROM cart c
        JOIN products p ON c.product_id = p.id
        WHERE c.user_id = $user_id";
$result = $conn->query($sql);

if ($result->num_rows == 0) {
    header('Location: cart.php');
    exit;
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $total_amount = 0;
    $order_items = array();

    while ($row = $result->fetch_assoc()) {
        $product_id = $row['id'];
        $quantity = $row['quantity'];
        $price = $row['price'];
        $item_total = $quantity * $price;
        $total_amount += $item_total;
        $order_items[] = array(
            'product_id' => $product_id,
            'quantity' => $quantity
        );
    }

    // Insert order into the database
    $order_date = date('Y-m-d H:i:s');
    $sql = "INSERT INTO orders (user_id, order_date, total_amount) VALUES ($user_id, '$order_date', $total_amount)";

    if ($conn->query($sql) === TRUE) {
        $order_id = $conn->insert_id;

        // Insert order items into the database
        foreach ($order_items as $item) {
            $product_id = $item['product_id'];
            $quantity = $item['quantity'];
            $sql = "INSERT INTO order_items (order_id, product_id, quantity) VALUES ($order_id, $product_id, $quantity)";
            $conn->query($sql);
        }

        // Clear the cart
        $sql = "DELETE FROM cart WHERE user_id = $user_id";
        $conn->query($sql);

        $success_message = "Order placed successfully!";
    } else {
        $error_message = "Error: " . $sql . "<br>" . $conn->error;
    }

    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HRM | Checkout</title>
    <link rel="stylesheet" href="style.css">
    <style>
        /* style.css */

body {
    font-family: Arial, sans-serif;
    margin: 0;
    padding: 0;
    background-color: #f2f2f2;
}

h2 {
    color: #333;
}

.checkout {
    background-color: #fff;
    padding: 20px;
    margin: 20px auto;
    max-width: 800px;
    border-radius: 5px;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
}

table {
    width: 100%;
    border-collapse: collapse;
}

th, td {
    padding: 10px;
    text-align: left;
}

th {
    background-color: #f2f2f2;
}

tr:nth-child(even) {
    background-color: #f9f9f9;
}

.total {
    font-weight: bold;
}

.btn {
    display: inline-block;
    background-color: #333;
    color: #fff;
    padding: 8px 16px;
    border: none;
    border-radius: 3px;
    text-decoration: none;
    cursor: pointer;
}

.btn:hover {
    background-color: #767576;
}

    </style>
</head>
<body>
    <?php include 'header.php'; ?>

    <main>
        <section class="checkout">
            <h2>Checkout</h2>
            <?php
            if (isset($error_message)) {
                echo "<p class='error'>" . $error_message . "</p>";
            } elseif (isset($success_message)) {
                echo "<p class='success'>" . $success_message . "</p>";
            } else {
                echo "<form action='" . htmlspecialchars($_SERVER["PHP_SELF"]) . "' method='post'>";
                echo "<table>";
                echo "<tr><th>Product</th><th>Price</th><th>Quantity</th><th>Total</th></tr>";
                $total_amount = 0;
                while ($row = $result->fetch_assoc()) {
                    $item_total = $row['price'] * $row['quantity'];
                    $total_amount += $item_total;
                    echo "<tr>";
                    echo "<td>" . $row['name'] . "</td>";
                    echo "<td>$" . $row['price'] . "</td>";
                    echo "<td>" . $row['quantity'] . "</td>";
                    echo "<td>$" . $item_total . "</td>";
                    echo "<td><a href='remove.php?id=" . $row['id'] . "' class='btn'>Remove</a></td>";
                    echo "</tr>";
                }
                echo "<tr><td colspan='3' class='total'>Total:</td><td class='total'>$" . $total_amount . "</td></tr>";
                echo "</table>";
                echo "<input type='submit' value='Place Order' class='btn'>";
                echo "</form>";
            }
            ?>
        </section>
    </main>

    <?php include 'footer.php'; ?>
</body>
</html>
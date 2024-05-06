<?php
session_start();
include 'db_connect.php';

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

// Add product to the cart
if (isset($_GET['id'])) {
    $product_id = $_GET['id'];
    $user_id = $_SESSION['user_id'];

    // Check if the product is already in the cart
    $sql = "SELECT * FROM cart WHERE user_id = $user_id AND product_id = $product_id";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // Update the quantity
        $row = $result->fetch_assoc();
        $new_quantity = $row['quantity'] + 1;
        $sql = "UPDATE cart SET quantity = $new_quantity WHERE user_id = $user_id AND product_id = $product_id";
    } else {
        // Insert new item into the cart
        $sql = "INSERT INTO cart (user_id, product_id, quantity) VALUES ($user_id, $product_id, 1)";
    }

    if ($conn->query($sql) === TRUE) {
        header('Location: cart.php');
        exit;
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

$conn->close();
?>
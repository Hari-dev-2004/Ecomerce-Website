<?php
session_start();
include 'db_connect.php';

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

// Add product to the wishlist
if (isset($_GET['id'])) {
    $product_id = $_GET['id'];
    $user_id = $_SESSION['user_id'];

    // Check if the product is already in the wishlist
    $sql = "SELECT * FROM wishlist WHERE user_id = $user_id AND product_id = $product_id";
    $result = $conn->query($sql);

    if ($result->num_rows == 0) {
        // Insert new item into the wishlist
        $sql = "INSERT INTO wishlist (user_id, product_id) VALUES ($user_id, $product_id)";

        if ($conn->query($sql) === TRUE) {
            header('Location: wishlist.php');
            exit;
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
    }
}

$conn->close();
?>
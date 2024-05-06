<?php
session_start();
include 'db_connect.php';

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

// Remove product from the wishlist
if (isset($_GET['id'])) {
    $product_id = $_GET['id'];
    $user_id = $_SESSION['user_id'];

    $sql = "DELETE FROM wishlist WHERE user_id = $user_id AND product_id = $product_id";

    if ($conn->query($sql) === TRUE) {
        header('Location: wishlist.php');
        exit;
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

$conn->close();
?>
<?php
session_start();
include 'db_connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve order ID and cancellation reason from the form data
    $order_id = $_POST['orderId'];
    $reason = $_POST['reason'];

    // Update orders table with cancellation reason and status
    $sql = "UPDATE orders SET status = 'canceled', reason = '$reason' WHERE id = $order_id";
    if ($conn->query($sql) === TRUE) {
        // Order canceled successfully
        echo "Order Canceled";
        exit;
    } else {
        echo "Error updating record: " . $conn->error;
    }
}
?>

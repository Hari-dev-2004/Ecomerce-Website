<?php
session_start();
include 'db_connect.php';

// Check if the user is an admin
if (!isset($_SESSION['admin']) || $_SESSION['admin'] !== true) {
    header('Location: login.php');
    exit;
}

// Handle order actions
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Handle updating delivery date
    if (isset($_POST['update_delivery_date'])) {
        $order_id = $_POST['order_id'];
        $new_delivery_date = $_POST['delivery_date'];
        $sql = "UPDATE orders SET delivery_date = '$new_delivery_date' WHERE id = $order_id";
        $conn->query($sql);
    }

    // Handle updating order status
    if (isset($_POST['update_order_status'])) {
        $order_id = $_POST['order_id'];
        $new_status = $_POST['status'];
        $sql = "UPDATE orders SET status = '$new_status' WHERE id = $order_id";
        $conn->query($sql);
    }

    // Handle order cancellation
    if (isset($_POST['cancel_order'])) {
        $order_id = $_POST['order_id'];
        $sql = "UPDATE orders SET status = 'cancelled' WHERE id = $order_id";
        $conn->query($sql);
    }

    // Handle other order actions as needed
}

// Fetch orders
$sql_orders = "SELECT * FROM orders";
$result_orders = $conn->query($sql_orders);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Order Management</title>
    <style>
        <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f5f5f5;
            margin: 0;
            padding: 0;
        }

        h2 {
            text-align: center;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
        }

        tr:nth-child(even) {
            background-color: #f2f2f2;
        }

        tr:hover {
            background-color: #ddd;
        }

        form {
            display: inline-block;
        }

        select, input[type="date"], input[type="submit"] {
            padding: 5px;
            border-radius: 5px;
            border: 1px solid #ddd;
            margin-bottom: 5px;
        }

        input[type="submit"] {
            background-color: #333;
            color: #fff;
            border: none;
            cursor: pointer;
        }

        input[type="submit"]:hover {
            background-color: #767576;
        }
    </style>
    </style>
</head>
<body>
<?php include 'adminheader.php'; ?>
    <h2>Order Management</h2>
    <table>
        <tr>
            <th>ID</th>
            <th>User ID</th>
            <th>Total Amount</th>
            <th>Order Date</th>
            <th>Delivery Date</th>
            <th>Status</th>
            <th>Action</th>
        </tr>
        <?php while ($row = $result_orders->fetch_assoc()): ?>
            <tr>
                <td><?php echo $row['id']; ?></td>
                <td><?php echo $row['user_id']; ?></td>
                <td><?php echo $row['total_amount']; ?></td>
                <td><?php echo $row['order_date']; ?></td>
                <td>
                    <form action="" method="post">
                        <input type="hidden" name="order_id" value="<?php echo $row['id']; ?>">
                        <input type="date" name="delivery_date" value="<?php echo $row['delivery_date']; ?>">
                        <input type="submit" name="update_delivery_date" value="Update">
                    </form>
                </td>
                <td>
                    <form action="" method="post">
                        <input type="hidden" name="order_id" value="<?php echo $row['id']; ?>">
                        <select name="status">
                            <option value="pending" <?php echo ($row['status'] == 'pending') ? 'selected' : ''; ?>>Pending</option>
                            <option value="shipped" <?php echo ($row['status'] == 'shipped') ? 'selected' : ''; ?>>Shipped</option>
                            <option value="delivered" <?php echo ($row['status'] == 'delivered') ? 'selected' : ''; ?>>Delivered</option>
                            <option value="cancelled" <?php echo ($row['status'] == 'cancelled') ? 'selected' : ''; ?>>Cancelled</option>
                        </select>
                        <input type="submit" name="update_order_status" value="Update">
                    </form>
                </td>
                <td>
                    <form action="" method="post">
                        <input type="hidden" name="order_id" value="<?php echo $row['id']; ?>">
                        <input type="submit" name="cancel_order" value="Cancel Order">
                    </form>
                </td>
                <td>
                    <!-- Add more actions if needed -->
                </td>
            </tr>
        <?php endwhile; ?>
    </table>
</body>
</html>

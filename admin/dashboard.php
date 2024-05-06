<?php
session_start();
include 'db_connect.php';

// Check if the user is an admin
if (!isset($_SESSION['admin']) || $_SESSION['admin'] !== true) {
    header('Location: login.php');
    exit;
}

// Handle user deletion
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['delete_user'])) {
    $user_id = $_POST['delete_user'];
    $sql = "DELETE FROM users WHERE id = $user_id";
    $conn->query($sql);
}

// Handle product deletion
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['delete_product'])) {
    $product_id = $_POST['delete_product'];
    $sql = "DELETE FROM products WHERE id = $product_id";
    $conn->query($sql);
}

// Handle order status update
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update_order_status'])) {
    $order_id = $_POST['order_id'];
    $new_status = $_POST['status'];
    $sql = "UPDATE orders SET status = '$new_status' WHERE id = $order_id";
    $conn->query($sql);
}

// Fetch users
$sql_users = "SELECT * FROM users";
$result_users = $conn->query($sql_users);

// Fetch products
$sql_products = "SELECT * FROM products";
$result_products = $conn->query($sql_products);

// Fetch orders
$sql_orders = "SELECT * FROM orders";
$result_orders = $conn->query($sql_orders);
?>

<!DOCTYPE html>
<html>
<head>
    <title>HRM Admin</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f5f5f5;
            margin: 0;
            padding: 0;
        }

        h1, h2 {
            text-align: center;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
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

        select {
            padding: 5px;
            border-radius: 5px;
            border: 1px solid #ddd;
        }

        input[type="submit"] {
            padding: 5px 10px;
            border: none;
            border-radius: 5px;
            background-color: #333;
            color: #fff;
            cursor: pointer;
        }

        input[type="submit"]:hover {
            background-color: #767576;
        }
    </style>
    
</head>
<body>
<?php include 'adminheader.php'; ?>
    <!-- Users Table -->
    <h2>Users</h2>
    <table>
        <tr>
            <th>ID</th>
            <th>Username</th>
            <th>Email</th>
            <th>Action</th>
        </tr>
        <?php while ($row = $result_users->fetch_assoc()): ?>
            <tr>
                <td><?php echo $row['id']; ?></td>
                <td><?php echo $row['username']; ?></td>
                <td><?php echo $row['email']; ?></td>
                <td>
                    <form action="" method="post">
                        <input type="hidden" name="delete_user" value="<?php echo $row['id']; ?>">
                        <input type="submit" value="Delete">
                    </form>
                </td>
            </tr>
        <?php endwhile; ?>
    </table>

    <!-- Products Table -->
    <h2>Products</h2>
    <table>
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Description</th>
            <th>Action</th>
        </tr>
        <?php while ($row = $result_products->fetch_assoc()): ?>
            <tr>
                <td><?php echo $row['id']; ?></td>
                <td><?php echo $row['name']; ?></td>
                <td><?php echo $row['description']; ?></td>
                <td>
                    <form action="" method="post">
                        <input type="hidden" name="delete_product" value="<?php echo $row['id']; ?>">
                        <input type="submit" value="Delete">
                    </form>
                </td>
            </tr>
        <?php endwhile; ?>
    </table>

    <!-- Orders Table -->
    <h2>Orders</h2>
    <table>
        <tr>
            <th>ID</th>
            <th>User ID</th>
            <th>Total Amount</th>
            <th>Status</th>
            <th>Action</th>
        </tr>
        <?php while ($row = $result_orders->fetch_assoc()): ?>
            <tr>
                <td><?php echo $row['id']; ?></td>
                <td><?php echo $row['user_id']; ?></td>
                <td><?php echo $row['total_amount']; ?></td>
                <td>
                    <form action="" method="post">
                        <input type="hidden" name="order_id" value="<?php echo $row['id']; ?>">
                        <select name="status">
                            <option value="pending">Pending</option>
                            <option value="shipped">Shipped</option>
                            <option value="delivered">Delivered</option>
                        </select>
                        <input type="submit" name="update_order_status" value="Update">
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

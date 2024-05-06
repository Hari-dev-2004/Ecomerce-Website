<?php
session_start();
include 'db_connect.php';

// Check if the user is an admin
if (!isset($_SESSION['admin']) || $_SESSION['admin'] !== true) {
    header('Location: login.php');
    exit;
}

// Handle user actions
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Handle user deletion
    if (isset($_POST['delete_user'])) {
        $user_id = $_POST['delete_user'];
        $sql = "DELETE FROM users WHERE id = $user_id";
        if ($conn->query($sql) === TRUE) {
            // User deleted successfully
            header("Refresh:0");
            exit;
        } else {
            // Error deleting user
            echo "Error: " . $conn->error;
        }
    }

    // Handle user status update
    if (isset($_POST['update_status'])) {
        $user_id = $_POST['user_id'];
        $new_status = $_POST['status'];
        $sql = "UPDATE users SET status = '$new_status' WHERE id = $user_id";
        if ($conn->query($sql) === TRUE) {
            // User status updated successfully
            header("Refresh:0");
            exit;
        } else {
            // Error updating user status
            echo "Error: " . $conn->error;
        }
    }
}

// Fetch users
$sql_users = "SELECT * FROM users";
$result_users = $conn->query($sql_users);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Customer Management</title>
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

        select, input[type="submit"] {
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
</head>
<body>
<?php include 'adminheader.php'; ?>
    <h2>Customer Management</h2>
    <table>
        <tr>
            <th>ID</th>
            <th>Username</th>
            <th>Email</th>
            <th>Phone</th>
            <th>Address</th>
            <th>City</th>
            <th>State</th>
            <th>Zip</th>
            <th>Status</th>
            <th>Action</th>
        </tr>
        <?php while ($row = $result_users->fetch_assoc()): ?>
            <tr>
                <td><?php echo $row['id']; ?></td>
                <td><?php echo $row['username']; ?></td>
                <td><?php echo $row['email']; ?></td>
                <td><?php echo $row['phone']; ?></td>
                <td><?php echo $row['address']; ?></td>
                <td><?php echo $row['city']; ?></td>
                <td><?php echo $row['state']; ?></td>
                <td><?php echo $row['zip']; ?></td>
                <td>
                    <form action="" method="post">
                        <input type="hidden" name="user_id" value="<?php echo $row['id']; ?>">
                        <select name="status">
                            <option value="active" <?php if ($row['status'] == 'active') echo 'selected'; ?>>Active</option>
                            <option value="inactive" <?php if ($row['status'] == 'inactive') echo 'selected'; ?>>Inactive</option>
                        </select>
                        <input type="submit" name="update_status" value="Update">
                    </form>
                </td>
                <td>
                    <form action="" method="post">
                        <input type="hidden" name="delete_user" value="<?php echo $row['id']; ?>">
                        <input type="submit" value="Delete">
                    </form>
                </td>
            </tr>
        <?php endwhile; ?>
    </table>
</body>
</html>

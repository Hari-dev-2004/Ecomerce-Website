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
    // Handle admin deletion
    if (isset($_POST['delete_admin'])) {
        $admin_id = $_POST['delete_admin'];
        $sql = "DELETE FROM admin WHERE id = $admin_id";
        if ($conn->query($sql) === TRUE) {
            // Admin deleted successfully
            header("Refresh:0");
            exit;
        } else {
            // Error deleting admin
            echo "Error: " . $conn->error;
        }
    }

    // Handle password update
    if (isset($_POST['update_password'])) {
        $admin_id = $_POST['admin_id'];
        $new_password = $_POST['password'];
        $sql = "UPDATE admin SET password = '$new_password' WHERE id = $admin_id";
        if ($conn->query($sql) === TRUE) {
            // Password updated successfully
            header("Refresh:0");
            exit;
        } else {
            // Error updating password
            echo "Error: " . $conn->error;
        }
    }

    // Handle adding new admin
    if (isset($_POST['add_admin'])) {
        $username = $_POST['username'];
        $email = $_POST['email'];
        $password = $_POST['password']; // Note: It's a good practice to hash passwords before storing them
        // Hash the password before storing it in the database
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        // Insert new admin into the database
        $sql = "INSERT INTO admin (admin_id, password) VALUES ('$username', '$password')";
        if ($conn->query($sql) === TRUE) {
            // Admin added successfully
            header("Refresh:0");
            exit;
        } else {
            // Error adding admin
            echo "Error: " . $conn->error;
        }
    }
}

// Fetch admins
$sql_admins = "SELECT * FROM admin";
$result_admins = $conn->query($sql_admins);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin Management</title>
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

        input[type="password"], input[type="submit"], input[type="text"], input[type="email"] {
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
    <h2>Admin Management</h2>
    <form action="" method="post">
        <label for="username">Username:</label>
        <input type="text" id="username" name="username" required><br>
        <label for="password">Password:</label>
        <input type="password" id="password" name="password" required><br>
        <input type="submit" name="add_admin" value="Add Admin">
    </form>

    <table>
        <tr>
            <th>ID</th>
            <th>Username</th>
            <th>Email</th>
            <th>Password</th>
            <th>Action</th>
        </tr>
        <?php while ($row = $result_admins->fetch_assoc()): ?>
            <tr>
                <td><?php echo $row['id']; ?></td>
                <td><?php echo $row['admin_id']; ?></td>
                
                <td>
                    <form action="" method="post">
                        <input type="hidden" name="admin_id" value="<?php echo $row['id']; ?>">
                        <input type="password" name="password" placeholder="Enter new password" required>
                        <input type="submit" name="update_password" value="Update">
                    </form>
                </td>
                <td>
                    <form action="" method="post">
                        <input type="hidden" name="delete_admin" value="<?php echo $row['id']; ?>">
                        <input type="submit" value="Delete">
                    </form>
                </td>
            </tr>
        <?php endwhile; ?>
    </table>
</body>
</html>

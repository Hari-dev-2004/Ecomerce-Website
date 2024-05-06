<?php
session_start();
include 'db_connect.php'; // Include the file with database connection logic

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $admin_id = $_POST['admin_id'];
    $password = $_POST['password'];

    // Perform admin authentication
    $sql = "SELECT * FROM admin WHERE admin_id = '$admin_id' AND password = '$password'";
    $result = $conn->query($sql);

    if ($result->num_rows == 1) {
        // Admin authenticated, set session variable and redirect to admin dashboard
        $_SESSION['admin'] = true;
        header('Location: dashboard.php');
        exit;
    } else {
        // Invalid credentials, display error message
        $error_message = "Invalid admin ID or password. Please try again.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin Login</title>
    <style>
        body {
    font-family: Arial, sans-serif;
    background-color: #f5f5f5;
    margin: 0;
    padding: 0;
}

h1 {
    text-align: center;
}

form {
    max-width: 400px;
    margin: 20px auto;
    padding: 20px;
    border: 1px solid #ccc;
    border-radius: 5px;
    background-color: #f9f9f9;
}

form label {
    display: block;
    margin-bottom: 10px;
}

form input[type="text"],
form input[type="password"],
form input[type="submit"] {
    width: 100%;
    padding: 10px;
    margin-bottom: 10px;
    border: 1px solid #ccc;
    border-radius: 3px;
}

form input[type="submit"] {
    background-color: #333;
    color: white;
    border: none;
    cursor: pointer;
}

form input[type="submit"]:hover {
    background-color: #767576;
}

.error {
    color: red;
}

    </style>
</head>
<body>
    <h1>Admin Login</h1>
    <?php if (isset($error_message)): ?>
        <p><?php echo $error_message; ?></p>
    <?php endif; ?>
    <form action="" method="post">
        <label for="admin_id">Admin ID:</label>
        <input type="text" id="admin_id" name="admin_id" required><br><br>
        
        <label for="password">Password:</label>
        <input type="password" id="password" name="password" required><br><br>
        
        <input type="submit" value="Login">
    </form>
</body>
</html>

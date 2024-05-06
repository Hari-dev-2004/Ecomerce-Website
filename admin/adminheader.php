<?php
if (isset($_SESSION['admin'])) {
    $username = $_SESSION['admin'];
} else {
    $username = "Guest";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HRM</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <header>
        <nav>
            <div class="logo">
                <a href="dashboard.php"><h1>HRM</h1></a>
            </div>
            <ul>
                <?php if (isset($_SESSION['admin'])) { ?>
                    <li><a href="productmanage.php">Products</a></li>
                    <li><a href="ordermanagement.php">Orders</a></li>
                    <li><a href="usermanagement.php">Customers</a></li>
                    <li><a href="adminmanagement.php">Admins</a></li>
                    <li><a href="logout.php">Logout</a></li>
                <?php } else { ?>
                    <li><a href="admin.php">Login</a></li>
                <?php } ?>
            </ul>
            
        </nav>
    </header>
</body>
</html>

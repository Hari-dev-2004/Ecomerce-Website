<?php
session_start();
include 'db_connect.php';

// Check if the user is an admin
if (!isset($_SESSION['admin']) || $_SESSION['admin'] !== true) {
    header('Location: login.php');
    exit;
}

// Handle product actions
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Add new product
    if (isset($_POST['add_product'])) {
        $name = $_POST['name'];
        $description = $_POST['description'];
        $price = $_POST['price'];
        $image = $_POST['image'];
        $status = $_POST['status'];

        // Insert new product into database
        $sql = "INSERT INTO products (name, description, price, image, status) 
                VALUES ('$name', '$description', '$price', '$image', '$status')";
        if ($conn->query($sql) === TRUE) {
            echo "New product added successfully";
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
    }

    // Remove product
    if (isset($_POST['remove_product'])) {
        $product_id = $_POST['remove_product'];

        // Delete product from database
        $sql = "DELETE FROM products WHERE id = $product_id";
        if ($conn->query($sql) === TRUE) {
            echo "Product removed successfully";
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
    }

    // Update product status
    if (isset($_POST['update_status'])) {
        $product_id = $_POST['product_id'];
        $new_status = $_POST['status'];

        // Update product status in database
        $sql = "UPDATE products SET status = '$new_status' WHERE id = $product_id";
        if ($conn->query($sql) === TRUE) {
            echo "Product status updated successfully";
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Product Management</title>
    <style>
           body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
        }

        h1, h2 {
            text-align: center;
        }

        form {
            margin-bottom: 20px;
        }

        label {
            display: block;
            margin-bottom: 5px;
        }

        input[type="text"],
        input[type="number"],
        textarea,
        select {
            width: 100%;
            padding: 8px;
            margin-bottom: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            box-sizing: border-box;
        }

        select {
            width: 100%;
        }

        input[type="submit"] {
            padding: 10px 20px;
            background-color: #333;
            color: #fff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        input[type="submit"]:hover {
            background-color: #555;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th, td {
            padding: 8px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        th {
            background-color: #f2f2f2;
        }

        tr:hover {
            background-color: #f5f5f5;
        }
    </style>
</head>
<body>
<?php include 'adminheader.php'; ?>
    <h1>Product Management</h1>

    <!-- Add New Product Form -->
    <h2>Add New Product</h2>
    <form action="" method="post">
        <label for="name">Name:</label><br>
        <input type="text" id="name" name="name" required><br>
        <label for="description">Description:</label><br>
        <textarea id="description" name="description"></textarea><br>
        <label for="price">Price:</label><br>
        <input type="number" id="price" name="price" step="0.01" required><br>
        <label for="image">Image URL:</label><br>
        <input type="text" id="image" name="image"><br>
        <label for="status">Status:</label><br>
        <select id="status" name="status">
            <option value="in_stock">In Stock</option>
            <option value="out_of_stock">Out of Stock</option>
        </select><br>
        <input type="submit" name="add_product" value="Add Product">
    </form>

    <!-- Product List -->
    <h2>Product List</h2>
    <table>
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Description</th>
            <th>Price</th>
            <th>Image</th>
            <th>Status</th>
            <th>Actions</th>
        </tr>
        <?php
        $sql = "SELECT * FROM products";
        $result = $conn->query($sql);
        while ($row = $result->fetch_assoc()): ?>
            <tr>
                <td><?php echo $row['id']; ?></td>
                <td><?php echo $row['name']; ?></td>
                <td><?php echo $row['description']; ?></td>
                <td><?php echo $row['price']; ?></td>
                <td><?php echo $row['image']; ?></td>
                <td><?php echo $row['status']; ?></td>
                <td>
                    <form action="" method="post">
                        <input type="hidden" name="remove_product" value="<?php echo $row['id']; ?>">
                        <input type="submit" value="Remove">
                    </form>
                    <form action="" method="post">
                        <input type="hidden" name="product_id" value="<?php echo $row['id']; ?>">
                        <select name="status">
                            <option value="in_stock">In Stock</option>
                            <option value="out_of_stock">Out of Stock</option>
                        </select>
                        <input type="submit" name="update_status" value="Update">
                    </form>
                </td>
            </tr>
        <?php endwhile; ?>
    </table>
</body>
</html>

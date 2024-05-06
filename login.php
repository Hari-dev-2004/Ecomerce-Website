<?php
session_start();
include 'db_connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $identifier = $_POST['identifier']; // Accepts either email or phone
    $password = $_POST['password'];

    // Retrieve user from database using email or phone
    $sql = "SELECT * FROM users WHERE email = '$identifier' OR phone = '$identifier'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();

        // Verify the password
        if (password_verify($password, $row['password'])) {
            // Login successful
            $_SESSION['user_id'] = $row['id'];
            $_SESSION['username'] = $row['username'];
            header("Location: index.php");
            exit;
        } else {
            $error_message = "Invalid email, phone, or password.";
        }
    } else {
        $error_message = "Invalid email, phone, or password.";
    }

    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HRM | Login</title>
    <link rel="stylesheet" href="style.css">
    <style>
        /* Add the registration form styles */
        .login {
            max-width: 400px;
            margin: 0 auto;
            padding: 20px;
            border: 1px solid #ccc;
            border-radius: 5px;
            background-color: #f9f9f9;
        }

        .login h2 {
            text-align: center;
            margin-bottom: 20px;
        }

        .login form {
            display: flex;
            flex-direction: column;
        }

        .login label {
            margin-bottom: 5px;
        }

        .login input[type="text"],
        .login input[type="password"],
        .login input[type="submit"] {
            padding: 10px;
            margin-bottom: 10px;
            border: 1px solid #ccc;
            border-radius: 3px;
        }

        .login input[type="submit"] {
            background-color: #333;
            color: white;
            cursor: pointer;
        }

        .login input[type="submit"]:hover {
            background-color: #767576;
        }
        .login a{
            color: #333;
            text-decoration: solid;
            cursor: pointer;
        }
        
        .error {
            color: red;
            margin-bottom: 10px;
        }
    </style>
</head>
<body>
    <?php include 'header.php'; ?>

    <main>
        <section class="login">
            <h2>Login</h2>
            <?php
            if (isset($error_message)) {
                echo "<p class='error'>" . $error_message . "</p>";
            }
            ?>
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post">
                <label for="identifier">Email or Phone:</label>
                <input type="text" id="identifier" name="identifier" required>

                <label for="password">Password:</label>
                <input type="password" id="password" name="password" required>

                <input type="submit" value="Login">
                <b><a href="register.php">Register</a></b>
            </form>
        </section>
    </main>

    <?php include 'footer.php'; ?>
</body>
</html>

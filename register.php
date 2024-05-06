<?php
session_start();
include 'db_connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $phone = $_POST['phone'];

    // Validate and sanitize input data
    $username = filter_var($username, FILTER_SANITIZE_STRING);
    $email = filter_var($email, FILTER_SANITIZE_EMAIL);
    $phone = filter_var($phone, FILTER_SANITIZE_STRING);

    // Password requirements
    $uppercase = preg_match('@[A-Z]@', $password);
    $lowercase = preg_match('@[a-z]@', $password);
    $specialchar = preg_match('@[^\w]@', $password);

    if (!$uppercase || !$lowercase || !$specialchar || strlen($password) < 8) {
        $error_message = "Password must contain at least 8 characters including at least one uppercase letter, one lowercase letter, and one special character.";
    } else {
        // Check if email or phone number already exists
        $sql = "SELECT * FROM users WHERE email = '$email' OR phone = '$phone'";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            $error_message = "Email or phone number already exists.";
        } else {
            // Hash the password
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);

            // Insert new user into the database
            $sql = "INSERT INTO users (username, email, password, phone) VALUES ('$username', '$email', '$hashed_password', '$phone')";

            if ($conn->query($sql) === TRUE) {
                $_SESSION['success_message'] = "Registration successful! You can now log in.";
                header("Location: login.php");
                exit();
            } else {
                $error_message = "Error: " . $sql . "<br>" . $conn->error;
            }
        }
    }

    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HRM | Register</title>
    <link rel="stylesheet" href="style.css">
    <style>
        .registration {
            max-width: 400px;
            margin: 0 auto;
            padding: 20px;
            border: 1px solid #ccc;
            border-radius: 5px;
            background-color: #f9f9f9;
        }

        .registration h2 {
            text-align: center;
            margin-bottom: 20px;
        }

        .registration form {
            display: flex;
            flex-direction: column;
        }

        .registration label {
            margin-bottom: 5px;
        }

        .registration input[type="text"],
        .registration input[type="email"],
        .registration input[type="password"],
        .registration input[type="tel"],
        .registration input[type="submit"] {
            padding: 10px;
            margin-bottom: 10px;
            border: 1px solid #ccc;
            border-radius: 3px;
        }

        .registration input[type="submit"] {
            background-color: #333;
            color: white;
            cursor: pointer;
        }

        .registration input[type="submit"]:hover {
            background-color:  #767576;
        }

        .registration a{
            color: #333;
            text-decoration: solid;
            cursor: pointer;
        }
        
        .error {
            color: red;
            margin-bottom: 10px;
        }

        .success {
            color: green;
            margin-bottom: 10px;
        }
    </style>
</head>
<body>
    <?php include 'header.php'; ?>

    <main>
        <section class="registration">
            <h2>Register</h2>
            <?php
            if (isset($error_message)) {
                echo "<p class='error'>" . $error_message . "</p>";
            }
            ?>
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post" id="registrationForm">
                <label for="username">Username:</label>
                <input type="text" id="username" name="username" required>

                <label for="email">Email:</label>
                <input type="email" id="email" name="email" required>

                <label for="password">Password:</label>
                <input type="password" id="password" name="password" required>
                <small>Password must contain at least 8 characters including at least one uppercase letter, one lowercase letter, and one special character.</small>

                <label for="phone">Phone Number:</label>
                <input type="tel" id="phone" name="phone">

                <input type="submit" value="Register">
                <b><a href="login.php">Login</a></b>
            </form>
        </section>
    </main>

    <?php include 'footer.php'; ?>

    <script>
        // Check if email or phone already exists
        <?php if(isset($error_message) && strpos($error_message, "Email or phone number already exists.") !== false): ?>
            alert("Email or phone number already exists. Please use a different email or phone number.");
        <?php endif; ?>
    </script>
</body>
</html>

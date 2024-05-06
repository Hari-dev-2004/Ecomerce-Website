<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Us</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }

        .container {
            max-width: 600px;
            margin: 20px auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        h1 {
            text-align: center;
            margin-bottom: 20px;
        }

        form {
            margin-bottom: 20px;
        }

        label {
            display: block;
            margin-bottom: 5px;
        }

        input[type="text"],
        input[type="email"],
        textarea {
            width: 100%;
            padding: 10px;
            margin-bottom: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
        }

        textarea {
            height: 100px;
        }

        input[type="submit"] {
            background-color: #333;
            color: #fff;
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        input[type="submit"]:hover {
            background-color: #b4afb4;
        }

        .error {
            color: red;
            margin-bottom: 10px;
        }
    </style>
</head>
<body>
<?php include 'header.php'; ?>
    <div class="container">
        <h1>Contact Us</h1>
        <?php
        // Include the database connection file
        include 'db_connect.php';

        // Define variables and initialize with empty values
        $name = $email = $message = "";
        $name_err = $email_err = $message_err = "";

        // Processing form data when form is submitted
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            // Validate name
            if (empty(trim($_POST["name"]))) {
                $name_err = "Please enter your name.";
            } else {
                $name = trim($_POST["name"]);
            }

            // Validate email
            if (empty(trim($_POST["email"]))) {
                $email_err = "Please enter your email address.";
            } else {
                $email = trim($_POST["email"]);
            }

            // Validate message
            if (empty(trim($_POST["message"]))) {
                $message_err = "Please enter your message.";
            } else {
                $message = trim($_POST["message"]);
            }

            // If there are no errors, insert data into database
            if (empty($name_err) && empty($email_err) && empty($message_err)) {
                $sql = "INSERT INTO contact (username, email, message) VALUES (?, ?, ?)";
                
                if ($stmt = $conn->prepare($sql)) {
                    $stmt->bind_param("sss", $param_name, $param_email, $param_message);
                    
                    // Set parameters
                    $param_name = $name;
                    $param_email = $email;
                    $param_message = $message;
                    
                    // Attempt to execute the prepared statement
                    if ($stmt->execute()) {
                        echo "<p>Your message has been sent. We'll get back to you soon.</p>";
                    } else {
                        echo "<p>Oops! Something went wrong. Please try again later.</p>";
                    }
                    
                    // Close statement
                    $stmt->close();
                }
            }
        }
        ?>

        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <div>
                <label for="name">Name:</label>
                <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($name); ?>">
                <span class="error"><?php echo $name_err; ?></span>
            </div>
            <div>
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($email); ?>">
                <span class="error"><?php echo $email_err; ?></span>
            </div>
            <div>
                <label for="message">Message:</label>
                <textarea id="message" name="message"><?php echo htmlspecialchars($message); ?></textarea>
                <span class="error"><?php echo $message_err; ?></span>
            </div>
            <div>
                <input type="submit" value="Submit">
            </div>
        </form>
    </div>
</body>
</html>

<?php
session_start();
include 'db_connect.php';

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get user information
$user_id = $_SESSION['user_id'];
$sql = "SELECT username, email, phone FROM users WHERE id = $user_id";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $name = $row['username'];
    $email = $row['email'];
    $phone = $row['phone'];
}

// Update personal information
if (isset($_POST['update_personal_info'])) {
    $name = $_POST['username'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];

    $sql = "UPDATE users SET username = '$name', email = '$email', phone = '$phone' WHERE id = $user_id";
    $conn->query($sql);
    header("Location: profile.php"); // Redirect to profile page
    exit;
}
?>

<link rel="stylesheet" href="styles.css">

<!-- HTML form for editing personal information -->
<form method="post">
    <label for="username">Name:</label>
    <input type="text" name="username" value="<?php echo $name; ?>">

    <label for="email">Email:</label>
    <input type="email" name="email" value="<?php echo $email; ?>">

    <label for="phone">Phone:</label>
    <input type="tel" name="phone" value="<?php echo $phone; ?>">

    <input type="submit" name="update_personal_info" value="Update Personal Information">
</form>

<?php
// Close the database connection
$conn->close();
?>
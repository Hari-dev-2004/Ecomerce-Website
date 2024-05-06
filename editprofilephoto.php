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
$sql = "SELECT profile_photo FROM users WHERE id = $user_id";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $profile_photo = $row['profile_photo'];
}

// Update profile photo
if (isset($_POST['update_profile_photo'])) {
    $upload_dir = 'H:\xampp\htdocs\hmr';
    $file_name = basename($_FILES['profile_photo']['name']);
    $file_path = $upload_dir . $file_name;

    if (move_uploaded_file($_FILES['profile_photo']['tmp_name'], $file_path)) {
        $sql = "UPDATE users SET profile_photo = '$file_name' WHERE id = $user_id";
        $conn->query($sql);
        header("Location: profile.php"); // Redirect to profile page
        exit;
    }
}
?>
<link rel="stylesheet" href="styles.css">
<!-- HTML form for editing profile photo -->
<form method="post" enctype="multipart/form-data">
    <input type="file" name="profile_photo">
    <input type="submit" name="update_profile_photo" value="Update Profile Photo">
</form>

<?php
// Close the database connection
$conn->close();
?>
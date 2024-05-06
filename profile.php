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
$sql = "SELECT * FROM users WHERE id = $user_id";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $name = $row['username'];
    $email = $row['email'];
    $phone = $row['phone'];
    $profile_photo = $row['profile_photo'];
}

// Get address information
$sql = "SELECT * FROM users WHERE id = $user_id";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $address_row = $result->fetch_assoc();
    $address = $address_row['address'];
    $city = $address_row['city'];
    $state = $address_row['state'];
    $zip = $address_row['zip'];
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Profile Page</title>
    <link rel="stylesheet" href="style.css">
    <style>
        /* General Styles */
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
        }

        .container {
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
        }

        h1, h2 {
            color: #333;
        }

        /* Profile Image Styles */
        .profile-image {
            width: 150px;
            height: 150px;
            border-radius: 50%;
            object-fit: cover;
        }

        .initial-image {
            width: 150px;
            height: 150px;
            border-radius: 50%;
            background-color: #333;
            color: #fff;
            font-size: 72px;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        /* Profile Section Styles */
        .profile-section {
            background-color: #f5f5f5;
            padding: 20px;
            margin-bottom: 20px;
            border-radius: 5px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            text-align: center;
        }

        .profile-section h2 {
            margin-top: 0;
        }

        .profile-section p {
            margin: 10px 0;
        }

        /* Link Styles */
        .edit-link, .add-link {
            display: inline-block;
            background-color: #333;
            color: #fff;
            text-decoration: none;
            padding: 8px 16px;
            border-radius: 5px;
            transition: background-color 0.3s ease;
        }

        .edit-link:hover, .add-link:hover {
            background-color: #555;
        }
    </style>
</head>
<body>
    <?php include 'header.php'; ?>
    <div class="container">
        <h1>My Profile</h1>
        <div class="profile-section">
            <h2>Profile Image</h2>
            <?php if (isset($profile_photo) && !empty($profile_photo)): ?>
                <img src="H:\xampp\htdocs\hmr<?php echo $profile_photo; ?>" alt="Profile Photo" class="profile-image">
            <?php else: ?>
                <div class="initial-image"><?php echo strtoupper(substr($name, 0, 1)); ?></div>
            <?php endif; ?>
            <a href="editprofilephoto.php" class="edit-link">Edit Profile Photo</a>
        </div>
        <div class="profile-section">
            <h2>Personal Information</h2>
            <p><strong>Name:</strong> <?php echo $name; ?></p>
            <p><strong>Email:</strong> <?php echo $email; ?></p>
            <p><strong>Phone:</strong> <?php echo $phone; ?></p>
            <a href="editpersonalinformation.php" class="edit-link">Edit Personal Information</a>
        </div>
        <div class="profile-section">
            <h2>Address Information</h2>
            <?php if (isset($address)): ?>
                <p><strong>Address:</strong> <?php echo $address; ?></p>
                <p><strong>City:</strong> <?php echo $city; ?></p>
                <p><strong>State:</strong> <?php echo $state; ?></p>
                <p><strong>Zip:</strong> <?php echo $zip; ?></p>
                <a href="editaddress.php" class="edit-link">Edit Address</a>
            <?php else: ?>
                <p>You haven't added an address yet.</p>
                <a href="editaddress.php" class="add-link">Add Address</a>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>

<?php
// Close the database connection
$conn->close();
?>
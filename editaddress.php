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

// Get address information
$sql = "SELECT address, city, state, zip FROM users WHERE id = $user_id";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $address_row = $result->fetch_assoc();
    $address = $address_row['address'];
    $city = $address_row['city'];
    $state = $address_row['state'];
    $zip = $address_row['zip'];
}

// Update address information
if (isset($_POST['update_address'])) {
    $address = $_POST['address'];
    $city = $_POST['city'];
    $state = $_POST['state'];
    $zip = $_POST['zip'];

    $sql = "UPDATE users SET address = '$address', city = '$city', state = '$state', zip = '$zip' WHERE id = $user_id";
    $conn->query($sql);
    header("Location: profile.php"); // Redirect to profile page
    exit;
}

// Array of Indian states
$states = array(
    'Andhra Pradesh', 'Arunachal Pradesh', 'Assam', 'Bihar', 'Chhattisgarh', 'Goa', 'Gujarat', 'Haryana', 'Himachal Pradesh',
    'Jharkhand', 'Karnataka', 'Kerala', 'Madhya Pradesh', 'Maharashtra', 'Manipur', 'Meghalaya', 'Mizoram', 'Nagaland', 'Odisha',
    'Punjab', 'Rajasthan', 'Sikkim', 'Tamil Nadu', 'Telangana', 'Tripura', 'Uttar Pradesh', 'Uttarakhand', 'West Bengal'
);
?>

<link rel="stylesheet" href="styles.css">

<!-- HTML form for editing address information -->
<form method="post">
    <label for="address">Address:</label>
    <input type="text" name="address" value="<?php echo $address; ?>">

    <label for="city">City:</label>
    <input type="text" name="city" value="<?php echo $city; ?>">

    <label for="state">State:</label>
    <select name="state" id="state">
        <?php foreach ($states as $state_option): ?>
            <option value="<?php echo $state_option; ?>" <?php if ($state_option == $state) echo 'selected'; ?>><?php echo $state_option; ?></option>
        <?php endforeach; ?>
    </select>

    <label for="zip">Zip:</label>
    <input type="text" name="zip" value="<?php echo $zip; ?>">

    <input type="submit" name="update_address" value="Update Address">
</form>

<?php
// Close the database connection
$conn->close();
?>
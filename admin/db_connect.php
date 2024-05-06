<?php
$servername = "sql207.infinityfree.com";
$username = "if0_36464878";
$password = "8WU5RESIab3VqHT";
$database = "if0_36464878_hmr";

// Create connection
$conn = new mysqli($servername, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
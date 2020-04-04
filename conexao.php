<?php
$servername = "localhost";
$username = "Gabriel Prediger";
$password = "462600";

// Create connection
$conn = new mysqli($servername, $username, $password);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
echo "Connected successfully";
?>
<?php
$servername = "localhost";
$username = "root"; // change it to your DB username
$password = ""; // change it to your DB password
$dbname = "trustcare"; // change to your DB name

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>

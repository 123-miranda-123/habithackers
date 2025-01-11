<?php

$servername = "localhost"; // Change to your remote host when hosting online
$username = "root"; // Change to your remote database username
$password = ""; // Change to your remote database password
$dbname = "habithub";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

?>
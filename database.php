<?php
$hostName = "localhost";
$dbUser = "root";
$dbPassword = "root";
$dbName = "habithub";
$conn = mysqli_connect($hostName, $dbUser, $dbPassword, $dbName);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
?>
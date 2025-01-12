<?php
/*$hostName = "sql206.infinityfree.com"; 
$dbUser = "if0_38083411";
$dbPassword = "89WHnn05SP4S6Z";
$dbName = "if0_38083411_habithub";
$conn = mysqli_connect($hostName, $dbUser, $dbPassword, $dbName);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
*/


$hostName = "localhost"; 
$dbUser = "root";
$dbPassword = "root";
$dbName = "habithub";
$conn = mysqli_connect($hostName, $dbUser, $dbPassword, $dbName);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
?>
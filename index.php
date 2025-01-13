<?php
session_start();
if (isset($_SESSION["user_role"])) {
    if ($_SESSION["user_role"] === "admin") {
        header("Location: admin-dashboard.php");
        exit();
    } else if ($_SESSION["user_role"] === "captain") {
        header("Location: captain-dashboard.php");
        exit();
    } else if ($_SESSION["user_role"] === "member") {
        header("Location: member-dashboard.php");
        exit();
    } else {
        header("Location: index.html");
        exit();
    }
}
header("Location: index.html");
exit();
?>
<?php
require_once "database.php";

// Ensure ID is provided
if (!isset($_GET["id"])) {
    header("Location: admin-dashboard.php?message=" . urlencode("Invalid user ID."));
    exit();
}

// Retrieve user data for editing
$id = $_GET["id"];
$stmt = $conn->prepare("SELECT name, email, role FROM users WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$stmt->bind_result($name, $email, $role);

if (!$stmt->fetch()) {
    header("Location: admin-dashboard.php?message=" . urlencode("User not found."));
    exit();
}
$stmt->close();

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] === "GET") {


    $sql = "DELETE FROM users WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        header("Location: admin-dashboard.php?message=" . urlencode("User deleted successfully!"));
        exit();
    } else {
        $error = "Error deleting user: " . $stmt->error;
    }
    $stmt->close();
}
?>
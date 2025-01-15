<?php
require_once "database.php";

// Ensure ID is provided
if (!isset($_GET["id"])) {
    header("Location: admin-dashboard.php?message=" . urlencode("Invalid team ID."));
    exit();
}

// Retrieve user data for editing
$t_id = $_GET["id"];
$stmt = $conn->prepare("SELECT name, captain_id FROM teams WHERE id = ?");
$stmt->bind_param("i", $t_id);
$stmt->execute();
$stmt->bind_result($name, $cap_id);

if (!$stmt->fetch()) {
    header("Location: admin-dashboard.php?message=" . urlencode("Team not found."));
    exit();
}
$stmt->close();

// Handle form submission

$sql = "DELETE FROM teams WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $t_id);

if ($stmt->execute()) {
    header("Location: admin-dashboard.php?message=" . urlencode("User deleted successfully!"));
    exit();
} else {
    $error = "Error deleting user: " . $stmt->error;
}
$stmt->close();
?>
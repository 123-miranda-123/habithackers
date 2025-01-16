<?php
require_once "database.php";

// Ensure ID is provided
if (!isset($_GET["id"])) {
    header("Location: captain-dashboard.php?message=" . urlencode("Invalid user ID."));
    exit();
}

// Retrieve user data for editing
$id = $_GET["id"];
$stmt = $conn->prepare("SELECT name, email, role FROM users WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$stmt->bind_result($name, $email, $role);

if (!$stmt->fetch()) {
    header("Location: captain-dashboard.php?message=" . urlencode("User not found."));
    exit();
}
$stmt->close();

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] === "GET") {


    $sql = "DELETE FROM team_members WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        header("Location: captain-dashboard.php?message=" . urlencode("User successfully removed from team."));
        exit();
    } else {
        $error = "Error removing user from team: " . $stmt->error;
    }
    $stmt->close();
}
?>
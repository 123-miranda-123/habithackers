<?php
require_once 'database.php';

// Check if habit is selected
if (isset($_GET['habit_id'])) {
    $habit_id = $_GET['habit_id'];
    
    // Fetch habit details
    $sql = "SELECT * FROM habit_types WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $habit_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $habit = $result->fetch_assoc();
}
?>
<?php
// Handling the progress saving
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $progress = $_POST['progress'];
    $habit_id = $_POST['habit_id'];

    // Update progress in the user_habits table
    $sql = "UPDATE user_habits SET progress = progress + ? WHERE user_id = ? AND habit_type_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("iii", $progress, $_SESSION['user_id'], $habit_id);
    $stmt->execute();
    header("Location: member-dashboard.php"); // Redirect back to dashboard
}
?>
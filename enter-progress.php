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
<!-- Form to enter progress -->
<form action="enter_progress.php" method="POST">
    <label for="progress">Enter Progress:</label>
    <input type="number" name="progress" required>
    <input type="hidden" name="habit_id" value="<?php echo $habit['id']; ?>">
    <button type="submit">Submit Progress</button>
</form>

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
    header("Location: dashboard.php"); // Redirect back to dashboard
}
?>
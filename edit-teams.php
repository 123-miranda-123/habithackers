<?php
require_once "database.php";

// Ensure ID is provided
if (!isset($_GET["id"])) {
    header("Location: admin-dashboard.php?message=" . urlencode("Invalid user ID."));
    exit();
}

// Retrieve user data for editing
$id = $_GET["id"];
$stmt = $conn->prepare("SELECT name, captain_id FROM teams WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$stmt->bind_result($name, $captain_id);

if (!$stmt->fetch()) {
    header("Location: admin-dashboard.php?message=" . urlencode("User not found."));
    exit();
}
$stmt->close();

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if (isset($_POST["submit"])) {
        $name = $_POST["name"];
        $cap_id = $_POST["captain"];
        $c = 'Captain';
        $m = 'Member';
        if ($captain_id !== $cap_id) {
            $sql = "UPDATE users SET role = ? WHERE id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("si",$c, $cap_id) ;
            $stmt->execute();
            $stmt->bind_param("si", $m, $captain_id);
            $stmt->execute();
            $stmt->close();
        }
        $sql = "UPDATE teams SET name = ?, captain_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("si", $name, $cap_id);

        if ($stmt->execute()) {
            header("Location: admin-dashboard.php?message=" . urlencode("User updated successfully!"));
            exit();
        } else {
            $error = "Error updating user: " . $stmt->error;
        }
        $stmt->close();
    } else {
        header("Location: admin-dashboard.php");
        exit();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit User - Habit Hub</title>
    <link href="styles/header.css" rel="stylesheet" type="text/css"/>
    <link href="styles/login.css" rel="stylesheet" type="text/css"/>
    <link rel="icon" href="images/icon.png" type="image/png">
</head>
<body>
<section class="container">
    <form action="" method="POST">
        <h2>Edit Team</h2>
        <input type="hidden" name="id" value="<?php echo htmlspecialchars($id); ?>">

        <div class="input-group">
            <label for="name">Team Name</label>
            <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($name); ?>" required>
        </div>

        <div class="input-group">
            <label for="captain">Team Captain</label>
            <select name="captain" id="captain" required>
                <option value = "" disabled selected>Select Captain</option>
                <?php 
                $sql = "SELECT * FROM users WHERE role = 'Member' OR role = 'Captain'";
                $stmt = $conn->query($sql);
                if (!$stmt) {
                    echo "Invalid query: " . $conn->error;
                    exit();
                }
                while($row = $stmt->fetch_assoc()) {
                    echo "<option value = ". $row["id"] . ">".$row["name"] ."</option>";
                }
                ?>
            </select>
        </div>

        <?php if (isset($error)) : ?>
            <p style="color: red;"><?php echo htmlspecialchars($error); ?></p>
        <?php endif; ?>

        <div class="submit-btn">
            <button id="submit" type="submit" name="submit">Submit</button>
            <button onclick = "window.history.back()" id ="cancel">Cancel</button>
        </div>
    </form>
</section>
</body>
</html>





<?php
    require_once "database.php";
    if (!isset($_GET["id"])) {
        header("Location: admin-dashboard.php");
        exit();
    }
?>

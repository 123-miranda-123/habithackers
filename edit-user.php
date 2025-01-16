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
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $name = $_POST["name"];
    $email = $_POST["email"];
    $role = $_POST["role"];

    $sql = "UPDATE users SET name = ?, email = ?, role = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssi", $name, $email, $role, $id);

    if ($stmt->execute()) {
        header("Location: admin-dashboard.php?message=" . urlencode("User updated successfully!"));
        exit();
    } else {
        $error = "Error updating user: " . $stmt->error;
    }
    $stmt->close();
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
        <h2>Edit User</h2>
        <input type="hidden" name="id" value="<?php echo htmlspecialchars($id); ?>">

        <div class="input-group">
            <label for="name">Full Name</label>
            <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($name); ?>" required>
        </div>

        <div class="input-group">
            <label for="email">Email</label>
            <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($email); ?>" required>
        </div>

        <div class="input-group">
            <label for="role">Account Type</label>
            <select name="role" id="role" required>
                <option value="Member" <?php if ($role === "Member") echo "selected"; ?>>Team Member</option>
                <option value="Captain" <?php if ($role === "Captain") echo "selected"; ?>>Team Captain</option>
                <option value="Admin" <?php if ($role === "Admin") echo "selected"; ?>>Admin</option>
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

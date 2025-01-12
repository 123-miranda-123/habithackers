<?php
    require_once "database.php";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Team - Habit Hub</title>
    <link href="styles/header.css" rel="stylesheet" type="text/css"/>
    <link href="styles/login.css" rel="stylesheet" type="text/css"/>
    <link rel="icon" href="images/icon.png" type="image/png">
</head>
<body>
<section class = "container">

<form action="edit-user.php" method="POST">
            <h2>Edit User</h2>
            <input type = "hidden" name = "id" value = "<?php echo $id; ?>">
            <div class="input-group">
                <label for="name">Full Name</label>
                <input type="text" id="name" name="name" value = "<?php echo $name; ?>" required>
            </div>

            <div class="input-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" value = "<?php echo $email; ?>"required>
            </div>

            <div class="input-group">
                <label for="role">Account Type</label>
                <select name="role" id="role" required>
                    <option value="" disabled selected>Select Role</option>
                    <option value="member">Team Member</option>
                    <option value="captain">Team Captain</option>
                    <option value="admin">Admin</option>
                </select>
            </div>
            
            <div class="submit-btn">
                <button id="submit" type="submit" name="submit">Register</button>
                <button id = "cancel" type="cancel" name = "cancel">Cancel</button>
            </div>
</form>
</section>
</body>
</html>

<?php

$id = "";
$name = "";
$email = "";
$role = "";

if (isset($_POST["cancel"])) {
    header("Location: admin-dashboard.php");
    exit();
}


if (isset($_POST["submit"])) {

    if (!isset($_GET["id"])) {
        header("Location: admin-dashboard.php");
        exit();
    }
    $id = $_GET["id"];

    $sql = "SELECT * FROM users WHERE id = $id";
    $result = $conn->query($sql);
    $row = $result->fetch_assoc();

    if ($row) {
        header("Location: admin-dashboard.php");
        exit();
    }

    $name = $row["name"];
    $email = $row["email"];
    $role = $row["role"];

} else {
    $id = $_POST["id"];
    $name = $_POST["name"];
    $email = $_POST["email"];
    $role = $_POST["role"];

    do {

        $sql = "UPDATE users SET name = '$name', email = '$email', role = '$role' WHERE id = $id";
    
        $result = $conn->query($sql);
        
        if (!$result) {
            header("Location: edit-user.php?message=" . urlencode("Error updating user: " . $conn->error));
            exit();
        }
        header("Location: admin-dashboard.php?message=" . urlencode("User updated successfully!"));
        exit();

    } while (false);
}
?>
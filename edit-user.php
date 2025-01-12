<?php
    require_once "database.php";

    if (isset($_POST["submit"])) {
        $name = $_POST["name"];
        $email = $_POST["email"];
        $role = $_POST["role"];
        $user_id = $_GET["id"];

        $sql = "UPDATE users SET name = ?, email = ?, role = ? WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssi", $name, $email, $role, $user_id);
        if ($stmt->execute()) {
            header("Location: admin-dashboard.php?message=" . urlencode("User updated successfully."));
            exit();
        } else {
            header("Location: admin-dashboard.php?message=" . urlencode("Error updating user: " . $stmt->error));
            exit();
        }
    } else {
        $user_id = $_GET["id"];
        $sql = "SELECT * FROM users WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();
        $name = $user["name"];
        $email = $user["email"];
        $role = $user["role"];
    }
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
            </div>
</form>
</section>
</body>
</html>
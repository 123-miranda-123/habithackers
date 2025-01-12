<?php
session_start();
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
    <form action="new-user.php" method="POST">
        <h2>Create a New User</h2>

        <div id = "message">
        <?php
        if (isset($_GET['message'])) {
            echo '<p>' . htmlspecialchars($_GET['message']) . '</p>';
        }
        ?>
        </div>

        <div class = "input-group">
            <label for="name" >Full Name:</label>
            <input type="text" id="name" name="name" required>
        </div>

        <div class = "input-group">
            <label for="email" >Email:</label>
            <input type="text" id="email" name="email" required>
        </div>

        <div class="input-group">
            <label for="password">Password</label>
            <input type="password" id="password" name="password" required>
        </div>

        <div class="input-group">
            <label for="repeatPassword">Repeat Password</label>
            <input type="password" id="repeatPassword" name="repeatPassword" required>
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

        <div class = "submit-btn">
            <button type="submit" name = "submit">Create User</button>
            <button id = "cancel" type="cancel" name = "cancel">Cancel</button>
        </div>
    </form>
</section>
</body>
</html>

<?php
function validatePassword($password) {
    if (strlen($password) < 8) {
        return "Password must be at least 8 characters long.";
    }
    if (!preg_match("/[A-Z]/", $password)) {
        return "Password must contain at least one uppercase letter.";
    }
    if (!preg_match("/[0-9]/", $password)) {
        return "Password must contain at least one number.";
    }
    if (!preg_match("/[\W_]/", $password)) {
        return "Password must contain at least one special character.";
    }
    return true;
}

if (isset($_POST["cancel"])) {
    header("Location: admin-dashboard.php");
    exit();
}

if (isset($_POST["submit"])) {
    $name = $_POST["name"];
    $email = $_POST["email"];
    $password = $_POST["password"];
    $repeatPassword = $_POST["repeatPassword"];
    $role = $_POST["role"];
    $passwordHash = password_hash($password, PASSWORD_DEFAULT);

    require_once "database.php";
    
    if ($password !== $repeatPassword) {
        header("Location: new-user.php?message=" . urlencode("Passwords do not match."));
        exit();
    } else {
        $passwordValidation = validatePassword($password);
        if ($passwordValidation !== true) {
            header("Location: new-user.php?message=" . urlencode($passwordValidation));
            exit();
        } else {

        // Check database connection
        if (!$conn) {
            die("Connection failed: " . mysqli_connect_error());
        }

        // Check if email already exists
        $sql = "SELECT * FROM users WHERE email = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
        $rowCount = $result->num_rows;
         
        if ($rowCount > 0) {
            header("Location: new-user.php?message=" . urlencode("Email already exists."));
            exit();
         }
         
        // Insert data into database
        $sql = "INSERT INTO users (name, email, password, role) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        if ($stmt) {
            $stmt->bind_param("ssss", $name, $email, $passwordHash, $role); // Bind values into the SQL command
            if ($stmt->execute()) { // Execute the statement
                header("Location: admin-dashboard.php?message=" . urlencode("Successfully created user!"));
                exit();
            } else {
                header("Location: new-user.php?message=" . urlencode("Error executing query: " . $stmt->error));
                exit();
            }
        } else {
            header("Location: new-user.php?message=" . urlencode("Error preparing statement: " . $conn->error));
            exit();
        }
         
        }
    }
}
?>
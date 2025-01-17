<?php
session_start();
require_once "database.php";

// Ensure the user is logged in
if (!isset($_SESSION["user_id"])) {
    header("Location: login.php?message=" . urlencode("Please log in to access your profile."));
    exit();
}

// Fetch user details (if needed for display)
$user_id = $_SESSION["user_id"];
$sql = "SELECT id, name, email, password FROM users WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

// Handle account deletion
if (isset($_POST["delete_account"])) {
    $sql = "DELETE FROM users WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $user_id);
    if ($stmt->execute()) {
        session_destroy();
        header("Location: register.php?message=" . urlencode("Account deleted successfully."));
        exit();
    } else {
        $error_message = "Error deleting account: " . $stmt->error;
    }
}

// Handle password update
if (isset($_POST["update_password"])) {
    $current_password = $_POST["current_password"];
    $new_password = $_POST["new_password"];
    $repeat_password = $_POST["repeat_password"];

    // Validate new password
    if ($new_password !== $repeat_password) {
        $error_message = "New passwords do not match.";
    } elseif (validatePassword($new_password) !== true) {
        $error_message = validatePassword($new_password);
    } else {
        // Check current password
        if (password_verify($current_password, $user["password"])) {
            $new_password_hash = password_hash($new_password, PASSWORD_DEFAULT);
            $sql = "UPDATE users SET password = ? WHERE id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("si", $new_password_hash, $user_id);
            if ($stmt->execute()) {
                $success_message = "Password updated successfully.";
            } else {
                $error_message = "Error updating password: " . $stmt->error;
            }
        } else {
            $error_message = "Current password is incorrect.";
        }
    }
}

// Validate password function
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
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Profile</title>
    <link href="styles/login.css" rel="stylesheet" type="text/css"/>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap');

        button {
            font-family: 'Lexend';
        }

        .button-container {
            display: flex;
            justify-content: center;
            gap: 20px;
            margin-top: 20px;
        }

        .button-container button {
            width: 100px;
            height: 50px;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
        }

        .submit:hover {
            background-color:rgb(81, 153, 235);
        }

        .submit {
            background-color: #57a4fe;
            color: white;
            border: none;
        }

        .cancel {
            background-color: white;
            border: solid 1px #57a4fe;
            color:#57a4fe;
        }
        
        .delete-button {
            width: 200px;
            height: 50px;
            background-color: red;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
        }

        .delete-button:hover {
            background-color: darkred;
        }

        h3 {
            margin-bottom: 20px;
        }
        p {
            font-family: "Poppins", serif;
            font-weight: 400;
            font-style: normal;
        }

        label {
            font-family: "Poppins", serif;
            font-weight: 600;
            font-style: normal;
        }
    </style>
</head>
<body>
    <b class="container">
        <h2>User Profile</h2>
        <?php
        if (isset($error_message)) {
            echo "<p style='color: red;'>$error_message</p>";
        }
        if (isset($success_message)) {
            echo "<p style='color: green;'>$success_message</p>";
        }
        ?>
        <div class="profile-section">
            <p>Hello, <?= htmlspecialchars($user["name"]) ?>!</p>
            <p><?= htmlspecialchars($user["email"]) ?></p>
        </div>
        <br></br>
        <!-- Update Password Form -->
        <form action="user-profile.php" method="POST">
            <h3>Update Password</h3>
            <div class="input-group">
                <label for="current_password">Current Password</label>
                <input type="password" id="current_password" name="current_password" required>
            </div>
            <div class="input-group">
                <label for="new_password">New Password</label>
                <input type="password" id="new_password" name="new_password" required>
            </div>
            <div class="input-group">
                <label for="repeat_password">Repeat New Password</label>
                <input type="password" id="repeat_password" name="repeat_password" required>
            </div>
            <div class="button-container">
                <button type="submit" class = "submit" name="update_password">Update Password</button>
                <button typ="button" class= "cancel" onclick = "window.history.back()">Cancel</button>
            </div>
        </form>

        <!-- Delete Account Form -->
        <form action="user-profile.php" method="POST" onsubmit="return confirm('Are you sure you want to delete your account? This action cannot be undone.');">
            <br>
            <button type="submit" name="delete_account" class="delete-button">Delete Account</button>
        </form>
    </section>
</body>
</html>

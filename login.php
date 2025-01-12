<?php
session_start();
if (isset($_SESSION["user_role"])) {
    if ($_SESSION["user_role"] === "admin") {
        header("Location: admin-dashboard.php");
        exit();
    } else if ($_SESSION["user_role"] === "captain") {
        header("Location: captain-dashboard.php");
        exit();
    } else if ($_SESSION["user_role"] === "member") {
        header("Location: member-dashboard.php");
        exit();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign In - Habit Hub</title>
    <link href="styles/header.css" rel="stylesheet" type="text/css"/>
    <link href="styles/login.css" rel="stylesheet" type="text/css">
    <link rel="icon" href="images/icon.png" type="image/png">
</head>
<body>
    <nav class="header">
        <div class="header-container">
            <a href="index.html">
                <img src="images/Banner 2.png" alt="Habit Hub Logo" class="logo">
            </a>
            <div class="auth-buttons">
                <a href="help.html" id="help">Help</a>
                <a href="register.php" id="register">Get Started</a>
                <a href="login.php" id="login">Sign In</a>
            </div>
        </div>
    </nav>

    <div id="message">
        <?php
        if (isset($_GET['message'])) {
            echo '<p>' . htmlspecialchars($_GET['message']) . '</p>';
        }
        ?>
    </div>

    <div class="container">
        <h2>Sign In</h2>
        <form action="login.php" method="POST">
            <div class="input-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" required>
            </div>
            
            <div class="input-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" required>
            </div>
            
            <div class="submit-btn">
                <button id="submit" type="submit" name="submit">Sign In</button>
            </div>

            <div class="signlink">
                <p>Don't have an account? <a href="register.php">Sign up here</a></p>
            </div>
        </form>
    </div>

</body>
</html>

<?php
if (isset($_POST["submit"])) {
    require_once "database.php";
    $email = $_POST["email"];
    $password = $_POST["password"];

    $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    if ($user && password_verify($password, $user["password"])) {
        session_start();
        $_SESSION["user_id"] = $user["id"];
        $_SESSION["user_name"] = $user["name"];
        $_SESSION["user_role"] = $user["role"];
        if ($user["role"] === "admin") {
            header("Location: admin-dashboard.php");
            exit();
        } else if ($user["role"] === "captain") {
            header("Location: captain-dashboard.php");
            exit();
        } else if ($user["role"] === "member") {
            header("Location: member-dashboard.php");
            exit();
        } else {
            header("Location: login.php?message=" . urlencode("Unknown user role"));
            exit();
        }
    } else {
        header("Location: login.php?message=" . urlencode("Invalid email or password"));
        exit();
    }
}
?>
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
    <style>
    @import url('https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap');
    </style>
</head>
<body>
<section class = "container">
    <form action="new-team.php" method="POST">
        <h2>Create a New Team</h2>

        <div id = "message">
        <?php
        if (isset($_GET['message'])) {
            echo '<p>' . htmlspecialchars($_GET['message']) . '</p>';
        }
        ?>
        </div>

        <div class = "input-group">
            <label for="name" >Team Name</label>
            <input type="text" id="name" name="name" required>
        </div>

        <div class = "input-group">
            <label for="captain" >Team Captain</label>
            <select name="captain" id="captain" required>
                <option value = "" disabled selected>Select Captain</option>
                <?php 
                $sql = "SELECT * FROM users WHERE role = 'Member'";
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

        <div class = "submit-btn">
            <button type="submit" name = "submit">Create Team</button>
            <button id = "cancel" type="button" name = "cancel" a href = "window.history.back()">Cancel</button>
        </div>
        
    </form>
</section>
</body>
</html>

<?php


if (isset($_POST["submit"])) {
    $name = $_POST["name"];
    $captain_id = $_POST["captain"];

    require_once "database.php";
    
    // Check database connection
    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }

    // Check if email already exists
    $sql = "SELECT * FROM teams WHERE name = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $name);
    $stmt->execute();
    $result = $stmt->get_result();
    $rowCount = $result->num_rows;
        
    if ($rowCount > 0) {
        header("Location: new-team.php?message=" . urlencode("Name already exists."));
        exit();
        }

    $sql = "UPDATE users SET role = 'Captain' WHERE id = ?";
    $stmt = $conn->prepare(query: $sql);
    $stmt->bind_param("i", $captain_id) ;
    $stmt->execute();
    $stmt->close();

    // Insert data into database
    $sql = "INSERT INTO teams (name, captain_id) VALUES (?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("si", $name, $captain_id);
    

    if ($stmt->execute()) { // Execute the statement
        header("Location: admin-dashboard.php?message=" . urlencode("Successfully created team!"));
        exit();
    } else {    
        header("Location: new-team.php?message=" . urlencode("Error executing query: " . $stmt->error));
        exit(); 
    }
    
}
?>
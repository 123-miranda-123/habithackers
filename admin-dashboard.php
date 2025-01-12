<?php
session_start();
require_once "database.php";

if ($conn->connect_error) {
    die("Connection failed: " . $connection->connect_error);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Habit Hub</title>
    <link href="styles/header.css" rel="stylesheet" type="text/css"/>
    <link href="styles/manage-dashboard.css" rel="stylesheet" type="text/css"/>
    <link rel="icon" href="images/icon.png" type="image/png">
</head>
<body>
    <nav class="header">
        <div class="header-container">
            <a href="index.php">
                <img src="images/Banner 2.png" alt="Habit Hub Logo" class="logo">
            </a>
            <div class="auth-buttons">
                <a href="help.html" id="help">Help</a>
                <a href="logout.php" id="logout">Logout</a>
            </div>
        </div>
    </nav>

    <section class = "container">
        <h1>Admin Dashboard</h1>
        <h2>Users</h2>
        <button class = "add"><a href = "new-user.php">+ New User</a></button>
        <table class = "users-table">
            <tr>
                <th>User ID</th>
                <th>Name</th>
                <th>Email</th>
                <th>Role</th>
                <th>Actions</th>
            </tr>
            <?php

            $sql = "SELECT * FROM users";
            $result = $conn->query($sql);

            if (! $result) {
                echo "Invalid query: " . $connection->error;
                exit();
            }

            while ($row = $result->fetch_assoc()) {
                echo "<tr>
                <td>" . $row["id"] . "</td>
                <td>" . $row["name"] . "</td>
                <td>" . $row["email"] . "</td>
                <td>" . $row["role"] . "</td>
                <td>
                    <button id = 'edit'><a href = edit-user.php>Edit</a></button>
                    <button id = 'delete'>Delete</button>
                </td>
            </tr>";
            }
            
            ?>
        </table>


        <h2>Teams</h2>
        <table class = "teams-table">
            <tr>
                <th>Team ID</th>
                <th>Team Name</th>
                <th>Team Captain </th>
                <th>Role</th>
                <th>Actions</th>
            </tr>
            <tr>
                <td>1</td>
                <td>Habit Hackers</td>
                <td>John Doe</td>
                <td>Team Captain</td>
                <td>
                    <button>Edit</button>
                    <button>Delete</button>
                </td>
            </tr>
        </table>
    </section>
</body>
</html>
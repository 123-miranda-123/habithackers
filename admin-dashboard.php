<?php
session_start();
require_once "database.php";

if (($_SESSION["user_role"]) !== "Admin") {
    header("Location: index.php");
}
// Ensure user is logged in
if (!isset($_SESSION['user_id'])) {
    // Redirect to login page if user is not logged in
    header("Location: login.php");
    exit();
}
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
    <style>
    @import url('https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap');
    </style>
    <link rel="icon" href="images/icon.png" type="image/png">
</head>
<body>
    <script>
      function openEdit(id) {
        window.location.href = 'edit-user.php?id=' + encodeURIComponent(id);
      }
      function openCapEdit(id) {
        window.location.href = 'edit-captain.php?id=' + encodeURIComponent(id);
      }
      function openCapDelete(id) {
        window.location.href = 'delete-captain.php?id=' + encodeURIComponent(id);
      }
      function openDelete(id) {
        window.location.href = 'delete-user.php?id=' + encodeURIComponent(id);
      }
    </script>
    

    <nav class="header">
        <div class="header-container">
            <a href="index.ph p">
                <img src="images/Banner 2.png" alt="Habit Hub Logo" class="logo">
            </a>
            <div class="auth-buttons">
                <a href ="leaderboard.php" id="leaderboard">Leaderboard</a>
                <a href="help.html" id="help">Help</a>
                <a href="logout.php" id="logout">Logout</a>
            </div>
        </div>
    </nav>
    <div id="overlay" class="overlay">
    

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

            if (!$result) {
                echo "Invalid query: " . $connection->error;
                exit();
            }

            while ($row = $result->fetch_assoc()) {
                echo "
                <tr> 
                <td id = 'user-id'>" . $row["id"] . "</td>
                <td>" . $row["name"] . "</td>
                <td>" . $row["email"] . "</td>
                <td>" . $row["role"] . "</td>
                <td>
                  
                    <button id = 'edit' onclick = openEdit(".$row["id"].")>Edit</button>
                    <button id = 'delete' onclick = openDelete(".$row["id"].")>Delete</button>
                  
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
            <?php 
            $sql = "SELECT * from teams";
            $result = $conn->query($sql);
            if (!$result) {
                echo "Invalid query: ". $conn->error;
                exit();
            }
            while ($row = $result->fetch_assoc()) {
                $sql = "SELECT * from users where id = " + $row["captain-id"];
                $res = $conn->query($sql);
                if (!$res) {
                    echo "Invalid query: ". $conn->error;
                    exit();
                }
                $inf = $res->fetch_assoc();

                echo "<tr> 
                <td id = 'team-id'>". $row["id"] . "</td>
                 <td>".$row["name"] . "</td>
                 <td>".$inf["name"]."</td>
                 <td>Team Captain</td>
                 <td>
                  
                    <button id = 'edit' onclick = openEdit(".$row["id"].")>Edit</button>
                    <button id = 'delete' onclick = openDelete(".$row["id"].")>Delete</button>
                  
                </td>";
            }
            ?>
            <tr>
                <td>1</td>
                <td>Habit Hackers</td>
                <td>John Doe</td>
                <td>Team Captain</td>
                <td>
                    <button href = "edit-user.php">Edit</button>
                    <button>Delete</button>
         </td>
            </tr>
        </table>
    </section>
</body>
</html>

<?php 


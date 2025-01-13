<?php
session_start();
require_once "database.php";

if (strtolower($_SESSION["user_role"]) !== "admin") {
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

<style>
     .overlay {
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background: rgba(0, 0, 0, 0.5);
      display: none;
      justify-content: center;
      align-items: center;
      z-index: 1000;
    }
     /* Styles for the popup menu */
     .popup {
      background: #fff;
      padding: 20px;
      border-radius: 10px;
      width: 300px;
      text-align: center;
      box-shadow: 0 2px 10px rgba(0, 0, 0, 0.2);
    }

    .popup h2 {
      margin-top: 0;
    }

    .dropdown {
      width: 100%;
      margin: 10px 0;
    }

    button {
      padding: 10px 15px;
      margin: 10px 5px;
      border: none;
      border-radius: 5px;
      cursor: pointer;
    }

    .open-btn {
      background-color: #007bff;
      color: white;
    }

    .cancel-btn {
      background-color: #f44336;
      color: white;
    }

    .submit-btn {
      background-color: #4caf50;
      color: white;
    }

    .greyed-out {
      filter: brightness(0.5);
    }
</style>



<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Habit Hub</title>
    <link href="styles/header.css" rel="stylesheet" type="text/css"/>
    <link href="styles/manage-dashboard.css" rel="stylesheet" type="text/css"/>
    <link rel="icon" href="images/icon.png" type="image/png">
</head>
<body>
    <script>
        function openPopup() {
            document.getElementById('overlay').style.display = 'flex';
            document.getElementById('main-content').classList.add('greyed-out');
        } function closePopup() {
            document.getElementById('overlay').style.display = 'none';
            document.getElementById('main-content').classList.remove('greyed-out');
        }
    </script>
    <nav class="header">
        <div class="header-container">
            <a href="index.php">
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
    <div class="popup">
      <h2>Popup Menu</h2>
      <select class="dropdown">
        <option value="">Select Option 1</option>
        <option value="option1">Option 1</option>
        <option value="option2">Option 2</option>
        <option value="option3">Option 3</option>
      </select>
      <input type="text" class="input-box" placeholder="Type here for input 1">
      <input type="text" class="input-box" placeholder="Type here for input 2">
      <div>
        <button class="cancel-btn" onclick='closePopup()'>Cancel</button>
        <button class="submit-btn">Submit</button>
      </div>
    </div>
  </div>

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
                    <button id = 'edit' onclick= 'openPopup()'  >Edit</button>

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
                    <button href = "edit-user.php">Edit</button>
                    <button>Delete</button>
                </td>
            </tr>
        </table>
    </section>
</body>
</html>
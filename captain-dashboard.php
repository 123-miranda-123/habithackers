<ta?php
    session_start();
    require_once("database.php");
    
    if (strtolower($_SESSION["user_role"]) !== "captain") {
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
    <title>Captain Dashboard - Habit Hub</title>
    <link href="styles/header.css" rel="stylesheet" type="text/css"/>
    <link href="styles/manage-dashboard.css" rel="stylesheet" type="text/css"/>
    <link rel="icon" href="images/icon.png" type="image/png">
    <style>
    @import url('https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap');
    </style>
</head>
<body>
    <script>
      function openEdit(id) {
        window.location.href = 'edit-user.php?id=' + encodeURIComponent(id);
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

    <section class = "container">
        <h1>Captain Dashboard</h1>
        <h2>Team Members</h2>

        <table class = "users-table">
            <tr>
                <th>Name</th>
                <th>Email</th>
                <th>Role</th>
                <th>Actions</th>
            </tr>
            <tr>
                <td>John Doe</td>
                <td>doe@gmail.com</td>
                <td>Team Captain</td>
                <td>Actions</td>
            </tr>
        
        <?php
            $sql = "SELECT * FROM team_members";
            $result = $conn->query($sql);
            if (! $result) {
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



    </section>
</body>
</html>
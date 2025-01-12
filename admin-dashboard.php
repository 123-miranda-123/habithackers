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
            <a href="index.html">
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
        </table>
    </section>
</body>
</html>
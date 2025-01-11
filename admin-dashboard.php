<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Habit Hub</title>
    <link href="styles/header.css" rel="stylesheet" type="text/css"/>
    <link href="styles/index.css" rel="stylesheet" type="text/css"/>
    <link href="styles/footer.css" rel="stylesheet" type="text/css"/>
    <link rel="icon" href="images/icon.png" type="image/png">
    <link href = "php/register.php" type = "php"/>
</head>
<ody>
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
    <h2>User Management</h2>
    <table>
    <tr>
        <th>Name</th>
        <th>Email</th>
        <th>Role</th>
        <th>Actions</th>
    </tr>
    <tr>
        <td>{{this.name}}</td>
        <td>{{this.email}}</td>
        <td>{{this.role}}</td>
        <td>
            <form action="/admin/promote/{{this.id}}" method="POST">
                <button type="submit">Promote to Captain</button>
            </form>
        </td>
    </tr>
    {{/each}}
    </table>
</ody>
</html>
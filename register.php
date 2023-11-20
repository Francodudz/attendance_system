<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Registration</title>
</head>
<body>
    <h2>User Registration</h2>
    <form action="register_process.php" method="post">
        <label for="username">Username:</label>
        <input type="text" name="username" required><br>

        <label for="password">Password:</label>
        <input type="password" name="password" required><br>

        <label for="user_type">User Type:</label>
        <select name="user_type" required>
            <option value="admin">Administrator</option>
            <option value="teacher">Teacher</option>
        </select><br>

    <button type="button" onclick="window.location.href='login.php'">Login</button>
</body>
</html>

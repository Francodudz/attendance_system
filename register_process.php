<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Include database connection code here
    require_once 'db_config.php';

    // Function to sanitize and validate user input
    function sanitizeInput($input)
    {
        $input = trim($input);
        $input = stripslashes($input);
        $input = htmlspecialchars($input);
        return $input;
    }

    // Get and sanitize form values
    $username = sanitizeInput($_POST["username"]);
    $password = sanitizeInput($_POST["password"]);
    $user_type = sanitizeInput($_POST["user_type"]);

    // Validate username
    if (!preg_match("/^[a-zA-Z0-9_]*$/", $username)) {
        echo "Invalid username format. Only letters, numbers, and underscores are allowed.";
        exit();
    }

    // Hash the password before storing
    $hashed_password = hash('sha256', $password);

    // Use prepared statements to prevent SQL injection
    $query = "INSERT INTO users (username, password, user_type) VALUES (?, ?, ?)";
    $stmt = mysqli_prepare($conn, $query);

    mysqli_stmt_bind_param($stmt, "sss", $username, $hashed_password, $user_type);
    $result = mysqli_stmt_execute($stmt);

    if ($result) {
        echo "Registration successful! <a href='login.php'>Login</a>";
    } else {
        // Registration failed
        echo "Error: " . mysqli_error($conn);
    }

    // Close the prepared statement and database connection
    mysqli_stmt_close($stmt);
    mysqli_close($conn);
}
?>

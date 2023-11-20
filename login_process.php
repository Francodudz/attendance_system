<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Include database connection code here
    require_once 'db_config.php';

    $username = mysqli_real_escape_string($conn, $_POST["username"]);
    $password = mysqli_real_escape_string($conn, $_POST["password"]);

    // Hash the password before comparing
    $hashed_password = hash('sha256', $password);

    // Use prepared statements to prevent SQL injection
    $query = "SELECT * FROM users WHERE username = ? AND password = ?";
    $stmt = mysqli_prepare($conn, $query);

    mysqli_stmt_bind_param($stmt, "ss", $username, $hashed_password);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if ($result) {
        if (mysqli_num_rows($result) == 1) {
            $user = mysqli_fetch_assoc($result);

            // Store user information in the session
            $_SESSION["user_id"] = $user["user_id"];
            $_SESSION["username"] = $user["username"];
            $_SESSION["user_type"] = $user["user_type"];

            // Redirect to the dashboard or appropriate page
            header("Location: dashboard.php");
            exit();
        } else {
            // Invalid credentials
            echo "Invalid username or password";
        }
    } else {
        // Query execution error
        echo "Error: " . mysqli_error($conn);
    }

    // Close the prepared statement and database connection
    mysqli_stmt_close($stmt);
    mysqli_close($conn);
}
?>

<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit();
}

// Include database connection code here
require_once 'db_config.php';

// Get user information from the session
$user_id = $_SESSION["user_id"];
$user_type = $_SESSION["user_type"];

// Function to get a list of classes
function getClasses($conn, $user_type, $user_id)
{
    if ($user_type === 'admin') {
        // Return all classes for administrators
        $query = "SELECT * FROM classes";
    } elseif ($user_type === 'teacher') {
        // Return classes for the teacher
        $query = "SELECT * FROM classes WHERE teacher_id = $user_id";
    }

    $result = mysqli_query($conn, $query);

    return $result;
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Classes</title>
</head>
<body>
    <h2>Class List</h2>

    <?php
    // Display content based on user type
    if ($user_type === 'admin') {
        echo '<p>Administrator View Classes Content</p>';
    } elseif ($user_type === 'teacher') {
        echo '<p>Teacher View Classes Content</p>';
    }
    ?>

    <ul>
        <?php
        $classes = getClasses($conn, $user_type, $user_id);

        while ($class = mysqli_fetch_assoc($classes)) {
            echo "<li><a href='mark_attendance.php?class_id={$class['class_id']}'>{$class['class_name']} - {$class['start_date']}</a></li>";
        }
        ?>
    </ul>

    <p><a href="dashboard.php">Back to Dashboard</a></p>
</body>
</html>

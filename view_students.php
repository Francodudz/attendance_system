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

// Function to get a list of students
function getStudents($conn)
{
    $query = "SELECT * FROM students";
    $result = mysqli_query($conn, $query);

    return $result;
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Students</title>
</head>
<body>
    <h2>Student List</h2>

    <?php
    // Display content based on user type
    if ($user_type === 'admin') {
        echo '<p>Administrator View Students Content</p>';
    } elseif ($user_type === 'teacher') {
        echo '<p>Teacher View Students Content</p>';
    }
    ?>

    <table border="1">
        <thead>
            <tr>
                <th>Student ID</th>
                <th>Student Name</th>
                <th>Student ID Number</th>
                <th>Class</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $students = getStudents($conn);

            while ($student = mysqli_fetch_assoc($students)) {
                echo "<tr>
                        <td>{$student['student_id']}</td>
                        <td>{$student['student_name']}</td>
                        <td>{$student['student_id_number']}</td>
                        <td>{$student['class']}</td>
                      </tr>";
            }
            ?>
        </tbody>
    </table>

    <p><a href="dashboard.php">Back to Dashboard</a></p>
</body>
</html>

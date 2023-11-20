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
$username = $_SESSION["username"];
$user_type = $_SESSION["user_type"];

// Function to get upcoming classes
function getUpcomingClasses($conn, $user_type, $user_id)
{
    if ($user_type === 'admin') {
        // Return all upcoming classes for administrators
        $query = "SELECT * FROM classes WHERE start_date >= CURDATE() ORDER BY start_date LIMIT 5";
    } elseif ($user_type === 'teacher') {
        // Return upcoming classes for the teacher
        $query = "SELECT * FROM classes WHERE teacher_id = $user_id AND start_date >= CURDATE() ORDER BY start_date LIMIT 5";
    }

    $result = mysqli_query($conn, $query);

    return $result;
}

// Function to get recent attendance
function getRecentAttendance($conn, $user_type, $user_id)
{
    if ($user_type === 'admin') {
        // Return all recent attendance records for administrators
        $query = "SELECT * FROM attendance ORDER BY attendance_date DESC LIMIT 5";
    } elseif ($user_type === 'teacher') {
        // Return recent attendance records for the teacher's classes
        $query = "SELECT a.* FROM attendance a
                  JOIN classes c ON a.class_id = c.class_id
                  WHERE c.teacher_id = $user_id
                  ORDER BY a.attendance_date DESC LIMIT 5";
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
    <title>Dashboard</title>
</head>
<body>
    <p><a href="add_update_student.php">Add/Update Student</a></p>
    <p><a href="mark_attendance.php">Mark Attendance</a></p>
    <p><a href="view_classes.php">View Classes</a></p>
    <p><a href="view_student.php">View Student</a></p>
    <p><a href="generate_report.php">Generate Report</a></p>
    <h2>Welcome, <?php echo $username; ?>!</h2>

    <?php
    // Display content based on user type
    if ($user_type === 'admin') {
        echo '<p>Administrator Dashboard Content</p>';
    } elseif ($user_type === 'teacher') {
        echo '<p>Teacher Dashboard Content</p>';
    }
    ?>

    <h3>Upcoming Classes</h3>
    <ul>
        <?php
        $upcomingClasses = getUpcomingClasses($conn, $user_type, $user_id);

        while ($class = mysqli_fetch_assoc($upcomingClasses)) {
            echo "<li>{$class['class_name']} - {$class['start_date']}</li>";
        }
        ?>
    </ul>

    <h3>Recent Attendance</h3>
    <ul>
        <?php
        $recentAttendance = getRecentAttendance($conn, $user_type, $user_id);

        while ($attendance = mysqli_fetch_assoc($recentAttendance)) {
            echo "<li>{$attendance['attendance_date']} - {$attendance['status']}</li>";
        }
        ?>
    </ul>

    <p><a href="logout.php">Logout</a></p>
</body>
</html>

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

// Get class ID from the URL
$class_id = isset($_GET['class_id']) ? mysqli_real_escape_string($conn, $_GET['class_id']) : null;

// Function to get a list of students for a class
function getStudentsInClass($conn, $class_id)
{
    if ($class_id === null) {
        return false;
    }

    $query = "SELECT * FROM students WHERE class_id = $class_id";
    $result = mysqli_query($conn, $query);

    return $result;
}

  

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mark Attendance</title>
</head>
<body>
    <h2>Mark Attendance</h2>

    <?php
    // Display content based on user type
    if ($user_type === 'admin') {
        echo '<p>Administrator Mark Attendance Content</p>';
    } elseif ($user_type === 'teacher') {
        echo '<p>Teacher Mark Attendance Content</p>';
    }
    ?>

    <form action="mark_attendance_process.php" method="post">
        <input type="hidden" name="class_id" value="<?php echo $class_id; ?>">

        <table border="1">
            <thead>
                <tr>
                    <th>Student ID</th>
                    <th>Student Name</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $students = getStudentsInClass($conn, $class_id);

                if ($students) {
                    while ($student = mysqli_fetch_assoc($students)) {
                        echo "<tr>
                                <td>{$student['student_id']}</td>
                                <td>{$student['student_name']}</td>
                                <td>
                                    <select name='status[{$student['student_id']}]'>
                                        <option value='present'>Present</option>
                                        <option value='absent'>Absent</option>
                                    </select>
                                </td>
                              </tr>";
                    }
                }
                ?>
            </tbody>
                
                ?>
            </tbody>
        </table>

        <button type="submit">Submit Attendance</button>
    </form>

    <p><a href="view_classes.php">Back to Class List</a></p>
    <p><a href="dashboard.php">Back to Dashboard</a></p>
</body>
</html>

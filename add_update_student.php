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

// Initialize variables for form values
$student_id = '';
$student_name = '';
$student_id_number = '';
$class = '';

// Check if editing an existing student
if (isset($_GET['edit'])) {
    $edit_id = mysqli_real_escape_string($conn, $_GET['edit']);
    $query = "SELECT * FROM students WHERE student_id = $edit_id";
    $result = mysqli_query($conn, $query);

    if ($result && mysqli_num_rows($result) == 1) {
        $row = mysqli_fetch_assoc($result);
        $student_id = $row['student_id'];
        $student_name = $row['student_name'];
        $student_id_number = $row['student_id_number'];
        $class = $row['class'];
    }
}

// Function to add or update a student
function addUpdateStudent($conn, $student_id, $student_name, $student_id_number, $class)
{
    if ($student_id == '') {
        // Add a new student
        $query = "INSERT INTO students (student_name, student_id_number, class) VALUES (?, ?, ?)";
        $stmt = mysqli_prepare($conn, $query);
        mysqli_stmt_bind_param($stmt, "sss", $student_name, $student_id_number, $class);
    } else {
        // Update an existing student
        $query = "UPDATE students SET student_name=?, student_id_number=?, class=? WHERE student_id=?";
        $stmt = mysqli_prepare($conn, $query);
        mysqli_stmt_bind_param($stmt, "sssi", $student_name, $student_id_number, $class, $student_id);
    }

    $result = mysqli_stmt_execute($stmt);

    if ($result) {
        echo "Student information updated successfully!";
    } else {
        echo "Error: " . mysqli_error($conn);
    }

    // Close the prepared statement
    mysqli_stmt_close($stmt);
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add/Update Student</title>
</head>
<body>
    <h2>Add/Update Student</h2>

    <?php
    // Display content based on user type
    if ($user_type === 'admin') {
        echo '<p>Administrator Add/Update Student Content</p>';
    } elseif ($user_type === 'teacher') {
        echo '<p>Teacher Add/Update Student Content</p>';
    }
    ?>

    <form action="add_update_student_process.php" method="post">
        <input type="hidden" name="student_id" value="<?php echo $student_id; ?>">

        <label for="student_name">Student Name:</label>
        <input type="text" name="student_name" value="<?php echo $student_name; ?>" required><br>

        <label for="student_id_number">Student ID Number:</label>
        <input type="text" name="student_id_number" value="<?php echo $student_id_number; ?>" required><br>

        <label for="class">Class:</label>
        <input type="text" name="class" value="<?php echo $class; ?>" required><br>

        <button type="submit">Save</button>
    </form>

    <p><a href="view_students.php">View Students</a></p>
    <p><a href="dashboard.php">Back to Dashboard</a></p>
</body>
</html>

<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit();
}

// Include database connection code here
require_once 'db_config.php';

// Get form values
$class_id = mysqli_real_escape_string($conn, $_POST['class_id']);
$status = $_POST['status'];

// Loop through the status array and record attendance
foreach ($status as $student_id => $attendance_status) {
    $query = "INSERT INTO attendance (student_id, class_id, attendance_date, status)
              VALUES ($student_id, $class_id, CURDATE(), '$attendance_status')";
    mysqli_query($conn, $query);
}

// Redirect to the page to view classes
header("Location: view_classes.php");
exit();
?>

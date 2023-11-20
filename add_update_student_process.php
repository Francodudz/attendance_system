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
$student_id = mysqli_real_escape_string($conn, $_POST['student_id']);
$student_name = mysqli_real_escape_string($conn, $_POST['student_name']);
$student_id_number = mysqli_real_escape_string($conn, $_POST['student_id_number']);
$class = mysqli_real_escape_string($conn, $_POST['class']);

// Include the function to add or update a student
include_once 'add_update_student.php';

// Call the function to add or update the student
addUpdateStudent($conn, $student_id, $student_name, $student_id_number, $class);

// Redirect to the page to view students
header("Location: view_students.php");
exit();
?>

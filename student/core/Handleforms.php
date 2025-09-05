<?php
// Location: student/core/Handleforms.php
session_start();
require_once '../../config.php';
require_once '../classes/Database.php';
require_once '../classes/Student.php';

$student = new Student;

// Ensure the request is a POST request
if($_SERVER['REQUEST_METHOD'] == 'POST'){

    // --- Handle Student Login ---
    if(isset($_POST['login'])){
        $student_id = $_POST['student_id'];
        $password = $_POST['password'];
        $loggedInStudent = $student->login($student_id, $password);

        if($loggedInStudent){
            $_SESSION['student_db_id'] = $loggedInStudent->id;
            $_SESSION['student_id'] = $loggedInStudent->student_id;
            $_SESSION['student_name'] = $loggedInStudent->full_name;
            header('Location: ../dashboard.php');
            exit();
        } else {
            header('Location: ../index.php?error=1');
            exit();
        }
    }

    // --- Handle New Student Registration ---
    if(isset($_POST['register'])){
        if ($student->findById($_POST['student_id'])) {
            header('Location: ../register.php?error=Student ID already taken.');
            exit();
        }

        $hashed_password = password_hash($_POST['password'], PASSWORD_DEFAULT);
        $data = [
            'full_name' => trim($_POST['full_name']),
            'student_id' => trim($_POST['student_id']),
            'password' => $hashed_password,
            'course_id' => $_POST['course_id'],
            'year_level' => $_POST['year_level']
        ];

        if ($student->register($data)) {
            header('Location: ../register.php?success=1');
            exit();
        } else {
            header('Location: ../register.php?error=Something went wrong.');
            exit();
        }
    }

    // --- Handle Marking Attendance ---
    if(isset($_POST['mark_attendance'])) {
        // Security Check: Make sure the user is logged in
        if(!isset($_SESSION['student_db_id'])) {
            header('Location: ../index.php');
            exit();
        }
        
        // Get the submitted data
        $student_db_id = $_SESSION['student_db_id'];
        $date = $_POST['attendance_date'];
        $status = $_POST['status'];

        // Server-side Validation
        if (empty($date) || empty($status)) {
            header('Location: ../dashboard.php?error=empty_fields');
            exit();
        }
        if (!in_array($status, ['Present', 'Late'])) {
            header('Location: ../dashboard.php?error=invalid_status');
            exit();
        }
        if ($date > date('Y-m-d')) {
            header('Location: ../dashboard.php?error=future_date');
            exit();
        }

        // Check if attendance for this date has already been marked
        if ($student->hasMarkedAttendanceForDate($student_db_id, $date)) {
            header('Location: ../dashboard.php?error=already_marked');
            exit();
        }
        
        // Mark the attendance with the chosen date and status
        if ($student->markAttendance($student_db_id, $date, $status)) {
            header('Location: ../dashboard.php?marked=success');
            exit();
        } else {
            header('Location: ../dashboard.php?marked=failure');
            exit();
        }
    }
}

// If the file is accessed without a POST request, redirect to the dashboard.
header('Location: ../dashboard.php');
exit();
?>
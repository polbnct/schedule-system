<?php
// Location: admin/core/Handleforms.php
session_start();
require_once '../../config.php';
require_once '../classes/Database.php';
require_once '../classes/Admin.php';
require_once '../classes/Course.php';

// Instantiate the classes to use their methods
$admin = new Admin();
$course = new Course();

// Ensure the request is a POST request
if($_SERVER['REQUEST_METHOD'] == 'POST'){

    // --- Handle Admin Login ---
    if(isset($_POST['login'])){
        $username = $_POST['username'];
        $password = $_POST['password'];
        $loggedInAdmin = $admin->login($username, $password);

        if($loggedInAdmin){
            $_SESSION['admin_id'] = $loggedInAdmin->id;
            $_SESSION['admin_username'] = $loggedInAdmin->username;
            header('Location: ../dashboard.php');
            exit();
        } else {
            header('Location: ../index.php?error=1');
            exit();
        }
    }

    // --- Handle New Admin Registration ---
    if(isset($_POST['register'])){
        $username = trim($_POST['username']);

        // Check if username already exists
        if ($admin->findByUsername($username)) {
            header('Location: ../register.php?error=Username already taken.');
            exit();
        }

        // Hash the password securely
        $hashed_password = password_hash($_POST['password'], PASSWORD_DEFAULT);

        // Attempt to register the new admin
        if ($admin->register($username, $hashed_password)) {
            header('Location: ../register.php?success=1');
            exit();
        } else {
            header('Location: ../register.php?error=Registration failed.');
            exit();
        }
    }

    // --- Security Check: All actions below require the admin to be logged in ---
    if (!isset($_SESSION['admin_id'])) {
        header('Location: ../index.php');
        exit();
    }

    // --- Handle Add Course ---
    if(isset($_POST['add_course'])){
        $course_name = trim($_POST['course_name']);
        if(!empty($course_name) && $course->addCourse($course_name)){
            header('Location: ../dashboard.php?action=added');
        } else {
            header('Location: ../dashboard.php?action=error');
        }
        exit();
    }

    // --- Handle Update Course ---
    if(isset($_POST['update_course'])) {
        $course_id = $_POST['course_id'];
        $course_name = trim($_POST['course_name']);

        if(!empty($course_name) && $course->updateCourse($course_id, $course_name)) {
            header('Location: ../dashboard.php?action=updated');
        } else {
            header('Location: ../dashboard.php?action=error');
        }
        exit();
    }

    // --- Handle Delete Course ---
    if(isset($_POST['delete_course'])) {
        $course_id = $_POST['course_id'];
        if($course->deleteCourse($course_id)) {
            header('Location: ../dashboard.php?action=deleted');
        } else {
            header('Location: ../dashboard.php?action=error');
        }
        exit();
    }
}

// If the file is accessed without a POST request, redirect to the dashboard.
header('Location: ../dashboard.php');
exit();
?>
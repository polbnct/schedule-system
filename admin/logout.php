<?php
// Start the session to access session variables
session_start();

// Unset all of the session variables
$_SESSION = array();

// Destroy the session completely
session_destroy();

// Redirect the user to the admin login page after logging out
header("location: index.php");
exit;
?>
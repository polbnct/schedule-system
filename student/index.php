<?php
session_start();

// If the student is already logged in, redirect them to the student dashboard
if (isset($_SESSION['student_id'])) {
    header('Location: dashboard.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Login | Attendance System</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-200 flex items-center justify-center h-screen">
    <div class="w-full max-w-sm">
        <form action="core/Handleforms.php" method="POST" class="bg-white shadow-lg rounded-xl px-8 pt-6 pb-8 mb-4">
            <h2 class="text-3xl font-bold mb-6 text-center text-gray-800">Student Login</h2>

            <?php if (isset($_GET['error'])): ?>
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                    <span class="block sm:inline">Invalid Student ID or password.</span>
                </div>
            <?php endif; ?>

            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="student_id">
                    Student ID
                </label>
                <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="student_id" name="student_id" type="text" placeholder="Enter your Student ID" required>
            </div>

            <div class="mb-6">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="password">
                    Password
                </label>
                <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 mb-3 leading-tight focus:outline-none focus:shadow-outline" id="password" name="password" type="password" placeholder="******************" required>
            </div>

            <div class="flex items-center justify-center">
                <button class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-6 rounded-lg focus:outline-none focus:shadow-outline" type="submit" name="login">
                    Sign In
                </button>
            </div>
             <p class="text-center mt-4">
                <a href="register.php" class="font-bold text-sm text-blue-500 hover:text-blue-800">Don't have an account? Register</a>
            </p>
        </form>
        <p class="text-center text-gray-500 text-xs">
            &copy;<?php echo date("Y"); ?> Attendance System. All rights reserved.
        </p>
    </div>
</body>
</html>
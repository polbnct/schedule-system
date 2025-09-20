<?php
// We need to fetch the list of courses for the dropdown menu.
require_once '../config.php';
// The Database and Course classes are needed.
require_once 'classes/Database.php';
require_once '../admin/classes/Course.php'; // Path to the existing Course class

$course = new Course();
$courses = $course->getCourses();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Registration | Attendance System</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-200 flex items-center justify-center min-h-screen py-8">
    <div class="w-full max-w-md">
        <form action="core/Handleforms.php" method="POST" class="bg-white shadow-lg rounded-xl px-8 pt-6 pb-8 mb-4">
            <h2 class="text-3xl font-bold mb-6 text-center text-gray-800">Student Registration</h2>

            <?php if (isset($_GET['error'])): ?>
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                    <span><?php echo htmlspecialchars($_GET['error']); ?></span>
                </div>
            <?php endif; ?>
            <?php if (isset($_GET['success'])): ?>
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                    <span>Registration successful! You can now log in.</span>
                </div>
            <?php endif; ?>

            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="full_name">Full Name</label>
                <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="full_name" name="full_name" type="text" placeholder="e.g., John Doe" required>
            </div>
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="student_id">Student ID</label>
                <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="student_id" name="student_id" type="text" placeholder="e.g., 2025-001" required>
            </div>
             <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="course_id">Course/Program</label>
                <select id="course_id" name="course_id" class="shadow border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
                    <option value="">-- Select a Course --</option>
                    <?php foreach($courses as $c): ?>
                        <option value="<?php echo $c->id; ?>"><?php echo htmlspecialchars($c->course_name); ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="year_level">Year Level</label>
                <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="year_level" name="year_level" type="number" min="1" max="5" placeholder="e.g., 1" required>
            </div>
            <div class="mb-6">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="password">Password</label>
                <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="password" name="password" type="password" placeholder="******************" required>
            </div>

            <div class="flex items-center justify-center">
                <button class="bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-6 rounded-lg focus:outline-none focus:shadow-outline" type="submit" name="register">Register</button>
            </div>
            <p class="text-center mt-4">
                <a href="index.php" class="font-bold text-sm text-blue-500 hover:text-blue-800">Already have an account? Login</a>
            </p>
        </form>
    </div>
</body>
</html>
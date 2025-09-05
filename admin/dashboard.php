<?php
session_start();
if(!isset($_SESSION['admin_id'])){
    header('Location: index.php');
    exit();
}

require_once '../config.php';
require_once 'classes/Database.php';
require_once 'classes/Course.php';

$course = new Course();
$courses = $course->getCourses();

// Prepare user feedback message
$message = '';
if (isset($_GET['action'])) {
    switch ($_GET['action']) {
        case 'added': $message = 'Course added successfully!'; break;
        case 'updated': $message = 'Course updated successfully!'; break;
        case 'deleted': $message = 'Course deleted successfully!'; break;
        case 'error': $message = 'An error occurred. Please try again.'; break;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
    <div class="container mx-auto mt-10 p-4">
        <div class="bg-white p-8 rounded-lg shadow-2xl">
            <!-- Header -->
            <div class="flex justify-between items-center mb-6 border-b pb-4">
                <h1 class="text-3xl font-bold text-gray-800">Welcome, <?php echo htmlspecialchars($_SESSION['admin_username']); ?></h1>
                <a href="logout.php" class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded-lg">Logout</a>
            </div>

            <!-- User Feedback Message -->
            <?php if ($message): ?>
                <div class="mb-4 p-4 rounded-lg <?php echo ($_GET['action'] == 'error') ? 'bg-red-100 text-red-700' : 'bg-green-100 text-green-700'; ?>">
                    <?php echo $message; ?>
                </div>
            <?php endif; ?>

            <!-- Main Content Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">

                <!-- Column 1: Manage Courses -->
                <div class="bg-gray-50 p-6 rounded-lg border">
                    <h2 class="text-2xl font-semibold mb-4 text-gray-700 border-b pb-2">Manage Courses</h2>
                    
                    <!-- Add New Course Form -->
                    <form action="core/Handleforms.php" method="POST" class="mb-6">
                        <label for="course_name" class="block text-sm font-medium text-gray-700 mb-1">Add New Course</label>
                        <div class="flex items-center">
                            <input type="text" name="course_name" id="course_name" placeholder="e.g., Bachelor of Science" class="flex-grow border p-2 rounded-l-md focus:ring-blue-500 focus:border-blue-500" required>
                            <button type="submit" name="add_course" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-r-md">Add</button>
                        </div>
                    </form>

                    <!-- Existing Courses List -->
                    <h3 class="text-lg font-semibold mb-2 text-gray-600">Existing Courses</h3>
                    <div class="space-y-2">
                        <?php foreach($courses as $c): ?>
                            <div class="bg-white p-3 rounded-lg shadow-sm flex items-center justify-between">
                                <!-- Update Course Form -->
                                <form action="core/Handleforms.php" method="POST" class="flex-grow flex items-center">
                                    <input type="hidden" name="course_id" value="<?php echo $c->id; ?>">
                                    <input type="text" name="course_name" value="<?php echo htmlspecialchars($c->course_name); ?>" class="w-full border-transparent focus:border-blue-500 focus:ring-0 rounded-md">
                                    <button type="submit" name="update_course" class="ml-2 text-sm text-blue-600 hover:text-blue-800 font-semibold">Save</button>
                                </form>
                                <!-- Delete Course Form -->
                                <form action="core/Handleforms.php" method="POST" onsubmit="return confirm('Are you sure you want to delete this course? This will also delete all students and attendance records associated with it.');">
                                    <input type="hidden" name="course_id" value="<?php echo $c->id; ?>">
                                    <button type="submit" name="delete_course" class="ml-2 text-sm text-red-600 hover:text-red-800 font-semibold">Delete</button>
                                </form>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>

                <!-- Column 2: View Attendance -->
                <div class="bg-gray-50 p-6 rounded-lg border">
                    <h2 class="text-2xl font-semibold mb-4 text-gray-700 border-b pb-2">View Attendance</h2>
                    <form action="view_attendance.php" method="GET" class="space-y-4">
                        <div>
                            <label for="course" class="block text-sm font-medium text-gray-700">Course/Program:</label>
                            <select name="course_id" id="course" class="mt-1 block w-full p-2 border border-gray-300 rounded-md shadow-sm" required>
                                <option value="">-- Select a Course --</option>
                                <?php foreach($courses as $c): ?>
                                    <option value="<?php echo $c->id; ?>"><?php echo htmlspecialchars($c->course_name); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div>
                            <label for="year_level" class="block text-sm font-medium text-gray-700">Year Level:</label>
                            <input type="number" name="year_level" id="year_level" min="1" class="mt-1 block w-full p-2 border border-gray-300 rounded-md shadow-sm" placeholder="e.g., 1" required>
                        </div>
                        <button type="submit" class="w-full bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded-lg">View Records</button>
                    </form>
                </div>

            </div> <!-- End Grid -->
        </div>
    </div>
</body>
</html>
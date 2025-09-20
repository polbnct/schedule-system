<?php
session_start();
if(!isset($_SESSION['admin_id'])){
    header('Location: index.php');
    exit();
}

require_once '../config.php';
require_once 'classes/Database.php';
require_once 'classes/Course.php'; // Include the Course class

$db = new Database();
$courseHandler = new Course();

// Get variables from URL and sanitize them
$course_id = isset($_GET['course_id']) ? (int)$_GET['course_id'] : 0;
$year_level = isset($_GET['year_level']) ? (int)$_GET['year_level'] : 0;

// Fetch the course name for the title
$course = $courseHandler->getCourseById($course_id);
$course_name = $course ? $course->course_name : 'Unknown Course';

// Fetch the attendance records
$db->query("
    SELECT s.full_name, a.attendance_date, a.status
    FROM attendance a
    JOIN students s ON a.student_id = s.id
    WHERE s.course_id = :course_id AND s.year_level = :year_level
    ORDER BY a.attendance_date DESC, s.full_name ASC
");
$db->bind(':course_id', $course_id);
$db->bind(':year_level', $year_level);
$attendance_records = $db->resultSet();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Attendance</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
    <div class="container mx-auto mt-10 p-4">
        <div class="bg-white p-8 rounded-lg shadow-2xl">
            <!-- DYNAMIC HEADER -->
            <div class="border-b pb-4 mb-6">
                <h1 class="text-3xl font-bold text-gray-800">Attendance Records</h1>
                <p class="text-xl text-gray-600">
                    Course: <span class="font-semibold"><?php echo htmlspecialchars($course_name); ?></span> | 
                    Year Level: <span class="font-semibold"><?php echo htmlspecialchars($year_level); ?></span>
                </p>
            </div>

            <a href="dashboard.php" class="mb-6 inline-block bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-lg">&larr; Back to Dashboard</a>
            
            <div class="overflow-x-auto border rounded-lg">
                <table class="min-w-full bg-white">
                    <thead class="bg-gray-200">
                        <tr>
                            <th class="py-3 px-4 text-left font-semibold text-gray-600">Student Name</th>
                            <th class="py-3 px-4 text-left font-semibold text-gray-600">Date</th>
                            <th class="py-3 px-4 text-left font-semibold text-gray-600">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($attendance_records)): ?>
                            <tr>
                                <td colspan="3" class="py-4 px-4 text-center text-gray-500">No attendance records found for this selection.</td>
                            </tr>
                        <?php else: ?>
                            <?php foreach($attendance_records as $record): ?>
                            <tr class="hover:bg-gray-50 border-b">
                                <td class="py-2 px-4 text-gray-700"><?php echo htmlspecialchars($record->full_name); ?></td>
                                <td class="py-2 px-4 text-gray-700"><?php echo date("F j, Y", strtotime($record->attendance_date)); ?></td>
                                <td class="py-2 px-4 font-semibold
                                    <?php
                                        if ($record->status == 'Present') echo 'text-green-600';
                                        elseif ($record->status == 'Late') echo 'text-yellow-600';
                                        else echo 'text-red-600';
                                    ?>
                                ">
                                    <?php echo htmlspecialchars($record->status); ?>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>
</html>
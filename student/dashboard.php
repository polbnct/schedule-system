<?php
session_start();
if(!isset($_SESSION['student_id'])){
    header('Location: index.php');
    exit();
}

require_once '../config.php';
require_once 'classes/Database.php';
require_once 'classes/Student.php';

$student = new Student();

// Get the full attendance history for the table on the right
$attendanceHistory = $student->getAttendanceHistory($_SESSION['student_db_id']);

// Prepare messages for the user based on URL parameters
$message = '';
$message_type = '';
if (isset($_GET['error'])) {
    $message_type = 'error';
    switch ($_GET['error']) {
        case 'already_marked':
            $message = 'You have already marked your attendance for that date.';
            break;
        case 'future_date':
            $message = 'You cannot mark attendance for a future date.';
            break;
        case 'empty_fields':
            $message = 'Please select both a date and a status.';
            break;
        default:
            $message = 'An unknown error occurred.';
            break;
    }
}
if (isset($_GET['marked']) && $_GET['marked'] == 'success') {
    $message_type = 'success';
    $message = 'Your attendance has been recorded successfully!';
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
    <div class="container mx-auto mt-10 p-4">
        <div class="bg-white p-8 rounded-lg shadow-2xl">
            <!-- Header -->
            <div class="flex justify-between items-center border-b pb-4 mb-6">
                <div>
                    <h1 class="text-3xl font-bold text-gray-800">Welcome, <?php echo htmlspecialchars($_SESSION['student_name']); ?></h1>
                    <p class="text-gray-500">Student ID: <?php echo htmlspecialchars($_SESSION['student_id']); ?></p>
                </div>
                <a href="logout.php" class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded-lg transition duration-300">Logout</a>
            </div>

            <!-- Main Content Area -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">

                <!-- Section 1: Mark Attendance Form -->
                <div class="bg-gray-50 p-6 rounded-lg border">
                    <h2 class="text-2xl font-semibold mb-4 text-gray-700">Mark Attendance</h2>
                    
                    <!-- Display Success or Error Messages -->
                    <?php if ($message && $message_type == 'success'): ?>
                        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4" role="alert">
                            <p><?php echo $message; ?></p>
                        </div>
                    <?php elseif ($message && $message_type == 'error'): ?>
                         <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-4" role="alert">
                            <p><?php echo $message; ?></p>
                        </div>
                    <?php endif; ?>

                    <!-- The New Form -->
                    <form action="core/Handleforms.php" method="POST" class="space-y-4">
                        <div>
                            <label for="attendance_date" class="block text-sm font-medium text-gray-700">Select Date</label>
                            <input 
                                type="date" 
                                id="attendance_date" 
                                name="attendance_date"
                                class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                                max="<?php echo date('Y-m-d'); ?>" 
                                required
                            >
                        </div>
                        
                        <div>
                            <label for="status" class="block text-sm font-medium text-gray-700">Select Status</label>
                            <select 
                                id="status" 
                                name="status"
                                class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                                required
                            >
                                <option value="">-- Choose your status --</option>
                                <option value="Present">Present (On Time)</option>
                                <option value="Late">Late</option>
                            </select>
                        </div>

                        <div>
                            <button type="submit" name="mark_attendance" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-4 rounded-lg transition duration-300">
                                Submit Attendance
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Section 2: Attendance History (No Changes Here) -->
                <div class="bg-gray-50 p-6 rounded-lg border">
                    <h2 class="text-2xl font-semibold mb-4 text-gray-700">Attendance History</h2>
                    <div class="overflow-y-auto h-64 border rounded-lg">
                        <table class="min-w-full bg-white">
                            <thead class="bg-gray-200 sticky top-0">
                                <tr>
                                    <th class="py-3 px-4 text-left font-semibold text-gray-600">Date</th>
                                    <th class="py-3 px-4 text-left font-semibold text-gray-600">Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (empty($attendanceHistory)): ?>
                                    <tr>
                                        <td colspan="2" class="py-4 px-4 text-center text-gray-500">No attendance records found.</td>
                                    </tr>
                                <?php else: ?>
                                    <?php foreach($attendanceHistory as $record): ?>
                                    <tr class="hover:bg-gray-50 border-b">
                                        <td class="py-3 px-4 text-gray-700"><?php echo date("F j, Y", strtotime($record->attendance_date)); ?></td>
                                        <td class="py-3 px-4 font-semibold
                                            <?php
                                                if ($record->status == 'Present') echo 'text-green-600';
                                                elseif ($record->status == 'Late') echo 'text-yellow-600';
                                                else echo 'text-red-600';
                                            ?>
                                        ">
                                            <?php echo $record->status; ?>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>

            </div> <!-- End Grid -->
        </div>
    </div>
</body>
</html>
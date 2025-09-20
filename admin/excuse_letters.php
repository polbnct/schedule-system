<?php

// Start session and include necessary files.
// Ensure you have a check here to make sure only logged-in admins can access this page.
session_start();
require_once '../config.php'; // Adjust path as needed
require_once 'classes/Database.php'; // Adjust path as needed
require_once 'classes/Admin.php'; // The file we created above

$admin = new Admin();

// --- Handle Form Submissions (Approval/Rejection) ---
// This block processes the update when an admin clicks the "Update" button
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_status'])) {
    $letter_id = $_POST['letter_id'];
    $status = $_POST['status'];
    $remarks = trim($_POST['admin_remarks']);
    
    // Basic validation
    if (!empty($letter_id) && in_array($status, ['Approved', 'Rejected'])) {
        $admin->updateExcuseStatus($letter_id, $status, $remarks);
        // Redirect to the same page to prevent form resubmission on refresh
        header('Location: excuse_letters.php?update=success');
        exit();
    }
}

// --- Get Data for Display ---
// Fetch all courses to populate the filter dropdown
$courses = $admin->getAllCourses();
// Check if a course is selected from the GET request URL
$selected_course = isset($_GET['filter_course']) ? $_GET['filter_course'] : null;

// Validate the selected course ID to ensure it's a number
$filter_course_id = filter_var($selected_course, FILTER_VALIDATE_INT) ? $selected_course : null;

// Fetch the excuse letters, filtered by course if one was selected
$excuse_letters = $admin->getExcuseLetters($filter_course_id);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Manage Excuse Letters</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">

    <div class="container mx-auto mt-10 p-4">
        <div class="bg-white p-8 rounded-lg shadow-2xl">
            <!-- Header -->
            <div class="border-b pb-4 mb-6">
                <h1 class="text-3xl font-bold text-gray-800">Manage Excuse Letters</h1>
                <p class="text-gray-500">Review, filter, and respond to student submissions.</p>
            </div>
            
            <?php if(isset($_GET['update']) && $_GET['update'] == 'success'): ?>
                <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6" role="alert">
                    <p>The excuse letter status has been updated successfully.</p>
                </div>
            <?php endif; ?>

            <!-- Filter Form -->
            <form action="excuse_letters.php" method="GET" class="bg-gray-50 p-4 rounded-lg border mb-6 flex items-center space-x-4">
                <label for="filter_course" class="font-semibold text-gray-700">Filter by Program:</label>
                <select name="filter_course" id="filter_course" class="border-gray-300 rounded-md shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50" onchange="this.form.submit()">
                    <option value="">All Programs</option>
                    <?php foreach ($courses as $course): ?>
                        <option value="<?php echo $course->id; ?>" <?php echo ($selected_course == $course->id) ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($course->course_name); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
                <a href="excuse_letters.php" class="text-blue-600 hover:underline">Clear Filter</a>
            </form>

            <!-- Excuse Letters Table -->
            <div class="overflow-x-auto border rounded-lg">
                <table class="min-w-full bg-white">
                    <thead class="bg-gray-200">
                        <tr>
                            <th class="py-3 px-4 text-left font-semibold text-gray-600">Student</th>
                            <th class="py-3 px-4 text-left font-semibold text-gray-600">Program</th>
                            <th class="py-3 px-4 text-left font-semibold text-gray-600">Date</th>
                            <th class="py-3 px-4 text-left font-semibold text-gray-600">Status</th>
                            <th class="py-3 px-4 text-left font-semibold text-gray-600 w-1/4">Reason</th>
                            <th class="py-3 px-4 text-left font-semibold text-gray-600 w-1/4">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($excuse_letters)): ?>
                            <tr>
                                <td colspan="6" class="py-10 px-4 text-center text-gray-500">No excuse letters found for the selected filter.</td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($excuse_letters as $letter): ?>
                                <tr class="hover:bg-gray-50 border-b">
                                    <td class="py-3 px-4 text-gray-700"><?php echo htmlspecialchars($letter->student_name); ?></td>
                                    <td class="py-3 px-4 text-gray-700"><?php echo htmlspecialchars($letter->course_name); ?></td>
                                    <td class="py-3 px-4 text-gray-700 whitespace-nowrap"><?php echo date("M j, Y", strtotime($letter->absence_date)); ?></td>
                                    <td class="py-3 px-4 font-semibold">
                                        <?php
                                            $status = htmlspecialchars($letter->status);
                                            $colorClass = 'text-gray-600'; // Default for Pending
                                            if ($status == 'Approved') $colorClass = 'text-green-600';
                                            if ($status == 'Rejected') $colorClass = 'text-red-600';
                                        ?>
                                        <span class="<?php echo $colorClass; ?>"><?php echo $status; ?></span>
                                    </td>
                                    <td class="py-3 px-4 text-gray-600 text-sm"><?php echo htmlspecialchars($letter->reason); ?></td>
                                    <td class="py-3 px-4">
                                        <!-- Mini-form for each letter -->
                                        <form action="excuse_letters.php" method="POST" class="space-y-2">
                                            <input type="hidden" name="letter_id" value="<?php echo $letter->id; ?>">
                                            <select name="status" class="w-full text-sm border-gray-300 rounded-md shadow-sm">
                                                <option value="Approved" <?php echo ($letter->status == 'Approved') ? 'selected' : ''; ?>>Approve</option>
                                                <option value="Rejected" <?php echo ($letter->status == 'Rejected') ? 'selected' : ''; ?>>Reject</option>
                                                <option value="Pending" <?php echo ($letter->status == 'Pending') ? 'selected' : ''; ?>>Set to Pending</option>
                                            </select>
                                            <input type="text" name="admin_remarks" class="w-full text-sm border-gray-300 rounded-md shadow-sm" placeholder="Add optional remarks..." value="<?php echo htmlspecialchars($letter->admin_remarks ?? ''); ?>">
                                            <button type="submit" name="update_status" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-2 text-sm rounded-lg transition duration-300">Update</button>
                                        </form>
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
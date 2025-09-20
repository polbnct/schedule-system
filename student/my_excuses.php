<?php
session_start();
// Redirect to login if not authenticated
if (!isset($_SESSION['student_id'])) {
    header('Location: index.php');
    exit();
}

require_once '../config.php';
require_once 'classes/Database.php';
require_once 'classes/Student.php';

$student = new Student();
// Fetch all excuse letters for the currently logged-in student
$excuseLetters = $student->getExcuseLetters($_SESSION['student_db_id']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Excuse Letters</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">

    <div class="container mx-auto mt-10 p-4">
        <div class="bg-white p-8 rounded-lg shadow-2xl">
            <!-- Header -->
            <div class="flex flex-col sm:flex-row justify-between sm:items-center border-b pb-4 mb-6">
                <div>
                    <h1 class="text-3xl font-bold text-gray-800">My Submitted Excuse Letters</h1>
                    <p class="text-gray-500">Here is the history and status of your submissions.</p>
                </div>
                <a href="dashboard.php" class="mt-4 sm:mt-0 bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-lg transition duration-300">
                    &larr; Back to Dashboard
                </a>
            </div>

            <!-- Display Success Message -->
            <?php if (isset($_GET['success'])): ?>
                <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6" role="alert">
                    <p>Your excuse letter was submitted successfully and is now pending review.</p>
                </div>
            <?php endif; ?>

            <!-- Table of Excuse Letters -->
            <div class="overflow-x-auto border rounded-lg">
                <table class="min-w-full bg-white">
                    <thead class="bg-gray-200">
                        <tr>
                            <th class="py-3 px-4 text-left font-semibold text-gray-600">Date of Absence</th>
                            <th class="py-3 px-4 text-left font-semibold text-gray-600">Status</th>
                            <th class="py-3 px-4 text-left font-semibold text-gray-600">Reason</th>
                            <th class="py-3 px-4 text-left font-semibold text-gray-600">Admin Remarks</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($excuseLetters)): ?>
                            <tr>
                                <td colspan="4" class="py-4 px-4 text-center text-gray-500">You have not submitted any excuse letters yet.</td>
                            </tr>
                        <?php else: ?>
                            <?php foreach($excuseLetters as $letter): ?>
                            <tr class="hover:bg-gray-50 border-b">
                                <td class="py-3 px-4 text-gray-700 whitespace-nowrap"><?php echo date("F j, Y", strtotime($letter->absence_date)); ?></td>
                                <td class="py-3 px-4 font-semibold">
                                    <?php
                                        $status = htmlspecialchars($letter->status);
                                        $colorClass = 'text-gray-600'; // Default for Pending
                                        if ($status == 'Approved') $colorClass = 'text-green-600';
                                        if ($status == 'Rejected') $colorClass = 'text-red-600';
                                    ?>
                                    <span class="<?php echo $colorClass; ?>"><?php echo $status; ?></span>
                                </td>
                                <td class="py-3 px-4 text-gray-700 max-w-sm truncate"><?php echo htmlspecialchars($letter->reason); ?></td>
                                <td class="py-3 px-4 text-gray-500 italic"><?php echo htmlspecialchars($letter->admin_remarks ?? 'No remarks yet.'); ?></td>
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
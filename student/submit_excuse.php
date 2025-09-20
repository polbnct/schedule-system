<?php

session_start();
// Redirect to login if not authenticated
if (!isset($_SESSION['student_id'])) {
    header('Location: index.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Submit Excuse Letter</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 flex items-center justify-center min-h-screen">

    <div class="w-full max-w-2xl mx-auto p-4">
        <div class="bg-white p-8 rounded-xl shadow-2xl">
            <!-- Header -->
            <div class="border-b border-gray-200 pb-4 mb-6">
                <h1 class="text-3xl font-bold text-gray-800">Submit an Excuse Letter</h1>
                <p class="text-gray-500 mt-1">Fill out the form below to request an excuse for an absence.</p>
            </div>

            <!-- Display Error Messages -->
            <?php if (isset($_GET['error'])): ?>
                <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded-md" role="alert">
                    <p class="font-bold">Error</p>
                    <p>
                        <?php 
                            if ($_GET['error'] == 'empty') {
                                echo 'Please fill out both the date and the reason before submitting.';
                            } else {
                                echo 'Something went wrong on our end. Please try again.';
                            }
                        ?>
                    </p>
                </div>
            <?php endif; ?>

            <!-- Excuse Letter Form -->
            <form action="core/Handleforms.php" method="POST" class="space-y-6">
                <div>
                    <label for="absence_date" class="block text-sm font-medium text-gray-700 mb-1">Date of Absence</label>
                    <input 
                        type="date" 
                        id="absence_date" 
                        name="absence_date"
                        class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 transition"
                        max="<?php echo date('Y-m-d'); ?>" 
                        required
                    >
                </div>
                
                <div>
                    <label for="reason" class="block text-sm font-medium text-gray-700 mb-1">Reason for Absence</label>
                    <textarea 
                        id="reason" 
                        name="reason" 
                        rows="5"
                        class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 transition"
                        placeholder="Please provide a clear and detailed reason for your absence (e.g., medical appointment, family emergency)..."
                        required
                    ></textarea>
                </div>

                <!-- Action Buttons -->
                <div class="flex items-center space-x-4 pt-4">
                    <button type="submit" name="submit_excuse" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-4 rounded-lg transition duration-300 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        Submit for Review
                    </button>
                    <a href="dashboard.php" class="w-full text-center bg-gray-200 hover:bg-gray-300 text-gray-800 font-bold py-3 px-4 rounded-lg transition duration-300">
                        Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>

</body>
</html>
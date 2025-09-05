<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Registration | Attendance System</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-200 flex items-center justify-center min-h-screen">
    <div class="w-full max-w-sm">
        <form action="core/Handleforms.php" method="POST" class="bg-white shadow-lg rounded-xl px-8 pt-6 pb-8 mb-4">
            <h2 class="text-3xl font-bold mb-6 text-center text-gray-800">Admin Registration</h2>

            <?php if (isset($_GET['error'])): ?>
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4" role="alert">
                    <span><?php echo htmlspecialchars($_GET['error']); ?></span>
                </div>
            <?php endif; ?>
             <?php if (isset($_GET['success'])): ?>
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4" role="alert">
                    <span>Admin registered successfully! You may now log in.</span>
                </div>
            <?php endif; ?>

            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="username">Username</label>
                <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="username" name="username" type="text" required>
            </div>
            <div class="mb-6">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="password">Password</label>
                <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="password" name="password" type="password" placeholder="Choose a strong password" required>
            </div>
            <div class="flex items-center justify-center">
                <button class="bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-6 rounded-lg focus:outline-none focus:shadow-outline" type="submit" name="register">Register Admin</button>
            </div>
             <p class="text-center mt-4">
                <a href="index.php" class="font-bold text-sm text-blue-500 hover:text-blue-800">Back to Login</a>
            </p>
        </form>
    </div>
</body>
</html>
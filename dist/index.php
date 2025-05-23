<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Industry System</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="relative min-h-screen flex items-center justify-center bg-cover bg-center" style="background-image: url('./../assets/loginbg.jpg');">
    <!-- Dark Overlay -->
    <div class="absolute inset-0 bg-black opacity-50"></div>
    <div class="flex items-center justify-center min-h-screen gap-4 bg-transparent">
        <div class="bg-white flex flex-col p-7 text-center w-x h-y shadow-2xl rounded-xl">
            <h1 class="text-3xl text-black font-bold mb-5 w-auto ">Welcome to the Industry System</h1>
            <div class="flex flex-col gap-2 justify-center text-xl">
                <a class="bg-blue-600 px-8 py-2 rounded-md hover:bg-blue-700 text-white" href="login.php">Login</a>
                <a class="bg-gray-500 px- py-2 rounded-md hover:bg-gray-700 text-white" href="register.php">Register</a>
            </div>
        </div>
    </div>
</div>
</body>
</html>

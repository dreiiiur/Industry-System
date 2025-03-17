<head>
    <title>Landing Page</title>
    <link rel="stylesheet" href="style.css">
</head>
<?php 
session_start(); 

// Ensure Admin is Logged In
if (!isset($_SESSION["user_id"]) || $_SESSION["role"] != "admin") { 
    header("Location: login.php"); 
    exit(); 
}

// Get Admin's Name from Session (Ensure it is set)
$admin_name = isset($_SESSION["username"]) ? $_SESSION["username"] : "Admin"; 
$admin_role = isset($_SESSION["role"]) ? $_SESSION["role"] :"Admin";
?>
<header class="bg-blue-600 flex justify-between items-center p-4 md:p-6">
    <h1 class="text-white font-bold text-3xl md:text-4xl"> Home</h1>
    <nav class="flex space-x-4 md:space-x-8">
        <a href="admin_home.php" class="text-white font-semibold px-4 py-2 rounded">Home</a>
        <a href="admin_dashboard.php" class="text-white font-semibold px-4 py-2 rounded">User Management</a>
        <a href="profile.php" class="text-white font-semibold  px-4 py-2 rounded">Update Profile</a>
        <a href="logout.php" class="bg-red-600 text-white hover:bg-red-400 font-semibold px-4 py-2 rounded">Logout</a>
    </nav>
</header>
<div class="flex items-start justify-start min-h-screen w-full bg-white text-white">
    <div class="p-8 max-w-auto w-full">
        <h1 class="text-4xl font-bold mb-4 text-gray-800">Welcome, <?= htmlspecialchars($admin_role);?> <?= htmlspecialchars($admin_name); ?></h1>
        <p class="text-lg text-gray-700">We're glad to have you back!</p>
    </div>
</div>

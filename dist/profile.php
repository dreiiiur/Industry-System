<head>
    <title>Profile</title>
    <link rel="stylesheet" href="style.css">
</head>

<?php
session_start();
include("db.php");

// Check if admin is logged in
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: login.php");
    exit();
}

// Fetch all users
$admin_name = isset($_SESSION["username"]) ? $_SESSION["username"] : "Admin";
$admin_role = isset($_SESSION["role"]) ? $_SESSION["role"] : "Admin";
$result = $conn->query("SELECT id, username, email, age, address, role FROM users WHERE role != 'admin'");
?>

<header class="bg-transparent flex justify-between items-center p-4 md:p-6">
    <a class="text-blue-600 font-bold text-3xl md:text-4xl">Profile</a>
</header>
<main class="flex h-1/3 min-h-screen w-full bg-gray-50">
    <!-- Sidebar -->
    <div class="w-64 bg-white p-4 text-[blue-600] flex flex-col min-h-screen">
        <nav class="flex flex-col gap-4 text-blue-600">
            <div class="flex items-center hover:bg-gray-200 text-white">
                <img src="./../assets/admin-icon.png" alt="Profile Picture" class="w-8 h-8 rounded-full mr-2">
                <a href="#" class="text-blue-600 font-bold px-4 py-2 rounded text-2xl ">Admin
                    <?= htmlspecialchars($admin_name); ?></a>
            </div>
            <a href="admin_home.php"
                class="text-blue-600 font-semibold px-4 py-2 rounded hover:bg-blue-700 hover:text-white">Home</a>
            <a href="admin_dashboard.php"
                class=" text-blue-600 font-semibold px-4 py-2 rounded hover:bg-blue-700 hover:text-white">Accounts</a>
            <a href="profile.php"
                class="bg-blue-600 text-white font-semibold px-4 py-2 rounded hover:bg-blue-700 hover:text-white">Profile</a>
            <a href="logout.php" class="text-white bg-red-500 px-4 py-2 rounded hover:bg-red-600 font-bold">Logout</a>
        </nav>
    </div>

    <!-- Main Content -->
    <div class="flex-grow p-4">
    <div class="w-auto mx-auto mt-8 ml-5">
        <div class="bg-white p-8 rounded-lg shadow-lg space-y-4">
        <div class="flex justify-between">
            <h1 class="text-3xl font-bold text-start text-blue-600 mb-6">Profile</h1>
            <a href="update_profile.php" class="text-white bg-blue-600 hover:bg-blue-700 hover:text-white px-4 py-2 rounded font-bold justify-items-end text-end">Update Profile</a>
        </div>
            <div class="flex items-center flex-grow">
                <img src="./../assets/admin-icon.png" alt="Profile Picture" class="w-16 h-16 rounded-full mr-4">
                <div class="flex-grow px-4 py-2 gap-4 space-y-4">
                    <h3 class="text-xl text-blue-600 font-semibold mb-2">
                        Admin Name: <?= htmlspecialchars($admin_name); ?>
                    </h3>
                    <p class="text-blue-600">
                        Role: <?= htmlspecialchars($admin_role); ?>
                    </p>
                   
                <!-- Move the button inside this div and apply ml-auto to push it right -->

            </div>
        </div>
    </div>
</div>

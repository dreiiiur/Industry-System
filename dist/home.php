<?php 
session_start(); 

// Ensure User is Logged In
if (!isset($_SESSION["user_id"])) { 
    header("Location: login.php"); 
    exit(); 
}

// Get User's Name from Session (Ensure it is set)
$user_name = isset($_SESSION["username"]) ? $_SESSION["username"] : "User"; 
$user_role = isset($_SESSION["role"]) ? $_SESSION["role"] :"User";
?>
<head>
    <link rel="stylesheet" href="style.css">
</head>

<div class="min-h-screen bg-gray-100 flex flex-col justify-center">
    <div class="max-w-xl mx-auto p-4">
        <div class="bg-white rounded-lg shadow-lg p-8">
            <h1 class="text-3xl font-bold mb-4">Welcome, <?php echo $user_role;?> <?= htmlspecialchars($user_name); ?></h1>
            <div class="flex flex-col gap-4 text-center">
                <a class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-3 px-5 rounded" href="profile.php">Update Profile</a>
                <a class="bg-red-400 hover:bg-red-700 text-white font-bold py-3 px-5 rounded" href="logout.php">Logout</a>
            </div>
        </div>
    </div>
</div>


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
    <title>Home</title>
    <link rel="stylesheet" href="style.css">
</head>
<?php include("user_header.php"); ?>

<div class="min-h-screen bg-gray-100 flex items-start">
    <div class="w-1/3 max-w-sm p-4 ml-8">
        <div class="bg-white rounded-lg shadow-lg p-8">
            <h1 class="text-blue-600 text-3xl font-bold mb-4">Welcome, <?php echo $user_role;?> <?= htmlspecialchars($user_name); ?></h1>
        </div>
    </div>
</div>



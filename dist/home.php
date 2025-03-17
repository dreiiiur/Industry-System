<?php 
session_start(); 

// Ensure User is Logged In
if (!isset($_SESSION["user_id"])) { 
    header("Location: login.php"); 
    exit(); 
}

// Get User's Name from Session (Ensure it is set)
$user_name = isset($_SESSION["username"]) ? $_SESSION["username"] : "User"; 
?>
<head>
    <link rel="stylesheet" href="style.css">
</head>
<h1>User Home</h1>

<h1>Welcome, <?= htmlspecialchars($user_name); ?></h1>
<a href="profile.php">Update Profile</a> | <a href="logout.php">Logout</a>

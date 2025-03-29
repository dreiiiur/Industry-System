<?php
session_start();
include("db.php");

// Check if user is logged in
if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit();
}
if (isset($_POST['submit'])) {
    $_SESSION['toast'] = "User added successfully!";
    header("Location: admin_home.php"); // Redirect to refresh page
    exit();
}

$userId = $_SESSION["user_id"];

// Fetch user details securely
$stmt = $conn->prepare("SELECT * FROM users WHERE id = ?");
$stmt->bind_param("i", $userId);
$stmt->execute();
$result = $stmt->get_result();
$admin_name = isset($_SESSION["username"]) ? $_SESSION["username"] : "Admin"; 
$admin_role = isset($_SESSION["role"]) ? $_SESSION["role"] :"Admin";

if ($result->num_rows == 0) {
    die("Error: User not found.");
}

$user = $result->fetch_assoc();
$stmt->close();

// Handle profile update
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $newUsername = trim($_POST["username"]);
    $newEmail = trim($_POST["email"]);
    $newAge = intval($_POST["age"]);
    $newAddress = trim($_POST["address"]);

    $stmt = $conn->prepare("UPDATE users SET username = ?, email = ?, age = ?, address = ? WHERE id = ?");
    $stmt->bind_param("ssisi", $newUsername, $newEmail, $newAge, $newAddress, $userId);

    if ($stmt->execute()) {
        echo "Profile updated successfully!";
        header("Location: profile.php");
        exit();
    } else {
        echo "Error updating profile.";
    }
    $stmt->close();
}

// Handle password update
if (isset($_POST["password"]) && !empty($_POST["password"])) {
    $password = $_POST["password"];
    $confirmPassword = $_POST["confirm_password"];

    if ($password === $confirmPassword) {
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $conn->prepare("UPDATE users SET password = ? WHERE id = ?");
        $stmt->bind_param("si", $hashedPassword, $userId);
        $stmt->execute();
        $stmt->close();
        echo "Password updated successfully!";
    } else {
        echo "Error: Passwords do not match!";
    }
}
?>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Profile</title>
    <link rel="stylesheet" href="style.css">
    <style>
        /* Simple Toast CSS */
        .toast-message {
            position: fixed;
            bottom: 20px;
            right: 20px;
            background-color: #4CAF50;
            color: white;
            padding: 15px;
            border-radius: 5px;
            box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1);
            display: none;
        }
    </style>
</head>
<header class="bg-transparent flex justify-between items-center p-4 md:p-6">
    <a class="text-blue-600 font-bold text-3xl md:text-4xl">Accounts</a>
</header>
<main class="flex h-1/3 min-h-screen w-full bg-gray-100">
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

    <div class="w-1/2 mx-auto mt-8">
        <div class=" bg-white p-8 rounded-lg shadow-lg space-y-4">
        <h2 class="text-blue-600 text-2xl font-bold text-center mb-4">Update Profile</h2>
        <form method="POST" class="space-y-4">
            <div class="space-y-1">
                <label for="username" class="block text-blue-600 font-medium">Username:</label>
                <input type="text" name="username" id="username" value="<?= htmlspecialchars($user['username']); ?>" required class="block w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            <div class="space-y-1">
                <label for="email" class="block text-blue-600 font-medium">Email:</label>
                <input type="email" name="email" id="email" value="<?= htmlspecialchars($user['email']); ?>" required class="block w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            <div class="space-y-1">
                <label for="age" class="block text-blue-600 font-medium">Age:</label>
                <input type="number" name="age" id="age" value="<?= htmlspecialchars($user['age']); ?>" required class="block w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            <div class="space-y-1">
                <label for="address" class="block text-blue-600 font-medium">Address:</label>
                <input type="text" name="address" id="address" value="<?= htmlspecialchars($user['address']); ?>" required class="block w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            <h3 class="text-lg font-bold mt-4 text-blue-600">Change Password (Optional)</h3>
            <div class="space-y-1">
                <label for="password" class="block text-blue-600 font-medium">New Password:</label>
                <input type="password" name="password" id="password" class="block w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            <div class="space-y-1">
                <label for="confirm_password" class="block text-blue-600 font-medium">Confirm Password:</label>
                <input type="password" name="confirm_password" id="confirm_password" class="block w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            <button type="submit" name="update" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">Update</button>
        </form>
        </div>
        
    </div>


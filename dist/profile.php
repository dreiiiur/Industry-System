<?php
session_start();
include("db.php");

// Check if user is logged in
if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit();
}

$userId = $_SESSION["user_id"];

// Fetch user details securely
$stmt = $conn->prepare("SELECT * FROM users WHERE id = ?");
$stmt->bind_param("i", $userId);
$stmt->execute();
$result = $stmt->get_result();

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

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Profile</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<header class="bg-blue-600 flex justify-between items-center p-4 md:p-6">
<a class="text-white font-bold text-3xl md:text-4xl">Update Profile</a>
    <nav class="flex space-x-4 md:space-x-8">
        <a href="admin_home.php" class="text-white font-semibold px-4 py-2 rounded">Home</a>
        <a href="admin_dashboard.php" class="text-white font-semibold px-4 py-2 rounded">User Management</a>
        <a href="profile.php" class="text-white font-semibold  px-4 py-2 rounded">Update Profile</a>
        <a href="logout.php" class="bg-red-600 text-white hover:bg-red-400 font-semibold px-4 py-2 rounded">Logout</a>
    </nav>
</header>
    <div class="max-w-lg mx-auto p-6 bg-white">
        <h2 class=" text-black text-2xl font-bold text-center mb-4">Update Profile</h2>
        <form method="POST" class="space-y-6">
            <div class="space-y-1">
                <label for="username" class="block text-gray-700 font-medium">Username:</label>
                <input type="text" name="username" id="username" value="<?= htmlspecialchars($user['username']); ?>" required class="block w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            <div class="space-y-1">
                <label for="email" class="block text-gray-700 font-medium">Email:</label>
                <input type="email" name="email" id="email" value="<?= htmlspecialchars($user['email']); ?>" required class="block w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            <div class="space-y-1">
                <label for="age" class="block text-gray-700 font-medium">Age:</label>
                <input type="number" name="age" id="age" value="<?= htmlspecialchars($user['age']); ?>" required class="block w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            <div class="space-y-1">
                <label for="address" class="block text-gray-700 font-medium">Address:</label>
                <input type="text" name="address" id="address" value="<?= htmlspecialchars($user['address']); ?>" required class="block w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            <h3 class="text-lg font-bold mt-6 text-gray-700">Change Password (Optional)</h3>
            <div class="space-y-1">
                <label for="password" class="block text-gray-700 font-medium">New Password:</label>
                <input type="password" name="password" id="password" class="block w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            <div class="space-y-1">
                <label for="confirm_password" class="block text-gray-700 font-medium">Confirm Password:</label>
                <input type="password" name="confirm_password" id="confirm_password" class="block w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            <button type="submit" class="w-full bg-blue-500 hover:bg-blue-600 text-white font-bold py-3 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">Update</button>
        </form>
    </div>
</body>
</html>

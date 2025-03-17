<?php
session_start();
include("db.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST["username"]);
    $email = trim($_POST["email"]);
    $password = $_POST["password"];
    $confirm_password = $_POST["confirm_password"];
    $age = intval($_POST["age"]);
    $address = trim($_POST["address"]);
    $role = isset($_POST["role"]) && ($_POST["role"] == "admin") ? "admin" : "user";

    // Check if passwords match
    if ($password !== $confirm_password) {
        echo "Error: Passwords do not match!";
        exit();
    }

    // Secure password hashing
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Use Prepared Statements to Prevent SQL Injection
    $stmt = $conn->prepare("INSERT INTO users (username, email, password, age, address, role) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssss", $username, $email, $hashed_password, $age, $address, $role);

    if ($stmt->execute()) {
        header("Location: admin_dashboard.php");
        exit();
    } else {
        echo "Error: " . $conn->error;
    }

    $stmt->close();
    $conn->close();
}
?>
<head>
<link rel="stylesheet" href="style.css">
</head>
<header class="bg-blue-600 flex justify-between items-center p-4 md:p-6">
    <a class="text-white font-bold text-3xl md:text-4xl">Add User</a>
    <nav class="flex space-x-4 md:space-x-8">
        <a href="admin_home.php" class="text-white font-semibold px-4 py-2 rounded">Home</a>
        <a href="admin_dashboard.php" class="text-white font-semibold px-4 py-2 rounded">User Management</a>
        <a href="profile.php" class="text-white font-semibold  px-4 py-2 rounded">Update Profile</a>
        <a href="logout.php" class="bg-red-600 text-white hover:bg-red-400 font-semibold px-4 py-2 rounded">Logout</a>
    </nav>
</header>
<form method="POST" class="space-y-4 max-w-md mx-auto bg-white p-6 rounded-lg shadow-md">
    <h2 class="text-2xl font-bold mb-4 text-center text-black">Add User</h2>
    <input type="text" name="username" placeholder="Username" required class="block w-full px-4 py-2 border border-gray-300 rounded-md">
    <input type="email" name="email" placeholder="Email" required class="block w-full px-4 py-2 border border-gray-300 rounded-md">
    <input type="password" name="password" placeholder="Password" required class="block w-full px-4 py-2 border border-gray-300 rounded-md">
    <input type="password" name="confirm_password" placeholder="Confirm Password" required class="block w-full px-4 py-2 border border-gray-300 rounded-md">
    <input type="number" name="age" placeholder="Age" required class="block w-full px-4 py-2 border border-gray-300 rounded-md">
    <input type="text" name="address" placeholder="Address" required class="block w-full px-4 py-2 border border-gray-300 rounded-md">
    <select name="role" class="block w-full px-4 py-2 border border-gray-300 rounded-md">
        <option value="user">User</option>
        <option value="admin">Admin</option>
    </select>
    <button type="submit" class="w-full bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 rounded-md">Create User</button>
</form>

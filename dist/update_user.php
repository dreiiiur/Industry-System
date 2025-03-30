<?php
session_start();
include("db.php");

$admin_name = isset($_SESSION["username"]) ? $_SESSION["username"] :"Admin";
// Check if 'id' exists in URL
if (!isset($_GET["id"]) || empty($_GET["id"])) {
    die("Error: User ID not provided.");
}

$id = intval($_GET["id"]); // Convert to integer for security

// Fetch user details securely
$stmt = $conn->prepare("SELECT * FROM users WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) {
    die("Error: User not found.");
}

$row = $result->fetch_assoc();
$stmt->close();

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST["username"]);
    $email = trim($_POST["email"]);
    $age = intval($_POST["age"]);
    $address = trim($_POST["address"]);
    $role = $_POST["role"];

    // Update user info
    $stmt = $conn->prepare("UPDATE users SET username = ?, email = ?, age = ?, address = ?, role = ? WHERE id = ?");
    $stmt->bind_param("ssissi", $username, $email, $age, $address, $role, $id);

    if ($stmt->execute()) {
        echo "User updated successfully!";
        header("Location: admin_dashboard.php");
        exit();
    } else {
        echo "Error updating user.";
    }
    $stmt->close();
}

// Handle password update
if (isset($_POST["password"]) && !empty($_POST["password"])) {
    $password = $_POST["password"];
    $confirm_password = $_POST["confirm_password"];

    if ($password === $confirm_password) {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $conn->prepare("UPDATE users SET password = ? WHERE id = ?");
        $stmt->bind_param("si", $hashed_password, $id);
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
    <title>Update User</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<header class="bg-transparent flex justify-between items-center p-4 md:p-6 sticky top-0 z-50">
    <a class="text-blue-600 font-bold text-3xl md:text-4xl">Update User</a>
</header>
<div class="flex min-h-screen flex-grow bg-gray-100">
    <!-- Sidebar -->
    <div class="w-64 bg-white p-4 text-[blue-600] flex flex-col">
        <nav class="flex flex-col gap-4 text-blue-600">
            <div class="flex items-center hover:bg-gray-200 text-white">
                <img src="./../assets/admin-icon.png" alt="Profile Picture" class="w-8 h-8 rounded-full mr-2">
                <a href="#" class="text-blue-600 font-bold px-4 py-2 rounded text-2xl ">Admin
                    <?= htmlspecialchars($admin_name); ?></a>
            </div>
            <a href="admin_home.php"
                class="bg-blue-600 text-white font-semibold px-4 py-2 rounded hover:bg-blue-700 hover:text-white">Home</a>
            <a href="admin_dashboard.php"
                class=" font-semibold px-4 py-2 rounded hover:bg-blue-700 hover:text-white">Accounts</a>
            <a href="profile.php"
                class=" font-semibold px-4 py-2 rounded hover:bg-blue-700 hover:text-white">Profile</a>
            <a href="logout.php" class="text-white bg-red-500 px-4 py-2 rounded hover:bg-red-600 font-bold">Logout</a>
        </nav>
    </div>

    <div class="flex-grow p-4 gap-4">
    <div class="max-w-lg mx-auto p-6 bg-white rounded-lg shadow-lg gap-6">
        <h2 class=" text-blue-600 text-3xl font-bold text-center mb-6">Update User</h2>
        <form method="POST" class="space-y-4">
            <div class="">
                <label for="username" class="block text-gray-700">Username:</label>
                <input type="text" name="username" id="username" value="<?= htmlspecialchars($row['username']); ?>" required class="block w-full px-4 py-2 border-2 border-gray-300 rounded-md">
            </div>
            <div class="">
                <label for="email" class="block text-gray-700">Email:</label>
                <input type="email" name="email" id="email" value="<?= htmlspecialchars($row['email']); ?>" required class="block w-full px-4 py-2 border-2 border-gray-300 rounded-md">
            </div>
            <div class="">
                <label for="age" class="block text-gray-700">Age:</label>
                <input type="number" name="age" id="age" value="<?= htmlspecialchars($row['age']); ?>" required class="block w-full px-4 py-2 border-2 border-gray-300 rounded-md">
            </div>
            <div class="">
                <label for="address" class="block text-gray-700">Address:</label>
                <input type="text" name="address" id="address" value="<?= htmlspecialchars($row['address']); ?>" required class="block w-full px-4 py-2 border-2 border-gray-300 rounded-md">
            </div>
            <button type="submit" class=" w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 rounded-md">Update</button>
        </form>
    </div>
    </div>
   
</body>
</html>

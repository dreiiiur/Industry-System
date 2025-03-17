<?php
session_start();
include("db.php");

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
<header class="bg-blue-600 flex justify-between items-center p-4 md:p-6">
<a class="text-white font-bold text-3xl md:text-4xl">Update Profile</a>
    <nav class="flex space-x-4 md:space-x-8">
        <a href="admin_home.php" class="text-white font-semibold px-4 py-2 rounded">Home</a>
        <a href="admin_dashboard.php" href="Home.php" class="text-white font-semibold px-4 py-2 rounded">User Management</a>
        <a href="profile.php" class="text-white font-semibold  px-4 py-2 rounded">Update Profile</a>
        <a href="logout.php" class="bg-red-600 text-white hover:bg-red-400 font-semibold px-4 py-2 rounded">Logout</a>
    </nav>
</header>
    <div class="max-w-lg mx-auto p-6 bg-white rounded-lg shadow-lg gap-">
        <h2 class="text-2xl font-bold text-center mb-4">Update User</h2>
        <form method="POST">
            <div class="space-y-2">
                <label for="username" class="block text-gray-700">Username:</label>
                <input type="text" name="username" id="username" value="<?= htmlspecialchars($row['username']); ?>" required class="block w-full px-4 py-2 border-2 border-gray-300 rounded-md">
            </div>
            <div class="space-y-2">
                <label for="email" class="block text-gray-700">Email:</label>
                <input type="email" name="email" id="email" value="<?= htmlspecialchars($row['email']); ?>" required class="block w-full px-4 py-2 border-2 border-gray-300 rounded-md">
            </div>
            <div class="space-y-2">
                <label for="age" class="block text-gray-700">Age:</label>
                <input type="number" name="age" id="age" value="<?= htmlspecialchars($row['age']); ?>" required class="block w-full px-4 py-2 border-2 border-gray-300 rounded-md">
            </div>
            <div class="space-y-2">
                <label for="address" class="block text-gray-700">Address:</label>
                <input type="text" name="address" id="address" value="<?= htmlspecialchars($row['address']); ?>" required class="block w-full px-4 py-2 border-2 border-gray-300 rounded-md">
            </div>
            <button type="submit" class="block w-full bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-md">Update</button>
        </form>
    </div>
</body>
</html>

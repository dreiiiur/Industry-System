<?php
session_start();
include("db.php");

$admin_name = isset($_SESSION["username"]) ? $_SESSION["username"] : "Admin";
$admin_role = isset($_SESSION["role"]) ? $_SESSION["role"] : "Admin";

if (!isset($_SESSION["user_id"]) || $_SESSION["role"] != "admin") {
    header("Location: login.php");
    exit();
}

// Process form submission
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

    // Use prepared statements
    $stmt = $conn->prepare("INSERT INTO users (username, email, password, age, address, role) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sssiss", $username, $email, $hashed_password, $age, $address, $role);

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
<title>Add User</title>
<link rel="stylesheet" href="style.css">
</head>
<header class="bg-transparent flex justify-between items-center p-4 md:p-6 sticky top-0 z-50">
    <a class="text-blue-600 font-bold text-3xl md:text-4xl">Add User</a>
</header>

<div class="flex min-h-screen flex-grow bg-gray-100">
    <!-- Sidebar -->
    <div class="w-64 bg-white p-4 text-[blue-600] flex flex-col">
        <nav class="flex flex-col gap-4 text-blue-600">
            <div class="flex items-center hover:bg-gray-200 text-white">
                <img src="./../assets/admin-icon.png" alt="Profile Picture" class="w-8 h-8 rounded-full mr-2">
                <a href="#" class="text-blue-600 font-bold px-4 py-2 rounded text-2xl ">Admin
                    <?php echo htmlspecialchars($admin_name); ?></a>
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

    <div class="flex-grow p-4 justify-center items-center">
    <form method="POST" class="space-y-6 max-w-4xl mx-auto bg-white p-8 rounded-lg shadow-lg w-1/2 gap-4 justify-center items-center">
    <h2 class="text-3xl font-bold text-center text-blue-600 mb-6">Add User</h2>
    
    <div class="space-y-4">
        <input type="text" name="username" placeholder="Username" required class="block w-full px-4 py-3 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
        <input type="email" name="email" placeholder="Email" required class="block w-full px-4 py-3 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
        <input type="password" name="password" placeholder="Password" required class="block w-full px-4 py-3 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
        <input type="password" name="confirm_password" placeholder="Confirm Password" required class="block w-full px-4 py-3 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
        <input type="number" name="age" placeholder="Age" required class="block w-full px-4 py-3 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
        <input type="text" name="address" placeholder="Address" required class="block w-full px-4 py-3 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
        <select name="role" class="block w-full px-4 py-3 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
            <option value="user">User</option>
            <option value="admin">Admin</option>
        </select>
    </div>
    
    <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">Create User</button>
</form>
    </div>


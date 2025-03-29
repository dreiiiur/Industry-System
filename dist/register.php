<head>
    <title>Landing Page</title>
    <link rel="stylesheet" href="style.css">
</head>
<?php
session_start();
include("db.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST["username"]);
    $email = trim($_POST["email"]);
    $age = trim($_POST["age"]);
    $address = trim($_POST["address"]);
    $password = $_POST["password"];
    $confirm_password = $_POST["confirm_password"];

    // Ensure role is set correctly
    $role = isset($_POST["role"]) && ($_POST["role"] == "admin") ? "admin" : "user";

    // Password validation
    if ($password !== $confirm_password) {
        echo "Error: Passwords do not match!";
        exit();
    }

    // Hash the password securely
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Insert user into the database
    $stmt = $conn->prepare("INSERT INTO users (username, email, age, address, password, role) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssss", $username, $email, $age, $address, $hashed_password, $role);

    if ($stmt->execute()) {
        echo "Registration successful!";
        header("Location: login.php");
        exit();
    } else {
        echo "Error: " . $conn->error;
    }

    $stmt->close();
    $conn->close();
}
?>

<div class="min-h-screen bg-blue-600 flex flex-col justify-center gap-4">
    <div class="bg-white max-w-3xl mx-auto p-6 xl:p-12 w-1/2 rounded-lg shadow-lg gap-5">
        <h2 class="text-3xl font-bold mb-4 text-center text-blue-600">Create an Account</h2>
        <form method="POST" class="space-y-4">
            <div class="grid grid-cols-2 gap-4">
            <div class="space-y-2">
                <label for="username" class="block text-gray-500">Username</label>
                <input type="text" name="username" id="username" required class="block w-full px-4 py-3 border-2 border-gray-300 rounded-md">
            </div>
            <div class="space-y-2">
                <label for="email" class="block text-gray-500">Email</label>
                <input type="email" name="email" id="email" required class="block w-full px-4 py-3 border-2 border-gray-300 rounded-md">
            </div>
            </div>
            <div class="space-y-2">
                <label for="age" class="block text-gray-500">Age</label>
                <input type="number" name="age" id="age" required class="block w-full px-4 py-3 border-2 border-gray-300 rounded-md">
            </div>
           
            <div class="space-y-2">
                <label for="address" class="block text-gray-500">Address</label>
                <input type="text" name="address" id="address" required class="block w-full px-4 py-3 border-2 border-gray-300 rounded-md">
            </div>
            <div class="space-y-2">
                <label for="password" class="block text-gray-500">Password</label>
                <input type="password" name="password" id="password" required class="block w-full px-4 py-3 border-2 border-gray-300 rounded-md">
            </div>
            <div class="space-y-2">
                <label for="confirm_password" class="block text-gray-500">Confirm Password</label>
                <input type="password" name="confirm_password" id="confirm_password" required class="block w-full px-4 py-3 border-2 border-gray-300 rounded-md">
            </div>
            <div class="space-y-2">
                <label for="role" class="block text-gray-500">Register as:</label>
                <select name="role" id="role" class="block w-full px-4 py-3 border-2 border-gray-300 rounded-md">
                    <option value="user">User</option>
                    <option value="admin">Admin</option>
                </select>
            </div>
            <button type="submit" class="block w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-4 rounded-md">Register</button>
            <div class="text-center">
                <a class="text-gray-500 hover:text-blue-500" href="login.php">Already have an account? Login</a>
            </div>
        </form>
    </div>
</div>


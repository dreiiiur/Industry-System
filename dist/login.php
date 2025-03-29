<head>
    <title>Landing Page</title>
    <link rel="stylesheet" href="style.css">
</head>
<?php
session_start();
include("db.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST["email"];
    $password = $_POST["password"];

    $result = $conn->query("SELECT * FROM users WHERE email='$email'");
    $user = $result->fetch_assoc();

    if ($user && password_verify($password, $user["password"])) {
        $_SESSION["user_id"] = $user["id"];
        $_SESSION["username"] = $user["username"];
        $_SESSION["role"] = $user["role"];

        if ($user["role"] == "admin") {
            $_SESSION["admin_logged_in"] = true;
            header("Location: admin_home.php");
        } else {
            header("Location: home.php");
        }
    } else {    
        echo "Invalid login credentials!";
    }
}
?>

<!-- #region Login -->
<div class="relative min-h-screen flex items-center justify-center bg-cover bg-center" style="background-image: url('./../assets/loginbg.jpg');">
    <!-- Dark Overlay -->
    <div class="absolute inset-0 bg-black opacity-50"></div>

    <!-- Login Form Container -->
    <div class="relative z-10 bg-white p-12 rounded-lg shadow-lg w-full max-w-lg">
        <form method="POST" class="space-y-4">
            <h2 class="text-3xl font-bold mb-2 text-center text-blue-600">Industry System Login</h2>
            <div class="space-y-2">
                <input type="email" name="email" id="email" placeholder="Email" required class="block w-full px-4 py-3 border-2 border-gray-300 rounded-md">
            </div>
            <div class="space-y-2">
                <input type="password" name="password" id="password" placeholder="Password" required class="block w-full px-4 py-3 border-2 border-gray-300 rounded-md">
            </div>
            <button type="submit" class="block w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-4 rounded-md">Login</button>
            <div class="text-center">
                <a class="text-gray-500 hover:text-blue-500" href="register.php">Don't have an account? Create one</a>
            </div>
        </form>
    </div>
</div>



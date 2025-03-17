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

<div class="min-h-screen bg-gray-100 flex flex-col justify-center">
    <div class="max-w-lg mx-auto w-full">
        <div class="bg-white p-12 rounded-lg shadow-lg">
            <form method="POST" class="gap-5">
                <h2 class="text-3xl font-bold mb-2 text-center text-black">Login</h2>
                <div class="space-y-2">
                    <label for="email" class="block text-gray-500">Email</label>
                    <input type="email" name="email" id="email" required class="block w-full px-4 py-3 border-2 border-gray-300 rounded-md">
                <div class="space-y-2">
                    <label for="password" class="block text-gray-500">Password</label>
                    <input type="password" name="password" id="password" required class="block w-full px-4 py-3 border-2 border-gray-300 rounded-md">
                </div>
                <button type="submit" class="block w-full bg-blue-500 hover:bg-blue-700 text-white font-bold py-3 px-4 rounded-md">Login</button>
                <div class="text-center">
                    <a class="text-gray-500 hover:text-blue-500" href="register.php">Don't have an account? Create one</a>
                </div>
            </form>
        </div>
    </div>
</div>


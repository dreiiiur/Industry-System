<head>
    <title>Update Profile</title>
    <link rel="stylesheet" href="style.css">
</head>

<?php
session_start();
include("db.php");

if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit();
}
if (isset($_POST["submit"])) {
    $_SESSION["toast"] = "Password updated successfully!";
    header("Location: home.php"); // Redirect to refresh page
    exit();
}


if (isset($_POST['submit'])) {
    if (isset($_POST['password']) && !empty($_POST['password'])) {
        $password = $_POST['password'];
        $confirm_password = $_POST['confirm_password'];

        if ($password === $confirm_password) {
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $conn->prepare("UPDATE users SET password = ? WHERE id = ?");
            $stmt->bind_param("si", $hashed_password, $_SESSION["user_id"]);
            $stmt->execute();
            $stmt->close();
            echo "Password updated successfully!";
        } else {
            echo "Error: Passwords do not match!";
        }
    } else {
        echo "Error: Password field is empty!";
    }
}
?>

    <?php include("user_header.php"); ?>
<div class="min-h-screen bg-gray-100 flex flex-col mt-4 ">
    <div class="mt-12 container mx-auto p-4 md:p-6 bg-white rounded-lg shadow-lg w-1/2 mb-6 md:mt-24" style="margin-top: 20px;">
        <h1 class="text-3xl font-bold text-center text-blue-600 mb-6">Update Profile</h1>
        <form method="POST" action="<?php echo $_SERVER['PHP_SELF']; ?>" class="space-y-6">
            <div class="flex flex-col md:flex-row items-center">
                <label for="name" class="w-full md:w-1/3 mr-4 md:mr-0">Name:</label>
                <input type="text" id="name" name="name" required class="w-full md:w-2/3 px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            <div class="flex flex-col md:flex-row items-center">
                <label for="email" class="w-full md:w-1/3 mr-4 md:mr-0">Email:</label>
                <input type="email" id="email" name="email" required class="w-full md:w-2/3 px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            <div class="flex flex-col md:flex-row items-center">
                <label for="password" class="w-full md:w-1/3 mr-4 md:mr-0">Password:</label>
                <input type="password" id="password" name="password" required class="w-full md:w-2/3 px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            <div class="flex flex-col md:flex-row items-center">
                <label for="confirm_password" class="w-full md:w-1/3 mr-4 md:mr-0">Confirm Password:</label>
                <input type="password" id="confirm_password" name="confirm_password" required class="w-full md:w-2/3 px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            <input type="submit" name="submit" value="Update Password" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-4 rounded-lg">
        </form> 
    </div>
</div>


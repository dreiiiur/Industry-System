<head>
    <title>Profile</title>
    <link rel="stylesheet" href="style.css">
</head>
<?php include("user_header.php"); ?>

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

$user_name = isset($_SESSION["username"]) ? $_SESSION["username"] : "User";
$user_role = isset($_SESSION["role"]) ? $_SESSION["role"] : "User";
?>

<div class="min-h-screen bg-gray-100 flex flex-col mt-4 ">
    <div class="mt-12 container mx-auto p-4 md:p-6 bg-white rounded-lg shadow-lg w-full mb-6 md:mt-24"
        style="margin-top: 20px;">
        <div class="flex justify-between">
            <h1 class="text-3xl font-bold text-start text-blue-600 mb-6">Profile</h1>
            <a href="user_profile_update.php" class="text-white bg-blue-600 hover:bg-blue-700 hover:text-white px-4 py-2 rounded font-bold justify-items-end text-end">Update Profile</a>
        </div>

        <div>
            <div class="flex items-center flex-grow">
                <img src="./../assets/admin-icon.png" alt="Profile Picture" class="w-16 h-16 rounded-full mr-4">
                <div class="flex-grow px-4 py-2 gap-4 space-y-4">
                    <h3 class="text-xl text-blue-600 font-semibold mb-2">
                        Name: <?= htmlspecialchars($user_name); ?>
                    </h3>

                </div>
            </div>
        </div>
    </div>
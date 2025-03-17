<?php
session_start();
include("db.php");

// Check if admin is logged in
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: login.php");
    exit();
}

// Handle user deletion securely
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']); // Sanitize input
    $stmt = $conn->prepare("DELETE FROM users WHERE id = ?");
    $stmt->bind_param("i", $id);
    
    if ($stmt->execute()) {
        header("Location: admin_dashboard.php");
        exit();
    } else {
        echo "Error deleting user.";
    }
    $stmt->close();
}

// Fetch all users
$result = $conn->query("SELECT id, username, email, age, address, role FROM users");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<header class="bg-blue-600 flex justify-between items-center p-4 md:p-6">
    <a class="text-white font-bold text-3xl md:text-4xl">Dashboard</a>
    <nav class="flex space-x-4 md:space-x-8">
        <a href="admin_home.php" class="text-white font-semibold px-4 py-2 rounded">Home</a>
        <a href="admin_dashboard.php" class="text-white font-semibold px-4 py-2 rounded">User Management</a>
        <a href="profile.php" class="text-white font-semibold  px-4 py-2 rounded">Update Profile</a>
        <a href="logout.php" class="bg-red-600 text-white hover:bg-red-400 font-semibold px-4 py-2 rounded">Logout</a>
    </nav>
</header>
    <div class="container mx-auto p-4">
        <div class="flex justify-between items-center">
        <h2 class="text-3xl font-bold mt-8 mb-4">User Management</h2>
        <a href="add_user.php" class="bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded"> Add User</a>
        </div>
        <table class="table-auto w-full text-center">
            <thead>
            <tr class="bg-blue-600 text-center text-white">
                <th class="px-4 py-2">ID</th>
                <th class="px-4 py-2">Username</th>
                <th class="px-4 py-2">Email</th>
                <th class="px-4 py-2">Age</th>
                <th class="px-4 py-2">Address</th>
                <th class="px-4 py-2">Role</th>
                <th class="px-4 py-2">Actions</th>
            </tr>
            </thead>
            <tbody>
            <?php while ($row = $result->fetch_assoc()): ?>
                <tr class="bg-white border-b">
                    <td class="px-4 py-2 font-bold"><?= htmlspecialchars($row['id']); ?></td>
                    <td class="px-4 py-2"><?= htmlspecialchars($row['username']); ?></td>
                    <td class="px-4 py-2"><?= htmlspecialchars($row['email']); ?></td>
                    <td class="px-4 py-2"><?= htmlspecialchars($row['age']); ?></td>
                    <td class="px-4 py-2"><?= htmlspecialchars($row['address']); ?></td>
                    <td class="px-4 py-2"><?= htmlspecialchars($row['role']); ?></td>
                    <td class="px-4 py-2">
                        <a href="update_user.php?id=<?= $row['id']; ?>" class="text-blue-500 hover:text-blue-700">✏️ Edit</a>
                        <a href="admin_dashboard.php?delete=<?= $row['id']; ?>" class="text-red-500 hover:text-red-700" onclick="return confirm('Are you sure?')">🗑️ Delete</a>
                    </td>
                </tr>
            <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</body>
</html>

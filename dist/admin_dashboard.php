<head>
    <title>Accounts</title>
    <link rel="stylesheet" href="style.css">
</head>
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
        header("Location: admin_home.php");
        exit();
    } else {
        echo "Error deleting user.";
    }
    $stmt->close();
}

// Fetch all users
$admin_name = isset($_SESSION["username"]) ? $_SESSION["username"] : "Admin"; 
$admin_role = isset($_SESSION["role"]) ? $_SESSION["role"] :"Admin";
$result = $conn->query("SELECT id, username, email, age, address, role FROM users WHERE role != 'admin'");
?>

<header class="bg-transparent flex justify-between items-center p-4 md:p-6 sticky top-0 z-50">
    <a class="text-blue-600 font-bold text-3xl md:text-4xl">Accounts</a>
</header>
<div class="flex min-h-screen w-full bg-gray-100">
    <!-- Sidebar -->
    <div class="w-64 bg-white p-4 text-[blue-600] flex flex-col">
        <nav class="flex flex-col gap-4 text-blue-600">
            <div class="flex items-center hover:bg-gray-200 text-white">
                <img src="./../assets/admin-icon.png" alt="Profile Picture" class="w-8 h-8 rounded-full mr-2">
                <a href="#" class="text-blue-600 font-bold px-4 py-2 rounded text-2xl ">Admin
                    <?= htmlspecialchars($admin_name); ?></a>
            </div>
            <a href="admin_home.php"
                class="text-blue-600 font-semibold px-4 py-2 rounded hover:bg-blue-700 hover:text-white">Home</a>
            <a href="admin_dashboard.php"
                class="bg-blue-600 text-white font-semibold px-4 py-2 rounded hover:bg-blue-700 hover:text-white">Accounts</a>
            <a href="profile.php"
                class=" font-semibold px-4 py-2 rounded hover:bg-blue-700 hover:text-white">Profile</a>
            <a href="logout.php" class="text-white bg-red-500 px-4 py-2 rounded hover:bg-red-600 font-bold">Logout</a>
        </nav>
    </div>
        <!-- Users Section -->
        <div class="container mx-auto">
            <div class="flex items-center justify-between w-full mb-4 mt-6 bg-white p-6 rounded-2xl shadow-lg">
                <h2 class="text-3xl font-bold text-blue-600">Users</h2>
                <div class="flex items-center gap-4 w-full max-w-xl">
                    <form method="get" action="admin_dashboard.php" class="flex-grow">
                        <input type="search" name="search" placeholder="Search for names..."
                            value="<?php echo isset($_GET['search']) ? $_GET['search'] : ''; ?>"
                            class="w-full px-4 py-3 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                            oninput="this.form.submit()">
                        <button type="submit" class="hidden"></button>
                    </form>
                    <?php
                    $search = isset($_GET['search']) ? $_GET['search'] : '';
                    $sql = "SELECT id, username, email, age, address, role FROM users WHERE username LIKE '%$search%' AND role != 'admin'";
                    $result = $conn->query($sql);
                    ?>
                    <a href="add_user.php"
                        class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-4 rounded whitespace-nowrap h-full flex items-center justify-center">
                        Add User
                    </a>
                </div>
            </div>



            <!-- Table Section -->
            <div class="bg-white rounded-2xl shadow-lg overflow-x-auto p-6">
                <table class="table-auto w-full text-center rounded-xl shadow-lg">
                    <thead class="bg-blue-600 text-white mb-6">
                        <tr>
                            <th class="px-6 py-3">ID</th>
                            <th class="px-6 py-3">Username</th>
                            <th class="px-6 py-3">Email</th>
                            <th class="px-6 py-3">Age</th>
                            <th class="px-6 py-3">Address</th>
                            <th class="px-6 py-3">Role</th>
                            <th class="px-6 py-3">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="space-y-4">
                        
                        <?php while ($row = $result->fetch_assoc()): ?>
                            <tr class="bg-white border-b hover:bg-gray-100">
                                <td class="px-6 py-3 font-bold text-blue-600"><?= htmlspecialchars($row['id']); ?></td>
                                <td class="px-6 py-3"><?= htmlspecialchars($row['username']); ?></td>
                                <td class="px-6 py-3"><?= htmlspecialchars($row['email']); ?></td>
                                <td class="px-6 py-3"><?= htmlspecialchars($row['age']); ?></td>
                                <td class="px-6 py-3"><?= htmlspecialchars($row['address']); ?></td>
                                <td class="px-6 py-3"><?= htmlspecialchars($row['role']); ?></td>
                                <td class="px-6 py-3 flex gap-4 justify-center">
                                    <a href="update_user.php?id=<?= $row['id']; ?>"
                                        class="text-blue-500 hover:text-blue-700">✏️ Edit</a>
                                    <a href="admin_home.php?delete=<?= $row['id']; ?>"
                                        class="text-red-500 hover:text-red-700"
                                        onclick="return confirm('Are you sure you want to delete this account?')">🗑️
                                        Delete</a>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>


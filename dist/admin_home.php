<head>
    <title>Landing Page</title>
    <link rel="stylesheet" href="style.css">
</head>
<?php
session_start();
include("db.php");


// Ensure Admin is Logged In
if (!isset($_SESSION["user_id"]) || $_SESSION["role"] != "admin") {
    header("Location: login.php");
    exit();
}

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

// Get Admin's Name from Session (Ensure it is set)
$admin_name = isset($_SESSION["username"]) ? $_SESSION["username"] : "Admin";
$admin_role = isset($_SESSION["role"]) ? $_SESSION["role"] : "Admin";

$result = $conn->query("SELECT id, username, email, age, address, role FROM users WHERE role != 'admin'");
$total_accounts = $result->num_rows;
$stmt = $conn->prepare("SELECT id, username, email, age, address, role FROM users WHERE role != 'admin'");
$stmt->execute();
$result = $stmt->get_result();
?>
<header class="bg-transparent flex justify-between items-center p-4 md:p-6 sticky top-0 z-50">
    <a class="text-blue-600 font-bold text-3xl md:text-4xl">Dashboard</a>
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

    <!-- Main Content -->
    <div class="flex flex-col min-h-screen flex-grow bg-gray-100 mx-auto p-4">
        <div class="flex flex-col justify-between items-start gap-6 w-auto">

            <!-- Accounts Overview -->
            <div class="flex flex-row space-x-4">
            
             <div class="flex flex-col ">
             <h2 class="text-3xl font-bold mt-8 mb-4 text-blue-600">Accounts</h2>
            <div class="bg-white rounded-3xl p-8 shadow-md">
                <div class="flex flex-col items-center gap-4">
                    <h3 class="text-2xl font-semibold text-gray-600">Total Accounts:</h3>
                    <h3 class="text-2xl font-bold text-blue-600"><?= $total_accounts; ?></h3>
            </div>
            </div>
            </div>

            <!-- Logs Overview -->
            <div class="flex flex-r">
            
             <div class="flex flex-col">
             <h2 class="text-3xl font-bold mt-8 mb-4 text-red-500">Logs</h2>
            <div class="bg-white rounded-3xl p-8 shadow-md">
                <div class="flex flex-col items-center gap-4">
                    <h3 class="text-2xl font-semibold text-gray-600">Critical Tracks:</h3>
                    <h3 class="text-2xl font-bold text-red-500"><?= $total_accounts; ?></h3>
            </div>
            </div>
            </div>    
            </div>
        </div>

        <!-- Users Section -->
        <div class="bg-white p-6 rounded-lg shadow-lg mt-6 w-full">
            <!-- Header Section -->
            <!-- Header Section -->
            <div class="flex items-center justify-between w-full mb-4">
                <h2 class="text-3xl font-bold text-blue-600">Users</h2>
                <div class="flex items-center gap-4 w-full max-w-xl">
                    <form method="get" action="admin_home.php" class="flex-grow">
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
            <div class="overflow-x-auto p-6">
                <table class="table-auto w-full text-center rounded-xl">
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
                            <tr class="bg-white border-b">
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
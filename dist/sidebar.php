
<div class="flex min-h-screen w-full bg-gray-100">
    <!-- Sidebar -->
    <div class="w-64 bg-white p-4 text-[blue-600] flex flex-col">
        <nav class="flex flex-col space-y-4 text-blue-600">
            <div class="flex items-center hover:bg-gray-200 text-white">
                <img src="./../assets/admin-icon.png" alt="Profile Picture" class="w-8 h-8 rounded-full mr-2">
                <a href="#" class="text-blue-600 font-bold px-4 py-2 rounded text-2xl ">Admin <?= htmlspecialchars($admin_name); ?></a>
            </div>
            <a href="admin_home.php" class="text-blue-600 font-semibold px-4 py-2 rounded hover:bg-blue-700 hover:text-white">Home</a>
            <a href="admin_dashboard.php" class=" font-semibold px-4 py-2 rounded hover:bg-blue-700 hover:text-white">User Management</a>
            <a href="profile.php" class=" font-semibold px-4 py-2 rounded hover:bg-blue-700 hover:text-white">Profile</a>
            <a href="logout.php" class="text-white bg-red-500 px-4 py-2 rounded hover:bg-red-600 font-bold">Logout</a>
        </nav>
    </div>
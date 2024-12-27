<?php
session_start();
require_once '../classes/Database.php';
require_once '../classes/Country.php';
require_once '../classes/Ville.php';

// Check if user is logged in and is a regular user
if (!isset($_SESSION['user_id'])) {
    header('Location: Login.php');
    exit();
} elseif ($_SESSION['is_admin']) {
    header('Location: Admin_Dashboard.php');
    exit();
}

// Check if country_id is provided
if (!isset($_GET['country_id'])) {
    header('Location: User_Page.php');
    exit();
}

// Get country and its cities
$country = Country::getById($_GET['country_id']);
if (!$country) {
    header('Location: User_Page.php');
    exit();
}

$cities = Ville::getByPaysId($_GET['country_id']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Cities of <?= htmlspecialchars($country->getNom()) ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-[#F4A460]">
    <!-- Navbar -->
    <nav class="bg-[#8B4513] text-[#FFDAB9] p-4">
        <div class="max-w-7xl mx-auto px-4">
            <div class="flex justify-between items-center">
                <span class="text-2xl font-bold">Africa Géo-Junior</span>
                <a href="User_Page.php" class="hover:text-white">Back to Map</a>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="max-w-7xl mx-auto px-4 py-8">
        <h1 class="text-3xl font-bold text-[#8B4513] mb-8">
            Cities of <?= htmlspecialchars($country->getNom()) ?>
        </h1>

        <?php if (empty($cities)): ?>
            <p class="text-[#8B4513] text-xl">No cities found for this country.</p>
        <?php else: ?>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <?php foreach ($cities as $city): ?>
                    <div class="bg-[#FFDAB9] rounded-lg shadow-lg p-6 hover:shadow-xl transition-shadow">
                        <h2 class="text-2xl font-bold text-[#8B4513] mb-2">
                            <?= htmlspecialchars($city->getNom()) ?>
                        </h2>
                        <p class="text-[#8B4513] mb-2">
                            <span class="font-semibold">Type:</span> 
                            <?= htmlspecialchars($city->getType()) ?>
                        </p>
                        <p class="text-[#8B4513]">
                            <?= nl2br(htmlspecialchars($city->getDescription())) ?>
                        </p>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</body>
</html>

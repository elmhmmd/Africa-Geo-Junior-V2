<?php
session_start();
require_once '../classes/Database.php';
require_once '../classes/Country.php';
require_once '../classes/Ville.php';

// Check if user is logged in and is admin
if (!isset($_SESSION['user_id']) || !$_SESSION['is_admin']) {
    header('Location: Login.php');
    exit();
}

if (!isset($_GET['country_id'])) {
    header('Location: Admin_Dashboard.php');
    exit();
}

$country = Country::getById($_GET['country_id']);
if (!$country) {
    header('Location: Admin_Dashboard.php');
    exit();
}

$cities = Ville::getByPaysId($_GET['country_id']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Cities Admin - <?= htmlspecialchars($country->getNom()) ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-[#F4A460]">
    <!-- Navbar -->
    <nav class="bg-[#8B4513] text-[#FFDAB9] p-4">
        <div class="max-w-7xl mx-auto px-4">
            <div class="flex justify-between items-center">
                <span class="text-2xl font-bold">Cities of <?= htmlspecialchars($country->getNom()) ?></span>
                <a href="Admin_Dashboard.php" class="hover:text-white">Back to Countries</a>
            </div>
        </div>
    </nav>

    <!-- Success/Error Messages -->
    <?php if (isset($_GET['success'])): ?>
        <div class="max-w-7xl mx-auto px-4 py-3 mt-4">
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative">
                <span class="block sm:inline">City added successfully!</span>
            </div>
        </div>
    <?php endif; ?>

    <?php if (isset($_GET['error'])): ?>
        <div class="max-w-7xl mx-auto px-4 py-3 mt-4">
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative">
                <span class="block sm:inline">Error: <?= htmlspecialchars($_GET['error']) ?></span>
            </div>
        </div>
    <?php endif; ?>

    <!-- Main Content -->
    <div class="max-w-7xl mx-auto px-4 py-8">
        <button onclick="showAddCityForm()" class="bg-green-700 text-white px-4 py-2 rounded hover:bg-green-800 mb-8">
            Add City
        </button>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <?php foreach ($cities as $city): ?>
            <div class="bg-[#FFDAB9] rounded-lg shadow-lg p-6" id="city-<?= $city->getId() ?>">
                <form class="edit-form hidden" action="Modif_City.php" method="post">
                    <input type="hidden" name="id" value="<?= $city->getId() ?>">
                    <input type="text" name="nom" value="<?= htmlspecialchars($city->getNom()) ?>" 
                           class="w-full mb-2 p-2 rounded">
                    <input type="text" name="type" value="<?= htmlspecialchars($city->getType()) ?>" 
                           class="w-full mb-2 p-2 rounded">
                    <textarea name="description" class="w-full mb-2 p-2 rounded" rows="3"
                              ><?= htmlspecialchars($city->getDescription()) ?></textarea>
                    <div class="flex space-x-2">
                        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 flex-1">
                            Save
                        </button>
                        <button type="button" onclick="toggleEdit(<?= $city->getId() ?>)" 
                                class="bg-gray-600 text-white px-4 py-2 rounded hover:bg-gray-700 flex-1">
                            Cancel
                        </button>
                    </div>
                </form>
                <div class="city-info">
                    <h2 class="text-2xl font-bold text-[#8B4513] mb-2"><?= htmlspecialchars($city->getNom()) ?></h2>
                    <p class="text-[#3D1810] mb-2">Type: <?= htmlspecialchars($city->getType()) ?></p>
                    <p class="text-[#3D1810] mb-4"><?= nl2br(htmlspecialchars($city->getDescription())) ?></p>
                </div>
                <div class="buttons flex space-x-2">
                    <button onclick="toggleEdit(<?= $city->getId() ?>)" 
                            class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 flex-1">
                        Modify
                    </button>
                    <form action="Delete_City.php" method="get" class="flex-1">
                        <input type="hidden" name="city_id" value="<?= $city->getId() ?>">
                        <button type="submit" onclick="return confirm('Are you sure you want to delete this city?')"
                                class="bg-red-600 text-white px-4 py-2 rounded hover:bg-red-700 w-full">
                            Delete
                        </button>
                    </form>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>

    <!-- Add City Form -->
    <div id="add-city-form" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center">
        <div class="bg-[#FFDAB9] p-8 rounded-lg shadow-lg">
            <h2 class="text-2xl font-bold text-[#8B4513] mb-4">Add City</h2>
            <form action="Add_City.php" method="post" class="space-y-4">
                <input type="hidden" name="country_id" value="<?= htmlspecialchars($country->getId()) ?>">
                <input type="text" name="nom" placeholder="City Name" required minlength="1"
                       class="w-full p-2 rounded">
                <input type="text" name="type" placeholder="City Type (optional)"
                       class="w-full p-2 rounded">
                <textarea name="description" placeholder="Description (optional)"
                         class="w-full p-2 rounded" rows="3"></textarea>
                <div class="flex space-x-2">
                    <button type="submit" 
                            class="bg-[#8B4513] text-[#FFDAB9] px-4 py-2 rounded hover:bg-[#704214] flex-1">
                        Add
                    </button>
                    <button type="button" onclick="hideAddCityForm()" 
                            class="bg-gray-600 text-[#FFDAB9] px-4 py-2 rounded hover:bg-gray-700 flex-1">
                        Cancel
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function toggleEdit(cityId) {
            const cityElement = document.getElementById(`city-${cityId}`);
            const editForm = cityElement.querySelector('.edit-form');
            const cityInfo = cityElement.querySelector('.city-info');
            const buttons = cityElement.querySelector('.buttons');

            editForm.classList.toggle('hidden');
            cityInfo.classList.toggle('hidden');
            buttons.classList.toggle('hidden');
        }

        function showAddCityForm() {
            document.getElementById('add-city-form').classList.remove('hidden');
        }

        function hideAddCityForm() {
            document.getElementById('add-city-form').classList.add('hidden');
        }
    </script>
</body>
</html>
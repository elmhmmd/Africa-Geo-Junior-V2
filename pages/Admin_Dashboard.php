<?php
session_start();
require_once '../classes/Database.php';
require_once '../classes/Country.php';

// Check if user is logged in and is admin
if (!isset($_SESSION['user_id']) || !$_SESSION['is_admin']) {
    header('Location: Login.php');
    exit();
}

$countries = Country::getAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-[#F4A460]">
    <!-- Navbar -->
    <nav class="bg-[#8B4513] text-[#FFDAB9] p-4">
        <div class="max-w-7xl mx-auto px-4">
            <div class="flex justify-between items-center">
                <span class="text-2xl font-bold">Africa Géo-Junior Admin</span>
                <a href="Logout.php" class="hover:text-white">Logout</a>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="max-w-7xl mx-auto px-4 py-8">
        <div class="flex justify-between items-center mb-8">
            <button onclick="showAddCountryForm()" class="bg-green-700 text-white px-4 py-2 rounded hover:bg-green-800">
                Add Country
            </button>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <?php 
            // Load SVG content to get country codes
            $svgContent = new DOMDocument();
            $svgContent->load('../assets/africa.svg');
            $paths = $svgContent->getElementsByTagName('path');
            $countryCodeMap = [];
            foreach ($paths as $path) {
                if ($path->hasAttribute('data-name') && $path->hasAttribute('data-id')) {
                    $countryCodeMap[trim($path->getAttribute('data-name'))] = $path->getAttribute('data-id');
                }
            }

            foreach ($countries as $country): 
                $countryCode = isset($countryCodeMap[$country->getNom()]) ? $countryCodeMap[$country->getNom()] : '';
            ?>
            <div class="bg-[#FFDAB9] rounded-lg shadow-lg p-6" id="country-<?= $country->getId() ?>">
                <div class="flex justify-between items-start mb-4">
                    <h2 class="text-2xl font-bold text-[#8B4513]">
                        <span class="country-name"><?= htmlspecialchars($country->getNom()) ?></span>
                    </h2>
                    <?php if ($countryCode): ?>
                    <img src="https://flagsapi.com/<?= $countryCode ?>/flat/64.png" 
                         alt="<?= htmlspecialchars($country->getNom()) ?> flag" 
                         class="w-16 h-12 object-cover rounded">
                    <?php endif; ?>
                </div>
                <form class="edit-form hidden">
                    <input type="text" name="nom" value="<?= htmlspecialchars($country->getNom()) ?>" 
                           class="w-full mb-2 p-2 rounded">
                    <input type="number" name="population" value="<?= $country->getPopulation() ?>" 
                           class="w-full mb-2 p-2 rounded">
                    <input type="text" name="langue" value="<?= htmlspecialchars($country->getLangue()) ?>" 
                           class="w-full mb-2 p-2 rounded">
                </form>
                <div class="country-info">
                    <p class="text-[#3D1810] mb-2">Population: <?= number_format($country->getPopulation()) ?></p>
                    <p class="text-[#3D1810] mb-4">Languages: <?= htmlspecialchars($country->getLangue()) ?></p>
                </div>
                <div class="flex space-x-2">
                    <button onclick="toggleEdit(<?= $country->getId() ?>)" 
                            class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 flex-1">
                        Modify
                    </button>
                    <button onclick="deleteCountry(<?= $country->getId() ?>)" 
                            class="bg-red-600 text-white px-4 py-2 rounded hover:bg-red-700 flex-1">
                        Delete
                    </button>
                </div>
                <a href="admin_cities.php?country_id=<?= $country->getId() ?>" 
                   class="block text-center bg-[#8B4513] text-[#FFDAB9] px-4 py-2 rounded mt-2 hover:bg-[#704214]">
                    Show Cities
                </a>
            </div>
            <?php endforeach; ?>
        </div>
    </div>

    <!-- Add Country Modal -->
    <div id="addCountryModal" class="fixed inset-0 bg-black bg-opacity-50 hidden">
        <div class="bg-[#FFDAB9] max-w-md mx-auto mt-20 p-6 rounded-lg">
            <h2 class="text-2xl font-bold text-[#8B4513] mb-4">Add New Country</h2>
            <form id="addCountryForm" class="space-y-4">
                <input type="text" name="nom" placeholder="Country Name" required 
                       class="w-full p-2 rounded">
                <input type="number" name="population" placeholder="Population" required 
                       class="w-full p-2 rounded">
                <input type="text" name="langue" placeholder="Languages" required 
                       class="w-full p-2 rounded">
                <div class="flex space-x-2">
                    <button type="submit" class="bg-green-700 text-white px-4 py-2 rounded hover:bg-green-800 flex-1">
                        Save
                    </button>
                    <button type="button" onclick="hideAddCountryForm()" 
                            class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600 flex-1">
                        Cancel
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function toggleEdit(countryId) {
            const card = document.getElementById(`country-${countryId}`);
            const form = card.querySelector('.edit-form');
            const info = card.querySelector('.country-info');
            const modifyBtn = card.querySelector('button');

            if (form.classList.contains('hidden')) {
                form.classList.remove('hidden');
                info.classList.add('hidden');
                modifyBtn.textContent = 'Save';
                modifyBtn.onclick = () => saveChanges(countryId);
            } else {
                saveChanges(countryId);
            }
        }

        function saveChanges(countryId) {
            const card = document.getElementById(`country-${countryId}`);
            const form = card.querySelector('.edit-form');
            const formData = new FormData();
            
            formData.append('id', countryId);
            formData.append('nom', form.querySelector('[name="nom"]').value);
            formData.append('population', form.querySelector('[name="population"]').value);
            formData.append('langue', form.querySelector('[name="langue"]').value);

            fetch('admin_actions.php?action=update_country', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    location.reload();
                } else {
                    alert('Error updating country: ' + (data.message || 'Unknown error'));
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Error updating country');
            });
        }

        document.getElementById('addCountryForm').onsubmit = function(e) {
            e.preventDefault();
            const formData = new FormData(this);

            fetch('admin_actions.php?action=add_country', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    location.reload();
                } else {
                    alert('Error adding country: ' + (data.message || 'Unknown error'));
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Error adding country');
            });
        };

        function deleteCountry(countryId) {
            if (confirm('Are you sure you want to delete this country? This will also delete all its cities.')) {
                fetch(`admin_actions.php?action=delete_country&id=${countryId}`, {
                    method: 'POST'
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        document.getElementById(`country-${countryId}`).remove();
                    } else {
                        alert('Error deleting country: ' + (data.message || 'Unknown error'));
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Error deleting country');
                });
            }
        }

        function showAddCountryForm() {
            document.getElementById('addCountryModal').classList.remove('hidden');
        }

        function hideAddCountryForm() {
            document.getElementById('addCountryModal').classList.add('hidden');
        }
    </script>
</body>
</html>

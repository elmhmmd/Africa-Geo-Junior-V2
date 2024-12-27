<?php
session_start();
require_once '../classes/Database.php';
require_once '../classes/Country.php';

// Check if user is logged in and is a regular user
if (!isset($_SESSION['user_id'])) {
    header('Location: Login.php');
    exit();
} elseif ($_SESSION['is_admin']) {
    header('Location: Admin_Dashboard.php');
    exit(); 
}

// Get all countries data
try {
    $countries = Country::getAll();
    $countriesData = array_reduce($countries, function($acc, $country) {
        $acc[$country->getNom()] = [
            'id' => $country->getId(),
            'population' => $country->getPopulation(),
            'langue' => $country->getLangue()
        ];
        return $acc;
    }, []);
} catch (Exception $e) {
    $countriesData = [];
    error_log("Error fetching countries: " . $e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Africa Map with Flags</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body {
            margin: 0;
            overflow: hidden;
            background-color: #F4A460; /* Sandy brown background */
        }
        .map-container {
            display: flex;
            justify-content: center;
            align-items: center;
            height: calc(100vh - 64px - 2rem); /* Subtract navbar height and margins */
            margin: 1rem; /* Add margin all around */
        }
        svg {
            max-height: calc(100vh - 64px - 4rem); /* Leave room for margins */
            max-width: 95vw; /* Prevent horizontal overflow */
            width: auto;
            height: auto; /* Allow proportional scaling */
        }
        .country {
            fill: #FFDAB9; /* Peachpuff color for countries */
            stroke: #8B4513; /* Saddle brown for borders */
            stroke-width: 0.5;
            transition: fill 0.3s;
        }
        .country:hover {
            fill: #DEB887; /* Burlywood color on hover */
            cursor: pointer;
        }
        #flag-container {
            position: absolute;
            padding: 5px;
            background: white;
            border: 1px solid #8B4513;
            border-radius: 4px;
            display: none;
            z-index: 10;
            pointer-events: none;
        }
        #flag-container img {
            display: block;
        }
        #country-name {
            font-size: small;
            text-align: center;
            color: #3D1810;
            font-weight: bold;
        }
        .modal {
            display: none;
            position: fixed;
            z-index: 20;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0,0,0,0.5);
        }

        .modal-content {
            background-color: #FFDAB9;
            margin: 15% auto;
            padding: 20px;
            border: 2px solid #8B4513;
            border-radius: 8px;
            width: 80%;
            max-width: 500px;
            position: relative;
        }

        .close {
            color: #8B4513;
            float: right;
            font-size: 28px;
            font-weight: bold;
            cursor: pointer;
        }

        .show-cities-btn {
            background-color: #8B4513;
            color: #FFDAB9;
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            width: 100%;
            margin-top: 15px;
        }

        .show-cities-btn:hover {
            background-color: #704214;
        }
    </style>
</head>
<body>
    <!-- Navbar -->
    <nav class="bg-[#8B4513] text-[#FFDAB9] p-4">
        <div class="max-w-7xl mx-auto px-4">
            <div class="flex items-center">
                <span class="text-2xl font-bold">Africa Géo-Junior</span>
            </div>
        </div>
    </nav>

    <div class="map-container">
        <div id="flag-container">
            <img id="flag-image" width="64" height="64" alt="Country flag">
            <div id="country-name"></div>
        </div>

        <!-- Load SVG from external file -->
        <?php include '../assets/africa.svg'; ?>
    </div>

    <!-- Add Modal -->
    <div id="countryModal" class="modal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <h2 id="modalCountryName" class="text-2xl font-bold text-[#8B4513] mb-4"></h2>
            <div id="modalCountryInfo" class="text-[#3D1810]">
                <p id="modalPopulation" class="mb-2"></p>
                <p id="modalLanguages" class="mb-2"></p>
            </div>
            <button class="show-cities-btn">Show Cities</button>
        </div>
    </div>

    <script>
        // Create array of valid countries from PHP with proper indexing
        const countriesData = <?php echo json_encode($countriesData); ?>;
        console.log('Countries data:', countriesData); // Debug line
        
        const flagContainer = document.getElementById('flag-container');
        const flagImage = document.getElementById('flag-image');
        const countryName = document.getElementById('country-name');

        const modal = document.getElementById('countryModal');
        const closeBtn = document.getElementsByClassName('close')[0];
        const modalCountryName = document.getElementById('modalCountryName');
        const modalPopulation = document.getElementById('modalPopulation');
        const modalLanguages = document.getElementById('modalLanguages');
        let currentCountryId = null;

        document.querySelectorAll('path[data-id]').forEach(path => { 
            path.classList.add('country');
            const pathCountryName = path.getAttribute('data-name');

            // Only add hover effects for countries in our database
            if (countriesData[pathCountryName]) {
                path.addEventListener('mousemove', (e) => {
                    const countryId = e.target.getAttribute('data-id');
                    flagImage.src = `https://flagsapi.com/${countryId}/flat/64.png`;
                    countryName.textContent = e.target.getAttribute('data-name');
                    flagContainer.style.display = 'block';

                    // Position the flag near the cursor
                    let x = e.clientX + 10;
                    let y = e.clientY - flagContainer.offsetHeight - 10;

                    // Keep the flag within the viewport
                    const containerWidth = flagContainer.offsetWidth;
                    const containerHeight = flagContainer.offsetHeight;
                    const viewportWidth = window.innerWidth;
                    const viewportHeight = window.innerHeight;

                    if (x + containerWidth > viewportWidth) {
                        x = e.clientX - containerWidth - 10;
                    }
                    if (y < 0) {
                        y = e.clientY + 10;
                    }

                    flagContainer.style.left = `${x}px`;
                    flagContainer.style.top = `${y}px`;
                });

                path.addEventListener('mouseout', () => {
                    flagContainer.style.display = 'none';
                });

                // Add visual indication that this country is interactive
                path.style.cursor = 'pointer';

                path.addEventListener('click', (e) => {
                    const clickedCountryName = e.target.getAttribute('data-name');
                    const data = countriesData[clickedCountryName];
                    console.log('Clicked country:', clickedCountryName, 'Data:', data); // Debug line
                    
                    if (data) {
                        currentCountryId = data.id; // Make sure this gets set
                        modalCountryName.textContent = clickedCountryName;
                        modalPopulation.textContent = `Population: ${new Intl.NumberFormat().format(data.population)}`;
                        modalLanguages.textContent = `Languages: ${data.langue}`;
                        console.log('Set currentCountryId to:', currentCountryId); // Debug line
                        modal.style.display = 'block';
                    }
                });
            } else {
                // Make non-database countries look inactive
                path.style.fill = '#ddd';
                path.style.cursor = 'default';
            }
        });

        // Close modal when clicking the X
        closeBtn.onclick = () => {
            modal.style.display = 'none';
        };

        // Close modal when clicking outside
        window.onclick = (e) => {
            if (e.target == modal) {
                modal.style.display = 'none';
            }
        };

        // Simplify the show cities button click handler
        document.querySelector('.show-cities-btn').onclick = function() {
            console.log('Button clicked, currentCountryId:', currentCountryId); // Debug line
            if (currentCountryId) {
                window.location.href = 'cities.php?country_id=' + currentCountryId;
            } else {
                console.error('No country ID set!'); // Debug line
            }
        };
    </script>
</body>
</html>
</html>
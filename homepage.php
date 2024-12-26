<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Africa Géo-Junior V2</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        .video-container {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: -1;
            overflow: hidden;
            filter: brightness(0.7);
        }
        .video-container iframe {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 100vw;
            height: 100vh;
            pointer-events: none;
        }
    </style>
</head>
<body class="bg-[#F4A460]">
    <!-- Navbar -->
    <nav class="bg-[#8B4513]/90 fixed w-full z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16">
                <div class="flex items-center">
                    <span class="text-2xl font-bold text-[#FFDAB9]">Africa Géo-Junior</span>
                </div>
                <div class="flex items-center space-x-4">
                    <a href="signup.php" class="bg-[#CD853F] text-[#FFDAB9] px-4 py-2 rounded hover:bg-[#8B4513] transition">S'inscrire</a>
                    <a href="login.php" class="bg-[#DEB887] text-[#3D1810] px-4 py-2 rounded hover:bg-[#CD853F] transition">Se connecter</a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Hero Section with YouTube Video Background -->
    <div class="relative h-screen flex items-center justify-center bg-[#8B4513]/20">
        <div class="video-container">
            <iframe 
                src="https://www.youtube.com/embed/x5OtCngO5A4?autoplay=1&mute=1&loop=1&playlist=x5OtCngO5A4&controls=0" 
                frameborder="0" 
                allowfullscreen>
            </iframe>
        </div>
        <div class="text-center z-10">
            <h1 class="text-5xl font-bold text-[#FFDAB9] mb-6">Découvrez l'Afrique</h1>
            <p class="text-2xl text-[#FFDAB9] mb-8">Une aventure éducative à travers le continent africain</p>
            <a href="login.php" class="bg-[#CD853F] text-[#FFDAB9] px-8 py-4 rounded-lg text-xl hover:bg-[#8B4513] transition shadow-lg">
                Commencer l'aventure
            </a>
        </div>
    </div>

    <!-- Features Section -->
    <div class="bg-[#DEB887] py-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div class="bg-[#FFDAB9] p-6 rounded-lg shadow-md">
                    <h3 class="text-xl font-bold text-[#3D1810] mb-4">Explorez les Pays</h3>
                    <p class="text-[#8B4513]">Découvrez la richesse et la diversité des pays africains à travers une interface interactive.</p>
                </div>
                <div class="bg-[#FFDAB9] p-6 rounded-lg shadow-md">
                    <h3 class="text-xl font-bold text-[#3D1810] mb-4">Apprenez la Géographie</h3>
                    <p class="text-[#8B4513]">Une approche éducative moderne pour maîtriser la géographie africaine.</p>
                </div>
                <div class="bg-[#FFDAB9] p-6 rounded-lg shadow-md">
                    <h3 class="text-xl font-bold text-[#3D1810] mb-4">Statistiques Dynamiques</h3>
                    <p class="text-[#8B4513]">Accédez à des données en temps réel sur les pays et les villes d'Afrique.</p>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
